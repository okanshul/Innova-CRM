<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('meetings.view');

        $query = Meeting::with(['host', 'attendees']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $perPage = $request->get('per_page', 10);
        $meetings = $query->orderBy('start_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $meetings
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('meetings.create');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:255',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'outcome_summary' => 'nullable|string',
            'host_id' => 'nullable|exists:users,id',
        ]);

        $validated['created_by'] = auth()->id();

        $meeting = Meeting::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meeting scheduled successfully.',
            'data' => $meeting,
            'redirect' => route('meetings.index')
        ], 201);
    }

    public function show(string $id)
    {
        Gate::authorize('meetings.view');

        $meeting = Meeting::with(['host', 'attendees.attendee'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $meeting
        ]);
    }

    public function update(Request $request, string $id)
    {
        Gate::authorize('meetings.edit');

        $meeting = Meeting::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:255',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'outcome_summary' => 'nullable|string',
            'host_id' => 'nullable|exists:users,id',
        ]);

        $validated['updated_by'] = auth()->id();

        $meeting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meeting updated successfully.',
            'data' => $meeting,
            'redirect' => route('meetings.index')
        ]);
    }

    public function destroy(string $id)
    {
        Gate::authorize('meetings.delete');

        $meeting = Meeting::findOrFail($id);
        $meeting->deleted_by = auth()->id();
        $meeting->save();
        $meeting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meeting deleted successfully.'
        ]);
    }
}
