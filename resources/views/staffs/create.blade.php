@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Add Staff for {{ Auth::user()->salon?->name ?? 'No Salon Assigned' }}</h2>

    <form action="{{ route(Auth::user()->role . '.staffs.store') }}" method="POST" class="styled-form">
        @csrf

        <div class="mb-3">
            <label class="form-label">Staff Name</label>
            <input type="text" name="name" class="form-control styled-input" placeholder="Enter staff name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control styled-input" placeholder="Enter phone number" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role / Position</label>
            <input type="text" name="role" class="form-control styled-input" placeholder="Enter staff role (e.g., Hair Stylist, Cleaner)" required>
        </div>

        <button type="submit" class="btn-add">Save</button>

        <div class="mt-3">
            <label>Added by: {{ Auth::user()->name }}</label>
        </div>
    </form>
</div>
@endsection
