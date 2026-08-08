@php
    $idPrefix = $idPrefix ?? 'perm';
    $directPermissions = $directPermissions ?? [];
    $rolePermissions = $rolePermissions ?? [];

    $modulesMeta = [
        'staff' => ['title' => 'Staff Management', 'icon' => 'fa-user-group'],
        'contacts' => ['title' => 'Contacts', 'icon' => 'fa-address-book'],
        'deals' => ['title' => 'Deals', 'icon' => 'fa-handshake'],
        'pipeline' => ['title' => 'Pipeline', 'icon' => 'fa-diagram-project'],
        'reports' => ['title' => 'Reports', 'icon' => 'fa-chart-pie'],
        'tasks' => ['title' => 'Tasks', 'icon' => 'fa-list-check'],
        'settings' => ['title' => 'Settings', 'icon' => 'fa-gear'],
    ];

    $actions = ['view', 'create', 'edit', 'delete'];
@endphp

<style>
    .perm-matrix-card .form-check-input {
        width: 1rem !important;
        height: 1rem !important;
        cursor: pointer;
        margin-top: 0 !important;
    }
    .perm-matrix-card .table td {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
</style>

<div class="card border rounded-3 shadow-none overflow-hidden perm-matrix-card" id="permissionsMatrixContainer">
    <div class="card-header bg-body-tertiary d-flex align-items-center justify-content-between py-2.5 px-3 border-bottom">
        <div class="d-flex align-items-center gap-2 fw-semibold text-body-emphasis small">
            <i class="fa-solid fa-shield-halved" style="color: #6366F1;"></i>
            <span>Module Permissions Matrix</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" id="selectAllPermissions" class="btn btn-sm btn-purple-light fw-semibold border-0 perm-global-select-all" style="font-size: 0.775rem; color: #6366F1; background-color: #f3e8ff;">
                <i class="fa-solid fa-check-double me-1"></i> Select All Permissions
            </button>
            <button type="button" id="clearAllPermissions" class="btn btn-sm btn-light border text-secondary fw-semibold perm-global-clear-all" style="font-size: 0.775rem;">
                <i class="fa-solid fa-xmark me-1"></i> Clear All
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="permissionsMatrix" style="font-size: 0.85rem;">
            <thead class="bg-body-tertiary border-bottom text-secondary">
                <tr class="fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.03em;">
                    <th class="ps-3 py-2 text-uppercase">Module</th>
                    <th class="text-center py-2 text-uppercase" style="width: 15%;">View</th>
                    <th class="text-center py-2 text-uppercase" style="width: 15%;">Create</th>
                    <th class="text-center py-2 text-uppercase" style="width: 15%;">Edit</th>
                    <th class="text-center py-2 text-uppercase" style="width: 15%;">Delete</th>
                    <th class="pe-3 text-center py-2 text-uppercase" style="width: 15%;">All</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedPermissions as $group => $perms)
                    @php
                        $meta = $modulesMeta[$group] ?? ['title' => ucfirst($group), 'icon' => 'fa-folder'];
                        $groupPermMap = [];
                        foreach ($perms as $p) {
                            $parts = explode('.', $p);
                            $act = $parts[1] ?? '';
                            $groupPermMap[$act] = $p;
                        }

                        $allRowChecked = true;
                    @endphp
                    <tr class="perm-module-row" data-module="{{ $group }}" data-group="{{ $group }}">
                        <td class="ps-3 py-2 fw-semibold text-body-emphasis">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid {{ $meta['icon'] }}" style="color: #6366F1; width: 16px;"></i>
                                <span>{{ $meta['title'] }}</span>
                            </div>
                        </td>
                        @foreach($actions as $action)
                            <td class="text-center py-2">
                                @if(isset($groupPermMap[$action]))
                                    @php
                                        $perm = $groupPermMap[$action];
                                        $isChecked = in_array($perm, $rolePermissions) || in_array($perm, $directPermissions);
                                        $inputCssId = $idPrefix . '_' . str_replace('.', '_', $perm);

                                        if (!$isChecked) $allRowChecked = false;
                                    @endphp
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <input class="form-check-input custom-checkbox perm-checkbox"
                                               type="checkbox"
                                               name="permissions[]"
                                               value="{{ $perm }}"
                                               id="{{ $inputCssId }}"
                                               data-module="{{ $group }}"
                                               data-group="{{ $group }}"
                                               data-action="{{ $action }}"
                                               data-permission="{{ $perm }}"
                                               {{ $isChecked ? 'checked' : '' }}>
                                    </div>
                                @else
                                    <span class="text-body-tertiary fw-light">—</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="pe-3 text-center py-2">
                            <div class="form-check d-inline-block m-0">
                                <input class="form-check-input custom-checkbox perm-row-all perm-row-select-all"
                                       type="checkbox"
                                       id="{{ $idPrefix }}_row_all_{{ $group }}"
                                       data-module="{{ $group }}"
                                       data-group="{{ $group }}"
                                       {{ $allRowChecked ? 'checked' : '' }}
                                       title="Select all permissions for {{ $meta['title'] }}">
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
