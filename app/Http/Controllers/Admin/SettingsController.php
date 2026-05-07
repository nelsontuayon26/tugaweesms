<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Services\ActivityLogService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = $this->settingService->getAllSettings();
        
        // Get all school years for the academic dropdown
        $schoolYears = \App\Models\SchoolYear::orderBy('name', 'desc')->get();
        
        // Get active school year for real-time academic sync
        $activeSchoolYear = \App\Models\SchoolYear::getActive();
        
        // Override academic settings with active school year data
        if ($activeSchoolYear) {
            $settings['active_school_year_id'] = $activeSchoolYear->id;
            $settings['current_school_year'] = $activeSchoolYear->name;
            $settings['school_year_start'] = $activeSchoolYear->start_date ? $activeSchoolYear->start_date->format('Y-m-d') : '';
            $settings['school_year_end'] = $activeSchoolYear->end_date ? $activeSchoolYear->end_date->format('Y-m-d') : '';
        }
        
        // Get recent activity logs for the logs tab
        $recentLogs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        // Get activity stats
        $activityStats = ActivityLogService::getStats(24);
        
        // Get system health metrics
        $health = $this->getHealthMetrics();
        
        return view('admin.settings.index', compact(
            'settings', 
            'schoolYears',
            'activeSchoolYear',
            'recentLogs', 
            'activityStats',
            'health'
        ));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        try {
            $data = $request->except(['_token', '_method']);

            // Handle file upload
            if ($request->hasFile('school_logo')) {
                $path = $this->settingService->handleFileUpload(
                    $request->file('school_logo'), 
                    'school_logo'
                );
                $data['school_logo'] = $path;
            }

            // Sync active school year from school_years table
            if ($request->filled('active_school_year_id')) {
                $selectedYear = \App\Models\SchoolYear::find($request->input('active_school_year_id'));
                if ($selectedYear) {
                    // Deactivate all other school years
                    \App\Models\SchoolYear::where('id', '!=', $selectedYear->id)->update(['is_active' => false]);
                    // Activate selected
                    $selectedYear->update(['is_active' => true]);
                    
                    // Sync settings
                    $data['active_school_year_id'] = $selectedYear->id;
                    $data['current_school_year'] = $selectedYear->name;
                    $data['school_year_start'] = $selectedYear->start_date ? $selectedYear->start_date->format('Y-m-d') : '';
                    $data['school_year_end'] = $selectedYear->end_date ? $selectedYear->end_date->format('Y-m-d') : '';
                }
            }

            $this->settingService->updateSettings($data);
            
            // Log the settings change
            ActivityLogService::log(
                'updated',
                'Setting',
                null,
                'Settings updated by admin',
                null,
                ['changed_keys' => array_keys($data)]
            );

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Settings updated successfully!');

        } catch (\Exception $e) {
            Log::error('Settings update failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Create database backup
     */
    public function backup()
    {
        try {
            $path = $this->settingService->createBackup();
            
            ActivityLogService::log(
                'created',
                'Backup',
                null,
                'Database backup created',
                null,
                ['path' => $path]
            );
            
            return response()->download($path)->deleteFileAfterSend();
            
        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Backup creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        try {
            $this->settingService->clearCache();
            
            ActivityLogService::log(
                'cleared',
                'Cache',
                null,
                'Application cache cleared'
            );
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cache cleared successfully!'
                ]);
            }
            
            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Cache cleared successfully!');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to clear cache: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Toggle enrollment submissions on/off
     */
    public function toggleEnrollment(Request $request)
    {
        try {
            $value = $request->input('enrollment_enabled', '0');
            $enabled = $value === '1' || $value === true || $value === 1 || $value === 'true';
            
            Setting::set(
                'enrollment_enabled', 
                $enabled, 
                'boolean', 
                'enrollment'
            );
            
            $status = $enabled ? 'enabled' : 'disabled';
            
            ActivityLogService::log(
                $enabled ? 'enabled' : 'disabled',
                'Setting',
                null,
                "Enrollment submissions {$status}"
            );
            
            $isAjax = $request->ajax() || $request->wantsJson() || $request->isJson();
            
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => "Enrollment submissions {$status}!",
                    'enabled' => $enabled
                ]);
            }
            
            return redirect()
                ->route('admin.enrollment.index')
                ->with('success', "Enrollment submissions {$status}!");
                
        } catch (\Exception $e) {
            Log::error('Enrollment toggle failed: ' . $e->getMessage());
            
            $isAjax = $request->ajax() || $request->wantsJson() || $request->isJson();
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to toggle enrollment: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()
                ->back()
                ->with('error', 'Failed to toggle enrollment: ' . $e->getMessage());
        }
    }

    /**
     * Reset settings to defaults
     */
    public function reset()
    {
        try {
            if (!request()->confirm_reset) {
                return redirect()
                    ->route('admin.settings.index')
                    ->with('error', 'Please confirm settings reset');
            }

            $this->settingService->resetToDefaults();
            
            ActivityLogService::log(
                'reset',
                'Setting',
                null,
                'All settings reset to defaults'
            );
            
            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Settings reset to default values!');
                
        } catch (\Exception $e) {
            Log::error('Settings reset failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Failed to reset settings: ' . $e->getMessage());
        }
    }

    /**
     * Export data
     */
    public function export(string $type)
    {
        $allowedTypes = ['students', 'teachers', 'grades', 'attendance'];
        
        if (!in_array($type, $allowedTypes)) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Invalid export type');
        }

        $filename = $type . '_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');
            
            switch ($type) {
                case 'students':
                    fputcsv($file, ['LRN', 'Last Name', 'First Name', 'Grade Level', 'Section', 'Status']);
                    $students = \App\Models\Student::with(['gradeLevel', 'section'])->get();
                    foreach ($students as $s) {
                        fputcsv($file, [
                            $s->lrn,
                            $s->user->last_name ?? '',
                            $s->user->first_name ?? '',
                            $s->gradeLevel?->name ?? '',
                            $s->section?->name ?? '',
                            $s->user->status ?? 'active'
                        ]);
                    }
                    break;
                    
                case 'teachers':
                    fputcsv($file, ['ID', 'Last Name', 'First Name', 'Email', 'Status']);
                    $teachers = \App\Models\Teacher::with('user')->get();
                    foreach ($teachers as $t) {
                        fputcsv($file, [
                            $t->id,
                            $t->user?->last_name ?? '',
                            $t->user?->first_name ?? '',
                            $t->user?->email ?? '',
                            $t->user?->status ?? 'active'
                        ]);
                    }
                    break;
                    
                case 'grades':
                    fputcsv($file, ['Student', 'Subject', 'Grade', 'Quarter', 'School Year']);
                    $grades = \App\Models\Grade::with(['student.user', 'subject', 'schoolYear'])->limit(1000)->get();
                    foreach ($grades as $g) {
                        fputcsv($file, [
                            ($g->student?->user?->last_name ?? '') . ', ' . ($g->student?->user?->first_name ?? ''),
                            $g->subject?->name ?? '',
                            $g->score ?? 'N/A',
                            $g->quarter ?? 'N/A',
                            $g->schoolYear?->name ?? ''
                        ]);
                    }
                    break;
                    
                case 'attendance':
                    fputcsv($file, ['Student', 'Date', 'Status', 'Remarks']);
                    $attendances = \App\Models\Attendance::with(['student.user'])->limit(1000)->get();
                    foreach ($attendances as $a) {
                        fputcsv($file, [
                            ($a->student?->user?->last_name ?? '') . ', ' . ($a->student?->user?->first_name ?? ''),
                            $a->date ?? '',
                            $a->status ?? '',
                            $a->remarks ?? ''
                        ]);
                    }
                    break;
            }
            
            fclose($file);
        };

        ActivityLogService::log(
            'exported',
            $type,
            null,
            "Exported {$type} data to CSV"
        );

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Regenerate API key
     */
    public function regenerateApiKey()
    {
        try {
            $newKey = bin2hex(random_bytes(32));
            
            $this->settingService->updateSetting('api_key', $newKey, 'advanced');
            
            ActivityLogService::log(
                'regenerated',
                'API',
                null,
                'API key regenerated'
            );
            
            return response()->json([
                'success' => true,
                'api_key' => $newKey
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent activity logs as JSON
     */
    public function getLogs(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');
        
        if ($request->has('action') && $request->action !== 'all') {
            $query->byAction($request->action);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('entity_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        
        $logs = $query->limit(50)->get()->map(function($log) {
            return [
                'id' => $log->id,
                'action' => $log->action,
                'action_icon' => $log->action_icon,
                'action_color' => $log->action_color,
                'entity_type' => $log->entity_type,
                'description' => $log->description,
                'user_name' => $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'System',
                'ip_address' => $log->ip_address,
                'created_at' => $log->created_at->diffForHumans(),
                'created_at_full' => $log->created_at->format('M d, Y h:i A'),
            ];
        });
        
        return response()->json([
            'success' => true,
            'logs' => $logs,
            'stats' => ActivityLogService::getStats(24)
        ]);
    }

    /**
     * Download system log file
     */
    public function downloadLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!file_exists($logPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Log file not found'
            ], 404);
        }
        
        return response()->download($logPath, 'system-logs-' . date('Y-m-d') . '.log');
    }

    /**
     * Clear old log files
     */
    public function clearLogs(Request $request)
    {
        try {
            $request->validate(['days' => 'required|integer|min:1|max:365']);
            
            $logPath = storage_path('logs/laravel.log');
            $cleared = false;
            
            if (file_exists($logPath)) {
                // Archive current log before clearing
                $archivePath = storage_path('logs/laravel-' . date('Y-m-d-His') . '.log');
                copy($logPath, $archivePath);
                
                // Clear the log file
                file_put_contents($logPath, '');
                $cleared = true;
            }
            
            // Also clear old archived logs
            $cutoff = now()->subDays($request->days);
            $logFiles = glob(storage_path('logs/*.log'));
            $deletedCount = 0;
            
            foreach ($logFiles as $file) {
                if (basename($file) !== 'laravel.log' && filemtime($file) < $cutoff->timestamp) {
                    unlink($file);
                    $deletedCount++;
                }
            }
            
            ActivityLogService::log(
                'cleared',
                'Log',
                null,
                "System logs cleared ({$deletedCount} old archives deleted)"
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Logs cleared successfully!',
                'archives_deleted' => $deletedCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update email configuration
     */
    public function updateEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'mail_driver' => 'required|string',
                'mail_host' => 'nullable|string',
                'mail_port' => 'nullable|integer',
                'mail_username' => 'nullable|string',
                'mail_password' => 'nullable|string',
                'mail_encryption' => 'nullable|string',
                'mail_from_address' => 'nullable|email',
                'mail_from_name' => 'nullable|string',
            ]);
            
            foreach ($validated as $key => $value) {
                $this->settingService->updateSetting($key, $value, 'email');
            }
            
            ActivityLogService::log(
                'updated',
                'Setting',
                null,
                'Email configuration updated'
            );
            
            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Email settings updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Failed to update email settings: ' . $e->getMessage());
        }
    }

    /**
     * Get system health metrics
     */
    public function getHealth()
    {
        $metrics = $this->getHealthMetrics();
        
        return response()->json([
            'success' => true,
            'health' => $metrics
        ]);
    }

    /**
     * Get health metrics array
     */
    private function getHealthMetrics(): array
    {
        // Disk usage
        $diskTotal = disk_total_space(base_path());
        $diskFree = disk_free_space(base_path());
        $diskUsed = $diskTotal - $diskFree;
        $diskPercent = round(($diskUsed / $diskTotal) * 100, 1);
        
        // Memory usage
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        
        // Convert to readable
        $formatBytes = function($bytes) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $unitIndex = 0;
            while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
                $bytes /= 1024;
                $unitIndex++;
            }
            return round($bytes, 2) . ' ' . $units[$unitIndex];
        };
        
        // Laravel log size
        $logPath = storage_path('logs/laravel.log');
        $logSize = file_exists($logPath) ? filesize($logPath) : 0;
        
        // Database connection test
        $dbConnected = false;
        $dbName = config('database.connections.mysql.database');
        try {
            \DB::connection()->getPdo();
            $dbConnected = true;
        } catch (\Exception $e) {
            $dbConnected = false;
        }
        
        // Queue status
        $queueJobs = 0;
        $failedJobs = 0;
        try {
            if (\Schema::hasTable('jobs')) {
                $queueJobs = \DB::table('jobs')->count();
            }
            if (\Schema::hasTable('failed_jobs')) {
                $failedJobs = \DB::table('failed_jobs')->count();
            }
        } catch (\Exception $e) {
            // Queue tables may not exist
        }
        
        return [
            'disk' => [
                'total' => $formatBytes($diskTotal),
                'used' => $formatBytes($diskUsed),
                'free' => $formatBytes($diskFree),
                'percent' => $diskPercent,
                'status' => $diskPercent > 90 ? 'critical' : ($diskPercent > 75 ? 'warning' : 'good')
            ],
            'memory' => [
                'limit' => $memoryLimit,
                'usage' => $formatBytes($memoryUsage),
                'peak' => $formatBytes($memoryPeak),
            ],
            'logs' => [
                'size' => $formatBytes($logSize),
                'path' => $logPath,
            ],
            'database' => [
                'connected' => $dbConnected,
                'name' => $dbName,
                'version' => $dbConnected ? \DB::select('SELECT VERSION() as version')[0]->version : null,
            ],
            'queue' => [
                'pending' => $queueJobs,
                'failed' => $failedJobs,
            ],
            'php' => [
                'version' => PHP_VERSION,
                'max_upload' => ini_get('upload_max_filesize'),
                'max_execution' => ini_get('max_execution_time') . 's',
            ],
            'laravel' => [
                'version' => app()->version(),
                'environment' => config('app.env'),
                'debug' => config('app.debug'),
            ]
        ];
    }
}
