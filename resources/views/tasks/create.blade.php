@extends('layouts.app', ['title' => 'InnovaCRM - Add Task'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Tasks', 'url' => route('tasks.index')],
        ['label' => 'Add Task'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="Add New Task" subtitle="Create a task assignment or follow-up." icon="fa-regular fa-square-check">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('tasks.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="taskCreateForm" action="{{ route('crm.api.tasks.store') }}" method="POST">
                    @csrf
                    <div class="p-3 position-relative z-2">
                        <x-form.input class="mb-3" name="title" label="Task Title" icon="fa-solid fa-heading" :required="true" placeholder="Follow up with client regarding quote" />
                        <x-form.textarea class="mb-3" name="description" label="Description" icon="fa-solid fa-paragraph" placeholder="Detailed notes about this task..." />

                        <div class="row">
                            <x-form.select class="col-12 col-md-6 mb-3" name="priority" label="Priority" icon="fa-solid fa-flag" :required="true">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="status" label="Status" icon="fa-solid fa-circle-check" :required="true">
                                <option value="pending" selected>Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="assigned_to" label="Assign To" icon="fa-solid fa-user">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.input class="col-12 col-md-6 mb-3" type="date" name="due_date" label="Due Date" icon="fa-regular fa-calendar" />
                        </div>
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4 position-relative z-1">
                        <x-button.secondary href="{{ route('tasks.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Save Task" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tasks.js') }}"></script>
@endpush

