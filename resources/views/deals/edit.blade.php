@extends('layouts.app', ['title' => 'InnovaCRM - Edit Deal'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Deals', 'url' => route('deals.index')],
        ['label' => 'Edit Deal'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="Edit Deal" subtitle="Update deal details." icon="fa-solid fa-gem">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('deals.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="dealEditForm" action="{{ route('crm.api.deals.update', $deal->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-3 position-relative z-2">
                        <div class="row">
                            <x-form.input class="col-12 col-md-6 mb-3" name="title" label="Deal Title" icon="fa-solid fa-heading" :required="true" :value="$deal->title" />
                            <x-form.input class="col-12 col-md-3 mb-3" type="number" step="0.01" name="value" label="Deal Value ($)" icon="fa-solid fa-dollar-sign" :required="true" :value="$deal->value" />
                            <x-form.input class="col-12 col-md-3 mb-3" name="currency" label="Currency" icon="fa-solid fa-money-bill" :required="true" :value="$deal->currency" />

                            <x-form.select class="col-12 col-md-6 mb-3" name="pipeline_id" id="pipelineSelect" label="Pipeline" icon="fa-solid fa-bars-staggered" :required="true">
                                @foreach($pipelines as $pipeline)
                                    <option value="{{ $pipeline->id }}" {{ $deal->pipeline_id == $pipeline->id ? 'selected' : '' }}>{{ $pipeline->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="stage_id" id="stageSelect" label="Stage" icon="fa-solid fa-layer-group" :required="true">
                                @foreach($pipelines as $pipeline)
                                    @if($pipeline->id == $deal->pipeline_id)
                                        @foreach($pipeline->stages as $stage)
                                            <option value="{{ $stage->id }}" {{ $deal->stage_id == $stage->id ? 'selected' : '' }}>{{ $stage->name }} ({{ $stage->probability }}%)</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="company_id" label="Company" icon="fa-solid fa-building">
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $deal->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="contact_id" label="Contact" icon="fa-regular fa-address-book">
                                <option value="">Select Contact</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}" {{ $deal->contact_id == $contact->id ? 'selected' : '' }}>{{ $contact->full_name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="status" label="Status" icon="fa-solid fa-circle-check" :required="true">
                                <option value="open" {{ $deal->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="won" {{ $deal->status === 'won' ? 'selected' : '' }}>Closed Won</option>
                                <option value="lost" {{ $deal->status === 'lost' ? 'selected' : '' }}>Closed Lost</option>
                            </x-form.select>

                            <x-form.input class="col-12 col-md-6 mb-3" type="date" name="expected_close_date" label="Expected Close Date" icon="fa-regular fa-calendar" :value="$deal->expected_close_date ? $deal->expected_close_date->format('Y-m-d') : ''" />
                        </div>
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4 position-relative z-1">
                        <x-button.secondary href="{{ route('deals.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Update Deal" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/deals.js') }}"></script>
@endpush

