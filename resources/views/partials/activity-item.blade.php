<div class="d-flex justify-content-between align-items-start py-2 px-0 bg-transparent opacity-90">
    <div class="d-flex gap-2 align-items-start">
        <div class="avatar bg-{{ $activity['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold mt-1 shadow-sm"
            style="width: 32px; height: 32px; font-size: 0.7rem;">
            {{ $activity['initials'] }}
        </div>
        <div>
            <p class="mb-1 text-body-emphasis" style="font-size: 0.775rem; line-height: 1.25;">
                <span class="fw-bold">{{ $activity['user'] }}</span>
                <span class="text-secondary fw-normal ms-1">{{ $activity['action'] }}</span>
            </p>
            <p class="mb-0 text-secondary d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                <i class="{{ $activity['icon'] }}" style="font-size: 0.75rem;"></i>
                <span class="text-truncate d-inline-block" style="max-width: 210px;">{{ $activity['note'] }}</span>
            </p>
        </div>
    </div>
    <span class="text-secondary flex-shrink-0 ms-2" style="font-size: 0.65rem;">{{ $activity['time'] }}</span>
</div>
