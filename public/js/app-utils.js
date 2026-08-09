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
