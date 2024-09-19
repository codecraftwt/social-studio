<aside id="sidebar" class="bg-light col-md-2 p-3">
    <h4>Sidebar</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Dashboard</a>
        </li>
        
        @if(auth()->check() && auth()->user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" href="#" data-bs-toggle="collapse" data-bs-target="#categoriesSubmenu" aria-expanded="{{ request()->routeIs('categories.*') ? 'true' : 'false' }}" aria-controls="categoriesSubmenu">
                    Categories
                    <i class="bi bi-caret-down-fill ms-2"></i>
                </a>
                <ul id="categoriesSubmenu" class="nav flex-column collapse {{ request()->routeIs('categories.*') ? 'show' : '' }}">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.create') ? 'active' : '' }}" href="{{ route('categories.create') }}">Add Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">View Categories</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" href="#" data-bs-toggle="collapse" data-bs-target="#postsSubmenu" aria-expanded="{{ request()->routeIs('posts.*') ? 'true' : 'false' }}" aria-controls="postsSubmenu">
                    Posts
                    <i class="bi bi-caret-down-fill ms-2"></i>
                </a>
                <ul id="postsSubmenu" class="nav flex-column collapse {{ request()->routeIs('posts.*') ? 'show' : '' }}">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}" href="{{ route('posts.create') }}">Add Post</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}" href="{{ route('posts.index') }}">View Posts</a>
                    </li>
                </ul>
            </li>
        @endif
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('header.create')}}">Create Header/Footer</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Settings</a>
        </li>
        <!-- Add more sidebar links here -->
    </ul>
</aside>


<style>
       /* Sidebar styling */
   #sidebar {
    background-color: #f8f9fa;
    border-right: 1px solid #dee2e6;
    height: 100vh;
    overflow-y: auto;
}

/* Main link styling */
.nav-link {
    padding: 10px 15px;
    color: #343a40;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

/* Styling for active link */
.nav-link.active {
    background-color: #e9ecef;
    color: #0d6efd;
    font-weight: bold;
}

/* Styling for sub-menu */
/* Indent sub-menu items */
#categoriesSubmenu, #postsSubmenu {
    padding-left: 1.5rem; /* Indent sub-menu items */
}

/* Styling for sub-menu links */
#categoriesSubmenu .nav-link, #postsSubmenu .nav-link {
    font-size: 0.875rem; /* Smaller font size for sub-menu items */
    color: #495057;
    padding: 8px 15px;
    transition: background-color 0.3s ease;
}

/* Hover effect for sub-menu items */
#categoriesSubmenu .nav-link:hover, #postsSubmenu .nav-link:hover {
    background-color: #e9ecef;
}

/* Down arrow rotation */
.nav-link[aria-expanded="true"] .bi-caret-down-fill {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
}

.nav-link[aria-expanded="true"] .bi-caret-down-fill {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
}

/* Ensure proper rotation when the menu is collapsed */
.nav-link .bi-caret-down-fill {
    transition: transform 0.3s ease;
}

</style>