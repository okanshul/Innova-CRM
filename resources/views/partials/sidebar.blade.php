<aside id="sidebar" class="offcanvas-lg offcanvas-start d-flex flex-column p-3">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none sidebar-brand px-2">
        <div class="bg-primary text-white p-2 rounded-3 d-flex align-items-center justify-content-center logo-icon"
            style="width: 32px; height: 32px; min-width: 32px;">
            <i class="fa-solid fa-cube"></i>
        </div>
        <span class="fs-4 fw-bold sidebar-text ms-2">InnovaCRM</span>
    </a>
    <hr class="text-secondary mt-0 opacity-25">
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="sidebar-link active" aria-current="page" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                <i class="fa-solid fa-compass"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Contacts">
                <i class="fa-solid fa-users"></i>
                <span class="sidebar-text">Contacts</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Deals">
                <i class="fa-solid fa-dollar-sign"></i>
                <span class="sidebar-text">Deals</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Pipeline">
                <i class="fa-solid fa-filter"></i>
                <span class="sidebar-text">Pipeline</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Reports">
                <i class="fa-solid fa-chart-column"></i>
                <span class="sidebar-text">Reports</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Tasks">
                <i class="fa-solid fa-square-check"></i>
                <span class="sidebar-text">Tasks</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Calendar">
                <i class="fa-solid fa-calendar-days"></i>
                <span class="sidebar-text">Calendar</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Mail">
                <i class="fa-solid fa-envelope"></i>
                <span class="sidebar-text">Mail</span>
            </a>
        </li>
        <li class="mt-4">
            <a href="#" class="sidebar-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Settings">
                <i class="fa-solid fa-gear"></i>
                <span class="sidebar-text">Settings</span>
            </a>
        </li>
    </ul>

    <div class="mt-auto sidebar-footer">
        <div class="card upgrade-card mb-3">
            <div class="card-body text-center p-3 text-white">
                <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex p-2 mb-2">
                    <i class="fa-solid fa-star text-white"></i>
                </div>
                <h6 class="fw-bold mb-1">Upgrade Plan</h6>
                <p class="small text-white-50 mb-3" style="font-size: 0.75rem;">Unlock more features with Premium Plan.
                </p>
                <button
                    class="btn btn-primary btn-sm w-100 fw-medium bg-white text-primary border-0 rounded-pill py-2">Upgrade
                    Now</button>
            </div>
        </div>

        <div class="dropdown">
            <a href="#"
                class="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-2 py-1 rounded hover-bg sidebar-user"
                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="position-relative d-flex justify-content-center align-items-center avatar-wrapper">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=6366F1&color=fff" alt="mdo"
                        width="32" height="32" class="rounded-circle me-0 avatar">
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"
                        style="transform: translate(20%, 20%);"></span>
                </div>
                <div class="d-flex flex-column lh-sm sidebar-text ms-2">
                    <strong class="fs-6">John Doe</strong>
                    <span class="text-secondary" style="font-size: 0.75rem;">john.doe@innova.com</span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-dark text-small shadow-lg rounded-4 border-0 p-2 mt-2"
                aria-labelledby="dropdownUser1" style="min-width: 220px; background-color: #1a1a30;">
                <div class="d-flex align-items-center gap-3 p-2 mb-2 bg-white bg-opacity-10 rounded-3">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=6366F1&color=fff" alt="mdo"
                        width="36" height="36" class="rounded-circle me-0 avatar">
                    <div class="d-flex flex-column lh-sm text-truncate">
                        <strong class="fw-bold text-white" style="font-size: 0.85rem;">John Doe</strong>
                        <span class="text-white-50 text-truncate" style="font-size: 0.725rem;">john.doe@innova.com</span>
                    </div>
                </div>
                <div class="dropdown-divider my-1 opacity-25"></div>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-white-50" href="#">
                    <i class="fa-regular fa-user text-primary" style="width: 18px;"></i>
                    <span class="text-white">Profile</span>
                </a>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-white-50" href="#">
                    <i class="fa-solid fa-gear text-info" style="width: 18px;"></i>
                    <span class="text-white">Settings</span>
                </a>
                <div class="dropdown-divider my-1 opacity-25"></div>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-danger" href="#">
                    <i class="fa-solid fa-right-from-bracket" style="width: 18px;"></i>
                    <span class="fw-medium">Sign out</span>
                </a>
            </div>
        </div>
    </div>
</aside>

