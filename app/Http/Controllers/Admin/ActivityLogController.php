<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filters
        if ($request->has('action') && $request->action !== 'all') {
            $query->byAction($request->action);
        }

        if ($request->has('entity_type') && $request->entity_type !== 'all') {
            $query->where('entity_type', $request->entity_type);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->forUser($request->user_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('entity_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->orderBy('created_at', 'desc')
                      ->paginate(50)
                      ->withQueryString();

        // Stats
        $stats = ActivityLogService::getStats(24);

        // Unique entity types for filter
        $entityTypes = ActivityLog::select('entity_type')
            ->distinct()
            ->orderBy('entity_type')
            ->pluck('entity_type');

        // Unique actions for filter
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Users for filter
        $users = \App\Models\User::select('id', 'first_name', 'last_name', 'email')
            ->orderBy('last_name')
            ->get();

        return view('admin.activity-logs.index', compact(
            'logs',
            'stats',
            'entityTypes',
            'actions',
            'users'
        ));
    }

    public function show(ActivityLog $log)
    {
        $log->load('user');
        return view('admin.activity-logs.show', compact('log'));
    }

    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $cutoffDate = now()->subDays($request->days);
        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        ActivityLogService::log(
            'cleared',
            'ActivityLog',
            null,
            "Cleared {$deletedCount} activity logs older than {$request->days} days",
            null,
            ['deleted_count' => $deletedCount, 'days' => $request->days]
        );

        return back()->with('success', "{$deletedCount} activity logs cleared successfully.");
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->has('action') && $request->action !== 'all') {
            $query->byAction($request->action);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'activity-logs-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'User', 'Action', 'Entity Type', 'Entity ID', 'Description', 'IP Address']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user?->name ?? 'System',
                    $log->action,
                    $log->entity_type,
                    $log->entity_id ?? 'N/A',
                    $log->description,
                    $log->ip_address ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
