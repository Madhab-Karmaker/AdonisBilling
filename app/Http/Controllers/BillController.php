<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillPayment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with('creator');

        // Search by bill id, customer name, or phone
        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
                $q->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($from = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Payment status filter: paid | partial | unpaid
        if ($status = $request->get('status')) {
            if ($status === 'paid') {
                $query->whereColumn('paid_amount', '>=', 'total_amount');
            } elseif ($status === 'partial') {
                $query->whereColumn('paid_amount', '<', 'total_amount')
                      ->where('paid_amount', '>', 0);
            } elseif ($status === 'unpaid') {
                $query->where(function ($q) {
                    $q->whereNull('paid_amount')->orWhere('paid_amount', 0);
                });
            }
        }

        $bills = $query->orderByDesc('created_at')->get();

        return view('bills.index', [
            'bills' => $bills,
            'filters' => [
                'q' => $request->get('q'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'status' => $request->get('status'),
            ],
        ]);
    }
    
    public function create()
    {
        $services = Service::all();
        return view('bills.create', compact('services'));
    }

    public function show(Bill $bill)
    {
        $bill->load('items.service', 'creator');
        return view('bills.show', compact('bill'));
    }

    public function receipt(Bill $bill)
    {
        $bill->load(['salon', 'items.service', 'items.staffs', 'payments']);
        return view('bills.receipt', compact('bill'));
    }

    public function store(Request $request)
    {
        // Validate bill, items, and optional payments (single or multiple)
        $validated = $request->validate([
            // Bill fields
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',

            // Bill items (services)
            'service_id' => 'required|array|min:1',
            'service_id.*' => 'required|integer|exists:services,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',

            // Optional single (main) or legacy fields from Blade
            'payment_method' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'partial_payment_amount' => 'nullable|numeric|min:0',
            'transaction_id' => 'nullable',
            'paid_at' => 'nullable',

            // Optional multiple partial payments (array inputs)
            // Example Blade names: payment_amount[], payment_method[], bank_name[], transaction_id[], paid_at[]
            'payment_amount' => 'sometimes|array',
            'payment_amount.*' => 'nullable|numeric|min:0.01',
            'payment_method.*' => 'nullable|string|max:50',
            'bank_name.*' => 'nullable|string|max:100',
            'transaction_id.*' => 'nullable|string|max:100',
            'paid_at.*' => 'nullable|date',
        ]);

        // Ensure services and quantities align
        if (count($validated['service_id']) !== count($validated['quantity'])) {
            
            return back()->withErrors(['quantity' => 'Quantity count must match services count.'])->withInput();
        }

        // Use a DB transaction to keep bill, items, and payments consistent
        $bill = DB::transaction(function () use ($validated) {
            // Create the bill (total_amount and paid_amount will be updated)
            $bill = Bill::create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'total_amount' => 0,
                'paid_amount' => 0,
                'is_partial' => false,
                'user_id' => auth()->id(),
                'salon_id' => optional(auth()->user())->salon_id,
            ]);

            $totalAmount = 0;

            // Create BillItems from selected services
            foreach ($validated['service_id'] as $index => $serviceId) {
                $service = Service::findOrFail($serviceId);
                $quantity = (int) $validated['quantity'][$index];
                $lineTotal = $service->price * $quantity;

                BillItem::create([
                    'bill_id' => $bill->id,
                    'service_id' => $serviceId,
                    'quantity' => $quantity,
                    'price' => $service->price, // unit price captured at time of billing
                ]);

                $totalAmount += $lineTotal;
            }

            // Update total amount on the bill
            $bill->update(['total_amount' => $totalAmount]);

            $totalPaid = 0.0;

            // Handle multiple partial payments if provided as arrays
            if (!empty($validated['payment_amount']) && is_array($validated['payment_amount'])) {
                $count = count($validated['payment_amount']);
                // Align parallel arrays by index
                for ($i = 0; $i < $count; $i++) {
                    $amount = (float) ($validated['payment_amount'][$i] ?? 0);
                    if ($amount <= 0) {
                        continue; // skip non-positive entries
                    }

                    $method = is_array($validated['payment_method'] ?? null) ? ($validated['payment_method'][$i] ?? null) : null;
                    if (empty($method)) {
                        // Skip entries without a method to avoid validation noise
                        continue;
                    }

                    BillPayment::create([
                        'bill_id' => $bill->id,
                        'amount' => $amount,
                        'payment_method' => $method,
                        'bank_name' => is_array($validated['bank_name'] ?? null) ? ($validated['bank_name'][$i] ?? null) : null,
                        'transaction_id' => is_array($validated['transaction_id'] ?? null) ? ($validated['transaction_id'][$i] ?? null) : null,
                        'paid_at' => (is_array($validated['paid_at'] ?? null) && isset($validated['paid_at'][$i])) ? $validated['paid_at'][$i] : now(),
                    ]);

                    $totalPaid += $amount;
                }
            }
            else {
                // Determine legacy single-payment behavior based on Blade's payment_method select
                $selectedMethod = $validated['payment_method'] ?? null;

                if ($selectedMethod === 'partial') {
                    // If a single partial amount is provided, create one partial payment
                    $partialAmount = (float) ($validated['partial_payment_amount'] ?? 0);
                    if ($partialAmount > 0) {
                        BillPayment::create([
                            'bill_id' => $bill->id,
                            'amount' => $partialAmount,
                            'payment_method' => 'partial',
                            'bank_name' => $validated['bank_name'] ?? null,
                            'transaction_id' => $validated['transaction_id'] ?? null,
                            'paid_at' => $validated['paid_at'] ?? now(),
                        ]);
                        $totalPaid += $partialAmount;
                    }
                } elseif (!empty($selectedMethod)) {
                    // Treat as full payment of the computed total
                    BillPayment::create([
                        'bill_id' => $bill->id,
                        'amount' => (float) $totalAmount,
                        'payment_method' => $selectedMethod,
                        'bank_name' => $validated['bank_name'] ?? null,
                        'transaction_id' => $validated['transaction_id'] ?? null,
                        'paid_at' => $validated['paid_at'] ?? now(),
                    ]);
                    $totalPaid += (float) $totalAmount;
                }
            }

            // Update paid_amount and partial flag based on totalPaid
            $bill->update([
                'paid_amount' => $totalPaid,
                // is_partial is true if the bill is not fully paid yet
                'is_partial' => $totalPaid < $totalAmount,
            ]);

            return $bill;
        });

        return redirect()->route('manager.bills.index')
            ->with('success', 'Bill created successfully!');
    }

}
