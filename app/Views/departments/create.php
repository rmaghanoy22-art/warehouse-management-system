<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-lg mx-auto">
    <div class="mb-6"><a href="/departments" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Departments</a></div>
    <div class="card">
        <div class="card-header bg-gradient-to-r from-gray-50 to-white"><h3 class="text-lg font-bold">Add New Department</h3></div>
        <div class="card-body">
            <form action="/departments/store" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                <div><label class="form-label">Department Name *</label><input type="text" name="name" value="<?= old('name') ?>" class="form-input" required></div>
                <div><label class="form-label">Code *</label><input type="text" name="code" value="<?= old('code') ?>" class="form-input uppercase" placeholder="e.g., ADMIN, ENG, OPS" maxlength="20" required><small class="text-gray-500 text-xs">Unique department code (auto-capitalized)</small></div>
                <div><label class="form-label">Description</label><textarea name="description" class="form-input" rows="3"><?= old('description') ?></textarea></div>
                <div class="flex gap-3"><button type="submit" class="btn-primary">Create Department</button><a href="/departments" class="btn-secondary">Cancel</a></div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
