<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DealController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('deals.view');

        $query = Deal::with(['company', 'contact', 'pipeline', 'stage', 'owner']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('pipeline_id')) {
            $query->where('pipeline_id', $request->get('pipeline_id'));
        }

        $perPage = $request->get('per_page', setting('items_per_page', 10));
        $deals = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $deals
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('deals.create');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'company_id' => 'nullable|exists:companies,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'pipeline_id' => 'required|exists:pipelines,id',
            'stage_id' => 'required|exists:pipeline_stages,id',
            'owner_id' => 'nullable|exists:users,id',
            'expected_close_date' => 'nullable|date',
            'status' => 'required|in:open,won,lost',
            'lost_reason' => 'nullable|string',
        ]);

        if (empty($validated['expected_close_date'])) {
            $validated['expected_close_date'] = now()->addDays((int) setting('deal_close_days', 30))->toDateString();
        }

        $validated['created_by'] = auth()->id();

        $deal = Deal::create($validated);

        \App\Services\NotificationDispatcher::dispatch('new_deal', auth()->user(), [
            'subject' => 'New Deal Created',
            'message' => "New deal '{$deal->title}' created with value " . format_currency($deal->value)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Deal created successfully.',
            'data' => $deal,
            'redirect' => route('deals.index')
        ], 201);
    }

    public function show(string $id)
    {
        Gate::authorize('deals.view');

        $deal = Deal::with(['company', 'contact', 'pipeline', 'stage', 'owner'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $deal
        ]);
    }

    public function update(Request $request, string $id)
    {
        Gate::authorize('deals.edit');

        $deal = Deal::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'company_id' => 'nullable|exists:companies,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'pipeline_id' => 'required|exists:pipelines,id',
            'stage_id' => 'required|exists:pipeline_stages,id',
            'owner_id' => 'nullable|exists:users,id',
            'expected_close_date' => 'nullable|date',
            'status' => 'required|in:open,won,lost',
            'lost_reason' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $deal->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Deal updated successfully.',
            'data' => $deal,
            'redirect' => route('deals.index')
        ]);
    }

    public function destroy(string $id)
    {
        Gate::authorize('deals.delete');

        $deal = Deal::findOrFail($id);
        $deal->deleted_by = auth()->id();
        $deal->save();
        $deal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deal deleted successfully.'
        ]);
    }
}
