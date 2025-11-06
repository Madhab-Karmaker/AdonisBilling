@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>All Staffs — {{ Auth::user()->salon?->name ?? 'No Salon Assigned' }}</h2>
        <a href="{{ route(Auth::user()->role . '.staffs.create') }}" class="btn btn-primary">+ Add New Staff</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($staffs->isEmpty())
        <p class="text-muted">No staff members found.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $index => $staff)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $staff->name }}</td>
                            <td>{{ $staff->phone }}</td>
                            <td>{{ $staff->role }}</td>
                            <td>{{ $staff->creator->name ?? 'N/A' }}</td>
                            <td>{{ $staff->created_at->format('d M, Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route(Auth::user()->role . '.staffs.edit', $staff->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                                
                                <form action="{{ route(Auth::user()->role . '.staffs.destroy', $staff->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this staff?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
