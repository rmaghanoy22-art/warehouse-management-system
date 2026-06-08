<?php

namespace App\Libraries;

use App\Models\AuditLogModel;

/**
 * Audit Service
 * Provides a clean API for logging all system actions.
 */
class AuditService
{
    protected AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Log a create action.
     */
    public function logCreate(string $entityType, int $entityId, array $newValues): void
    {
        $this->auditLogModel->logAction(
            session()->get('user_id'),
            'create',
            $entityType,
            $entityId,
            null,
            $newValues
        );
    }

    /**
     * Log an update action.
     */
    public function logUpdate(string $entityType, int $entityId, array $oldValues, array $newValues): void
    {
        $this->auditLogModel->logAction(
            session()->get('user_id'),
            'update',
            $entityType,
            $entityId,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log a delete action.
     */
    public function logDelete(string $entityType, int $entityId, array $oldValues): void
    {
        $this->auditLogModel->logAction(
            session()->get('user_id'),
            'delete',
            $entityType,
            $entityId,
            $oldValues,
            null
        );
    }

    /**
     * Log a custom action (login, approve, reject, etc.).
     */
    public function logCustom(string $action, string $entityType, ?int $entityId = null, ?array $details = null): void
    {
        $this->auditLogModel->logAction(
            session()->get('user_id'),
            $action,
            $entityType,
            $entityId,
            null,
            $details
        );
    }
}
