<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\View\View;

class AuditTrailController extends Controller
{
    public function index(): View
    {
        $logs = AuditLog::with('user:id,name,email')
            ->when(request('action'), fn ($q, $action) => $q->where('action', 'like', "%{$action}%"))
            ->when(request('entity_type'), fn ($q, $entity) => $q->where('entity_type', $entity))
            ->when(request('user_id'), fn ($q, $userId) => $q->where('user_id', $userId))
            ->when(request('date_from'), fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when(request('date_to'), fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->latest('created_at')->paginate(20)->withQueryString();

        return view('superadmin.audit.index', [
            'logs' => $logs,
            'users' => User::whereHas('auditLogs')->orderBy('name')->get(['id', 'name']),
            'entityTypes' => AuditLog::query()->select('entity_type')->distinct()->orderBy('entity_type')->pluck('entity_type'),
        ]);
    }
}
