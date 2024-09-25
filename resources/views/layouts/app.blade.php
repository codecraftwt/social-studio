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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss'])
</head>
<style>

</style>

<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        <!-- Header -->

        @guest
                @include('layouts.header')
        @endguest

        <div class="d-flex flex-grow-1">
            <!-- Sidebar -->
            @auth
                @if(auth()->user())
                    @include('layouts.sidebar')
                @endif
            @endauth

            <!-- Main Content -->
            <main class="flex-grow-1 p-3">
            @auth
               @if(auth()->user())
               <div class="d-flex justify-content-between align-items-center mb-4">
               <div class="search-field-area ml-40">
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
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/') }}" class="btn btn-primary">Home</a>
                    </div>
                    <div class="icons d-flex align-items-center">
                        <a href="#" class="me-3" title="Notifications">
                            <i class="bi bi-bell" style="font-size: 1.5rem;"></i>
                        </a>
                        <a href="#" title="Messages">
                            <i class="bi bi-chat" style="font-size: 1.5rem;"></i>
                        </a>
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li>
                                    <span class="dropdown-item">Welcome, {{ Auth::user()->name }}</span>
                                </li>
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
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
                @endif
               @endauth
                <div class="content-div mt-5">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Footer -->
        @include('layouts.footer')
    </div>

    <!-- Bootstrap JS Bundle (including Popper) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>
