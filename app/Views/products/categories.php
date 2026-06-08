<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Product Categories</h2>
        <p class="text-sm text-gray-500 mt-1">Organize your products into categories</p>
    </div>
    <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')" class="btn-primary"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Category</button>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Name</th><th>Description</th><th>Products</th><th class="text-right">Actions</th></tr></thead>
            <tbody>
                <?php if (empty($categories)): ?>
                <tr><td colspan="4" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <p class="text-gray-400 font-medium">No categories yet</p>
                </td></tr>
                <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td class="font-semibold text-gray-900"><?= esc($cat['name']) ?></td>
                    <td class="text-gray-500 text-xs max-w-xs truncate"><?= esc($cat['description'] ?? '—') ?></td>
                    <td><span class="badge badge-primary"><?= $cat['product_count'] ?? 0 ?></span></td>
                    <td class="text-right">
                        <form action="/categories/delete/<?= $cat['id'] ?>" method="POST" class="inline" data-confirm="Are you sure you want to delete this category? All products under this category will have their category unlinked.">
                            <?= csrf_field() ?>
                            <button class="btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100"><h3 class="text-lg font-bold text-gray-900">Add Category</h3></div>
        <div class="p-6">
            <form action="/categories/store" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div><label class="form-label">Name *</label><input type="text" name="name" class="form-input" required autofocus></div>
                <div><label class="form-label">Description</label><textarea name="description" class="form-input" rows="2"></textarea></div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">Create</button>
                    <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')" class="btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
