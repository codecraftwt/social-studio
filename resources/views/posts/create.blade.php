@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Post</h2>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf
        <div class="mb-3">
            <label for="link" class="form-label">Link</label>
            <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link') }}">
            @error('link')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="post_image" class="form-label">Post Image</label>
            <input type="file" class="form-control @error('post_image') is-invalid @enderror" id="post_image" name="post_image" accept="image/*" onchange="previewAndResizeImage(event)">
            @error('post_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="post_title" class="form-label">Post Title</label>
            <input type="text" class="form-control @error('post_title') is-invalid @enderror" id="post_title" name="post_title" value="{{ old('post_title') }}" required>
            @error('post_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="post_explanation" class="form-label">Post Explanation</label>
            <textarea class="form-control @error('post_explanation') is-invalid @enderror" id="post_explanation" name="post_explanation" rows="5" required>{{ old('post_explanation') }}</textarea>
            @error('post_explanation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
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
        <button type="submit" class="btn btn-primary">Add Post</button>
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
        const width = 750; // Desired width
        const height = 600; // Desired height

        canvas.width = width;
        canvas.height = height;

        ctx.drawImage(img, 0, 0, width, height);

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
</script>
@endsection
