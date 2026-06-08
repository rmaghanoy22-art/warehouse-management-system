<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Stock Movement Logs</h2>
        <p class="text-sm text-gray-500 mt-1">Track all inventory changes and adjustments</p>
    </div>
    <a href="/stock/adjust" class="btn-primary"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Adjust Stock</a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <select name="product_id" class="form-select">
                <option value="">All Products</option>
                <?php foreach ($products as $p): ?>
                <option value="<?= $p['id'] ?>" <?= ($currentProduct ?? '') == $p['id'] ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="log_type" class="form-select">
                <option value="">All Types</option>
                <option value="add" <?= ($currentType ?? '') === 'add' ? 'selected' : '' ?>>Add</option>
                <option value="remove" <?= ($currentType ?? '') === 'remove' ? 'selected' : '' ?>>Remove</option>
                <option value="adjust" <?= ($currentType ?? '') === 'adjust' ? 'selected' : '' ?>>Adjust</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>Filter
                </button>
                <a href="/stock/logs" class="btn-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Date</th><th>Product</th><th>Type</th><th>Qty</th><th>Before</th><th>After</th><th>By</th><th>Notes</th></tr></thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr><td colspan="8" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-gray-400 font-medium">No stock logs found</p>
                </td></tr>
                <?php else: foreach ($logs as $log): ?>
                <tr>
                    <td class="text-xs text-gray-500"><?= formatDateTime($log['created_at']) ?></td>
                    <td class="font-semibold"><?= esc($log['product_name'] ?? '') ?></td>
                    <td>
                        <?php $typeColors = ['add' => 'badge-success', 'remove' => 'badge-danger', 'adjust' => 'badge-info']; ?>
                        <span class="badge <?= $typeColors[$log['log_type']] ?? 'badge-gray' ?> capitalize"><?= $log['log_type'] ?></span>
                    </td>
                    <td class="font-mono font-semibold <?php 
                        if ($log['log_type'] === 'add' || ($log['log_type'] === 'adjust' && (int)$log['new_quantity'] > (int)$log['previous_quantity'])) {
                            echo 'text-emerald-600';
                        } elseif ($log['log_type'] === 'remove' || ($log['log_type'] === 'adjust' && (int)$log['new_quantity'] < (int)$log['previous_quantity'])) {
                            echo 'text-red-600';
                        } else {
                            echo 'text-gray-500';
                        }
                    ?>"><?php
                        if ($log['log_type'] === 'add' || ($log['log_type'] === 'adjust' && (int)$log['new_quantity'] > (int)$log['previous_quantity'])) {
                            echo '+';
                        } elseif ($log['log_type'] === 'remove' || ($log['log_type'] === 'adjust' && (int)$log['new_quantity'] < (int)$log['previous_quantity'])) {
                            echo '-';
                        }
                    ?><?= $log['quantity'] ?></td>
                    <td class="text-gray-500"><?= $log['previous_quantity'] ?></td>
                    <td class="font-semibold"><?= $log['new_quantity'] ?></td>
                    <td><?= esc($log['performed_by_name'] ?? '') ?></td>
                    <td class="text-xs text-gray-500 max-w-xs truncate"><?= esc($log['notes'] ?? '—') ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
