@extends('layouts.app', ['title' => 'InnovaCRM - Schedule Meeting'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Meetings', 'url' => route('meetings.index')],
        ['label' => 'Schedule Meeting'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="Schedule New Meeting" subtitle="Create a client or internal team meeting." icon="fa-solid fa-video">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('meetings.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="meetingCreateForm" action="{{ route('crm.api.meetings.store') }}" method="POST">
                    @csrf
                    <div class="p-3 position-relative z-2">
                        <x-form.input class="mb-3" name="title" label="Meeting Title" icon="fa-solid fa-heading" :required="true" placeholder="Product Demo & Discovery Call" />
                        <x-form.textarea class="mb-3" name="description" label="Agenda / Notes" icon="fa-solid fa-paragraph" placeholder="Meeting agenda items..." />

                        <div class="row">
                            <x-form.input class="col-12 col-md-6 mb-3" type="datetime-local" name="start_at" label="Start Time" icon="fa-regular fa-clock" :required="true" />
                            <x-form.input class="col-12 col-md-6 mb-3" type="datetime-local" name="end_at" label="End Time" icon="fa-regular fa-clock" :required="true" />

                            <x-form.input class="col-12 col-md-6 mb-3" name="location" label="Location" icon="fa-solid fa-location-dot" placeholder="Conference Room 1 or Online" />
                            <x-form.input class="col-12 col-md-6 mb-3" name="meeting_link" label="Virtual Link" icon="fa-solid fa-link" placeholder="https://zoom.us/j/123456789" />

                            <x-form.select class="col-12 col-md-6 mb-3" name="host_id" label="Meeting Host" icon="fa-solid fa-user">
                                <option value="">Select Host</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ auth()->id() == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="status" label="Status" icon="fa-solid fa-circle-check" :required="true">
                                <option value="scheduled" selected>Scheduled</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="rescheduled">Rescheduled</option>
                            </x-form.select>
                        </div>
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4 position-relative z-1">
                        <x-button.secondary href="{{ route('meetings.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Schedule Meeting" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/meetings.js') }}"></script>
@endpush

