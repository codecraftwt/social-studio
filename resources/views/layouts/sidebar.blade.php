<aside id="sidebar" class="bg-light col-md-2 p-3">
    <button class="btn btn-light mb-3" id="toggleSidebar">
        <i class="bi bi-x-square"></i>
    </button>
    <div class="text-center mb-1">
        <a href="{{ url('/') }}">
            <img src="{{ asset('storage/images/walstar_logo.png') }}" alt="Logo" class="img-fluid logo">
        </a>
    </div>
    <hr class="my-4">
         <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-house me-2"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            @if(auth()->check() && auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link d-flex  {{ request()->routeIs('categories.*') || request()->routeIs('subcategories.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#categoriesSubmenu" role="button" aria-expanded="{{ request()->routeIs('categories.*') || request()->routeIs('subcategories.*') ? 'true' : 'false' }}" aria-controls="categoriesSubmenu">
                        <i class="bi bi-tags me-2"></i>
                        <span class="sidebar-text">Categories</span>
                        <i class="bi bi-caret-down-fill ms-2" style="padding-left: 60px;"></i>
                    </a>
                    <div class="collapse nav flex-column {{ request()->routeIs('categories.*') || request()->routeIs('subcategories.*') ? 'show' : '' }}" id="categoriesSubmenu">
                        <ul class="nav flex-column">
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
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('subcategories.index') ? 'active' : '' }}" href="{{ route('subcategories.index') }}">
                                    <i class="bi bi-eye me-2"></i>
                                    <span class="sidebar-text">View SubCategory</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex  {{ request()->routeIs('posts.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#postsSubmenu" role="button" aria-expanded="false" aria-controls="postsSubmenu">
                        <i class="bi bi-tags me-2"></i>
                        <span class="sidebar-text">Posts</span>
                        <i class="bi bi-caret-down-fill ms-2" style="padding-left: 94px;"></i>
                    </a>
                    <div class="collapse nav flex-column {{ request()->routeIs('posts.*') ? 'show' : '' }}" id="postsSubmenu">
                        <ul class="nav flex-column ">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}" href="{{ route('posts.create') }}">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    <span class="sidebar-text">Add Post</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                                    <i class="bi bi-eye me-2"></i>
                                    <span class="sidebar-text">View Post</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex  {{ request()->routeIs('users.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#userSubMenu" role="button" aria-expanded="false" aria-controls="userSubMenu">
                        <i class="bi bi-person-circle me-2"></i>
                        <span class="sidebar-text">User</span>
                        <i class="bi bi-caret-down-fill ms-2" style="padding-left: 100px;"></i>
                    </a>
                    <div class="collapse nav flex-column {{ request()->routeIs('users.*') ? 'show' : '' }}" id="userSubMenu">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    <span class="sidebar-text">Add User</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="bi bi-eye me-2"></i>
                                    <span class="sidebar-text">View Users</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex {{ request()->routeIs('transactions') ? 'active' : '' }}" href="{{ route('transactions') }}">
                        <i class="bi bi-person me-2"></i>
                        <span class="sidebar-text">Payment List</span>
                    </a>
                </li>
            @endif

        <li class="nav-item">
            <a class="nav-link d-flex {{ request()->routeIs('header.create') ? 'active' : '' }}" href="{{ route('header.create')}}">
                <i class="bi bi-pencil-square me-2"></i>
                <span class="sidebar-text">Create Header/Footer</span>
            </a>
        </li>
        @if(auth()->check() && auth()->user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link d-flex" href="{{ route('payments.create')}}">
                    <i class="bi bi-gear me-2"></i>
                    <span class="sidebar-text">Settings</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link d-flex" href="{{ route('profile.profile') }}">
                    <i class="bi bi-gear me-2"></i>
                    <span class="sidebar-text">Settings</span>
                </a>
            </li>
        @endif
    </ul>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Toggle button for desktop
    const toggleSidebar = document.getElementById('toggleSidebar');
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        });
    }

    // Toggle button for mobile
    const toggleSidebardesktop = document.getElementById('toggleSidebardesktop');
    if (toggleSidebardesktop) {
        toggleSidebardesktop.addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        });
    }
});
// document.getElementById('toggleSidebarmobile').addEventListener('click', function(event) {
//     event.stopPropagation(); // Prevent the click from bubbling up
// });

function isMobileView() {
    return window.innerWidth < 768; // Adjust this value as necessary
}

// Add event listener for the toggle button
document.getElementById('toggleSidebarmobile').addEventListener('click', function(event) {
    if (isMobileView()) {
        event.stopPropagation(); // Prevent the click from bubbling up
    }
});

// Close the dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.dropdown-menu.show');
    if (dropdown && !dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

</script>


