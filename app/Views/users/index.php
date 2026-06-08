<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">User Management</h2>
        <p class="text-sm text-gray-500 mt-1">Manage system users and their roles</p>
    </div>
    <a href="/users/create" class="btn-primary"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>Add User</a>
</div>
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Department</th><th>Status</th><th>Last Login</th><th class="text-right">Actions</th></tr></thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr><td colspan="7" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <p class="text-gray-400 font-medium">No users found</p>
                </td></tr>
                <?php else: ?>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);"><?= strtoupper(substr($u['username'], 0, 1)) ?></div>
                            <span class="font-semibold"><?= esc($u['username']) ?></span>
                        </div>
                    </td>
                    <td class="text-sm text-gray-500"><?= esc($u['email']) ?></td>
                    <td><span class="badge badge-primary capitalize"><?= $u['role'] ?></span></td>
                    <td><?= esc($u['department_name'] ?? '—') ?></td>
                    <td><span class="badge <?= statusBadgeClass($u['status']) ?> capitalize"><?= $u['status'] ?></span></td>
                    <td class="text-xs text-gray-500"><?= $u['last_login'] ? timeAgo($u['last_login']) : 'Never' ?></td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/users/edit/<?= $u['id'] ?>" class="btn-secondary btn-sm">Edit</a>
                            <?php if ((int)$u['id'] !== (int)session()->get('user_id')): ?>
                            <form action="/users/delete/<?= $u['id'] ?>" method="POST" data-confirm="Are you sure you want to delete this user? This action cannot be undone."><?= csrf_field() ?><button class="btn-danger btn-sm">Delete</button></form>
                            <?php endif; ?>
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
