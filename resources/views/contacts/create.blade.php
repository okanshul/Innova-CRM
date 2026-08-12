@extends('layouts.app', ['title' => 'InnovaCRM - Add Contact'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Contacts', 'url' => route('contacts.index')],
        ['label' => 'Add Contact'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="Add New Contact" subtitle="Fill in the details below to add a new contact." icon="fa-regular fa-address-book">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('contacts.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="contactCreateForm" action="{{ route('crm.api.contacts.store') }}" method="POST">
                    @csrf
                    <div class="p-3">
                        <div class="row">
                            <x-form.input class="col-12 col-md-6 mb-3" name="first_name" label="First Name" icon="fa-solid fa-user" :required="true" placeholder="John" />
                            <x-form.input class="col-12 col-md-6 mb-3" name="last_name" label="Last Name" icon="fa-solid fa-user" placeholder="Doe" />
                            <x-form.input class="col-12 col-md-6 mb-3" type="email" name="email" label="Email Address" icon="fa-solid fa-envelope" placeholder="john.doe@example.com" />
                            <x-form.input class="col-12 col-md-6 mb-3" name="phone" label="Phone" icon="fa-solid fa-phone" placeholder="+1 555-0192" />
                            <x-form.input class="col-12 col-md-6 mb-3" name="job_title" label="Job Title" icon="fa-solid fa-id-badge" placeholder="Sales Manager" />

                            <x-form.select class="col-12 col-md-6 mb-3" name="company_id" label="Company" icon="fa-solid fa-building">
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="status" label="Status" icon="fa-solid fa-circle-check" :required="true">
                                <option value="lead">Lead</option>
                                <option value="prospect">Prospect</option>
                                <option value="customer">Customer</option>
                                <option value="inactive">Inactive</option>
                            </x-form.select>

                            <x-form.input class="col-12 col-md-6 mb-3" name="source" label="Lead Source" icon="fa-solid fa-globe" placeholder="Website, Referral, etc." />
                        </div>
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4">
                        <x-button.secondary href="{{ route('contacts.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Save Contact" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/contacts.js') }}"></script>
@endpush

