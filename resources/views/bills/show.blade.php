{{-- resources/views/bills/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Bill Details</h2>

    {{-- Bill Info --}}
    <div class="card mb-3 p-3">
        <p><strong>Bill ID:</strong> {{ $bill->id }}</p>
        <p><strong>Customer Name:</strong> {{ $bill->customer_name }}</p>
        <p><strong>Customer Phone:</strong> {{ $bill->customer_phone }}</p>
        <p><strong>Created by:</strong> {{ $bill->creator->name }} ({{ $bill->creator->role }})</p>
        <p><strong>Created at:</strong> {{ $bill->created_at->format('d M Y, H:i') }}</p>
    </div>

    {{-- Bill Items --}}
    <h4>Items</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Service</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bill->items as $item)
            <tr>
                <td>{{ $item->service->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Bill Total --}}
    <div class="mt-3">
        <p><strong>Total Amount:</strong> {{ number_format($bill->items->sum(fn($i) => $i->quantity * $i->price), 2) }}</p>
    </div>
</div>
@endsection
