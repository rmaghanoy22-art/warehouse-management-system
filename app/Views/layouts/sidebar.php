<?php helper('permission'); ?>
<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-40 flex flex-col sidebar-gradient text-gray-300 transition-all duration-300 ease-in-out"
       :class="[
           sidebarOpen ? 'w-64' : 'w-20',
           mobileSidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
       ]">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-5 h-16 border-b border-white/5 shrink-0">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 shrink-0" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div x-show="sidebarOpen" x-transition class="min-w-0">
            <span class="font-bold text-white text-lg block leading-tight">WMS</span>
            <span class="text-[10px] text-gray-500 font-medium tracking-wider uppercase">Warehouse System</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <?php if (isAdmin()): ?>
        <!-- Admin Navigation -->
        <a href="/dashboard" class="sidebar-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Dashboard</span>
        </a>

        <div class="pt-5 pb-2" x-show="sidebarOpen"><span class="px-4 text-[10px] font-bold uppercase tracking-widest text-gray-600">Inventory</span></div>
        <div class="pt-3" x-show="!sidebarOpen"></div>

        <a href="/products" class="sidebar-link <?= str_starts_with(uri_string(), 'products') ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Products</span>
        </a>

        <a href="/categories" class="sidebar-link <?= str_starts_with(uri_string(), 'categories') ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Categories</span>
        </a>

        <a href="/departments" class="sidebar-link <?= str_starts_with(uri_string(), 'departments') ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Departments</span>
        </a>

        <div class="pt-5 pb-2" x-show="sidebarOpen"><span class="px-4 text-[10px] font-bold uppercase tracking-widest text-gray-600">Operations</span></div>
        <div class="pt-3" x-show="!sidebarOpen"></div>

        <a href="/supply-requests" class="sidebar-link <?= str_starts_with(uri_string(), 'supply-requests') ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Supply Requests</span>
        </a>

        <a href="/stock/logs" class="sidebar-link <?= str_starts_with(uri_string(), 'stock/logs') || uri_string() === 'stock/adjust' ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Stock Logs</span>
        </a>

        <a href="/stock/expiration" class="sidebar-link <?= str_starts_with(uri_string(), 'stock/expiration') ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Expiration</span>
        </a>

        <div class="pt-5 pb-2" x-show="sidebarOpen"><span class="px-4 text-[10px] font-bold uppercase tracking-widest text-gray-600">Administration</span></div>
        <div class="pt-3" x-show="!sidebarOpen"></div>

        <a href="/users" class="sidebar-link <?= str_starts_with(uri_string(), 'users') ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Users</span>
        </a>

        <a href="/audit-logs" class="sidebar-link <?= str_starts_with(uri_string(), 'audit') ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Audit Logs</span>
        </a>

        <?php else: ?>
        <!-- Staff Navigation -->
        <a href="/staff/dashboard" class="sidebar-link <?= uri_string() === 'staff/dashboard' ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Dashboard</span>
        </a>

        <a href="/staff/requests" class="sidebar-link <?= str_starts_with(uri_string(), 'staff/requests') ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">My Requests</span>
        </a>

        <a href="/staff/inventory" class="sidebar-link <?= str_starts_with(uri_string(), 'staff/inventory') ? 'active' : '' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Inventory</span>
        </a>
        <?php endif; ?>
    </nav>

    <!-- User Profile & Toggle -->
    <div class="border-t border-white/5 p-3 space-y-2">
        <!-- User info -->
        <div class="flex items-center gap-3 px-3 py-2" x-show="sidebarOpen">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <?= strtoupper(substr(session()->get('username') ?? 'U', 0, 1)) ?>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-gray-200 truncate"><?= esc(session()->get('username') ?? 'User') ?></p>
                <p class="text-[10px] text-gray-500 capitalize"><?= esc(session()->get('user_role') ?? 'guest') ?></p>
            </div>
        </div>
        <!-- Toggle -->
        <button @click="sidebarOpen = !sidebarOpen" class="sidebar-link w-full hidden lg:flex">
            <svg class="w-5 h-5 shrink-0 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Collapse</span>
        </button>
    </div>
</aside>
