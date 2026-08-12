@extends('layouts.app', ['title' => 'InnovaCRM - Role Details'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Roles & Permissions', 'url' => route('roles.index')],
        ['label' => ucfirst($role->name)],
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                <x-page-header :title="ucfirst($role->name) . ' Role'" subtitle="Granted permissions list" icon="fa-solid fa-user-shield">
                    <x-slot:actions>
                        @can('roles.edit')
                            <x-button.primary href="{{ route('roles.edit', $role->id) }}" icon="fa-regular fa-pen-to-square me-1" label="Edit" />
                        @endcan
                        <x-button.secondary href="{{ route('roles.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <div class="p-4">
                    <h6 class="fw-bold mb-3">Permissions Assigned ({{ $role->permissions->count() }})</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($role->permissions as $perm)
                            <span class="badge bg-primary-subtle text-primary p-2 fs-6">{{ $perm->name }}</span>
                        @empty
                            <span class="text-secondary small">No specific permissions assigned to this role.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
