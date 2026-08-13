@extends('layouts.app', ['title' => 'InnovaCRM - Edit Contact'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Contacts', 'url' => route('contacts.index')],
        ['label' => 'Edit Contact'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="Edit Contact" subtitle="Update contact details." icon="fa-regular fa-address-book">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('contacts.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="contactEditForm" action="{{ route('crm.api.contacts.update', $contact->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-3 position-relative z-2">
                        <div class="row">
                            <x-form.input class="col-12 col-md-6 mb-3" name="first_name" label="First Name" icon="fa-solid fa-user" :required="true" :value="$contact->first_name" />
                            <x-form.input class="col-12 col-md-6 mb-3" name="last_name" label="Last Name" icon="fa-solid fa-user" :value="$contact->last_name" />
                            <x-form.input class="col-12 col-md-6 mb-3" type="email" name="email" label="Email Address" icon="fa-solid fa-envelope" :value="$contact->email" />
                            <x-form.input class="col-12 col-md-6 mb-3" name="phone" label="Phone" icon="fa-solid fa-phone" :value="$contact->phone" />
                            <x-form.input class="col-12 col-md-6 mb-3" name="job_title" label="Job Title" icon="fa-solid fa-id-badge" :value="$contact->job_title" />

                            <x-form.select class="col-12 col-md-6 mb-3" name="company_id" label="Company" icon="fa-solid fa-building">
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $contact->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="status" label="Status" icon="fa-solid fa-circle-check" :required="true">
                                <option value="lead" {{ $contact->status === 'lead' ? 'selected' : '' }}>Lead</option>
                                <option value="prospect" {{ $contact->status === 'prospect' ? 'selected' : '' }}>Prospect</option>
                                <option value="customer" {{ $contact->status === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="inactive" {{ $contact->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </x-form.select>

                            <x-form.input class="col-12 col-md-6 mb-3" name="source" label="Lead Source" icon="fa-solid fa-globe" :value="$contact->source" />
                        </div>
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4 position-relative z-1">
                        <x-button.secondary href="{{ route('contacts.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Update" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/contacts.js') }}"></script>
@endpush

