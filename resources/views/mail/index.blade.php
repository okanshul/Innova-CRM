@extends('layouts.app', ['title' => 'InnovaCRM - Mail Inbox'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Mail']]" />

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
        <x-page-header title="Mail Inbox" subtitle="Manage client communications and emails." icon="fa-regular fa-envelope">
            <x-slot:actions>
                <button class="btn btn-primary rounded-3 px-3 py-2 fw-semibold border-0 shadow-sm" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Compose Email
                </button>
            </x-slot:actions>
        </x-page-header>

        <div class="p-4">
            <div class="card border p-4 rounded-4 bg-body-tertiary text-center py-5">
                <i class="fa-regular fa-paper-plane fs-1 text-primary mb-3"></i>
                <h5 class="fw-bold">CRM Mail Inbox</h5>
                <p class="text-secondary small mb-0">Connect your email server or view synced email threads with leads and contacts.</p>
            </div>
        </div>
    </div>
@endsection
