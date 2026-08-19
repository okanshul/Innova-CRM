@extends('layouts.app', ['title' => 'InnovaCRM - Task Details'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Tasks', 'url' => route('tasks.index')],
        ['label' => $task->title],
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body mb-4">
                <x-page-header :title="$task->title" subtitle="Task Details" icon="fa-regular fa-square-check">
                    <x-slot:actions>
                        @can('tasks.edit')
                            <x-button.primary href="{{ route('tasks.edit', $task->id) }}" icon="fa-regular fa-pen-to-square me-1" label="Edit" />
                        @endcan
                        <x-button.secondary href="{{ route('tasks.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <div class="p-4">
                    <div class="mb-4">
                        <label class="text-secondary small fw-semibold">Description</label>
                        <p class="mb-0 text-body-emphasis">{{ $task->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Assigned To</label>
                            <p class="mb-0 text-body-emphasis">{{ $task->assignedTo->name ?? 'Unassigned' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Created By</label>
                            <p class="mb-0 text-body-emphasis">{{ $task->createdBy->name ?? 'System' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Priority</label>
                            <div><span class="badge bg-warning text-uppercase">{{ $task->priority }}</span></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Status</label>
                            <div><span class="badge bg-primary text-capitalize">{{ str_replace('_', ' ', $task->status) }}</span></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-secondary small fw-semibold">Due Date</label>
                            <p class="mb-0 text-body-emphasis">{{ $task->due_date ? format_date($task->due_date) : 'No Due Date' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
