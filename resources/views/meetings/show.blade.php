@extends('layouts.app', ['title' => 'InnovaCRM - Meeting Details'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Meetings', 'url' => route('meetings.index')],
        ['label' => $meeting->title],
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body mb-4">
                <x-page-header :title="$meeting->title" subtitle="Meeting Details" icon="fa-solid fa-video">
                    <x-slot:actions>
                        @can('meetings.edit')
                            <x-button.primary href="{{ route('meetings.edit', $meeting->id) }}" icon="fa-regular fa-pen-to-square me-1" label="Edit" />
                        @endcan
                        <x-button.secondary href="{{ route('meetings.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <div class="p-4">
                    <div class="mb-4">
                        <label class="text-secondary small fw-semibold">Agenda / Description</label>
                        <p class="mb-0 text-body-emphasis">{{ $meeting->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Host</label>
                            <p class="mb-0 text-body-emphasis">{{ $meeting->host->name ?? 'Unassigned' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Status</label>
                            <div><span class="badge bg-primary text-capitalize">{{ $meeting->status }}</span></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Start Time</label>
                            <p class="mb-0 text-body-emphasis">{{ $meeting->start_at ? format_datetime($meeting->start_at) : 'N/A' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">End Time</label>
                            <p class="mb-0 text-body-emphasis">{{ $meeting->end_at ? format_datetime($meeting->end_at) : 'N/A' }}</p>
                        </div>
                        @if($meeting->meeting_link)
                            <div class="col-12">
                                <label class="text-secondary small fw-semibold">Meeting Link</label>
                                <p class="mb-0"><a href="{{ $meeting->meeting_link }}" target="_blank" class="text-primary">{{ $meeting->meeting_link }}</a></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
