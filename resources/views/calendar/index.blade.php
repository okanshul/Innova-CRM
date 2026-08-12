@extends('layouts.app', ['title' => 'InnovaCRM - Calendar'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Calendar']]" />

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
        <x-page-header title="Calendar & Events" subtitle="Schedule meetings and view upcoming task due dates." icon="fa-regular fa-calendar" />

        <div class="p-4">
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="card border p-3 rounded-4 bg-body-tertiary text-center py-5">
                        <i class="fa-regular fa-calendar-days fs-1 text-primary mb-3"></i>
                        <h5 class="fw-bold">Interactive Calendar</h5>
                        <p class="text-secondary small">View scheduled meetings, upcoming events, and task milestones.</p>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card border p-3 rounded-4 bg-body">
                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-clock text-primary me-2"></i>Upcoming Events</h6>
                        <div class="list-group list-group-flush">
                            @forelse($meetings as $meeting)
                                <div class="list-group-item bg-transparent px-0 py-2">
                                    <strong class="d-block text-body-emphasis">{{ $meeting->title }}</strong>
                                    <span class="small text-secondary"><i class="fa-regular fa-clock me-1"></i> {{ $meeting->start_at ? $meeting->start_at->format('M d, g:i A') : 'TBD' }}</span>
                                </div>
                            @empty
                                <div class="text-secondary small py-2">No upcoming meetings scheduled.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
