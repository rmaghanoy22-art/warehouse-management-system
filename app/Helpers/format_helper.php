<?php

/**
 * Format Helper
 * Utility functions for formatting dates, statuses, and display values.
 */

if (!function_exists('formatDate')) {
    function formatDate(?string $date, string $format = 'M d, Y'): string
    {
        if (!$date) return '—';
        return date($format, strtotime($date));
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime(?string $date): string
    {
        if (!$date) return '—';
        return date('M d, Y h:i A', strtotime($date));
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo(?string $datetime): string
    {
        if (!$datetime) return '—';
        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . 'm ago';
        if ($diff < 86400) return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        return date('M d, Y', $time);
    }
}

if (!function_exists('statusBadgeClass')) {
    function statusBadgeClass(string $status): string
    {
        return match ($status) {
            'active', 'approved', 'released' => 'badge-success',
            'pending'                         => 'badge-warning',
            'inactive', 'rejected'            => 'badge-danger',
            'discontinued'                    => 'badge-gray',
            default                           => 'badge-info',
        };
    }
}

if (!function_exists('expirationStatus')) {
    function expirationStatus(?string $expirationDate): array
    {
        if (!$expirationDate) {
            return ['label' => 'N/A', 'class' => 'badge-gray'];
        }

        $now = time();
        $expiry = strtotime($expirationDate);
        $daysUntil = (int) floor(($expiry - $now) / 86400);

        if ($daysUntil < 0) {
            return ['label' => 'Expired', 'class' => 'badge-danger'];
        }
        if ($daysUntil <= 30) {
            return ['label' => "Expires in {$daysUntil}d", 'class' => 'badge-warning'];
        }
        return ['label' => 'OK', 'class' => 'badge-success'];
    }
}
