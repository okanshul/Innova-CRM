<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            ['title' => 'Total Revenue', 'value' => '$124,560', 'change' => '+12.5%', 'is_positive' => true, 'icon' => 'fa-solid fa-dollar-sign', 'color' => 'primary'],
            ['title' => 'New Leads', 'value' => '1,245', 'change' => '+8.3%', 'is_positive' => true, 'icon' => 'fa-solid fa-users', 'color' => 'info'],
            ['title' => 'Active Deals', 'value' => '328', 'change' => '+15.7%', 'is_positive' => true, 'icon' => 'fa-solid fa-briefcase', 'color' => 'success'],
            ['title' => 'Conversion Rate', 'value' => '24.6%', 'change' => '-3.2%', 'is_positive' => false, 'icon' => 'fa-solid fa-bullseye', 'color' => 'warning'],
        ];

        $pipeline = [
            'Lead' => ['count' => 12, 'value' => '$24,300', 'color' => 'primary', 'deals' => [
                ['company' => 'Acme Corporation', 'value' => '$4,500', 'contact' => 'Michael Smith', 'initials' => 'MS', 'time' => '2d ago', 'contact_color' => 'primary'],
                ['company' => 'Globex Inc.', 'value' => '$3,200', 'contact' => 'Sarah Johnson', 'initials' => 'SJ', 'time' => '3d ago', 'contact_color' => 'info']
            ]],
            'Qualified' => ['count' => 8, 'value' => '$18,750', 'color' => 'info', 'deals' => [
                ['company' => 'Stark Industries', 'value' => '$8,750', 'contact' => 'Tony Stark', 'initials' => 'TS', 'time' => '1d ago', 'contact_color' => 'secondary'],
                ['company' => 'Wayne Enterprises', 'value' => '$4,200', 'contact' => 'Bruce Wayne', 'initials' => 'BW', 'time' => '2d ago', 'contact_color' => 'dark']
            ]],
            'Proposal' => ['count' => 6, 'value' => '$22,100', 'color' => 'purple', 'deals' => [
                ['company' => 'Oscorp', 'value' => '$7,800', 'contact' => 'Norman Osborn', 'initials' => 'NO', 'time' => '1d ago', 'contact_color' => 'success'],
                ['company' => 'Cyberdyne Systems', 'value' => '$5,600', 'contact' => 'John Connor', 'initials' => 'JC', 'time' => '2d ago', 'contact_color' => 'info']
            ]],
            'Negotiation' => ['count' => 4, 'value' => '$15,300', 'color' => 'warning', 'deals' => [
                ['company' => 'Umbrella Corporation', 'value' => '$8,900', 'contact' => 'Albert Wesker', 'initials' => 'AW', 'time' => '1d ago', 'contact_color' => 'purple'],
                ['company' => 'Initech', 'value' => '$6,400', 'contact' => 'Peter Gibbons', 'initials' => 'PG', 'time' => '2d ago', 'contact_color' => 'info']
            ]],
            'Closed Won' => ['count' => 10, 'value' => '$42,110', 'color' => 'success', 'deals' => [
                ['company' => 'Wonka Industries', 'value' => '$12,500', 'contact' => 'Willy Wonka', 'initials' => 'WW', 'time' => '3d ago', 'contact_color' => 'success'],
                ['company' => 'Hooli', 'value' => '$9,750', 'contact' => 'Richard Hendricks', 'initials' => 'RH', 'time' => '4d ago', 'contact_color' => 'success']
            ]]
        ];

        $activities = [
            ['user' => 'Michael Smith', 'action' => 'called you', 'note' => 'Discussed about proposal for Acme Corp deal', 'time' => '10:30 AM', 'initials' => 'MS', 'color' => 'primary', 'icon' => 'fa-solid fa-phone'],
            ['user' => 'Sarah Johnson', 'action' => 'sent an email', 'note' => 'Re: Proposal for marketing collaboration', 'time' => 'Yesterday', 'initials' => 'SJ', 'color' => 'info', 'icon' => 'fa-solid fa-envelope'],
            ['user' => 'Tony Stark', 'action' => 'updated deal stage', 'note' => 'Stark Industries moved to Proposal', 'time' => '2d ago', 'initials' => 'TS', 'color' => 'purple', 'icon' => 'fa-solid fa-circle-info'],
            ['user' => 'Bruce Wayne', 'action' => 'meeting scheduled', 'note' => 'Meeting scheduled on May 28, 2024', 'time' => '3d ago', 'initials' => 'BW', 'color' => 'success', 'icon' => 'fa-solid fa-calendar-days']
        ];

        $contacts = [
            ['name' => 'Michael Smith', 'initials' => 'MS', 'color' => 'primary', 'company' => 'Acme Corporation', 'status' => 'Lead', 'status_class' => 'lead', 'last_contact' => 'May 25, 2024', 'value' => '$4,500', 'owner' => 'John Doe'],
            ['name' => 'Sarah Johnson', 'initials' => 'SJ', 'color' => 'info', 'company' => 'Globex Inc.', 'status' => 'Qualified', 'status_class' => 'qualified', 'last_contact' => 'May 24, 2024', 'value' => '$3,200', 'owner' => 'John Doe'],
            ['name' => 'Tony Stark', 'initials' => 'TS', 'color' => 'purple', 'company' => 'Stark Industries', 'status' => 'Proposal', 'status_class' => 'proposal', 'last_contact' => 'May 23, 2024', 'value' => '$6,750', 'owner' => 'Jane Smith'],
            ['name' => 'Bruce Wayne', 'initials' => 'BW', 'color' => 'success', 'company' => 'Wayne Enterprises', 'status' => 'Negotiation', 'status_class' => 'negotiation', 'last_contact' => 'May 22, 2024', 'value' => '$4,200', 'owner' => 'John Doe'],
            ['name' => 'Norman Osborn', 'initials' => 'NO', 'color' => 'warning', 'company' => 'Oscorp', 'status' => 'Proposal', 'status_class' => 'proposal', 'last_contact' => 'May 21, 2024', 'value' => '$7,800', 'owner' => 'Jane Smith'],
        ];

        return view('dashboard', compact('stats', 'pipeline', 'activities', 'contacts'));
    }
}
