@extends('layouts.app', ['title' => 'InnovaCRM - Edit Staff'])

@section('content')
    <!-- Breadcrumb Component -->
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Staff', 'url' => route('staff.index')],
        ['label' => 'Edit ' . $staff->name],
    ]" />

    <!-- Edit Staff Form Card -->
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                <!-- Page Header Component inside Card Header -->
                <x-page-header title="Edit Staff" subtitle="Update information for {{ $staff->name }}."
                    icon="fa-solid fa-user-pen" iconBg="#e0f2fe" iconColor="#0284c7">
                    <x-slot:actions>
                        <x-button.primary href="{{ route('staff.show', $staff->id) }}" icon="fa-solid fa-eye pe-1" label="View" />
                        <x-button.secondary href="{{ route('staff.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="staffEditForm" action="{{ route('crm.api.staff.update', $staff->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs custom-form-tabs px-4 pt-2 bg-body" id="editStaffTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab"
                                data-bs-target="#details-pane" type="button" role="tab" aria-controls="details-pane"
                                aria-selected="true">
                                <i class="fa-solid fa-user-gear me-2"></i>Staff Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="permissions-tab" data-bs-toggle="tab"
                                data-bs-target="#permissions-pane" type="button" role="tab"
                                aria-controls="permissions-pane" aria-selected="false">
                                <i class="fa-solid fa-key me-2" style="color: #6366F1;"></i>Permissions
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="editStaffTabsContent">
                        <!-- Tab 1: Details -->
                        <div class="tab-pane fade show active p-4" id="details-pane" role="tabpanel"
                            aria-labelledby="details-tab" tabindex="0">
                            <div class="row">
                                <x-form.input class="col-md-6 mb-3" name="name" label="Full Name" icon="fa-solid fa-user"
                                    :required="true" :value="$staff->name" placeholder="e.g. Michael Smith" />
                                <x-form.input class="col-md-6 mb-3" type="email" name="email" label="Email Address"
                                    icon="fa-solid fa-envelope" :required="true" :value="$staff->email"
                                    placeholder="michael.smith@innovacrm.com" />
                                <x-form.input class="col-md-6 mb-3" type="password" name="password" label="New Password"
                                    icon="fa-solid fa-lock" minlength="8"
                                    placeholder="Leave blank to keep current password" />
                                <x-form.input class="col-md-6 mb-3" name="phone" label="Phone Number"
                                    icon="fa-solid fa-phone" :value="$staff->phone" placeholder="+1 (555) 123-4567" />

                                <x-form.select class="col-md-6 mb-3" name="role" label="Role"
                                    icon="fa-solid fa-user-shield" :required="true">
                                    <option value="staff" {{ old('role', $staff->role_name) === 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="manager" {{ old('role', $staff->role_name) === 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="admin" {{ old('role', $staff->role_name) === 'admin' ? 'selected' : '' }}>Admin</option>
                                </x-form.select>

                                <x-form.select class="col-md-6 mb-3" name="department" label="Department"
                                    icon="fa-solid fa-briefcase" :required="true">
                                    <option value="Sales" {{ old('department', $staff->department) === 'Sales' ? 'selected' : '' }}>Sales</option>
                                    <option value="Marketing" {{ old('department', $staff->department) === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                    <option value="Customer Support" {{ old('department', $staff->department) === 'Customer Support' ? 'selected' : '' }}>Customer Support</option>
                                    <option value="Finance" {{ old('department', $staff->department) === 'Finance' ? 'selected' : '' }}>Finance</option>
                                    <option value="IT" {{ old('department', $staff->department) === 'IT' ? 'selected' : '' }}>IT</option>
                                    <option value="Operations" {{ old('department', $staff->department) === 'Operations' ? 'selected' : '' }}>Operations</option>
                                </x-form.select>

                                <x-form.input class="col-md-6 mb-3" name="position" label="Position Title"
                                    icon="fa-solid fa-id-badge" :value="$staff->position" placeholder="e.g. Sales Executive" />

                                <x-form.select class="col-md-3 mb-3" name="status" label="Status"
                                    icon="fa-solid fa-circle-check" :required="true">
                                    <option value="active" {{ old('status', $staff->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $staff->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-form.select>

                                <x-form.input class="col-md-3 mb-3" type="date" name="joined_date" label="Joined Date" icon="fa-solid fa-calendar-days" :value="$staff->joined_date" />
                            </div>
                        </div>

                        <!-- Tab 2: Permissions -->
                        <div class="tab-pane fade p-4" id="permissions-pane" role="tabpanel"
                            aria-labelledby="permissions-tab" tabindex="0">
                            <div class="mb-3">
                                <h6 class="fw-bold text-body-emphasis mb-1">Direct Permissions</h6>
                                <p class="text-secondary small mb-0">Grant or revoke specific individual permissions
                                    overriding or supplementing role defaults for {{ $staff->name }}.</p>
                            </div>
                            @include('staff.partials.permissions-accordion', [
                                'groupedPermissions' => $groupedPermissions ?? [],
                                'directPermissions' => old('permissions', $directPermissions ?? []),
                                'rolePermissions' => $rolePermissions ?? [],
                                'idPrefix' => 'edit_perm',
                            ])
                        </div>
                    </div>

                    <!-- Card Footer with Actions -->
                    <div class="card-footer border-top bg-body px-4 py-3 d-flex align-items-center justify-content-end gap-2">
                        <x-button.secondary href="{{ route('staff.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Update" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/staff.js') }}"></script>
@endpush
