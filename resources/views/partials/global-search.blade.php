<!-- Global Search Command Palette Modal -->
<div class="modal fade" id="globalSearchModal" tabindex="-1" aria-labelledby="globalSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background-color: var(--bs-body-bg);">
            
            <!-- Modal Header & Search Bar -->
            <div class="p-3 pb-0 border-bottom position-relative bg-body-tertiary bg-opacity-25">
                <!-- Search Input Card -->
                <div class="search-input-box w-100 d-flex align-items-center bg-body rounded-3 px-3 py-1 border shadow-sm">
                    <span class="text-primary me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <input type="text" 
                           id="globalSearchInput" 
                           class="form-control border-0 bg-transparent shadow-none fs-6 py-2 px-1 flex-grow-1 w-100" 
                           placeholder="Search contacts, deals, tasks, meetings..." 
                           autocomplete="off"
                           style="outline: none !important; box-shadow: none !important;">
                    
                    <!-- Search Spinner (Hidden by default) -->
                    <div id="globalSearchSpinner" class="spinner-border spinner-border-sm text-primary me-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    <!-- Clear Query Button -->
                    <button id="globalSearchClear" type="button" class="btn btn-search-icon-action text-secondary p-0 text-decoration-none me-1 d-none" title="Clear Search">
                        <i class="fa-solid fa-circle-xmark fs-5"></i>
                    </button>

                    <!-- Close Modal Button -->
                    <button type="button" class="btn btn-search-icon-action text-secondary p-0 text-decoration-none ms-1" data-bs-dismiss="modal" aria-label="Close" title="Close Search (Esc)">
                        <i class="fa-solid fa-xmark fs-5"></i>
                    </button>
                </div>

                <!-- Category Filters Pill Bar -->
                <div class="d-flex align-items-center gap-2 my-3 overflow-x-auto text-nowrap no-scrollbar" id="globalSearchCategories">
                    <button type="button" class="btn btn-sm filter-category-btn active" data-category="all">
                        <i class="fa-solid fa-layer-group fs-7"></i> All
                    </button>
                    <button type="button" class="btn btn-sm filter-category-btn" data-category="contacts">
                        <i class="fa-solid fa-address-book fs-7"></i> Contacts
                    </button>
                    <button type="button" class="btn btn-sm filter-category-btn" data-category="deals">
                        <i class="fa-solid fa-handshake fs-7"></i> Deals
                    </button>
                    <button type="button" class="btn btn-sm filter-category-btn" data-category="tasks">
                        <i class="fa-solid fa-list-check fs-7"></i> Tasks
                    </button>
                    <button type="button" class="btn btn-sm filter-category-btn" data-category="meetings">
                        <i class="fa-solid fa-calendar-days fs-7"></i> Meetings
                    </button>
                    <button type="button" class="btn btn-sm filter-category-btn" data-category="staff">
                        <i class="fa-solid fa-user-gear fs-7"></i> Staff
                    </button>
                    <button type="button" class="btn btn-sm filter-category-btn" data-category="companies">
                        <i class="fa-solid fa-building fs-7"></i> Companies
                    </button>
                </div>
            </div>

            <!-- Modal Results Container -->
            <div id="globalSearchResults" class="p-2 p-sm-3 py-2" style="max-height: 420px; overflow-y: auto;">
                <!-- Content will be injected dynamically by global-search.js -->
            </div>

            <!-- Modal Footer Controls -->
            <div class="px-3 py-2 border-top bg-body-tertiary d-flex align-items-center justify-content-between text-secondary" style="font-size: 0.775rem;">
                <div class="d-none d-md-flex align-items-center gap-3">
                    <span class="d-flex align-items-center gap-1">
                        <kbd class="bg-body border text-body px-2 rounded">↑</kbd>
                        <kbd class="bg-body border text-body px-2 rounded">↓</kbd> navigate
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <kbd class="bg-body border text-body px-2 rounded">↵</kbd> select
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <kbd class="bg-body border text-body px-2 rounded">esc</kbd> close
                    </span>
                </div>
                <span class="d-md-none text-muted opacity-75">
                    <i class="fa-solid fa-arrow-pointer me-1"></i> Tap to open
                </span>
                <div id="globalSearchResultCount" class="fw-medium text-muted text-nowrap ms-auto">
                    Quick Navigation
                </div>
            </div>

        </div>
    </div>
</div>
