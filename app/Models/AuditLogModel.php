<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Audit Log Model
 * Records all system actions for compliance and traceability.
 */
class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    protected $allowedFields = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip_address',
    ];

    /**
     * Get audit logs with user details.
     */
    public function getLogsWithUser(?string $entityType = null, ?string $action = null): array
    {
        $builder = $this->select('audit_logs.*, users.username')
                        ->join('users', 'users.id = audit_logs.user_id', 'left');

        if ($entityType) {
            $builder->where('audit_logs.entity_type', $entityType);
        }

        if ($action) {
            $builder->where('audit_logs.action', $action);
        }

        return $builder->orderBy('audit_logs.created_at', 'DESC')->findAll();
    }

    /**
     * Log an action.
     */
    public function logAction(
        ?int $userId,
        string $action,
        string $entityType,
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): bool {
        return $this->insert([
            'user_id'     => $userId,
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'old_values'  => $oldValues ? json_encode($oldValues) : null,
            'new_values'  => $newValues ? json_encode($newValues) : null,
            'ip_address'  => service('request')->getIPAddress(),
        ]) !== false;
    }
}
