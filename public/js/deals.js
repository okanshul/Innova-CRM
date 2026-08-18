document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('dealsTableBody');
    const mobileList = document.getElementById('dealsMobileCardList');
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
            formId: 'dealCreateForm',
            url: (form) => form.action,
            method: 'POST'
        });

        bindFormSubmit({
            formId: 'dealEditForm',
            url: (form) => form.action,
            method: 'POST'
        });
    }

    function updateBulkDeleteState() {
        const checkedBoxes = document.querySelectorAll('.deal-checkbox:checked');
        const count = checkedBoxes.length;
        const totalBoxes = document.querySelectorAll('.deal-checkbox').length;

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

    function getDealStatusBadge(status) {
        const s = (status || '').toLowerCase();
        if (s === 'won' || s === 'closed_won') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">Closed Won</span>`;
        if (s === 'lost' || s === 'closed_lost') return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem;">Closed Lost</span>`;
        return `<span class="badge rounded-pill fw-semibold px-3 py-1" style="background-color: #e0f2fe; color: #0369a1; font-size: 0.75rem;">Open</span>`;
    }

    function getStageBadge(stage) {
        if (!stage) return `<span class="badge rounded-pill fw-semibold px-2.5 py-1" style="background-color: #f1f5f9; color: #64748b; font-size: 0.775rem;">N/A</span>`;
        const color = stage.color || '#6366f1';
        return `<span class="badge rounded-pill fw-semibold px-2.5 py-1" style="background-color: ${color}1f; color: ${color}; border: 1px solid ${color}33; font-size: 0.775rem;">${stage.name}</span>`;
    }

    function formatDate(dateStr) {
        if (!dateStr) return 'N/A';
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
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
                        fetchDeals(parseInt(btn.dataset.page));
                    }
                });
            }
        }
    }

    function fetchDeals(page = 1) {
        if (!tableBody && !mobileList) return;
        currentPage = page;

        loadDataTable({
            url: '/api/deals',
            tableBodyId: 'dealsTableBody',
            summaryId: 'paginationSummary',
            controlsId: 'paginationControls',
            page: currentPage,
            perPage: perPageSelect ? perPageSelect.value : 10,
            params: {
                search: searchInput ? searchInput.value : '',
                status: filterStatus ? filterStatus.value : ''
            },
            emptyMessage: 'No deals found.',
            rowRenderer: renderDealRow,
            onRendered: (items) => {
                renderMobile(items);
                syncMobilePagination();
                bindDeleteButtons();
                attachCheckboxListeners();
            }
        });
    }

    function renderDealRow(item) {
        const canEdit = window.userPermissions && window.userPermissions.canEdit;
        const canDelete = window.userPermissions && window.userPermissions.canDelete;

        const editBtn = canEdit ? `
            <a href="/deals/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Deal">
                <i class="fa-regular fa-pen-to-square"></i>
            </a>
        ` : '';

        const deleteBtn = canDelete ? `
            <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.title}" title="Delete Deal">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        ` : '';

        return `
            <tr class="border-bottom hover-bg-light transition-colors">
                <td class="ps-3 py-3">
                    <input type="checkbox" class="form-check-input custom-checkbox deal-checkbox" value="${item.id}">
                </td>
                <td class="py-3">
                    <a href="/deals/${item.id}" class="text-body-emphasis fw-semibold text-decoration-none">${item.title}</a>
                </td>
                <td class="py-3 fw-bold text-success">$${parseFloat(item.value).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                <td class="py-3">${getStageBadge(item.stage)}</td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.company ? item.company.name : (item.contact ? item.contact.first_name : 'N/A')}</td>
                <td class="py-3">${getDealStatusBadge(item.status)}</td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${formatDate(item.expected_close_date)}</td>
                <td class="text-end pe-3 py-3">
                    <div class="d-inline-flex align-items-center justify-content-end">
                        <a href="/deals/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        ${editBtn}
                        ${deleteBtn}
                    </div>
                </td>
            </tr>
        `;
    }

    function renderMobile(deals) {
        if (!mobileList) return;
        mobileList.innerHTML = '';

        if (!deals || deals.length === 0) {
            const isFiltered = hasActiveFilters({ search: searchInput ? searchInput.value : '', status: filterStatus ? filterStatus.value : '' });
            mobileList.innerHTML = getEmptyStateHtml({ title: 'No deals found', module: 'deals', showClearBtn: isFiltered });
            const clearBtn = mobileList.querySelector('.btn-clear-filters-action');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    if (filterStatus) filterStatus.value = '';
                    fetchDeals(1);
                });
            }
            return;
        }

        const canEdit = window.userPermissions && window.userPermissions.canEdit;
        const canDelete = window.userPermissions && window.userPermissions.canDelete;

        deals.forEach(item => {
            const formattedValue = `$${parseFloat(item.value).toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            const statusBadge = getDealStatusBadge(item.status);
            const collapseId = `dealCollapse_${item.id}`;

            const card = document.createElement('div');
            card.className = 'card border-0 shadow-sm rounded-3 mb-2 overflow-hidden';

            let actionButtonsHtml = `
                <a href="/deals/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                    <i class="fa-regular fa-eye"></i>
                </a>
            `;
            if (canEdit) {
                actionButtonsHtml += `
                    <a href="/deals/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Deal">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                `;
            }
            if (canDelete) {
                actionButtonsHtml += `
                    <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.title}" title="Delete Deal">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                `;
            }

            card.innerHTML = `
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
                            <div class="fw-bold text-success text-truncate" style="font-size: 0.825rem;">
                                ${formattedValue}
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
                                    <i class="fa-solid fa-dollar-sign me-1" style="color: #6366f1; width: 16px;"></i> Deal Value :
                                </div>
                                <div class="fw-bold text-success text-end">
                                    ${formattedValue}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-diagram-project me-1" style="color: #6366f1; width: 16px;"></i> Stage :
                                </div>
                                <div class="fw-medium text-end">
                                    ${getStageBadge(item.stage)}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-building me-1" style="color: #6366f1; width: 16px;"></i> Company/Contact :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${item.company ? item.company.name : (item.contact ? item.contact.first_name : 'N/A')}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-calendar-days me-1" style="color: #6366f1; width: 16px;"></i> Expected Close :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${formatDate(item.expected_close_date)}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-chart-line me-1" style="color: #6366f1; width: 16px;"></i> Status :
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
            mobileList.appendChild(card);
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
        document.querySelectorAll('.deal-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteState);
        });
        updateBulkDeleteState();
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            document.querySelectorAll('.deal-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateBulkDeleteState();
        });
    }

    if (btnBulkDelete) {
        btnBulkDelete.addEventListener('click', async function () {
            const checkedBoxes = document.querySelectorAll('.deal-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            if (ids.length === 0) return;

            const confirmed = await confirmDelete(`${ids.length} selected deals`);
            if (!confirmed) return;

            try {
                await Promise.all(ids.map(id =>
                    apiRequest(`/api/deals/${id}`, { method: 'DELETE' })
                ));
                if (typeof showSuccessToast === 'function') {
                    showSuccessToast(`${ids.length} deals deleted successfully.`);
                }
                fetchDeals(currentPage);
            } catch (err) {
                if (typeof showErrorToast === 'function') {
                    showErrorToast(err.message || 'Failed to delete selected deals.');
                }
            }
        });
    }

    function bindDeleteButtons() {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', async function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || 'this deal';

                const confirmed = await confirmDelete(name);
                if (!confirmed) return;

                try {
                    const data = await apiRequest(`/api/deals/${id}`, { method: 'DELETE' });
                    if (data.success) {
                        if (typeof showSuccessToast === 'function') {
                            showSuccessToast(data.message || 'Deal deleted successfully.');
                        }
                        fetchDeals(currentPage);
                    }
                } catch (err) {
                    if (typeof showErrorToast === 'function') {
                        showErrorToast(err.message || 'Failed to delete deal.');
                    }
                }
            });
        });
    }

    if (searchInput) searchInput.addEventListener('input', () => fetchDeals(1));
    if (filterStatus) filterStatus.addEventListener('change', () => fetchDeals(1));
    if (perPageSelect) perPageSelect.addEventListener('change', () => fetchDeals(1));
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
            fetchDeals(1);
        });
    }

    const btnExport = document.getElementById('btnExport');
    if (btnExport) {
        btnExport.addEventListener('click', () => {
            exportTableData({
                url: '/api/deals',
                filename: `deals_export_${new Date().toISOString().slice(0, 10)}.csv`,
                headers: ['ID', 'Title', 'Value', 'Stage', 'Company/Contact', 'Status', 'Expected Close Date'],
                params: {
                    search: searchInput ? searchInput.value : '',
                    status: filterStatus ? filterStatus.value : ''
                },
                formatRow: (item) => [
                    item.id,
                    item.title,
                    item.value,
                    item.stage ? item.stage.name : 'N/A',
                    item.company ? item.company.name : (item.contact ? `${item.contact.first_name} ${item.contact.last_name || ''}`.trim() : 'N/A'),
                    item.status,
                    formatDate(item.expected_close_date)
                ]
            });
        });
    }

    fetchDeals(1);
});
