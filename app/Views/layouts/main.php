<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Warehouse Management System - Enterprise inventory and supply management">
    <title><?= esc($title ?? 'WMS') ?> — Warehouse Management System</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Sleek scrollbar utility */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        /* Completely hide scrollbars */
        .scrollbar-none::-webkit-scrollbar {
            display: none !important;
        }
        .scrollbar-none {
            -ms-overflow-style: none !important;  /* IE and Edge */
            scrollbar-width: none !important;  /* Firefox */
        }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: true, mobileSidebar: false }">
<div class="flex h-full">
    <!-- Sidebar -->
    <?= $this->include('layouts/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
        <!-- Header -->
        <?= $this->include('layouts/header') ?>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6 bg-gradient-to-br from-gray-50 via-white to-gray-50/80">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mb-6 page-enter" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-medium"><?= session()->getFlashdata('success') ?></span>
                <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800 transition-colors">&times;</button>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mb-6 page-enter" x-data="{ show: true }" x-show="show"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span class="font-medium"><?= session()->getFlashdata('error') ?></span>
                <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800 transition-colors">&times;</button>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger mb-6 page-enter">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <ul class="list-disc list-inside">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="page-enter">
                <?= $this->renderSection('content') ?>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-100 bg-white/60 backdrop-blur-sm px-6 py-3 shrink-0">
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-400">&copy; <?= date('Y') ?> Warehouse Management System</p>
                <p class="text-xs text-gray-400 hidden sm:block">v1.0.0</p>
            </div>
        </footer>
    </div>
</div>

<!-- Mobile sidebar overlay -->
<div x-show="mobileSidebar" @click="mobileSidebar = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 lg:hidden" x-cloak x-transition.opacity></div>

<!-- Custom WMS Global Modal (Confirm / Prompt) -->
<div x-data="{
    open: false,
    type: 'confirm',
    title: '',
    message: '',
    placeholder: '',
    value: '',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    resolve: null,
    show(e) {
        this.type = e.detail.type;
        this.title = e.detail.title;
        this.message = e.detail.message || '';
        this.placeholder = e.detail.placeholder || '';
        this.value = '';
        this.confirmText = e.detail.confirmText || 'Confirm';
        this.cancelText = e.detail.cancelText || 'Cancel';
        this.resolve = e.detail.resolve;
        this.open = true;
        if (this.type === 'prompt') {
            this.$nextTick(() => {
                setTimeout(() => {
                    const el = this.$refs.inputField;
                    if (el) {
                        el.focus();
                        el.select();
                    }
                }, 100);
            });
        }
    },
    confirm() {
        if (this.type === 'prompt' && !this.value.trim()) return;
        if (this.resolve) this.resolve(this.type === 'prompt' ? this.value : true);
        this.open = false;
    },
    cancel() {
        if (this.resolve) this.resolve(this.type === 'prompt' ? null : false);
        this.open = false;
    }
}"
@wms-show-modal.window="show($event)"
x-show="open" 
class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
x-cloak
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0">
     
    <!-- Modal Dialog -->
    <div @click.outside="cancel()"
         x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
         
        <!-- Header -->
        <div class="px-6 pt-6 pb-4 flex items-start gap-4">
            <!-- Icon -->
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                 :class="type === 'prompt' ? 'bg-primary-50 text-primary-600' : 'bg-red-50 text-red-600'">
                <template x-if="type === 'prompt'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </template>
                <template x-if="type === 'confirm'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </template>
            </div>
            
            <div class="flex-1">
                <h3 class="text-base font-bold text-gray-900" x-text="title">Confirm Action</h3>
                <p class="text-sm text-gray-500 mt-1" x-text="message" x-show="message"></p>
            </div>
        </div>
        
        <!-- Input field for Prompt -->
        <div class="px-6 py-2" x-show="type === 'prompt'">
            <input type="text" 
                   x-model="value" 
                   x-ref="inputField"
                   @keydown.enter="confirm()"
                   class="form-input w-full"
                   :placeholder="placeholder"
                   required>
        </div>
        
        <!-- Footer / Actions -->
        <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3 border-t border-gray-100">
            <button @click="cancel()" 
                    class="btn-secondary py-2 px-4 text-xs font-semibold" 
                    x-text="cancelText">Cancel</button>
            <button @click="confirm()" 
                    :disabled="type === 'prompt' && !value.trim()"
                    class="py-2 px-4 text-xs font-semibold text-white rounded-xl transition-all duration-200"
                    :class="type === 'prompt' ? 'bg-primary-600 hover:bg-primary-700 shadow-md shadow-primary-500/20 disabled:opacity-50' : 'bg-red-600 hover:bg-red-700 shadow-md shadow-red-500/20'"
                    x-text="confirmText">Confirm</button>
        </div>
    </div>
</div>

<script src="/assets/js/app.js?v=1.0.2"></script>
</body>
</html>
