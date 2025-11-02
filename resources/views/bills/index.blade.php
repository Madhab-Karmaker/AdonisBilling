@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5 p-4 shadow rounded bg-light">
    <h2 class="mb-4 text-center" style="color: #32bbed;">All Bills</h2>

    <div class="table-responsive">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Bill Id</th>
                    <th>Customer Name</th>
                    <th>Order Amount (৳)</th>
                    <th>Customer Phone</th>
                    <th>Made By </th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bills as $index => $bill)
                <tr onclick="window.location='{{ route('manager.bills.show', $bill->id) }}'" style="cursor:pointer;">
                    <td>{{ $bill->id }}</td>
                    <td>{{ $bill->customer_name }}</td>
                    <td>{{ $bill->total_amount }}</td>
                    <td>{{ $bill->customer_phone }}</td>
                    <td>{{ $bill->creator->name ?? 'N/A' }}</td>
                    <td>{{ $bill->created_at->format('d M Y h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    .styled-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-width: 600px;
    }

    .styled-table thead tr {
        background-color: #32bbed;
        color: #ffffff;
        text-align: center;
        font-weight: bold;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        text-align: center;
    }

    .styled-table tbody tr {
        background-color: #ffffff;
        transition: all 0.3s ease;
    }

    .styled-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .styled-table tbody tr:hover {
        background-color: rgba(50, 187, 237, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(50, 187, 237, 0.2);
    }

    @media (max-width: 768px) {
        .styled-table th,
        .styled-table td {
            padding: 8px;
            font-size: 14px;
        }
    }
</style>
@endsection
