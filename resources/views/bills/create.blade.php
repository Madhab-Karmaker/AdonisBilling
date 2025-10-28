@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Create Bill</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bills.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" name="customer_name" id="customer_name" class="form-control" required>
        </div>

        <div id="services-container">
            <div class="row mb-3 service-row">
                <div class="col-md-5">
                    <label class="form-label">Service</label>
                    <select name="service_id[]" class="form-select service-select" required>
                        <option value="">-- Select Service --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="1" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Price (৳)</label>
                    <input type="text" class="form-control price-display" readonly>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-service">−</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" id="add-service">+ Add Service</button>

        <div class="mb-3">
            <label class="form-label">Receptionist ID (for testing)</label>
            <input type="number" name="receptionist_id" class="form-control" value="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Bill</button>
    </form>
</div>

<script>
    // Add new service row
    document.getElementById('add-service').addEventListener('click', function() {
        const container = document.getElementById('services-container');
        const firstRow = container.querySelector('.service-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelectorAll('input, select').forEach(el => el.value = '');
        container.appendChild(newRow);
    });

    // Remove service row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-service')) {
            const rows = document.querySelectorAll('.service-row');
            if (rows.length > 1) {
                e.target.closest('.service-row').remove();
            }
        }
    });

    // Auto update price
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('service-select') || e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('.service-row');
            const serviceSelect = row.querySelector('.service-select');
            const qty = row.querySelector('.quantity-input').value;
            const price = serviceSelect.selectedOptions[0]?.dataset.price || 0;
            row.querySelector('.price-display').value = price * qty;
        }
    });
</script>
@endsection
