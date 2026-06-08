<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Welcome Banner -->
<div class="mb-8 p-6 rounded-2xl bg-gradient-to-r from-primary-600 via-primary-700 to-indigo-800 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-white/5 rounded-full translate-y-1/2"></div>
    <div class="relative z-10">
        <h2 class="text-2xl font-bold">Welcome back, <?= esc(session()->get('username')) ?>! 👋</h2>
        <p class="text-primary-200 mt-1 text-sm">Here's what's happening in your warehouse today.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card stagger-in">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Products</p>
                <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalProducts ?></p>
            </div>
            <div class="w-12 h-12 rounded-xl icon-bg-primary flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
            <?php if (count($lowStockProducts) > 0): ?>
            <span class="inline-flex w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
            <span class="text-red-500 font-medium"><?= count($lowStockProducts) ?> low stock</span>
            <?php else: ?>
            <span class="text-emerald-500 font-medium">All stocked</span>
            <?php endif; ?>
        </p>
    </div>

    <div class="stat-card stagger-in">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pending Requests</p>
                <p class="text-3xl font-bold text-amber-600 mt-1"><?= $pendingRequests ?></p>
            </div>
            <div class="w-12 h-12 rounded-xl icon-bg-amber flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <a href="/supply-requests?status=pending" class="text-xs text-amber-600 hover:text-amber-700 mt-3 block font-medium transition-colors">View pending →</a>
    </div>

    <div class="stat-card stagger-in">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Released Today</p>
                <p class="text-3xl font-bold text-emerald-600 mt-1"><?= $releasedRequests ?></p>
            </div>
            <div class="w-12 h-12 rounded-xl icon-bg-emerald flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3"><?= $approvedRequests ?> awaiting release</p>
    </div>

    <div class="stat-card stagger-in">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Active Users</p>
                <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalUsers ?></p>
            </div>
            <div class="w-12 h-12 rounded-xl icon-bg-blue flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3"><?= $totalDepartments ?> departments</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="card">
        <div class="card-header"><h3 class="text-sm font-bold text-gray-800">Request Status Distribution</h3></div>
        <div class="card-body"><canvas id="statusChart" height="200"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="text-sm font-bold text-gray-800">Quick Actions</h3></div>
        <div class="card-body grid grid-cols-2 gap-3">
            <a href="/products/create" class="btn-primary text-center py-3.5 rounded-xl">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Product
            </a>
            <a href="/supply-requests?status=pending" class="btn-warning text-center py-3.5 rounded-xl">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>Review Requests
            </a>
            <a href="/stock/adjust" class="btn-secondary text-center py-3.5 rounded-xl">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Adjust Stock
            </a>
            <a href="/users/create" class="btn-secondary text-center py-3.5 rounded-xl">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>Add User
            </a>
        </div>
    </div>
</div>

<!-- Low Stock & Expiring Alerts -->
<?php if (!empty($lowStockProducts) || !empty($expiringProducts)): ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <?php if (!empty($lowStockProducts)): ?>
    <div class="card border-amber-200/60">
        <div class="card-header bg-gradient-to-r from-amber-50 to-orange-50 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <h3 class="text-sm font-bold text-amber-800">Low Stock Alerts (<?= count($lowStockProducts) ?>)</h3>
        </div>
        <div class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
            <?php foreach (array_slice($lowStockProducts, 0, 5) as $p): ?>
            <div class="px-6 py-3 flex items-center justify-between hover:bg-amber-50/30 transition-colors">
                <div>
                    <p class="text-sm font-medium text-gray-900"><?= esc($p['name']) ?></p>
                    <p class="text-xs text-gray-500"><?= esc($p['code']) ?></p>
                </div>
                <span class="badge badge-danger"><?= $p['quantity_in_stock'] ?> left</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($expiringProducts)): ?>
    <div class="card border-red-200/60">
        <div class="card-header bg-gradient-to-r from-red-50 to-rose-50 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-sm font-bold text-red-800">Expiration Alerts (<?= count($expiringProducts) ?>)</h3>
        </div>
        <div class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
            <?php foreach (array_slice($expiringProducts, 0, 5) as $p): $exp = expirationStatus($p['expiration_date']); ?>
            <div class="px-6 py-3 flex items-center justify-between hover:bg-red-50/30 transition-colors">
                <div>
                    <p class="text-sm font-medium text-gray-900"><?= esc($p['name']) ?></p>
                    <p class="text-xs text-gray-500">Exp: <?= formatDate($p['expiration_date']) ?></p>
                </div>
                <span class="badge <?= $exp['class'] ?>"><?= $exp['label'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Recent Requests Table -->
<div class="card">
    <div class="card-header flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-800">Recent Supply Requests</h3>
        <a href="/supply-requests" class="text-xs text-primary-600 hover:text-primary-700 font-semibold transition-colors">View All →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr><th>Code</th><th>Requester</th><th>Product</th><th>Qty</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php if (empty($recentRequests)): ?>
                <tr><td colspan="6" class="text-center text-gray-400 py-12">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    No requests yet
                </td></tr>
                <?php else: ?>
                <?php foreach ($recentRequests as $req): ?>
                <tr>
                    <td class="font-mono text-xs font-semibold text-primary-600"><?= esc($req['request_code']) ?></td>
                    <td class="font-medium"><?= esc($req['requester_name']) ?></td>
                    <td><?= esc($req['product_name']) ?></td>
                    <td class="font-mono"><?= $req['requested_quantity'] ?></td>
                    <td><span class="badge <?= statusBadgeClass($req['status']) ?> capitalize"><?= $req['status'] ?></span></td>
                    <td class="text-xs text-gray-500"><?= timeAgo($req['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Released', 'Rejected'],
                datasets: [{
                    data: [<?= $pendingRequests ?>, <?= $approvedRequests ?>, <?= $releasedRequests ?>, <?= $rejectedRequests ?>],
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(59, 130, 246, 0.85)',
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(239, 68, 68, 0.85)'
                    ],
                    borderWidth: 0,
                    borderRadius: 6,
                    spacing: 3,
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true, pointStyleWidth: 10, font: { size: 12, weight: '600' } }
                    }
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
