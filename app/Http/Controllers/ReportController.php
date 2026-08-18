<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Company;
use App\Models\PipelineStage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

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

        // 1. Monthly Revenue & Deals Trend (Current Year)
        $currentYear = date('Y');
        $monthlyData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = [
                'month' => $months[$m - 1],
                'revenue' => 0,
                'deals_count' => 0,
            ];
        }

        $revenueRaw = Deal::selectRaw('MONTH(created_at) as month_num, SUM(CASE WHEN status = "won" THEN value ELSE 0 END) as total_rev, COUNT(id) as cnt')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month_num')
            ->get();

        foreach ($revenueRaw as $row) {
            $mNum = (int) $row->month_num;
            if (isset($monthlyData[$mNum])) {
                $monthlyData[$mNum]['revenue'] = (float) $row->total_rev;
                $monthlyData[$mNum]['deals_count'] = (int) $row->cnt;
            }
        }

        $chartMonthlyLabels = array_column(array_values($monthlyData), 'month');
        $chartMonthlyRevenue = array_column(array_values($monthlyData), 'revenue');
        $chartMonthlyDeals = array_column(array_values($monthlyData), 'deals_count');

        // 2. Win / Loss Status Breakdown
        $wonDeals = Deal::where('status', 'won')->count();
        $lostDeals = Deal::where('status', 'lost')->count();
        $openDeals = Deal::whereNotIn('status', ['won', 'lost'])->orWhereNull('status')->count();
        $totalStatusDeals = max($totalDeals, 1);
        $winRate = round(($wonDeals / $totalStatusDeals) * 100, 1);

        $statusChartData = [
            'labels' => ['Won', 'Lost', 'In Progress'],
            'data' => [$wonDeals, $lostDeals, $openDeals],
            'win_rate' => $winRate,
        ];

        // 3. Deals by Stage
        $stages = PipelineStage::withCount('deals')
            ->withSum('deals', 'value')
            ->orderBy('order', 'asc')
            ->get();

        $stageLabels = [];
        $stageValues = [];
        $stageCounts = [];
        foreach ($stages as $stage) {
            $stageLabels[] = $stage->name;
            $stageValues[] = (float) ($stage->deals_sum_value ?? 0);
            $stageCounts[] = $stage->deals_count;
        }

        // 4. Leads by Source
        $leadsBySource = Lead::select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();

        $sourceLabels = array_keys($leadsBySource);
        $sourceCounts = array_values($leadsBySource);

        return view('reports.index', compact(
            'stats',
            'chartMonthlyLabels',
            'chartMonthlyRevenue',
            'chartMonthlyDeals',
            'statusChartData',
            'stageLabels',
            'stageValues',
            'stageCounts',
            'sourceLabels',
            'sourceCounts'
        ));
    }
}
