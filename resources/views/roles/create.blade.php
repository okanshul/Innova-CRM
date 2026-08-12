@extends('layouts.app', ['title' => 'InnovaCRM - Add Role'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Roles & Permissions', 'url' => route('roles.index')],
        ['label' => 'Add Role'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="Add New Role" subtitle="Define a system role and assign module permissions." icon="fa-solid fa-user-shield">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('roles.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="roleCreateForm" action="{{ route('crm.api.roles.store') }}" method="POST">
                    @csrf
                    <div class="p-3 position-relative z-2">
                        <x-form.input class="mb-4" name="name" label="Role Name" icon="fa-solid fa-shield-halved" :required="true" placeholder="e.g. Sales Manager" />

                        <h6 class="fw-bold mb-3 text-body-emphasis">Assign Module Permissions</h6>
                        @include('staff.partials.permissions-accordion', [
                            'groupedPermissions' => $groupedPermissions ?? [],
                            'directPermissions' => [],
                            'rolePermissions' => [],
                            'idPrefix' => 'role_create_perm',
                        ])
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4 position-relative z-1">
                        <x-button.secondary href="{{ route('roles.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Save Role" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/roles.js') }}"></script>
@endpush

