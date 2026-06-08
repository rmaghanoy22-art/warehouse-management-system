<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Products</h2>
        <p class="text-sm text-gray-500 mt-1"><?= count($products) ?> products found</p>
    </div>
    <a href="/products/create" class="btn-primary">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Product
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="/products" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Search by name or code..." class="form-input">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($currentCategory ?? '') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" <?= ($currentStatus ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($currentStatus ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                <option value="discontinued" <?= ($currentStatus ?? '') === 'discontinued' ? 'selected' : '' ?>>Discontinued</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>Filter
                </button>
                <a href="/products" class="btn-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table" id="productsTable">
            <thead>
                <tr><th>Code</th><th>Product Name</th><th>Category</th><th>Stock</th><th>Unit</th><th>Expiration</th><th>Status</th><th class="text-right">Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr><td colspan="8" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p class="text-gray-400 font-medium">No products found</p>
                    <p class="text-gray-300 text-xs mt-1">Try adjusting your filters</p>
                </td></tr>
                <?php else: foreach ($products as $p): $exp = expirationStatus($p['expiration_date'] ?? null); ?>
                <tr>
                    <td class="font-mono text-xs font-semibold"><?= esc($p['code']) ?></td>
                    <td>
                        <div class="font-medium text-gray-900"><?= esc($p['name']) ?></div>
                        <?php if ($p['description']): ?><div class="text-xs text-gray-400 truncate max-w-xs"><?= esc($p['description']) ?></div><?php endif; ?>
                    </td>
                    <td><span class="badge badge-gray"><?= esc($p['category_name'] ?? '—') ?></span></td>
                    <td>
                        <?php $isLow = $p['quantity_in_stock'] <= $p['reorder_level']; ?>
                        <span class="<?= $isLow ? 'text-red-600 font-bold' : 'text-gray-900' ?>"><?= $p['quantity_in_stock'] ?></span>
                        <?php if ($isLow): ?><span class="badge badge-danger ml-1 text-[10px]">LOW</span><?php endif; ?>
                    </td>
                    <td class="capitalize text-xs"><?= esc($p['unit_of_measurement']) ?></td>
                    <td><span class="badge <?= $exp['class'] ?>"><?= $exp['label'] ?></span></td>
                    <td><span class="badge <?= statusBadgeClass($p['status']) ?> capitalize"><?= $p['status'] ?></span></td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/products/edit/<?= $p['id'] ?>" class="btn-secondary btn-sm">Edit</a>
                            <form action="/products/delete/<?= $p['id'] ?>" method="POST" data-confirm="Are you sure you want to delete this product? This action cannot be undone.">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
