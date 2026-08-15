/**
 * InnovaCRM Shared Utility Functions & Helpers
 */

/**
 * 1. apiRequest(url, options)
 * Generic fetch wrapper with CSRF token, JSON handling, and structured error handling.
 */
async function apiRequest(url, options = {}) {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const defaultHeaders = {
        'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : '',
        'Accept': 'application/json',
    };

    if (options.body && !(options.body instanceof FormData)) {
        defaultHeaders['Content-Type'] = 'application/json';
    }

    const fetchOptions = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...(options.headers || {})
        }
    };

    const response = await fetch(url, fetchOptions);
    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        const error = new Error(data.message || 'Request failed');
        error.status = response.status;
        error.errors = data.errors || {};
        error.data = data;
        throw error;
    }

    return data;
}

/**
 * 4. SweetAlert2 Wrapper Helpers
 */
function showSuccessToast(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
        });
    } else {
        alert(message);
    }
}

function showErrorToast(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });
    } else {
        alert(message);
    }
}

async function confirmDelete(itemName = 'this item') {
    if (typeof Swal !== 'undefined') {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: `This will permanently delete ${itemName}. This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6366F1',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4 border-0 shadow'
            }
        });
        return result.isConfirmed;
    }
    return confirm(`Are you sure you want to delete ${itemName}?`);
}

/**
 * Helper to check if search or filter params are active
 */
function hasActiveFilters(params = {}) {
    let isFiltered = false;

    if (params && typeof params === 'object') {
        isFiltered = Object.entries(params).some(([key, val]) => {
            if (['page', 'per_page', 'sort', 'order'].includes(key)) return false;
            if (val === null || val === undefined) return false;
            const strVal = String(val).trim().toLowerCase();
            return strVal !== '' && strVal !== 'all';
        });
    }

    if (isFiltered) return true;

    // Check DOM elements for active filters (search, select, date inputs, etc.)
    const filterInputs = document.querySelectorAll('.filter-controls-wrapper input, .filter-controls-wrapper select, input[id*="filter"], select[id*="filter"], input[type="date"], input[id*="Date"], input[name*="date"]');
    for (let input of filterInputs) {
        if (input.id === 'perPage' || input.name === 'perPage') continue;
        const val = (input.value || '').trim().toLowerCase();
        if (val !== '' && val !== 'all') {
            return true;
        }
    }

    return false;
}

/**
 * Helper to generate empty state UI matching design
 */
function getEmptyStateHtml(options = {}) {
    const {
        title = null,
        module = 'records',
        message = null,
        showClearBtn = true
    } = options;

    const displayTitle = title || `No ${module} found`;
    const displayMessage = showClearBtn
        ? (message || `We couldn't find any ${module} matching your search or filters. Try adjusting your search criteria.`)
        : (message || `There are currently no ${module} available.`);

    return `
        <div class="empty-state-container text-center py-5 px-3 my-2">
            <div class="empty-state-illustration position-relative mb-3 d-inline-block">
                <div class="position-relative d-inline-block">
                    <i class="fa-solid fa-clipboard-list" style="font-size: 3.5rem; color: #cbd5e1;"></i>
                </div>
            </div>
            <h5 class="fw-bold text-body-emphasis mb-1 fs-5">${displayTitle}</h5>
            <p class="text-secondary small mb-3 mx-auto" style="max-width: 420px; line-height: 1.5; font-size: 0.85rem;">${displayMessage}</p>
            ${showClearBtn ? `
                <button type="button" class="btn btn-sm px-3 py-1 rounded-3 fw-medium btn-clear-filters-action d-inline-flex align-items-center gap-2" style="border: 1px solid #c7d2fe; color: #4f46e5; background-color: #f5f3ff;">
                    <i class="fa-solid fa-filter"></i> Clear Filters
                </button>
            ` : ''}
        </div>
    `;
}

/**
 * 2. loadDataTable({ url, tableBodyId, rowRenderer, emptyMessage, paginationContainerId, summaryId, controlsId, page, perPage, params })
 * Dynamic data table loader and pagination renderer.
 */
async function loadDataTable(options = {}) {
    const {
        url,
        tableBodyId = 'staffTableBody',
        rowRenderer,
        emptyMessage = 'No records found.',
        summaryId = 'paginationSummary',
        controlsId = 'paginationControls',
        page = 1,
        perPage = 10,
        params = {},
        onRendered = null
    } = options;

    const tableBody = document.getElementById(tableBodyId);
    const paginationSummary = document.getElementById(summaryId);
    const paginationControls = document.getElementById(controlsId);

    if (!tableBody || typeof rowRenderer !== 'function') return;

    // Show loading spinner row
    tableBody.innerHTML = `
        <tr>
            <td colspan="100" class="text-center py-5 text-secondary">
                <i class="fa-solid fa-spinner fa-spin fs-4 mb-2" style="color: #6366F1;"></i>
                <div class="small fw-medium">Loading data...</div>
            </td>
        </tr>
    `;

    try {
        const queryParams = new URLSearchParams({
            page,
            per_page: perPage,
            ...params
        });

        const data = await apiRequest(`${url}?${queryParams.toString()}`);
        const pagination = data.data || data;
        const items = pagination.data || (Array.isArray(pagination) ? pagination : []);

        tableBody.innerHTML = '';

        if (!items || items.length === 0) {
            let moduleName = 'records';
            const cleanTitle = emptyMessage.replace(/\.$/, '');
            if (cleanTitle.toLowerCase().includes('task')) moduleName = 'tasks';
            else if (cleanTitle.toLowerCase().includes('contact')) moduleName = 'contacts';
            else if (cleanTitle.toLowerCase().includes('deal')) moduleName = 'deals';
            else if (cleanTitle.toLowerCase().includes('meeting')) moduleName = 'meetings';
            else if (cleanTitle.toLowerCase().includes('staff')) moduleName = 'staff';
            else if (cleanTitle.toLowerCase().includes('role')) moduleName = 'roles';
            else if (cleanTitle.toLowerCase().includes('pipeline')) moduleName = 'pipelines';

            const isFiltered = hasActiveFilters(params);

            tableBody.innerHTML = `
                <tr>
                    <td colspan="100" class="p-0">
                        ${getEmptyStateHtml({ title: cleanTitle, module: moduleName, showClearBtn: isFiltered })}
                    </td>
                </tr>
            `;

            const clearBtn = tableBody.querySelector('.btn-clear-filters-action');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    const resetTrigger = document.getElementById('btnFilterTrigger') || document.getElementById('btnResetFilters');
                    if (resetTrigger) {
                        resetTrigger.click();
                    } else {
                        document.querySelectorAll('.filter-controls-wrapper input, .filter-controls-wrapper select, input[id*="filter"], select[id*="filter"], input[type="date"], input[id*="Date"], input[name*="date"]').forEach(i => {
                            if (i.id === 'perPage' || i.name === 'perPage') return;
                            i.value = '';
                            i.dispatchEvent(new Event('input', { bubbles: true }));
                            i.dispatchEvent(new Event('change', { bubbles: true }));
                        });
                    }
                });
            }

            if (paginationSummary) paginationSummary.textContent = 'Showing 0 entries';
            if (paginationControls) paginationControls.innerHTML = '';
            if (typeof onRendered === 'function') onRendered(items, pagination);
            return;
        }

        items.forEach(item => {
            const rowHtml = rowRenderer(item);
            if (typeof rowHtml === 'string') {
                tableBody.insertAdjacentHTML('beforeend', rowHtml);
            } else if (rowHtml instanceof HTMLElement) {
                tableBody.appendChild(rowHtml);
            }
        });

        // Render Pagination Controls & Summary
        const total = pagination.total || items.length;
        const from = pagination.from || (total > 0 ? (page - 1) * perPage + 1 : 0);
        const to = pagination.to || Math.min(page * perPage, total);
        const lastPage = pagination.last_page || Math.ceil(total / perPage);

        if (paginationSummary) {
            paginationSummary.textContent = `Showing ${from} to ${to} of ${total} entries`;
        }

        if (paginationControls) {
            let html = '';

            // Previous button
            html += `
                <button class="page-btn" ${page <= 1 ? 'disabled' : ''} data-page="${page - 1}">
                    <i class="fa-solid fa-chevron-left fs-xs"></i>
                </button>
            `;

            // Page numbers
            for (let p = 1; p <= lastPage; p++) {
                if (p === 1 || p === lastPage || (p >= page - 1 && p <= page + 1)) {
                    html += `<button class="page-btn ${p === page ? 'active' : ''}" data-page="${p}">${p}</button>`;
                } else if (p === page - 2 || p === page + 2) {
                    html += `<span class="px-1 text-secondary">...</span>`;
                }
            }

            // Next button
            html += `
                <button class="page-btn" ${page >= lastPage ? 'disabled' : ''} data-page="${page + 1}">
                    <i class="fa-solid fa-chevron-right fs-xs"></i>
                </button>
            `;

            paginationControls.innerHTML = html;

            paginationControls.querySelectorAll('.page-btn:not(:disabled)').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const targetPage = parseInt(e.currentTarget.dataset.page);
                    if (targetPage && targetPage !== page) {
                        loadDataTable({ ...options, page: targetPage });
                    }
                });
            });
        }

        if (typeof onRendered === 'function') onRendered(items, pagination);

    } catch (err) {
        console.error('Data Table Load Error:', err);
        tableBody.innerHTML = `
            <tr>
                <td colspan="100" class="text-center py-4 text-danger">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> Failed to load data. Please try again.
                </td>
            </tr>
        `;
    }
}

/**
 * 3. bindFormSubmit({ formId, url, method, onSuccess, transformPayload, showToast })
 * Reusable form submit handler with validation error highlighting & SweetAlert2 toast.
 */
function bindFormSubmit(config = {}) {
    const {
        formId,
        url,
        method = 'POST',
        onSuccess,
        transformPayload,
        showToast = true,
        submitBtnSelector = 'button[type="submit"]'
    } = config;

    const form = typeof formId === 'string' ? document.getElementById(formId) : formId;
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Clear previous validation errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback-ajax').forEach(el => el.remove());

        const submitBtn = form.querySelector(submitBtnSelector);
        let originalBtnText = '';
        if (submitBtn) {
            originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Submitting...';
        }

        try {
            let body;
            const targetUrl = typeof url === 'function' ? url(form) : (url || form.action);
            const targetMethod = (method || form.getAttribute('method') || 'POST').toUpperCase();

            if (typeof transformPayload === 'function') {
                body = JSON.stringify(transformPayload(form));
            } else {
                body = new FormData(form);
                if (targetMethod !== 'POST' && targetMethod !== 'GET') {
                    body.append('_method', targetMethod);
                }
            }

            const data = await apiRequest(targetUrl, {
                method: targetMethod === 'GET' ? 'GET' : 'POST',
                body: targetMethod === 'GET' ? undefined : body
            });

            if (showToast) {
                showSuccessToast(data.message || 'Operation completed successfully!');
            }

            if (typeof onSuccess === 'function') {
                onSuccess(data, form);
            } else if (data.redirect) {
                window.location.href = data.redirect;
            }

        } catch (err) {
            if (err.status === 422 && err.errors) {
                let firstInvalid = null;
                for (const [field, messages] of Object.entries(err.errors)) {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        if (!firstInvalid) firstInvalid = input;

                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback-ajax text-danger ps-2 mt-1';
                        errorDiv.textContent = messages[0];

                        if (input.classList.contains('custom-select-hidden')) {
                            const selectWrapper = input.closest('.custom-select-wrapper');
                            if (selectWrapper) {
                                selectWrapper.appendChild(errorDiv);
                            } else {
                                input.insertAdjacentElement('afterend', errorDiv);
                            }
                        } else if (input.type === 'password') {
                            const relWrapper = input.closest('.position-relative');
                            if (relWrapper) {
                                relWrapper.insertAdjacentElement('afterend', errorDiv);
                            } else {
                                input.insertAdjacentElement('afterend', errorDiv);
                            }
                        } else {
                            input.insertAdjacentElement('afterend', errorDiv);
                        }
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
            } else {
                showErrorToast(err.message || 'An error occurred while submitting.');
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }
    });

    // Auto clear error state on user input
    form.querySelectorAll('.form-control, .form-select').forEach(input => {
        const clearError = function () {
            this.classList.remove('is-invalid');

            let errDiv;
            if (this.classList.contains('custom-select-hidden')) {
                const selectWrapper = this.closest('.custom-select-wrapper');
                if (selectWrapper) {
                    errDiv = selectWrapper.querySelector('.invalid-feedback-ajax');
                }
            } else if (this.type === 'password') {
                const relWrapper = this.closest('.position-relative');
                if (relWrapper && relWrapper.nextElementSibling && relWrapper.nextElementSibling.classList.contains('invalid-feedback-ajax')) {
                    errDiv = relWrapper.nextElementSibling;
                }
            } else {
                if (this.nextElementSibling && this.nextElementSibling.classList.contains('invalid-feedback-ajax')) {
                    errDiv = this.nextElementSibling;
                }
            }

            if (errDiv) errDiv.remove();
        };
        input.addEventListener('input', clearError);
        input.addEventListener('change', clearError);
    });
}

/**
 * 5. bindDeleteAction({ selector, url, tableReloadFn, itemNameAttr })
 * Generic delegated click handler for delete action buttons.
 */
function bindDeleteAction(options = {}) {
    const {
        selector = '.delete-action',
        url,
        tableReloadFn,
        itemNameAttr = 'data-name'
    } = options;

    document.addEventListener('click', async function (e) {
        const btn = e.target.closest(selector);
        if (!btn) return;

        e.preventDefault();
        const itemName = btn.getAttribute(itemNameAttr) || btn.dataset.name || btn.dataset.title || 'this item';
        const itemId = btn.dataset.id;

        const confirmed = await confirmDelete(itemName);
        if (!confirmed) return;

        try {
            let targetUrl = typeof url === 'function' ? url(itemId, btn) : url;
            if (typeof targetUrl === 'string' && targetUrl.includes(':id')) {
                targetUrl = targetUrl.replace(':id', itemId);
            } else if (!targetUrl && itemId) {
                targetUrl = `/api/staff/${itemId}`;
            }

            const data = await apiRequest(targetUrl, { method: 'DELETE' });
            showSuccessToast(data.message || 'Item deleted successfully');

            if (typeof tableReloadFn === 'function') {
                tableReloadFn();
            }
        } catch (err) {
            console.error('Delete Action Error:', err);
            showErrorToast(err.message || 'Failed to delete item.');
        }
    });
}

/**
 * 6. initCustomSelects(targetSelector)
 * Converts standard select elements into modern custom themed dropdowns
 * matching the design aesthetic with instant two-way event synchronization.
 */
function initCustomSelects(targetSelector = 'select:not(.no-custom-select), .form-select:not(.no-custom-select), .custom-filter-select') {
    const selects = document.querySelectorAll(targetSelector);
    selects.forEach(select => {
        if (
            select.dataset.customSelectInit === 'true' ||
            select.closest('.custom-select-wrapper') ||
            select.closest('.flatpickr-calendar') ||
            select.closest('.swal2-container') ||
            select.classList.contains('flatpickr-monthDropdown-months')
        ) {
            return;
        }
        select.dataset.customSelectInit = 'true';

        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';

        // Inherit layout/grid classes from select to wrapper
        if (select.className) {
            const classList = select.className.split(' ').filter(c =>
                c.startsWith('col-') || c.startsWith('w-') || c.startsWith('flex-') ||
                c.startsWith('mb-') || c.startsWith('mt-') || c.startsWith('me-') || c.startsWith('ms-') || c === 'shadow-none'
            );
            if (classList.length > 0) wrapper.classList.add(...classList);
        }

        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        select.classList.add('custom-select-hidden');

        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger' + (select.disabled ? ' disabled' : '') + (select.classList.contains('is-invalid') ? ' is-invalid' : '');
        trigger.tabIndex = 0;

        const labelSpan = document.createElement('span');
        labelSpan.className = 'custom-select-label text-truncate';

        const arrowIcon = document.createElement('i');
        arrowIcon.className = 'fa-solid fa-chevron-down custom-select-arrow ms-2';

        trigger.appendChild(labelSpan);
        trigger.appendChild(arrowIcon);
        wrapper.appendChild(trigger);

        const menu = document.createElement('div');
        menu.className = 'custom-select-menu';
        wrapper.appendChild(menu);

        let searchInput = null;

        const renderMenuOptions = () => {
            menu.innerHTML = '';
            searchInput = null;

            const selectedOption = select.options[select.selectedIndex] || select.options[0];
            labelSpan.textContent = selectedOption ? selectedOption.text : (select.getAttribute('placeholder') || 'Select...');
            if (selectedOption && (selectedOption.value === '' || selectedOption.value === null)) {
                trigger.classList.add('is-placeholder');
            } else {
                trigger.classList.remove('is-placeholder');
            }

            // Only show search bar for dynamic data selects or explicitly searchable selects
            const isDynamicSelect =
                select.dataset.searchable === 'true' ||
                select.classList.contains('searchable') ||
                ['assigned_to', 'user_id', 'staff_id', 'company_id', 'contact_id', 'host_id', 'pipeline_id', 'stage_id', 'role_id', 'department_id', 'department'].includes(select.name) ||
                (select.options.length >= 6 && !['priority', 'status', 'per_page', 'limit', 'guard_name'].includes(select.name));

            if (isDynamicSelect) {
                const searchContainer = document.createElement('div');
                searchContainer.className = 'custom-select-search-wrapper p-2 border-bottom sticky-top bg-body';
                searchContainer.style.zIndex = '10';

                searchContainer.innerHTML = `
                    <div class="position-relative d-flex align-items-center">
                        <input type="text" class="form-control form-control-sm custom-select-search-input" placeholder="Search..." autocomplete="off" style="font-size: 0.8rem; border-radius: 6px;">
                        <i class="fa-solid fa-magnifying-glass text-secondary" style="font-size: 0.75rem;"></i>
                    </div>
                `;

                searchInput = searchContainer.querySelector('.custom-select-search-input');

                searchContainer.addEventListener('click', (e) => e.stopPropagation());
                searchInput.addEventListener('keydown', (e) => {
                    e.stopPropagation();
                    if (e.key === 'Escape') closeMenu();
                });

                searchInput.addEventListener('input', () => {
                    const term = searchInput.value.toLowerCase().trim();
                    let matchCount = 0;
                    const items = optionsListContainer.querySelectorAll('.custom-select-option');
                    items.forEach(item => {
                        const txt = item.textContent.toLowerCase();
                        if (txt.includes(term)) {
                            item.style.display = '';
                            matchCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    let noResultsEl = optionsListContainer.querySelector('.custom-select-no-results');
                    if (matchCount === 0) {
                        if (!noResultsEl) {
                            noResultsEl = document.createElement('div');
                            noResultsEl.className = 'custom-select-no-results text-center py-2 text-secondary small fs-xs';
                            noResultsEl.textContent = 'No options found';
                            optionsListContainer.appendChild(noResultsEl);
                        } else {
                            noResultsEl.style.display = '';
                        }
                    } else if (noResultsEl) {
                        noResultsEl.style.display = 'none';
                    }
                });

                menu.appendChild(searchContainer);
            }

            const optionsListContainer = document.createElement('div');
            optionsListContainer.className = 'custom-select-options-list';

            const validDataOptions = Array.from(select.options).filter(opt => opt.value !== '');
            if (select.options.length === 0 || validDataOptions.length === 0) {
                const emptyMsg = document.createElement('div');
                emptyMsg.className = 'custom-select-no-results text-center py-3 text-secondary small fs-xs fw-medium';
                emptyMsg.innerHTML = '<i class="fa-solid fa-folder-open me-1 opacity-50"></i> No options available';
                optionsListContainer.appendChild(emptyMsg);
            }

            Array.from(select.options).forEach((opt, idx) => {
                const optItem = document.createElement('div');
                optItem.className = `custom-select-option ${opt.selected ? 'selected' : ''} ${opt.disabled ? 'disabled' : ''}`;
                optItem.dataset.value = opt.value;

                const textSpan = document.createElement('span');
                textSpan.textContent = opt.text;
                optItem.appendChild(textSpan);

                optItem.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (opt.disabled) return;

                    if (select.selectedIndex !== idx) {
                        select.selectedIndex = idx;
                        select.dispatchEvent(new Event('change', { bubbles: true }));
                        select.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    closeMenu();
                });

                optionsListContainer.appendChild(optItem);
            });

            menu.appendChild(optionsListContainer);
        };

        const updatePosition = () => {
            if (!menu.classList.contains('show')) return;
            const rect = trigger.getBoundingClientRect();
            const menuHeight = menu.offsetHeight || 250;
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;

            if (spaceBelow < menuHeight && spaceAbove > spaceBelow) {
                wrapper.classList.add('dropup');
            } else {
                wrapper.classList.remove('dropup');
            }
        };

        const openMenu = () => {
            if (select.disabled || trigger.classList.contains('disabled')) return;
            document.querySelectorAll('.custom-select-menu.show').forEach(m => {
                if (m !== menu) {
                    const wrap = m.closest('.custom-select-wrapper');
                    if (wrap && wrap._closeCustomMenu) {
                        wrap._closeCustomMenu();
                    } else {
                        m.classList.remove('show');
                        m.previousElementSibling?.classList.remove('active');
                        wrap?.classList.remove('dropup');
                    }
                }
            });
            renderMenuOptions();
            menu.classList.add('show');
            trigger.classList.add('active');

            // Auto-detect available space below/above on open
            updatePosition();

            // Re-evaluate positioning dynamically on scroll and resize events
            window.addEventListener('scroll', updatePosition, true);
            window.addEventListener('resize', updatePosition);

            if (searchInput) {
                setTimeout(() => searchInput.focus(), 50);
            }
        };

        const closeMenu = () => {
            menu.classList.remove('show');
            trigger.classList.remove('active');
            wrapper.classList.remove('dropup');

            // Remove scroll and resize listeners on menu close
            window.removeEventListener('scroll', updatePosition, true);
            window.removeEventListener('resize', updatePosition);
        };

        wrapper._closeCustomMenu = closeMenu;

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            if (menu.classList.contains('show')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        trigger.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (menu.classList.contains('show')) closeMenu(); else openMenu();
            } else if (e.key === 'Escape') {
                closeMenu();
            }
        });

        // Sync focus when native select or associated label is focused
        select.addEventListener('focus', () => {
            trigger.focus();
        });

        // Handle select disabled or invalid state updates
        select.addEventListener('change', () => {
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption) {
                labelSpan.textContent = selectedOption.text;
                if (selectedOption.value === '' || selectedOption.value === null) {
                    trigger.classList.add('is-placeholder');
                } else {
                    trigger.classList.remove('is-placeholder');
                }
            }
            if (select.classList.contains('is-invalid')) {
                trigger.classList.add('is-invalid');
            } else {
                trigger.classList.remove('is-invalid');
            }
        });

        // MutationObserver to update custom menu if <select> options change dynamically
        let isObserverRendering = false;
        const observer = new MutationObserver(() => {
            if (isObserverRendering) return;
            isObserverRendering = true;
            renderMenuOptions();
            isObserverRendering = false;
        });
        observer.observe(select, { childList: true, subtree: true });

        renderMenuOptions();
    });
}

// Global click outside to dismiss open custom select menus
document.addEventListener('click', (e) => {
    if (!e.target.closest('.custom-select-wrapper')) {
        document.querySelectorAll('.custom-select-menu.show').forEach(m => {
            const wrap = m.closest('.custom-select-wrapper');
            if (wrap && wrap._closeCustomMenu) {
                wrap._closeCustomMenu();
            } else {
                m.classList.remove('show');
                m.previousElementSibling?.classList.remove('active');
                wrap?.classList.remove('dropup');
            }
        });
    }
});

// Auto-initialize custom selects and flatpickr instances on page load
document.addEventListener('DOMContentLoaded', () => {
    initCustomSelects();
    initFlatpickrs();
});

/**
 * 7. initFlatpickrs(container)
 * Auto-initializes Flatpickr instances on [data-flatpickr] elements
 */
function initFlatpickrs(container = document) {
    if (typeof flatpickr === 'undefined') return;

    const scope = typeof container === 'string' ? document.querySelector(container) : container;
    if (!scope) return;

    const inputs = scope.querySelectorAll('[data-flatpickr="true"], input.flatpickr, input[type="date"].use-flatpickr, input[type="datetime-local"].use-flatpickr');

    inputs.forEach(input => {
        if (input.dataset.flatpickrInit === 'true' || input._flatpickr) return;
        input.dataset.flatpickrInit = 'true';

        const enableTime = input.dataset.enableTime === 'true';
        const noCalendar = input.dataset.noCalendar === 'true';
        const mode = input.dataset.mode || 'single';
        const time24hr = input.dataset.time24hr === 'true' || input.dataset.time_24hr === 'true';
        const dateFormat = input.dataset.dateFormat || (noCalendar ? (time24hr ? 'H:i' : 'h:i K') : (enableTime ? (time24hr ? 'd-m-Y H:i' : 'd-m-Y h:i K') : 'd-m-Y'));
        const altFormat = input.dataset.altFormat || null;
        const altInput = input.dataset.altInput === 'true' || !!altFormat;
        const minDate = input.dataset.minDate || null;
        const maxDate = input.dataset.maxDate || null;

        const config = {
            enableTime: enableTime,
            noCalendar: noCalendar,
            mode: mode,
            dateFormat: dateFormat,
            time_24hr: time24hr,
            allowInput: true,
            disableMobile: true,
            monthSelectorType: 'dropdown',
        };

        if (altInput) {
            config.altInput = true;
            config.altFormat = altFormat || (noCalendar ? (time24hr ? 'H:i' : 'h:i K') : (enableTime ? (time24hr ? 'M d, Y H:i' : 'M d, Y h:i K') : 'M d, Y'));
            config.altInputClass = input.className.replace('flatpickr-input-target', '') + ' flatpickr-alt-input';
        }

        if (minDate) config.minDate = minDate;
        if (maxDate) config.maxDate = maxDate;

        const fp = flatpickr(input, config);

        // Bind click on icon suffix trigger button to open flatpickr instance
        const wrapper = input.closest('.flatpickr-wrapper-input') || input.parentElement;
        if (wrapper) {
            const toggleBtn = wrapper.querySelector('.flatpickr-toggle-btn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    fp.open();
                });
            }
        }
    });
}

/**
 * Global helper to export table/API data to CSV
 */
async function exportTableData(options = {}) {
    const {
        url,
        filename = 'export.csv',
        headers = [],
        formatRow,
        params = {},
        btnId = 'btnExport'
    } = options;

    const btn = document.getElementById(btnId);
    let originalHtml = '';

    try {
        if (btn) {
            btn.disabled = true;
            originalHtml = btn.innerHTML;
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> <span>Exporting...</span>`;
        }

        const queryParams = new URLSearchParams({
            per_page: 1000,
            ...params
        });

        const data = await apiRequest(`${url}?${queryParams.toString()}`);
        const rawPayload = (data && typeof data === 'object' && data.data) ? data.data : data;
        const records = Array.isArray(rawPayload)
            ? rawPayload
            : (rawPayload && typeof rawPayload === 'object' && Array.isArray(rawPayload.data) ? rawPayload.data : []);

        if (!Array.isArray(records) || records.length === 0) {
            if (typeof showErrorToast === 'function') showErrorToast('No records available to export.');
            return;
        }

        const csvRows = [headers.join(',')];

        records.forEach(item => {
            if (typeof formatRow === 'function') {
                const formatted = formatRow(item);
                if (Array.isArray(formatted)) {
                    const escapedRow = formatted.map(val => {
                        if (val === null || val === undefined) return '""';
                        const str = String(val).replace(/"/g, '""');
                        return `"${str}"`;
                    });
                    csvRows.push(escapedRow.join(','));
                }
            }
        });

        const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const downloadUrl = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = downloadUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(downloadUrl);
        if (typeof showSuccessToast === 'function') showSuccessToast('Data exported successfully.');
    } catch (err) {
        console.error('Export error:', err);
        if (typeof showErrorToast === 'function') showErrorToast(err.message || 'Failed to export data.');
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml || `<i class="fa-solid fa-download"></i> <span>Export</span>`;
        }
    }
}

/* ==========================================================================
   GLOBAL PERMISSIONS MATRIX UTILITY
   ========================================================================== */
if (typeof window.PermissionsMatrix === 'undefined') {
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
}
