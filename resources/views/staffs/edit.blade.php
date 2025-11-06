@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Staff — {{ $staff->name }}</h2>

    <form action="{{ route(Auth::user()->role . '.staffs.update', $staff->id) }}" method="POST" class="styled-form">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Staff Name</label>
            <input type="text" name="name" class="form-control styled-input" 
                   value="{{ old('name', $staff->name) }}" placeholder="Enter staff name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control styled-input" 
                   value="{{ old('phone', $staff->phone) }}" placeholder="Enter phone number" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role / Position</label>
            <input type="text" name="role" class="form-control styled-input" 
                   value="{{ old('role', $staff->role) }}" placeholder="Enter staff role (e.g., Hair Stylist, Cleaner)" required>
        </div>

        <button type="submit" class="btn-add">Update Staff</button>

        <div class="mt-3">
            <label>Last updated by: {{ Auth::user()->name }}</label>
        </div>
    </form>
</div>
@endsection
