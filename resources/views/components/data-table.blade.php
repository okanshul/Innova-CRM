@props([
    'id' => 'staffTable',
    'headers' => [],
    'showCheckboxColumn' => false,
    'tableBodyId' => 'staffTableBody',
    'paginationRowId' => 'tablePaginationRow',
    'summaryId' => 'paginationSummary',
    'controlsId' => 'paginationControls',
    'selectAllId' => 'selectAll',
])

<div {{ $attributes->merge(['class' => '']) }}>
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="{{ $id }}">
            <thead class="border-bottom bg-body-tertiary">
                <tr class="text-secondary fw-bold" style="font-size: 0.75rem; letter-spacing: 0.03em;">
                    @if($showCheckboxColumn)
                        <th class="ps-3 py-3" style="width: 40px;">
                            <input type="checkbox" class="form-check-input custom-checkbox" id="{{ $selectAllId }}">
                        </th>
                    @endif

                    @if(isset($headerSlot) && $headerSlot->isNotEmpty())
                        {{ $headerSlot }}
                    @elseif(is_array($headers))
                        @foreach($headers as $index => $header)
                            @php
                                $isLast = $loop->last;
                                $isFirst = $loop->first && !$showCheckboxColumn;
                                $alignClass = (is_array($header) && isset($header['align'])) ? "text-{$header['align']}" : ($isLast ? 'text-end pe-3' : '');
                                $title = is_array($header) ? ($header['title'] ?? '') : $header;
                                $paddingClass = $isFirst ? 'ps-3 py-3' : ($isLast ? 'pe-3 py-3' : 'py-3');
                            @endphp
                            <th class="{{ $paddingClass }} {{ $alignClass }}">
                                {{ strtoupper($title) }}
                            </th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody id="{{ $tableBodyId }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    <!-- Footer Pagination -->
    <div class="d-flex flex-wrap align-items-center justify-content-between p-3 bg-body rounded-bottom-4" id="{{ $paginationRowId }}">
        <div class="text-secondary small fw-medium" id="{{ $summaryId }}">
            Showing 0 entries
        </div>
        <div class="d-flex align-items-center gap-2" id="{{ $controlsId }}">
            <!-- Dynamic JS Pagination -->
        </div>
    </div>
</div>
