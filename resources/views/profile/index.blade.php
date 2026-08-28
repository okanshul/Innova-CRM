@extends('layouts.app', ['title' => 'InnovaCRM - User Profile'])

@section('content')
    <!-- Breadcrumb Component -->
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'My Profile']
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body mb-4">
                <!-- Page Header Component inside Card Header -->
                <x-page-header 
                    title="User Profile" 
                    subtitle="Manage your personal information, avatar, and security settings."
                    icon="fa-solid fa-user-circle" 
                />

                <!-- Profile Cover Banner & Header -->
                <div class="position-relative">
                    <div class="profile-cover-banner" style="height: 140px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);"></div>
                    <div class="p-3 text-center bg-body position-relative rounded-bottom-4">
                        <div class="profile-avatar-wrapper mb-3 mx-auto" style="margin-top: -65px; width: 110px; height: 110px; position: relative;">
                            @php
                                $hasAvatar = $user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar);
                                $avatarSrc = $hasAvatar
                                    ? asset('storage/' . $user->avatar)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366F1&color=fff';
                                $uiAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366F1&color=fff';
                            @endphp
                            <img src="{{ $avatarSrc }}" onerror="this.onerror=null;this.src='{{ $uiAvatar }}';" id="profileAvatarPreview" class="profile-avatar-img rounded-circle border border-4 border-white shadow" alt="{{ $user->name }}" style="width: 110px; height: 110px; object-fit: cover;">
                            <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow cursor-pointer" style="width: 32px; height: 32px; cursor: pointer;" title="Change Avatar">
                                <i class="fa-solid fa-camera fs-xs"></i>
                            </label>
                        </div>
                        <h3 class="fw-bold mb-1 text-body-emphasis" id="profileDisplayName">{{ $user->name }}</h3>
                        <p class="text-secondary mb-3 small fw-medium d-flex align-items-center justify-content-center gap-2 flex-wrap">
                            <span><i class="fa-solid fa-envelope text-primary me-1 opacity-75"></i>{{ $user->email }}</span>
                            <span class="text-muted">•</span>
                            <span><i class="fa-solid fa-briefcase text-primary me-1 opacity-75"></i>{{ $user->position ?? 'Team Member' }}</span>
                            <span class="text-muted">•</span>
                            <span><i class="fa-solid fa-building text-purple me-1 opacity-75" style="color: #8b5cf6;"></i>{{ $user->department ?? 'General' }}</span>
                        </p>

                        <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-medium" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-user-shield me-1"></i>{{ ucfirst($user->getRoleNames()->first() ?? 'Staff') }}
                            </span>
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-medium" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-circle-check me-1"></i>Active Account
                            </span>
                            <span class="badge bg-body-tertiary text-secondary border px-3 py-2 rounded-pill fw-medium" style="font-size: 0.8rem;">
                                <i class="fa-regular fa-calendar-days me-1"></i>Joined {{ $user->created_at ? format_date($user->created_at) : 'Recently' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs & Content -->
    <div class="row">
        <div class="col-12 col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <div class="card-header bg-body border-bottom-0 p-3 pb-0">
                    <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold text-nowrap" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile-pane" type="button" role="tab" aria-controls="edit-profile-pane" aria-selected="true">
                                <i class="fa-regular fa-user me-2 text-primary"></i>Personal Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold text-nowrap" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-pane" type="button" role="tab" aria-controls="security-pane" aria-selected="false">
                                <i class="fa-solid fa-shield-halved me-2 text-danger"></i>Security & Password
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold text-nowrap" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-pane" type="button" role="tab" aria-controls="overview-pane" aria-selected="false">
                                <i class="fa-solid fa-chart-pie me-2 text-info"></i>Activity Stats
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-3">
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Tab 1: Personal Information Form -->
                        <div class="tab-pane fade show active" id="edit-profile-pane" role="tabpanel" aria-labelledby="edit-profile-tab" tabindex="0">
                            <form id="profileUpdateForm" action="{{ route('crm.api.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" id="avatarInput" name="avatar" class="d-none" accept="image/*">

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <x-form.input name="name" label="Full Name" icon="fa-solid fa-user" :required="true" :value="$user->name" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-form.input type="email" name="email" label="Email Address" icon="fa-solid fa-envelope" :required="true" :value="$user->email" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-form.input name="phone" label="Phone Number" icon="fa-solid fa-phone" :value="$user->phone" placeholder="+1 (555) 000-0000" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-form.select name="department" label="Department" icon="fa-solid fa-building">
                                            <option value="">Select Department</option>
                                            <option value="Sales" {{ $user->department === 'Sales' ? 'selected' : '' }}>Sales</option>
                                            <option value="Marketing" {{ $user->department === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                            <option value="Customer Support" {{ $user->department === 'Customer Support' ? 'selected' : '' }}>Customer Support</option>
                                            <option value="Finance" {{ $user->department === 'Finance' ? 'selected' : '' }}>Finance</option>
                                            <option value="IT" {{ $user->department === 'IT' ? 'selected' : '' }}>IT</option>
                                            <option value="Operations" {{ $user->department === 'Operations' ? 'selected' : '' }}>Operations</option>
                                            <option value="Management" {{ $user->department === 'Management' ? 'selected' : '' }}>Management</option>
                                        </x-form.select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-form.input name="position" label="Position / Job Title" icon="fa-solid fa-briefcase" :value="$user->position" placeholder="e.g. Senior Account Manager" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-form.input name="role" label="Role / Permissions" icon="fa-solid fa-user-shield" :value="ucfirst($user->getRoleNames()->first() ?? 'Staff')" readonly class="bg-body-tertiary" />
                                    </div>

                                </div>

                                <div class="border-top pt-3 mt-4 d-flex align-items-center justify-content-end gap-2">
                                    <x-button.primary type="submit" id="btnSaveProfile" label="Update" />
                                </div>
                            </form>
                        </div>

                        <!-- Tab 2: Security & Password Form -->
                        <div class="tab-pane fade" id="security-pane" role="tabpanel" aria-labelledby="security-tab" tabindex="0">
                            <form id="passwordUpdateForm" action="{{ route('crm.api.profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <div class="col-12">
                                        <x-form.input type="password" name="current_password" label="Current Password" icon="fa-solid fa-lock" :required="true" placeholder="Enter current password" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-form.input type="password" name="password" label="New Password" icon="fa-solid fa-key" :required="true" placeholder="Minimum 8 characters" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-form.input type="password" name="password_confirmation" label="Confirm New Password" icon="fa-solid fa-check-double" :required="true" placeholder="Repeat new password" />
                                    </div>
                                </div>

                                <div class="border-top pt-3 mt-4 d-flex align-items-center justify-content-end gap-2">
                                    <x-button.primary type="submit" id="btnSavePassword" label="Update" />
                                </div>
                            </form>
                        </div>

                        <!-- Tab 3: Activity & Stats -->
                        <div class="tab-pane fade" id="overview-pane" role="tabpanel" aria-labelledby="overview-tab" tabindex="0">
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="p-3 rounded-4 bg-primary-subtle text-primary border border-primary-subtle text-center">
                                        <div class="fs-2 fw-bold mb-1">{{ $user->deals->count() }}</div>
                                        <div class="small fw-semibold"><i class="fa-solid fa-handshake me-1"></i>Deals Owned</div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="p-3 rounded-4 bg-success-subtle text-success border border-success-subtle text-center">
                                        <div class="fs-2 fw-bold mb-1">{{ $user->tasks->count() }}</div>
                                        <div class="small fw-semibold"><i class="fa-solid fa-list-check me-1"></i>Tasks Assigned</div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="p-3 rounded-4 bg-purple-subtle text-purple border border-purple-subtle text-center" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <div class="fs-2 fw-bold mb-1">{{ $user->hostedMeetings->count() }}</div>
                                        <div class="small fw-semibold"><i class="fa-solid fa-video me-1"></i>Meetings Hosted</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border border-body-tertiary rounded-3 bg-body p-3">
                                <h6 class="fw-bold mb-3 text-body-emphasis"><i class="fa-solid fa-circle-info text-primary me-2"></i>Account Information</h6>
                                <div class="row g-2 fs-sm text-secondary">
                                    <div class="col-6 col-md-4"><strong>User ID:</strong> #{{ $user->id }}</div>
                                    <div class="col-6 col-md-4"><strong>Status:</strong> <span class="text-success fw-medium">Active</span></div>
                                    <div class="col-6 col-md-4"><strong>Primary Role:</strong> {{ ucfirst($user->getRoleNames()->first() ?? 'Staff') }}</div>
                                    <div class="col-6 col-md-4"><strong>Guard:</strong> web</div>
                                    <div class="col-6 col-md-4"><strong>Joined:</strong> {{ $user->created_at ? format_date($user->created_at) : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary Box -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-body p-3 mb-4">
                <h5 class="fw-bold text-body-emphasis mb-3"><i class="fa-solid fa-shield-cat me-2 text-primary"></i>Security Checklist</h5>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3 small">
                    <li class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check text-success fs-6"></i>
                        <span>Email address verified</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check text-success fs-6"></i>
                        <span>Role-based access permissions active</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-shield text-info fs-6"></i>
                        <span>SSL encrypted connection</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
@endpush
