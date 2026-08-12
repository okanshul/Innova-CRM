<!-- Mobile Bottom Navigation Bar (< 768px) -->
<nav class="mobile-bottom-nav d-md-none border-top bg-body fixed-bottom z-3 shadow-lg">
    <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
    </a>
    
    @can('contacts.view')
    <a href="{{ route('contacts.index') }}" class="mobile-nav-item {{ request()->routeIs('contacts.*') ? 'active' : '' }}">
        <i class="fa-regular fa-address-book"></i>
        <span>Contacts</span>
    </a>
    @endcan

    @can('deals.view')
    <a href="{{ route('deals.index') }}" class="mobile-nav-item {{ request()->routeIs('deals.*') ? 'active' : '' }}">
        <i class="fa-solid fa-gem"></i>
        <span>Deals</span>
    </a>
    @endcan

    @can('tasks.view')
    <a href="{{ route('tasks.index') }}" class="mobile-nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
        <i class="fa-regular fa-square-check"></i>
        <span>Tasks</span>
    </a>
    @endcan

    <a href="#" class="mobile-nav-item" id="mobileMoreToggle" onclick="event.preventDefault(); document.getElementById('sidebarToggle').click();">
        <i class="fa-solid fa-border-all"></i>
        <span>More</span>
    </a>
</nav>
