<header
    class="header border-bottom py-2 px-3 px-xl-4 d-flex align-items-center justify-content-between sticky-top z-3"
    style="min-height: 64px; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-link link-body-emphasis p-0 fs-4 text-decoration-none shadow-none" id="sidebarToggle">
            <i class="fa-solid fa-bars fs-6"></i>
        </button>
        <div class="input-group d-none d-md-flex align-items-center bg-body-tertiary rounded-3 px-2 py-1 border ms-2"
            style="width: 400px;">
            <i class="fa-solid fa-magnifying-glass text-secondary mx-2"></i>
            <input type="text" class="form-control border-0 bg-transparent shadow-none p-0 flex-grow-1"
                style="font-size: 0.875rem; border: none !important; background: transparent !important; box-shadow: none !important; padding: 0 !important; outline: none !important;" placeholder="Search contacts, deals, tasks...">
            <span
                class="badge text-secondary rounded-2 bg-transparent px-1 py-1 d-flex align-items-center justify-content-center"
                style="font-size: 0.6rem; min-width:24px;">⌘ <span class="ps-1"
                    style="font-size: 13px">K</span></span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Theme Switch -->
        <div class="d-flex align-items-center gap-2 me-2">
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

        <!-- New Button -->
        <button class="btn btn-primary btn-sm px-3 rounded-3 d-flex align-items-center gap-2 fw-medium shadow-sm me-1" style="font-size: 0.8rem;">
            <i class="fa-solid fa-plus fs-xs"></i> New
        </button>

        <div class="position-relative cursor-pointer text-secondary hover-primary ms-1">
            <i class="fa-solid fa-bell fs-5"></i>
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-white p-0 d-flex align-items-center justify-content-center"
                style="font-size:0.55rem; width:16px;height:16px;">3</span>
        </div>

        <div class="cursor-pointer text-secondary hover-primary ms-1">
            <i class="fa-solid fa-envelope fs-5"></i>
        </div>

        <div class="dropdown ms-2">
            <a href="#"
                class="d-flex align-items-center text-body-emphasis text-decoration-none shadow-none px-1 py-1 rounded"
                id="dropdownUserHeader" data-bs-toggle="dropdown" aria-expanded="false"
                style="outline: none; box-shadow: none;">
                <div class="position-relative me-2">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=6366F1&color=fff' }}" alt="{{ auth()->user()->name }}" width="36" height="36"
                        class="rounded-circle object-fit-cover shadow-sm">
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"
                        style="width:9px; height:9px;"></span>
                </div>
                <div class="d-none d-md-flex align-items-center gap-2 text-start me-1">
                    <div class="d-flex flex-column lh-1">
                        <strong class="fs-6 fw-bold">{{ auth()->user()->name }}</strong>
                        <span class="text-secondary text-capitalize" style="font-size: 0.7rem;">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-down ms-1 fs-xs text-secondary opacity-75"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 border-0 p-2 mt-2"
                aria-labelledby="dropdownUserHeader" style="min-width: 230px;">
                <div class="d-flex align-items-center gap-3 p-2 mb-2 bg-body-tertiary rounded-3">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=6366F1&color=fff' }}" alt="{{ auth()->user()->name }}" width="38" height="38"
                        class="rounded-circle object-fit-cover">
                    <div class="d-flex flex-column lh-sm text-truncate">
                        <strong class="fw-bold text-body-emphasis" style="font-size: 0.85rem;">{{ auth()->user()->name }}</strong>
                        <span class="text-secondary text-truncate" style="font-size: 0.725rem;">{{ auth()->user()->email }}</span>
                        <span class="badge bg-primary-subtle text-primary mt-1 align-self-start text-capitalize" style="font-size: 0.6rem;">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</span>
                    </div>
                </div>
                <div class="dropdown-divider my-1 opacity-25"></div>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="#">
                    <i class="fa-regular fa-user text-secondary transition-colors" style="width: 18px;"></i>
                    <span class="fw-medium">My Profile</span>
                </a>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="#">
                    <i class="fa-solid fa-sliders text-secondary transition-colors" style="width: 18px;"></i>
                    <span class="fw-medium">Account Settings</span>
                </a>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="#">
                    <i class="fa-solid fa-shield-halved text-secondary transition-colors" style="width: 18px;"></i>
                    <span class="fw-medium">Security</span>
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
