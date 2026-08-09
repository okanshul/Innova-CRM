document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('staffTableBody');
    const selectAllCheckbox = document.getElementById('selectAll');

    // Filter controls
    const filterDepartment = document.getElementById('filterDepartment');
    const filterStatus = document.getElementById('filterStatus');
    const perPageSelect = document.getElementById('perPage');
    const searchInput = document.getElementById('searchInput');
    const paginationSummary = document.getElementById('paginationSummary');
    const paginationControls = document.getElementById('paginationControls');

    // State
    let currentPage = 1;
    let perPage = 10;
    let currentSearch = '';
    let currentDepartment = '';
    let currentStatus = '';

    const getHeaders = () => ({
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    });

    // Helper: Department Icon
    const getDeptIcon = (dept) => {
        if (!dept) return '<i class="fa-solid fa-briefcase me-1.5" style="color:#8b5cf6;"></i> N/A';
        const lower = dept.toLowerCase();
        if (lower.includes('sales')) return `<i class="fa-solid fa-briefcase me-1.5" style="color:#8b5cf6;"></i> Sales`;
        if (lower.includes('market')) return `<i class="fa-solid fa-bullhorn me-1.5" style="color:#0284c7;"></i> Marketing`;
        if (lower.includes('support')) return `<i class="fa-solid fa-shield-cat me-1.5" style="color:#c2410c;"></i> Customer Support`;
        if (lower.includes('finan')) return `<i class="fa-solid fa-coins me-1.5" style="color:#16a34a;"></i> Finance`;
        if (lower.includes('it') || lower.includes('engine')) return `<i class="fa-solid fa-server me-1.5" style="color:#2563eb;"></i> IT`;
        return `<i class="fa-solid fa-gears me-1.5" style="color:#64748b;"></i> ${dept}`;
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

    // Load Staff Data
    const loadStaff = async (page = 1) => {
        currentPage = page;
        try {
            const params = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
                search: currentSearch,
                department: currentDepartment,
                status: currentStatus
            });

            const response = await fetch(`/api/staff?${params.toString()}`, { headers: getHeaders() });
            const data = await response.json();

            if (data.success) {
                renderTable(data.data.data);
                renderPagination(data.data);
            }
        } catch (error) {
            console.error('Error loading staff:', error);
        }
    };

    // Render Table Rows
    const renderTable = (staffList) => {
        if (!tableBody) return;
        tableBody.innerHTML = '';
        if (selectAllCheckbox) selectAllCheckbox.checked = false;

        if (!staffList || staffList.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="9" class="text-center py-5 text-secondary">No staff members found matching your criteria.</td></tr>`;
            return;
        }

        staffList.forEach(staff => {
            const tr = document.createElement('tr');
            tr.className = 'border-bottom hover-bg-light transition-colors';

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
                    <button class="action-btn action-btn-delete delete-staff" data-id="${staff.id}" title="Delete Member">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                `;
            }

            tr.innerHTML = `
                <td class="ps-4 py-3">
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
                <td class="pe-4 py-3 text-end">
                    <div class="d-inline-flex align-items-center">
                        ${actionButtons}
                    </div>
                </td>
            `;

            tableBody.appendChild(tr);
        });

        attachEventListeners();
    };

    // Render Pagination Controls
    const renderPagination = (paginationData) => {
        if (!paginationSummary || !paginationControls) return;

        const { from, to, total, current_page, last_page } = paginationData;

        if (total === 0) {
            paginationSummary.textContent = 'Showing 0 entries';
            paginationControls.innerHTML = '';
            return;
        }

        paginationSummary.textContent = `Showing ${from || 0} to ${to || 0} of ${total} entries`;

        let html = '';

        // Previous button
        html += `
            <button class="page-btn" ${current_page === 1 ? 'disabled' : ''} data-page="${current_page - 1}">
                <i class="fa-solid fa-chevron-left fs-xs"></i>
            </button>
        `;

        // Page Numbers
        for (let page = 1; page <= last_page; page++) {
            if (page === 1 || page === last_page || (page >= current_page - 1 && page <= current_page + 1)) {
                html += `
                    <button class="page-btn ${page === current_page ? 'active' : ''}" data-page="${page}">${page}</button>
                `;
            } else if (page === current_page - 2 || page === current_page + 2) {
                html += `<span class="px-1 text-secondary">...</span>`;
            }
        }

        // Next button
        html += `
            <button class="page-btn" ${current_page === last_page ? 'disabled' : ''} data-page="${current_page + 1}">
                <i class="fa-solid fa-chevron-right fs-xs"></i>
            </button>
        `;

        paginationControls.innerHTML = html;

        paginationControls.querySelectorAll('.page-btn:not(:disabled)').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const targetPage = parseInt(e.currentTarget.dataset.page);
                if (targetPage && targetPage !== currentPage) {
                    loadStaff(targetPage);
                }
            });
        });
    };

    // Bulk Delete Elements
    const btnBulkDelete = document.getElementById('btnBulkDelete');
    const selectedCountSpan = document.getElementById('selectedCount');

    // Helper: Update Bulk Delete Button State
    const updateBulkDeleteState = () => {
        const checkedBoxes = document.querySelectorAll('.staff-checkbox:checked');
        const count = checkedBoxes.length;
        const totalBoxes = document.querySelectorAll('.staff-checkbox').length;

        if (selectedCountSpan) {
            selectedCountSpan.textContent = count;
        }

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

/* ==========================================================================
   VANILLA JAVASCRIPT PERMISSIONS MATRIX LOGIC
   Handles row "All" checkboxes, global "Select All / Clear All" buttons,
   indeterminate visual states, getter/setter helpers, and event delegation.
   ========================================================================== */
const PermissionsMatrix = (() => {
    const getModule = (el) => {
        return el.dataset.module || el.dataset.group || el.getAttribute('data-module') || el.getAttribute('data-group');
    };

    const getPermissionKey = (cb) => {
        return cb.dataset.permission || cb.value;
    };

    /**
     * 1. updateRowAllState(module, container)
     * Re-evaluates that row's "All" checkbox (checked / unchecked / indeterminate).
     */
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

    /**
     * Update all module rows' "All" states in a container.
     */
    const updateAllRowStates = (container = document) => {
        const root = (container instanceof Element) ? container : document;
        const rows = root.querySelectorAll('.perm-module-row');
        rows.forEach(row => {
            const module = getModule(row);
            if (module) updateRowAllState(module, root);
        });
    };

    /**
     * 2. handleRowAllChange(rowAllCheckbox)
     */
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

    /**
     * 3. handleActionCheckboxChange(checkbox)
     * If 'view' is unchecked -> uncheck all other permissions in that module row.
     * If 'create', 'edit', or 'delete' is checked -> automatically check 'view'.
     */
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

    /**
     * 4. selectAllPermissions(container)
     */
    const selectAllPermissions = (container = document) => {
        const root = (container instanceof Element) ? container : document;
        root.querySelectorAll('.perm-checkbox:not(:disabled)').forEach(cb => {
            cb.checked = true;
        });
        root.querySelectorAll('.perm-row-all, .perm-row-select-all').forEach(cb => {
            cb.checked = true;
            cb.indeterminate = false;
        });
        updateAllRowStates(root);
    };

    /**
     * 5. clearAllPermissions(container)
     */
    const clearAllPermissions = (container = document) => {
        const root = (container instanceof Element) ? container : document;
        root.querySelectorAll('.perm-checkbox:not(:disabled)').forEach(cb => {
            cb.checked = false;
        });
        root.querySelectorAll('.perm-row-all, .perm-row-select-all').forEach(cb => {
            cb.checked = false;
            cb.indeterminate = false;
        });
        updateAllRowStates(root);
    };

    /**
     * 6. getCheckedPermissions(container)
     */
    const getCheckedPermissions = (container = document) => {
        const root = (container instanceof Element) ? container : document;
        const checkedBoxes = Array.from(root.querySelectorAll('.perm-checkbox:checked:not(:disabled)'));
        return checkedBoxes.map(cb => getPermissionKey(cb));
    };

    /**
     * 7. setCheckedPermissions(permissionsArray, container)
     */
    const setCheckedPermissions = (permissionsArray = [], container = document) => {
        const root = (container instanceof Element) ? container : document;
        const permSet = new Set(permissionsArray || []);

        root.querySelectorAll('.perm-checkbox:not(:disabled)').forEach(cb => {
            const key = getPermissionKey(cb);
            cb.checked = permSet.has(key);
        });

        updateAllRowStates(root);
    };

    /**
     * 8. Event Delegation Initialization
     */
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

// Attach to window object for external availability
window.PermissionsMatrix = PermissionsMatrix;

// Manage Permissions Matrix Rendering
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

    let html = `
        <style>
            .perm-matrix-card .form-check-input {
                width: 1rem !important;
                height: 1rem !important;
                cursor: pointer;
                margin-top: 0 !important;
            }
            .perm-matrix-card .table td {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }
        </style>
        <div class="card border rounded-3 shadow-none overflow-hidden perm-matrix-card" id="modalPermissionsMatrixContainer">
            <div class="card-header bg-body-tertiary d-flex align-items-center justify-content-between py-2.5 px-3 border-bottom">
                <div class="d-flex align-items-center gap-2 fw-semibold text-body-emphasis small">
                    <i class="fa-solid fa-shield-halved" style="color: #6366F1;"></i>
                    <span>Module Permissions Matrix</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="modalSelectAllPermissions" class="btn btn-sm btn-purple-light fw-semibold border-0 perm-global-select-all" style="font-size: 0.775rem; color: #6366F1; background-color: #f3e8ff;">
                        <i class="fa-solid fa-check-double me-1"></i> Select All Permissions
                    </button>
                    <button type="button" id="modalClearAllPermissions" class="btn btn-sm btn-light border text-secondary fw-semibold perm-global-clear-all" style="font-size: 0.775rem;">
                        <i class="fa-solid fa-xmark me-1"></i> Clear All
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="modalPermissionsMatrix" style="font-size: 0.85rem;">
                    <thead class="bg-body-tertiary border-bottom text-secondary">
                        <tr class="fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.03em;">
                            <th class="ps-3 py-2 text-uppercase">Module</th>
                            <th class="text-center py-2 text-uppercase" style="width: 15%;">View</th>
                            <th class="text-center py-2 text-uppercase" style="width: 15%;">Create</th>
                            <th class="text-center py-2 text-uppercase" style="width: 15%;">Edit</th>
                            <th class="text-center py-2 text-uppercase" style="width: 15%;">Delete</th>
                            <th class="pe-3 text-center py-2 text-uppercase" style="width: 15%;">All</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    for (const [group, perms] of Object.entries(groupedPermissions)) {
        const meta = groupMeta[group] || { title: group.charAt(0).toUpperCase() + group.slice(1), icon: 'fa-folder' };
        const groupPermMap = {};
        perms.forEach(p => {
            const parts = p.split('.');
            const act = parts[1] || '';
            groupPermMap[act] = p;
        });

        let allRowChecked = true;

        let rowCells = '';
        actions.forEach(action => {
            if (groupPermMap[action]) {
                const perm = groupPermMap[action];
                const isRole = roleSet.has(perm);
                const isDirect = directSet.has(perm);
                const isChecked = isRole || isDirect;
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

        const isRowAllChecked = allRowChecked;

        html += `
            <tr class="perm-module-row" data-module="${group}" data-group="${group}">
                <td class="ps-3 py-2 fw-semibold text-body-emphasis">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid ${meta.icon}" style="color: #6366F1; width: 16px;"></i>
                        <span>${meta.title}</span>
                    </div>
                </td>
                ${rowCells}
                <td class="pe-3 text-center py-2">
                    <div class="form-check d-inline-block m-0">
                        <input class="form-check-input custom-checkbox perm-row-all perm-row-select-all"
                               type="checkbox"
                               id="modal_row_all_${group}"
                               data-module="${group}"
                               data-group="${group}"
                               ${isRowAllChecked ? 'checked' : ''}
                               title="Select all permissions for ${meta.title}">
                    </div>
                </td>
            </tr>
        `;
    }

    html += `
                    </tbody>
                </table>
            </div>
        </div>
    `;
    return html;
};

    const permissionsModalEl = document.getElementById('permissionsModal');
    const permissionsModalForm = document.getElementById('permissionsModalForm');
    const permissionsModalSpinner = document.getElementById('permissionsModalSpinner');
    const permissionsModalContent = document.getElementById('permissionsModalContent');
    const permStaffName = document.getElementById('permStaffName');
    const permStaffRoleBadge = document.getElementById('permStaffRoleBadge');
    let permissionsModalInstance = null;

    if (permissionsModalEl && typeof bootstrap !== 'undefined') {
        permissionsModalInstance = new bootstrap.Modal(permissionsModalEl);
    }

    const openPermissionsModal = async (staffId, staffName, staffRole) => {
        if (!permissionsModalEl) return;
        if (!permissionsModalInstance && typeof bootstrap !== 'undefined') {
            permissionsModalInstance = new bootstrap.Modal(permissionsModalEl);
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
            const response = await fetch(`/api/staff/${staffId}/permissions`, { headers: getHeaders() });
            const data = await response.json();

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
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> Saving...';
            }

            try {
                // Collect checked and enabled (editable) direct permissions
                const checkedPermissions = PermissionsMatrix.getCheckedPermissions(permissionsModalForm);

                const response = await fetch(`/api/staff/${staffId}/permissions`, {
                    method: 'PUT',
                    headers: {
                        ...getHeaders(),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ permissions: checkedPermissions })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    if (permissionsModalInstance) permissionsModalInstance.hide();
                    showToast('success', data.message || 'Permissions updated successfully');
                } else {
                    const errorMsg = data.message || 'Failed to update permissions.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error!',
                            text: errorMsg,
                            icon: 'error',
                            confirmButtonColor: '#6366f1'
                        });
                    } else {
                        alert(errorMsg);
                    }
                }
            } catch (err) {
                console.error('Save permissions error:', err);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while saving permissions.',
                        icon: 'error',
                        confirmButtonColor: '#6366f1'
                    });
                } else {
                    alert('An error occurred while saving permissions.');
                }
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            }
        });
    }

    // Delegated click & change handlers for Matrix & Select All / Clear All actions
    document.addEventListener('click', (e) => {
        const globalSelectBtn = e.target.closest('.perm-global-select-all');
        if (globalSelectBtn) {
            const card = globalSelectBtn.closest('.perm-matrix-card') || document;
            card.querySelectorAll('.perm-checkbox:not(:disabled)').forEach(cb => cb.checked = true);
            card.querySelectorAll('.perm-row-select-all').forEach(cb => cb.checked = true);
            return;
        }

        const globalClearBtn = e.target.closest('.perm-global-clear-all');
        if (globalClearBtn) {
            const card = globalClearBtn.closest('.perm-matrix-card') || document;
            card.querySelectorAll('.perm-checkbox:not(:disabled)').forEach(cb => cb.checked = false);
            card.querySelectorAll('.perm-row-select-all').forEach(cb => cb.checked = false);
            return;
        }
    });

    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('perm-row-select-all')) {
            const row = e.target.closest('.perm-module-row');
            if (row) {
                const isChecked = e.target.checked;
                row.querySelectorAll('.perm-checkbox:not(:disabled)').forEach(cb => cb.checked = isChecked);
            }
            return;
        }

        if (e.target.classList.contains('perm-checkbox')) {
            const row = e.target.closest('.perm-module-row');
            if (row) {
                const rowSelectAll = row.querySelector('.perm-row-select-all');
                if (rowSelectAll) {
                    const rowCheckboxes = Array.from(row.querySelectorAll('.perm-checkbox:not(:disabled)'));
                    rowSelectAll.checked = rowCheckboxes.length > 0 && rowCheckboxes.every(cb => cb.checked);
                }
            }
        }
    });

    // Event Listeners for Dynamic Table Rows
    const attachEventListeners = () => {
        document.querySelectorAll('.manage-permissions').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const name = e.currentTarget.dataset.name;
                const role = e.currentTarget.dataset.role;
                openPermissionsModal(id, name, role);
            });
        });

        document.querySelectorAll('.delete-staff').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                deleteStaff(id);
            });
        });

        document.querySelectorAll('.staff-checkbox').forEach(cb => {
            cb.addEventListener('change', () => {
                updateBulkDeleteState();
            });
        });

        updateBulkDeleteState();
    };

    // Filter Handlers
    if (filterDepartment) {
        filterDepartment.addEventListener('change', (e) => {
            currentDepartment = e.target.value;
            loadStaff(1);
        });
    }

    if (filterStatus) {
        filterStatus.addEventListener('change', (e) => {
            currentStatus = e.target.value;
            loadStaff(1);
        });
    }

    if (perPageSelect) {
        perPageSelect.addEventListener('change', (e) => {
            perPage = parseInt(e.target.value);
            loadStaff(1);
        });
    }

    // Debounced Search Input
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = e.target.value.trim();
                loadStaff(1);
            }, 300);
        });
    }

    // Select All Checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', (e) => {
            document.querySelectorAll('.staff-checkbox').forEach(cb => {
                cb.checked = e.target.checked;
            });
            updateBulkDeleteState();
        });
    }

    // Toast Notification Helper
    const showToast = (icon, title) => {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({ icon, title });
        }
    };

    // Bulk Delete Action with SweetAlert2
    if (btnBulkDelete) {
        btnBulkDelete.addEventListener('click', async () => {
            const checkedBoxes = document.querySelectorAll('.staff-checkbox:checked');
            const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
            const count = selectedIds.length;

            if (count === 0) return;

            if (typeof Swal !== 'undefined') {
                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete ${count} selected staff member${count > 1 ? 's' : ''}!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-4 border-0 shadow',
                        confirmButton: 'btn btn-danger rounded-3 px-3.5 py-2 fw-semibold ms-2',
                        cancelButton: 'btn btn-light border rounded-3 px-3.5 py-2'
                    },
                    buttonsStyling: false
                });

                if (!result.isConfirmed) return;

                try {
                    btnBulkDelete.disabled = true;
                    btnBulkDelete.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> Deleting...';

                    const deletePromises = selectedIds.map(id =>
                        fetch(`/api/staff/${id}`, {
                            method: 'DELETE',
                            headers: getHeaders()
                        })
                    );

                    await Promise.all(deletePromises);

                    showToast('success', `${count} staff member${count > 1 ? 's' : ''} deleted successfully`);
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                    loadStaff(currentPage);
                } catch (error) {
                    console.error('Error in bulk delete:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while performing bulk delete.',
                        icon: 'error',
                        confirmButtonColor: '#6366f1'
                    });
                } finally {
                    btnBulkDelete.disabled = false;
                    btnBulkDelete.innerHTML = `<i class="fa-regular fa-trash-can pe-1"></i> Delete Selected (<span id="selectedCount">0</span>)`;
                }
            } else {
                if (!confirm(`Are you sure you want to delete ${count} selected staff member(s)?`)) return;
                try {
                    const deletePromises = selectedIds.map(id =>
                        fetch(`/api/staff/${id}`, {
                            method: 'DELETE',
                            headers: getHeaders()
                        })
                    );

                    await Promise.all(deletePromises);
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                    loadStaff(currentPage);
                } catch (error) {
                    console.error('Error in bulk delete:', error);
                }
            }
        });
    }

    // Delete Single Staff with SweetAlert2
    const deleteStaff = async (id) => {
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this staff member record!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4 border-0 shadow',
                    confirmButton: 'btn btn-danger rounded-3 px-3.5 py-2 fw-semibold ms-2',
                    cancelButton: 'btn btn-light border rounded-3 px-3.5 py-2'
                },
                buttonsStyling: false
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch(`/api/staff/${id}`, {
                    method: 'DELETE',
                    headers: getHeaders()
                });

                if (response.ok) {
                    showToast('success', 'Staff member deleted successfully');
                    loadStaff(currentPage);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while deleting.',
                        icon: 'error',
                        confirmButtonColor: '#6366f1'
                    });
                }
            } catch (error) {
                console.error('Error deleting staff:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Network error occurred.',
                    icon: 'error',
                    confirmButtonColor: '#6366f1'
                });
            }
        } else {
            if (!confirm('Are you sure you want to delete this staff member?')) return;
            try {
                const response = await fetch(`/api/staff/${id}`, {
                    method: 'DELETE',
                    headers: getHeaders()
                });

                if (response.ok) {
                    loadStaff(currentPage);
                } else {
                    alert('An error occurred while deleting.');
                }
            } catch (error) {
                console.error('Error deleting staff:', error);
            }
        }
    };

    // Initial Load if on index page table
    if (tableBody) {
        loadStaff(1);
    }
});
