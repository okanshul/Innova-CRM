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

        $perPage = $request->get('per_page', setting('items_per_page', 10));
        $logs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}
