@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Bills</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Total</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bills as $bill)
            <tr>
                <td>{{ $bill->customer_name }}</td>
                <td>{{ $bill->total_price }} ৳</td>
                <td>{{ $bill->created_at->format('d M Y h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
