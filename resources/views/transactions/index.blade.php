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
                    <th>Expiry Date</th>
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
                        <td>{{ $transaction->plan_expiry_date }}</td>
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
        <button type="button" class="btn btn-warning mt-3" id="bulkDeactivateButton">Deactivate Selected</button>
        <button type="button" class="btn btn-danger mt-3" id="bulkDeleteButton">Delete Selected</button>
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
                // alert('Transaction approved!');
                // location.reload();
                Swal.fire({
                    title: 'Success',
                    text: 'Transaction approved!',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(error) {
                // alert('An error occurred while approving the transaction.');
                Swal.fire({
                    title: 'Error!',
                    text: 'Internal Server Error Occured.',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
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
                Swal.fire({
                    title: 'Error',
                    text: 'Transaction rejected!',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(error) {
                // alert('An error occurred while rejecting the transaction.');
                Swal.fire({
                    title: 'Error!',
                    text: 'Internal Server Error Occured.',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Delete button click
    $('.delete-btn').click(function() {
        const transactionId = $(this).data('id');
        // Use SweetAlert for confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to delete this transaction?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/transactions/${transactionId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Transaction deleted!',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload the page to reflect changes
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the transaction: ' + (xhr.responseJSON.message || 'Please try again.'),
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                console.log('Deletion canceled');
            }
        });
    });

    $('#bulkDeleteButton').click(function() {
        const selectedTransactions = $('input[name="transactions[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        console.log(selectedTransactions);

        if (selectedTransactions.length === 0) {
            Swal.fire({
                title: 'No Selection!',
                text: 'Please select at least one transaction to delete.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to delete the selected transactions?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to delete the selected transactions
                $.ajax({
                    url: $('#bulkDeleteForm').attr('action'), // Use the form's action URL
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Add CSRF token for security
                        transactions: selectedTransactions // Send the selected transaction IDs
                    },
                    success: function(response) {
                        // Handle the success response
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Selected transactions have been deleted.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload the page to reflect changes
                        });
                    },
                    error: function(xhr) {
                        // Handle the error response
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'An error occurred while deleting transactions.',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    $('#bulkDeactivateButton').click(function() {
        const selectedTransactions = $('input[name="transactions[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        console.log(selectedTransactions);

        if (selectedTransactions.length === 0) {
            Swal.fire({
                title: 'No Selection!',
                text: 'Please select at least one transaction to deactivate.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to deactivate the selected transactions?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, deactivate them!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("transactions.bulkDeactivate") }}', // Use the new route
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Add CSRF token for security
                        transactions: selectedTransactions // Send the selected transaction IDs
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deactivated!',
                            text: 'Selected transactions have been deactivated.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload the page to reflect changes
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'An error occurred while deactivating transactions.',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

});
</script>

@endsection
