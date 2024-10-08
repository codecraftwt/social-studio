@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<div class="container">
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4 mb-5">
            <form method="POST" action="{{ route('upload.payment') }}" enctype="multipart/form-data">
                @csrf
                <h1 class="text-center mb-4">Payment Details</h1>

                <div class="input-box">
                    <select id="subscription_type" name="subscription_type" class="@error('subscription_type') is-invalid @enderror" required>
                        <option value="">Select Subscription</option>
                        <option value="three_months" {{ (request()->query('plan') === 'three_months') ? 'selected' : (old('subscription_type') === 'three_months' ? 'selected' : '') }}>Three Months Pack (₹499)</option>
                        <option value="six_months" {{ (request()->query('plan') === 'six_months') ? 'selected' : (old('subscription_type') === 'six_months' ? 'selected' : '') }}>Six Months Pack (₹699)</option>
                        <option value="one_year" {{ (request()->query('plan') === 'one_year') ? 'selected' : (old('subscription_type') === 'one_year' ? 'selected' : '') }}>One Year Pack (₹999)</option>
                    </select>
                    @error('subscription_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-lock'></i></span>
                    <input id="transaction_id" type="text" class="@error('transaction_id') is-invalid @enderror" name="transaction_id" value="{{ old('transaction_id') }}" required>
                    <label for="transaction_id">Transaction ID</label>
                    @error('transaction_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box2">
                    <label for="payment_screenshot">Payment Screenshot</label>
                    <input id="payment_screenshot" type="file" name="payment_screenshot" accept="image/*,.pdf" required class="@error('payment_screenshot') is-invalid @enderror">
                    @error('payment_screenshot')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-calendar'></i></span>
                    <input id="payment_date" type="text" class="flatpickr @error('payment_date') is-invalid @enderror" name="payment_date" value="{{ old('payment_date') }}" required>
                    <label for="payment_date">Payment Date</label>
                    @error('payment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-money'></i></span>
                    <input id="amount" type="number" class="@error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}"  required >
                    <label for="amount">Amount</label>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-log btn-lg">{{ __('Submit Payment Details') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<footer class="footer text-center mt-5 plans-footer">
    <p class="mb-0 text-white">© 2024 Walstar. All Rights Reserved.</p>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#payment_date", {
            dateFormat: "Y-m-d", 
        });


    const subscriptionType = document.getElementById('subscription_type');
        const amountInput = document.getElementById('amount');

        // Function to set the amount based on selected subscription
        function setAmount() {
            let amount = 0;

            switch (subscriptionType.value) {
                case 'three_months':
                    amount = 499;
                    break;
                case 'six_months':
                    amount = 699;
                    break;
                case 'one_year':
                    amount = 999;
                    break;
            }

            amountInput.value = amount;
            amountInput.addEventListener('keydown', function(e) {
                e.preventDefault(); // Prevent key presses
            });
            // amountInput.readOnly = true;
        }

        // Set the initial amount on page load
        setAmount();

        // Update amount when subscription type changes
        subscriptionType.addEventListener('change', setAmount);
    });
</script>
@endsection
