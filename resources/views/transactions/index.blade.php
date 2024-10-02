@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4">Transaction Details</h1>

    <form action="{{ route('transactions.bulkApprove') }}" method="POST" id="bulkApproveForm">
        @csrf
        <table class="table table-bordered" id="transactionTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Transaction ID</th>
                    <th>Subscription Type</th>
                    <th>Payment Date</th>
                    <th>Payment Screenshot</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td><input type="checkbox" name="transactions[]" value="{{ $transaction->id }}"></td>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->user_id }}</td>
                        <td>{{ $transaction->transaction_id }}</td>
                        <td>{{ $transaction->subscription_type }}</td>
                        <td>{{ $transaction->payment_date ? $transaction->payment_date->format('Y-m-d') : 'N/A' }}</td>
                        <td>
                            @if ($transaction->payment_screenshot)
                                <a href="{{ Storage::url($transaction->payment_screenshot) }}" target="_blank">View Screenshot</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $transaction->status == 1 ? 'Approved' : 'Pending' }}</td>
                        <td>
                            @if ($transaction->status == 0)
                                <button type="button" class="btn btn-success btn-sm approve-btn" data-id="{{ $transaction->id }}">Approve</button>
                            @else
                                <button type="button" class="btn btn-danger btn-sm reject-btn" data-id="{{ $transaction->id }}">Reject</button>
                            @endif
                            <button type="button" class="btn btn-warning btn-sm delete-btn" data-id="{{ $transaction->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-success mt-3">Approve Selected</button>
    </form>

    <form action="{{ route('transactions.bulkDelete') }}" method="POST" id="bulkDeleteForm" style="margin-top: 20px;">
        @csrf
        <button type="submit" class="btn btn-danger">Delete Selected</button>
    </form>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#transactionTable').DataTable();

    // Select all checkbox functionality
    $('#select-all').on('click', function() {
        $('input[name="transactions[]"]').prop('checked', this.checked);
    });

    // Approve button click
    $('.approve-btn').click(function() {
        const transactionId = $(this).data('id');
        $.ajax({
            url: `/transactions/${transactionId}/approve`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Transaction approved!');
                location.reload();
            },
            error: function(error) {
                alert('An error occurred while approving the transaction.');
            }
        });
    });

    // Reject button click
    $('.reject-btn').click(function() {
        const transactionId = $(this).data('id');
        $.ajax({
            url: `/transactions/${transactionId}/reject`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Transaction rejected!');
                location.reload();
            },
            error: function(error) {
                alert('An error occurred while rejecting the transaction.');
            }
        });
    });

    // Delete button click
    $('.delete-btn').click(function() {
        const transactionId = $(this).data('id');
        if (confirm('Are you sure you want to delete this transaction?')) {
            $.ajax({
                url: `/transactions/${transactionId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Transaction deleted!');
                    location.reload();
                },
                error: function(error) {
                    alert('An error occurred while deleting the transaction.');
                }
            });
        }
    });
});
</script>

@endsection
