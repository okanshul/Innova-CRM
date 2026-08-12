@extends('layouts.app', ['title' => 'InnovaCRM - Pipeline Details'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pipelines', 'url' => route('pipelines.index')],
        ['label' => $pipeline->name],
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                <x-page-header :title="$pipeline->name" :subtitle="$pipeline->description ?? 'Pipeline Details'" icon="fa-solid fa-bars-staggered">
                    <x-slot:actions>
                        @can('pipeline.edit')
                            <x-button.primary href="{{ route('pipelines.edit', $pipeline->id) }}" icon="fa-regular fa-pen-to-square me-1" label="Edit" />
                        @endcan
                        <x-button.secondary href="{{ route('pipelines.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <div class="p-4">
                    <h6 class="fw-bold mb-3">Pipeline Stages</h6>
                    <div class="list-group rounded-3">
                        @forelse($pipeline->stages as $stage)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge me-2" style="background-color: {{ $stage->color }}">&nbsp;</span>
                                    <strong class="text-body-emphasis">{{ $stage->name }}</strong>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary">{{ $stage->probability }}% Probability</span>
                            </div>
                        @empty
                            <div class="list-group-item text-secondary py-3">No stages configured yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
