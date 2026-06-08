<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-lg mx-auto">
    <div class="mb-6"><a href="/users" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Users</a></div>
    <div class="card">
        <div class="card-header bg-gradient-to-r from-gray-50 to-white"><h3 class="text-lg font-bold">Edit User — <?= esc($user['username']) ?></h3></div>
        <div class="card-body">
            <form action="/users/update/<?= $user['id'] ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                <div><label class="form-label">Username *</label><input type="text" name="username" value="<?= old('username', $user['username']) ?>" class="form-input" required></div>
                <div><label class="form-label">Email *</label><input type="email" name="email" value="<?= old('email', $user['email']) ?>" class="form-input" required></div>
                <div>
                    <label class="form-label">New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span></label>
                    <div class="password-wrapper relative">
                        <input type="password" id="edit_user_password" name="password" class="form-input pr-11" minlength="8" placeholder="Enter new password">
                        <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition-colors" tabindex="-1">
                            <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-select" required>
                            <?php foreach (['staff','admin','warehouse'] as $r): ?>
                            <option value="<?= $r ?>" <?= old('role', $user['role']) === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select">
                            <?php foreach ($departments as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= old('department_id', $user['department_id']) == $d['id'] ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= old('status', $user['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= old('status', $user['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="flex gap-3"><button type="submit" class="btn-primary">Update User</button><a href="/users" class="btn-secondary">Cancel</a></div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
