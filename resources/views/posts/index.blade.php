@extends('layouts.app')

@section('content')
<div class="container">
<div id="successMessage" class="alert alert-success d-none" role="alert">
    Post updated successfully!
</div>
    <h2>Posts</h2>

    <!-- Display success message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Bulk Delete Form -->
    <form action="{{ route('posts.bulkDelete') }}" method="POST">
        @csrf
        <table id="postsTable" class="table table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Link</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td><input type="checkbox" name="posts[]" value="{{ $post->id }}"></td>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->post_title }}</td>
                        <td>{{ $post->category->name ?? 'N/A' }}</td>
                        <td>
                            @if($post->link)
                                <a href="{{ $post->link }}" target="_blank">View Link</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($post->post_image)
                                <img src="{{ Storage::url($post->post_image) }}" alt="Post Image" style="width:100px;">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPostModal" data-id="{{ $post->id }}" data-title="{{ $post->post_title }}" data-link="{{ $post->link }}" data-category="{{ $post->category_id }}">Edit</button>

                            <!-- Delete Button -->
                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $post->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-danger">Delete Selected</button>
    </form>
</div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPostForm" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit-title" name="post_title">
                    </div>
                    <div class="mb-3">
                        <label for="edit-explanation" class="form-label">Explanation</label>
                        <textarea class="form-control" id="edit-explanation" name="post_explanation" rows="3">{{ $post->post_explanation }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-link" class="form-label">Link</label>
                        <input type="text" class="form-control" id="edit-link" name="link">
                    </div>
                    <div class="mb-3">
                        <label for="edit-category" class="form-label">Category</label>
                        <select id="edit-category" name="category_id" class="form-select">
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-post_image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="edit-post_image" name="post_image">
                        <img id="edit-image-preview" src="" alt="Post Image" style="width:100px; display:none;">
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
    var editModal = document.getElementById('editPostModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        var link = button.getAttribute('data-link');
        var categoryId = button.getAttribute('data-category');
        var imageUrl = button.getAttribute('data-image'); // Make sure to pass image URL

        var modalForm = document.getElementById('editPostForm');
        modalForm.action = '/posts/' + id; // Update form action with the post ID

        modalForm.querySelector('#edit-title').value = title; // Set the title field value
        modalForm.querySelector('#edit-link').value = link; // Set the link field value
        modalForm.querySelector('#edit-category').value = categoryId; // Set the category field value

        // Update image preview
        var imagePreview = modalForm.querySelector('#edit-image-preview');
        if (imageUrl) {
            imagePreview.src = `/storage/${imageUrl}`;
            imagePreview.style.display = 'block';
        } else {
            imagePreview.style.display = 'none';
        }

        // Populate category dropdown
        var categorySelect = modalForm.querySelector('#edit-category');
        var categories = @json($categories); // Pass categories from the Blade view
        categorySelect.innerHTML = '';
        categories.forEach(category => {
            var option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            if (category.id == categoryId) {
                option.selected = true;
            }
            categorySelect.appendChild(option);
        });
    });
    
    // document.getElementById('editPostForm').addEventListener('submit', function(event) {
    //     event.preventDefault(); // Prevent default form submission

    //     var formData = new FormData(this); // Create a FormData object from the form

    //     fetch(this.action, {
    //         method: 'POST',
    //         body: formData,
    //         headers: {
    //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //             'Accept': 'application/json'
    //         }
    //     })
    //     .then(response => {
    //         if (!response.ok) {
    //             return response.text().then(text => { throw new Error(text) });
    //         }
    //         return response.json();
    //     })
    //     .then(data => {
    //         if (data.success) {
    //             location.reload(); // Reload the page to reflect the changes
    //         } else {
    //             alert('An error occurred while updating the post.');
    //         }
    //     })
    //     .catch(error => {
    //         alert('An error occurred: ' + error.message);
    //     });
    // });

    document.getElementById('editPostForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var formData = new FormData(this); // Create a FormData object from the form

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show the success message
                var successMessage = document.getElementById('successMessage');
                successMessage.classList.remove('d-none');
                
                // Optionally hide the success message after a few seconds
                setTimeout(() => {
                    successMessage.classList.add('d-none');
                }, 5000); // Hide after 5 seconds

                // Optionally reload the page after a delay
                setTimeout(() => {
                    location.reload();
                }, 7000); 
            } else {
                alert('An error occurred while updating the post.');
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
    });



    document.getElementById('select-all').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('input[name="posts[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this post?')) {
                fetch(`/posts/${postId}`, {
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
                        alert('An error occurred while deleting the post.');
                    }
                })
                .catch(error => {
                    alert('An error occurred: ' + error.message);
                });
            }
        });
    });

    var editModal = document.getElementById('editPostModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        var link = button.getAttribute('data-link');
        var categoryId = button.getAttribute('data-category');

        var modalForm = document.getElementById('editPostForm');
        modalForm.action = '/posts/' + id; // Update form action
        modalForm.querySelector('#edit-title').value = title; // Set the title field value
        modalForm.querySelector('#edit-link').value = link; // Set the link field value
        modalForm.querySelector('#edit-category').value = categoryId; // Set the category field value
    });

    $(document).ready(function() {
        $('#postsTable').DataTable();
    });
</script>
@endsection
