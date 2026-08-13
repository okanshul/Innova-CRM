<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function index()
    {
        $user = auth()->user()->load(['roles', 'deals', 'tasks', 'hostedMeetings']);
        return view('profile.index', compact('user'));
    }
}
