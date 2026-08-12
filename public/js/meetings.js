document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('meetingsTableBody');
    const mobileList = document.getElementById('meetingsMobileCardList');
    const searchInput = document.getElementById('searchInput');
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
            formId: 'meetingCreateForm',
            url: (form) => form.action,
            method: 'POST'
        });

        bindFormSubmit({
            formId: 'meetingEditForm',
            url: (form) => form.action,
            method: 'POST'
        });
    }

    function updateBulkDeleteState() {
        const checkedBoxes = document.querySelectorAll('.meeting-checkbox:checked');
        const count = checkedBoxes.length;
        const totalBoxes = document.querySelectorAll('.meeting-checkbox').length;

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

    function getMeetingStatusBadge(status) {
        const s = (status || '').toLowerCase();
        if (s === 'completed') return `<span class="badge rounded-pill fw-semibold px-2.5 py-1" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">Completed</span>`;
        if (s === 'cancelled') return `<span class="badge rounded-pill fw-semibold px-2.5 py-1" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem;">Cancelled</span>`;
        if (s === 'rescheduled') return `<span class="badge rounded-pill fw-semibold px-2.5 py-1" style="background-color: #ffedd5; color: #c2410c; font-size: 0.75rem;">Rescheduled</span>`;
        return `<span class="badge rounded-pill fw-semibold px-2.5 py-1" style="background-color: #e0f2fe; color: #0369a1; font-size: 0.75rem;">Scheduled</span>`;
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
                        fetchMeetings(parseInt(btn.dataset.page));
                    }
                });
            }
        }
    }

    function fetchMeetings(page = 1) {
        if (!tableBody && !mobileList) return;
        currentPage = page;

        loadDataTable({
            url: '/api/meetings',
            tableBodyId: 'meetingsTableBody',
            summaryId: 'paginationSummary',
            controlsId: 'paginationControls',
            page: currentPage,
            perPage: perPageSelect ? perPageSelect.value : 10,
            params: {
                search: searchInput ? searchInput.value : '',
                status: filterStatus ? filterStatus.value : ''
            },
            emptyMessage: 'No meetings found.',
            rowRenderer: renderMeetingRow,
            onRendered: (items) => {
                renderMobile(items);
                syncMobilePagination();
                bindDeleteButtons();
                attachCheckboxListeners();
            }
        });
    }

    function renderMeetingRow(item) {
        const canEdit = window.userPermissions && window.userPermissions.canEdit;
        const canDelete = window.userPermissions && window.userPermissions.canDelete;

        const editBtn = canEdit ? `
            <a href="/meetings/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Meeting">
                <i class="fa-regular fa-pen-to-square"></i>
            </a>
        ` : '';

        const deleteBtn = canDelete ? `
            <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.title}" title="Delete Meeting">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        ` : '';

        return `
            <tr class="border-bottom hover-bg-light transition-colors">
                <td class="ps-3 py-3">
                    <input type="checkbox" class="form-check-input custom-checkbox meeting-checkbox" value="${item.id}">
                </td>
                <td class="py-3">
                    <a href="/meetings/${item.id}" class="text-body-emphasis fw-semibold text-decoration-none">${item.title}</a>
                </td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.host ? item.host.name : 'Unassigned'}</td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.start_at ? new Date(item.start_at).toLocaleString() : 'N/A'}</td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.end_at ? new Date(item.end_at).toLocaleString() : 'N/A'}</td>
                <td class="py-3" style="font-size: 0.85rem;">${item.meeting_link ? `<a href="${item.meeting_link}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none"><i class="fa-solid fa-video me-1"></i> Join</a>` : (item.location || 'N/A')}</td>
                <td class="py-3">${getMeetingStatusBadge(item.status)}</td>
                <td class="text-end pe-3 py-3">
                    <div class="d-inline-flex align-items-center justify-content-end">
                        <a href="/meetings/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        ${editBtn}
                        ${deleteBtn}
                    </div>
                </td>
            </tr>
        `;
    }

    function renderMobile(meetings) {
        if (!mobileList) return;
        mobileList.innerHTML = '';

        if (!meetings || meetings.length === 0) {
            mobileList.innerHTML = `<div class="text-center py-4 text-secondary small">No meetings found.</div>`;
            return;
        }

        const canEdit = window.userPermissions && window.userPermissions.canEdit;
        const canDelete = window.userPermissions && window.userPermissions.canDelete;

        meetings.forEach(item => {
            const collapseId = `meetingCollapse_${item.id}`;
            const statusBadge = getMeetingStatusBadge(item.status);

            let actionButtonsHtml = `
                <a href="/meetings/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                    <i class="fa-regular fa-eye"></i>
                </a>
            `;
            if (canEdit) {
                actionButtonsHtml += `
                    <a href="/meetings/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Meeting">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                `;
            }
            if (canDelete) {
                actionButtonsHtml += `
                    <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.title}" title="Delete Meeting">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                `;
            }

            const startTimeStr = item.start_at ? new Date(item.start_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }) : 'N/A';
            const endTimeStr = item.end_at ? new Date(item.end_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }) : 'N/A';

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
                                Host: ${item.host ? item.host.name : 'Unassigned'}
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
                                    <i class="fa-solid fa-user me-1" style="color: #6366f1; width: 16px;"></i> Host :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${item.host ? item.host.name : 'Unassigned'}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-clock me-1" style="color: #6366f1; width: 16px;"></i> Start Time :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${startTimeStr}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-hourglass-end me-1" style="color: #6366f1; width: 16px;"></i> End Time :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${endTimeStr}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-location-dot me-1" style="color: #6366f1; width: 16px;"></i> Location / Link :
                                </div>
                                <div class="fw-medium text-end">
                                    ${item.meeting_link ? `<a href="${item.meeting_link}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none"><i class="fa-solid fa-video me-1"></i> Join Meeting</a>` : (item.location || 'N/A')}
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
        document.querySelectorAll('.meeting-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteState);
        });
        updateBulkDeleteState();
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            document.querySelectorAll('.meeting-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateBulkDeleteState();
        });
    }

    if (btnBulkDelete) {
        btnBulkDelete.addEventListener('click', async function () {
            const checkedBoxes = document.querySelectorAll('.meeting-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            if (ids.length === 0) return;

            const confirmed = await confirmDelete(`${ids.length} selected meetings`);
            if (!confirmed) return;

            try {
                await Promise.all(ids.map(id =>
                    apiRequest(`/api/meetings/${id}`, { method: 'DELETE' })
                ));
                if (typeof showSuccessToast === 'function') {
                    showSuccessToast(`${ids.length} meetings deleted successfully.`);
                }
                fetchMeetings(currentPage);
            } catch (err) {
                if (typeof showErrorToast === 'function') {
                    showErrorToast(err.message || 'Failed to delete selected meetings.');
                }
            }
        });
    }

    function bindDeleteButtons() {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', async function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || 'this meeting';

                const confirmed = await confirmDelete(name);
                if (!confirmed) return;

                try {
                    const data = await apiRequest(`/api/meetings/${id}`, { method: 'DELETE' });
                    if (data.success) {
                        if (typeof showSuccessToast === 'function') {
                            showSuccessToast(data.message || 'Meeting deleted successfully.');
                        }
                        fetchMeetings(currentPage);
                    }
                } catch (err) {
                    if (typeof showErrorToast === 'function') {
                        showErrorToast(err.message || 'Failed to delete meeting.');
                    }
                }
            });
        });
    }

    if (searchInput) searchInput.addEventListener('input', () => fetchMeetings(1));
    if (filterStatus) filterStatus.addEventListener('change', () => fetchMeetings(1));
    if (perPageSelect) perPageSelect.addEventListener('change', () => fetchMeetings(1));
    if (btnFilterReset) {
        btnFilterReset.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (filterStatus) filterStatus.value = '';
            fetchMeetings(1);
        });
    }

    fetchMeetings(1);
});
