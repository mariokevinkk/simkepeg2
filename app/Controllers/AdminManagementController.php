<?php

namespace App\Controllers;

use App\Libraries\EmployeeAccess;
use App\Models\UnitModel;
use App\Models\EmployeeModel;
use App\Models\UserModel;

class AdminManagementController extends BaseController
{
    private const PERMISSIONS = [
        'employees.view' => 'Melihat data pegawai',
        'employees.create' => 'Menambah pegawai',
        'employees.update' => 'Mengubah data pegawai',
        'documents.manage' => 'Mengunggah dan memverifikasi dokumen',
        'history.manage' => 'Mengelola riwayat pegawai',
        'reports.view' => 'Melihat laporan unit',
    ];

    private function requireSuperAdmin()
    {
        if ((EmployeeAccess::user()['role'] ?? '') !== 'superadmin') {
            return redirect()->to('/');
        }
        return null;
    }

    public function index()
    {
        if ($denied = $this->requireSuperAdmin()) {
            return $denied;
        }

        return view('manajemen_admin', [
            'active_menu' => 'manajemen-admin',
            'role' => 'superadmin',
            'adminAccounts' => (new UserModel())->adminAccounts(),
            'units' => (new UnitModel())->activeUnits(),
            'permissionOptions' => self::PERMISSIONS,
        ]);
    }

    public function create()
    {
        if ($denied = $this->requireSuperAdmin()) {
            return $denied;
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[150]',
            'username' => 'required|min_length[4]|max_length[80]|alpha_numeric_punct',
            'email' => 'required|valid_email|max_length[190]',
            'password' => 'required|min_length[10]|max_length[255]',
            'unit_ids' => 'required',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $username = trim((string) $this->request->getPost('username'));
        $email = strtolower(trim((string) $this->request->getPost('email')));
        if ($userModel->where('username', $username)->orWhere('email', $email)->first()) {
            return redirect()->back()->withInput()->with('error', 'Username atau email sudah terdaftar.');
        }

        $unitIds = $this->validatedUnitIds((array) $this->request->getPost('unit_ids'));
        if ($unitIds === []) {
            return redirect()->back()->withInput()->with('error', 'Pilih setidaknya satu unit aktif.');
        }

        $permissions = $this->validatedPermissions((array) $this->request->getPost('permissions'));
        $userId = (int) $userModel->insert([
            'name' => trim((string) $this->request->getPost('name')),
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'admin_unit',
            'status' => 'active',
            'permissions' => json_encode($permissions, JSON_THROW_ON_ERROR),
        ], true);
        if (! $userId) {
            return redirect()->back()->withInput()->with('error', 'Akun admin unit gagal dibuat.');
        }

        $userModel->replaceUnits($userId, $unitIds);
        return redirect()->to('/manajemen-admin')->with('success', 'Admin unit berhasil dibuat.');
    }

    public function update(int $userId)
    {
        if ($denied = $this->requireSuperAdmin()) {
            return $denied;
        }

        $userModel = new UserModel();
        $admin = $userModel->find($userId);
        if ($admin === null || $admin['role'] !== 'admin_unit') {
            return redirect()->to('/manajemen-admin')->with('error', 'Akun admin unit tidak ditemukan.');
        }

        $unitIds = $this->validatedUnitIds((array) $this->request->getPost('unit_ids'));
        if ($unitIds === []) {
            return redirect()->back()->with('error', 'Admin unit harus memiliki setidaknya satu unit akses.');
        }
        $permissions = $this->validatedPermissions((array) $this->request->getPost('permissions'));
        $status = $this->request->getPost('status') === 'disabled' ? 'disabled' : 'active';

        $userModel->update($userId, ['permissions' => json_encode($permissions, JSON_THROW_ON_ERROR), 'status' => $status]);
        $userModel->replaceUnits($userId, $unitIds);

        return redirect()->to('/manajemen-admin')->with('success', 'Hak akses admin unit berhasil diperbarui.');
    }

    public function accounts()
    {
        if ($denied = $this->requireSuperAdmin()) {
            return $denied;
        }

        return view('manajemen_akun', [
            'active_menu' => 'manajemen-akun',
            'role' => 'superadmin',
            'accounts' => (new UserModel())->accountsWithUnits(),
        ]);
    }

    public function updateStatus(int $userId)
    {
        if ($denied = $this->requireSuperAdmin()) {
            return $denied;
        }

        $userModel = new UserModel();
        $account = $userModel->find($userId);
        if ($account === null || $account['role'] === 'superadmin') {
            return redirect()->to('/manajemen-akun')->with('error', 'Akun tidak dapat diubah.');
        }

        $status = $this->request->getPost('status');
        if (! in_array($status, ['active', 'disabled'], true)) {
            return redirect()->to('/manajemen-akun')->with('error', 'Status akun tidak valid.');
        }

        $userModel->update($userId, ['status' => $status]);
        if ((int) ($account['employee_id'] ?? 0) > 0 && $account['role'] === 'pegawai') {
            (new EmployeeModel())->update((int) $account['employee_id'], [
                'employee_status' => $status === 'active' ? 'Aktif' : 'Nonaktif',
            ]);
        }
        return redirect()->to('/manajemen-akun')->with('success', 'Status akun berhasil diperbarui.');
    }

    public function createUnit()
    {
        if ($denied = $this->requireSuperAdmin()) {
            return $denied;
        }

        $name = trim((string) $this->request->getPost('name'));
        $code = strtoupper(trim((string) $this->request->getPost('code')));
        $type = trim((string) $this->request->getPost('type')) ?: 'unit';
        if (mb_strlen($name) < 2 || mb_strlen($name) > 120 || mb_strlen($code) > 80 || mb_strlen($type) > 30) {
            return redirect()->back()->withInput()->with('error', 'Nama unit, kode, atau jenis unit tidak valid.');
        }
        $unitModel = new UnitModel();
        if ($unitModel->where('name', $name)->first() !== null) {
            return redirect()->back()->withInput()->with('error', 'Nama unit sudah terdaftar.');
        }
        $unitModel->insert(['name' => $name, 'code' => $code, 'type' => $type, 'is_active' => 1]);
        return redirect()->to('/manajemen-bangsal')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function updateUnitStatus(int $unitId)
    {
        if ($denied = $this->requireSuperAdmin()) {
            return $denied;
        }

        $unitModel = new UnitModel();
        if ($unitModel->find($unitId) === null) {
            return redirect()->to('/manajemen-bangsal')->with('error', 'Unit tidak ditemukan.');
        }
        $isActive = $this->request->getPost('is_active');
        if (! in_array($isActive, ['0', '1'], true)) {
            return redirect()->back()->with('error', 'Status unit tidak valid.');
        }
        $unitModel->update($unitId, ['is_active' => (int) $isActive]);
        return redirect()->to('/manajemen-bangsal')->with('success', 'Status unit berhasil diperbarui.');
    }

    private function validatedUnitIds(array $input): array
    {
        $requested = array_values(array_unique(array_filter(array_map('intval', $input), static fn (int $id): bool => $id > 0)));
        if ($requested === []) {
            return [];
        }
        $available = (new UnitModel())->where('is_active', 1)->whereIn('id', $requested)->findColumn('id');
        return array_map('intval', $available ?? []);
    }

    private function validatedPermissions(array $input): array
    {
        $permissions = [];
        foreach (array_keys(self::PERMISSIONS) as $permission) {
            $permissions[$permission] = in_array($permission, $input, true);
        }
        return $permissions;
    }
}
