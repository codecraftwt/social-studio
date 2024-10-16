@extends('layouts.app')

@section('content')
<div class="container my-5 payment-scanner">
    <h3 class="text-center mb-4">Payment Scanner</h3>

    <div class="alert alert-info text-center mb-4">
        कृपया आपली पेमेंट यादी असलेली स्कॅनर वापरा आणि खात्यात जमा करा.
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="scanner">
                <label for="paymentMethod" class="form-label d-none">Select Payment Method:</label>
                <select id="paymentMethod" class="form-select mb-3 d-none">
                    <option value="">Select...</option>
                    <option value="phonepay">PhonePe</option>
                    <option value="googlepay">Google Pay</option>
                    <option value="paytm">Paytm</option>
                </select>

                <div class="scanner-area text-center">
                    @if ($scannerDetail && $scannerDetail->OR_code)
                        <img src="{{ Storage::url($scannerDetail->OR_code) }}" alt="Scanner" class="img-fluid custom-scanner-img">
                    @else
                        <p>No QR code available.</p>
                    @endif
                    <p>Scan the QR code to make payment.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-2">
            <div class="account-details">
                <h5 class="text-center">Account Details for Payment</h5>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Account Name:</strong> {{ $accountDetails->account_name }}</li>
                    <li class="list-group-item"><strong>Account Number:</strong> {{ $accountDetails->account_number }}</li>
                    <li class="list-group-item"><strong>IFSC Code:</strong> {{ $accountDetails->ifsc_code }}</li>
                    <li class="list-group-item"><strong>Bank Name:</strong> {{ $accountDetails->bank_name }}</li>
                </ul>
            </div>
            @if ($accountDetails->account_name === 'N/A')
                <div class="alert alert-warning text-center">
                    No account details available.
                </div>
            @endif
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="/plans/create?plan={{ request()->query('plan') }}" class="upload-button btn btn-primary btn-log">Upload Screenshot & Details</a>
    </div>
</div>

<footer class="footer text-center mt-5 plans-footer">
    <p class="mb-0 text-white">© 2024 Walstar. All Rights Reserved.</p>
</footer>

@endsection
