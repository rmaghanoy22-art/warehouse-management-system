<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">My Supply Requests</h2>
        <p class="text-sm text-gray-500 mt-1">Track and manage your supply requests</p>
    </div>
    <a href="/staff/requests/create" class="btn-primary"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>New Request</a>
</div>
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Code</th><th>Product</th><th>Qty</th><th>Status</th><th>Feedback</th><th>Date</th></tr></thead>
            <tbody>
                <?php if (empty($requests)): ?>
                <tr><td colspan="6" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-gray-400 font-medium">No requests yet</p>
                    <a href="/staff/requests/create" class="text-primary-600 text-xs font-semibold hover:underline mt-1 inline-block">Create your first request →</a>
                </td></tr>
                <?php else: foreach ($requests as $req): ?>
                <tr class="cursor-pointer" onclick="window.location='/staff/requests/<?= $req['id'] ?>'">
                    <td class="font-mono text-xs font-semibold text-primary-600"><?= esc($req['request_code']) ?></td>
                    <td class="font-medium"><?= esc($req['product_name']) ?></td>
                    <td class="font-mono"><?= $req['requested_quantity'] ?></td>
                    <td><span class="badge <?= statusBadgeClass($req['status']) ?> capitalize"><?= $req['status'] ?></span></td>
                    <td class="text-xs text-gray-500 max-w-xs truncate"><?= esc($req['feedback'] ?? $req['rejection_reason'] ?? '—') ?></td>
                    <td class="text-xs text-gray-500"><?= timeAgo($req['created_at']) ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
