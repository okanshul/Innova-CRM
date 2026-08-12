<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Meeting;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;

class CalendarController extends Controller
{
    public function index()
    {
        Gate::authorize('calendar.view');

        $events = CalendarEvent::with('user')->get();
        $meetings = Meeting::with('host')->get();
        $tasks = Task::whereNotNull('due_date')->get();

        return view('calendar.index', compact('events', 'meetings', 'tasks'));
    }
}
