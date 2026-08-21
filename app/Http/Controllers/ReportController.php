<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Company;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('reports.view');

        // Filter Inputs
        $period = $request->get('period', 'all');
        $startDateInput = $request->get('start_date');
        $endDateInput = $request->get('end_date');
        $ownerId = $request->get('owner_id');
        $pipelineId = $request->get('pipeline_id');

        // Determine Date Range
        $startDate = null;
        $endDate = null;

        if ($period === 'today') {
            $startDate = Carbon::today();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($period === 'this_week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($period === 'this_month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($period === 'this_quarter') {
            $startDate = Carbon::now()->startOfQuarter();
            $endDate = Carbon::now()->endOfQuarter();
        } elseif ($period === 'this_year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
        } elseif ($period === 'custom' && $startDateInput && $endDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        }

        // 1. Filtered Deal Query
        $dealQuery = Deal::query();
        if ($startDate && $endDate) {
            $dealQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($ownerId) {
            $dealQuery->where('owner_id', $ownerId);
        }
        if ($pipelineId) {
            $dealQuery->where('pipeline_id', $pipelineId);
        }

        // 2. Filtered Contact Query
        $contactQuery = Contact::query();
        if ($startDate && $endDate) {
            $contactQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($ownerId) {
            $contactQuery->where('owner_id', $ownerId);
        }

        // 3. Filtered Lead Query
        $leadQuery = Lead::query();
        if ($startDate && $endDate) {
            $leadQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($ownerId) {
            $leadQuery->where('owner_id', $ownerId);
        }

        // 4. Filtered Company Query
        $companyQuery = Company::query();
        if ($startDate && $endDate) {
            $companyQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($ownerId) {
            $companyQuery->where('owner_id', $ownerId);
        }

        // Stats calculation
        $totalRevenue = (clone $dealQuery)->where('status', 'won')->sum('value');
        $totalDeals = (clone $dealQuery)->count();
        $totalContacts = (clone $contactQuery)->count();
        $totalLeads = (clone $leadQuery)->count();
        $totalCompanies = (clone $companyQuery)->count();

        $stats = [
            'total_revenue' => '$' . number_format($totalRevenue, 2),
            'total_deals' => number_format($totalDeals),
            'total_contacts' => number_format($totalContacts),
            'total_leads' => number_format($totalLeads),
            'total_companies' => number_format($totalCompanies),
        ];

        // Monthly Revenue & Deals Trend
        $monthlyData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = [
                'month' => $months[$m - 1],
                'revenue' => 0,
                'deals_count' => 0,
            ];
        }

        $trendQuery = (clone $dealQuery);
        if ($period === 'this_year' || !$startDate) {
            $year = date('Y');
            $trendQuery->whereYear('created_at', $year);
        }

        $revenueRaw = $trendQuery
            ->selectRaw('MONTH(created_at) as month_num, SUM(CASE WHEN status = "won" THEN value ELSE 0 END) as total_rev, COUNT(id) as cnt')
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

        // Win / Loss Status Breakdown
        $wonDeals = (clone $dealQuery)->where('status', 'won')->count();
        $lostDeals = (clone $dealQuery)->where('status', 'lost')->count();
        $openDeals = (clone $dealQuery)->whereNotIn('status', ['won', 'lost'])->orWhereNull('status')->count();
        $totalStatusDeals = max($totalDeals, 1);
        $winRate = round(($wonDeals / $totalStatusDeals) * 100, 1);

        $statusChartData = [
            'labels' => ['Won', 'Lost', 'In Progress'],
            'data' => [$wonDeals, $lostDeals, $openDeals],
            'win_rate' => $winRate,
        ];

        // Deals by Stage
        $stageQuery = PipelineStage::query();
        if ($pipelineId) {
            $stageQuery->where('pipeline_id', $pipelineId);
        }
        $stages = $stageQuery
            ->withCount(['deals' => function ($q) use ($startDate, $endDate, $ownerId) {
                if ($startDate && $endDate) $q->whereBetween('created_at', [$startDate, $endDate]);
                if ($ownerId) $q->where('owner_id', $ownerId);
            }])
            ->withSum(['deals' => function ($q) use ($startDate, $endDate, $ownerId) {
                if ($startDate && $endDate) $q->whereBetween('created_at', [$startDate, $endDate]);
                if ($ownerId) $q->where('owner_id', $ownerId);
            }], 'value')
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

        // Leads by Source
        $sourceQuery = (clone $leadQuery);
        $leadsBySource = $sourceQuery
            ->select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();

        $sourceLabels = array_keys($leadsBySource);
        $sourceCounts = array_values($leadsBySource);

        // Fetch Dropdown options for filter view
        $owners = User::select('id', 'name')->orderBy('name')->get();
        $pipelines = Pipeline::select('id', 'name')->orderBy('name')->get();

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
            'sourceCounts',
            'owners',
            'pipelines',
            'period',
            'startDateInput',
            'endDateInput',
            'ownerId',
            'pipelineId'
        ));
    }
}
