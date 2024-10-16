@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Post</h2>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <input type="text" name="link" id="link" required class=" @error('link') is-invalid @enderror">
                    <label>Link</label>
                    @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <input type="text" class=" @error('post_title') is-invalid @enderror" name="post_title" id="post_title" value="{{ old('post_title') }}" required>
                    <label>Post Title</label>
                    @error('post_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <select class=" @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <select class=" @error('subcategory_id') is-invalid @enderror" id="subcategory_id" name="sub_category_id" required>
                        <option value="">Select a subcategory</option>
                        <!-- Subcategories will be populated here via JavaScript -->
                    </select>
                    @error('subcategory_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box2">
                    <label for="post_image" class="form-label">Post Image</label>
                    <input type="file" class=" @error('post_image') is-invalid @enderror" id="post_image" name="post_image" accept="image/*" >
                    @error('post_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box2">
                    <label for="post_pdf" class="form-label">Post PDF</label>
                    <input type="file" class="@error('post_pdf') is-invalid @enderror" id="post_pdf" name="post_pdf" accept=".pdf" onchange="previewPdf(event)">
                    @error('post_pdf')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <select class=" @error('header_footer_access') is-invalid @enderror" id="header_footer_access" name="header_footer_access" required>
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
                <div class="input-box3">
                    <!-- Color picker input -->
                    <input type="color" id="customColor" name="border_color" value="{{ old('border_color', '#ffffff') }}" class="form-lable" required />
                    <label for="border_color" class="form-label">Select border Color</label>
                    @error('border_color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="input-box">
                <textarea class=" @error('post_explanation') is-invalid @enderror" id="post_explanation" name="post_explanation" rows="2" required>{{ old('post_explanation') }}</textarea>
                <label for="post_explanation">Post Explanation</label>
                @error('post_explanation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
                    <div class="input-box3">
                        <input type="color" id="header_color" name="header_color" value="{{ old('header_color', '#ffffff') }}" />
                        <label for="header_color" class="form-label">Header Color</label>
                        @error('header_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-box3">
                        <input type="color" id="text_color" name="text_color" value="{{ old('text_color', '#ffffff') }}" />
                        <label for="text_color" class="form-label">Header Text Color</label>
                        @error('text_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-large btn-log">Add Post</button>
    </form>

    <canvas id="canvas" style="display:none;"></canvas>
</div>

<script>
function previewAndResizeImage(event) {
    const file = event.target.files[0];
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    const img = new Image();
    img.onload = function() {
        const canvasWidth = 750; // Desired width
        const canvasHeight = 600; // Total desired height
        const headerHeight = 80; // Header height
        const footerHeight = 80; // Footer height

        // Calculate the height for the image area
        const imageHeight = canvasHeight - headerHeight - footerHeight;

        canvas.width = canvasWidth;
        canvas.height = canvasHeight;

        // Draw header
        ctx.fillStyle = 'white'; // Header color
        ctx.fillRect(0, 0, canvasWidth, headerHeight);

        // Draw the image
        ctx.drawImage(img, 0, headerHeight, canvasWidth, imageHeight);

        // Draw footer
        ctx.fillStyle = 'white'; // Footer color
        ctx.fillRect(0, canvasHeight - footerHeight, canvasWidth, footerHeight);

        // Convert canvas to Blob
        canvas.toBlob(function(blob) {
            const newFile = new File([blob], file.name, { type: file.type });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);
            document.getElementById('post_image').files = dataTransfer.files;
        }, file.type);
    };
    img.src = URL.createObjectURL(file);
}

document.addEventListener('DOMContentLoaded', function() {
    const subcategories = @json($subcategories);
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');

    categorySelect.addEventListener('change', function() {
        const selectedCategoryId = this.value;

        // Clear the subcategory select
        subcategorySelect.innerHTML = '<option value="">Select a subcategory</option>';

        if (selectedCategoryId && subcategories[selectedCategoryId]) {
            // Populate the subcategory select
            subcategories[selectedCategoryId].forEach(subcategory => {
                const option = document.createElement('option');
                option.value = subcategory.id;
                option.textContent = subcategory.sub_category_name; // Ensure this matches your structure
                subcategorySelect.appendChild(option);
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
</script>
@endsection
