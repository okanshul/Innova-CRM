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
            tableBody.innerHTML = `
                <tr>
                    <td colspan="100" class="text-center py-5 text-secondary">${emptyMessage}</td>
                </tr>
            `;
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
                    <i class="fa-solid fa-circle-exclamation me-1.5"></i> Failed to load data. Please try again.
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
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> Submitting...';
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

                        const container = input.closest('.position-relative') || input.parentNode;
                        container.appendChild(errorDiv);
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
            const container = this.closest('.position-relative') || this.parentNode;
            const errDiv = container.querySelector('.invalid-feedback-ajax');
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
        if (select.dataset.customSelectInit === 'true' || select.closest('.custom-select-wrapper')) return;
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

        const renderMenuOptions = () => {
            menu.innerHTML = '';
            const selectedOption = select.options[select.selectedIndex] || select.options[0];
            labelSpan.textContent = selectedOption ? selectedOption.text : (select.getAttribute('placeholder') || 'Select...');

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

                    select.selectedIndex = idx;
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    select.dispatchEvent(new Event('input', { bubbles: true }));
                    closeMenu();
                });

                menu.appendChild(optItem);
            });
        };

        const openMenu = () => {
            if (select.disabled || trigger.classList.contains('disabled')) return;
            document.querySelectorAll('.custom-select-menu.show').forEach(m => {
                if (m !== menu) {
                    m.classList.remove('show');
                    m.previousElementSibling?.classList.remove('active');
                }
            });
            renderMenuOptions();
            menu.classList.add('show');
            trigger.classList.add('active');
        };

        const closeMenu = () => {
            menu.classList.remove('show');
            trigger.classList.remove('active');
        };

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
            }
            if (select.classList.contains('is-invalid')) {
                trigger.classList.add('is-invalid');
            } else {
                trigger.classList.remove('is-invalid');
            }
            renderMenuOptions();
        });

        // MutationObserver to update custom menu if <select> options change dynamically
        const observer = new MutationObserver(() => {
            renderMenuOptions();
        });
        observer.observe(select, { childList: true, subtree: true, attributes: true });

        renderMenuOptions();
    });
}

// Global click outside to dismiss open custom select menus
document.addEventListener('click', (e) => {
    if (!e.target.closest('.custom-select-wrapper')) {
        document.querySelectorAll('.custom-select-menu.show').forEach(m => {
            m.classList.remove('show');
            m.previousElementSibling?.classList.remove('active');
        });
    }
});

// Auto-initialize custom selects on page load
document.addEventListener('DOMContentLoaded', () => {
    initCustomSelects();
});

