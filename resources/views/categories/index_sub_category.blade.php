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
                        {{ $subcategory->status == 1 ? 'Active' : 'Inactive' }}
                        <button class="btn btn-sm {{ $subcategory->status == 1 ? 'btn-warning' : 'btn-success' }} toggle-status" 
                                data-id="{{ $subcategory->id }}">
                            {{ $subcategory->status == 1 ? 'Deactivate' : 'Activate' }}
                        </button>
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
                if (confirm('Are you sure you want to delete this subcategory?')) {
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
                            alert('An error occurred while deleting the subcategory.');
                        }
                    })
                    .catch(error => {
                        alert('An error occurred: ' + error.message);
                    });
                }
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

        $('.toggle-status').on('click', function() {
        var subCategoryId = $(this).data('id');
        var button = $(this);

        $.ajax({
            url: '/subcategories/' + subCategoryId + '/toggle-status',
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
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });

    $('#selectAll').on('change', function() {
        $('.sub-category-checkbox').prop('checked', this.checked);
    });

    // Handle bulk toggle status action
    $('#bulk-activate, #bulk-deactivate').click(function() {
        var selectedSubCategories = Array.from(document.querySelectorAll('.sub-category-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedSubCategories.length === 0) {
            alert('Please select at least one sub-category to toggle.');
            return;
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
                    alert('Failed to update sub-categories status.');
                }
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseJSON.message);
            }
        });
    });

    $('#bulk-delete').click(function() {
        var selectedSubCategories = Array.from(document.querySelectorAll('.sub-category-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedSubCategories.length === 0) {
            alert('Please select at least one sub-category to delete.');
            return;
        }

        if (confirm('Are you sure you want to delete the selected sub-categories?')) {
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
                        alert('Failed to delete sub-categories.');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseJSON.message);
                }
            });
        }
    });
});
</script>
@endsection