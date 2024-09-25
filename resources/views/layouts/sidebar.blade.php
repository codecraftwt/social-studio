<aside id="sidebar" class="bg-light col-md-2 p-3">
    <button class="btn btn-light mb-0" id="toggleSidebar">
        <i class="bi bi-list"></i>
    </button>
    <div class="text-center mb-1">
        <img src="{{ asset('storage/images/walstar_logo.png') }}" alt="Logo" class="img-fluid" >
    </div>
    <h4 class="sidebar-title">Sidebar</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                <i class="bi bi-house me-2"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        
        @if(auth()->check() && auth()->user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#categoriesSubmenu" aria-expanded="{{ request()->routeIs('categories.*') ? 'true' : 'false' }}" aria-controls="categoriesSubmenu">
                    <i class="bi bi-tags"></i>
                    <span class="sidebar-text">Categories</span>
                    <i class="bi bi-caret-down-fill ms-2"></i>
                </a>
                <ul id="categoriesSubmenu" class="nav flex-column collapse {{ request()->routeIs('categories.*') ? 'show' : '' }}">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.create') ? 'active' : '' }}" href="{{ route('categories.create') }}">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span class="sidebar-text">Add Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                            <i class="bi bi-eye me-2"></i>
                            <span class="sidebar-text">View Categories</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#postsSubmenu" aria-expanded="{{ request()->routeIs('posts.*') ? 'true' : 'false' }}" aria-controls="postsSubmenu">
                    <i class="bi bi-file-earmark-text"></i>
                    <span class="sidebar-text">Posts</span>
                    <i class="bi bi-caret-down-fill ms-2"></i>
                </a>
                <ul id="postsSubmenu" class="nav flex-column collapse {{ request()->routeIs('posts.*') ? 'show' : '' }}">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}" href="{{ route('posts.create') }}">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span class="sidebar-text">Add Post</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                            <i class="bi bi-eye me-2"></i>
                            <span class="sidebar-text">View Posts</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link d-flex {{ request()->routeIs('header.create') ? 'active' : '' }}" href="{{ route('header.create')}}">
                <i class="bi bi-pencil-square me-2"></i>
                <span class="sidebar-text">Create Header/Footer</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex" href="#">
                <i class="bi bi-person me-2"></i>
                <span class="sidebar-text">Profile</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex" href="#">
                <i class="bi bi-gear me-2"></i>
                <span class="sidebar-text">Settings</span>
            </a>
        </li>
    </ul>
</aside>
<script>
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
    });
</script>
