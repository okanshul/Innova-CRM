@extends('layouts.app', ['title' => 'InnovaCRM - Edit Pipeline'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pipelines', 'url' => route('pipelines.index')],
        ['label' => 'Edit Pipeline'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="Edit Pipeline" subtitle="Update pipeline settings." icon="fa-solid fa-bars-staggered">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('pipelines.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="pipelineEditForm" action="{{ route('crm.api.pipelines.update', $pipeline->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-3 position-relative z-2">
                        <x-form.input class="mb-3" name="name" label="Pipeline Name" icon="fa-solid fa-heading" :required="true" :value="$pipeline->name" />
                        <x-form.textarea class="mb-3" name="description" label="Description" icon="fa-solid fa-paragraph" :value="$pipeline->description" />
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4 position-relative z-1">
                        <x-button.secondary href="{{ route('pipelines.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Update" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pipelines.js') }}"></script>
@endpush

