<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table = 'units';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'code', 'type', 'is_active'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function activeUnits(): array
    {
        return $this->where('is_active', 1)->orderBy('name')->findAll();
    }

    public function namesByIds(array $ids): array
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        if ($ids === []) {
            return [];
        }

        return $this->select('id, name')->whereIn('id', $ids)->orderBy('name')->findAll();
    }

    public function withEmployeeCounts(): array
    {
        return $this->select('units.*, COUNT(employees.id) AS employee_count')
            ->join('employees', 'employees.unit_id = units.id', 'left')
            ->groupBy('units.id')
            ->orderBy('units.name')
            ->findAll();
    }
}
