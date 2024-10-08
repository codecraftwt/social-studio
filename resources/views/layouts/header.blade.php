<nav class="navbar navbar-expand-md home-page-header custom-header text-white shadow-sm">
    <div class="container">
        <a class="navbar-brand text-white" href="{{ url('/') }}">
            <img src="{{ asset('storage/images/walstar_logo.png') }}" alt="Logo" class="img-fluid" style="height: 50px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> {{ __('Login') }}
                            </a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                    @else
                        <li class="nav-item me-3">
                            <a class="nav-link text-white mt-2" href="{{ url('/') }}">
                                <span>Home</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown me-3">
                            <a class="nav-link dropdown-toggle text-white mt-2" href="#" role="button" 
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Subscription</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ url('/user-transactions') }}">Subscription History Preview</a></li>
                                <li><a class="dropdown-item" href="{{ url('/plans') }}">Purchase New Subscription</a></li>
                            </ul>
                        </li>

                        <!-- <li class="nav-item me-3">
                            <a class="nav-link text-white mt-2" href="">
                                <span>My Settings</span>
                            </a>
                        </li> -->

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" 
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <!-- <i class="bi bi-person-circle" style="font-size: 30px; color: white;"></i> -->
                                @if (Auth::user()->profile_pic) <!-- Check if the user has a profile picture -->
                                    <img src="{{ Storage::url(Auth::user()->profile_pic) }}" alt="Profile Picture" class="rounded-circle" style="width: 40px; height: 40px;">
                                @else
                                    <i class="bi bi-person-circle" style="font-size: 30px; color: white;"></i> <!-- Fallback icon -->
                                @endif
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <span class="dropdown-item">Welcome: {{ Auth::user()->name }}</span>
                                <!-- <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="bi bi-person"></i> User Profile
                                </a> -->
                                <a class="dropdown-item" href="{{ url('/profile/profile') }}">
                                    <i class="bi bi-gear"></i>  View Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
            </ul>
        </div>
    </div>
</nav>
