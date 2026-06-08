<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Welcome Banner -->
<div class="mb-8 p-6 rounded-2xl bg-gradient-to-r from-primary-600 via-indigo-600 to-purple-700 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-1/3 w-48 h-48 bg-white/5 rounded-full translate-y-1/2"></div>
    <div class="relative z-10 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Hello, <?= esc(session()->get('username')) ?>! 👋</h2>
            <p class="text-primary-200 mt-1 text-sm">Track your supply requests and manage your inventory needs.</p>
        </div>
        <a href="/staff/requests/create" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 bg-white/15 hover:bg-white/25 backdrop-blur rounded-xl text-sm font-semibold text-white transition-all duration-200 ring-1 ring-white/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Request
        </a>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card stagger-in">
        <div class="flex items-center justify-between">
            <div><p class="text-sm font-medium text-gray-500">Total Requests</p><p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalRequests ?></p></div>
            <div class="w-12 h-12 rounded-xl icon-bg-primary flex items-center justify-center"><svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
        </div>
    </div>
    <div class="stat-card stagger-in">
        <div class="flex items-center justify-between">
            <div><p class="text-sm font-medium text-gray-500">Pending</p><p class="text-3xl font-bold text-amber-600 mt-1"><?= $pendingCount ?></p></div>
            <div class="w-12 h-12 rounded-xl icon-bg-amber flex items-center justify-center"><svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        </div>
    </div>
    <div class="stat-card stagger-in">
        <div class="flex items-center justify-between">
            <div><p class="text-sm font-medium text-gray-500">Approved</p><p class="text-3xl font-bold text-blue-600 mt-1"><?= $approvedCount ?></p></div>
            <div class="w-12 h-12 rounded-xl icon-bg-blue flex items-center justify-center"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        </div>
    </div>
    <div class="stat-card stagger-in">
        <div class="flex items-center justify-between">
            <div><p class="text-sm font-medium text-gray-500">Released</p><p class="text-3xl font-bold text-emerald-600 mt-1"><?= $releasedCount ?></p></div>
            <div class="w-12 h-12 rounded-xl icon-bg-emerald flex items-center justify-center"><svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
        </div>
    </div>
</div>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <h2 class="text-lg font-bold text-gray-800">My Recent Requests</h2>
    <a href="/staff/requests/create" class="btn-primary sm:hidden">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>New Request
    </a>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Code</th><th>Product</th><th>Qty</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                <?php if (empty($myRequests)): ?>
                <tr><td colspan="5" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-gray-400 font-medium">No requests yet</p>
                    <p class="text-gray-300 text-xs mt-1">Create your first supply request to get started!</p>
                </td></tr>
                <?php else: foreach ($myRequests as $req): ?>
                <tr class="cursor-pointer" onclick="window.location='/staff/requests/<?= $req['id'] ?>'">
                    <td class="font-mono text-xs font-semibold text-primary-600"><?= esc($req['request_code']) ?></td>
                    <td class="font-medium"><?= esc($req['product_name']) ?></td>
                    <td><?= $req['requested_quantity'] ?> <?= esc($req['unit_of_measurement'] ?? '') ?></td>
                    <td><span class="badge <?= statusBadgeClass($req['status']) ?> capitalize"><?= $req['status'] ?></span></td>
                    <td class="text-xs text-gray-500"><?= timeAgo($req['created_at']) ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
