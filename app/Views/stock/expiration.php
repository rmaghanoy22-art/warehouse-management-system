<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Expiration Tracking</h2>
        <p class="text-sm text-gray-500 mt-1">Monitor products nearing or past expiration</p>
    </div>
</div>

<?php if (empty($expiringProducts)): ?>
<div class="card">
    <div class="card-body text-center py-16">
        <div class="w-20 h-20 rounded-2xl icon-bg-emerald flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-gray-600 font-semibold text-lg">All Clear!</p>
        <p class="text-gray-400 text-sm mt-1">No products with expiration concerns</p>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Product</th><th>Code</th><th>Category</th><th>Stock</th><th>Expiration Date</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($expiringProducts as $p): $exp = expirationStatus($p['expiration_date']); ?>
                <tr>
                    <td class="font-semibold text-gray-900"><?= esc($p['name']) ?></td>
                    <td class="font-mono text-xs font-semibold"><?= esc($p['code']) ?></td>
                    <td><span class="badge badge-gray"><?= esc($p['category_name'] ?? '—') ?></span></td>
                    <td class="font-mono"><?= $p['quantity_in_stock'] ?></td>
                    <td class="text-sm"><?= formatDate($p['expiration_date']) ?></td>
                    <td><span class="badge <?= $exp['class'] ?>"><?= $exp['label'] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>
