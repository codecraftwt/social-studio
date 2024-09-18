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

        <!-- Check if there are no posts and display a message if true -->
        @if ($posts->isEmpty())
            <div class="alert alert-info" role="alert">
                No records found.
            </div>
        @else
            <!-- Bulk Delete Form -->
            <form action="{{ route('posts.bulkDelete') }}" method="POST">
                @csrf
                <table id="postsTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="form-check-input">
                                <span id="select-all-label" class="btn btn-primary btn-sm" style="cursor: pointer; "><i
                                        class="bi bi-check2-square "></i></span>
                            </th>


                            <th>Title</th>
                            <th>Category</th>
                            <th>Link</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $serialNumber = 1; @endphp
                        @foreach ($posts as $post)
                            <tr>
                                <td><input type="checkbox" name="posts[]" value="{{ $post->id }}"></td>
                                <td>{{ $serialNumber++ }}</td> <!-- Increment serial number here -->

                                <td>{{ $post->post_title }}</td>
                                <td>{{ $post->category->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($post->link)
                                        <a href="{{ $post->link }}" target="_blank">View Link</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if ($post->post_image)
                                        <img src="{{ Storage::url('post_images/' . $post->post_image) }}" alt="Post Image"
                                            style="width:100px;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editPostModal" data-id="{{ $post->id }}"
                                        data-title="{{ $post->post_title }}" data-link="{{ $post->link }}"
                                        data-category="{{ $post->category_id }}"
                                        data-image="{{ $post->post_image }}">Edit</button>

                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                        data-id="{{ $post->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-danger">Delete Selected</button>
            </form>
        @endif
    </div>
    <!-- Edit Post Modal -->
    <div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPostForm" action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit-title" name="post_title">
                        </div>
                        <div class="mb-3">
                            <label for="edit-explanation" class="form-label">Explanation</label>
                            <textarea class="form-control" id="edit-explanation" name="post_explanation"></textarea>
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


    <!-- Include your script here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle modal show event
            // Handle modal show event
            $('#editPostModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id');
                var title = button.data('title');
                var link = button.data('link');
                var categoryId = button.data('category');
                var imageUrl = button.data('image'); // Get the image URL

                var $modalForm = $('#editPostForm');
                $modalForm.attr('action', '/posts/' + id); // Update form action with the post ID

                $modalForm.find('#edit-title').val(title); // Set the title field value
                $modalForm.find('#edit-link').val(link); // Set the link field value
                $modalForm.find('#edit-category').val(categoryId); // Set the category field value

                // Update image preview
                var $imagePreview = $modalForm.find('#edit-image-preview');
                if (imageUrl) {
                    $imagePreview.attr('src', '/storage/post_images/' +
                        imageUrl); // Ensure this path is correct
                    $imagePreview.show(); // Show the image preview
                } else {
                    $imagePreview.hide(); // Hide the image preview
                }

                // Populate category dropdown
                var categories = @json($categories); // Pass categories from the Blade view
                var $categorySelect = $modalForm.find('#edit-category');
                $categorySelect.empty();
                $.each(categories, function(index, category) {
                    var $option = $('<option></option>').val(category.id).text(category.name);
                    if (category.id == categoryId) {
                        $option.prop('selected', true);
                    }
                    $categorySelect.append($option);
                });
            });


            // Handle form submission
            $('#editPostForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                var formData = new FormData(this); // Create a FormData object from the form

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        if (data.success) {
                            // Show the success message
                            $('#successMessage').removeClass('d-none');
                            setTimeout(function() {
                                $('#successMessage').addClass('d-none');
                            }, 5000);

                            // Optionally reload the page after a delay
                            setTimeout(function() {
                                location.reload();
                            }, 2000);

                            // Hide the modal
                            $('#editPostModal').modal('hide');
                        } else {
                            alert('An error occurred while updating the post.');
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });

            // Handle select all checkbox
            $('#select-all').on('click', function() {
                var isChecked = $(this).is(':checked');
                $('input[name="posts[]"]').prop('checked', isChecked);
            });

            // Handle delete buttons
            $('.delete-btn').on('click', function() {
                var postId = $(this).data('id');
                if (confirm('Are you sure you want to delete this post?')) {
                    $.ajax({
                        url: '/posts/' + postId,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('An error occurred while deleting the post.');
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred: ' + xhr.responseText);
                        }
                    });
                }
            });
            // Handle span click to toggle checkbox
            $('#select-all-label').on('click', function() {
                var checkbox = $('#select-all');
                checkbox.prop('checked', !checkbox.prop('checked')); // Toggle the checkbox state
                var isChecked = checkbox.is(':checked');
                $('input[name="posts[]"]').prop('checked', isChecked);
            });
        });
    </script>



@endsection
