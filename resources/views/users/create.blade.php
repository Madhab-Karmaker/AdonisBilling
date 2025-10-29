@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add User</h2>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="manager">Manager</option>
                <option value="receptionist">Receptionist</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Salon (optional)</label>
            <select name="salon_id" class="form-select">
                <option value="">-- Select Salon --</option>
                @foreach(App\Models\Salon::all() as $salon)
                    <option value="{{ $salon->id }}">{{ $salon->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save User</button>
    </form>
</div>
@endsection
