<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Department — Warehouse Management System</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full">
<div class="min-h-full flex items-center justify-center px-6 py-12 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome to WMS</h2>
                <p class="text-gray-500 text-sm mb-1">Logged in as: <strong><?= htmlspecialchars($user_email ?? 'Unknown') ?></strong></p>
                <p class="text-gray-500 text-sm">Please select your department</p>
            </div>

            <!-- Department Selection Form -->
            <form action="/auth/google/set-department" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-input" required autofocus>
                        <option value="">-- Select a department --</option>
                        <?php /** @var array $departments */ ?>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept['id']) ?>">
                                <?= htmlspecialchars($dept['name']) ?>
                                <small class="text-gray-500">(<?= htmlspecialchars($dept['code']) ?>)</small>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">This determines which department's inventory you'll manage and which requests you can create.</p>
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-base">
                    Continue
                </button>
            </form>

            <!-- Help Text -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-xs text-gray-500 text-center">
                    Not sure which department? You can update this later in your profile settings.
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
