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
                                <div>
                                    <h5 class="fw-bold mb-0 text-body-emphasis">General Settings</h5>
                                    <p class="text-secondary small mb-0">Configure basic application settings.</p>
                                </div>
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

                    <!-- Tab 2: Company Profile -->
                    <div class="tab-pane fade" id="v-pills-company" role="tabpanel" aria-labelledby="v-pills-company-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-0 text-body-emphasis">Company Profile</h5>
                                    <p class="text-secondary small mb-0">Update your company information and details.</p>
                                </div>
                                <x-button.primary type="submit" icon="fa-solid fa-check" label="Save Changes" />
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-regular fa-building me-2 text-primary"></i>Company Information</h6>
                                        <x-form.input class="mb-3" name="company_profile_name" label="Company Name" icon="fa-regular fa-building" :value="$settings['company_name'] ?? 'InnovaCRM Inc.'" />
                                        <x-form.input class="mb-3" type="email" name="company_profile_email" label="Company Email" icon="fa-regular fa-envelope" :value="$settings['company_email'] ?? 'info@innovacrm.com'" />
                                        <x-form.input class="mb-3" name="company_profile_phone" label="Phone Number" icon="fa-solid fa-phone" :value="$settings['company_phone'] ?? '+1 (800) 123-4567'" />
                                        <x-form.input class="mb-3" type="url" name="company_profile_website" label="Website" icon="fa-solid fa-globe" :value="$settings['company_website'] ?? 'https://www.innovacrm.com'" />
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-solid fa-location-dot me-2 text-primary"></i>Company Address</h6>
                                        <x-form.input class="mb-3" name="company_address_1" label="Address Line 1" icon="fa-solid fa-location-dot" :value="$settings['company_address_1'] ?? '123 Business Street'" />
                                        <x-form.input class="mb-3" name="company_address_2" label="Address Line 2" icon="fa-solid fa-building-user" :value="$settings['company_address_2'] ?? 'Suite 100'" />
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <x-form.input name="company_city" label="City" icon="fa-solid fa-city" :value="$settings['company_city'] ?? 'San Francisco'" />
                                            </div>
                                            <div class="col-6">
                                                <x-form.input name="company_state" label="State" icon="fa-solid fa-map-location-dot" :value="$settings['company_state'] ?? 'California'" />
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <x-form.input name="company_postal" label="Postal Code" icon="fa-solid fa-mail-bulk" :value="$settings['company_postal'] ?? '94107'" />
                                            </div>
                                            <div class="col-6">
                                                <x-form.input name="company_country" label="Country" icon="fa-solid fa-flag" :value="$settings['company_country'] ?? 'United States'" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Localization -->
                    <div class="tab-pane fade" id="v-pills-localization" role="tabpanel" aria-labelledby="v-pills-localization-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-0 text-body-emphasis">Localization</h5>
                                    <p class="text-secondary small mb-0">Manage language, region and locale preferences.</p>
                                </div>
                                <x-button.primary type="submit" icon="fa-solid fa-check" label="Save Changes" />
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3 gy-4">
                                    <div class="col-md-4">
                                        <x-form.select name="localization_language" label="Application Language" icon="fa-solid fa-language">
                                            <option value="en" selected>English</option>
                                            <option value="es">Spanish</option>
                                            <option value="fr">French</option>
                                            <option value="de">German</option>
                                        </x-form.select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-form.select name="localization_date_format" label="Date Format" icon="fa-regular fa-calendar">
                                            <option value="MMM D, YYYY" selected>Nov 5, 2026 (MMM D, YYYY)</option>
                                            <option value="YYYY-MM-DD">2026-11-05 (YYYY-MM-DD)</option>
                                            <option value="DD/MM/YYYY">05/11/2026 (DD/MM/YYYY)</option>
                                        </x-form.select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-form.select name="localization_time_format" label="Time Format" icon="fa-regular fa-clock">
                                            <option value="12" selected>12 Hours (02:30 PM)</option>
                                            <option value="24">24 Hours (14:30)</option>
                                        </x-form.select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-form.select name="localization_timezone" label="Time Zone" icon="fa-solid fa-earth-americas">
                                            <option value="Asia/Kolkata" selected>(UTC+05:30) Asia/Kolkata</option>
                                            <option value="UTC">(UTC+00:00) UTC / London</option>
                                            <option value="America/New_York">(UTC-05:00) America/New_York</option>
                                        </x-form.select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-form.select name="localization_first_day" label="First Day of Week" icon="fa-solid fa-calendar-day">
                                            <option value="Monday" selected>Monday</option>
                                            <option value="Sunday">Sunday</option>
                                            <option value="Saturday">Saturday</option>
                                        </x-form.select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-form.select name="localization_number_format" label="Number Format" icon="fa-solid fa-arrow-down-1-9">
                                            <option value="1,234.56" selected>1,234.56</option>
                                            <option value="1.234,56">1.234,56</option>
                                            <option value="1 234,56">1 234,56</option>
                                        </x-form.select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-form.select name="localization_currency" label="Currency" icon="fa-solid fa-dollar-sign">
                                            <option value="USD" selected>USD ($)</option>
                                            <option value="EUR">EUR (€)</option>
                                            <option value="GBP">GBP (£)</option>
                                            <option value="INR">INR (₹)</option>
                                        </x-form.select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-form.select name="localization_measurement" label="Measurement System" icon="fa-solid fa-ruler-combined">
                                            <option value="Metric" selected>Metric (kg, cm)</option>
                                            <option value="Imperial">Imperial (lb, in)</option>
                                        </x-form.select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Email Settings Pane -->
                    <div class="tab-pane fade" id="v-pills-email" role="tabpanel" aria-labelledby="v-pills-email-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-0 text-body-emphasis">Email Settings</h5>
                                    <p class="text-secondary small mb-0">Configure system email settings and SMTP.</p>
                                </div>
                                <x-button.primary type="submit" icon="fa-solid fa-check" label="Save Changes" />
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-4">
                                    <div class="col-md-7">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-solid fa-server me-2 text-primary"></i>SMTP Configuration</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <x-form.input name="smtp_driver" label="Mail Driver" icon="fa-solid fa-gear" value="SMTP" />
                                            </div>
                                            <div class="col-md-6">
                                                <x-form.input type="number" name="smtp_port" label="Port" icon="fa-solid fa-network-wired" value="587" />
                                            </div>
                                            <div class="col-12">
                                                <x-form.input name="smtp_host" label="Mail Host" icon="fa-solid fa-server" value="smtp.innovacrm.com" />
                                            </div>
                                            <div class="col-md-6">
                                                <x-form.select name="smtp_encryption" label="Encryption" icon="fa-solid fa-lock">
                                                    <option value="TLS" selected>TLS</option>
                                                    <option value="SSL">SSL</option>
                                                    <option value="None">None</option>
                                                </x-form.select>
                                            </div>
                                            <div class="col-md-6">
                                                <x-form.input name="smtp_username" label="Username" icon="fa-solid fa-user" value="no-reply@innovacrm.com" />
                                            </div>
                                            <div class="col-12">
                                                <x-form.input type="password" name="smtp_password" label="Password" icon="fa-solid fa-key" value="••••••••••••" />
                                            </div>
                                            <div class="col-12 pt-1">
                                                <button type="button" class="btn btn-outline-primary btn-sm rounded-3">
                                                    <i class="fa-solid fa-paper-plane me-2"></i> Send Test Email
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-solid fa-sliders me-2 text-primary"></i>Email Preferences</h6>
                                        <div class="d-flex flex-column gap-3">
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Use system email address</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Disables custom default email addresses.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="pref_sys_email" checked>
                                                </div>
                                            </div>
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Email Notifications</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Enable email notifications for system events.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="pref_notifications" checked>
                                                </div>
                                            </div>
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Email Verification</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Require email verification for new users.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="pref_verification" checked>
                                                </div>
                                            </div>
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Allow users to change email</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Allow users to change their email address.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="pref_change_email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 5: Notifications Pane -->
                    <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-0 text-body-emphasis">Notifications</h5>
                                    <p class="text-secondary small mb-0">Manage system notifications and alerts.</p>
                                </div>
                                <x-button.primary type="submit" icon="fa-solid fa-check" label="Save Changes" />
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-4">
                                    <div class="col-md-6 d-flex flex-column">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-regular fa-bell me-2 text-primary"></i>Notification Channels</h6>
                                        <div class="d-flex flex-column gap-3 flex-grow-1 justify-content-between">
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">In-App Notifications</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Receive notifications inside the application.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="channel_inapp" checked>
                                                </div>
                                            </div>
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Email Notifications</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Receive notifications via email.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="channel_email" checked>
                                                </div>
                                            </div>
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">SMS Notifications</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Receive notifications via SMS text.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="channel_sms">
                                                </div>
                                            </div>
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Browser Notifications</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Receive push notifications in browser.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="channel_browser" checked>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex flex-column">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-solid fa-list-check me-2 text-primary"></i>Notification Preferences</h6>
                                        <div class="p-3 border rounded-3 bg-body-tertiary d-flex flex-column justify-content-between flex-grow-1">
                                            <div class="form-check py-1">
                                                <input class="form-check-input" type="checkbox" name="notify_new_lead" id="notifyLead" checked>
                                                <label class="form-check-label fs-7 fw-medium text-body-emphasis ms-1" for="notifyLead">New lead assigned</label>
                                            </div>
                                            <div class="form-check py-1">
                                                <input class="form-check-input" type="checkbox" name="notify_deal_stage" id="notifyDeal" checked>
                                                <label class="form-check-label fs-7 fw-medium text-body-emphasis ms-1" for="notifyDeal">Deal stage updated</label>
                                            </div>
                                            <div class="form-check py-1">
                                                <input class="form-check-input" type="checkbox" name="notify_task_due" id="notifyTask" checked>
                                                <label class="form-check-label fs-7 fw-medium text-body-emphasis ms-1" for="notifyTask">Task due reminder</label>
                                            </div>
                                            <div class="form-check py-1">
                                                <input class="form-check-input" type="checkbox" name="notify_new_deal" id="notifyNewDeal" checked>
                                                <label class="form-check-label fs-7 fw-medium text-body-emphasis ms-1" for="notifyNewDeal">New deal created</label>
                                            </div>
                                            <div class="form-check py-1">
                                                <input class="form-check-input" type="checkbox" name="notify_new_user" id="notifyUser" checked>
                                                <label class="form-check-label fs-7 fw-medium text-body-emphasis ms-1" for="notifyUser">New user registered</label>
                                            </div>
                                            <div class="form-check py-1">
                                                <input class="form-check-input" type="checkbox" name="notify_meeting" id="notifyMeeting" checked>
                                                <label class="form-check-label fs-7 fw-medium text-body-emphasis ms-1" for="notifyMeeting">Meeting scheduled</label>
                                            </div>
                                            <div class="form-check py-1">
                                                <input class="form-check-input" type="checkbox" name="notify_invoice" id="notifyInvoice">
                                                <label class="form-check-label fs-7 fw-medium text-body-emphasis ms-1" for="notifyInvoice">Invoice created</label>
                                            </div>
                                            <div class="form-check py-1">
                                                <input class="form-check-input" type="checkbox" name="notify_payment" id="notifyPayment" checked>
                                                <label class="form-check-label fs-7 fw-medium text-body-emphasis ms-1" for="notifyPayment">Payment received</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 6: Security Pane -->
                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-0 text-body-emphasis">Security</h5>
                                    <p class="text-secondary small mb-0">Manage security settings and authentication.</p>
                                </div>
                                <x-button.primary type="submit" icon="fa-solid fa-check" label="Save Changes" />
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-solid fa-lock me-2 text-primary"></i>Password Policy</h6>
                                        <x-form.select class="mb-3" name="sec_min_password" label="Minimum Password Length" icon="fa-solid fa-text-height">
                                            <option value="8" selected>8 Characters</option>
                                            <option value="10">10 Characters</option>
                                            <option value="12">12 Characters</option>
                                        </x-form.select>
                                        <div class="p-3 border rounded-3 bg-body-tertiary mb-3 d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-1 fw-semibold fs-7">Require Number</h6>
                                                <p class="text-secondary small mb-0 fs-8">Must contain at least 1 numeric character.</p>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input switch-green" type="checkbox" name="sec_req_number" checked>
                                            </div>
                                        </div>
                                        <div class="p-3 border rounded-3 bg-body-tertiary mb-3 d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-1 fw-semibold fs-7">Require Special Character</h6>
                                                <p class="text-secondary small mb-0 fs-8">Must contain at least 1 symbol.</p>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input switch-green" type="checkbox" name="sec_req_special" checked>
                                            </div>
                                        </div>
                                        <x-form.select name="sec_password_expiry" label="Password Expiry" icon="fa-solid fa-calendar-xmark">
                                            <option value="90" selected>90 Days</option>
                                            <option value="30">30 Days</option>
                                            <option value="60">60 Days</option>
                                            <option value="0">Never</option>
                                        </x-form.select>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-solid fa-shield-halved me-2 text-primary"></i>Two-Factor Authentication (2FA)</h6>
                                        <div class="p-3 border rounded-3 bg-body-tertiary mb-3 d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-1 fw-semibold fs-7">Require 2FA for all users</h6>
                                                <p class="text-secondary small mb-0 fs-8">Mandatory 2FA authentication.</p>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input switch-green" type="checkbox" name="sec_req_2fa">
                                            </div>
                                        </div>
                                        <div class="p-3 border rounded-3 bg-body-tertiary mb-4 d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-1 fw-semibold fs-7">Allow users to enable 2FA</h6>
                                                <p class="text-secondary small mb-0 fs-8">Optional 2FA for user accounts.</p>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input switch-green" type="checkbox" name="sec_allow_2fa" checked>
                                            </div>
                                        </div>

                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-regular fa-clock me-2 text-primary"></i>Session Settings</h6>
                                        <x-form.select class="mb-3" name="sec_session_timeout" label="Session Timeout" icon="fa-regular fa-hourglass-half">
                                            <option value="1h" selected>1 Hour</option>
                                            <option value="30m">30 Minutes</option>
                                            <option value="2h">2 Hours</option>
                                        </x-form.select>
                                        <x-form.select name="sec_remember_duration" label="Remember Me Duration" icon="fa-regular fa-clock">
                                            <option value="7d" selected>7 Days</option>
                                            <option value="1d">1 Day</option>
                                            <option value="30d">30 Days</option>
                                        </x-form.select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 7: Users & Permissions Pane -->
                    <div class="tab-pane fade" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-users-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-0 text-body-emphasis">Users & Permissions</h5>
                                    <p class="text-secondary small mb-0">Manage users and their roles/permissions.</p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-3 fw-semibold px-3">
                                        <i class="fa-solid fa-user-plus me-1"></i> Add User
                                    </button>
                                    <x-button.primary type="submit" icon="fa-solid fa-check" label="Save Changes" />
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-body-tertiary border-bottom">
                                            <tr>
                                                <th class="ps-4 py-3 small fw-bold text-secondary">User</th>
                                                <th class="py-3 small fw-bold text-secondary">Role</th>
                                                <th class="py-3 small fw-bold text-secondary">Status</th>
                                                <th class="py-3 small fw-bold text-secondary">Last Active</th>
                                                <th class="pe-4 py-3 text-end small fw-bold text-secondary">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width: 36px; height: 36px;">AA</div>
                                                        <div>
                                                            <div class="fw-bold fs-7 text-body-emphasis">Anshul Admin</div>
                                                            <div class="text-secondary fs-8">anshul@innovacrm.com</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">Administrator</span></td>
                                                <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-1"><i class="fa-solid fa-circle fs-xs me-1"></i>Active</span></td>
                                                <td class="text-secondary fs-8">May 5, 2026, 10:30 AM</td>
                                                <td class="pe-4 text-end">
                                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-secondary rounded-circle"><i class="fa-regular fa-pen-to-square"></i></button>
                                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-danger rounded-circle"><i class="fa-regular fa-trash-can"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-circle bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width: 36px; height: 36px;">JD</div>
                                                        <div>
                                                            <div class="fw-bold fs-7 text-body-emphasis">John Doe</div>
                                                            <div class="text-secondary fs-8">john@innovacrm.com</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">Manager</span></td>
                                                <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-1"><i class="fa-solid fa-circle fs-xs me-1"></i>Active</span></td>
                                                <td class="text-secondary fs-8">May 5, 2026, 09:15 AM</td>
                                                <td class="pe-4 text-end">
                                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-secondary rounded-circle"><i class="fa-regular fa-pen-to-square"></i></button>
                                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-danger rounded-circle"><i class="fa-regular fa-trash-can"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-circle bg-purple-subtle text-purple rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width: 36px; height: 36px;">JS</div>
                                                        <div>
                                                            <div class="fw-bold fs-7 text-body-emphasis">Jane Smith</div>
                                                            <div class="text-secondary fs-8">jane@innovacrm.com</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-purple-subtle text-purple rounded-pill px-3 py-1">Sales Executive</span></td>
                                                <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-1"><i class="fa-solid fa-circle fs-xs me-1"></i>Active</span></td>
                                                <td class="text-secondary fs-8">May 4, 2026, 04:45 PM</td>
                                                <td class="pe-4 text-end">
                                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-secondary rounded-circle"><i class="fa-regular fa-pen-to-square"></i></button>
                                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-danger rounded-circle"><i class="fa-regular fa-trash-can"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-circle bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width: 36px; height: 36px;">MJ</div>
                                                        <div>
                                                            <div class="fw-bold fs-7 text-body-emphasis">Mike Johnson</div>
                                                            <div class="text-secondary fs-8">mike@innovacrm.com</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1">Support</span></td>
                                                <td><span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1"><i class="fa-solid fa-circle fs-xs me-1"></i>Inactive</span></td>
                                                <td class="text-secondary fs-8">Apr 30, 2026, 11:20 AM</td>
                                                <td class="pe-4 text-end">
                                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-secondary rounded-circle"><i class="fa-regular fa-pen-to-square"></i></button>
                                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-danger rounded-circle"><i class="fa-regular fa-trash-can"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 8: CRM Preferences Pane -->
                    <div class="tab-pane fade" id="v-pills-preferences" role="tabpanel" aria-labelledby="v-pills-preferences-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-0 text-body-emphasis">CRM Preferences</h5>
                                    <p class="text-secondary small mb-0">Configure CRM behavior and default values.</p>
                                </div>
                                <x-button.primary type="submit" icon="fa-solid fa-check" label="Save Changes" />
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <x-form.select class="mb-3" name="crm_default_lead_status" label="Default Lead Status" icon="fa-solid fa-filter">
                                            <option value="New" selected>New</option>
                                            <option value="Contacted">Contacted</option>
                                            <option value="Qualified">Qualified</option>
                                        </x-form.select>
                                        <x-form.select class="mb-3" name="crm_default_deal_stage" label="Default Deal Stage" icon="fa-solid fa-chart-line">
                                            <option value="Prospecting" selected>Prospecting</option>
                                            <option value="Qualification">Qualification</option>
                                            <option value="Proposal">Proposal</option>
                                        </x-form.select>
                                        <x-form.select class="mb-3" name="crm_default_source" label="Default Source" icon="fa-solid fa-globe">
                                            <option value="Website" selected>Website</option>
                                            <option value="Referral">Referral</option>
                                            <option value="Cold Call">Cold Call</option>
                                        </x-form.select>
                                        <x-form.select name="crm_lead_conversion" label="Lead Conversion" icon="fa-solid fa-arrows-rotate">
                                            <option value="Create Contact & Deal" selected>Create Contact & Deal</option>
                                            <option value="Create Contact Only">Create Contact Only</option>
                                        </x-form.select>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex flex-column gap-3">
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Enable Lead Scoring</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Automatically score incoming leads.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="crm_enable_scoring" checked>
                                                </div>
                                            </div>
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Enable Deal Forecast</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Forecast revenue based on deal probability.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="crm_enable_forecast" checked>
                                                </div>
                                            </div>
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Enable Activity Reminders</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Send reminders for upcoming tasks & meetings.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="crm_enable_reminders" checked>
                                                </div>
                                            </div>
                                            <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold fs-7">Auto Assign Leads</h6>
                                                    <p class="text-secondary small mb-0 fs-8">Round-robin lead assignment to sales team.</p>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input switch-green" type="checkbox" name="crm_auto_assign">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 9: Integrations Pane -->
                    <div class="tab-pane fade" id="v-pills-integrations" role="tabpanel" aria-labelledby="v-pills-integrations-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 border-bottom">
                                <h5 class="fw-bold mb-0 text-body-emphasis">Integrations</h5>
                                <p class="text-secondary small mb-0">Connect and manage third-party integrations.</p>
                            </div>
                            <div class="card-body p-3">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
                                    <div class="col">
                                        <div class="p-3 border rounded-4 bg-body text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                            <div class="mb-3">
                                                <div class="fs-2 text-danger mb-2"><i class="fa-brands fa-google"></i></div>
                                                <h6 class="fw-bold mb-1">Google Workspace</h6>
                                                <p class="text-secondary fs-8 mb-0">Sync Gmail & Google Calendar</p>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary rounded-3 w-100 fw-semibold">Connect</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="p-3 border rounded-4 bg-body text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                            <div class="mb-3">
                                                <div class="fs-2 text-primary mb-2"><i class="fa-brands fa-microsoft"></i></div>
                                                <h6 class="fw-bold mb-1">Microsoft 365</h6>
                                                <p class="text-secondary fs-8 mb-0">Outlook & Teams integration</p>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary rounded-3 w-100 fw-semibold">Connect</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="p-3 border rounded-4 bg-body text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                            <div class="mb-3">
                                                <div class="fs-2 text-warning mb-2"><i class="fa-brands fa-slack"></i></div>
                                                <h6 class="fw-bold mb-1">Slack</h6>
                                                <p class="text-secondary fs-8 mb-0">Notifications & Bot sync</p>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary rounded-3 w-100 fw-semibold">Connect</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="p-3 border rounded-4 bg-body text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                            <div class="mb-3">
                                                <div class="fs-2 text-dark mb-2"><i class="fa-brands fa-mailchimp"></i></div>
                                                <h6 class="fw-bold mb-1">Mailchimp</h6>
                                                <p class="text-secondary fs-8 mb-0">Email marketing automation</p>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary rounded-3 w-100 fw-semibold">Connect</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="p-3 border rounded-4 bg-body text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                            <div class="mb-3">
                                                <div class="fs-2 text-danger mb-2"><i class="fa-solid fa-comment-sms"></i></div>
                                                <h6 class="fw-bold mb-1">Twilio (SMS)</h6>
                                                <p class="text-secondary fs-8 mb-0">Automated SMS messages</p>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary rounded-3 w-100 fw-semibold">Connect</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="p-3 border rounded-4 bg-body text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                            <div class="mb-3">
                                                <div class="fs-2 text-indigo mb-2" style="color: #6366f1;"><i class="fa-brands fa-stripe"></i></div>
                                                <h6 class="fw-bold mb-1">Stripe</h6>
                                                <p class="text-secondary fs-8 mb-0">Payment gateway & invoicing</p>
                                            </div>
                                            <button type="button" class="btn btn-soft-success rounded-3 w-100 fw-semibold disabled"><i class="fa-solid fa-circle-check me-1"></i> Connected</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="p-3 border rounded-4 bg-body text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                            <div class="mb-3">
                                                <div class="fs-2 text-warning mb-2" style="color: #f97316;"><i class="fa-solid fa-bolt"></i></div>
                                                <h6 class="fw-bold mb-1">Zapier</h6>
                                                <p class="text-secondary fs-8 mb-0">Workflow automation bridge</p>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary rounded-3 w-100 fw-semibold">Connect</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="p-3 border rounded-4 bg-body text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                            <div class="mb-3">
                                                <div class="fs-2 text-info mb-2"><i class="fa-solid fa-network-wired"></i></div>
                                                <h6 class="fw-bold mb-1">Webhook</h6>
                                                <p class="text-secondary fs-8 mb-0">Custom API HTTP endpoints</p>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary rounded-3 w-100 fw-semibold">Connect</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 10: Backup & Restore Pane -->
                    <div class="tab-pane fade" id="v-pills-backup" role="tabpanel" aria-labelledby="v-pills-backup-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 border-bottom">
                                <h5 class="fw-bold mb-0 text-body-emphasis">Backup & Restore</h5>
                                <p class="text-secondary small mb-0">Create a backup of your application data or restore from a backup.</p>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-4 bg-body-tertiary h-100 d-flex flex-column justify-content-between">
                                            <div>
                                                <h6 class="fw-bold mb-1 text-body-emphasis">Create Backup</h6>
                                                <p class="text-secondary small mb-3">Create a manual backup of your database & system assets.</p>
                                                <div class="p-3 bg-body rounded-3 border mb-3">
                                                    <span class="text-secondary fs-8 fw-medium">Last Backup:</span>
                                                    <span class="fw-semibold fs-8 text-body-emphasis">May 4, 2026, 11:30 PM</span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm rounded-3 fw-semibold py-2">
                                                <i class="fa-solid fa-plus me-2"></i> Create New Backup
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-4 bg-body-tertiary text-center h-100 d-flex flex-column justify-content-between">
                                            <div>
                                                <h6 class="fw-bold mb-1 text-body-emphasis">Restore Backup</h6>
                                                <p class="text-secondary small mb-3">Restore your application data from a backup zip file.</p>
                                            </div>
                                            <div class="border border-dashed rounded-3 p-3 bg-body text-center">
                                                <i class="fa-solid fa-cloud-arrow-up fs-2 text-primary mb-2"></i>
                                                <p class="fs-8 text-secondary mb-2">Drag and drop backup file here or</p>
                                                <input type="file" id="restoreFileInput" class="d-none" accept=".zip">
                                                <button type="button" class="btn btn-outline-primary btn-sm px-3" onclick="document.getElementById('restoreFileInput').click()">Choose File</button>
                                                <div class="fs-8 text-muted mt-2">Only .zip files are allowed.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h6 class="fw-bold mb-3 text-body-emphasis">Backup History</h6>
                                    <div class="table-responsive border rounded-3">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-body-tertiary">
                                                <tr>
                                                    <th class="ps-3 py-2 small fw-bold text-secondary">File Name</th>
                                                    <th class="py-2 small fw-bold text-secondary">Size</th>
                                                    <th class="py-2 small fw-bold text-secondary">Created At</th>
                                                    <th class="pe-3 py-2 text-end small fw-bold text-secondary">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="ps-3 py-2 fw-semibold fs-7"><i class="fa-regular fa-file-zipper me-2 text-primary"></i>backup_2026_05_04_233000.zip</td>
                                                    <td class="py-2 fs-7 text-secondary">48.6 MB</td>
                                                    <td class="py-2 fs-7 text-secondary">May 4, 2026, 11:30 PM</td>
                                                    <td class="pe-3 py-2 text-end">
                                                        <button type="button" class="btn btn-sm btn-icon btn-ghost-primary rounded-circle" title="Download"><i class="fa-solid fa-download"></i></button>
                                                        <button type="button" class="btn btn-sm btn-icon btn-ghost-danger rounded-circle" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 11: System Info Pane -->
                    <div class="tab-pane fade" id="v-pills-system" role="tabpanel" aria-labelledby="v-pills-system-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 border-bottom">
                                <h5 class="fw-bold mb-0 text-body-emphasis">System Information</h5>
                                <p class="text-secondary small mb-0">View system diagnostics, environment, and server information.</p>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-solid fa-desktop me-2 text-primary"></i>Application Info</h6>
                                        <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Version</span>
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3">v2.4.1</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">PHP Version</span>
                                                <span class="fw-semibold fs-7">8.2.14</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Framework</span>
                                                <span class="fw-semibold fs-7">Laravel 13</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Environment</span>
                                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Production</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Debug Mode</span>
                                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Off</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Database Driver</span>
                                                <span class="fw-semibold fs-7">MySQL</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-solid fa-server me-2 text-primary"></i>Server Info</h6>
                                        <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Server Software</span>
                                                <span class="fw-semibold fs-7">nginx/1.24.0</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Operating System</span>
                                                <span class="fw-semibold fs-7">Linux</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Server Time</span>
                                                <span class="fw-semibold fs-7">May 5, 2026, 12:10 PM</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Timezone</span>
                                                <span class="fw-semibold fs-7">(UTC+05:30) Asia/Kolkata</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Memory Usage</span>
                                                <span class="fw-semibold fs-7">128.0 MB</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span class="text-secondary fs-7 fw-medium">Disk Usage</span>
                                                <span class="fw-semibold fs-7">12.4 GB / 100 GB</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 12: Audit Log Pane -->
                    <div class="tab-pane fade" id="v-pills-audit" role="tabpanel" aria-labelledby="v-pills-audit-tab" tabindex="0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                            <div class="card-header border-0 bg-body p-3 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-0 text-body-emphasis">Audit Log</h5>
                                    <p class="text-secondary small mb-0">Track system changes, administrative actions, and user activities.</p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <select class="form-select form-select-sm" style="width: 140px;">
                                        <option value="">All Actions</option>
                                        <option value="Created">Created</option>
                                        <option value="Updated">Updated</option>
                                        <option value="Deleted">Deleted</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-body-tertiary border-bottom">
                                            <tr>
                                                <th class="ps-4 py-3 small fw-bold text-secondary">Time</th>
                                                <th class="py-3 small fw-bold text-secondary">User</th>
                                                <th class="py-3 small fw-bold text-secondary">Action</th>
                                                <th class="py-3 small fw-bold text-secondary">Module</th>
                                                <th class="pe-4 py-3 text-end small fw-bold text-secondary">IP Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="ps-4 py-3 fs-8 text-secondary">May 5, 2026, 11:45 AM</td>
                                                <td class="fw-semibold fs-7">Anshul Admin</td>
                                                <td><span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">Updated</span></td>
                                                <td class="fs-7">Deal</td>
                                                <td class="pe-4 text-end fs-8 text-secondary">192.168.1.10</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4 py-3 fs-8 text-secondary">May 5, 2026, 11:00 AM</td>
                                                <td class="fw-semibold fs-7">John Doe</td>
                                                <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Created</span></td>
                                                <td class="fs-7">Contact</td>
                                                <td class="pe-4 text-end fs-8 text-secondary">192.168.1.12</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4 py-3 fs-8 text-secondary">May 5, 2026, 10:15 AM</td>
                                                <td class="fw-semibold fs-7">Jane Smith</td>
                                                <td><span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1">Deleted</span></td>
                                                <td class="fs-7">Task</td>
                                                <td class="pe-4 text-end fs-8 text-secondary">192.168.1.15</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4 py-3 fs-8 text-secondary">May 5, 2026, 09:30 AM</td>
                                                <td class="fw-semibold fs-7">Mike Johnson</td>
                                                <td><span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">Login</span></td>
                                                <td class="fs-7">System</td>
                                                <td class="pe-4 text-end fs-8 text-secondary">192.168.1.18</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
