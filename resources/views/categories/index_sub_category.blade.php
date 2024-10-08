@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2>Subcategories</h2>

    <!-- Subcategories Table -->
    <table id="subcategoriesTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="selectAll"> <!-- Checkbox to select all -->
                </th>
                <th>#</th>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subcategories as $subcategory)
                <tr>
                    <td>
                        <input type="checkbox" class="sub-category-checkbox" value="{{ $subcategory->id }}">
                    </td>
                    <td>{{ $subcategory->id }}</td>
                    <td>{{ $subcategory->sub_category_name }}</td>
                    <td>{{ $subcategory->category->name }}</td>
                    <td>
                        <label class="switch post-switch">
                            <input type="checkbox" class="toggle-status" 
                                data-id="{{ $subcategory->id }}" 
                                {{ $subcategory->status == 1 ? 'checked' : '' }} 
                                onchange="toggleStatus(this)">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-log btn-sm" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal" data-id="{{ $subcategory->id }}" data-name="{{ $subcategory->sub_category_name }}" data-category-id="{{ $subcategory->category_id }}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm delete-subcategory-btn" data-id="{{ $subcategory->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button id="bulk-activate" class="btn btn-success mb-3" data-status="1">Activate Selected</button>
    <button id="bulk-deactivate" class="btn btn-warning mb-3" data-status="0">Deactivate Selected</button>
    <button id="bulk-delete" class="btn btn-danger mb-3">Delete Selected</button>
</div>

<!-- Edit Subcategory Modal -->
<div class="modal fade" id="editSubcategoryModal" tabindex="-1" aria-labelledby="editSubcategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubcategoryModalLabel">Edit Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSubcategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="input-box">
                        <input type="text" name="sub_category_name" id="edit-sub-name" required>
                        <label>Subcategory Name</label>
                    </div>
                    <div class="mb-3">
                        <div class="input-box">
                            <select id="edit-category" name="category_id" class=" @error('category_id') is-invalid @enderror" required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') === $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-log">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
        // Initialize DataTable
        $('#subcategoriesTable').DataTable();

        // Handle delete button functionality
        document.querySelectorAll('.delete-subcategory-btn').forEach(button => {
            button.addEventListener('click', function() {
                const subcategoryId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you really want to delete this subcategory?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/subcategories/${subcategoryId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload(); // Reload the page to reflect the changes
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while deleting the subcategory.',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred: ' + error.message,
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        });
                    } else {
                        console.log('Deletion canceled');
                    }
                });
            });
        });

        // Populate modal with subcategory data
        var editSubModal = document.getElementById('editSubcategoryModal');
        editSubModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var categoryId = button.getAttribute('data-category-id');

            var modalForm = document.getElementById('editSubcategoryForm');
            modalForm.action = '/subcategories/' + id; // Update form action
            modalForm.querySelector('#edit-sub-name').value = name; // Set the name field value
            modalForm.querySelector('#edit-category').value = categoryId; // Set the category field value
        });

        // $('.toggle-status').on('click', function() {
        //     var subCategoryId = $(this).data('id');
        //     var button = $(this);

        //     $.ajax({
        //         url: '/subcategories/' + subCategoryId + '/toggle-status',
        //         type: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}'
        //         },
        //         success: function(response) {
        //             // Toggle button appearance
        //             if (response.status == 1) {
        //                 button.removeClass('btn-success').addClass('btn-warning').text('Deactivate');
        //                 button.closest('td').contents().first().replaceWith('Active');
        //             } else {
        //                 button.removeClass('btn-warning').addClass('btn-success').text('Activate');
        //                 button.closest('td').contents().first().replaceWith('Inactive');
        //             }
        //         },
        //         // error: function(xhr) {
        //         //     .catch(xhr => {
        //         //         Swal.fire({
        //         //             title: 'Error!',
        //         //             text: 'An error occurred: ' + xhr.responseText,
        //         //             icon: 'error',
        //         //             confirmButtonColor: '#3085d6',
        //         //             confirmButtonText: 'OK'
        //         //         });
        //         //     });
        //         // }
        //     });
        // });

        window.toggleStatus = function(checkbox) {
            var subCategoryId = $(checkbox).data('id');
            var status = checkbox.checked ? 1 : 0;

            $.ajax({
                url: '/subcategories/' + subCategoryId + '/toggle-status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    // Handle response if needed
                    Swal.fire({
                        title: 'Success!',
                        text: 'Status updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                },
                error: function(xhr) {
                    // If there's an error, revert the checkbox state
                    checkbox.checked = !checkbox.checked; // Revert the checkbox state
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update status.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        };


    $('#selectAll').on('change', function() {
        $('.sub-category-checkbox').prop('checked', this.checked);
    });

    // Handle bulk toggle status action
    $('#bulk-activate, #bulk-deactivate').click(function() {
        var selectedSubCategories = Array.from(document.querySelectorAll('.sub-category-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedSubCategories.length === 0) {
            Swal.fire({
                title: 'Warning!',
                text: 'Please select at least one sub-category to toggle.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }

        var status = $(this).data('status');

        $.ajax({
            url: '{{ route("subcategories.bulkToggleStatus") }}',
            type: 'POST',
            data: {
                sub_category_ids: selectedSubCategories,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload(); // Reload the page to reflect changes
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update sub-categories status.',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
                // error: function(xhr) {
                //     Swal.fire({
                //         title: 'Error!',
                //         text: xhr.responseJSON.message || 'An error occurred. Please try again.',
                //         icon: 'error',
                //         confirmButtonColor: '#d33',
                //         confirmButtonText: 'OK'
                //     });
                }
        });
    });

    $('#bulk-delete').click(function() {
        var selectedSubCategories = Array.from(document.querySelectorAll('.sub-category-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedSubCategories.length === 0) {
            Swal.fire({
                title: 'Warning!',
                text: 'Please select at least one sub-category to delete.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Use SweetAlert for confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to delete the selected sub-categories?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("subcategories.bulkDelete") }}',
                    type: 'POST',
                    data: {
                        sub_category_ids: selectedSubCategories,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete sub-categories.',
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred: ' + (xhr.responseJSON.message || 'Please try again later.'),
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Close'
                        });
                    }
                });
            } else {
                console.log('Deletion canceled');
            }
        });

    });
});
</script>
@endsection
