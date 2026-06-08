<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <?php if (isAdmin()): ?>
        <a href="/supply-requests" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Supply Requests</a>
        <?php else: ?>
        <a href="/staff/requests" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to My Requests</a>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
            <div>
                <h3 class="text-lg font-bold text-gray-900"><?= esc($request['request_code']) ?></h3>
                <p class="text-sm text-gray-500 mt-1">Submitted <?= formatDateTime($request['requested_at'] ?? $request['created_at']) ?></p>
            </div>
            <span class="badge <?= statusBadgeClass($request['status']) ?> capitalize text-sm px-4 py-1.5"><?= $request['status'] ?></span>
        </div>
        <div class="card-body space-y-6">
            <!-- Request Details -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 rounded-xl bg-gray-50"><p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Requester</p><p class="font-semibold mt-1 text-sm"><?= esc($request['requester_name']) ?></p></div>
                <div class="p-3 rounded-xl bg-gray-50"><p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Department</p><p class="font-semibold mt-1 text-sm"><?= esc($request['department_name']) ?></p></div>
                <div class="p-3 rounded-xl bg-gray-50"><p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Product</p><p class="font-semibold mt-1 text-sm"><?= esc($request['product_name']) ?> (<?= esc($request['product_code']) ?>)</p></div>
                <div class="p-3 rounded-xl bg-gray-50"><p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Requested Qty</p><p class="font-semibold mt-1 text-sm"><?= $request['requested_quantity'] ?> <?= esc($request['unit_of_measurement'] ?? '') ?></p></div>
                <?php if ($request['approved_quantity']): ?>
                <div class="p-3 rounded-xl bg-emerald-50"><p class="text-[10px] text-emerald-600 uppercase font-bold tracking-wider">Approved Qty</p><p class="font-semibold mt-1 text-sm text-emerald-700"><?= $request['approved_quantity'] ?></p></div>
                <?php endif; ?>
                <?php if ($request['approver_name']): ?>
                <div class="p-3 rounded-xl bg-gray-50"><p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Processed By</p><p class="font-semibold mt-1 text-sm"><?= esc($request['approver_name']) ?></p></div>
                <?php endif; ?>
            </div>

            <?php if ($request['notes']): ?>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100"><p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-2">Notes</p><p class="text-sm text-gray-700"><?= esc($request['notes']) ?></p></div>
            <?php endif; ?>

            <?php if ($request['feedback']): ?>
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100"><p class="text-[10px] text-blue-600 uppercase font-bold tracking-wider mb-2">Feedback</p><p class="text-sm text-blue-800"><?= esc($request['feedback']) ?></p></div>
            <?php endif; ?>

            <?php if ($request['rejection_reason']): ?>
            <div class="bg-red-50 rounded-xl p-4 border border-red-100"><p class="text-[10px] text-red-600 uppercase font-bold tracking-wider mb-2">Rejection Reason</p><p class="text-sm text-red-800"><?= esc($request['rejection_reason']) ?></p></div>
            <?php endif; ?>

            <!-- Timeline -->
            <div class="border-t pt-6">
                <h4 class="text-sm font-bold text-gray-800 mb-4">Timeline</h4>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-3 h-3 rounded-full bg-blue-500 mt-1 shrink-0 ring-4 ring-blue-100"></div>
                        <div><p class="text-sm font-semibold">Request submitted</p><p class="text-xs text-gray-500"><?= formatDateTime($request['requested_at'] ?? $request['created_at']) ?></p></div>
                    </div>
                    <?php if ($request['approved_at']): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-3 h-3 rounded-full bg-emerald-500 mt-1 shrink-0 ring-4 ring-emerald-100"></div>
                        <div><p class="text-sm font-semibold">Approved by <?= esc($request['approver_name']) ?></p><p class="text-xs text-gray-500"><?= formatDateTime($request['approved_at']) ?></p></div>
                    </div>
                    <?php endif; ?>
                    <?php if ($request['released_at']): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-3 h-3 rounded-full bg-primary-500 mt-1 shrink-0 ring-4 ring-primary-100"></div>
                        <div><p class="text-sm font-semibold">Released / Fulfilled</p><p class="text-xs text-gray-500"><?= formatDateTime($request['released_at']) ?></p></div>
                    </div>
                    <?php endif; ?>
                    <?php if ($request['rejected_at']): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-3 h-3 rounded-full bg-red-500 mt-1 shrink-0 ring-4 ring-red-100"></div>
                        <div><p class="text-sm font-semibold">Rejected</p><p class="text-xs text-gray-500"><?= formatDateTime($request['rejected_at']) ?></p></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
