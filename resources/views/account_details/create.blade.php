@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="col-md-12 ">
        <div class="card">
            <div class="card-body">
                <!-- Add your content here -->
                ACCOUNT AND PAYMENT DETAILS
            </div>
        </div>
    </div>
    
    <h2 class="mt-4">Manage Account and Payment Methods</h2>
    
    <div class="d-flex mb-4 mt-4">
        <a href="#" id="createAccountBtn" class="me-2 shadow-lg">
            <i class="bx bx-plus-circle"></i>Add Account Details
        </a>
        <a href="#" id="createPaymentBtn" class="shadow-lg">
            <i class="bx bx-plus-circle"></i> Add Scanner Details
        </a>
    </div>

    <!-- Create Account Form -->
    <form id="accountForm" action="{{ route('accounts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <input type="text" name="account_name" id="account_name" required class="@error('account_name') is-invalid @enderror">
                    <label>Account Name</label>
                    @error('account_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <input type="text" name="account_number" id="account_number" required class="@error('account_number') is-invalid @enderror">
                    <label>Account Number</label>
                    @error('account_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <input type="text" name="ifsc_code" id="ifsc_code" required class="@error('ifsc_code') is-invalid @enderror">
                    <label>IFSC Code</label>
                    @error('ifsc_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <input type="text" name="bank_name" id="bank_name" required class="@error('bank_name') is-invalid @enderror">
                    <label>Bank Name</label>
                    @error('bank_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-log">Add Account</button>
    </form>

    <!-- Create Payment Method Form -->
    <form id="paymentForm" action="{{ route('payments.store') }}" method="POST" class="d-none mb-4" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <select class="@error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                        <option value="">Choose a payment method</option>
                        <option value="Google Pay">Google Pay</option>
                        <option value="Phone Pay">Phone Pay</option>
                        <option value="Paytm">Paytm</option>
                        <!-- Add other payment methods as needed -->
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box2">
                    <label for="OR_code" class="form-label">Upload OR Code</label>
                    <input type="file" class="@error('OR_code') is-invalid @enderror" id="OR_code" name="OR_code" accept="image/*" required>
                    @error('OR_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-secondary btn-log">Add Payment Method</button>
    </form>
</div>

<h2 class="mt-4">Account Details</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Account Number</th>
                <th>IFSC Code</th>
                <th>Bank Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accountDetails as $account)
                <tr>
                    <td>{{ $account->account_name }}</td>
                    <td>{{ $account->account_number }}</td>
                    <td>{{ $account->ifsc_code }}</td>
                    <td>{{ $account->bank_name }}</td>
                    <td>
                        <span class="badge {{ $account->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $account->status ? 'Active' : 'Deactivated' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('accounts.toggleStatus', $account->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $account->status ? 'btn-danger' : 'btn-success' }}">
                                {{ $account->status ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="mt-4">Scanner Details</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Payment Method</th>
                <th>OR Code</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($scannerDetails as $scanner)
                <tr>
                    <td>{{ $scanner->payment_method }}</td>
                    <td><img src="{{ asset('storage/' . $scanner->OR_code) }}" alt="OR Code" width="50"></td>
                    <td>
                        <span class="badge {{ $scanner->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $scanner->status ? 'Active' : 'Deactivated' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('payments.toggleStatus', $scanner->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $scanner->status ? 'btn-danger' : 'btn-success' }}">
                                {{ $scanner->status ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.getElementById('createAccountBtn').addEventListener('click', function() {
        document.getElementById('accountForm').classList.remove('d-none');
        document.getElementById('paymentForm').classList.add('d-none');
    });

    document.getElementById('createPaymentBtn').addEventListener('click', function() {
        document.getElementById('paymentForm').classList.remove('d-none');
        document.getElementById('accountForm').classList.add('d-none');
    });
</script>
@endsection
