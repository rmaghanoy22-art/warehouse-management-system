<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Inventory</h2>
        <p class="text-sm text-gray-500 mt-1">Browse available products (read-only)</p>
    </div>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Code</th><th>Product</th><th>Category</th><th>In Stock</th><th>Unit</th><th>Status</th></tr></thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr><td colspan="6" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p class="text-gray-400 font-medium">No products available</p>
                </td></tr>
                <?php else: ?>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td class="font-mono text-xs font-semibold"><?= esc($p['code']) ?></td>
                    <td class="font-medium text-gray-900"><?= esc($p['name']) ?></td>
                    <td><span class="badge badge-gray"><?= esc($p['category_name'] ?? '—') ?></span></td>
                    <td>
                        <span class="<?= ($p['quantity_in_stock'] <= $p['reorder_level']) ? 'text-red-600 font-bold' : 'text-gray-900' ?>">
                            <?= $p['quantity_in_stock'] ?>
                        </span>
                        <?php if ($p['quantity_in_stock'] <= $p['reorder_level']): ?>
                        <span class="badge badge-danger ml-1 text-[10px]">LOW</span>
                        <?php endif; ?>
                    </td>
                    <td class="capitalize text-xs"><?= esc($p['unit_of_measurement']) ?></td>
                    <td><span class="badge <?= statusBadgeClass($p['status']) ?> capitalize"><?= $p['status'] ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
