<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DealController extends Controller
{
    public function index()
    {
        Gate::authorize('deals.view');

        $pipelines = Pipeline::with('stages')->get();

        return view('deals.index', compact('pipelines'));
    }

    public function create()
    {
        Gate::authorize('deals.create');

        $companies = Company::orderBy('name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $pipelines = Pipeline::with('stages')->get();
        $users = User::orderBy('name')->get();

        return view('deals.create', compact('companies', 'contacts', 'pipelines', 'users'));
    }

    public function show($id)
    {
        Gate::authorize('deals.view');

        $deal = Deal::with(['company', 'contact', 'pipeline', 'stage', 'owner'])->findOrFail($id);

        return view('deals.show', compact('deal'));
    }

    public function edit($id)
    {
        Gate::authorize('deals.edit');

        $deal = Deal::findOrFail($id);
        $companies = Company::orderBy('name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $pipelines = Pipeline::with('stages')->get();
        $users = User::orderBy('name')->get();

        return view('deals.edit', compact('deal', 'companies', 'contacts', 'pipelines', 'users'));
    }
}
