<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait AuditLogger
{
    /**
     * Log an activity manually.
     */
    public function logActivity($action, $oldValues = null, $newValues = null)
    {
        // If used in a model, use its table and id
        $tableName = $this->getTable();
        $recordId = $this->getKey();

        AuditLog::create([
            'user_id' => Auth::id(), // Null if system/guest
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Boot the trait to auto-log standard events.
     * Optional: Can be disabled if manual logging is preferred.
     */
    public static function bootAuditLogger()
    {
        static::created(function ($model) {
            $model->logActivity('CREATE', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            // Avoid logging if only timestamps changed
            $changes = $model->getChanges();
            $original = $model->getOriginal();

            // Filter relevant changes
            $newValues = [];
            $oldValues = [];
            foreach ($changes as $key => $value) {
                if ($key !== 'updated_at') {
                    $newValues[$key] = $value;
                    $oldValues[$key] = $original[$key] ?? null;
                }
            }

            if (!empty($newValues)) {
                $model->logActivity('UPDATE', $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('DELETE', $model->getAttributes(), null);
        });
    }
}
