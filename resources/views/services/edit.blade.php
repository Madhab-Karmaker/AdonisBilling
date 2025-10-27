@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Service</h2>

    <form action="{{ route('services.update', $service) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Service Name</label>
            <input type="text" name="name" value="{{ $service->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" name="price" value="{{ $service->price }}" class="form-control" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
