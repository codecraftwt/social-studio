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

            <!-- Check if there are posts and display the table or a message -->
            @if ($posts->isEmpty())
                <div class="alert alert-info">
                    No records found.
                </div>
            @else
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
            @endif
        </form>
    </div>

    <!-- Edit Post Modal -->
    <!-- Modal code remains the same -->

    <!-- Include necessary scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this post?')) {
                    $.ajax({
                        url: '/posts/' + id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data.success) {
                                location.reload(); // Reload the page to reflect changes
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
        });
    </script>
@endsection
