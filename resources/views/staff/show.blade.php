@extends('layouts.app', ['title' => 'InnovaCRM - Staff Details: ' . $staff->name])

@section('content')
    <!-- Breadcrumb Component -->
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Staff', 'url' => route('staff.index')],
        ['label' => $staff->name]
    ]" />

    <!-- Staff Profile Detail Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                <!-- Page Header Component inside Card Header -->
                <x-page-header 
                    title="Staff Details" 
                    subtitle="View profile and details for {{ $staff->name }}."
                    icon="fa-solid fa-user-gear" 
                >
                    <x-slot:actions>
                        @can('staff.edit')
                            <x-button.primary href="{{ route('staff.edit', $staff->id) }}" icon="fa-regular fa-pen-to-square me-1" label="Edit" />
                        @endcan
                        <x-button.secondary href="{{ route('staff.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <!-- Profile Banner & Hero Header -->
                <div class="position-relative">
                    <div class="profile-cover-banner"></div>
                    <div class="px-4 pb-4 text-center border-bottom bg-body position-relative">
                        <div class="profile-avatar-wrapper mb-3">
                            @php
                                $avatarSrc = $staff->avatar
                                    ? asset('storage/' . $staff->avatar)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($staff->name) . '&background=6366F1&color=fff';
                            @endphp
                            <img src="{{ $avatarSrc }}" class="profile-avatar-img" alt="{{ $staff->name }}">
                        </div>
                        <h3 class="fw-bold mb-1 text-body-emphasis">{{ $staff->name }}</h3>
                        <p class="text-secondary mb-3 small fw-medium d-flex align-items-center justify-content-center gap-2">
                            <span><i class="fa-solid fa-briefcase text-primary me-1 opacity-75"></i>{{ $staff->position ?? 'Staff Member' }}</span>
                            <span class="text-muted">•</span>
                            <span><i class="fa-solid fa-building text-purple me-1 opacity-75" style="color: #8b5cf6;"></i>{{ $staff->department ?? 'General' }}</span>
                        </p>

                        <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                            <x-badge.status :value="$staff->position ?? $staff->role_name" type="role" class="shadow-sm">
                                <i class="fa-solid fa-user-shield me-1"></i>{{ ucfirst($staff->position ?? $staff->role_name) }}
                            </x-badge.status>

                            @if($staff->status === 'active')
                                <x-badge.status value="active" class="shadow-sm">
                                    <span class="pulse-dot active"></span> Active Status
                                </x-badge.status>
                            @else
                                <x-badge.status value="inactive" class="shadow-sm">
                                    <span class="pulse-dot inactive"></span> Inactive
                                </x-badge.status>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="card-body p-4 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                        <h6 class="fw-bold mb-0 text-body-emphasis d-flex align-items-center gap-2">
                            <i class="fa-solid fa-id-badge text-primary"></i> Account Information
                        </h6>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-semibold fs-xs">
                            System ID #{{ $staff->id }}
                        </span>
                    </div>

                    <div class="row g-3 g-md-4">
                        <!-- Email Address -->
                        <div class="col-md-6">
                            <div class="info-card d-flex align-items-center gap-3">
                                <div class="icon-box icon-box-indigo">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Email Address</div>
                                    <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">{{ $staff->email }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <div class="info-card d-flex align-items-center gap-3">
                                <div class="icon-box icon-box-emerald">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Phone Number</div>
                                    <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">{{ $staff->phone ?? 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="col-md-6">
                            <div class="info-card d-flex align-items-center gap-3">
                                <div class="icon-box icon-box-purple">
                                    <i class="fa-solid fa-building-user"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Department</div>
                                    <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">{{ $staff->department ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- System Role -->
                        <div class="col-md-6">
                            <div class="info-card d-flex align-items-center gap-3">
                                <div class="icon-box icon-box-amber">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">System Role</div>
                                    <div class="fw-bold text-body-emphasis text-capitalize text-truncate" style="font-size: 0.925rem;">{{ $staff->role_name }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Joined Date -->
                        <div class="col-md-6">
                            <div class="info-card d-flex align-items-center gap-3">
                                <div class="icon-box icon-box-sky">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Joined Date</div>
                                    <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">
                                        {{ $staff->joined_date ? \Carbon\Carbon::parse($staff->joined_date)->format('F d, Y') : $staff->created_at->format('F d, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Staff Record ID -->
                        <div class="col-md-6">
                            <div class="info-card d-flex align-items-center gap-3">
                                <div class="icon-box icon-box-rose">
                                    <i class="fa-solid fa-id-card"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Staff Record ID</div>
                                    <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">ID #{{ $staff->id }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
