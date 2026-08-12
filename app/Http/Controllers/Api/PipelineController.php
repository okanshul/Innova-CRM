<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PipelineController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('pipeline.view');

        $pipelines = Pipeline::with('stages')->orderBy('order')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $pipelines
            ]
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('pipeline.create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();

        $pipeline = Pipeline::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pipeline created successfully.',
            'data' => $pipeline,
            'redirect' => route('pipelines.index')
        ], 201);
    }

    public function show(string $id)
    {
        Gate::authorize('pipeline.view');

        $pipeline = Pipeline::with('stages')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $pipeline
        ]);
    }

    public function update(Request $request, string $id)
    {
        Gate::authorize('pipeline.edit');

        $pipeline = Pipeline::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->id();

        $pipeline->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pipeline updated successfully.',
            'data' => $pipeline,
            'redirect' => route('pipelines.index')
        ]);
    }

    public function destroy(string $id)
    {
        Gate::authorize('pipeline.delete');

        $pipeline = Pipeline::findOrFail($id);
        $pipeline->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pipeline deleted successfully.'
        ]);
    }
}
