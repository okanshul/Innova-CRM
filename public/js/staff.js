/**
 * InnovaCRM Staff Management JavaScript
 * Refactored to leverage app-utils.js (loadDataTable, bindFormSubmit, bindDeleteAction, SweetAlert2)
 */

document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('staffTableBody');
    const selectAllCheckbox = document.getElementById('selectAll');
    const btnBulkDelete = document.getElementById('btnBulkDelete');
    const selectedCountSpan = document.getElementById('selectedCount');

    // Filter controls
    const filterDepartment = document.getElementById('filterDepartment');
    const filterStatus = document.getElementById('filterStatus');
    const perPageSelect = document.getElementById('perPage');
    const searchInput = document.getElementById('searchInput');

    // State
    let currentPage = 1;
    let perPage = 10;
    let currentSearch = '';
    let currentDepartment = '';
    let currentStatus = '';

    // Helper: Department Icon
    const getDeptIcon = (dept) => {
        if (!dept) return '<i class="fa-solid fa-briefcase me-1" style="color:#8b5cf6;"></i> N/A';
        const lower = dept.toLowerCase();
        if (lower.includes('sales')) return `<i class="fa-solid fa-briefcase me-1" style="color:#8b5cf6;"></i> Sales`;
        if (lower.includes('market')) return `<i class="fa-solid fa-bullhorn me-2" style="color:#0284c7;"></i> Marketing`;
        if (lower.includes('support')) return `<i class="fa-solid fa-shield-cat me-2" style="color:#c2410c;"></i> Customer Support`;
        if (lower.includes('finan')) return `<i class="fa-solid fa-coins me-2" style="color:#16a34a;"></i> Finance`;
        if (lower.includes('it') || lower.includes('engine')) return `<i class="fa-solid fa-server me-2" style="color:#2563eb;"></i> IT`;
        return `<i class="fa-solid fa-gears me-2" style="color:#64748b;"></i> ${dept}`;
    };

    // Helper: Role Badge Class
    const getRoleBadgeClass = (pos, role) => {
        const title = (pos || role || '').toLowerCase();
        if (title.includes('manager') || title.includes('executive') || title.includes('admin')) return 'role-badge-purple';
        if (title.includes('lead') || title.includes('writer') || title.includes('market')) return 'role-badge-cyan';
        if (title.includes('agent') || title.includes('support')) return 'role-badge-orange';
        if (title.includes('accountant') || title.includes('finan')) return 'role-badge-green';
        if (title.includes('sys') || title.includes('it') || title.includes('developer')) return 'role-badge-blue';
        return 'role-badge-purple';
    };

    // Helper: Date Formatter
    const formatDate = (dateStr) => {
        if (!dateStr) return 'N/A';
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    };

    // Table Row HTML Renderer
    const renderStaffRow = (staff) => {
        const avatarSrc = staff.avatar
            ? `/storage/${staff.avatar}`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(staff.name)}&background=6366F1&color=fff`;

        const positionDisplay = staff.position || (staff.role_name ? staff.role_name.charAt(0).toUpperCase() + staff.role_name.slice(1) : 'Staff');
        const badgeClass = getRoleBadgeClass(staff.position, staff.role_name);

        const statusBadge = staff.status === 'active'
            ? `<span class="status-badge status-badge-active">Active</span>`
            : `<span class="status-badge status-badge-inactive">Inactive</span>`;

        let actionButtons = `
            <a href="/staff/${staff.id}" class="action-btn action-btn-view me-1" title="View Details">
                <i class="fa-regular fa-eye"></i>
            </a>
        `;
        if (window.userPermissions && window.userPermissions.canEdit) {
            actionButtons += `
                <button class="action-btn action-btn-perm manage-permissions me-1" data-id="${staff.id}" data-name="${staff.name}" data-role="${staff.role_name || 'staff'}" title="Manage Permissions">
                    <i class="fa-solid fa-shield-halved"></i>
                </button>
                <a href="/staff/${staff.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Member">
                    <i class="fa-regular fa-pen-to-square"></i>
                </a>
            `;
        }
        if (window.userPermissions && window.userPermissions.canDelete) {
            actionButtons += `
                <button class="action-btn action-btn-delete delete-staff" data-id="${staff.id}" data-name="${staff.name}" title="Delete Member">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            `;
        }

        return `
            <tr class="border-bottom hover-bg-light transition-colors">
                <td class="ps-3 py-3">
                    <input type="checkbox" class="form-check-input custom-checkbox staff-checkbox" value="${staff.id}">
                </td>
                <td class="py-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="${avatarSrc}" class="rounded-circle object-fit-cover shadow-sm" width="38" height="38" alt="${staff.name}">
                        <div>
                            <div class="fw-semibold text-body-emphasis" style="font-size: 0.875rem;">${staff.name}</div>
                            <div class="text-secondary" style="font-size: 0.775rem;">${staff.email}</div>
                        </div>
                    </div>
                </td>
                <td class="py-3 fw-medium text-body-secondary" style="font-size: 0.85rem;">
                    ${getDeptIcon(staff.department)}
                </td>
                <td class="py-3">
                    <span class="role-badge ${badgeClass}">${positionDisplay}</span>
                </td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">
                    ${staff.email}
                </td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">
                    ${staff.phone || '+1 (555) 000-0000'}
                </td>
                <td class="py-3">
                    ${statusBadge}
                </td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">
                    ${formatDate(staff.joined_date || staff.created_at)}
                </td>
                <td class="pe-3 py-3 text-end">
                    <div class="d-inline-flex align-items-center">
                        ${actionButtons}
                    </div>
                </td>
            </tr>
        `;
    };

    // Helpers for Mobile Card Design
    const getAvatarBg = (name) => {
        const colors = ['#6366F1', '#5B5FC7', '#4F46E5', '#6366F1', '#5B5FC7'];
        if (!name) return colors[0];
        let hash = 0;
        for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
        return colors[Math.abs(hash) % colors.length];
    };

    const getInitials = (name) => {
        if (!name) return 'ST';
        const parts = name.trim().split(' ');
        if (parts.length >= 2) {
            return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
        }
        return name.substring(0, 2).toUpperCase();
    };

    // Mobile Staff Card HTML Renderer
    const renderStaffMobileCard = (staff) => {
        const initials = getInitials(staff.name);
        const avatarBg = getAvatarBg(staff.name);

        const avatarHtml = staff.avatar
            ? `<img src="/storage/${staff.avatar}" class="rounded-circle object-fit-cover shadow-sm flex-shrink-0" width="42" height="42" alt="${staff.name}">`
            : `<div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white shadow-sm" style="width: 42px; height: 42px; background-color: ${avatarBg}; font-size: 0.9rem; letter-spacing: 0.5px;">${initials}</div>`;

        const statusBadge = staff.status === 'active'
            ? `<span class="badge rounded-pill fw-semibold px-2 py-1" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">Active</span>`
            : `<span class="badge rounded-pill fw-semibold px-2 py-1" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem;">Inactive</span>`;

        const positionDisplay = staff.position || (staff.role_name ? staff.role_name.charAt(0).toUpperCase() + staff.role_name.slice(1) : 'Staff');
        const badgeClass = getRoleBadgeClass(staff.position, staff.role_name);
        const collapseId = `staffCollapse_${staff.id}`;

        let actionButtonsHtml = `
            <a href="/staff/${staff.id}" class="action-btn action-btn-view me-1" title="View Details">
                <i class="fa-regular fa-eye"></i>
            </a>
        `;
        if (window.userPermissions && window.userPermissions.canEdit) {
            actionButtonsHtml += `
                <button class="action-btn action-btn-perm manage-permissions me-1" data-id="${staff.id}" data-name="${staff.name}" data-role="${staff.role_name || 'staff'}" title="Manage Permissions">
                    <i class="fa-solid fa-shield-halved"></i>
                </button>
                <a href="/staff/${staff.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Member">
                    <i class="fa-regular fa-pen-to-square"></i>
                </a>
            `;
        }
        if (window.userPermissions && window.userPermissions.canDelete) {
            actionButtonsHtml += `
                <button class="action-btn action-btn-delete delete-staff" data-id="${staff.id}" data-name="${staff.name}" title="Delete Member">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            `;
        }

        return `
            <div class="border-bottom mobile-card-item bg-body" style="min-width: 0;">
                <div class="d-flex align-items-center justify-content-between p-3 mobile-card-header" 
                     data-bs-target="#${collapseId}" 
                     aria-expanded="false" 
                     aria-controls="${collapseId}"
                     style="cursor: pointer; min-width: 0;">
                    
                    <div class="d-flex align-items-center gap-3 min-w-0 flex-grow-1 me-2" style="min-width: 0;">
                        ${avatarHtml}
                        <div class="min-w-0 flex-grow-1" style="min-width: 0;">
                            <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.95rem;">
                                ${staff.name}
                            </div>
                            <div class="text-secondary text-truncate" style="font-size: 0.825rem;">
                                ${staff.email}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
                        ${statusBadge}
                        <button class="btn text-secondary p-0 border-0 shadow-none text-decoration-none lh-1 mobile-action-toggle ms-1" 
                                type="button" 
                                aria-label="Toggle Staff Details">
                            <i class="fa-solid fa-chevron-right chevron-icon" style="color: #6366f1; font-size: 0.85rem;"></i>
                        </button>
                    </div>
                </div>

                <div class="collapse" id="${collapseId}">
                    <div class="p-3 py-2 bg-body border-top" style="font-size: 0.825rem;">
                        <!-- Department Row -->
                        <div class="d-flex align-items-center justify-content-between py-1">
                            <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-briefcase me-1" style="color: #6366f1; width: 16px;"></i> Department :
                            </div>
                            <div class="fw-medium text-body-secondary text-end">
                                ${staff.department || '-'}
                            </div>
                        </div>

                        <!-- Role Row -->
                        <div class="d-flex align-items-center justify-content-between py-1">
                            <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-user me-1" style="color: #6366f1; width: 16px;"></i> Role :
                            </div>
                            <div class="text-end">
                                <span class="role-badge ${badgeClass}">${positionDisplay}</span>
                            </div>
                        </div>

                        <!-- Phone Row -->
                        <div class="d-flex align-items-center justify-content-between py-1">
                            <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-phone me-1" style="color: #6366f1; width: 16px;"></i> Phone No. :
                            </div>
                            <div class="fw-medium text-end" style="color: #0284c7;">
                                ${staff.phone || '+1 (555) 000-0000'}
                            </div>
                        </div>

                        <!-- Joined Row -->
                        <div class="d-flex align-items-center justify-content-between py-1">
                            <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-calendar-days me-1" style="color: #6366f1; width: 16px;"></i> Joined On :
                            </div>
                            <div class="fw-medium text-body-secondary text-end">
                                ${formatDate(staff.joined_date || staff.created_at)}
                            </div>
                        </div>

                        <!-- Actions Row -->
                        <div class="d-flex align-items-center justify-content-between py-1">
                            <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-gear me-1" style="color: #6366f1; width: 16px;"></i> Actions :
                            </div>
                            <div class="d-inline-flex align-items-center ms-auto">
                                ${actionButtonsHtml}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    };

    // Load Staff Table Data via loadDataTable Helper
    const fetchStaffTable = (page = currentPage) => {
        currentPage = page;
        if (!tableBody) return;

        loadDataTable({
            url: '/api/staff',
            tableBodyId: 'staffTableBody',
            summaryId: 'paginationSummary',
            controlsId: 'paginationControls',
            page: currentPage,
            perPage: perPage,
            params: {
                search: currentSearch,
                department: currentDepartment,
                status: currentStatus
            },
            emptyMessage: 'No staff members found matching your criteria.',
            rowRenderer: renderStaffRow,
            onRendered: (items) => {
                if (selectAllCheckbox) selectAllCheckbox.checked = false;

                // Render Mobile Staff Cards
                const mobileCardList = document.getElementById('staffMobileCardList');
                const mobileSummary = document.getElementById('mobilePaginationSummary');
                const mobileControls = document.getElementById('mobilePaginationControls');
                const desktopSummary = document.getElementById('paginationSummary');
                const desktopControls = document.getElementById('paginationControls');

                if (mobileCardList) {
                    mobileCardList.innerHTML = '';
                    if (!items || items.length === 0) {
                        mobileCardList.innerHTML = getEmptyStateHtml({ title: 'No staff found', module: 'staff' });
                        const clearBtn = mobileCardList.querySelector('.btn-clear-filters-action');
                        if (clearBtn) {
                            clearBtn.addEventListener('click', () => {
                                const resetTrigger = document.getElementById('btnFilterTrigger') || document.getElementById('btnResetFilters');
                                if (resetTrigger) resetTrigger.click();
                            });
                        }
                    } else {
                        items.forEach(staff => {
                            mobileCardList.insertAdjacentHTML('beforeend', renderStaffMobileCard(staff));
                        });
                    }
                }

                if (mobileSummary && desktopSummary) {
                    mobileSummary.textContent = desktopSummary.textContent;
                }
                if (mobileControls && desktopControls) {
                    mobileControls.innerHTML = desktopControls.innerHTML;
                    if (!mobileControls.dataset.bound) {
                        mobileControls.dataset.bound = 'true';
                        mobileControls.addEventListener('click', (e) => {
                            const btn = e.target.closest('.page-btn');
                            if (btn && !btn.disabled && btn.dataset.page) {
                                fetchStaffTable(parseInt(btn.dataset.page));
                            }
                        });
                    }
                }

                attachEventListeners();
            }
        });
    };

    // Update Bulk Delete Button State
    const updateBulkDeleteState = () => {
        const checkedBoxes = document.querySelectorAll('.staff-checkbox:checked');
        const count = checkedBoxes.length;
        const totalBoxes = document.querySelectorAll('.staff-checkbox').length;

        if (selectedCountSpan) selectedCountSpan.textContent = count;

        if (btnBulkDelete) {
            if (count > 0) {
                btnBulkDelete.classList.remove('d-none');
                btnBulkDelete.classList.add('d-inline-flex');
            } else {
                btnBulkDelete.classList.add('d-none');
                btnBulkDelete.classList.remove('d-inline-flex');
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (totalBoxes > 0 && count === totalBoxes);
            selectAllCheckbox.indeterminate = (count > 0 && count < totalBoxes);
        }
    };

    // Attach Dynamic Row Listeners
    const attachEventListeners = () => {
        document.querySelectorAll('.manage-permissions').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const name = e.currentTarget.dataset.name;
                const role = e.currentTarget.dataset.role;
                openPermissionsModal(id, name, role);
            });
        });

        document.querySelectorAll('.staff-checkbox').forEach(cb => {
            cb.addEventListener('change', () => {
                updateBulkDeleteState();
            });
        });

        updateBulkDeleteState();
    };

    // Explicit click handler for mobile card header collapse toggle
    document.addEventListener('click', function (e) {
        const header = e.target.closest('.mobile-card-header');
        if (!header) return;

        const targetId = header.getAttribute('data-bs-target');
        if (targetId) {
            const collapseEl = document.querySelector(targetId);
            if (collapseEl && typeof bootstrap !== 'undefined') {
                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl);
                bsCollapse.toggle();
            }
        }
    });

    // Bootstrap Collapse events to sync chevron icon toggle state
    document.addEventListener('show.bs.collapse', function (e) {
        const item = e.target.closest('.mobile-card-item');
        if (item) {
            const header = item.querySelector('.mobile-card-header');
            const toggle = item.querySelector('.mobile-action-toggle');
            if (header) header.setAttribute('aria-expanded', 'true');
            if (toggle) toggle.setAttribute('aria-expanded', 'true');
        }
    });

    document.addEventListener('hide.bs.collapse', function (e) {
        const item = e.target.closest('.mobile-card-item');
        if (item) {
            const header = item.querySelector('.mobile-card-header');
            const toggle = item.querySelector('.mobile-action-toggle');
            if (header) header.setAttribute('aria-expanded', 'false');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Single Staff Delete via bindDeleteAction
    bindDeleteAction({
        selector: '.delete-staff',
        url: (id) => `/api/staff/${id}`,
        tableReloadFn: () => fetchStaffTable(currentPage),
        itemNameAttr: 'data-name'
    });

    // Bind Staff Create Form via AJAX
    bindFormSubmit({
        formId: 'staffCreateForm',
        showToast: true,
        onSuccess: (data) => {
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 800);
            }
        }
    });

    // Bind Staff Edit Form via AJAX
    bindFormSubmit({
        formId: 'staffEditForm',
        showToast: true,
        onSuccess: (data) => {
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 800);
            }
        }
    });

    // Bulk Delete Action with SweetAlert2 & apiRequest
    if (btnBulkDelete) {
        btnBulkDelete.addEventListener('click', async () => {
            const checkedBoxes = document.querySelectorAll('.staff-checkbox:checked');
            const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
            const count = selectedIds.length;

            if (count === 0) return;

            const confirmed = await confirmDelete(`${count} selected staff member${count > 1 ? 's' : ''}`);
            if (!confirmed) return;

            try {
                btnBulkDelete.disabled = true;
                btnBulkDelete.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Deleting...';

                const deletePromises = selectedIds.map(id => apiRequest(`/api/staff/${id}`, { method: 'DELETE' }));
                await Promise.all(deletePromises);

                showSuccessToast(`${count} staff member${count > 1 ? 's' : ''} deleted successfully`);
                if (selectAllCheckbox) selectAllCheckbox.checked = false;
                fetchStaffTable(currentPage);
            } catch (error) {
                console.error('Error in bulk delete:', error);
                showErrorToast(error.message || 'An error occurred while performing bulk delete.');
            } finally {
                btnBulkDelete.disabled = false;
                btnBulkDelete.innerHTML = `<i class="fa-regular fa-trash-can pe-1"></i> Delete Selected (<span id="selectedCount">0</span>)`;
            }
        });
    }

    // Filter Handlers
    if (filterDepartment) {
        filterDepartment.addEventListener('change', (e) => {
            currentDepartment = e.target.value;
            fetchStaffTable(1);
        });
    }

    if (filterStatus) {
        filterStatus.addEventListener('change', (e) => {
            currentStatus = e.target.value;
            fetchStaffTable(1);
        });
    }

    if (perPageSelect) {
        perPageSelect.addEventListener('change', (e) => {
            perPage = parseInt(e.target.value);
            fetchStaffTable(1);
        });
    }

    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = e.target.value.trim();
                fetchStaffTable(1);
            }, 300);
        });
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', (e) => {
            document.querySelectorAll('.staff-checkbox').forEach(cb => {
                cb.checked = e.target.checked;
            });
            updateBulkDeleteState();
        });
    }

    const btnFilterTrigger = document.getElementById('btnFilterTrigger');
    if (btnFilterTrigger) {
        btnFilterTrigger.addEventListener('click', () => {
            if (filterDepartment) filterDepartment.value = '';
            if (filterStatus) filterStatus.value = '';
            if (searchInput) searchInput.value = '';
            currentDepartment = '';
            currentStatus = '';
            currentSearch = '';
            fetchStaffTable(1);
        });
    }

    const btnExport = document.getElementById('btnExport');
    if (btnExport) {
        btnExport.addEventListener('click', async () => {
            try {
                btnExport.disabled = true;
                btnExport.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> <span>Exporting...</span>`;
                const params = new URLSearchParams({
                    search: currentSearch,
                    department: currentDepartment,
                    status: currentStatus,
                    per_page: 1000
                });
                const response = await fetch(`/api/staff?${params.toString()}`);
                const resData = await response.json();
                const items = resData.data || resData;

                if (!Array.isArray(items) || items.length === 0) {
                    if (typeof showErrorToast === 'function') showErrorToast('No staff data available to export.');
                    return;
                }

                const headers = ['ID', 'Name', 'Email', 'Phone', 'Department', 'Role/Position', 'Status', 'Joined Date'];
                const csvRows = [headers.join(',')];

                items.forEach(staff => {
                    const row = [
                        staff.id,
                        `"${(staff.name || '').replace(/"/g, '""')}"`,
                        `"${(staff.email || '').replace(/"/g, '""')}"`,
                        `"${(staff.phone || '').replace(/"/g, '""')}"`,
                        `"${(staff.department || '').replace(/"/g, '""')}"`,
                        `"${(staff.position || staff.role_name || '').replace(/"/g, '""')}"`,
                        staff.status || '',
                        `"${staff.joined_date || staff.created_at || ''}"`
                    ];
                    csvRows.push(row.join(','));
                });

                const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `staff_export_${new Date().toISOString().slice(0, 10)}.csv`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            } catch (err) {
                console.error('Export failed:', err);
            } finally {
                btnExport.disabled = false;
                btnExport.innerHTML = `<i class="fa-solid fa-download"></i> <span>Export</span>`;
            }
        });
    }

    /* ==========================================================================
       PERMISSIONS MATRIX & MODAL LOGIC
       ========================================================================== */
    const PermissionsMatrix = (() => {
        const getModule = (el) => el.dataset.module || el.dataset.group || el.getAttribute('data-module') || el.getAttribute('data-group');
        const getPermissionKey = (cb) => cb.dataset.permission || cb.value;

        const updateRowAllState = (module, container = document) => {
            if (!module) return;
            const root = (container instanceof Element) ? container : document;
            const row = root.querySelector(`.perm-module-row[data-module="${module}"], .perm-module-row[data-group="${module}"]`);
            if (!row) return;

            const rowAllCheckbox = row.querySelector('.perm-row-all, .perm-row-select-all');
            if (!rowAllCheckbox) return;

            const rowCheckboxes = Array.from(row.querySelectorAll('.perm-checkbox'));
            if (rowCheckboxes.length === 0) {
                rowAllCheckbox.checked = false;
                rowAllCheckbox.indeterminate = false;
                return;
            }

            const checkedCount = rowCheckboxes.filter(cb => cb.checked).length;
            const totalCount = rowCheckboxes.length;

            if (checkedCount === totalCount) {
                rowAllCheckbox.checked = true;
                rowAllCheckbox.indeterminate = false;
            } else if (checkedCount === 0) {
                rowAllCheckbox.checked = false;
                rowAllCheckbox.indeterminate = false;
            } else {
                rowAllCheckbox.checked = false;
                rowAllCheckbox.indeterminate = true;
            }
        };

        const updateAllRowStates = (container = document) => {
            const root = (container instanceof Element) ? container : document;
            const rows = root.querySelectorAll('.perm-module-row');
            rows.forEach(row => {
                const module = getModule(row);
                if (module) updateRowAllState(module, root);
            });
        };

        const handleRowAllChange = (rowAllCheckbox) => {
            const module = getModule(rowAllCheckbox);
            const row = rowAllCheckbox.closest('.perm-module-row') || document;
            const isChecked = rowAllCheckbox.checked;

            rowAllCheckbox.indeterminate = false;
            row.querySelectorAll(`.perm-checkbox[data-module="${module}"]:not(:disabled), .perm-checkbox[data-group="${module}"]:not(:disabled)`).forEach(cb => {
                cb.checked = isChecked;
            });

            const container = rowAllCheckbox.closest('.perm-matrix-card') || rowAllCheckbox.closest('form') || document;
            updateRowAllState(module, container);
        };

        const handleActionCheckboxChange = (checkbox) => {
            const module = getModule(checkbox);
            const action = checkbox.dataset.action || (checkbox.dataset.permission ? checkbox.dataset.permission.split('.')[1] : '');
            const row = checkbox.closest('.perm-module-row') || document;
            const container = checkbox.closest('.perm-matrix-card') || checkbox.closest('form') || document;

            if (action === 'view' && !checkbox.checked) {
                row.querySelectorAll(`.perm-checkbox[data-module="${module}"]:not(:disabled), .perm-checkbox[data-group="${module}"]:not(:disabled)`).forEach(cb => {
                    cb.checked = false;
                });
            } else if ((action === 'create' || action === 'edit' || action === 'delete') && checkbox.checked) {
                const viewCheckbox = row.querySelector(`.perm-checkbox[data-action="view"]`);
                if (viewCheckbox && !viewCheckbox.disabled) {
                    viewCheckbox.checked = true;
                }
            }

            updateRowAllState(module, container);
        };

        const selectAllPermissions = (container = document) => {
            const root = (container instanceof Element) ? container : document;
            root.querySelectorAll('.perm-checkbox:not(:disabled)').forEach(cb => cb.checked = true);
            root.querySelectorAll('.perm-row-all, .perm-row-select-all').forEach(cb => {
                cb.checked = true;
                cb.indeterminate = false;
            });
            updateAllRowStates(root);
        };

        const clearAllPermissions = (container = document) => {
            const root = (container instanceof Element) ? container : document;
            root.querySelectorAll('.perm-checkbox:not(:disabled)').forEach(cb => cb.checked = false);
            root.querySelectorAll('.perm-row-all, .perm-row-select-all').forEach(cb => {
                cb.checked = false;
                cb.indeterminate = false;
            });
            updateAllRowStates(root);
        };

        const getCheckedPermissions = (container = document) => {
            const root = (container instanceof Element) ? container : document;
            const checkedBoxes = Array.from(root.querySelectorAll('.perm-checkbox:checked:not(:disabled)'));
            const keys = checkedBoxes.map(cb => getPermissionKey(cb));
            return Array.from(new Set(keys));
        };

        const setCheckedPermissions = (permissionsArray = [], container = document) => {
            const root = (container instanceof Element) ? container : document;
            const permSet = new Set(permissionsArray || []);
            root.querySelectorAll('.perm-checkbox:not(:disabled)').forEach(cb => {
                const key = getPermissionKey(cb);
                cb.checked = permSet.has(key);
            });
            updateAllRowStates(root);
        };

        const init = () => {
            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('perm-checkbox')) {
                    handleActionCheckboxChange(e.target);
                } else if (e.target.classList.contains('perm-row-all') || e.target.classList.contains('perm-row-select-all')) {
                    handleRowAllChange(e.target);
                }
            });

            document.addEventListener('click', (e) => {
                const selectAllBtn = e.target.closest('#selectAllPermissions, .perm-global-select-all');
                if (selectAllBtn) {
                    e.preventDefault();
                    const container = selectAllBtn.closest('.perm-matrix-card') || selectAllBtn.closest('form') || document;
                    selectAllPermissions(container);
                    return;
                }

                const clearAllBtn = e.target.closest('#clearAllPermissions, .perm-global-clear-all');
                if (clearAllBtn) {
                    e.preventDefault();
                    const container = clearAllBtn.closest('.perm-matrix-card') || clearAllBtn.closest('form') || document;
                    clearAllPermissions(container);
                    return;
                }
            });

            updateAllRowStates();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        return {
            updateRowAllState,
            handleRowAllChange,
            handleActionCheckboxChange,
            selectAllPermissions,
            clearAllPermissions,
            getCheckedPermissions,
            setCheckedPermissions,
            updateAllRowStates
        };
    })();

    window.PermissionsMatrix = PermissionsMatrix;

    // Permissions Modal Elements & Logic
    const permissionsModalEl = document.getElementById('permissionsModal');
    const permissionsModalForm = document.getElementById('permissionsModalForm');
    const permissionsModalSpinner = document.getElementById('permissionsModalSpinner');
    const permissionsModalContent = document.getElementById('permissionsModalContent');
    const permStaffName = document.getElementById('permStaffName');
    const permStaffRoleBadge = document.getElementById('permStaffRoleBadge');
    let permissionsModalInstance = null;

    if (permissionsModalEl && typeof bootstrap !== 'undefined') {
        permissionsModalInstance = bootstrap.Modal.getOrCreateInstance(permissionsModalEl);
    }

    const groupMeta = {
        'staff': { title: 'Staff Management', icon: 'fa-user-group' },
        'contacts': { title: 'Contacts', icon: 'fa-address-book' },
        'deals': { title: 'Deals', icon: 'fa-handshake' },
        'pipeline': { title: 'Pipeline', icon: 'fa-diagram-project' },
        'reports': { title: 'Reports', icon: 'fa-chart-pie' },
        'tasks': { title: 'Tasks', icon: 'fa-list-check' },
        'settings': { title: 'Settings', icon: 'fa-gear' }
    };

    const renderPermissionsModalBody = (groupedPermissions, directPermissions, rolePermissions) => {
        const directSet = new Set(directPermissions || []);
        const roleSet = new Set(rolePermissions || []);
        const actions = ['view', 'create', 'edit', 'delete'];

        let tableRowsHtml = '';

        for (const [group, perms] of Object.entries(groupedPermissions)) {
            const meta = groupMeta[group] || { title: group.charAt(0).toUpperCase() + group.slice(1), icon: 'fa-folder' };
            const groupPermMap = {};
            perms.forEach(p => {
                const parts = p.split('.');
                groupPermMap[parts[1] || ''] = p;
            });

            let allRowChecked = true;
            let rowCells = '';

            actions.forEach(action => {
                if (groupPermMap[action]) {
                    const perm = groupPermMap[action];
                    const isChecked = roleSet.has(perm) || directSet.has(perm);
                    const inputId = `modal_perm_${perm.replace('.', '_')}`;

                    if (!isChecked) allRowChecked = false;

                    rowCells += `
                        <td class="text-center py-2">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <input class="form-check-input custom-checkbox perm-checkbox"
                                       type="checkbox"
                                       name="permissions[]"
                                       value="${perm}"
                                       id="${inputId}"
                                       data-module="${group}"
                                       data-group="${group}"
                                       data-action="${action}"
                                       data-permission="${perm}"
                                       ${isChecked ? 'checked' : ''}>
                            </div>
                        </td>
                    `;
                } else {
                    rowCells += `<td class="text-center py-2"><span class="text-body-tertiary fw-light">—</span></td>`;
                }
            });

            tableRowsHtml += `
                <tr class="perm-module-row" data-module="${group}" data-group="${group}">
                    <td class="ps-3 py-3 fw-semibold text-body-emphasis">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid ${meta.icon}" style="color: #6366F1; width: 18px; font-size: 0.95rem;"></i>
                            <span class="text-nowrap" style="font-size: 0.875rem;">${meta.title}</span>
                        </div>
                    </td>
                    ${rowCells}
                    <td class="pe-3 text-center py-3">
                        <div class="form-check d-inline-block m-0">
                            <input class="form-check-input custom-checkbox perm-row-all perm-row-select-all"
                                   type="checkbox"
                                   id="modal_row_all_${group}"
                                   data-module="${group}"
                                   data-group="${group}"
                                   ${allRowChecked ? 'checked' : ''}
                                   title="Select all permissions for ${meta.title}">
                        </div>
                    </td>
                </tr>
            `;
        }

        let html = `
            <div class="card border rounded-3 shadow-none overflow-hidden perm-matrix-card" id="modalPermissionsMatrixContainer">
                <div class="card-header bg-body-tertiary d-flex flex-column flex-sm-row align-items-sm-center justify-content-between p-3 py-2 border-bottom gap-2">
                    <div class="d-flex align-items-center gap-2 fw-semibold text-body-emphasis small">
                        <i class="fa-solid fa-shield-halved" style="color: #6366F1;"></i>
                        <span class="text-nowrap">Module Permissions Matrix</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-start justify-content-sm-end">
                        <button type="button" id="modalSelectAllPermissions" class="btn btn-sm btn-purple-light fw-semibold border-0 perm-global-select-all flex-fill flex-sm-grow-0" style="font-size: 0.775rem; color: #6366F1; background-color: #f3e8ff; padding: 0.35rem 0.65rem;">
                            <i class="fa-solid fa-check-double me-1"></i> Select All <span class="d-none d-md-inline">Permissions</span>
                        </button>
                        <button type="button" id="modalClearAllPermissions" class="btn btn-sm btn-light border text-secondary fw-semibold perm-global-clear-all flex-fill flex-sm-grow-0" style="font-size: 0.775rem; padding: 0.35rem 0.65rem;">
                            <i class="fa-solid fa-xmark me-1"></i> Clear All
                        </button>
                    </div>
                </div>
                <!-- Table View for Mobile & Desktop -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="modalPermissionsMatrix" style="font-size: 0.85rem; min-width: 520px;">
                        <thead class="bg-body-tertiary border-bottom text-secondary">
                            <tr class="fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.03em;">
                                <th class="ps-3 py-2 text-uppercase">Module</th>
                                <th class="text-center py-2 text-uppercase" style="width: 14%;">View</th>
                                <th class="text-center py-2 text-uppercase" style="width: 14%;">Create</th>
                                <th class="text-center py-2 text-uppercase" style="width: 14%;">Edit</th>
                                <th class="text-center py-2 text-uppercase" style="width: 14%;">Delete</th>
                                <th class="pe-3 text-center py-2 text-uppercase" style="width: 14%;">All</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRowsHtml}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        return html;
    };

    const openPermissionsModal = async (staffId, staffName, staffRole) => {
        if (!permissionsModalEl) return;
        if (!permissionsModalInstance && typeof bootstrap !== 'undefined') {
            permissionsModalInstance = bootstrap.Modal.getOrCreateInstance(permissionsModalEl);
        }

        if (permStaffName) permStaffName.textContent = staffName || 'Staff Member';
        if (permStaffRoleBadge) {
            permStaffRoleBadge.textContent = `Role: ${(staffRole || 'staff').charAt(0).toUpperCase() + (staffRole || 'staff').slice(1)}`;
        }
        if (permissionsModalForm) permissionsModalForm.dataset.staffId = staffId;

        if (permissionsModalSpinner) permissionsModalSpinner.classList.remove('d-none');
        if (permissionsModalContent) {
            permissionsModalContent.classList.add('d-none');
            permissionsModalContent.innerHTML = '';
        }

        if (permissionsModalInstance) permissionsModalInstance.show();

        try {
            const data = await apiRequest(`/api/staff/${staffId}/permissions`);

            if (data.success) {
                const { user, grouped_permissions, direct_permissions, role_permissions } = data.data;
                if (permStaffName) permStaffName.textContent = user.name;
                if (permStaffRoleBadge) {
                    permStaffRoleBadge.textContent = `Role: ${user.role_name.charAt(0).toUpperCase() + user.role_name.slice(1)}`;
                }

                if (permissionsModalContent) {
                    permissionsModalContent.innerHTML = renderPermissionsModalBody(
                        grouped_permissions,
                        direct_permissions,
                        role_permissions
                    );
                    permissionsModalContent.classList.remove('d-none');
                }
            } else {
                if (permissionsModalContent) {
                    permissionsModalContent.innerHTML = `<div class="alert alert-danger mb-0">${data.message || 'Failed to load permissions.'}</div>`;
                    permissionsModalContent.classList.remove('d-none');
                }
            }
        } catch (err) {
            console.error('Error fetching permissions:', err);
            if (permissionsModalContent) {
                permissionsModalContent.innerHTML = `<div class="alert alert-danger mb-0">Error loading permissions data.</div>`;
                permissionsModalContent.classList.remove('d-none');
            }
        } finally {
            if (permissionsModalSpinner) permissionsModalSpinner.classList.add('d-none');
        }
    };

    if (permissionsModalForm) {
        permissionsModalForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const staffId = permissionsModalForm.dataset.staffId;
            if (!staffId) return;

            const submitBtn = document.getElementById('btnSavePermissions');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Save Permissions';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...';
            }

            try {
                const checkedPermissions = PermissionsMatrix.getCheckedPermissions(permissionsModalForm);
                const data = await apiRequest(`/api/staff/${staffId}/permissions`, {
                    method: 'PUT',
                    body: JSON.stringify({ permissions: checkedPermissions })
                });

                if (permissionsModalInstance) permissionsModalInstance.hide();
                showSuccessToast(data.message || 'Permissions updated successfully');

            } catch (err) {
                console.error('Save permissions error:', err);
                showErrorToast(err.message || 'An error occurred while saving permissions.');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            }
        });
    }

    // Password Eye Toggle Handler for Forms
    document.querySelectorAll('.toggle-password-btn').forEach(btn => {
        btn.addEventListener('click', function () {
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

    // Initial Load for Staff Table
    if (tableBody) {
        fetchStaffTable(1);
    }
});
