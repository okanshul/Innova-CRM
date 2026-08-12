@extends('layouts.app', ['title' => 'InnovaCRM - Add Deal'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Deals', 'url' => route('deals.index')],
        ['label' => 'Add Deal'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                <x-page-header title="Add New Deal" subtitle="Fill in details to add a new deal opportunity." icon="fa-solid fa-gem">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('deals.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="dealCreateForm" action="{{ route('crm.api.deals.store') }}" method="POST">
                    @csrf
                    <div class="p-3">
                        <div class="row">
                            <x-form.input class="col-12 col-md-6 mb-3" name="title" label="Deal Title" icon="fa-solid fa-heading" :required="true" placeholder="Acme Software Enterprise License" />
                            <x-form.input class="col-12 col-md-3 mb-3" type="number" step="0.01" name="value" label="Deal Value ($)" icon="fa-solid fa-dollar-sign" :required="true" placeholder="10000.00" />
                            <x-form.input class="col-12 col-md-3 mb-3" name="currency" label="Currency" icon="fa-solid fa-money-bill" :required="true" value="USD" />

                            <x-form.select class="col-12 col-md-6 mb-3" name="pipeline_id" id="pipelineSelect" label="Pipeline" icon="fa-solid fa-bars-staggered" :required="true">
                                @foreach($pipelines as $pipeline)
                                    <option value="{{ $pipeline->id }}">{{ $pipeline->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="stage_id" id="stageSelect" label="Stage" icon="fa-solid fa-layer-group" :required="true">
                                @if(count($pipelines) > 0 && $pipelines[0]->stages)
                                    @foreach($pipelines[0]->stages as $stage)
                                        <option value="{{ $stage->id }}">{{ $stage->name }} ({{ $stage->probability }}%)</option>
                                    @endforeach
                                @endif
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="company_id" label="Company" icon="fa-solid fa-building">
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="contact_id" label="Contact" icon="fa-regular fa-address-book">
                                <option value="">Select Contact</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->full_name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="status" label="Status" icon="fa-solid fa-circle-check" :required="true">
                                <option value="open">Open</option>
                                <option value="won">Closed Won</option>
                                <option value="lost">Closed Lost</option>
                            </x-form.select>

                            <x-form.input class="col-12 col-md-6 mb-3" type="date" name="expected_close_date" label="Expected Close Date" icon="fa-regular fa-calendar" />
                        </div>
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2">
                        <x-button.secondary href="{{ route('deals.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Save Deal" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/deals.js') }}"></script>
@endpush

