@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div id="successMessage" class="alert alert-success d-none" role="alert">
        Posts deleted successfully!
    </div>
    <h2>Posts</h2>

    <table id="postsTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Sub-Category</th>
                <th>Link</th>
                <th>Status</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th style="width: 17%;"><input type="text" placeholder="Search Title" class="form-control" /></th>
                <th style="width: 17%;"><input type="text" placeholder="Search Category" class="form-control" /></th>
                <th style="width: 17%;"><input type="text" placeholder="Search Sub-Category" class="form-control" /></th>
                <th style="width: 17%;"><input type="text" placeholder="Search Link" class="form-control" /></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td><input type="checkbox" name="posts[]" class="post-checkbox" value="{{ $post->id }}"></td>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->post_title }}</td>
                    <td>{{ $post->category->name ?? 'N/A' }}</td>
                    <td>{{ $post->sub_category->sub_category_name ?? 'N/A' }}</td>
                    <td>
                        @if($post->link)
                            <a href="{{ $post->link }}" target="_blank">View Link</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <label class="switch post-switch">
                            <input type="checkbox" class="toggle-status" 
                                data-id="{{ $post->id }}" 
                                {{ $post->status == 1 ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        @if($post->post_image)
                            <img src="{{ Storage::url($post->post_image) }}" alt="Post Image" style="width:100px;">
                        @else
                            No Image
                        @endif
                    </td>
                    <td class="flex-buttons">
                        <button type="button" class="btn btn-log btn-sm" data-bs-toggle="modal" data-bs-target="#editPostModal" data-id="{{ $post->id }}" data-post_explanation="{{$post->post_explanation }}" data-title="{{ $post->post_title }}" data-link="{{ $post->link }}" data-category="{{ $post->category_id }}" data-sub_category="{{ $post->sub_category_id}}" data-image="{{ $post->post_image }}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm delete-btn mt-2" data-id="{{ $post->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button id="bulk-delete-btn" class="btn btn-danger">Delete Selected</button>
    <button type="button" class="btn btn-success" id="bulkActivate">Activate Selected</button>
    <button type="button" class="btn btn-warning" id="bulkDeactivate">Deactivate Selected</button>
</div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPostForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-box">
                                <input type="text" class=" @error('post_title') is-invalid @enderror" id="edit-title" name="post_title" required>
                                <label for="edit-title">Title</label>
                                @error('post_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-box">
                                <textarea class=" @error('post_explanation') is-invalid @enderror" id="edit-explanation" name="post_explanation" rows="3" required></textarea>
                                <label for="edit-explanation">Explanation</label>
                                @error('post_explanation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-box">
                                <input type="text" class=" @error('link') is-invalid @enderror" id="edit-link" name="link" required>
                                <label for="edit-link">Link</label>
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-box">
                                <select id="edit-category" name="category_id" class="@error('category_id') is-invalid @enderror" required>
                                    <!-- Options will be populated dynamically -->
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <select id="edit-sub_category" name="sub_category_id" class=" @error('sub_category_id') is-invalid @enderror" required>
                                    <!-- Options will be populated dynamically -->
                                </select>
                                @error('sub_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-box">
                                <select class=" @error('header_footer_access') is-invalid @enderror" id="header_footer_access" name="header_footer_access" >
                                    <option value="">Select header and footer Access Type</option>
                                    <option value="1" {{ old('header_footer_access') == '1' ? 'selected' : '' }}>Header and Footer</option>
                                    <option value="2" {{ old('header_footer_access') == '2' ? 'selected' : '' }}>Only Header</option>
                                    <option value="3" {{ old('header_footer_access') == '3' ? 'selected' : '' }}>Only Footer</option>
                                </select>
                                @error('header_footer_access')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <!-- Color picker input -->
                                <label for="border_color" class="form-label">Select border Color</label>
                                <input type="color" id="customColor" name="border_color" value="{{ old('border_color', '#ffffff') }}" class=""  />

                                @error('border_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="headerFields" style="display:none;">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-box">
                                    <input type="text" class=" @error('header_title') is-invalid @enderror" name="header_title" id="header_title" value="{{ old('header_title') }}">
                                    <label>Header Title</label>
                                    @error('header_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-box">
                                    <label for="header_color" class="form-label">Header Color</label>
                                    <input type="color" id="header_color" name="header_color" value="{{ old('header_color', '#ffffff') }}" />
                                    @error('header_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-box">
                                    <label for="text_color" class="form-label">Header Text Color</label>
                                    <input type="color" id="text_color" name="text_color" value="{{ old('text_color', '#ffffff') }}" />
                                    @error('text_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-box2">
                                <label>Image</label>
                                <input type="file" class=" @error('post_image') is-invalid @enderror" id="edit-post_image" name="post_image" onchange="toggleRequiredFields(this)">
                                <img id="edit-image-preview" src="" alt="Post Image" style="width:100px; display:none;">
                                @error('post_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mt-5">
                        <div class="col-md-12 mt-3">
                            <div class="input-box2">
                                <label>Update PDF File (optional)</label>
                                <input type="file" class="@error('post_pdf') is-invalid @enderror" id="edit-pdf_file" name="post_pdf" accept="application/pdf">
                                @error('post_pdf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
        var table = $('#postsTable').DataTable({
            responsive: true,
        });

        $('#postsTable thead tr:eq(1) th').each(function(i) {
            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table.column(i).search(this.value).draw();
                }
            });
        });

        // Handle modal data population
        var editModal = document.getElementById('editPostModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var title = button.getAttribute('data-title');
            var sub_category = button.getAttribute('data-sub_category');
            var post_explanation = button.getAttribute('data-post_explanation');
            var link = button.getAttribute('data-link');
            var categoryId = button.getAttribute('data-category');
            var imageUrl = button.getAttribute('data-image');

            var modalForm = document.getElementById('editPostForm');
            modalForm.action = '/posts/' + id;

            modalForm.querySelector('#edit-title').value = title;
            modalForm.querySelector('#edit-explanation').value = post_explanation;
            modalForm.querySelector('#edit-link').value = link;
            modalForm.querySelector('#edit-category').value = categoryId;
            modalForm.querySelector('#edit-sub_category').value = sub_category;

            var imagePreview = modalForm.querySelector('#edit-image-preview');
            if (imageUrl) {
                imagePreview.src = `/storage/${imageUrl}`;
                imagePreview.style.display = 'block';
            } else {
                imagePreview.style.display = 'none';
            }

            var categorySelect = modalForm.querySelector('#edit-category');
            var categories = @json($categories);
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

            var sub_categorySelect = modalForm.querySelector('#edit-sub_category'); // Make sure this matches your actual HTML
            var sub_categories = @json($SubCategory);
            sub_categorySelect.innerHTML = '';

            // Filter and populate subcategories based on the selected category
            sub_categories.forEach(sub_category => {
                if (sub_category.category_id == categoryId) { // Check if subcategory matches the selected category
                    var option = document.createElement('option');
                    option.value = sub_category.id;
                    option.textContent = sub_category.sub_category_name;
                    if (sub_category.id == sub_category.category_id) { // Use the correct variable for the selected sub-category ID
                        option.selected = true;
                    }
                    sub_categorySelect.appendChild(option);
                }
            });

            categorySelect.addEventListener('change', function() {
                const selectedCategoryId = this.value;
                 
                // Clear the subcategory select
                sub_categorySelect.innerHTML = '<option value="">Select a subcategory</option>';
                var sub_categories = @json($SubCategory->groupBy('category_id'));
                if (selectedCategoryId && sub_categories[selectedCategoryId]) {
                    sub_categories[selectedCategoryId].forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.sub_category_name;
                        sub_categorySelect.appendChild(option);
                    });
                }
            });

        });

        document.getElementById('bulk-delete-btn').addEventListener('click', function() {
            const selectedPosts = Array.from(document.querySelectorAll('.post-checkbox:checked')).map(checkbox => checkbox.value);

            if (selectedPosts.length === 0) {
                Swal.fire({
                    title: 'Warning',
                    text: 'Please select at least one post to delete.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you really want to delete the selected posts?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('posts.bulkDelete') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ posts: selectedPosts })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            document.getElementById('successMessage').textContent = data.message;
                            document.getElementById('successMessage').classList.remove('d-none');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'An error occurred while deleting posts.',
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'An error occurred: ' + error.message,
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
        document.getElementById('select-all').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('input[name="posts[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        $('.toggle-status').change(function() {
            var postId = $(this).data('id');
            var newStatus = this.checked ? 1 : 0; // Set status based on checkbox state

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to ' + (newStatus == 1 ? 'activate' : 'deactivate') + ' this post.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show a loading indicator
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we update the status.',
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/posts/' + postId + '/toggle-status', // Make sure this route exists
                        method: 'PATCH',
                        data: {
                            status: newStatus,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.close(); // Close the loading indicator

                            if (response.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Status updated successfully!',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Optionally, reload the page or update the UI
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Internal Server Error Occurred.',
                                    icon: 'error',
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function() {
                            Swal.close(); // Close the loading indicator
                            Swal.fire({
                                title: 'Error!',
                                text: 'Internal Server Error Occurred.',
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                } else {
                    // Revert toggle if user cancels
                    $(this).prop('checked', !this.checked);
                }
            });
        });

        $('#bulkActivate').click(function() {
            var selectedposts = $('input[name="posts[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (selectedposts.length === 0) {
                Swal.fire({
                    title: 'Warning',
                    text: 'Please select at least one category to activate.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }

            $.ajax({
                url: '/posts/bulk-toggle-status',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    posts: selectedposts,
                    status: 1 // Activate
                },
                success: function(response) {
                    location.reload(); // Reload the page to reflect the changes
                },
                error: function(xhr) {
                    // alert(xhr.responseJSON.message || 'An error occurred while activating the posts.');
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

        // Bulk deactivate functionality
        $('#bulkDeactivate').click(function() {
            var selectedposts = $('input[name="posts[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (selectedposts.length === 0) {
                Swal.fire({
                    title: 'Warning',
                    text: 'Please select at least one category to activate.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }

            $.ajax({
                url: '/posts/bulk-toggle-status',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    posts: selectedposts,
                    status: 0 // Deactivate
                },
                success: function(response) {
                    location.reload(); // Reload the page to reflect the changes
                },
                error: function(xhr) {
                    // alert(xhr.responseJSON.message || 'An error occurred while deactivating the posts.');
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
    
        $('.delete-btn').on('click', function() {
            const postId = $(this).data('id');
            const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Ensure CSRF token is set in your <head>

            if (confirm('Are you sure you want to delete this post?')) {
                $.ajax({
                    url: `/posts/${postId}`,
                    type: 'DELETE',
                    data: {
                        _token: csrfToken 
                    },
                    success: function(response) {
                        if (response.success) {
                            $(`input[value="${postId}"]`).closest('tr').remove();
                        } else {
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
                        // alert('Error deleting post: ' + xhr.responseJSON.message);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Internal Server Error Occured.',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });

        const headerFooterAccessSelect = document.getElementById('header_footer_access');
        const headerFields = document.getElementById('headerFields');

        headerFooterAccessSelect.addEventListener('change', function() {
            const value = this.value;
            if (value === '1' || value === '2') {
                headerFields.style.display = 'block'; // Show header fields
            } else {
                headerFields.style.display = 'none'; // Hide header fields
            }
        });
    });

    function toggleRequiredFields(input) {
            const headerFooterAccess = document.getElementById('header_footer_access');
            const borderColor = document.getElementById('border_color');
            if (input.files.length > 0) {
                headerFooterAccess.setAttribute('required', 'required');
                borderColor.setAttribute('required', 'required');
            } else {
                headerFooterAccess.removeAttribute('required');
                borderColor.removeAttribute('required');
            }
        }
</script>
@endsection
