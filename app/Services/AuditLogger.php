<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    /**
     * Fields that must never be persisted in an audit payload.
     *
     * @var list<string>
     */
    private const SENSITIVE_FIELDS = [
        'password',
        'password_confirmation',
        'token',
        'access_token',
        'refresh_token',
        'remember_token',
        'secret',
        'api_key',
        'bank_account_number',
        'npwp',
    ];

    public function __construct(private readonly ?Request $request = null)
    {
    }

    public function record(
        string $action,
        Model|string|null $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?User $actor = null,
        ?int $subjectId = null,
    ): AuditLog {
        $request = $this->request ?? request();
        $actor ??= Auth::user();

        [$entityType, $entityId] = $this->resolveSubject($subject, $subjectId);

        return AuditLog::create([
            'user_id' => $actor?->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $this->redact($oldValues),
            'new_values' => $this->redact($newValues),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    /**
     * @return array{0: string, 1: int|null}
     */
    private function resolveSubject(Model|string|null $subject, ?int $subjectId): array
    {
        if ($subject instanceof Model) {
            return [class_basename($subject), $subject->getKey()];
        }

        return [$subject ?? 'System', $subjectId];
    }

    private function redact(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        $redacted = [];
        foreach ($values as $key => $value) {
            if (in_array(strtolower((string) $key), self::SENSITIVE_FIELDS, true)) {
                $redacted[$key] = '[REDACTED]';
                continue;
            }

            $redacted[$key] = is_array($value) ? $this->redact($value) : $value;
        }

        return $redacted;
    }
}
