@extends('layouts.app')

@section('content')


<div class="container mt-4 mb-4">
    <h2>Categories</h2>

    <!-- Bulk Delete Form -->
    <form action="{{ route('categories.bulkDelete') }}" method="POST" class="mt-4">
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
                        <td>
                            {{ $category->status == 1 ? 'Active' : 'Inactive' }}
                            <button class="btn btn-sm {{ $category->status == 1 ? 'btn-warning' : 'btn-success' }} toggle-status" 
                                    data-id="{{ $category->id }}">
                                {{ $category->status == 1 ? 'Deactivate' : 'Activate' }}
                            </button>
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
        <button type="submit" class="btn btn-danger">Delete Selected</button>
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
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="input-box">
                        <input type="text" name="name" id="edit-name" required >
                        <label>Category Name</label>
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
                if (confirm('Are you sure you want to delete this category?')) {
                    fetch(`/categories/${categoryId}`, {
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
                            alert('An error occurred while deleting the category.');
                        }
                    })
                    .catch(error => {
                        alert('An error occurred: ' + error.message);
                    });
                }
            });
        });

        // Populate modal with category data
        var editModal = document.getElementById('editCategoryModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');

            var modalForm = document.getElementById('editCategoryForm');
            modalForm.action = '/categories/' + id; // Update form action
            modalForm.querySelector('#edit-name').value = name; // Set the name field value
        });

        // Handle "select all" functionality
        document.getElementById('select-all').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('input[name="categories[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    });

    $(document).ready(function() {
    $('.toggle-status').click(function(event) {
        event.preventDefault(); // Prevent the default action

        var categoryId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: '/categories/' + categoryId + '/toggle-status',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status == 1) {
                    button.removeClass('btn-success').addClass('btn-warning').text('Deactivate');
                    button.closest('td').contents().first().replaceWith('Active');
                } else {
                    button.removeClass('btn-warning').addClass('btn-success').text('Activate');
                    button.closest('td').contents().first().replaceWith('Inactive');
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message || 'An error occurred while updating the status.');
            }
        });
    });

    $('#bulkActivate').click(function() {
            var selectedCategories = $('input[name="categories[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (selectedCategories.length === 0) {
                alert('Please select at least one category to activate.');
                return;
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
                    alert(xhr.responseJSON.message || 'An error occurred while activating the categories.');
                }
            });
        });

        // Bulk deactivate functionality
        $('#bulkDeactivate').click(function() {
            var selectedCategories = $('input[name="categories[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (selectedCategories.length === 0) {
                alert('Please select at least one category to deactivate.');
                return;
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
                    alert(xhr.responseJSON.message || 'An error occurred while deactivating the categories.');
                }
            });
        });

});
</script>
@endsection
