<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Gate;

class MeetingController extends Controller
{
    public function index()
    {
        Gate::authorize('meetings.view');

        return view('meetings.index');
    }

    public function create()
    {
        Gate::authorize('meetings.create');

        $users = User::orderBy('name')->get();
        $contacts = Contact::orderBy('first_name')->get();

        return view('meetings.create', compact('users', 'contacts'));
    }

    public function show($id)
    {
        Gate::authorize('meetings.view');

        $meeting = Meeting::with(['host', 'attendees.attendee'])->findOrFail($id);

        return view('meetings.show', compact('meeting'));
    }

    public function edit($id)
    {
        Gate::authorize('meetings.edit');

        $meeting = Meeting::findOrFail($id);
        $users = User::orderBy('name')->get();
        $contacts = Contact::orderBy('first_name')->get();

        return view('meetings.edit', compact('meeting', 'users', 'contacts'));
    }
}
