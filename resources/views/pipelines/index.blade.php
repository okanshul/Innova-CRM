@extends('layouts.app', ['title' => 'InnovaCRM - Pipelines'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Pipelines']]" />

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
        <div class="card-header border-0 bg-body p-3">
            <x-page-header title="Sales Pipelines" icon="fa-solid fa-bars-staggered" :cardHeader="false" class="mb-4">
                <x-slot:actions>
                    @can('pipeline.create')
                        <x-button.primary href="{{ route('pipelines.create') }}" icon="fa-solid fa-plus fs-sm" label="Add Pipeline" />
                    @endcan
                </x-slot:actions>
            </x-page-header>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert" style="font-size: 0.875rem;">
                    <i class="fa-solid fa-circle-check me-1.5"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="d-none d-lg-block">
            <x-data-table id="pipelinesTable" tableBodyId="pipelinesTableBody" :showCheckboxColumn="false" :headers="[
                'PIPELINE NAME',
                'DESCRIPTION',
                'STAGES COUNT',
                'DEFAULT',
                ['title' => 'ACTIONS', 'align' => 'end'],
            ]" />
        </div>

        <div class="d-lg-none border-top">
            <div id="pipelinesMobileCardList" class="d-flex flex-column bg-body"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.userPermissions = {
            canEdit: @json(auth()->user()->can('pipeline.edit')),
            canDelete: @json(auth()->user()->can('pipeline.delete'))
        };
    </script>
    <script src="{{ asset('js/pipelines.js') }}"></script>
@endpush
