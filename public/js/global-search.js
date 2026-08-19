/**
 * InnovaCRM Global Search & Command Palette Controller
 */
document.addEventListener('DOMContentLoaded', () => {
    const searchModalElement = document.getElementById('globalSearchModal');
    if (!searchModalElement) return;

    const searchInput = document.getElementById('globalSearchInput');
    const searchResultsContainer = document.getElementById('globalSearchResults');
    const searchSpinner = document.getElementById('globalSearchSpinner');
    const searchClearBtn = document.getElementById('globalSearchClear');
    const searchCountBadge = document.getElementById('globalSearchResultCount');
    const categoryContainer = document.getElementById('globalSearchCategories');

    let searchModalInstance = null;
    let debounceTimer = null;
    let currentCategory = 'all';
    let currentResultsList = [];
    let selectedResultIndex = -1;

    // Get Bootstrap Modal Instance safely
    function getModalInstance() {
        if (!searchModalInstance && typeof bootstrap !== 'undefined') {
            searchModalInstance = bootstrap.Modal.getOrCreateInstance(searchModalElement);
        }
        return searchModalInstance;
    }

    // Open Modal Function
    function openGlobalSearch() {
        const modal = getModalInstance();
        if (modal) {
            modal.show();
        }
    }

    // Bind Keyboard Shortcut Ctrl+K / Cmd+K
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            openGlobalSearch();
        }
    });

    // Bind Click Triggers on Desktop & Mobile Search inputs/buttons
    document.querySelectorAll('#headerSearchTrigger, .header-search-input, [data-bs-target="#mobileSearchCollapse"]').forEach(el => {
        el.addEventListener('click', (e) => {
            // Prevent default behavior and open global search modal
            if (el.tagName === 'INPUT') {
                el.blur();
            }
            openGlobalSearch();
        });
    });

    // Auto-focus input when modal opens
    searchModalElement.addEventListener('shown.bs.modal', () => {
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
        performSearch();
    });

    // Reset state on modal hide
    searchModalElement.addEventListener('hidden.bs.modal', () => {
        selectedResultIndex = -1;
    });

    // Category Pill Filters click listener
    if (categoryContainer) {
        categoryContainer.querySelectorAll('.filter-category-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                categoryContainer.querySelectorAll('.filter-category-btn').forEach(b => {
                    b.classList.remove('active');
                });
                btn.classList.add('active');

                currentCategory = btn.getAttribute('data-category') || 'all';
                performSearch();
            });
        });
    }

    // Clear Input Button logic
    if (searchClearBtn) {
        searchClearBtn.addEventListener('click', () => {
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
                performSearch();
            }
        });
    }

    // Search Input key event handling (Debounce + Navigation)
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const val = searchInput.value.trim();
            if (searchClearBtn) {
                if (val.length > 0) {
                    searchClearBtn.classList.remove('d-none');
                } else {
                    searchClearBtn.classList.add('d-none');
                }
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                performSearch();
            }, 250);
        });

        searchInput.addEventListener('keydown', handleKeyNavigation);
    }

    // Perform Search API Request
    async function performSearch() {
        const query = searchInput ? searchInput.value.trim() : '';

        if (searchSpinner) searchSpinner.classList.remove('d-none');

        try {
            const endpoint = `/api/search?q=${encodeURIComponent(query)}&category=${encodeURIComponent(currentCategory)}`;
            const response = await apiRequest(endpoint);

            if (searchSpinner) searchSpinner.classList.add('d-none');

            if (response && response.success) {
                if (query.length === 0) {
                    renderQuickLinks(response.quick_links || []);
                } else {
                    renderResults(response.results || {}, response.total || 0, query);
                }
            }
        } catch (err) {
            console.error('Global search error:', err);
            if (searchSpinner) searchSpinner.classList.add('d-none');
            renderError();
        }
    }

    // Render Quick Links when Query is Empty
    function renderQuickLinks(links) {
        selectedResultIndex = -1;
        currentResultsList = links;

        if (!links || links.length === 0) {
            searchResultsContainer.innerHTML = `<div class="text-center p-4 text-secondary">No quick links available</div>`;
            return;
        }

        let html = `<div class="p-1 mb-1 text-secondary fw-semibold fs-7" style="letter-spacing: 0.05em;">Quick Navigation</div>`;
        html += `<div class="list-group list-group-flush border-0">`;

        links.forEach((item, index) => {
            html += createResultItemHTML(item, index);
        });

        html += `</div>`;
        searchResultsContainer.innerHTML = html;

        if (searchCountBadge) {
            searchCountBadge.textContent = `${links.length} Quick Links`;
        }

        bindItemHoverAndClick();
    }

    // Render Search Results
    function renderResults(resultsObj, totalCount, query) {
        selectedResultIndex = -1;
        currentResultsList = [];

        if (totalCount === 0) {
            searchResultsContainer.innerHTML = `
                <div class="text-center py-5 px-3">
                    <div class="bg-body-tertiary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="fa-solid fa-magnifying-glass fs-3 text-secondary"></i>
                    </div>
                    <h6 class="fw-bold mb-1">No results found for "${escapeHtml(query)}"</h6>
                    <p class="text-secondary small mb-0">Try searching for contacts, deals, tasks, staff, or companies by keyword.</p>
                </div>
            `;
            if (searchCountBadge) {
                searchCountBadge.textContent = '0 results';
            }
            return;
        }

        let html = '';
        let globalIndex = 0;

        const categoryTitles = {
            contacts: 'Contacts',
            deals: 'Deals',
            tasks: 'Tasks',
            meetings: 'Meetings',
            staff: 'Staff',
            companies: 'Companies'
        };

        for (const [catKey, items] of Object.entries(resultsObj)) {
            if (!items || items.length === 0) continue;

            html += `<div class="px-1 pt-2 pb-1 text-uppercase text-secondary fw-semibold fs-7 d-flex align-items-center justify-content-between" style="letter-spacing: 0.05em;">
                <span>${categoryTitles[catKey] || catKey}</span>
                <span class="badge bg-secondary-subtle text-secondary rounded-pill">${items.length}</span>
            </div>`;
            html += `<div class="list-group list-group-flush border-0 mb-2">`;

            items.forEach((item) => {
                currentResultsList.push(item);
                html += createResultItemHTML(item, globalIndex);
                globalIndex++;
            });

            html += `</div>`;
        }

        searchResultsContainer.innerHTML = html;

        if (searchCountBadge) {
            searchCountBadge.textContent = `${totalCount} ${totalCount === 1 ? 'result' : 'results'} found`;
        }

        bindItemHoverAndClick();
    }

    // Helper to generate result item HTML
    function createResultItemHTML(item, index) {
        return `
            <a href="${item.url}" 
               class="list-group-item list-group-item-action border-0 rounded-3 p-2 mb-1 d-flex align-items-center justify-content-between search-result-item" 
               data-index="${index}">
                <div class="d-flex align-items-center gap-3 text-truncate">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 bg-body-tertiary border text-primary" style="width: 38px; height: 38px;">
                        <i class="${item.icon || 'fa-solid fa-link'} fs-6"></i>
                    </div>
                    <div class="text-truncate">
                        <div class="fw-semibold text-body-emphasis lh-sm text-truncate fs-6">${escapeHtml(item.title)}</div>
                        <div class="text-secondary small text-truncate mt-1" style="font-size: 0.775rem;">${escapeHtml(item.subtitle)}</div>
                    </div>
                </div>
                ${item.badge ? `<span class="badge ${item.badge_class || 'bg-light text-dark'} rounded-pill ms-2 flex-shrink-0" style="font-size: 0.7rem;">${escapeHtml(item.badge)}</span>` : ''}
            </a>
        `;
    }

    // Render Error State
    function renderError() {
        searchResultsContainer.innerHTML = `
            <div class="text-center py-4 text-danger small">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> An error occurred while searching. Please try again.
            </div>
        `;
    }

    // Bind Hover and Click on items
    function bindItemHoverAndClick() {
        const items = searchResultsContainer.querySelectorAll('.search-result-item');
        items.forEach((item) => {
            item.addEventListener('mouseenter', () => {
                const idx = parseInt(item.getAttribute('data-index'), 10);
                setSelectedIndex(idx);
            });
        });
    }

    // Key Navigation (ArrowUp, ArrowDown, Enter)
    function handleKeyNavigation(e) {
        const items = searchResultsContainer.querySelectorAll('.search-result-item');
        if (!items || items.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            let nextIndex = selectedResultIndex + 1;
            if (nextIndex >= items.length) nextIndex = 0;
            setSelectedIndex(nextIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            let prevIndex = selectedResultIndex - 1;
            if (prevIndex < 0) prevIndex = items.length - 1;
            setSelectedIndex(prevIndex);
        } else if (e.key === 'Enter') {
            if (selectedResultIndex >= 0 && selectedResultIndex < currentResultsList.length) {
                e.preventDefault();
                const targetUrl = currentResultsList[selectedResultIndex].url;
                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            }
        }
    }

    // Highlight Selected Index
    function setSelectedIndex(index) {
        const items = searchResultsContainer.querySelectorAll('.search-result-item');
        items.forEach((item, i) => {
            if (i === index) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            } else {
                item.classList.remove('active');
            }
        });
        selectedResultIndex = index;
    }

    // Utility HTML Escaper
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
});
