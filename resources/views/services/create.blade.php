@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Add Service for {{ Auth::user()->salon?->name ?? 'No Salon Assigned' }}</h2>

    <form action="{{ route('services.store') }}" method="POST" class="styled-form">
        @csrf

        <div class="mb-3">
            <label class="form-label">Service Name</label>
            <input type="text" name="name" class="form-control styled-input" placeholder="Enter service name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" class="form-control styled-input" step="0.01" placeholder="Enter price" required>
        </div>

        <button type="submit" class="btn-add">Save</button>

        <div class="mt-3">
            <label>Added by: {{ Auth::user()->name }}</label>
        </div>
    </form>
</div>
@endsection
