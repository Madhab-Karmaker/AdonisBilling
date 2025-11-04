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

    <form method="POST" action="{{ Auth::user()->role === 'manager' ? route('manager.bills.store') : route('receptionist.bills.store') }}">
        @csrf

        {{-- Customer Info --}}
        <div class="row mb-3 align-items-end">
            <div class="col-md-6">
                <label for="customer_name" class="form-label">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Enter customer name" required>
            </div>
            <div class="col-md-6">
                <label for="customer_phone" class="form-label">Customer Phone</label>
                <input type="tel" name="customer_phone" id="customer_phone" class="form-control" placeholder="Enter phone number" required>
            </div>
        </div>

        {{-- Services --}}
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

        {{-- Add service button + total --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" class="btn btn-secondary" id="add-service">+ Add Service</button>
            <div class="fw-bold fs-5">
                Total: <span id="total-amount">0</span> ৳
            </div>
        </div>

        {{-- Hidden total --}}
        <input type="hidden" name="total_amount" id="total_amount">
       
        {{-- MAIN Payment Method Dropdown --}}
        <div class="row mb-3 align-items-end">

    <!-- Payment Method -->
    <div class="col-md-4">
        <label for="payment_method" class="form-label">Payment Method</label>
        <select name="payment_method" id="payment_method" class="form-select" required>
            <option value="">-- Select Payment Method --</option>
            <option value="cash">Cash</option>
            <option value="bkash">bKash</option>
            <option value="nagad">Nagad</option>
            <option value="card">Card</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="partial">Partial Payment</option>
        </select>
    </div>

    <!-- Bank Name (hidden by default) -->
    <div class="col-md-4" id="bank-transfer-wrapper" style="display:none;">
        <label for="bank_name" class="form-label">Bank Name</label>
        <input type="text" name="bank_name" id="bank_name" list="banks" class="form-control" placeholder="Type bank name">
    </div>

    <!-- Optional: Partial Payment Section -->
    <div class="col-md-4" id="partial-payment-wrapper" style="display:none;">
        <label for="partial_payment_amount" class="form-label">Partial Payment Amount (৳)</label>
        <input type="number" name="partial_payment_amount" id="partial_payment_amount" class="form-control" placeholder="Enter partial amount">
    </div>
</div>

<!-- Bank List -->
<datalist id="banks">
    <option value="Sonali Bank">
    <option value="Janata Bank">
    <option value="Rupali Bank">
    <option value="Agrani Bank">
    <option value="BRAC Bank">
    <option value="Dutch Bangla Bank">
    <option value="City Bank">
    <option value="Eastern Bank">
    <option value="Exim Bank">
    <option value="IFIC Bank">
    <option value="Islami Bank Bangladesh">
    <option value="Jamuna Bank">
    <option value="Mercantile Bank">
    <option value="Mutual Trust Bank">
    <option value="National Bank">
    <option value="NRB Bank">
    <option value="Prime Bank">
    <option value="Shahjalal Islami Bank">
    <option value="Standard Bank">
    <option value="Trust Bank">
    <option value="UCBL">
</datalist>
        {{--  This entire section will appear only if "Partial Payment" is selected --}}
        <div id="partial-payment-section" style="display:none;">
            <h5>Partial Payments</h5>
            <div id="payments-container">
                <div class="row payment-row mb-2">
                    <div class="col-md-4">
                        <select name="payment_method[]" class="form-select payment-method" required>
                            <option value="">-- Select Method --</option>
                            <option value="cash">Cash</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>


                    <div class="col-md-4 bank-field" style="display:none;">
                        <input type="text" name="bank_name[]" class="form-control" placeholder="Bank name">
                    </div>

                    <div class="col-md-3">
                        <input type="number" name="payment_amount[]" class="form-control" placeholder="Enter amount" required>
                    </div>
                    
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-payment">−</button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-payment" class="btn btn-secondary mt-2">+ Add Payment</button>
        </div>



        {{-- Submit --}}
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

    const mainPaymentSelect = document.getElementById("payment_method");
    const partialSection = document.getElementById("partial-payment-section");
    const bankWrapper = document.getElementById("bank-transfer-wrapper");
    const container = document.getElementById("payments-container");
    const addBtn = document.getElementById("add-payment");

    //  Show partial section only if "Partial Payment" is selected
        mainPaymentSelect.addEventListener("change", () => {

                if (mainPaymentSelect.value === "partial") {
                    // Show partial section, hide bank input
                    partialSection.style.display = "block";
                    bankWrapper.style.display = "none";
                } 
                else if (mainPaymentSelect.value === "bank_transfer") {
                    // Hide partial section, show bank input
                    partialSection.style.display = "none";
                    bankWrapper.style.display = "block"; // 
                    bankInput.setAttribute("list", "banks");
                } 
                else {
                    // Hide both when other payment types are selected
                    partialSection.style.display = "none";
                    bankWrapper.style.display = "none";

                    // Optional: reset all partial inputs when hidden
                    container.querySelectorAll("input, select").forEach(el => el.value = "");
                }
        });

        //  Add new payment row
        addBtn.addEventListener("click", () => {
            const first = container.querySelector(".payment-row");
            const clone = first.cloneNode(true);
            clone.querySelectorAll("input, select").forEach(el => el.value = "");
            clone.querySelector(".bank-field").style.display = "none";
            container.appendChild(clone);
        });

        //  Remove a payment row
        container.addEventListener("click", (e) => {
            if (e.target.classList.contains("remove-payment")) {
                if (container.querySelectorAll(".payment-row").length > 1) {
                    e.target.closest(".payment-row").remove();
                }
            }
        });

        //  Toggle bank field visibility when bank_transfer selected
        container.addEventListener("change", (e) => {
            if (e.target.classList.contains("payment-method")) {
                const row = e.target.closest(".payment-row");
                const bankField = row.querySelector(".bank-field");

                if (e.target.value === "bank_transfer") {
                    bankField.style.display = "block";
                    bankField.querySelector("input").setAttribute("required", true);
                } else {
                    bankField.style.display = "none";
                    bankField.querySelector("input").removeAttribute("required");
                    bankField.querySelector("input").value = "";
                }
            }
        });
    });



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
    const container = document.getElementById('services-container');

document.getElementById('add-service').addEventListener('click', () => {
    const firstRow = container.querySelector('.service-row');
    const newRow = firstRow.cloneNode(true);

    // Reset values and remove IDs
    newRow.querySelectorAll('input, select').forEach(el => {
        el.value = '';
        el.removeAttribute('id');
    });

    container.appendChild(newRow);
    updateTotal();
});

// Event delegation inside container only
container.addEventListener('click', e => {
    if (e.target.classList.contains('remove-service')) {
        const rows = container.querySelectorAll('.service-row');
        if (rows.length > 1) {
            e.target.closest('.service-row').remove();
            updateTotal();
        }
    }
});

container.addEventListener('input', e => {
    if (e.target.classList.contains('service-select') || e.target.classList.contains('quantity-input')) {
        const row = e.target.closest('.service-row');
        const serviceSelect = row.querySelector('.service-select');
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(serviceSelect.selectedOptions[0]?.dataset.price) || 0;
        row.querySelector('.price-display').value = (price * qty).toFixed(2);
        updateTotal();
    }
});

function updateTotal() {
    let total = 0;
    container.querySelectorAll('.price-display').forEach(el => {
        total += parseFloat(el.value) || 0;
    });
    document.getElementById('total-amount').innerText = total.toFixed(2);

    const totalInput = document.getElementById('total_amount');
    if (totalInput) totalInput.value = total.toFixed(2);
}

</script>
@endsection
