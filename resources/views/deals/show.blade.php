@extends('layouts.app', ['title' => 'InnovaCRM - Deal Details'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Deals', 'url' => route('deals.index')],
        ['label' => $deal->title],
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body mb-4">
                <x-page-header :title="$deal->title" :subtitle="'Value: $' . number_format($deal->value, 2)" icon="fa-solid fa-gem">
                    <x-slot:actions>
                        @can('deals.edit')
                            <x-button.primary href="{{ route('deals.edit', $deal->id) }}" icon="fa-regular fa-pen-to-square me-1" label="Edit" />
                        @endcan
                        <x-button.secondary href="{{ route('deals.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Stage</label>
                            <p class="mb-0 text-body-emphasis">{{ $deal->stage->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Pipeline</label>
                            <p class="mb-0 text-body-emphasis">{{ $deal->pipeline->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Company</label>
                            <p class="mb-0 text-body-emphasis">{{ $deal->company->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Contact</label>
                            <p class="mb-0 text-body-emphasis">{{ $deal->contact->full_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Status</label>
                            <div><span class="badge bg-success text-capitalize">{{ $deal->status }}</span></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Expected Close</label>
                            <p class="mb-0 text-body-emphasis">{{ $deal->expected_close_date ? $deal->expected_close_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
