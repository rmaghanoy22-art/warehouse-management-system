<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Supply Requests</h2>
        <p class="text-sm text-gray-500 mt-1">Manage all supply requests across departments</p>
    </div>
</div>

<!-- Status Filter Tabs -->
<div class="flex flex-wrap gap-2 mb-6">
    <a href="/supply-requests" class="<?= empty($currentStatus) ? 'bg-primary-600 text-white shadow-md shadow-primary-500/25' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' ?> px-4 py-2 text-sm font-semibold rounded-xl cursor-pointer transition-all duration-200">
        All (<?= array_sum($statusCounts) ?>)
    </a>
    <?php foreach ($statusCounts as $status => $count): ?>
    <a href="/supply-requests?status=<?= $status ?>" class="<?= ($currentStatus ?? '') === $status ? 'bg-primary-600 text-white shadow-md shadow-primary-500/25' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' ?> px-4 py-2 text-sm font-semibold rounded-xl cursor-pointer capitalize transition-all duration-200">
        <?= $status ?> (<?= $count ?>)
    </a>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Code</th><th>Requester</th><th>Department</th><th>Product</th><th>Qty</th><th>Status</th><th>Date</th><th class="text-right">Actions</th></tr></thead>
            <tbody>
                <?php if (empty($requests)): ?>
                <tr><td colspan="8" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-gray-400 font-medium">No requests found</p>
                </td></tr>
                <?php else: foreach ($requests as $req): ?>
                <tr>
                    <td class="font-mono text-xs font-semibold text-primary-600"><a href="/supply-requests/<?= $req['id'] ?>" class="hover:underline"><?= esc($req['request_code']) ?></a></td>
                    <td class="font-medium"><?= esc($req['requester_name']) ?></td>
                    <td><span class="badge badge-gray"><?= esc($req['department_name']) ?></span></td>
                    <td><?= esc($req['product_name']) ?></td>
                    <td class="font-mono"><?= $req['requested_quantity'] ?> <?= esc($req['unit_of_measurement'] ?? '') ?></td>
                    <td><span class="badge <?= statusBadgeClass($req['status']) ?> capitalize"><?= $req['status'] ?></span></td>
                    <td class="text-xs text-gray-500"><?= timeAgo($req['created_at']) ?></td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <?php if ($req['status'] === 'pending'): ?>
                            <form action="/supply-requests/approve/<?= $req['id'] ?>" method="POST" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="approved_quantity" value="<?= $req['requested_quantity'] ?>">
                                <button class="btn-success btn-sm">Approve</button>
                            </form>
                            <form action="/supply-requests/reject/<?= $req['id'] ?>" method="POST" class="inline" data-prompt-reject="Rejection Reason" data-placeholder="Enter the reason for rejecting this request...">
                                <?= csrf_field() ?>
                                <input type="hidden" name="rejection_reason" value="">
                                <button class="btn-danger btn-sm">Reject</button>
                            </form>
                            <?php elseif ($req['status'] === 'approved'): ?>
                            <form action="/supply-requests/release/<?= $req['id'] ?>" method="POST" class="inline" data-confirm="Are you sure you want to release this request? Stock will be deducted immediately.">
                                <?= csrf_field() ?>
                                <button class="btn-primary btn-sm">Release</button>
                            </form>
                            <?php else: ?>
                            <a href="/supply-requests/<?= $req['id'] ?>" class="btn-secondary btn-sm">View</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
