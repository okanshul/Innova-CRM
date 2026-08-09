<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InnovaCRM - Edit Staff</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Inline script to apply collapsed state -->
    <script>
        (function() {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    @vite(['resources/scss/theme.scss', 'resources/js/dashboard.js'])

    <style>
        .btn-purple-primary {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            border: none !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
            transition: all 0.2s ease;
        }

        .btn-purple-primary:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.4);
        }

        .card-body .form-control, .card-body .form-select {
            border-radius: 8px !important;
            border: 1px solid #e2e8f0 !important;
            font-size: 0.875rem !important;
            padding: 0.55rem 0.85rem !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .card-body .form-control:focus, .card-body .form-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        }

        .card-body .form-control.is-invalid, .card-body .form-select.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
        }

        .invalid-feedback-ajax, .invalid-feedback {
            display: block;
            color: #ef4444;
            font-size: 0.775rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }
        .custom-form-tabs {
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .custom-form-tabs .nav-link {
            color: #64748b;
            border: none !important;
            border-bottom: 2px solid transparent !important;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.75rem 1.25rem;
            transition: all 0.15s ease;
            background: transparent !important;
        }

        .custom-form-tabs .nav-link:hover {
            color: #6366f1;
            border-bottom-color: #cbd5e1 !important;
        }

        .custom-form-tabs .nav-link.active {
            color: #6366f1 !important;
            border-bottom-color: #6366f1 !important;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <script>
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

    @include('partials.sidebar')

    <div id="main-content">
        @include('partials.header')

        <main class="flex-grow-1 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0 align-items-center" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-secondary text-decoration-none d-inline-flex align-items-center hover-primary">
                            <i class="fa-solid fa-house me-1 text-secondary" style="font-size: 0.75rem;"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('staff.index') }}" class="text-secondary text-decoration-none hover-primary">Staff</a>
                    </li>
                    <li class="breadcrumb-item active text-body-emphasis fw-semibold" aria-current="page">Edit {{ $staff->name }}</li>
                </ol>
            </nav>

            <!-- Edit Staff Form Card -->
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                        <!-- Card Header -->
                        <div class="card-header border-bottom bg-body px-4 py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center"
                                     style="background: #e0f2fe; color: #0284c7; width: 44px; height: 44px;">
                                    <i class="fa-solid fa-user-pen pe-1"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0 text-body-emphasis">Edit Staff</h4>
                                    <p class="text-secondary small mb-0">Update information for {{ $staff->name }}.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('staff.show', $staff->id) }}" class="btn btn-purple-primary border rounded-3 px-3 fs-sm text-secondary fw-medium d-inline-flex align-items-center gap-1">
                                    <i class="fa-solid fa-eye pe-1"></i> View
                                </a>
                                <a href="{{ route('staff.index') }}" class="btn btn-light border rounded-3 px-3 fs-sm text-secondary fw-medium d-inline-flex align-items-center gap-1">
                                    <i class="fa-solid fa-angle-left pe-1"></i> Back
                                </a>
                            </div>
                        </div>

                        <form id="staffEditForm" action="{{ route('api.staff.update', $staff->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nav Tabs -->
                            <ul class="nav nav-tabs custom-form-tabs px-4 pt-2 bg-body" id="editStaffTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" 
                                            id="details-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#details-pane" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="details-pane" 
                                            aria-selected="true">
                                        <i class="fa-solid fa-user-gear me-2"></i>Staff Details
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" 
                                            id="permissions-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#permissions-pane" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="permissions-pane" 
                                            aria-selected="false">
                                        <i class="fa-solid fa-key me-2" style="color: #6366F1;"></i>Permissions
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="editStaffTabsContent">
                                <!-- Tab 1: Details -->
                                <div class="tab-pane fade show active p-4" id="details-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-medium small text-secondary ps-2">
                                                <i class="fa-solid fa-user text-secondary me-1"></i> Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $staff->name) }}" placeholder="e.g. Michael Smith">
                                            @error('name')
                                                <div class="invalid-feedback ps-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-medium small text-secondary ps-2">
                                                <i class="fa-solid fa-envelope text-secondary me-1"></i> Email Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $staff->email) }}" placeholder="michael.smith@innovacrm.com">
                                            @error('email')
                                                <div class="invalid-feedback ps-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-medium small text-secondary ps-2">
                                                <i class="fa-solid fa-lock text-secondary me-1"></i> New Password
                                            </label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror" name="password" minlength="8" placeholder="Leave blank to keep current password">
                                                <button type="button" class="btn btn-link text-secondary position-absolute end-0 top-50 translate-middle-y text-decoration-none pe-3 shadow-none toggle-password-btn" style="z-index: 5;" tabindex="-1">
                                                    <i class="fa-regular fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback ps-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-medium small text-secondary ps-2">
                                                <i class="fa-solid fa-phone text-secondary me-1"></i> Phone Number
                                            </label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $staff->phone) }}" placeholder="+1 (555) 123-4567">
                                            @error('phone')
                                                <div class="invalid-feedback ps-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-medium small text-secondary ps-2">
                                                <i class="fa-solid fa-user-shield text-secondary me-1"></i> Role <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('role') is-invalid @enderror" name="role">
                                                <option value="staff" {{ old('role', $staff->role_name) === 'staff' ? 'selected' : '' }}>Staff</option>
                                                <option value="manager" {{ old('role', $staff->role_name) === 'manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="admin" {{ old('role', $staff->role_name) === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback ps-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-medium small text-secondary ps-2">
                                                <i class="fa-solid fa-briefcase text-secondary me-1"></i> Department
                                            </label>
                                            <select class="form-select @error('department') is-invalid @enderror" name="department">
                                                <option value="Sales" {{ old('department', $staff->department) === 'Sales' ? 'selected' : '' }}>Sales</option>
                                                <option value="Marketing" {{ old('department', $staff->department) === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                                <option value="Customer Support" {{ old('department', $staff->department) === 'Customer Support' ? 'selected' : '' }}>Customer Support</option>
                                                <option value="Finance" {{ old('department', $staff->department) === 'Finance' ? 'selected' : '' }}>Finance</option>
                                                <option value="IT" {{ old('department', $staff->department) === 'IT' ? 'selected' : '' }}>IT</option>
                                                <option value="Operations" {{ old('department', $staff->department) === 'Operations' ? 'selected' : '' }}>Operations</option>
                                            </select>
                                            @error('department')
                                                <div class="invalid-feedback ps-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-medium small text-secondary ps-2">
                                                <i class="fa-solid fa-id-badge text-secondary me-1"></i> Position Title
                                            </label>
                                            <input type="text" class="form-control @error('position') is-invalid @enderror" name="position" value="{{ old('position', $staff->position) }}" placeholder="e.g. Sales Executive">
                                            @error('position')
                                                <div class="invalid-feedback ps-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-medium small text-secondary ps-2">
                                                <i class="fa-solid fa-circle-check text-secondary me-1"></i> Status <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('status') is-invalid @enderror" name="status">
                                                <option value="active" {{ old('status', $staff->status) === 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $staff->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback ps-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-medium small text-secondary ps-2">
                                                <i class="fa-solid fa-calendar-days text-secondary me-1"></i> Joined Date
                                            </label>
                                            <input type="date" class="form-control @error('joined_date') is-invalid @enderror" name="joined_date" value="{{ old('joined_date', $staff->joined_date) }}">
                                            @error('joined_date')
                                                <div class="invalid-feedback ps-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab 2: Permissions -->
                                <div class="tab-pane fade p-4" id="permissions-pane" role="tabpanel" aria-labelledby="permissions-tab" tabindex="0">
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-body-emphasis mb-1">Direct Permissions</h6>
                                        <p class="text-secondary small mb-0">Grant or revoke specific individual permissions overriding or supplementing role defaults for {{ $staff->name }}.</p>
                                    </div>
                                    @include('staff.partials.permissions-accordion', [
                                        'groupedPermissions' => $groupedPermissions ?? [],
                                        'directPermissions' => old('permissions', $directPermissions ?? []),
                                        'rolePermissions' => $rolePermissions ?? [],
                                        'idPrefix' => 'edit_perm'
                                    ])
                                </div>
                            </div>

                            <!-- Card Footer with Form Actions -->
                            <div class="card-footer border-top bg-body px-4 py-3 d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('staff.index') }}" class="btn btn-light border rounded-3 px-4 py-2 fw-medium text-secondary">Cancel</a>
                                <button type="submit" class="btn btn-purple-primary rounded-3 px-4 py-2 fw-semibold">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Staff Permissions & CRUD Script -->
    <script src="{{ asset('js/staff.js') }}"></script>

    <!-- AJAX Form Submission & Input Bottom Validation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('staffEditForm');
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback-ajax').forEach(el => el.remove());

                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> Updating...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.status === 422) {
                        // Display bottom validation error for each input
                        let firstInvalid = null;
                        for (const [field, messages] of Object.entries(data.errors || {})) {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                if (!firstInvalid) firstInvalid = input;

                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback-ajax text-danger ps-2 mt-1';
                                errorDiv.textContent = messages[0];
                                input.parentNode.appendChild(errorDiv);
                            }
                        }
                        if (firstInvalid) {
                            const tabPane = firstInvalid.closest('.tab-pane');
                            if (tabPane && tabPane.id) {
                                const tabBtn = document.querySelector(`[data-bs-target="#${tabPane.id}"]`);
                                if (tabBtn && typeof bootstrap !== 'undefined') {
                                    bootstrap.Tab.getOrCreateInstance(tabBtn).show();
                                }
                            }
                            firstInvalid.focus();
                        }
                    } else if (response.ok || data.success) {
                        window.location.href = data.redirect || "{{ route('staff.index') }}";
                    } else {
                        alert(data.message || 'An error occurred. Please try again.');
                    }
                } catch (err) {
                    console.error('Submit Error:', err);
                    alert('An error occurred while updating form.');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });

            // Clear input error on typing or selection
            form.querySelectorAll('.form-control, .form-select').forEach(input => {
                const clearError = function() {
                    this.classList.remove('is-invalid');
                    const container = this.closest('.position-relative') || this.parentNode;
                    const errDiv = container.querySelector('.invalid-feedback-ajax, .invalid-feedback') || this.parentNode.querySelector('.invalid-feedback-ajax, .invalid-feedback');
                    if (errDiv) errDiv.remove();
                };
                input.addEventListener('input', clearError);
                input.addEventListener('change', clearError);
            });

            // Password Eye Toggle
            document.querySelectorAll('.toggle-password-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentNode.querySelector('input');
                    if (!input) return;
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye', !isPassword);
                        icon.classList.toggle('fa-eye-slash', isPassword);
                    }
                });
            });
        });
    </script>
</body>

</html>
