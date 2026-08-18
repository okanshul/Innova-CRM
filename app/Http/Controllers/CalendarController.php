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
        $meetings = Meeting::with('host')->orderBy('start_at', 'asc')->get();
        $tasks = Task::with('assignedTo')->whereNotNull('due_date')->get();

        $calendarItems = [];

        foreach ($meetings as $meeting) {
            if (!$meeting->start_at) continue;
            $calendarItems[] = [
                'id' => 'meeting_' . $meeting->id,
                'title' => $meeting->title,
                'start' => $meeting->start_at->toIso8601String(),
                'end' => $meeting->end_at ? $meeting->end_at->toIso8601String() : null,
                'className' => 'fc-event-meeting',
                'backgroundColor' => '#f3e8ff',
                'borderColor' => '#d8b4fe',
                'textColor' => '#6b21a8',
                'extendedProps' => [
                    'category' => 'meeting',
                    'type' => 'Meeting',
                    'icon' => 'fa-calendar-days',
                    'location' => $meeting->location ?? 'N/A',
                    'link' => $meeting->meeting_link ?? '',
                    'host' => $meeting->host?->name ?? 'N/A',
                    'description' => $meeting->description ?? 'No description provided.',
                    'status' => ucfirst($meeting->status ?? 'scheduled'),
                    'url' => route('meetings.show', $meeting->id),
                ]
            ];
        }

        foreach ($tasks as $task) {
            if (!$task->due_date) continue;
            $calendarItems[] = [
                'id' => 'task_' . $task->id,
                'title' => $task->title,
                'start' => $task->due_date->toIso8601String(),
                'allDay' => true,
                'className' => 'fc-event-task',
                'backgroundColor' => '#fff7ed',
                'borderColor' => '#fed7aa',
                'textColor' => '#c2410c',
                'extendedProps' => [
                    'category' => 'task',
                    'type' => 'Task Due',
                    'icon' => 'fa-circle-check',
                    'assignee' => $task->assignedTo?->name ?? 'Unassigned',
                    'priority' => ucfirst($task->priority ?? 'medium'),
                    'status' => ucfirst($task->status ?? 'pending'),
                    'description' => $task->description ?? 'No description provided.',
                    'url' => route('tasks.show', $task->id),
                ]
            ];
        }

        foreach ($events as $event) {
            if (!$event->start_at) continue;
            $calendarItems[] = [
                'id' => 'event_' . $event->id,
                'title' => $event->title,
                'start' => $event->start_at->toIso8601String(),
                'end' => $event->end_at ? $event->end_at->toIso8601String() : null,
                'allDay' => (bool) $event->is_all_day,
                'className' => 'fc-event-event',
                'backgroundColor' => '#ecfeff',
                'borderColor' => '#a5f3fc',
                'textColor' => '#0e7490',
                'extendedProps' => [
                    'category' => 'event',
                    'type' => 'Calendar Event',
                    'icon' => 'fa-calendar-check',
                    'location' => $event->location ?? 'N/A',
                    'user' => $event->user?->name ?? 'N/A',
                    'description' => $event->description ?? 'No description provided.',
                ]
            ];
        }

        // Upcoming meetings for sidebar
        $upcomingMeetings = Meeting::with('host')
            ->where('start_at', '>=', now())
            ->orderBy('start_at', 'asc')
            ->take(10)
            ->get();

        if ($upcomingMeetings->isEmpty()) {
            $upcomingMeetings = Meeting::with('host')
                ->orderBy('start_at', 'desc')
                ->take(10)
                ->get();
        }

        return view('calendar.index', compact('events', 'meetings', 'tasks', 'calendarItems', 'upcomingMeetings'));
    }
}
