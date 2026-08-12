<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function index()
    {
        Gate::authorize('reports.view');

        $totalRevenue = Deal::where('status', 'won')->sum('value');
        $totalDeals = Deal::count();
        $totalContacts = Contact::count();
        $totalLeads = Lead::count();
        $totalCompanies = Company::count();

        $stats = [
            'total_revenue' => '$' . number_format($totalRevenue, 2),
            'total_deals' => number_format($totalDeals),
            'total_contacts' => number_format($totalContacts),
            'total_leads' => number_format($totalLeads),
            'total_companies' => number_format($totalCompanies),
        ];

        return view('reports.index', compact('stats'));
    }
}
