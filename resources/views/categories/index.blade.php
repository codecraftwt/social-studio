@extends('layouts.app')

@section('content')


<div class="container mt-4 mb-4">
    <h2>Categories</h2>

    <!-- Bulk Delete Form -->
    <form action="{{ route('categories.bulkDelete') }}" method="POST" class="mt-4" id="bulkDeleteForm">
        @csrf
        <table id="categoriesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td><input type="checkbox" name="categories[]" value="{{ $category->id }}"></td>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <!-- <td>
                            {{ $category->status == 1 ? 'Active' : 'Inactive' }}
                            <button class="btn btn-sm {{ $category->status == 1 ? 'btn-warning' : 'btn-success' }} toggle-status" 
                                    data-id="{{ $category->id }}">
                                {{ $category->status == 1 ? 'Deactivate' : 'Activate' }}
                            </button>
                        </td> -->
                        <td>
                            <label class="switch post-switch">
                                <input type="checkbox" class="toggle-status" 
                                    data-id="{{ $category->id }}" 
                                    {{ $category->status == 1 ? 'checked' : '' }} 
                                    onchange="toggleStatus(this)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-log btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-id="{{ $category->id }}" data-name="{{ $category->name }}">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $category->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" class="btn btn-success" id="bulkActivate">Activate Selected</button>
        <button type="button" class="btn btn-warning" id="bulkDeactivate">Deactivate Selected</button>
        <button type="button" class="btn btn-danger" id="bulkDeleteButton">Delete Selected</button>
    </form>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" method="POST" enctype="multipart/form-data"> <!-- Added enctype -->
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="input-box">
                        <input type="text" name="name" id="edit-name" required>
                        <label>Category Name</label>
                    </div>
                    <div class="input-box">
                        <input type="file" name="category_image" id="edit-category-image" accept="image/*"> <!-- New file input -->
                        <label>Category Image</label>
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
        var table = $('#categoriesTable').DataTable();

        // Handle delete button functionality
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you really want to delete this category?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/categories/${categoryId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                location.reload(); // Reload the page to reflect the changes
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while deleting the category.',
                                    icon: 'error',
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'Close'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred: ' + error.message,
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Close'
                            });
                        });
                    } else {
                        console.log('Deletion canceled');
                    }
                });
            });
        });

        // Populate modal with category data
        var editModal = document.getElementById('editCategoryModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var image = button.getAttribute('data-image');

            var modalForm = document.getElementById('editCategoryForm');
            modalForm.action = '/categories/' + id; // Update form action
            modalForm.querySelector('#edit-name').value = name; // Set the name field value

            if (image) {
                // Display the current image, if available
                var imagePreview = document.createElement('img');
                imagePreview.src = image; // Assuming the image URL is stored in the data-image attribute
                imagePreview.style.width = '100px';
                imagePreview.style.height = 'auto';
                modalForm.querySelector('.modal-body').appendChild(imagePreview);
            }
        });

        // Handle "select all" functionality
        document.getElementById('select-all').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('input[name="categories[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    });

    $(document).ready(function() {
        // $('.toggle-status').click(function(event) {
        //     event.preventDefault(); // Prevent the default action

        //     var categoryId = $(this).data('id');
        //     var button = $(this);
            
        //     $.ajax({
        //         url: '/categories/' + categoryId + '/toggle-status',
        //         method: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}'
        //         },
        //         success: function(response) {
        //             if (response.status == 1) {
        //                 button.removeClass('btn-success').addClass('btn-warning').text('Deactivate');
        //                 button.closest('td').contents().first().replaceWith('Active');
        //             } else {
        //                 button.removeClass('btn-warning').addClass('btn-success').text('Activate');
        //                 button.closest('td').contents().first().replaceWith('Inactive');
        //             }
        //         },
        //         error: function(xhr) {
        //             Swal.fire({
        //                 title: 'Error!',
        //                 text: xhr.responseJSON.message || 'An error occurred while updating the status.',
        //                 icon: 'error',
        //                 confirmButtonColor: '#d33',
        //                 confirmButtonText: 'Close'
        //             });
        //         }
        //     });
        // });

        window.toggleStatus =  function (checkbox) {
            var categoryId = $(checkbox).data('id');
            var status = checkbox.checked ? 1 : 0;

            $.ajax({
                url: '/categories/' + categoryId + '/toggle-status',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Status updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                },
                error: function(xhr) {
                    checkbox.checked = !checkbox.checked; 
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON.message || 'An error occurred while updating the status.',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Close'
                    });
                }
            });
        }


        $('#bulkActivate').click(function() {
            var selectedCategories = $('input[name="categories[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (selectedCategories.length === 0) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please select at least one category to activate.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }

            $.ajax({
                url: '/categories/bulk-toggle-status',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    categories: selectedCategories,
                    status: 1 // Activate
                },
                success: function(response) {
                    location.reload(); // Reload the page to reflect the changes
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON.message || 'An error occurred while activating the categories.',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Bulk deactivate functionality
        $('#bulkDeactivate').click(function() {
            var selectedCategories = $('input[name="categories[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (selectedCategories.length === 0) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please select at least one category to deactivate.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }

            $.ajax({
                url: '/categories/bulk-toggle-status',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    categories: selectedCategories,
                    status: 0 // Deactivate
                },
                success: function(response) {
                    location.reload(); // Reload the page to reflect the changes
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON.message || 'An error occurred while deactivating the categories.',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

});

document.getElementById('bulkDeleteButton').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to delete the selected categories?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if confirmed
                document.getElementById('bulkDeleteForm').submit();
            } else {
                console.log('Deletion canceled');
            }
        });
    });
</script>
@endsection
