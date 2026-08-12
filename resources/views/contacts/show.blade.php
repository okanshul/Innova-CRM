@extends('layouts.app', ['title' => 'InnovaCRM - Contact Details'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Contacts', 'url' => route('contacts.index')],
        ['label' => $contact->full_name],
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body mb-4">
                <x-page-header :title="$contact->full_name" :subtitle="$contact->job_title ?? 'Contact Profile'" icon="fa-regular fa-address-book">
                    <x-slot:actions>
                        @can('contacts.edit')
                            <x-button.primary href="{{ route('contacts.edit', $contact->id) }}" icon="fa-regular fa-pen-to-square me-1" label="Edit" />
                        @endcan
                        <x-button.secondary href="{{ route('contacts.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Email</label>
                            <p class="mb-0 text-body-emphasis">{{ $contact->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Phone</label>
                            <p class="mb-0 text-body-emphasis">{{ $contact->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Company</label>
                            <p class="mb-0 text-body-emphasis">{{ $contact->company->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Status</label>
                            <div><span class="badge bg-primary text-capitalize">{{ $contact->status }}</span></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Source</label>
                            <p class="mb-0 text-body-emphasis">{{ $contact->source ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Owner</label>
                            <p class="mb-0 text-body-emphasis">{{ $contact->owner->name ?? 'Unassigned' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
