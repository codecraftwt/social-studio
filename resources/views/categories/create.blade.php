@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="col-md-12 ">
        <div class="card">
            <div class="card-body">
                <!-- Add your content here -->
                CATEGORY!
            </div>
        </div>
    </div>
    <h2 class="mt-4">Manage Categories and Sub-Categories</h2>
    <div class="d-flex mb-4 mt-4">
        <a href="#" id="createCategoryBtn" class=" me-2 shadow-lg" >
            <i class="bx bx-plus-circle"></i> Create Category
        </a>
        <a href="#" id="createSubCategoryBtn" class=" shadow-lg" >
            <i class="bx bx-plus-circle"></i> Create Sub-Category
        </a>
    </div>


    <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class=" row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <input type="text" name="name" id="name" required class=" @error('name') is-invalid @enderror">
                    <label>Category Name</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box2">
                    <label for="category_image" class="form-label">Category Image</label>
                    <input type="file" class=" @error('category_image') is-invalid @enderror" id="category_image" name="category_image" accept="image/*" onchange="previewAndResizeImage(event)">
                    @error('category_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-log">Add Category</button>
    </form>

        <!-- Create Sub-Category Form -->
    <form id="subCategoryForm" action="{{ route('subcategories.store') }}" method="POST" class="d-none mb-4">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <input type="text" name="sub_category_name" id="sub_category_name" required class="@error('sub_category_name') is-invalid @enderror">
                    <label>Sub-Category Name</label>
                    @error('sub_category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <select class=" @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                        <option value="">Choose a category</option>
                        @foreach($categories as $category)
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
        <button type="submit" class="btn btn-secondary btn-log">Add Sub-Category</button>
    </form>
</div>
<script>
    document.getElementById('createCategoryBtn').addEventListener('click', function() {
        document.getElementById('categoryForm').classList.remove('d-none');
        document.getElementById('subCategoryForm').classList.add('d-none');
    });

    document.getElementById('createSubCategoryBtn').addEventListener('click', function() {
        document.getElementById('subCategoryForm').classList.remove('d-none');
        document.getElementById('categoryForm').classList.add('d-none');
    });
</script>
@endsection
