@extends('layouts.app', ['title' => 'InnovaCRM - Settings'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Settings']]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="System Settings" subtitle="Configure application preferences." icon="fa-solid fa-gear" />

                <form id="settingsForm" action="{{ route('crm.api.settings.store') }}" method="POST">
                    @csrf
                    <div class="p-4 position-relative z-2">
                        <div id="settingsAlert"></div>

                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-body-emphasis">General Preferences</h6>
                            <x-form.input class="mb-3" name="company_name" label="Company Name" icon="fa-solid fa-building" :value="$settings['company_name'] ?? 'InnovaCRM Inc.'" />
                            <x-form.input class="mb-3" type="email" name="system_email" label="System Notification Email" icon="fa-solid fa-envelope" :value="$settings['system_email'] ?? 'admin@innovacrm.com'" />
                            <x-form.select class="mb-3" name="currency_symbol" label="Default Currency" icon="fa-solid fa-dollar-sign">
                                <option value="USD" {{ ($settings['currency_symbol'] ?? 'USD') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ ($settings['currency_symbol'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="GBP" {{ ($settings['currency_symbol'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                <option value="INR" {{ ($settings['currency_symbol'] ?? '') === 'INR' ? 'selected' : '' }}>INR (₹)</option>
                            </x-form.select>
                        </div>
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4 position-relative z-1">
                        <x-button.primary type="submit" icon="fa-solid fa-check me-2" label="Save Settings" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/settings.js') }}"></script>
@endpush
