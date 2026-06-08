<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Departments</h2>
        <p class="text-sm text-gray-500 mt-1">Manage organizational departments</p>
    </div>
    <a href="/departments/create" class="btn-primary"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Department</a>
</div>
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Name</th><th>Description</th><th class="text-right">Actions</th></tr></thead>
            <tbody>
                <?php if (empty($departments)): ?>
                <tr><td colspan="3" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="text-gray-400 font-medium">No departments yet</p>
                </td></tr>
                <?php else: ?>
                <?php foreach ($departments as $dept): ?>
                <tr>
                    <td class="font-semibold text-gray-900"><?= esc($dept['name']) ?></td>
                    <td class="text-gray-500 text-sm"><?= esc($dept['description'] ?? '—') ?></td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/departments/edit/<?= $dept['id'] ?>" class="btn-secondary btn-sm">Edit</a>
                            <form action="/departments/delete/<?= $dept['id'] ?>" method="POST" data-confirm="Are you sure you want to delete this department? Users in this department will be unlinked.">
                                <?= csrf_field() ?>
                                <button class="btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
