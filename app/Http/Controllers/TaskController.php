<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index()
    {
        Gate::authorize('tasks.view');

        $users = User::orderBy('name')->get();

        return view('tasks.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('tasks.create');

        $users = User::orderBy('name')->get();

        return view('tasks.create', compact('users'));
    }

    public function show($id)
    {
        Gate::authorize('tasks.view');

        $task = Task::with(['assignedTo', 'createdBy'])->findOrFail($id);

        return view('tasks.show', compact('task'));
    }

    public function edit($id)
    {
        Gate::authorize('tasks.edit');

        $task = Task::findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'users'));
    }
}
