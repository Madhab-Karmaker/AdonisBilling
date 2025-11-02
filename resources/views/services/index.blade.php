@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: #32bbed;">Services</h2>

        {{-- Only manager can add new service --}}
        @if(auth()->user()->role === 'manager')
            <a href="{{ route('manager.services.create') }}" class="btn btn-add">
                <i class="bi bi-plus-circle me-1"></i> Add New Service
            </a>
        @endif
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Services Table --}}
    <div class="table-container shadow-sm rounded bg-white p-3">
        <div class="table-responsive">
            <table class="styled-table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price </th>
                        @if(auth()->user()->role === 'manager')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $index => $service)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $service->name }}</td>
                            <td>{{ number_format($service->price, 2) }}</td>

                            @if(auth()->user()->role === 'manager')
                                <td>
                                    <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this service?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'manager' ? 4 : 3 }}" class="text-center text-muted py-4">
                                No services found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
