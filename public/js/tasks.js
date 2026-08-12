document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('tasksTableBody');
    const mobileList = document.getElementById('tasksMobileCardList');
    const searchInput = document.getElementById('searchInput');
    const filterPriority = document.getElementById('filterPriority');
    const filterStatus = document.getElementById('filterStatus');
    const perPageSelect = document.getElementById('perPage');
    const btnFilterReset = document.getElementById('btnFilterTrigger');

    const selectAllCheckbox = document.getElementById('selectAll');
    const btnBulkDelete = document.getElementById('btnBulkDelete');
    const selectedCountSpan = document.getElementById('selectedCount');

    let currentPage = 1;

    // Bind AJAX Form Submissions
    if (typeof bindFormSubmit === 'function') {
        bindFormSubmit({
            formId: 'taskCreateForm',
            url: (form) => form.action,
            method: 'POST'
        });

        bindFormSubmit({
            formId: 'taskEditForm',
            url: (form) => form.action,
            method: 'POST'
        });
    }

    function updateBulkDeleteState() {
        const checkedBoxes = document.querySelectorAll('.task-checkbox:checked');
        const count = checkedBoxes.length;
        const totalBoxes = document.querySelectorAll('.task-checkbox').length;

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
    }

    function getPriorityBadgeClass(priority) {
        const p = (priority || '').toLowerCase();
        if (p === 'urgent') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem;">URGENT</span>`;
        if (p === 'high') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #ffedd5; color: #c2410c; font-size: 0.75rem;">HIGH</span>`;
        if (p === 'medium') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #e0f2fe; color: #0369a1; font-size: 0.75rem;">MEDIUM</span>`;
        return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #f1f5f9; color: #475569; font-size: 0.75rem;">LOW</span>`;
    }

    function getStatusBadgeClass(s) {
        if (s === 'completed') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">Completed</span>`;
        if (s === 'in_progress') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #e0e7ff; color: #4338ca; font-size: 0.75rem;">In Progress</span>`;
        if (s === 'cancelled') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem;">Cancelled</span>`;
        return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #fef3c7; color: #d97706; font-size: 0.75rem;">Pending</span>`;
    }

    function syncMobilePagination() {
        const mobileSummary = document.getElementById('mobilePaginationSummary');
        const mobileControls = document.getElementById('mobilePaginationControls');
        const desktopSummary = document.getElementById('paginationSummary');
        const desktopControls = document.getElementById('paginationControls');

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
                        fetchTasks(parseInt(btn.dataset.page));
                    }
                });
            }
        }
    }

    function fetchTasks(page = 1) {
        if (!tableBody && !mobileList) return;
        currentPage = page;

        loadDataTable({
            url: '/api/tasks',
            tableBodyId: 'tasksTableBody',
            summaryId: 'paginationSummary',
            controlsId: 'paginationControls',
            page: currentPage,
            perPage: perPageSelect ? perPageSelect.value : 10,
            params: {
                search: searchInput ? searchInput.value : '',
                priority: filterPriority ? filterPriority.value : '',
                status: filterStatus ? filterStatus.value : ''
            },
            emptyMessage: 'No tasks found.',
            rowRenderer: renderTaskRow,
            onRendered: (items) => {
                renderMobile(items);
                syncMobilePagination();
                bindDeleteButtons();
                attachCheckboxListeners();
            }
        });
    }

    function renderTaskRow(item) {
        const canEdit = window.userPermissions && window.userPermissions.canEdit;
        const canDelete = window.userPermissions && window.userPermissions.canDelete;

        const editBtn = canEdit ? `
            <a href="/tasks/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Task">
                <i class="fa-regular fa-pen-to-square"></i>
            </a>
        ` : '';

        const deleteBtn = canDelete ? `
            <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.title}" title="Delete Task">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        ` : '';

        return `
            <tr class="border-bottom hover-bg-light transition-colors">
                <td class="ps-3 py-3">
                    <input type="checkbox" class="form-check-input custom-checkbox task-checkbox" value="${item.id}">
                </td>
                <td class="py-3">
                    <a href="/tasks/${item.id}" class="text-body-emphasis fw-semibold text-decoration-none">${item.title}</a>
                </td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.assigned_to ? item.assigned_to.name : 'Unassigned'}</td>
                <td class="py-3">${getPriorityBadgeClass(item.priority)}</td>
                <td class="py-3">${getStatusBadgeClass(item.status)}</td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.due_date ? new Date(item.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A'}</td>
                <td class="text-end pe-3 py-3">
                    <div class="d-inline-flex align-items-center justify-content-end">
                        <a href="/tasks/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        ${editBtn}
                        ${deleteBtn}
                    </div>
                </td>
            </tr>
        `;
    }

    function renderMobile(tasks) {
        if (!mobileList) return;
        mobileList.innerHTML = '';

        if (!tasks || tasks.length === 0) {
            mobileList.innerHTML = getEmptyStateHtml({ title: 'No tasks found', module: 'tasks' });
            const clearBtn = mobileList.querySelector('.btn-clear-filters-action');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    const resetTrigger = document.getElementById('btnFilterTrigger') || document.getElementById('btnResetFilters');
                    if (resetTrigger) resetTrigger.click();
                });
            }
            return;
        }

        const canEdit = window.userPermissions && window.userPermissions.canEdit;
        const canDelete = window.userPermissions && window.userPermissions.canDelete;

        tasks.forEach(item => {
            const collapseId = `taskCollapse_${item.id}`;
            const priorityBadge = getPriorityBadgeClass(item.priority);
            const statusBadge = getStatusBadgeClass(item.status);

            let actionButtonsHtml = `
                <a href="/tasks/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                    <i class="fa-regular fa-eye"></i>
                </a>
            `;
            if (canEdit) {
                actionButtonsHtml += `
                    <a href="/tasks/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Task">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                `;
            }
            if (canDelete) {
                actionButtonsHtml += `
                    <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.title}" title="Delete Task">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                `;
            }

            const dueDateFormatted = item.due_date ? new Date(item.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';

            const cardHtml = `
                <div class="border-bottom mobile-card-item bg-body" style="min-width: 0;">
                    <div class="d-flex align-items-center justify-content-between p-3 mobile-card-header" 
                         data-bs-target="#${collapseId}" 
                         aria-expanded="false" 
                         aria-controls="${collapseId}"
                         style="cursor: pointer; min-width: 0;">
                        
                        <div class="min-w-0 flex-grow-1 me-2" style="min-width: 0;">
                            <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.95rem;">
                                ${item.title}
                            </div>
                            <div class="text-secondary text-truncate" style="font-size: 0.825rem;">
                                Assigned: ${item.assigned_to ? item.assigned_to.name : 'Unassigned'}
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
                            ${statusBadge}
                            <button class="btn text-secondary p-0 border-0 shadow-none text-decoration-none lh-1 mobile-action-toggle ms-1" 
                                    type="button" 
                                    aria-label="Toggle Details">
                                <i class="fa-solid fa-chevron-right chevron-icon" style="color: #6366f1; font-size: 0.85rem;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="collapse" id="${collapseId}">
                        <div class="p-3 py-2 bg-body border-top" style="font-size: 0.825rem;">
                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-user me-1" style="color: #6366f1; width: 16px;"></i> Assigned To :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${item.assigned_to ? item.assigned_to.name : 'Unassigned'}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-flag me-1" style="color: #6366f1; width: 16px;"></i> Priority :
                                </div>
                                <div class="text-end">
                                    ${priorityBadge}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-circle-check me-1" style="color: #6366f1; width: 16px;"></i> Status :
                                </div>
                                <div class="text-end">
                                    ${statusBadge}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-calendar-days me-1" style="color: #6366f1; width: 16px;"></i> Due Date :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${dueDateFormatted}
                                </div>
                            </div>

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
            mobileList.insertAdjacentHTML('beforeend', cardHtml);
        });
    }

    // Collapse toggle click delegation
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

    function attachCheckboxListeners() {
        document.querySelectorAll('.task-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteState);
        });
        updateBulkDeleteState();
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            document.querySelectorAll('.task-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateBulkDeleteState();
        });
    }

    if (btnBulkDelete) {
        btnBulkDelete.addEventListener('click', async function () {
            const checkedBoxes = document.querySelectorAll('.task-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            if (ids.length === 0) return;

            const confirmed = await confirmDelete(`${ids.length} selected tasks`);
            if (!confirmed) return;

            try {
                await Promise.all(ids.map(id =>
                    apiRequest(`/api/tasks/${id}`, { method: 'DELETE' })
                ));
                if (typeof showSuccessToast === 'function') {
                    showSuccessToast(`${ids.length} tasks deleted successfully.`);
                }
                fetchTasks(currentPage);
            } catch (err) {
                if (typeof showErrorToast === 'function') {
                    showErrorToast(err.message || 'Failed to delete selected tasks.');
                }
            }
        });
    }

    function bindDeleteButtons() {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', async function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || 'this task';
                
                const confirmed = await confirmDelete(name);
                if (!confirmed) return;

                try {
                    const data = await apiRequest(`/api/tasks/${id}`, { method: 'DELETE' });
                    if (data.success) {
                        if (typeof showSuccessToast === 'function') {
                            showSuccessToast(data.message || 'Task deleted successfully.');
                        }
                        fetchTasks(currentPage);
                    }
                } catch (err) {
                    if (typeof showErrorToast === 'function') {
                        showErrorToast(err.message || 'Failed to delete task.');
                    }
                }
            });
        });
    }

    if (searchInput) searchInput.addEventListener('input', () => fetchTasks(1));
    if (filterPriority) filterPriority.addEventListener('change', () => fetchTasks(1));
    if (filterStatus) filterStatus.addEventListener('change', () => fetchTasks(1));
    if (perPageSelect) perPageSelect.addEventListener('change', () => fetchTasks(1));
    if (btnFilterReset) {
        btnFilterReset.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (filterPriority) filterPriority.value = '';
            if (filterStatus) filterStatus.value = '';
            fetchTasks(1);
        });
    }

    fetchTasks(1);
});
