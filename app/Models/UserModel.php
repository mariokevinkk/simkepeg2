<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'employee_id',
        'name',
        'username',
        'email',
        'nip',
        'password_hash',
        'role',
        'status',
        'permissions',
        'last_login',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function findForLogin(string $identity): ?array
    {
        return $this->groupStart()
            ->where('email', $identity)
            ->orWhere('username', $identity)
            ->orWhere('nip', $identity)
            ->groupEnd()
            ->first();
    }

    public function allowedUnitIds(int $userId): array
    {
        $rows = $this->db->table('user_units')
            ->select('unit_id')
            ->join('units', 'units.id = user_units.unit_id')
            ->where('units.is_active', 1)
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();

        return array_map('intval', array_column($rows, 'unit_id'));
    }

    public function unitsForUser(int $userId): array
    {
        return $this->db->table('user_units')
            ->select('units.id, units.name, units.type')
            ->join('units', 'units.id = user_units.unit_id')
            ->where('user_units.user_id', $userId)
            ->orderBy('units.name')
            ->get()
            ->getResultArray();
    }

    public function replaceUnits(int $userId, array $unitIds): void
    {
        $unitIds = array_values(array_unique(array_map('intval', $unitIds)));
        $this->db->transStart();
        $this->db->table('user_units')->where('user_id', $userId)->delete();

        foreach ($unitIds as $unitId) {
            $this->db->table('user_units')->insert([
                'user_id' => $userId,
                'unit_id' => $unitId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $this->db->transComplete();
    }

    public function adminAccounts(): array
    {
        $accounts = $this->where('role', 'admin_unit')->orderBy('name')->findAll();
        foreach ($accounts as &$account) {
            $account['units'] = $this->unitsForUser((int) $account['id']);
            $account['permissions'] = json_decode($account['permissions'] ?? '', true) ?: [];
        }
        unset($account);

        return $accounts;
    }

    public function accountsWithUnits(): array
    {
        $accounts = $this->select('users.*, employees.unit_id, units.name AS unit_name')
            ->join('employees', 'employees.id = users.employee_id', 'left')
            ->join('units', 'units.id = employees.unit_id', 'left')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();

        foreach ($accounts as &$account) {
            $account['assigned_units'] = implode(', ', array_column($this->unitsForUser((int) $account['id']), 'name'));
        }
        unset($account);

        return $accounts;
    }
}
