<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('settings.view');

        $query = AuditLog::with('user')->latest();

        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        $logs = $query->limit(50)->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}
