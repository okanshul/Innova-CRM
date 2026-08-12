<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('contacts.view');

        $query = Contact::with(['company', 'owner']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $perPage = $request->get('per_page', 10);
        $contacts = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $contacts
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('contacts.create');

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'job_title' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'owner_id' => 'nullable|exists:users,id',
            'status' => 'required|in:lead,prospect,customer,inactive',
            'source' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
        ]);

        $validated['created_by'] = auth()->id();

        $contact = Contact::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully.',
            'data' => $contact,
            'redirect' => route('contacts.index')
        ], 201);
    }

    public function show(string $id)
    {
        Gate::authorize('contacts.view');

        $contact = Contact::with(['company', 'owner'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $contact
        ]);
    }

    public function update(Request $request, string $id)
    {
        Gate::authorize('contacts.edit');

        $contact = Contact::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'job_title' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'owner_id' => 'nullable|exists:users,id',
            'status' => 'required|in:lead,prospect,customer,inactive',
            'source' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
        ]);

        $validated['updated_by'] = auth()->id();

        $contact->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully.',
            'data' => $contact,
            'redirect' => route('contacts.index')
        ]);
    }

    public function destroy(string $id)
    {
        Gate::authorize('contacts.delete');

        $contact = Contact::findOrFail($id);
        $contact->deleted_by = auth()->id();
        $contact->save();
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully.'
        ]);
    }
}
