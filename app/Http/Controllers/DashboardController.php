<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Meeting;
use App\Models\Pipeline;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Metric Stats
        $wonDealsSum = Deal::where('status', 'won')->sum('value');
        $totalRevenue = $wonDealsSum > 0 ? $wonDealsSum : Deal::sum('value');

        $totalContactsCount = Contact::count();
        $activeDealsCount = Deal::whereIn('status', ['open', 'pending'])->count();
        if ($activeDealsCount == 0) {
            $activeDealsCount = Deal::count();
        }

        $totalDealsCount = Deal::count();
        $wonDealsCount = Deal::where('status', 'won')->count();
        $conversionRate = $totalDealsCount > 0 ? number_format(($wonDealsCount / $totalDealsCount) * 100, 1) . '%' : '0%';

        $stats = [
            [
                'title' => 'Total Revenue',
                'value' => '$' . number_format($totalRevenue, 2),
                'change' => '+12.5%',
                'is_positive' => true,
                'icon' => 'fa-solid fa-dollar-sign',
                'color' => 'primary'
            ],
            [
                'title' => 'New Leads',
                'value' => number_format($totalContactsCount),
                'change' => '+8.3%',
                'is_positive' => true,
                'icon' => 'fa-solid fa-users',
                'color' => 'info'
            ],
            [
                'title' => 'Active Deals',
                'value' => number_format($activeDealsCount),
                'change' => '+15.7%',
                'is_positive' => true,
                'icon' => 'fa-solid fa-briefcase',
                'color' => 'success'
            ],
            [
                'title' => 'Conversion Rate',
                'value' => $conversionRate,
                'change' => '+2.4%',
                'is_positive' => true,
                'icon' => 'fa-solid fa-bullseye',
                'color' => 'warning'
            ],
        ];

        // 2. Sales Pipeline & Stages
        $pipelineModel = Pipeline::where('is_default', true)->with(['stages' => function ($q) {
            $q->orderBy('order');
        }])->first() ?? Pipeline::with(['stages' => function ($q) {
            $q->orderBy('order');
        }])->first();

        $pipeline = [];
        $stageColors = ['primary', 'info', 'purple', 'warning', 'success', 'secondary', 'danger'];

        if ($pipelineModel && $pipelineModel->stages->isNotEmpty()) {
            foreach ($pipelineModel->stages as $index => $stage) {
                $dealsInStage = Deal::where('stage_id', $stage->id)
                    ->with(['company', 'contact'])
                    ->latest()
                    ->get();

                $count = $dealsInStage->count();
                $stageValue = $dealsInStage->sum('value');
                $color = $stageColors[$index % count($stageColors)];

                $formattedDeals = $dealsInStage->take(5)->map(function ($deal) {
                    $companyName = $deal->company ? $deal->company->name : ($deal->contact ? $deal->contact->full_name : $deal->title);
                    $contactName = $deal->contact ? $deal->contact->full_name : ($deal->company ? $deal->company->name : 'N/A');
                    $words = explode(' ', $contactName);
                    $initials = strtoupper(substr($words[0] ?? 'C', 0, 1) . substr($words[1] ?? '', 0, 1));

                    return [
                        'id' => $deal->id,
                        'company' => $companyName,
                        'value' => '$' . number_format($deal->value, 2),
                        'contact' => $contactName,
                        'initials' => $initials ?: 'CR',
                        'time' => $deal->created_at ? $deal->created_at->diffForHumans() : 'Recently',
                        'contact_color' => 'primary'
                    ];
                })->toArray();

                $pipeline[$stage->name] = [
                    'stage_id' => $stage->id,
                    'count' => $count,
                    'value' => '$' . number_format($stageValue, 2),
                    'color' => $color,
                    'deals' => $formattedDeals
                ];
            }
        } else {
            $pipeline = [
                'Lead' => ['stage_id' => null, 'count' => 0, 'value' => '$0.00', 'color' => 'primary', 'deals' => []],
                'Qualified' => ['stage_id' => null, 'count' => 0, 'value' => '$0.00', 'color' => 'info', 'deals' => []],
                'Proposal' => ['stage_id' => null, 'count' => 0, 'value' => '$0.00', 'color' => 'purple', 'deals' => []],
                'Negotiation' => ['stage_id' => null, 'count' => 0, 'value' => '$0.00', 'color' => 'warning', 'deals' => []],
                'Closed Won' => ['stage_id' => null, 'count' => 0, 'value' => '$0.00', 'color' => 'success', 'deals' => []]
            ];
        }

        // 3. Monthly Revenue Chart Data (12 months of current year)
        $monthlyRevenue = [];
        $currentYear = date('Y');
        for ($month = 1; $month <= 12; $month++) {
            $rev = Deal::where('status', 'won')
                ->whereYear('closed_at', $currentYear)
                ->whereMonth('closed_at', $month)
                ->sum('value');

            if ($rev == 0) {
                $rev = Deal::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->sum('value');
            }

            $monthlyRevenue[] = round((float) $rev, 2);
        }

        // 4. Leads by Source Donut Chart Data
        $sourcesQuery = Contact::selectRaw('source, count(*) as count')
            ->whereNotNull('source')
            ->where('source', '!=', '')
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();

        $totalLeadsCount = array_sum($sourcesQuery);
        $defaultSources = [
            'Website' => 0,
            'Referral' => 0,
            'Social Media' => 0,
            'Email Campaign' => 0,
            'Other' => 0,
        ];

        foreach ($sourcesQuery as $src => $cnt) {
            $normalizedKey = ucfirst(strtolower(trim($src)));
            if (array_key_exists($normalizedKey, $defaultSources)) {
                $defaultSources[$normalizedKey] += $cnt;
            } else {
                $defaultSources['Other'] += $cnt;
            }
        }

        $colorMap = [
            'Website' => ['bg' => 'bg-primary', 'hex' => '#6366F1'],
            'Referral' => ['bg' => 'bg-primary opacity-75', 'hex' => '#3b82f6'],
            'Social Media' => ['bg' => 'bg-info', 'hex' => '#06b6d4'],
            'Email Campaign' => ['bg' => 'bg-warning', 'hex' => '#f59e0b'],
            'Other' => ['bg' => 'bg-secondary', 'hex' => '#6b7280'],
        ];

        $leadsBySourceData = [];
        foreach ($defaultSources as $sourceName => $count) {
            $percentage = $totalLeadsCount > 0 ? round(($count / $totalLeadsCount) * 100) : 0;
            $leadsBySourceData[] = [
                'source' => $sourceName,
                'count' => $count,
                'percentage' => $percentage,
                'color_class' => $colorMap[$sourceName]['bg'],
                'hex' => $colorMap[$sourceName]['hex'],
            ];
        }

        // 5. Recent Activities
        $dbActivities = Activity::with(['user', 'creator'])->latest()->take(5)->get();
        $activities = [];

        if ($dbActivities->isNotEmpty()) {
            foreach ($dbActivities as $act) {
                $userName = $act->user ? $act->user->name : ($act->creator ? $act->creator->name : 'User');
                $words = explode(' ', $userName);
                $initials = strtoupper(substr($words[0] ?? 'U', 0, 1) . substr($words[1] ?? '', 0, 1));

                $activities[] = [
                    'user' => $userName,
                    'action' => strtolower($act->type ?? 'activity'),
                    'note' => $act->subject ?: ($act->description ?: 'Updated CRM records'),
                    'time' => $act->created_at ? $act->created_at->diffForHumans() : 'Recently',
                    'initials' => $initials ?: 'US',
                    'color' => 'primary',
                    'icon' => 'fa-solid fa-circle-info'
                ];
            }
        } else {
            $recentTasks = Task::with('assignedTo')->latest()->take(3)->get();
            foreach ($recentTasks as $task) {
                $userName = $task->assignedTo ? $task->assignedTo->name : 'Staff';
                $words = explode(' ', $userName);
                $initials = strtoupper(substr($words[0] ?? 'T', 0, 1) . substr($words[1] ?? '', 0, 1));

                $activities[] = [
                    'user' => $userName,
                    'action' => 'assigned task',
                    'note' => $task->title,
                    'time' => $task->created_at ? $task->created_at->diffForHumans() : 'Recently',
                    'initials' => $initials ?: 'TS',
                    'color' => 'info',
                    'icon' => 'fa-solid fa-list-check'
                ];
            }

            $recentMeetings = Meeting::with('host')->latest()->take(2)->get();
            foreach ($recentMeetings as $meeting) {
                $userName = $meeting->host ? $meeting->host->name : 'Host';
                $words = explode(' ', $userName);
                $initials = strtoupper(substr($words[0] ?? 'M', 0, 1) . substr($words[1] ?? '', 0, 1));

                $activities[] = [
                    'user' => $userName,
                    'action' => 'scheduled meeting',
                    'note' => $meeting->title,
                    'time' => $meeting->created_at ? $meeting->created_at->diffForHumans() : 'Recently',
                    'initials' => $initials ?: 'MT',
                    'color' => 'success',
                    'icon' => 'fa-solid fa-calendar-days'
                ];
            }
        }

        // 6. Recent Contacts
        $dbContacts = Contact::with(['company', 'owner'])->latest()->take(5)->get();
        $contacts = [];
        $colors = ['primary', 'info', 'purple', 'success', 'warning'];

        foreach ($dbContacts as $index => $c) {
            $fullName = $c->full_name ?: 'Contact';
            $words = explode(' ', $fullName);
            $initials = strtoupper(substr($words[0] ?? 'C', 0, 1) . substr($words[1] ?? '', 0, 1));
            $color = $colors[$index % count($colors)];

            $dealSum = Deal::where('contact_id', $c->id)->sum('value');
            $formattedValue = $dealSum > 0 ? '$' . number_format($dealSum, 2) : 'N/A';

            $contacts[] = [
                'id' => $c->id,
                'name' => $fullName,
                'initials' => $initials ?: 'CT',
                'color' => $color,
                'company' => $c->company ? $c->company->name : ($c->job_title ? $c->job_title : 'N/A'),
                'status' => ucfirst($c->status ?? 'Lead'),
                'status_class' => strtolower($c->status ?? 'lead'),
                'last_contact' => $c->last_contacted_at ? $c->last_contacted_at->format('M d, Y') : ($c->created_at ? $c->created_at->format('M d, Y') : 'N/A'),
                'value' => $formattedValue,
                'owner' => $c->owner ? $c->owner->name : 'Unassigned',
            ];
        }

        return view('dashboard', compact(
            'stats',
            'pipeline',
            'activities',
            'contacts',
            'monthlyRevenue',
            'leadsBySourceData',
            'totalLeadsCount'
        ));
    }
}
