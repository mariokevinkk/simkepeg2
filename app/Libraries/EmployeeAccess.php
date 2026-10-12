<?php

namespace App\Libraries;

final class EmployeeAccess
{
    public static function user(): array
    {
        return session()->get('auth_user') ?? [];
    }

    public static function can(string $permission, ?array $employee = null): bool
    {
        $user = self::user();
        if (($user['role'] ?? '') === 'superadmin') {
            return true;
        }

        if (($user['role'] ?? '') === 'admin_unit') {
            if (empty($user['permissions'][$permission])) {
                return false;
            }
            if ($employee === null) {
                return true;
            }
            return in_array((int) $employee['unit_id'], array_map('intval', $user['unit_ids'] ?? []), true);
        }

        if (($user['role'] ?? '') !== 'pegawai' || $employee === null) {
            return false;
        }
        if ((int) ($employee['user_id'] ?? 0) !== (int) ($user['id'] ?? 0)) {
            return false;
        }

        return in_array($permission, ['employees.view', 'profile.update', 'documents.manage'], true);
    }

    public static function canAccessEmployee(array $employee): bool
    {
        $user = self::user();
        if (($user['role'] ?? '') === 'superadmin') {
            return true;
        }
        if (($user['role'] ?? '') === 'admin_unit') {
            return in_array((int) $employee['unit_id'], array_map('intval', $user['unit_ids'] ?? []), true);
        }

        return (int) ($employee['user_id'] ?? 0) === (int) ($user['id'] ?? 0);
    }
}
