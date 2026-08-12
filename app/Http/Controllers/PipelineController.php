<?php

namespace App\Http\Controllers;

use App\Models\Pipeline;
use Illuminate\Support\Facades\Gate;

class PipelineController extends Controller
{
    public function index()
    {
        Gate::authorize('pipeline.view');

        $pipelines = Pipeline::with('stages')->get();

        return view('pipelines.index', compact('pipelines'));
    }

    public function create()
    {
        Gate::authorize('pipeline.create');

        return view('pipelines.create');
    }

    public function show($id)
    {
        Gate::authorize('pipeline.view');

        $pipeline = Pipeline::with('stages')->findOrFail($id);

        return view('pipelines.show', compact('pipeline'));
    }

    public function edit($id)
    {
        Gate::authorize('pipeline.edit');

        $pipeline = Pipeline::with('stages')->findOrFail($id);

        return view('pipelines.edit', compact('pipeline'));
    }
}
