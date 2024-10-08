{{-- resources/views/User/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container mb-4">
    <h1>User Management</h1>
    <div id="errorContainer"></div>

    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3 btn-log">Add User</a>

    <table class="table" id="userTable">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="selectAll"> <!-- Checkbox to select all -->
                </th>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Role</th>
                <th>Postal Code</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        <input type="checkbox" class="user-checkbox" value="{{ $user->id }}">
                    </td>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile }}</td>
                    <td>{{ $user->role->name }}</td>
                    <td>{{ $user->postal_code }}</td>
                    <!-- <td>
                        {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                        <button class="btn btn-sm {{ $user->status == 1 ? 'btn-warning' : 'btn-success' }} toggle-status" 
                                data-id="{{ $user->id }}">
                            {{ $user->status == 1 ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td> -->
                    <td>
                        <label class="switch post-switch">
                            <input type="checkbox" class="toggle-status" 
                                data-id="{{ $user->id }}" 
                                {{ $user->status == 1 ? 'checked' : '' }} 
                                onchange="toggleStatus(this)">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <button class="btn btn-warning edit-button btn-log" 
                                data-id="{{ $user->id }}" 
                                data-name="{{ $user->name }}" 
                                data-email="{{ $user->email }}" 
                                data-postal-code="{{ $user->postal_code }}" 
                                data-address="{{ $user->address }}" 
                                data-mobile="{{ $user->mobile }}" 
                                data-role="{{ $user->role_id }}"
                                data-current_location="{{ $user->current_location }}" 
                                data-profile-pic="{{ asset('storage/'.$user->profile_pic) }}">
                            Edit
                        </button>
                        <button class="btn btn-warning view-user-button btn-log" 
                                data-id="{{ $user->id }}" 
                                data-name="{{ $user->name }}" 
                                data-email="{{ $user->email }}" 
                                data-postal-code="{{ $user->postal_code }}" 
                                data-address="{{ $user->address }}" 
                                data-mobile="{{ $user->mobile }}" 
                                data-role="{{ $user->role_id }}"
                                data-current_location="{{ $user->current_location }}" 
                                data-profile-pic="{{ asset('storage/'.$user->profile_pic) }}">
                            View
                        </button>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-user-form" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-button">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button id="bulkDeleteBtn" class="btn btn-danger">Delete Selected</button>
    <button type="button" id="bulk-activate" class="btn btn-success">Activate Selected</button>
    <button type="button" id="bulk-deactivate" class="btn btn-warning">Deactivate Selected</button>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="editProfilePic" src="" alt="Profile Picture" class="img-fluid" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class='bx bx-user'></i></span>
                        <input type="text" name="name" id="editName" required class="@error('name') is-invalid @enderror">
                        <label>Name</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-envelope'></i></span>
                        <input type="email" name="email" id="editEmail" required class="@error('email') is-invalid @enderror">
                        <label>Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-pin'></i></span>
                        <input type="text" name="postal_code" id="editPostalCode" required class="@error('postal_code') is-invalid @enderror">
                        <label>Postal Code</label>
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-pin'></i></span>
                        <input type="text" name="current_location" id="editCurrent_location" required class="@error('current_location') is-invalid @enderror">
                        <label>Current Location</label>
                        @error('current_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-home'></i></span>
                        <input type="text" name="address" id="editAddress" required class="@error('address') is-invalid @enderror">
                        <label>Address</label>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-phone'></i></span>
                        <input type="text" name="mobile" id="editMobile" required class="@error('mobile') is-invalid @enderror">
                        <label>Mobile</label>
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-box">
                        <select class="form-select" name="role_id" id="edit-role_id" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-image'></i></span>
                        <input type="file" name="profile_pic" accept="image/*" class="@error('profile_pic') is-invalid @enderror">
                        <label>Profile Picture</label>
                        @error('profile_pic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img id="viewProfilePic" src="" alt="Profile Picture" class="img-fluid" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="input-row">
                    <div class="input-box">
                        <strong>Name:</strong> <span id="viewName"></span>
                    </div>
                    <div class="input-box">
                        <strong>Email:</strong> <span id="viewEmail"></span>
                    </div>
                </div>
                <div class="input-row">
                    <div class="input-box">
                        <strong>Mobile:</strong> <span id="viewMobile"></span>
                    </div>
                    <div class="input-box">
                        <strong>Role:</strong> <span id="viewRole"></span>
                    </div>
                </div>
                <div class="input-row">
                    <div class="input-box">
                        <strong>Postal Code:</strong> <span id="viewPostalCode"></span>
                    </div>
                    <div class="input-box">
                        <strong>Current Location:</strong> <span id="viewCurrentLocation"></span>
                    </div>
                </div>
                <div class="input-row">
                    <div class="input-box">
                        <strong>Address:</strong> <span id="viewAddress"></span>
                    </div>
                    <div class="input-box">
                        <strong>Status:</strong> <span id="viewStatus"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            const form = document.getElementById('editUserForm');
            form.action = `/users/update/${userId}`;
            form.method = "POST"; // Ensure method is set to POST
            
            // Populate fields
            document.getElementById('editName').value = this.getAttribute('data-name');
            document.getElementById('editCurrent_location').value = this.getAttribute('data-current_location');
            document.getElementById('editEmail').value = this.getAttribute('data-email');
            document.getElementById('edit-role_id').value = this.getAttribute('data-role');
            document.getElementById('editPostalCode').value = this.getAttribute('data-postal-code');
            document.getElementById('editAddress').value = this.getAttribute('data-address');
            document.getElementById('editMobile').value = this.getAttribute('data-mobile');

            // Display profile picture
            const profilePic = document.getElementById('editProfilePic');
            profilePic.src = this.getAttribute('data-profile-pic') || "{{ asset('default_profile_pic.png') }}";

            // Show the modal
            const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editUserModal.show();
        });
    });

     const viewButtons = document.querySelectorAll('.view-user-button');
    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Populate the modal fields
            document.getElementById('viewName').innerText = this.getAttribute('data-name');
            document.getElementById('viewEmail').innerText = this.getAttribute('data-email');
            document.getElementById('viewMobile').innerText = this.getAttribute('data-mobile');
            document.getElementById('viewRole').innerText = this.getAttribute('data-role');
            document.getElementById('viewPostalCode').innerText = this.getAttribute('data-postal-code');
            document.getElementById('viewCurrentLocation').innerText = this.getAttribute('data-current_location');
            document.getElementById('viewAddress').innerText = this.getAttribute('data-address');
            document.getElementById('viewStatus').innerText = this.getAttribute('data-status') == 1 ? 'Active' : 'Inactive';

            // Display profile picture
            const profilePic = document.getElementById('viewProfilePic');
            profilePic.src = this.getAttribute('data-profile-pic') || "{{ asset('default_profile_pic.png') }}";

            // Show the modal
            const viewUserModal = new bootstrap.Modal(document.getElementById('viewUserModal'));
            viewUserModal.show();
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Select/Deselect all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Handle bulk delete action
    document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
        const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedUsers.length === 0) {
            Swal.fire({
                title: 'Warning',
                text: 'Please select at least one user to delete.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to delete the selected users?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/users/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Laravel CSRF token
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_ids: selectedUsers }) // Send selected user IDs
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // If deletion is successful, refresh the page or update the UI
                        window.location.reload(); // Refresh the page
                    } else {
                        // Handle the error case if deletion was not successful
                        Swal.fire({
                            title: 'Error!',
                            text: 'Internal Server Error Occurred.',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again later.',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            } else {
                console.log('Deletion canceled');
            }
        });
    });
});

$(document).ready(function() {
    $('.toggle-status').on('click', function() {
        var userId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: '/users/' + userId + '/toggle-status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Toggle button appearance
                if (response.status == 1) {
                    button.removeClass('btn-success').addClass('btn-warning').text('Deactivate');
                    button.closest('td').contents().first().replaceWith('Active');
                } else {
                    button.removeClass('btn-warning').addClass('btn-success').text('Activate');
                    button.closest('td').contents().first().replaceWith('Inactive');
                }
            },
            error: function(xhr) {
                // alert('An error occurred: ' + xhr.responseText);
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
});

$(document).ready(function() {
    $('#selectAll').change(function() {
        $('.user-checkbox').prop('checked', this.checked);
    });

    $('#bulk-activate').click(function() {
        var selectedUsers = $('.user-checkbox:checked').map(function() {
            return this.value;
        }).get();

        if (selectedUsers.length === 0) {
            Swal.fire({
                title: 'Warning',
                text: 'Please select at least one user to delete.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }

        $.ajax({
            url: '{{ route("users.bulkActivate") }}',
            type: 'POST',
            data: {
                user_ids: selectedUsers,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload(); 
                } else {
                    // alert('Failed to activate users.');
                    Swal.fire({
                        title: 'Error!',
                        text: 'Internal Server Error Occured.',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                // alert('An error occurred: ' + xhr.responseJSON.message);
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

    $('#bulk-deactivate').click(function() {
        var selectedUsers = $('.user-checkbox:checked').map(function() {
            return this.value;
        }).get();

        if (selectedUsers.length === 0) {
            Swal.fire({
                title: 'Warning',
                text: 'Please select at least one user to delete.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }

        $.ajax({
            url: '{{ route("users.bulkDeactivate") }}',
            type: 'POST',
            data: {
                user_ids: selectedUsers,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload(); 
                } else {
                    // alert('Failed to deactivate users.');
                    Swal.fire({
                        title: 'Error!',
                        text: 'Internal Server Error Occured.',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                // alert('An error occurred: ' + xhr.responseJSON.message);
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
});

$(document).ready(function() {
    var table = $('#userTable').DataTable({
        responsive: true,
    });
});

document.querySelectorAll('.delete-user-form').forEach(form => {
        form.querySelector('.delete-button').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default form submission
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you really want to delete this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    form.submit();
                }
            });
        });
    });
</script>

@endsection
