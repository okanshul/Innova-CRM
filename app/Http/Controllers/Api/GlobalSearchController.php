<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Meeting;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        $category = $request->get('category', 'all');

        $results = [
            'contacts' => [],
            'deals' => [],
            'tasks' => [],
            'meetings' => [],
            'staff' => [],
            'companies' => [],
        ];

        $totalCount = 0;

        if (empty($q)) {
            return response()->json([
                'success' => true,
                'query' => '',
                'total' => 0,
                'results' => $results,
                'quick_links' => [
                    [
                        'title' => 'Dashboard Overview',
                        'subtitle' => 'View performance metrics and summary',
                        'icon' => 'fa-solid fa-chart-line',
                        'url' => route('dashboard'),
                        'badge' => 'Quick Link',
                        'badge_class' => 'bg-primary-subtle text-primary',
                    ],
                    [
                        'title' => 'Contacts Directory',
                        'subtitle' => 'Manage leads, prospects, and customers',
                        'icon' => 'fa-solid fa-address-book',
                        'url' => route('contacts.index'),
                        'badge' => 'Quick Link',
                        'badge_class' => 'bg-info-subtle text-info',
                    ],
                    [
                        'title' => 'Deals & Sales Pipeline',
                        'subtitle' => 'Track deals through pipeline stages',
                        'icon' => 'fa-solid fa-handshake',
                        'url' => route('deals.index'),
                        'badge' => 'Quick Link',
                        'badge_class' => 'bg-success-subtle text-success',
                    ],
                    [
                        'title' => 'Tasks & Todo List',
                        'subtitle' => 'Manage upcoming activities and tasks',
                        'icon' => 'fa-solid fa-list-check',
                        'url' => route('tasks.index'),
                        'badge' => 'Quick Link',
                        'badge_class' => 'bg-warning-subtle text-warning',
                    ],
                    [
                        'title' => 'Meetings Calendar',
                        'subtitle' => 'Schedule and view upcoming meetings',
                        'icon' => 'fa-solid fa-calendar-days',
                        'url' => route('meetings.index'),
                        'badge' => 'Quick Link',
                        'badge_class' => 'bg-purple-subtle text-purple',
                    ],
                    [
                        'title' => 'Staff Directory',
                        'subtitle' => 'View team members and roles',
                        'icon' => 'fa-solid fa-user-gear',
                        'url' => route('staff.index'),
                        'badge' => 'Quick Link',
                        'badge_class' => 'bg-secondary-subtle text-secondary',
                    ],
                ]
            ]);
        }

        // Search Contacts
        if (in_array($category, ['all', 'contacts'])) {
            $contacts = Contact::with('company')
                ->where(function ($query) use ($q) {
                    $query->where('first_name', 'like', "%{$q}%")
                        ->orWhere('last_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('job_title', 'like', "%{$q}%");
                })
                ->limit(6)
                ->get()
                ->map(function ($c) {
                    $name = trim($c->first_name . ' ' . $c->last_name);
                    $subParts = array_filter([$c->job_title, $c->company?->name, $c->email ?? $c->phone]);
                    return [
                        'id' => $c->id,
                        'category' => 'contacts',
                        'category_name' => 'Contacts',
                        'title' => $name,
                        'subtitle' => implode(' · ', $subParts) ?: 'Contact Record',
                        'icon' => 'fa-solid fa-address-book',
                        'url' => route('contacts.show', $c->id),
                        'badge' => ucfirst($c->status ?? 'Lead'),
                        'badge_class' => 'bg-primary-subtle text-primary',
                    ];
                })
                ->toArray();

            $results['contacts'] = $contacts;
            $totalCount += count($contacts);
        }

        // Search Deals
        if (in_array($category, ['all', 'deals'])) {
            $deals = Deal::with(['company', 'stage'])
                ->where('title', 'like', "%{$q}%")
                ->limit(6)
                ->get()
                ->map(function ($d) {
                    $formattedValue = '$' . number_format($d->value, 2);
                    $stageName = $d->stage?->name ?? ucfirst($d->status);
                    $subParts = array_filter([$formattedValue, $stageName, $d->company?->name]);
                    return [
                        'id' => $d->id,
                        'category' => 'deals',
                        'category_name' => 'Deals',
                        'title' => $d->title,
                        'subtitle' => implode(' · ', $subParts),
                        'icon' => 'fa-solid fa-handshake',
                        'url' => route('deals.show', $d->id),
                        'badge' => ucfirst($d->status),
                        'badge_class' => $d->status === 'won' ? 'bg-success-subtle text-success' : ($d->status === 'lost' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning'),
                    ];
                })
                ->toArray();

            $results['deals'] = $deals;
            $totalCount += count($deals);
        }

        // Search Tasks
        if (in_array($category, ['all', 'tasks'])) {
            $tasks = Task::where('title', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->limit(6)
                ->get()
                ->map(function ($t) {
                    $statusFormatted = str_replace('_', ' ', ucfirst($t->status));
                    $due = $t->due_date ? 'Due ' . \Carbon\Carbon::parse($t->due_date)->format('M d') : null;
                    $subParts = array_filter([$statusFormatted, 'Priority: ' . ucfirst($t->priority), $due]);
                    return [
                        'id' => $t->id,
                        'category' => 'tasks',
                        'category_name' => 'Tasks',
                        'title' => $t->title,
                        'subtitle' => implode(' · ', $subParts),
                        'icon' => 'fa-solid fa-list-check',
                        'url' => route('tasks.show', $t->id),
                        'badge' => ucfirst($t->priority),
                        'badge_class' => 'bg-info-subtle text-info',
                    ];
                })
                ->toArray();

            $results['tasks'] = $tasks;
            $totalCount += count($tasks);
        }

        // Search Meetings
        if (in_array($category, ['all', 'meetings'])) {
            $meetings = Meeting::where('title', 'like', "%{$q}%")
                ->orWhere('location', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->limit(6)
                ->get()
                ->map(function ($m) {
                    $time = \Carbon\Carbon::parse($m->start_at)->format('M d, Y @ h:i A');
                    $subParts = array_filter([$time, $m->location]);
                    return [
                        'id' => $m->id,
                        'category' => 'meetings',
                        'category_name' => 'Meetings',
                        'title' => $m->title,
                        'subtitle' => implode(' · ', $subParts),
                        'icon' => 'fa-solid fa-calendar-days',
                        'url' => route('meetings.show', $m->id),
                        'badge' => ucfirst($m->status),
                        'badge_class' => 'bg-purple-subtle text-purple',
                    ];
                })
                ->toArray();

            $results['meetings'] = $meetings;
            $totalCount += count($meetings);
        }

        // Search Staff
        if (in_array($category, ['all', 'staff'])) {
            $staff = User::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('position', 'like', "%{$q}%")
                    ->orWhere('department', 'like', "%{$q}%");
            })
                ->limit(6)
                ->get()
                ->map(function ($u) {
                    $subParts = array_filter([$u->position ?? 'Team Member', $u->department, $u->email]);
                    return [
                        'id' => $u->id,
                        'category' => 'staff',
                        'category_name' => 'Staff',
                        'title' => $u->name,
                        'subtitle' => implode(' · ', $subParts),
                        'icon' => 'fa-solid fa-user-gear',
                        'url' => route('staff.show', $u->id),
                        'badge' => $u->department ?? 'Staff',
                        'badge_class' => 'bg-secondary-subtle text-secondary',
                    ];
                })
                ->toArray();

            $results['staff'] = $staff;
            $totalCount += count($staff);
        }

        // Search Companies
        if (in_array($category, ['all', 'companies'])) {
            $companies = Company::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('industry', 'like', "%{$q}%")
                    ->orWhere('domain', 'like', "%{$q}%");
            })
                ->limit(6)
                ->get()
                ->map(function ($comp) {
                    $subParts = array_filter([$comp->industry, $comp->domain, $comp->phone ?? $comp->email]);
                    return [
                        'id' => $comp->id,
                        'category' => 'companies',
                        'category_name' => 'Companies',
                        'title' => $comp->name,
                        'subtitle' => implode(' · ', $subParts) ?: 'Company Record',
                        'icon' => 'fa-solid fa-building',
                        'url' => route('contacts.index', ['company_id' => $comp->id]),
                        'badge' => 'Company',
                        'badge_class' => 'bg-dark-subtle text-dark',
                    ];
                })
                ->toArray();

            $results['companies'] = $companies;
            $totalCount += count($companies);
        }

        return response()->json([
            'success' => true,
            'query' => $q,
            'total' => $totalCount,
            'results' => $results,
        ]);
    }
}
