<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    public static function log(
        string $action,
        string $entityType,
        ?int $entityId,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ActivityLog {
        return ActivityLog::log($action, $entityType, $entityId, $description, $oldValues, $newValues);
    }

    public static function logLogin(): ActivityLog
    {
        return ActivityLog::logLogin();
    }

    public static function logLogout(): ActivityLog
    {
        return ActivityLog::logLogout();
    }

    public static function logCreated($entity, string $entityType): ActivityLog
    {
        return ActivityLog::logCreated($entity, $entityType);
    }

    public static function logUpdated($entity, string $entityType, array $oldValues): ActivityLog
    {
        return ActivityLog::logUpdated($entity, $entityType, $oldValues);
    }

    public static function logDeleted($entity, string $entityType): ActivityLog
    {
        return ActivityLog::logDeleted($entity, $entityType);
    }

    public static function logApproval($entity, string $entityType): ActivityLog
    {
        return ActivityLog::logApproval($entity, $entityType);
    }

    public static function logRejection($entity, string $entityType, ?string $reason = null): ActivityLog
    {
        return ActivityLog::logRejection($entity, $entityType, $reason);
    }

    public static function logBulkAction(string $action, string $entityType, int $count, array $ids = []): ActivityLog
    {
        return ActivityLog::log(
            $action,
            $entityType,
            null,
            "Bulk {$action}: {$count} {$entityType}(s)",
            null,
            ['count' => $count, 'ids' => $ids]
        );
    }

    public static function logEnrollmentSubmission($application): ActivityLog
    {
        return ActivityLog::log(
            'submitted',
            'EnrollmentApplication',
            $application->id,
            "Enrollment application submitted: {$application->application_number}",
            null,
            $application->toArray()
        );
    }

    public static function logEnrollmentApproved($application, $sectionName): ActivityLog
    {
        return ActivityLog::log(
            'approved',
            'EnrollmentApplication',
            $application->id,
            "Enrollment approved: {$application->application_number} assigned to {$sectionName}",
            ['status' => 'pending', 'section_id' => null],
            ['status' => 'approved', 'section_name' => $sectionName]
        );
    }

    public static function logSettingChange(string $key, $oldValue, $newValue): ActivityLog
    {
        return ActivityLog::log(
            'updated',
            'Setting',
            null,
            "Setting changed: {$key}",
            ['value' => $oldValue],
            ['value' => $newValue]
        );
    }

    public static function getRecentActivity(int $limit = 20)
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getStats(int $hours = 24): array
    {
        return [
            'total' => ActivityLog::recent($hours)->count(),
            'created' => ActivityLog::recent($hours)->byAction('created')->count(),
            'updated' => ActivityLog::recent($hours)->byAction('updated')->count(),
            'deleted' => ActivityLog::recent($hours)->byAction('deleted')->count(),
            'approved' => ActivityLog::recent($hours)->byAction('approved')->count(),
            'rejected' => ActivityLog::recent($hours)->byAction('rejected')->count(),
            'logins' => ActivityLog::recent($hours)->byAction('login')->count(),
        ];
    }
}
