@extends('layouts.app')

@section('content')

    <div class="container-fluid dashboard-view">
        <div class="row">
            <!-- <h2>DASHBOARD</h2> -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Add your content here -->
                       DASHBOARD!
                    </div>
                </div>
            </div>
            <div class="profile-info mt-3">
                <span class="ms-2">Welcome  To - Walstar Poster | {{ Auth::user()->name }}</span> <!-- Display logged user name -->
            </div>
        </div>
        
        <div class="row mt-4 mt-4">
            @if(auth()->check() && auth()->user()->isAdmin())
            <div class="col-md-4 mt-4">
                <a href="{{ url('user/lists') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Users</h5>
                            <p class="card-text">Number of Users: <h3 class="gradient-color2" id="totalPosts">{{ $totalUsers }}</h3></p>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            <div class="col-md-4 mt-4">
                <a href="{{ url('categories') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Categories</h5>
                            <p class="card-text">Number of Categories: <h3 class="gradient-color2" id="totalPosts">{{ $totalCategories }}</h3></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mt-4">
                <a href="{{ url('subcategories') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Sub-Categories</h5>
                            <p class="card-text">Number of Sub-Categories: <h3 class="gradient-color2" id="totalPosts">{{ $SubCategory }}</h3></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mt-4">
                <a href="{{ url('posts') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Posts</h5>
                            <p class="card-text">Number of Posts: <h3 class="gradient-color2" id="totalPosts">{{ $totalPosts }}</h3></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success_message'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success_message') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endsection
