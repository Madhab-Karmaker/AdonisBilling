<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Service;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with('creator')->get();
        return view('bills.index', compact('bills'));
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

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'service_id' => 'required|array',
            'service_id.*' => 'exists:services,id',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1'
        ]);

        // Create bill
        $bill = Bill::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'] ?? null,
            'total_amount' => 0, // will update later
            'user_id' => auth()->id(),
            'salon_id' => auth()->user()->salon_id,
        ]);

        $total = 0;

        // Loop through services
        foreach ($validated['service_id'] as $key => $serviceId) {
            $service = Service::findOrFail($serviceId);
            $qty = (int) $validated['quantity'][$key];
            $subtotal = $service->price * $qty;

            BillItem::create([
                'bill_id' => $bill->id,
                'service_id' => $serviceId,
                'quantity' => $qty,
                'price' => $service->price, // unit price
                'subtotal' => $subtotal
            ]);

            $total += $subtotal;
        }

        // Update total amount
        $bill->update(['total_amount' => $total]);

        return redirect()->route('manager.bills.index')->with('success', 'Bill created successfully!');
    }

}
