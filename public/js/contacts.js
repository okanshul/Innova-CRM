document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('contactsTableBody');
    const mobileList = document.getElementById('contactsMobileCardList');
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
            formId: 'contactCreateForm',
            url: (form) => form.action,
            method: 'POST'
        });

        bindFormSubmit({
            formId: 'contactEditForm',
            url: (form) => form.action,
            method: 'POST'
        });
    }

    function updateBulkDeleteState() {
        const checkedBoxes = document.querySelectorAll('.contact-checkbox:checked');
        const count = checkedBoxes.length;
        const totalBoxes = document.querySelectorAll('.contact-checkbox').length;

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

    function getContactStatusBadge(status) {
        const s = (status || '').toLowerCase();
        if (s === 'customer') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">Customer</span>`;
        if (s === 'prospect') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #e0e7ff; color: #4338ca; font-size: 0.75rem;">Prospect</span>`;
        if (s === 'lead') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #e0f2fe; color: #0369a1; font-size: 0.75rem;">Lead</span>`;
        return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem;">Inactive</span>`;
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
                        fetchContacts(parseInt(btn.dataset.page));
                    }
                });
            }
        }
    }

    function fetchContacts(page = 1) {
        if (!tableBody && !mobileList) return;
        currentPage = page;

        loadDataTable({
            url: '/api/contacts',
            tableBodyId: 'contactsTableBody',
            summaryId: 'paginationSummary',
            controlsId: 'paginationControls',
            page: currentPage,
            perPage: perPageSelect ? perPageSelect.value : 10,
            params: {
                search: searchInput ? searchInput.value : '',
                status: filterStatus ? filterStatus.value : ''
            },
            emptyMessage: 'No contacts found.',
            rowRenderer: renderContactRow,
            onRendered: (items) => {
                renderMobile(items);
                syncMobilePagination();
                bindDeleteButtons();
                attachCheckboxListeners();
            }
        });
    }

    function renderContactRow(item) {
        const canEdit = window.userPermissions && window.userPermissions.canEdit;
        const canDelete = window.userPermissions && window.userPermissions.canDelete;

        const editBtn = canEdit ? `
            <a href="/contacts/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Contact">
                <i class="fa-regular fa-pen-to-square"></i>
            </a>
        ` : '';

        const deleteBtn = canDelete ? `
            <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.first_name} ${item.last_name || ''}" title="Delete Contact">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        ` : '';

        return `
            <tr class="border-bottom hover-bg-light transition-colors">
                <td class="ps-3 py-3">
                    <input type="checkbox" class="form-check-input custom-checkbox contact-checkbox" value="${item.id}">
                </td>
                <td class="py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                            ${(item.first_name[0] || '').toUpperCase()}
                        </div>
                        <div>
                            <a href="/contacts/${item.id}" class="text-body-emphasis fw-semibold text-decoration-none">${item.first_name} ${item.last_name || ''}</a>
                        </div>
                    </div>
                </td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.email || 'N/A'}</td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.phone || 'N/A'}</td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.company ? item.company.name : 'N/A'}</td>
                <td class="py-3">${getContactStatusBadge(item.status)}</td>
                <td class="text-end pe-3 py-3">
                    <div class="d-inline-flex align-items-center justify-content-end">
                        <a href="/contacts/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        ${editBtn}
                        ${deleteBtn}
                    </div>
                </td>
            </tr>
        `;
    }

    function renderMobile(contacts) {
        if (!mobileList) return;
        mobileList.innerHTML = '';

        if (!contacts || contacts.length === 0) {
            const isFiltered = hasActiveFilters({ search: searchInput ? searchInput.value : '', status: filterStatus ? filterStatus.value : '' });
            mobileList.innerHTML = getEmptyStateHtml({ title: 'No contacts found', module: 'contacts', showClearBtn: isFiltered });
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

        contacts.forEach(item => {
            const collapseId = `contactCollapse_${item.id}`;
            const statusBadge = getContactStatusBadge(item.status);

            let actionButtonsHtml = `
                <a href="/contacts/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                    <i class="fa-regular fa-eye"></i>
                </a>
            `;
            if (canEdit) {
                actionButtonsHtml += `
                    <a href="/contacts/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Contact">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                `;
            }
            if (canDelete) {
                actionButtonsHtml += `
                    <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.first_name} ${item.last_name || ''}" title="Delete Contact">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                `;
            }

            const cardHtml = `
                <div class="border-bottom mobile-card-item bg-body" style="min-width: 0;">
                    <div class="d-flex align-items-center justify-content-between p-3 mobile-card-header" 
                         data-bs-target="#${collapseId}" 
                         aria-expanded="false" 
                         aria-controls="${collapseId}"
                         style="cursor: pointer; min-width: 0;">
                        
                        <div class="min-w-0 flex-grow-1 me-2" style="min-width: 0;">
                            <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.95rem;">
                                ${item.first_name} ${item.last_name || ''}
                            </div>
                            <div class="text-secondary text-truncate" style="font-size: 0.825rem;">
                                ${item.email || 'No email'}
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
                                    <i class="fa-solid fa-envelope me-1" style="color: #6366f1; width: 16px;"></i> Email :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${item.email || 'N/A'}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-phone me-1" style="color: #6366f1; width: 16px;"></i> Phone No. :
                                </div>
                                <div class="fw-medium text-end" style="color: #0284c7;">
                                    ${item.phone || 'N/A'}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-building me-1" style="color: #6366f1; width: 16px;"></i> Company :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${item.company ? item.company.name : 'N/A'}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-tag me-1" style="color: #6366f1; width: 16px;"></i> Status :
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
        document.querySelectorAll('.contact-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteState);
        });
        updateBulkDeleteState();
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            document.querySelectorAll('.contact-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateBulkDeleteState();
        });
    }

    if (btnBulkDelete) {
        btnBulkDelete.addEventListener('click', async function () {
            const checkedBoxes = document.querySelectorAll('.contact-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            if (ids.length === 0) return;

            const confirmed = await confirmDelete(`${ids.length} selected contacts`);
            if (!confirmed) return;

            try {
                await Promise.all(ids.map(id =>
                    apiRequest(`/api/contacts/${id}`, { method: 'DELETE' })
                ));
                if (typeof showSuccessToast === 'function') {
                    showSuccessToast(`${ids.length} contacts deleted successfully.`);
                }
                fetchContacts(currentPage);
            } catch (err) {
                if (typeof showErrorToast === 'function') {
                    showErrorToast(err.message || 'Failed to delete selected contacts.');
                }
            }
        });
    }

    function bindDeleteButtons() {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', async function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || 'this contact';

                const confirmed = await confirmDelete(name);
                if (!confirmed) return;

                try {
                    const data = await apiRequest(`/api/contacts/${id}`, { method: 'DELETE' });
                    if (data.success) {
                        if (typeof showSuccessToast === 'function') {
                            showSuccessToast(data.message || 'Contact deleted successfully.');
                        }
                        fetchContacts(currentPage);
                    }
                } catch (err) {
                    if (typeof showErrorToast === 'function') {
                        showErrorToast(err.message || 'Failed to delete contact.');
                    }
                }
            });
        });
    }

    if (searchInput) searchInput.addEventListener('input', () => fetchContacts(1));
    if (filterStatus) filterStatus.addEventListener('change', () => fetchContacts(1));
    if (perPageSelect) perPageSelect.addEventListener('change', () => fetchContacts(1));
    if (btnFilterReset) {
        btnFilterReset.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (filterStatus) {
                filterStatus.value = '';
                filterStatus.dispatchEvent(new Event('change', { bubbles: true }));
            }
            document.querySelectorAll('.filter-controls-wrapper input[type="date"], .filter-controls-wrapper input[id*="date"], input[name*="date"]').forEach(i => {
                i.value = '';
                i.dispatchEvent(new Event('change', { bubbles: true }));
            });
            fetchContacts(1);
        });
    }

    const btnExport = document.getElementById('btnExport');
    if (btnExport) {
        btnExport.addEventListener('click', () => {
            exportTableData({
                url: '/api/contacts',
                filename: `contacts_export_${new Date().toISOString().slice(0, 10)}.csv`,
                headers: ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Company', 'Status'],
                params: {
                    search: searchInput ? searchInput.value : '',
                    status: filterStatus ? filterStatus.value : ''
                },
                formatRow: (item) => [
                    item.id,
                    item.first_name,
                    item.last_name || '',
                    item.email || '',
                    item.phone || '',
                    item.company ? item.company.name : 'N/A',
                    item.status
                ]
            });
        });
    }

    fetchContacts(1);
});
