document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('pipelinesTableBody');
    const mobileList = document.getElementById('pipelinesMobileCardList');

    let currentPage = 1;

    // Bind AJAX Form Submissions
    if (typeof bindFormSubmit === 'function') {
        bindFormSubmit({
            formId: 'pipelineCreateForm',
            url: (form) => form.action,
            method: 'POST'
        });

        bindFormSubmit({
            formId: 'pipelineEditForm',
            url: (form) => form.action,
            method: 'POST'
        });
    }

    const searchInput = document.getElementById('searchInput');
    const perPageSelect = document.getElementById('perPage');
    const btnFilterReset = document.getElementById('btnFilterTrigger');

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
                        fetchPipelines(parseInt(btn.dataset.page));
                    }
                });
            }
        }
    }

    function fetchPipelines(page = 1) {
        if (!tableBody && !mobileList) return;
        currentPage = page;

        loadDataTable({
            url: '/api/pipelines',
            tableBodyId: 'pipelinesTableBody',
            summaryId: 'paginationSummary',
            controlsId: 'paginationControls',
            page: currentPage,
            perPage: perPageSelect ? parseInt(perPageSelect.value) : 10,
            params: {
                search: searchInput ? searchInput.value : ''
            },
            emptyMessage: 'No pipelines found.',
            rowRenderer: renderPipelineRow,
            onRendered: (items) => {
                renderMobile(items);
                syncMobilePagination();
                bindDeleteButtons();
            }
        });
    }

    function renderPipelineRow(item) {
        const canEdit = window.userPermissions && window.userPermissions.canEdit;
        const canDelete = window.userPermissions && window.userPermissions.canDelete;

        const editBtn = canEdit ? `
            <a href="/pipelines/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Pipeline">
                <i class="fa-regular fa-pen-to-square"></i>
            </a>
        ` : '';

        const deleteBtn = canDelete ? `
            <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.name}" title="Delete Pipeline">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        ` : '';

        return `
            <tr class="border-bottom hover-bg-light transition-colors">
                <td class="ps-3 py-3">
                    <a href="/pipelines/${item.id}" class="text-body-emphasis fw-semibold text-decoration-none">${item.name}</a>
                </td>
                <td class="py-3 text-secondary" style="font-size: 0.85rem;">${item.description || 'N/A'}</td>
                <td class="py-3"><span class="badge bg-info-subtle text-info">${item.stages ? item.stages.length : 0} stages</span></td>
                <td class="py-3">${item.is_default ? '<span class="badge rounded-pill fw-semibold px-2 py-1" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">Default</span>' : '<span class="badge rounded-pill fw-semibold px-2 py-1" style="background-color: #f1f5f9; color: #475569; font-size: 0.75rem;">No</span>'}</td>
                <td class="text-end pe-3 py-3">
                    <div class="d-inline-flex align-items-center justify-content-end">
                        <a href="/pipelines/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        ${editBtn}
                        ${deleteBtn}
                    </div>
                </td>
            </tr>
        `;
    }

    function renderMobile(pipelines) {
        if (!mobileList) return;
        mobileList.innerHTML = '';

        if (!pipelines || pipelines.length === 0) {
            mobileList.innerHTML = `<div class="text-center py-4 text-secondary small">No pipelines found.</div>`;
            return;
        }

        const canEdit = window.userPermissions && window.userPermissions.canEdit;
        const canDelete = window.userPermissions && window.userPermissions.canDelete;

        pipelines.forEach(item => {
            const collapseId = `pipelineCollapse_${item.id}`;
            const defaultBadge = item.is_default 
                ? `<span class="badge rounded-pill fw-semibold px-2 py-1" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">Default</span>`
                : `<span class="badge rounded-pill fw-semibold px-2 py-1" style="background-color: #f1f5f9; color: #475569; font-size: 0.75rem;">Standard</span>`;

            let actionButtonsHtml = `
                <a href="/pipelines/${item.id}" class="action-btn action-btn-view me-1" title="View Details">
                    <i class="fa-regular fa-eye"></i>
                </a>
            `;
            if (canEdit) {
                actionButtonsHtml += `
                    <a href="/pipelines/${item.id}/edit" class="action-btn action-btn-edit me-1" title="Edit Pipeline">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                `;
            }
            if (canDelete) {
                actionButtonsHtml += `
                    <button class="action-btn action-btn-delete btn-delete" data-id="${item.id}" data-name="${item.name}" title="Delete Pipeline">
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
                                ${item.name}
                            </div>
                            <div class="text-secondary text-truncate" style="font-size: 0.825rem;">
                                ${item.stages ? item.stages.length : 0} stages configured
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
                            ${defaultBadge}
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
                                    <i class="fa-solid fa-diagram-project me-1" style="color: #6366f1; width: 16px;"></i> Pipeline Name :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${item.name}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-align-left me-1" style="color: #6366f1; width: 16px;"></i> Description :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    ${item.description || 'N/A'}
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-list me-1" style="color: #6366f1; width: 16px;"></i> Stages Count :
                                </div>
                                <div class="fw-medium text-body-secondary text-end">
                                    <span class="badge bg-info-subtle text-info">${item.stages ? item.stages.length : 0} stages</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="fw-semibold text-body-emphasis d-flex align-items-center me-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-star me-1" style="color: #6366f1; width: 16px;"></i> Default :
                                </div>
                                <div class="text-end">
                                    ${defaultBadge}
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

    function bindDeleteButtons() {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', async function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || 'this pipeline';

                const confirmed = await confirmDelete(name);
                if (!confirmed) return;

                try {
                    const data = await apiRequest(`/api/pipelines/${id}`, { method: 'DELETE' });
                    if (data.success) {
                        if (typeof showSuccessToast === 'function') {
                            showSuccessToast(data.message || 'Pipeline deleted successfully.');
                        }
                        fetchPipelines(currentPage);
                    }
                } catch (err) {
                    if (typeof showErrorToast === 'function') {
                        showErrorToast(err.message || 'Failed to delete pipeline.');
                    }
                }
            });
        });
    }

    if (searchInput) searchInput.addEventListener('input', () => fetchPipelines(1));
    if (perPageSelect) perPageSelect.addEventListener('change', () => fetchPipelines(1));
    if (btnFilterReset) {
        btnFilterReset.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (perPageSelect) {
                perPageSelect.value = '10';
                perPageSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }
            fetchPipelines(1);
        });
    }

    const btnExport = document.getElementById('btnExport');
    if (btnExport) {
        btnExport.addEventListener('click', () => {
            exportTableData({
                url: '/api/pipelines',
                filename: `pipelines_export_${new Date().toISOString().slice(0, 10)}.csv`,
                headers: ['ID', 'Name', 'Description', 'Stages Count', 'Is Default'],
                params: {
                    search: searchInput ? searchInput.value : ''
                },
                formatRow: (item) => [
                    item.id,
                    item.name,
                    item.description || '',
                    item.stages ? item.stages.length : 0,
                    item.is_default ? 'Yes' : 'No'
                ]
            });
        });
    }

    fetchPipelines(1);
});
