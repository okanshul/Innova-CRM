<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class MailController extends Controller
{
    public function index()
    {
        Gate::authorize('mail.view');

        return view('mail.index');
    }
}
