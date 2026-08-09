<!-- Mobile Bottom Navigation Bar (< 768px) -->
<nav class="mobile-bottom-nav d-md-none border-top bg-body fixed-bottom z-3 shadow-lg">
    <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('staff.index') }}" class="mobile-nav-item {{ request()->routeIs('staff.*') ? 'active' : '' }}">
        <i class="fa-solid fa-user-group"></i>
        <span>Contacts</span>
    </a>
    <a href="#" class="mobile-nav-item">
        <i class="fa-solid fa-sack-dollar"></i>
        <span>Deals</span>
    </a>
    <a href="#" class="mobile-nav-item">
        <i class="fa-solid fa-square-check"></i>
        <span>Tasks</span>
    </a>
    <a href="#" class="mobile-nav-item" id="mobileMoreToggle" onclick="event.preventDefault(); document.getElementById('sidebarToggle').click();">
        <i class="fa-solid fa-border-all"></i>
        <span>More</span>
    </a>
</nav>
