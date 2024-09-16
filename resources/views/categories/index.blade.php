@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Categories</h2>

    <!-- Display success message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Bulk Delete Form -->
    <form action="{{ route('categories.bulkDelete') }}" method="POST" >
        @csrf
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>#</th>
                    <th>Name</th>
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
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-id="{{ $category->id }}" data-name="{{ $category->name }}">Edit</button>

                            <!-- Delete Form -->
                            <!-- <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form> -->
                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $category->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

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

    // JavaScript to populate the modal with category data
    var editModal = document.getElementById('editCategoryModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');

        var modalForm = document.getElementById('editCategoryForm');
        modalForm.action = '/categories/' + id; // Update form action
        modalForm.querySelector('#edit-name').value = name; // Set the name field value
    });

    // JavaScript to handle "select all" functionality
    document.getElementById('select-all').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('input[name="categories[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endsection
