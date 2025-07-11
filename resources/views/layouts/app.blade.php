<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Boxicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss'])
</head>
<style>

</style>

<body>
    <div id="app" class="d-flex flex-column min-vh-100">
    <div id="loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255, 255, 255, 0.8); z-index: 9999; text-align: center;">
        <div class="spinner" style="margin-top: 20%;">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>
        <!-- Header -->

        @guest
                @include('layouts.header')
        @endguest
        @if(request()->is('plans') || request()->is('plans/create') || request()->is('plans/scannerForm') || request()->is('user-transactions') || request()->is('custom-image') || request()->is('profile/profile'))
            @include('layouts.header')
        @endif
        <div class="d-flex flex-grow-1">
            <!-- Sidebar -->
            @auth
                @if(auth()->user() && !request()->is('plans') && !request()->is('plans/create') && !request()->is('plans/scannerForm') && !request()->is('user-transactions') && !request()->is('custom-image')  && auth()->user()->isAdmin())
                    @include('layouts.sidebar')
                @endif
            @endauth

            <!-- Main Content -->
            <main class="flex-grow-1 p-3">
            @auth
               @if(auth()->user() && !request()->is('plans') && !request()->is('plans/scannerForm') && !request()->is('plans/create') && !request()->is('user-transactions') && !request()->is('custom-image') && (!request()->is('profile/profile') && auth()->user()->isAdmin()))
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button class="btn btn-light me-2" id="toggleSidebardesktop">
                            <i class="bi bi-list"></i>
                        </button>
                        <button class="btn btn-light me-2" id="toggleSidebarmobile" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="bi bi-list"></i>
                        </button>
                        <div class="search-field-area d-none me-2">
                            <div class="search-inner">
                                <form action="#" method="GET">
                                    <div class="input-group">
                                        <input type="text" class="form-control primary-input input-left-icon" placeholder="Search" id="search" onkeyup="showResult(this.value)">
                                        <button class="btn btn-outline-secondary d-none" type="submit">
                                            <i class="ti-search"></i>
                                        </button>
                                    </div>
                                </form>
                            <div id="livesearch" style="display: none;"></div>
                        </div>
                    </div>
                        <!-- <div class="d-flex justify-content-center me-2">
                            <a href="{{ url('/') }}" class="btn btn-primary">Home</a>
                        </div> -->
                        <div class="icons d-flex align-items-center">
                            <!-- <a href="#" class="me-2 ms-2" title="Notifications">
                                <i class="bi bi-bell" style="font-size: 1.5rem;"></i>
                            </a>-->
                            @if(auth()->user() && auth()->user()->isAdmin())
                                <div class="dropdown">
                                    <a href="#" title="Messages" class="ms-2 dropdown-toggle" id="messageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-chat" style="font-size: 2.5rem;"></i>
                                        <span id="new-message-count" class="badge bg-danger position-absolute top-0 start-80 translate-middle rounded-pill" style="margin-top: 10px;"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="messageDropdown" id="message-list">
                                        <li><a class="dropdown-item" href="#">No new messages</a></li>
                                    </ul>
                                </div>
                            @endif
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <!-- <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i> -->
                                    @if (Auth::user()->profile_pic) <!-- Check if the user has a profile picture -->
                                        <img src="{{ Storage::url(Auth::user()->profile_pic) }}" alt="Profile Picture" class="rounded-circle" style="width: 40px; height: 40px;">
                                    @else
                                        <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i></i> <!-- Fallback icon -->
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                    <li>
                                        <span class="dropdown-item">Welcome, {{ Auth::user()->name }}</span>
                                    </li>
                                    <li>
                                        <!-- <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">Profile</a> -->
                                        <a class="dropdown-item" href="{{ route('profile.profile') }}"  data-bs-target="">Profile</a>
                                    </li>
                                    <!-- <li><a class="dropdown-item" href="{{ route('profile.profile') }}">Settings</a></li> -->
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <!-- Main Menu for Posts -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="#" id="postsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-tags"></i> Posts
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="postsDropdown">
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('posts.create') ? 'active' : '' }}" href="{{ route('posts.create') }}">
                                            <i class="bi bi-plus-circle"></i> Add Post
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('posts.index') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                                            <i class="bi bi-eye"></i> View Posts
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- Additional Nav Items -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="bi bi-person"></i> Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('profile.profile') ? 'active' : '' }}" href="{{ route('profile.profile') }}">
                                    <i class="bi bi-gear"></i> Settings
                                </a>
                            </li>
                        </ul>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="editProfileForm" action="{{ route('profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <!-- Display Profile Picture -->
                                        <div class="text-center mb-3">
                                            @if(Auth::user()->profile_pic)
                                                <img src="{{ asset('storage/' . Auth::user()->profile_pic) }}" alt="Profile Picture" class="img-fluid" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                            @else
                                                <img src="{{ asset('default_profile_pic.png') }}" alt="Default Profile Picture" class="img-fluid" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                            @endif
                                        </div>

                                        <div class="input-box">
                                            <span class="icon"><i class='bx bx-user'></i></span>
                                            <input type="text" name="name" value="{{ Auth::user()->name }}" required autocomplete="name" class="@error('name') is-invalid @enderror">
                                            <label>Name</label>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="input-box">
                                            <span class="icon"><i class='bx bx-envelope'></i></span>
                                            <input type="email" name="email" value="{{ Auth::user()->email }}" required autocomplete="email" class="@error('email') is-invalid @enderror">
                                            <label>Email</label>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="input-box">
                                            <span class="icon"><i class='bx bx-pin'></i></span>
                                            <input type="text" name="postal_code" value="{{ Auth::user()->postal_code }}" required autocomplete="postal_code" class="@error('postal_code') is-invalid @enderror">
                                            <label>Postal Code</label>
                                            @error('postal_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="input-box">
                                            <span class="icon"><i class='bx bx-home'></i></span>
                                            <input type="text" name="address" value="{{ Auth::user()->address }}" required autocomplete="address" class="@error('address') is-invalid @enderror">
                                            <label>Address</label>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="input-box">
                                            <span class="icon"><i class='bx bx-home'></i></span>
                                            <input type="text" name="current_location" value="{{ Auth::user()->current_location }}" required autocomplete="current_location" class="@error('current_location') is-invalid @enderror">
                                            <label>Current Location</label>
                                            @error('current_location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="input-box">
                                            <span class="icon"><i class='bx bx-phone'></i></span>
                                            <input type="text" name="mobile" value="{{ Auth::user()->mobile }}" required autocomplete="mobile" class="@error('mobile') is-invalid @enderror">
                                            <label>Mobile</label>
                                            @error('mobile')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="input-box">
                                            <span class="icon"><i class='bx bx-image'></i></span>
                                            <input type="file" name="profile_pic" accept="image/*" class="@error('profile_pic') is-invalid @enderror">
                                            <label>Profile Picture</label>
                                            @error('profile_pic')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-log">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
               @endauth
                <div class="content-div mt-5">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Footer -->
        @if (Request::url() == url('/'))
            @include('layouts.footer')
        @endif
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function fetchNewUserCount() {
            fetch('/api/new-user-count')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('new-message-count').innerText = data.newUserCount;
                })
                .catch(error => console.error('Error fetching new user count:', error));
        }

        fetchNewUserCount();
        setInterval(fetchNewUserCount, 60000); // Refresh every 60 seconds

        document.getElementById('messageDropdown').addEventListener('click', function(e) {
            e.preventDefault();
            
            const messageList = document.getElementById('message-list');
            
            // Clear existing items and show loading
            messageList.innerHTML = '<li><a class="dropdown-item" href="#">Loading...</a></li>';

            // Fetch new users
            fetch('/api/notification') // Ensure the endpoint is correct
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    // Clear loading message
                    messageList.innerHTML = '';

                    if (data.newUsers.length > 0) {
                        if (data.newUsers.length > 3) {
                            const countMessage = `<li><a class="dropdown-item" href="{{ url('user/lists') }}">${data.newUsers.length} new users registered</a></li>`;
                            messageList.innerHTML += countMessage;
                        } else {
                            data.newUsers.forEach(user => {
                                const messageItem = document.createElement('li');
                                messageItem.innerHTML = `<a class="dropdown-item" href="{{ url('user/lists') }}">New user registered: <strong>${user.name}</strong></a>`;
                                messageList.appendChild(messageItem);
                            });
                        }
                    }
                    if(data.unreadTransactionCount > 0){
                        const transactionMessage = `<li><a class="dropdown-item" href="{{ url('transactions') }}">You have ${data.unreadTransactionCount} new subscription(s).</a></li>`;
                        messageList.innerHTML += transactionMessage;
                    }
                    if (data.newUsers.length == 0 && data.unreadTransactionCount == 0) {
                        messageList.innerHTML = '<li><a class="dropdown-item" href="#">0 Notification</a></li>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                    messageList.innerHTML = '<li><a class="dropdown-item" href="#">Error loading messages</a></li>';
                });
        });
    });
</script>

</body>

</html>
