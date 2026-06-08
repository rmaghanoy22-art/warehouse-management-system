<?php

/**
 * Permission Helper
 * Utility functions for role-based access control checks.
 */

if (!function_exists('hasRole')) {
    /**
     * Check if current user has a specific role.
     */
    function hasRole(string $role): bool
    {
        return session()->get('user_role') === $role;
    }
}

if (!function_exists('hasAnyRole')) {
    /**
     * Check if current user has any of the specified roles.
     */
    function hasAnyRole(array $roles): bool
    {
        return in_array(session()->get('user_role'), $roles);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        return in_array(session()->get('user_role'), ['admin', 'warehouse']);
    }
}

if (!function_exists('isStaff')) {
    function isStaff(): bool
    {
        return session()->get('user_role') === 'staff';
    }
}

if (!function_exists('currentUserId')) {
    function currentUserId(): ?int
    {
        return session()->get('user_id');
    }
}

if (!function_exists('currentUserName')) {
    function currentUserName(): ?string
    {
        return session()->get('username');
    }
}

if (!function_exists('currentUserRole')) {
    function currentUserRole(): ?string
    {
        return session()->get('user_role');
    }
}
