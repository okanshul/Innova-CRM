@extends('layouts.app', ['title' => 'InnovaCRM - Calendar & Events'])

@push('styles')
    <!-- FullCalendar 6.1 CDN CSS/JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <style>
        /* Custom FullCalendar UI Overhaul - Pastel Style */
        #calendar {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .fc-header-toolbar {
            margin-bottom: 1rem !important;
            flex-wrap: wrap;
            gap: 0.75rem !important;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: var(--bs-body-color);
            letter-spacing: -0.01em;
        }

        /* Fix Button Group - Separated Pills with Clean Borders */
        .fc-button-group {
            display: inline-flex !important;
            gap: 4px !important;
        }

        .fc-button-group > .fc-button {
            border: 1px solid var(--bs-border-color) !important;
            border-radius: 0.5rem !important;
            margin: 0 !important;
            background-color: var(--bs-body-bg) !important;
            color: var(--bs-body-color) !important;
            font-weight: 600 !important;
            font-size: 0.8rem !important;
            padding: 0.4rem 0.85rem !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
            transition: all 0.2s ease !important;
        }

        .fc-button-group > .fc-button:hover,
        .fc-button-group > .fc-button:focus {
            background-color: rgba(99, 102, 241, 0.08) !important;
            border-color: rgba(99, 102, 241, 0.3) !important;
            color: #6366f1 !important;
            box-shadow: none !important;
        }

        .fc-button-group > .fc-button.fc-button-active,
        .fc-button-group > .fc-button:active {
            background-color: #6366f1 !important;
            border-color: #6366f1 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.25) !important;
        }

        /* Today Button */
        .fc-today-button {
            border: 1px solid var(--bs-border-color) !important;
            border-radius: 0.5rem !important;
            margin-left: 6px !important;
            font-weight: 600 !important;
            font-size: 0.8rem !important;
            padding: 0.4rem 0.85rem !important;
        }

        /* Header day cell titles (SUN, MON, TUE...) */
        .fc-col-header-cell {
            background-color: var(--bs-tertiary-bg);
            padding: 0.5rem 0 !important;
            border-color: var(--bs-border-color-translucent) !important;
        }

        .fc-col-header-cell-cushion,
        .fc-col-header-cell a {
            color: #475569 !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
            text-decoration: none !important;
        }

        /* Grid Borders & Day Top */
        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: var(--bs-border-color-translucent) !important;
        }

        .fc-daygrid-day-top {
            flex-direction: row !important;
            padding: 4px 6px !important;
        }

        .fc-daygrid-day-number {
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            color: var(--bs-body-color) !important;
            text-decoration: none !important;
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        /* Today Highlight */
        .fc-day-today {
            background-color: rgba(99, 102, 241, 0.04) !important;
        }

        .fc-day-today .fc-daygrid-day-number {
            background-color: #6366f1 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
        }

        /* Other Month Days */
        .fc-day-other .fc-daygrid-day-number {
            opacity: 0.35 !important;
        }

        /* Event Badges - Pastel Theme Styles (Exact Match to Reference Image) */
        .fc-daygrid-event-harness {
            margin-bottom: 4px !important;
        }

        .fc-event {
            cursor: pointer !important;
            border-radius: 6px !important;
            padding: 2.5px 7px !important;
            font-size: 0.74rem !important;
            font-weight: 600 !important;
            border: 1px solid transparent !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03) !important;
            transition: transform 0.15s ease, box-shadow 0.15s ease !important;
            line-height: 1.35 !important;
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08) !important;
            z-index: 5 !important;
        }

        /* Category Color Themes */
        .fc-event-meeting {
            background-color: #f3e8ff !important;
            border: 1px solid #d8b4fe !important;
            color: #6b21a8 !important;
        }
        .fc-event-meeting .fc-event-main-frame,
        .fc-event-meeting .fc-event-title {
            color: #6b21a8 !important;
        }
        .fc-event-meeting i {
            color: #7e22ce !important;
        }

        .fc-event-task {
            background-color: #fff7ed !important;
            border: 1px solid #fed7aa !important;
            color: #c2410c !important;
        }
        .fc-event-task .fc-event-main-frame,
        .fc-event-task .fc-event-title {
            color: #c2410c !important;
        }
        .fc-event-task i {
            color: #ea580c !important;
        }

        .fc-event-event {
            background-color: #ecfeff !important;
            border: 1px solid #a5f3fc !important;
            color: #0e7490 !important;
        }
        .fc-event-event .fc-event-main-frame,
        .fc-event-event .fc-event-title {
            color: #0e7490 !important;
        }
        .fc-event-event i {
            color: #0891b2 !important;
        }

        /* +X More Link */
        .fc-daygrid-more-link {
            font-size: 0.725rem !important;
            font-weight: 600 !important;
            color: #6366f1 !important;
            background-color: rgba(99, 102, 241, 0.08) !important;
            padding: 2px 7px !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            display: inline-block !important;
            margin-top: 2px !important;
            transition: background-color 0.15s ease;
        }

        .fc-daygrid-more-link:hover {
            background-color: rgba(99, 102, 241, 0.18) !important;
            color: #4f46e5 !important;
        }

        /* Popover Dialog */
        .fc-popover {
            border-radius: 12px !important;
            border: 1px solid var(--bs-border-color) !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15) !important;
            background-color: var(--bs-body-bg) !important;
            overflow: hidden;
        }

        .fc-popover-header {
            background-color: var(--bs-tertiary-bg) !important;
            font-weight: 700 !important;
            padding: 8px 12px !important;
            font-size: 0.85rem !important;
        }

        /* Sidebar & Layout Fixes */
        @media (min-width: 1200px) {
            .calendar-sidebar-col {
                border-left: 1px solid var(--bs-border-color-translucent);
                padding-left: 1.25rem !important;
            }
            .calendar-main-col {
                padding-right: 1.25rem !important;
            }
        }

        .upcoming-events-list {
            max-height: 900px;
            overflow-y: auto;
        }

        .upcoming-events-list::-webkit-scrollbar {
            width: 5px;
        }
        .upcoming-events-list::-webkit-scrollbar-track {
            background: transparent;
        }
        .upcoming-events-list::-webkit-scrollbar-thumb {
            background: var(--bs-border-color);
            border-radius: 10px;
        }
        .upcoming-events-list::-webkit-scrollbar-thumb:hover {
            background: var(--bs-secondary-color);
        }

        .upcoming-card {
            transition: all 0.2s ease;
            border-left: 3.5px solid #6366f1 !important;
        }
        .upcoming-card:hover {
            transform: translateX(3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .calendar-summary-card {
            background: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color-translucent);
            border-radius: 0.75rem;
        }

        /* Category Filter Buttons */
        .filter-btn {
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            border: 1px solid var(--bs-border-color);
            background: var(--bs-body-bg);
            color: var(--bs-secondary-color);
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .filter-btn:hover {
            border-color: rgba(99, 102, 241, 0.4);
            color: #6366f1;
        }
        .filter-btn.active {
            background: #6366f1;
            border-color: #6366f1;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.25);
        }
    </style>
@endpush

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Calendar']]" />

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
        <x-page-header title="Calendar & Events" subtitle="Schedule meetings, track upcoming events, and view task due dates."
            icon="fa-regular fa-calendar-days">
            <x-slot:actions>
                @can('meetings.create')
                    <x-button.primary href="{{ route('meetings.create') }}" icon="fa-solid fa-plus fs-sm"
                        label="Schedule Meeting" />
                @endcan
            </x-slot:actions>
        </x-page-header>

        <div class="card-body p-3">
            <div class="row">
                <!-- Main Calendar View (8 Cols) -->
                <div class="col-12 col-xl-8 calendar-main-col d-flex flex-column justify-content-between">
                    <div>
                        <!-- Filter bar header -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary-subtle text-primary p-2 rounded-circle">
                                    <i class="fa-solid fa-calendar-alt fs-6"></i>
                                </span>
                                <h6 class="fw-bold mb-0 text-body-emphasis">Calendar Overview</h6>
                            </div>
                            <div class="d-flex align-items-center gap-1 flex-wrap" id="eventFilters">
                                <button class="filter-btn active" data-filter="all">All</button>
                                <button class="filter-btn" data-filter="meeting">
                                    <i class="fa-solid fa-calendar-days me-1 fs-xs"></i> Meetings
                                </button>
                                <button class="filter-btn" data-filter="task">
                                    <i class="fa-solid fa-circle-check me-1 fs-xs"></i> Tasks
                                </button>
                                <button class="filter-btn" data-filter="event">
                                    <i class="fa-solid fa-calendar-day me-1 fs-xs"></i> Events
                                </button>
                            </div>
                        </div>

                        <!-- FullCalendar Container -->
                        <div id="calendar" style="min-height: 850px;"></div>
                    </div>

                    <!-- Calendar Legend Footer -->
                    <div class="d-flex align-items-center justify-content-center gap-4 flex-wrap pt-3 text-secondary small">
                        <span class="d-flex align-items-center gap-1">
                            <i class="fa-solid fa-calendar-days me-1 fs-xs" style="color: #8b5cf6;"></i>
                            <span class="fw-semibold text-body-secondary">Meetings ({{ $meetings->count() }})</span>
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <i class="fa-solid fa-circle-check me-1 fs-xs" style="color: #f59e0b;"></i>
                            <span class="fw-semibold text-body-secondary">Tasks Due ({{ $tasks->count() }})</span>
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <i class="fa-solid fa-calendar-day me-1 fs-xs" style="color: #06b6d4;"></i>
                            <span class="fw-semibold text-body-secondary">Calendar Events ({{ $events->count() }})</span>
                        </span>
                    </div>
                </div>

                <!-- Upcoming Events Sidebar (4 Cols) -->
                <div class="col-12 col-xl-4 calendar-sidebar-col d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <h6 class="fw-bold mb-0 text-body-emphasis d-flex align-items-center gap-2">
                                <i class="fa-solid fa-clock text-primary"></i> Upcoming Schedule
                            </h6>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 fs-xs fw-semibold">
                                {{ $upcomingMeetings->count() }} Items
                            </span>
                        </div>

                        <div class="upcoming-events-list pe-1">
                            @forelse($upcomingMeetings as $meeting)
                                <div class="card border-0 bg-body-tertiary rounded-3 p-3 mb-2 upcoming-card">
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                        <h6 class="fw-semibold mb-0 text-body-emphasis fs-sm text-truncate"
                                            title="{{ $meeting->title }}">
                                            {{ $meeting->title }}
                                        </h6>
                                        <span class="badge rounded-pill bg-primary-subtle text-primary fs-xs fw-medium px-2 py-0.5">
                                            Meeting
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 text-secondary fs-xs mb-2">
                                        <span>
                                            <i class="fa-regular fa-clock me-1 text-primary"></i>
                                            {{ $meeting->start_at ? $meeting->start_at->format('M d, h:i A') : 'TBD' }}
                                        </span>
                                        @if ($meeting->location)
                                            <span class="text-truncate" style="max-width: 130px;">
                                                <i class="fa-solid fa-location-dot me-1 text-danger"></i>
                                                {{ $meeting->location }}
                                            </span>
                                        @endif
                                    </div>
                                    @if ($meeting->host)
                                        <div class="pt-2 border-top border-light-subtle d-flex align-items-center justify-content-between text-secondary fs-xs">
                                            <span>
                                                <i class="fa-solid fa-user-tie me-1 text-secondary"></i> Host:
                                                <strong class="text-body-emphasis">{{ $meeting->host->name }}</strong>
                                            </span>
                                            <a href="{{ route('meetings.show', $meeting->id) }}"
                                                class="text-primary text-decoration-none fw-semibold">View <i
                                                    class="fa-solid fa-arrow-right fs-xs ms-0.5"></i></a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-5 text-secondary">
                                    <i class="fa-regular fa-calendar-xmark fs-2 mb-2 text-muted"></i>
                                    <p class="mb-0 small fw-medium">No upcoming meetings scheduled.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Event Detail Modal -->
    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4 bg-body">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalEventTitle">Event Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-1 fw-semibold fs-xs"
                            id="modalEventType">Meeting</span>
                        <span class="badge rounded-pill bg-success-subtle text-success px-2.5 py-1 fs-xs fw-semibold d-none"
                            id="modalEventStatus">Active</span>
                    </div>

                    <div class="bg-body-tertiary rounded-3 p-3 mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2 text-body-emphasis small fw-medium">
                            <i class="fa-regular fa-clock text-primary"></i>
                            <span id="modalEventTime">Start - End Time</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-secondary small d-none"
                            id="modalEventLocationWrapper">
                            <i class="fa-solid fa-location-dot text-danger"></i>
                            <span id="modalEventLocation">Location</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-semibold text-body-emphasis fs-xs text-uppercase mb-1" style="letter-spacing: 0.5px;">
                            Description</h6>
                        <p class="text-secondary small mb-0" id="modalEventDescription">Description text...</p>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-light border rounded-3 px-3 shadow-none"
                        data-bs-dismiss="modal">Close</button>
                    <a href="#" id="modalEventLink"
                        class="btn btn-sm btn-primary rounded-3 px-3 shadow-none d-none">View Details</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            const rawCalendarItems = @json($calendarItems ?? []);
            let activeFilter = 'all';

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    week: 'Week',
                    day: 'Day',
                    list: 'List'
                },
                editable: false,
                selectable: true,
                dayMaxEvents: 3,
                eventDisplay: 'block',
                eventClassNames: function(arg) {
                    const cat = arg.event.extendedProps.category || 'event';
                    return ['fc-event-' + cat];
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    if (activeFilter === 'all') {
                        successCallback(rawCalendarItems);
                    } else {
                        const filtered = rawCalendarItems.filter(item => {
                            const cat = item.extendedProps ? item.extendedProps.category : '';
                            return cat === activeFilter;
                        });
                        successCallback(filtered);
                    }
                },
                eventContent: function(arg) {
                    const props = arg.event.extendedProps || {};
                    let iconClass = props.icon || 'fa-calendar';
                    let iconHtml = `<i class="fa-solid ${iconClass} me-1 fs-xs"></i>`;

                    let timeHtml = '';
                    if (!arg.event.allDay && arg.event.start) {
                        let timeStr = arg.event.start.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true }).toLowerCase();
                        timeHtml = `<span class="opacity-80 me-1 fw-normal text-nowrap">${timeStr}</span>`;
                    }

                    let titleText = arg.event.title.replace(/^[📅✓📌\s]+/, '');

                    return {
                        html: `<div class="fc-event-main-frame d-flex align-items-center text-truncate px-1 py-0.5">
                                    ${iconHtml}
                                    ${timeHtml}
                                    <span class="fc-event-title text-truncate fw-semibold">${titleText}</span>
                               </div>`
                    };
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    const props = info.event.extendedProps || {};

                    let cleanTitle = info.event.title.replace(/^[📅✓📌\s]+/, '');
                    document.getElementById('modalEventTitle').textContent = cleanTitle;
                    document.getElementById('modalEventType').textContent = props.type || 'Event';

                    // Format times
                    let timeStr = info.event.start ? info.event.start.toLocaleString([], {
                        dateStyle: 'medium',
                        timeStyle: 'short'
                    }) : 'TBD';
                    if (info.event.end) {
                        timeStr += ' - ' + info.event.end.toLocaleTimeString([], {
                            timeStyle: 'short'
                        });
                    }
                    document.getElementById('modalEventTime').textContent = timeStr;

                    // Location
                    const locWrap = document.getElementById('modalEventLocationWrapper');
                    if (props.location && props.location !== 'N/A') {
                        document.getElementById('modalEventLocation').textContent = props.location;
                        locWrap.classList.remove('d-none');
                    } else {
                        locWrap.classList.add('d-none');
                    }

                    // Description
                    document.getElementById('modalEventDescription').textContent = props.description ||
                        'No description provided.';

                    // Detail Link
                    const linkBtn = document.getElementById('modalEventLink');
                    if (props.url) {
                        linkBtn.href = props.url;
                        linkBtn.classList.remove('d-none');
                    } else {
                        linkBtn.classList.add('d-none');
                    }

                    const modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                    modal.show();
                }
            });

            calendar.render();

            // Filter button event listeners
            const filterBtns = document.querySelectorAll('#eventFilters .filter-btn');
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    activeFilter = this.getAttribute('data-filter');
                    calendar.refetchEvents();
                });
            });
        });
    </script>
@endpush
