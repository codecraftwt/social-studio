<nav class="navbar navbar-expand-md home-page-header text-white shadow-sm">
    <div class="container">
        <a class="navbar-brand text-white" href="{{ url('/home') }}">
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
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" 
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="bi bi-person-circle" style="font-size: 30px; color: white;"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <span class="dropdown-item">Welcome: {{ Auth::user()->name }}</span>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person"></i> User Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-gear"></i> Settings
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
