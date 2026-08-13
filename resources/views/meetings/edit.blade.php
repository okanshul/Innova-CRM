@extends('layouts.app', ['title' => 'InnovaCRM - Edit Meeting'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Meetings', 'url' => route('meetings.index')],
        ['label' => 'Edit Meeting'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="Edit Meeting" subtitle="Update meeting details." icon="fa-solid fa-video">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('meetings.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="meetingEditForm" action="{{ route('crm.api.meetings.update', $meeting->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-3 position-relative z-2">
                        <x-form.input class="mb-3" name="title" label="Meeting Title" icon="fa-solid fa-heading" :required="true" :value="$meeting->title" />
                        <x-form.textarea class="mb-3" name="description" label="Agenda / Notes" icon="fa-solid fa-paragraph" :value="$meeting->description" />

                        <div class="row">
                            <x-form.input class="col-12 col-md-6 mb-3" type="datetime-local" name="start_at" label="Start Time" icon="fa-regular fa-clock" :required="true" :value="$meeting->start_at ? $meeting->start_at->format('Y-m-d\TH:i') : ''" />
                            <x-form.input class="col-12 col-md-6 mb-3" type="datetime-local" name="end_at" label="End Time" icon="fa-regular fa-clock" :required="true" :value="$meeting->end_at ? $meeting->end_at->format('Y-m-d\TH:i') : ''" />

                            <x-form.input class="col-12 col-md-6 mb-3" name="location" label="Location" icon="fa-solid fa-location-dot" :value="$meeting->location" />
                            <x-form.input class="col-12 col-md-6 mb-3" name="meeting_link" label="Virtual Link" icon="fa-solid fa-link" :value="$meeting->meeting_link" />

                            <x-form.select class="col-12 col-md-6 mb-3" name="host_id" label="Meeting Host" icon="fa-solid fa-user">
                                <option value="">Select Host</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $meeting->host_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="status" label="Status" icon="fa-solid fa-circle-check" :required="true">
                                <option value="scheduled" {{ $meeting->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ $meeting->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $meeting->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="rescheduled" {{ $meeting->status === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                            </x-form.select>
                        </div>
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4 position-relative z-1">
                        <x-button.secondary href="{{ route('meetings.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Update" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/meetings.js') }}"></script>
@endpush

