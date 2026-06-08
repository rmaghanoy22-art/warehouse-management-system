<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="text-xl font-bold text-gray-900 mb-6">Audit Logs</h2>

<div class="card mb-6">
    <div class="card-body">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <select name="entity_type" class="form-select">
                <option value="">All Entities</option>
                <?php foreach (['product','department','user','supply_request','auth'] as $e): ?>
                <option value="<?= $e ?>" <?= ($currentEntityType ?? '') === $e ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $e)) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="action" class="form-select">
                <option value="">All Actions</option>
                <?php foreach (['create','update','delete','login','logout','approve','reject','release','stock_adjust'] as $a): ?>
                <option value="<?= $a ?>" <?= ($currentAction ?? '') === $a ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $a)) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="flex gap-2"><button type="submit" class="btn-primary flex-1">Filter</button><a href="/audit-logs" class="btn-secondary">Clear</a></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Time</th><th>User</th><th>Action</th><th>Entity</th><th>Details</th><th>IP</th></tr></thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr><td colspan="6" class="text-center text-gray-400 py-8">No audit logs found</td></tr>
                <?php else: foreach ($logs as $log): ?>
                <tr>
                    <td class="text-xs text-gray-500 whitespace-nowrap"><?= formatDateTime($log['created_at']) ?></td>
                    <td><?= esc($log['username'] ?? 'System') ?></td>
                    <td><span class="badge badge-info capitalize"><?= str_replace('_', ' ', $log['action']) ?></span></td>
                    <td class="capitalize"><?= str_replace('_', ' ', $log['entity_type']) ?> <?= $log['entity_id'] ? '#' . $log['entity_id'] : '' ?></td>
                    <td class="text-xs text-gray-500 max-w-xs truncate">
                        <?php if ($log['new_values']): ?>
                        <?= esc(substr($log['new_values'], 0, 80)) ?>...
                        <?php else: ?>—<?php endif; ?>
                    </td>
                    <td class="font-mono text-xs"><?= esc($log['ip_address'] ?? '') ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
