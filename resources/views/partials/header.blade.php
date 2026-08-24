<header
    class="header border-bottom py-2 px-3 px-xl-4 d-flex align-items-center justify-content-between sticky-top"
    style="min-height: 64px; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 1030;">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-link link-body-emphasis p-0 fs-4 text-decoration-none shadow-none me-1" id="sidebarToggle">
            <i class="fa-solid fa-bars fs-5"></i>
        </button>
        
        <!-- Mobile Logo (Visible on mobile screens) -->
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none d-md-none">
            @if(setting('system_logo'))
                <img src="{{ asset(setting('system_logo')) }}" alt="{{ setting('app_name', 'InnovaCRM') }}" style="max-height: 32px; max-width: 120px; object-fit: contain;">
            @else
                <div class="logo-icon text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 32px; height: 32px; background: linear-gradient(135deg, #6366f1, #a855f7) !important;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7V17L12 22L22 17V7L12 Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M2 7L12 12L22 7" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M12 12V22" stroke="white" stroke-width="2" stroke-linejoin="round" />
                    </svg>
                </div>
                <span class="fw-bold fs-5 text-body-emphasis tracking-tight ms-1" style="letter-spacing: -0.03em;">{{ setting('app_name', 'InnovaCRM') }}</span>
            @endif
        </a>

        <!-- Desktop Search Bar -->
        <div class="input-group d-none d-md-flex align-items-center bg-body-tertiary rounded-3 p-2 border ms-2 cursor-pointer"
            id="headerSearchTrigger"
            data-bs-toggle="modal" data-bs-target="#globalSearchModal"
            style="width: 380px; cursor: pointer;">
            <i class="fa-solid fa-magnifying-glass text-secondary mx-2"></i>
            <input type="text" class="form-control border-0 bg-transparent shadow-none p-0 flex-grow-1 header-search-input"
                style="font-size: 0.875rem; border: none !important; background: transparent !important; box-shadow: none !important; padding: 0 !important; outline: none !important; cursor: pointer;" 
                placeholder="Search contacts, deals, tasks..." readonly>
            <span
                class="badge text-secondary rounded-2 bg-transparent px-1 py-1 d-flex align-items-center justify-content-center"
                style="font-size: 0.6rem; min-width:24px;">⌘ <span class="ps-1"
                    style="font-size: 13px">K</span></span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 gap-sm-3">
        <!-- Mobile Search Button -->
        <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center d-md-none text-secondary shadow-none border" 
                style="width: 36px; height: 36px; background-color: var(--bs-tertiary-bg);" 
                type="button" data-bs-toggle="modal" data-bs-target="#globalSearchModal" aria-label="Search">
            <i class="fa-solid fa-magnifying-glass fs-6"></i>
        </button>

        <!-- Theme Switch (Desktop/Tablet) -->
        <div class="d-none d-sm-flex align-items-center gap-2">
            <div class="bg-primary rounded-pill d-flex align-items-center position-relative cursor-pointer transition-all"
                id="theme-toggle" style="width: 44px; height: 24px; padding: 2px;">
                <div class="d-flex justify-content-between w-100 px-1 text-white opacity-75"
                    style="font-size: 0.7rem; pointer-events: none;">
                    <i class="fa-solid fa-sun"></i>
                    <i class="fa-solid fa-moon"></i>
                </div>
                <!-- Knob -->
                <div class="bg-white rounded-circle position-absolute transition-all shadow-sm d-flex align-items-center justify-content-center"
                    id="theme-knob" style="width: 18px; height: 18px; left: 3px; top: 3px;">
                </div>
            </div>
        </div>

        <!-- Notification Bell -->
        <div class="position-relative cursor-pointer text-secondary hover-primary bg-body-tertiary rounded-circle d-flex align-items-center justify-content-center shadow-none border" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-bell fs-6"></i>
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-white p-0 d-flex align-items-center justify-content-center"
                style="font-size:0.55rem; width:16px;height:16px;">3</span>
        </div>

        <!-- User Dropdown Profile -->
        <div class="dropdown ms-1">
            @php
                $userAvatar = (auth()->user()->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->avatar))
                    ? asset('storage/' . auth()->user()->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366F1&color=fff';
                $uiAvatar = 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366F1&color=fff';
            @endphp
            <a href="#"
                class="dropdown-toggle d-flex align-items-center text-body-emphasis text-decoration-none shadow-none p-0 rounded"
                id="dropdownUserHeader" data-bs-toggle="dropdown" aria-expanded="false" role="button"
                style="outline: none; box-shadow: none;">
                <div class="position-relative">
                    <img src="{{ $userAvatar }}" onerror="this.onerror=null;this.src='{{ $uiAvatar }}';" alt="{{ auth()->user()->name }}" width="36" height="36"
                        class="rounded-circle object-fit-cover shadow-sm border border-2 border-white">
                </div>
                <div class="d-none d-md-flex align-items-center gap-2 text-start ms-2 me-1">
                    <div class="d-flex flex-column lh-1">
                        <strong class="fs-6 fw-bold">{{ auth()->user()->name }}</strong>
                        <span class="text-secondary text-capitalize" style="font-size: 0.7rem;">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</span>
                    </div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 border-0 p-2 mt-2"
                aria-labelledby="dropdownUserHeader" style="min-width: 230px;">
                <div class="d-flex align-items-center gap-3 p-2 mb-2 bg-body-tertiary rounded-3">
                    <img src="{{ $userAvatar }}" onerror="this.onerror=null;this.src='{{ $uiAvatar }}';" alt="{{ auth()->user()->name }}" width="38" height="38"
                        class="rounded-circle object-fit-cover">
                    <div class="d-flex flex-column lh-sm text-truncate">
                        <strong class="fw-bold text-body-emphasis" style="font-size: 0.85rem;">{{ auth()->user()->name }}</strong>
                        <span class="text-secondary text-truncate" style="font-size: 0.725rem;">{{ auth()->user()->email }}</span>
                        <span class="badge bg-primary-subtle text-primary mt-1 align-self-start text-capitalize" style="font-size: 0.6rem;">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</span>
                    </div>
                </div>
                <div class="dropdown-divider my-1 opacity-25"></div>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('profile.index') }}">
                    <i class="fa-regular fa-user text-secondary transition-colors" style="width: 18px;"></i>
                    <span class="fw-medium">My Profile</span>
                </a>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('settings.index') }}">
                    <i class="fa-solid fa-gear text-secondary transition-colors" style="width: 18px;"></i>
                    <span class="fw-medium">Settings</span>
                </a>
                <div class="dropdown-divider my-1 opacity-25"></div>
                
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-danger w-100 text-start bg-transparent border-0">
                        <i class="fa-solid fa-right-from-bracket" style="width: 18px;"></i>
                        <span class="fw-medium">Sign out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Search Bar Collapse -->
<div class="collapse d-md-none bg-body border-bottom p-2 px-3 sticky-top z-2 shadow-sm" id="mobileSearchCollapse">
    <div class="input-group align-items-center bg-body-tertiary rounded-3 px-2 py-1 border cursor-pointer" data-bs-toggle="modal" data-bs-target="#globalSearchModal">
        <i class="fa-solid fa-magnifying-glass text-secondary mx-2"></i>
        <input type="text" class="form-control border-0 bg-transparent shadow-none p-0 flex-grow-1 header-search-input"
            style="font-size: 0.85rem; cursor: pointer;" placeholder="Search contacts, deals, tasks..." readonly>
    </div>
</div>

