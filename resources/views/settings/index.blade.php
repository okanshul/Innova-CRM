@extends('layouts.app', ['title' => 'InnovaCRM - System Settings'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Settings']]" />

    <!-- Top Banner Card -->
    <div class="settings-header-banner mb-3">
        <div class="d-flex align-items-center gap-3">
            <div class="settings-header-icon">
                <i class="fa-solid fa-gear"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-body-emphasis">System Settings</h4>
                <p class="text-secondary small mb-0">Configure application preferences and system settings.</p>
            </div>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="settingsAlert"></div>

    <form id="settingsForm" action="{{ route('crm.api.settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3.5">
            <!-- Left Vertical Sidebar Nav -->
            <div class="col-lg-3 col-xl-2">
                <div class="settings-vnav h-100">
                    <div class="nav flex-column nav-pills me-0" id="settings-tab" role="tablist"
                        aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general"
                            aria-selected="true">
                            <i class="fa-solid fa-sliders"></i> General Settings
                        </button>
                        <button class="nav-link" id="v-pills-company-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-company" type="button" role="tab" aria-controls="v-pills-company"
                            aria-selected="false">
                            <i class="fa-regular fa-building"></i> Company Profile
                        </button>
                        <button class="nav-link" id="v-pills-localization-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-localization" type="button" role="tab"
                            aria-controls="v-pills-localization" aria-selected="false">
                            <i class="fa-solid fa-globe"></i> Localization
                        </button>
                        <button class="nav-link" id="v-pills-email-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-email" type="button" role="tab" aria-controls="v-pills-email"
                            aria-selected="false">
                            <i class="fa-regular fa-envelope"></i> Email Settings
                        </button>
                        <button class="nav-link" id="v-pills-notifications-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-notifications" type="button" role="tab"
                            aria-controls="v-pills-notifications" aria-selected="false">
                            <i class="fa-regular fa-bell"></i> Notifications
                        </button>
                        <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-security" type="button" role="tab"
                            aria-controls="v-pills-security" aria-selected="false">
                            <i class="fa-solid fa-shield-halved"></i> Security
                        </button>
                        <button class="nav-link" id="v-pills-users-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-users" type="button" role="tab" aria-controls="v-pills-users"
                            aria-selected="false">
                            <i class="fa-solid fa-user-gear"></i> Users & Permissions
                        </button>
                        <button class="nav-link" id="v-pills-preferences-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-preferences" type="button" role="tab"
                            aria-controls="v-pills-preferences" aria-selected="false">
                            <i class="fa-solid fa-bars-staggered"></i> CRM Preferences
                        </button>
                        <button class="nav-link" id="v-pills-integrations-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-integrations" type="button" role="tab"
                            aria-controls="v-pills-integrations" aria-selected="false">
                            <i class="fa-solid fa-puzzle-piece"></i> Integrations
                        </button>
                        <button class="nav-link" id="v-pills-backup-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-backup" type="button" role="tab"
                            aria-controls="v-pills-backup" aria-selected="false">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Backup & Restore
                        </button>
                        <button class="nav-link" id="v-pills-system-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-system" type="button" role="tab"
                            aria-controls="v-pills-system" aria-selected="false">
                            <i class="fa-solid fa-desktop"></i> System Info
                        </button>
                        <button class="nav-link" id="v-pills-audit-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-audit" type="button" role="tab" aria-controls="v-pills-audit"
                            aria-selected="false">
                            <i class="fa-solid fa-clipboard-list"></i> Audit Log
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Settings Content Tabs -->
            <div class="col-lg-9 col-xl-10">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- Tab 1: General Settings Pane -->
                    <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel"
                        aria-labelledby="v-pills-general-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 d-flex align-items-center justify-content-between border-bottom">
                                <h5 class="fw-bold mb-0 text-body-emphasis">General Settings</h5>
                                <x-button.primary type="submit" icon="fa-solid fa-check" label="Save All Settings" />
                            </div>
                            <div class="card-body p-3">

                        <!-- 4x4 Grid of 16 Cards -->
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
                            <!-- Card 1: Company Name -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-regular fa-building"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Company Name</h6>
                                                <p class="settings-card-desc">Your company or organization name.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <input type="text" class="form-control" name="company_name"
                                            value="{{ $settings['company_name'] ?? 'InnovaCRM Inc.' }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: System Email -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-regular fa-envelope"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">System Email</h6>
                                                <p class="settings-card-desc">Default email address for system
                                                    notifications.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <input type="email" class="form-control" name="system_email"
                                            value="{{ $settings['system_email'] ?? 'admin@innovacrm.com' }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Default Currency -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-green">
                                                <i class="fa-solid fa-dollar-sign"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Default Currency</h6>
                                                <p class="settings-card-desc">Select the default currency used in the
                                                    application.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <select class="form-select" name="currency_symbol">
                                            <option value="USD"
                                                {{ ($settings['currency_symbol'] ?? 'USD') === 'USD' ? 'selected' : '' }}>
                                                USD ($)</option>
                                            <option value="EUR"
                                                {{ ($settings['currency_symbol'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR
                                                (€)</option>
                                            <option value="GBP"
                                                {{ ($settings['currency_symbol'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP
                                                (£)</option>
                                            <option value="INR"
                                                {{ ($settings['currency_symbol'] ?? '') === 'INR' ? 'selected' : '' }}>INR
                                                (₹)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 4: Date Format -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-regular fa-calendar-days"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Date Format</h6>
                                                <p class="settings-card-desc">Choose the default date format.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <select class="form-select" name="date_format">
                                            <option value="MMM D, YYYY"
                                                {{ ($settings['date_format'] ?? 'MMM D, YYYY') === 'MMM D, YYYY' ? 'selected' : '' }}>
                                                Nov 5, 2026 (MMM D, YYYY)</option>
                                            <option value="YYYY-MM-DD"
                                                {{ ($settings['date_format'] ?? '') === 'YYYY-MM-DD' ? 'selected' : '' }}>
                                                2026-11-05 (YYYY-MM-DD)</option>
                                            <option value="DD/MM/YYYY"
                                                {{ ($settings['date_format'] ?? '') === 'DD/MM/YYYY' ? 'selected' : '' }}>
                                                05/11/2026 (DD/MM/YYYY)</option>
                                            <option value="MM/DD/YYYY"
                                                {{ ($settings['date_format'] ?? '') === 'MM/DD/YYYY' ? 'selected' : '' }}>
                                                11/05/2026 (MM/DD/YYYY)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 5: Time Zone -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-regular fa-clock"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Time Zone</h6>
                                                <p class="settings-card-desc">Select the default time zone.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <select class="form-select" name="timezone">
                                            <option value="Asia/Kolkata"
                                                {{ ($settings['timezone'] ?? 'Asia/Kolkata') === 'Asia/Kolkata' ? 'selected' : '' }}>
                                                (UTC+05:30) Asia/Kolkata</option>
                                            <option value="UTC"
                                                {{ ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' }}>(UTC+00:00)
                                                UTC / London</option>
                                            <option value="America/New_York"
                                                {{ ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' }}>
                                                (UTC-05:00) America/New_York</option>
                                            <option value="Europe/Paris"
                                                {{ ($settings['timezone'] ?? '') === 'Europe/Paris' ? 'selected' : '' }}>
                                                (UTC+01:00) Europe/Paris</option>
                                            <option value="Asia/Singapore"
                                                {{ ($settings['timezone'] ?? '') === 'Asia/Singapore' ? 'selected' : '' }}>
                                                (UTC+08:00) Asia/Singapore</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 6: Time Format -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-orange">
                                                <i class="fa-regular fa-clock"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Time Format</h6>
                                                <p class="settings-card-desc">Choose the default time format.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <select class="form-select" name="time_format">
                                            <option value="12"
                                                {{ ($settings['time_format'] ?? '12') === '12' ? 'selected' : '' }}>12
                                                Hours (02:30 PM)</option>
                                            <option value="24"
                                                {{ ($settings['time_format'] ?? '') === '24' ? 'selected' : '' }}>24 Hours
                                                (14:30)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 7: Items Per Page -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-solid fa-list-ul"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Items Per Page</h6>
                                                <p class="settings-card-desc">Set default number of records per page.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <select class="form-select" name="items_per_page">
                                            <option value="10"
                                                {{ ($settings['items_per_page'] ?? '10') == '10' ? 'selected' : '' }}>10
                                                per page</option>
                                            <option value="25"
                                                {{ ($settings['items_per_page'] ?? '') == '25' ? 'selected' : '' }}>25 per
                                                page</option>
                                            <option value="50"
                                                {{ ($settings['items_per_page'] ?? '') == '50' ? 'selected' : '' }}>50 per
                                                page</option>
                                            <option value="100"
                                                {{ ($settings['items_per_page'] ?? '') == '100' ? 'selected' : '' }}>100
                                                per page</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 8: Language -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-solid fa-globe"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Language</h6>
                                                <p class="settings-card-desc">Select the default application language.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <select class="form-select" name="language">
                                            <option value="en"
                                                {{ ($settings['language'] ?? 'en') === 'en' ? 'selected' : '' }}>English
                                            </option>
                                            <option value="es"
                                                {{ ($settings['language'] ?? '') === 'es' ? 'selected' : '' }}>Spanish
                                            </option>
                                            <option value="fr"
                                                {{ ($settings['language'] ?? '') === 'fr' ? 'selected' : '' }}>French
                                            </option>
                                            <option value="de"
                                                {{ ($settings['language'] ?? '') === 'de' ? 'selected' : '' }}>German
                                            </option>
                                            <option value="hi"
                                                {{ ($settings['language'] ?? '') === 'hi' ? 'selected' : '' }}>Hindi
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 9: System Logo -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-regular fa-image"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">System Logo</h6>
                                                <p class="settings-card-desc">Upload your company logo.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <div class="logo-preview-box" id="logoPreviewBox">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center text-white fw-bold px-2 py-1"
                                                    style="font-size: 1.1rem; width: 32px; height: 32px;">
                                                    <i class="fa-solid fa-layer-group"></i>
                                                </div>
                                                <span class="fw-bold fs-5 text-body-emphasis">InnovaCRM</span>
                                            </div>
                                        </div>
                                        <input type="file" id="systemLogoInput" name="system_logo_file"
                                            class="d-none" accept="image/*">
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-soft-primary flex-fill"
                                                onclick="document.getElementById('systemLogoInput').click()">
                                                <i class="fa-solid fa-rotate me-1"></i> Change
                                            </button>
                                            <button type="button" class="btn btn-soft-danger flex-fill">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 10: Favicon -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-yellow">
                                                <i class="fa-regular fa-star"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Favicon</h6>
                                                <p class="settings-card-desc">Upload favicon for browser tab.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <div class="logo-preview-box" id="faviconPreviewBox">
                                            <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center text-white fw-bold"
                                                style="width: 32px; height: 32px;">
                                                <i class="fa-solid fa-layer-group fs-6"></i>
                                            </div>
                                        </div>
                                        <input type="file" id="faviconInput" name="favicon_file" class="d-none"
                                            accept="image/*">
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-soft-primary flex-fill"
                                                onclick="document.getElementById('faviconInput').click()">
                                                <i class="fa-solid fa-rotate me-1"></i> Change
                                            </button>
                                            <button type="button" class="btn btn-soft-danger flex-fill">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 11: Maintenance Mode -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-solid fa-wrench"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Maintenance Mode</h6>
                                                <p class="settings-card-desc">Put the application in maintenance mode.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <div class="form-check form-switch mb-0">
                                            <input type="hidden" name="maintenance_mode" value="0">
                                            <input class="form-check-input switch-green" type="checkbox"
                                                name="maintenance_mode" id="maintenanceModeSwitch" value="1"
                                                {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                                        </div>
                                        <div class="settings-callout callout-blue">
                                            <i class="fa-solid fa-circle-info"></i>
                                            <span>When enabled, only admins can access the application.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 12: Enable Recaptcha -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-green">
                                                <i class="fa-solid fa-shield-halved"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Enable Recaptcha</h6>
                                                <p class="settings-card-desc">Enable Google reCAPTCHA on forms.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <div class="form-check form-switch mb-0">
                                            <input type="hidden" name="enable_recaptcha" value="0">
                                            <input class="form-check-input switch-green" type="checkbox"
                                                name="enable_recaptcha" id="enableRecaptchaSwitch" value="1"
                                                {{ ($settings['enable_recaptcha'] ?? '1') == '1' ? 'checked' : '' }}>
                                        </div>
                                        <div class="settings-callout callout-green">
                                            <i class="fa-solid fa-circle-info"></i>
                                            <span>Helps protect your forms from spam and abuse.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 13: Default Landing Page -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-solid fa-desktop"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Default Landing Page</h6>
                                                <p class="settings-card-desc">Select default landing page after login.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <select class="form-select" name="default_landing_page">
                                            <option value="dashboard"
                                                {{ ($settings['default_landing_page'] ?? 'dashboard') === 'dashboard' ? 'selected' : '' }}>
                                                Dashboard</option>
                                            <option value="contacts"
                                                {{ ($settings['default_landing_page'] ?? '') === 'contacts' ? 'selected' : '' }}>
                                                Contacts</option>
                                            <option value="deals"
                                                {{ ($settings['default_landing_page'] ?? '') === 'deals' ? 'selected' : '' }}>
                                                Deals</option>
                                            <option value="tasks"
                                                {{ ($settings['default_landing_page'] ?? '') === 'tasks' ? 'selected' : '' }}>
                                                Tasks</option>
                                            <option value="reports"
                                                {{ ($settings['default_landing_page'] ?? '') === 'reports' ? 'selected' : '' }}>
                                                Reports</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 14: Deal Close Date -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-solid fa-chart-line"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Deal Close Date</h6>
                                                <p class="settings-card-desc">Set default expected close days for new
                                                    deals.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="deal_close_days"
                                                value="{{ $settings['deal_close_days'] ?? '30' }}">
                                            <span class="input-group-text">Days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 15: Auto Logout -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-solid fa-lock"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Auto Logout</h6>
                                                <p class="settings-card-desc">Automatically logout user after inactivity.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <select class="form-select" name="auto_logout">
                                            <option value="15 minutes"
                                                {{ ($settings['auto_logout'] ?? '') === '15 minutes' ? 'selected' : '' }}>
                                                15 Minutes</option>
                                            <option value="30 minutes"
                                                {{ ($settings['auto_logout'] ?? '') === '30 minutes' ? 'selected' : '' }}>
                                                30 Minutes</option>
                                            <option value="1 hour"
                                                {{ ($settings['auto_logout'] ?? '1 hour') === '1 hour' ? 'selected' : '' }}>
                                                1 Hour</option>
                                            <option value="2 hours"
                                                {{ ($settings['auto_logout'] ?? '') === '2 hours' ? 'selected' : '' }}>2
                                                Hours</option>
                                            <option value="never"
                                                {{ ($settings['auto_logout'] ?? '') === 'never' ? 'selected' : '' }}>Never
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 16: Email Verification -->
                            <div class="col">
                                <div class="settings-card">
                                    <div>
                                        <div class="settings-card-header">
                                            <div class="settings-icon-badge icon-blue">
                                                <i class="fa-solid fa-envelope-circle-check"></i>
                                            </div>
                                            <div class="settings-title-wrap">
                                                <h6 class="settings-card-title">Email Verification</h6>
                                                <p class="settings-card-desc">Require email verification for new users.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-card-body">
                                        <div class="form-check form-switch mb-0">
                                            <input type="hidden" name="email_verification" value="0">
                                            <input class="form-check-input switch-green" type="checkbox"
                                                name="email_verification" id="emailVerificationSwitch" value="1"
                                                {{ ($settings['email_verification'] ?? '1') == '1' ? 'checked' : '' }}>
                                        </div>
                                        <div class="settings-callout callout-green">
                                            <i class="fa-solid fa-circle-info"></i>
                                            <span>Users must verify email before login.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Placeholder panes for other tabs -->
                    <div class="tab-pane fade" id="v-pills-company" role="tabpanel"
                        aria-labelledby="v-pills-company-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-regular fa-building me-2 text-primary"></i> Company Profile</h5>
                            <p class="text-secondary mb-0">Configure company details, branding, address, and legal
                                registration numbers.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-localization" role="tabpanel"
                        aria-labelledby="v-pills-localization-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-solid fa-globe me-2 text-primary"></i> Localization</h5>
                            <p class="text-secondary mb-0">Manage region settings, translations, number formatting, and
                                regional holidays.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-email" role="tabpanel" aria-labelledby="v-pills-email-tab"
                        tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-regular fa-envelope me-2 text-primary"></i> Email Settings</h5>
                            <p class="text-secondary mb-0">SMTP configuration, email templates, and outgoing mail servers.
                            </p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel"
                        aria-labelledby="v-pills-notifications-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-regular fa-bell me-2 text-primary"></i> Notifications</h5>
                            <p class="text-secondary mb-0">Set up push notifications, system alerts, and SMS integrations.
                            </p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel"
                        aria-labelledby="v-pills-security-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-solid fa-shield-halved me-2 text-primary"></i> Security</h5>
                            <p class="text-secondary mb-0">Password policies, two-factor authentication, and IP whitelist
                                rules.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-users-tab"
                        tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-solid fa-user-gear me-2 text-primary"></i> Users & Permissions</h5>
                            <p class="text-secondary mb-0">User role permissions, access control levels, and team
                                assignments.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-preferences" role="tabpanel"
                        aria-labelledby="v-pills-preferences-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-solid fa-bars-staggered me-2 text-primary"></i> CRM Preferences</h5>
                            <p class="text-secondary mb-0">Pipeline defaults, deal stages, task types, and custom field
                                options.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-integrations" role="tabpanel"
                        aria-labelledby="v-pills-integrations-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-solid fa-puzzle-piece me-2 text-primary"></i> Integrations</h5>
                            <p class="text-secondary mb-0">Connect external services, webhooks, API keys, and third-party
                                tools.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-backup" role="tabpanel" aria-labelledby="v-pills-backup-tab"
                        tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-solid fa-cloud-arrow-up me-2 text-primary"></i> Backup & Restore</h5>
                            <p class="text-secondary mb-0">Database backups, automated snapshots, and system restore
                                points.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-system" role="tabpanel" aria-labelledby="v-pills-system-tab"
                        tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-solid fa-desktop me-2 text-primary"></i> System Info</h5>
                            <p class="text-secondary mb-0">PHP version, database status, server memory usage, and
                                environment diagnostics.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-audit" role="tabpanel" aria-labelledby="v-pills-audit-tab"
                        tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <h5 class="fw-bold text-body-emphasis mb-2"><i
                                    class="fa-solid fa-clipboard-list me-2 text-primary"></i> Audit Log</h5>
                            <p class="text-secondary mb-0">Track administrative activities, security logs, and user action
                                history.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('js/settings.js') }}"></script>
@endpush
