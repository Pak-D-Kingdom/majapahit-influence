<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Record a security-relevant change without persisting secrets.
     *
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public function record(
        string $action,
        string $entityType,
        int|string|null $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?User $user = null,
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user?->id ?? Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $this->sanitize($oldValues),
            'new_values' => $this->sanitize($newValues),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /** @param array<string, mixed>|null $values */
    private function sanitize(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        $sensitiveKeys = ['password', 'password_confirmation', 'remember_token', 'token', 'api_token', 'secret'];

        foreach ($values as $key => $value) {
            if (in_array(strtolower((string) $key), $sensitiveKeys, true)) {
                $values[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $values[$key] = $this->sanitize($value);
            }
        }

        return $values;
    }
}
