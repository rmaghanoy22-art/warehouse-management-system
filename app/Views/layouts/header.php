<?php helper('permission'); ?>
<!-- Header -->
<header class="sticky top-0 z-20 glass-header border-b border-gray-200/60 h-16 flex items-center justify-between px-6 shrink-0">
    <div class="flex items-center gap-4">
        <!-- Mobile menu toggle -->
        <button @click="mobileSidebar = !mobileSidebar" class="lg:hidden p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 active:scale-95">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <!-- Page Title with breadcrumb -->
        <div>
            <h1 class="text-lg font-bold text-gray-900"><?= esc($title ?? 'Dashboard') ?></h1>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- Date/Time -->
        <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-100">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="text-xs font-medium text-gray-500"><?= date('M d, Y') ?></span>
        </div>

        <!-- Role Badge -->
        <span class="badge badge-primary capitalize hidden sm:inline-flex"><?= esc(session()->get('user_role') ?? 'guest') ?></span>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 px-2 py-1.5 rounded-xl hover:bg-gray-100 transition-all duration-200 active:scale-[0.98]">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-md shadow-primary-500/20" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                    <?= strtoupper(substr(session()->get('username') ?? 'U', 0, 1)) ?>
                </div>
                <span class="text-sm font-semibold text-gray-700 hidden sm:block"><?= esc(session()->get('username') ?? 'User') ?></span>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-1 z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                    <p class="text-sm font-bold text-gray-800"><?= esc(session()->get('username')) ?></p>
                    <p class="text-xs text-gray-500 truncate"><?= esc(session()->get('email')) ?></p>
                    <span class="inline-flex items-center mt-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide bg-primary-50 text-primary-700"><?= esc(session()->get('user_role')) ?></span>
                </div>
                <a href="/logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </a>
            </div>
        </div>
    </div>
</header>
