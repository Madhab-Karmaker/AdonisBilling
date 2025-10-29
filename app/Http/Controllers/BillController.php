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
        $bills = Bill::with('receptionist')->get();
        return view('bills.index', compact('bills'));
    }
    
    public function create()
    {
        $services = Service::all();
        return view('bills.create', compact('services'));
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'service_id' => 'required|array',
            'quantity' => 'required|array',
            'receptionist_id' => 'required|integer|exists:users,id'
        ]);

        // Create bill (testing mode, salon_id = 1)
        $bill = Bill::create([
            'customer_name' => $validated['customer_name'],
            'total_price' => 0,
            'salon_id' => 1,
            'receptionist_id' => $validated['receptionist_id']
        ]);

        $total = 0;

        // Loop through services
        foreach ($validated['service_id'] as $key => $serviceId) {
            $service = Service::find($serviceId);
            $qty = $validated['quantity'][$key];
            $price = $service->price * $qty;

            BillItem::create([
                'bill_id' => $bill->id,
                'service_id' => $serviceId,
                'quantity' => $qty,
                'price' => $price
            ]);

            $total += $price;
        }

        // Update total
        $bill->update(['total_price' => $total]);

        return redirect()->route('bills.index')->with('success', 'Bill created successfully!');
    }
}
