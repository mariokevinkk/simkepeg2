<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeEducationModel extends Model
{
    protected $table = 'employee_educations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'employee_id',
        'tingkat',
        'nama',
        'instansi',
        'fakultas',
        'tempat',
        'nomor_ijazah',
        'tanggal_ijazah',
        'nilai',
        'status',
        'pejabat',
        'is_current',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function forEmployee(int $employeeId): array
    {
        return $this->where('employee_id', $employeeId)
            ->orderBy('is_current', 'DESC')
            ->orderBy('tanggal_ijazah', 'DESC')
            ->findAll();
    }

    public function setLatestCurrent(int $employeeId): void
    {
        $latest = \App\Libraries\EmployeeProfileData::latestEducationRow(
            $this->where('employee_id', $employeeId)->orderBy('id')->findAll(),
        );

        $this->where('employee_id', $employeeId)->set('is_current', 0)->update();
        if ($latest !== null) {
            $this->update((int) $latest['id'], ['is_current' => 1]);
        }
    }
}
