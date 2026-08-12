@extends('layouts.app', ['title' => 'InnovaCRM - Roles & Permissions'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Roles & Permissions']]" />

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
        <div class="card-header border-0 bg-body p-3">
            <x-page-header title="Roles & Permissions" icon="fa-solid fa-user-shield" :cardHeader="false" class="mb-4">
                <x-slot:actions>
                    @can('roles.create')
                        <x-button.primary href="{{ route('roles.create') }}" icon="fa-solid fa-plus fs-sm" label="Add Role" />
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
            <x-data-table id="rolesTable" tableBodyId="rolesTableBody" :showCheckboxColumn="false" :headers="[
                'ROLE NAME',
                'PERMISSIONS COUNT',
                'GUARD',
                ['title' => 'ACTIONS', 'align' => 'end'],
            ]" />
        </div>

        <div class="d-lg-none border-top">
            <div id="rolesMobileCardList" class="d-flex flex-column bg-body"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.userPermissions = {
            canEdit: @json(auth()->user()->can('roles.edit')),
            canDelete: @json(auth()->user()->can('roles.delete'))
        };
    </script>
    <script src="{{ asset('js/roles.js') }}"></script>
@endpush
