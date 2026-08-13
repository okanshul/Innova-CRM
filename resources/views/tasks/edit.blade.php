@extends('layouts.app', ['title' => 'InnovaCRM - Edit Task'])

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Tasks', 'url' => route('tasks.index')],
        ['label' => 'Edit Task'],
    ]" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-body">
                <x-page-header title="Edit Task" subtitle="Update task details." icon="fa-regular fa-square-check">
                    <x-slot:actions>
                        <x-button.secondary href="{{ route('tasks.index') }}" icon="fa-solid fa-angle-left pe-1" label="Back" />
                    </x-slot:actions>
                </x-page-header>

                <form id="taskEditForm" action="{{ route('crm.api.tasks.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-3">
                        <x-form.input class="mb-3" name="title" label="Task Title" icon="fa-solid fa-heading" :required="true" :value="$task->title" />
                        <x-form.textarea class="mb-3" name="description" label="Description" icon="fa-solid fa-paragraph" :value="$task->description" />

                        <div class="row">
                            <x-form.select class="col-12 col-md-6 mb-3" name="priority" label="Priority" icon="fa-solid fa-flag" :required="true">
                                <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $task->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="status" label="Status" icon="fa-solid fa-circle-check" :required="true">
                                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $task->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </x-form.select>

                            <x-form.select class="col-12 col-md-6 mb-3" name="assigned_to" label="Assign To" icon="fa-solid fa-user">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.input class="col-12 col-md-6 mb-3" type="date" name="due_date" label="Due Date" icon="fa-regular fa-calendar" :value="$task->due_date ? $task->due_date->format('Y-m-d') : ''" />
                        </div>
                    </div>

                    <div class="card-footer border-top bg-body p-3 d-flex align-items-center justify-content-end gap-2 rounded-bottom-4">
                        <x-button.secondary href="{{ route('tasks.index') }}" label="Cancel" />
                        <x-button.primary type="submit" label="Update" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tasks.js') }}"></script>
@endpush

