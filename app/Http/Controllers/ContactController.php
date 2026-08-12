<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    public function index()
    {
        Gate::authorize('contacts.view');

        $companies = Company::orderBy('name')->get();

        return view('contacts.index', compact('companies'));
    }

    public function create()
    {
        Gate::authorize('contacts.create');

        $companies = Company::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('contacts.create', compact('companies', 'users'));
    }

    public function show($id)
    {
        Gate::authorize('contacts.view');

        $contact = Contact::with(['company', 'owner'])->findOrFail($id);

        return view('contacts.show', compact('contact'));
    }

    public function edit($id)
    {
        Gate::authorize('contacts.edit');

        $contact = Contact::findOrFail($id);
        $companies = Company::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('contacts.edit', compact('contact', 'companies', 'users'));
    }
}
