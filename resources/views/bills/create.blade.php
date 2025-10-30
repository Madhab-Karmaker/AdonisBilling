@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5 p-4 shadow rounded bg-light">
    <h2 class="mb-4 text-center">Create Bill</h2>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Bill Form --}}
    <form action="{{ route('receptionist.bills.create') }}" method="POST">
        @csrf

        {{-- Customer Info --}}
        <div class="row mb-3 align-items-end">
            <div class="col-md-6">
                <label for="customer_name" class="form-label fw-semibold">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Enter customer name" required>
            </div>
            <div class="col-md-6">
                <label for="customer_phone" class="form-label fw-semibold">Customer Phone</label>
                <input type="tel" name="customer_phone" id="customer_phone" class="form-control" placeholder="Enter phone number" required>
            </div>
        </div>

        {{-- Service Selection --}}
        <div id="services-container">
            <div class="row mb-3 service-row">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Service</label>
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
                    <label class="form-label fw-semibold">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="1" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Price (৳)</label>
                    <input type="text" class="form-control price-display" readonly>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-service">−</button>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" class="btn btn-secondary" id="add-service">+ Add Service</button>
            <div class="fw-bold fs-5">
                Total: <span id="total-amount">0</span> ৳
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary px-5">Save Bill</button>
        </div>
    </form>
</div>

{{-- ===================== --}}
{{--   Phone Input Plugin   --}}
{{-- ===================== --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

<style>
    /* Theme colors */
    .iti__selected-flag {
        background-color: #32bbed !important;
        border-radius: 4px 0 0 4px;
    }
    .iti {
        width: 100%;
    }
    .iti input {
        width: 100%;
        height: 48px; /* match Bootstrap input height */
        border-color: #dee2e6;
        box-shadow: none !important;
    }
    /* Make label spacing consistent */
    .form-label {
        margin-bottom: 6px;
        color: #333;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const phoneInput = document.querySelector("#customer_phone");

        // Initialize intl-tel-input
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: "bd", // Default: Bangladesh 🇧🇩
            preferredCountries: ["bd", "in", "us", "gb", "ae"],
            separateDialCode: true,
        });

        // Store full number with country code when submitting
        const form = phoneInput.closest("form");
        form.addEventListener("submit", function () {
            phoneInput.value = iti.getNumber(); // e.g. +8801712345678
        });
    });
</script>

{{-- ===================== --}}
{{--   Bill Logic Script   --}}
{{-- ===================== --}}
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
                updateTotal();
            }
        }
    });

    // Update price and total dynamically
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('service-select') || e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('.service-row');
            const serviceSelect = row.querySelector('.service-select');
            const qty = row.querySelector('.quantity-input').value;
            const price = serviceSelect.selectedOptions[0]?.dataset.price || 0;
            row.querySelector('.price-display').value = price * qty;
            updateTotal();
        }
    });

    // Calculate total
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.price-display').forEach(el => {
            total += parseFloat(el.value) || 0;
        });
        document.getElementById('total-amount').innerText = total.toFixed(2);
        document.getElementById('total_amount').value = total.toFixed(2);
    }
</script>
@endsection
