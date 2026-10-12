<?php

namespace App\Controllers;

use App\Models\EmployeeDocumentModel;
use App\Models\EmployeeModel;
use App\Models\UnitModel;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function loginForm()
    {
        if (session()->get('auth_user_id')) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    public function login()
    {
        $rules = [
            'identity' => 'required|max_length[190]',
            'password' => 'required|max_length[255]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $identity = trim((string) $this->request->getPost('identity'));
        $userModel = new UserModel();
        $user = $userModel->findForLogin($identity);
        if ($user === null || ! password_verify((string) $this->request->getPost('password'), $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Username/email/NIP atau password tidak cocok.');
        }
        if ($user['status'] !== 'active') {
            $message = $user['status'] === 'pending'
                ? 'Pendaftaran berhasil. Akun menunggu persetujuan Super Admin.'
                : 'Akun sedang dinonaktifkan. Hubungi administrator.';
            return redirect()->back()->withInput()->with('error', $message);
        }

        $userModel->update((int) $user['id'], ['last_login' => date('Y-m-d H:i:s')]);
        session()->regenerate(true);
        session()->set('auth_user_id', (int) $user['id']);

        return redirect()->to('/');
    }

    public function changePasswordForm(): string
    {
        $user = session()->get('auth_user') ?? [];
        $role = ($user['role'] ?? '') === 'admin_unit'
            ? 'admin'
            : ($user['role'] ?? 'pegawai');

        return view('auth/change_password', [
            'active_menu' => 'ubah-password',
            'role' => $role,
        ]);
    }

    public function changePassword()
    {
        $rules = [
            'current_password' => 'required|max_length[255]',
            'new_password' => 'required|min_length[10]|max_length[255]',
            'new_password_confirm' => 'required|matches[new_password]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = (int) session()->get('auth_user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if ($user === null) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Sesi akun tidak ditemukan. Silakan login kembali.');
        }

        $currentPassword = (string) $this->request->getPost('current_password');
        $newPassword = (string) $this->request->getPost('new_password');
        if (! password_verify($currentPassword, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Password saat ini tidak cocok.');
        }
        if (hash_equals($currentPassword, $newPassword)) {
            return redirect()->back()->with('error', 'Password baru harus berbeda dari password saat ini.');
        }

        $updated = $userModel->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);
        if (! $updated) {
            return redirect()->back()->with('error', 'Password belum berhasil disimpan. Silakan coba lagi.');
        }

        session()->regenerate(true);

        return redirect()->to('/ubah-password')->with('success', 'Password berhasil diubah.');
    }

    public function registerForm(): string
    {
        return view('auth/register', ['units' => (new UnitModel())->activeUnits()]);
    }

    public function register()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[150]',
            'username' => 'required|min_length[4]|max_length[80]|alpha_numeric_punct',
            'email' => 'required|valid_email|max_length[190]',
            'nip' => 'required|min_length[4]|max_length[40]',
            'unit_id' => 'required|is_natural_no_zero',
            'password' => 'required|min_length[10]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $nip = trim((string) $this->request->getPost('nip'));
        $unitId = (int) $this->request->getPost('unit_id');
        $unit = (new UnitModel())->where('id', $unitId)->where('is_active', 1)->first();
        if ($unit === null) {
            return redirect()->back()->withInput()->with('error', 'Unit yang dipilih tidak tersedia.');
        }

        $userModel = new UserModel();
        if ($userModel->where('username', $username)->orWhere('email', $email)->orWhere('nip', $nip)->first()) {
            return redirect()->back()->withInput()->with('error', 'Username, email, atau NIP sudah terdaftar.');
        }

        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->where('nip', $nip)->first();
        if ($employee !== null) {
            $sameName = mb_strtolower(trim($employee['nama'])) === mb_strtolower(trim((string) $this->request->getPost('name')));
            $sameKnownEmail = ($employee['email'] ?? '') === '' || strcasecmp($employee['email'], $email) === 0;
            if ((int) $employee['unit_id'] !== $unitId || (int) ($employee['user_id'] ?? 0) > 0 || ! $sameName || ! $sameKnownEmail) {
                return redirect()->back()->withInput()->with('error', 'NIP sudah dipakai atau data pendaftaran tidak cocok dengan data pegawai. Hubungi administrator unit.');
            }
        }

        $db = db_connect();
        $db->transBegin();
        try {
            $employeeId = $employee === null
                ? $employeeModel->registerProfile([
                    'unit_id' => $unitId,
                    'nip' => $nip,
                    'nama' => trim((string) $this->request->getPost('name')),
                    'email' => $email,
                    'employee_status' => 'Menunggu Verifikasi',
                    'kedudukan' => 'Aktif',
                ])
                : (int) $employee['id'];
            $userId = (int) $userModel->insert([
                'employee_id' => $employeeId,
                'name' => trim((string) $this->request->getPost('name')),
                'username' => $username,
                'email' => $email,
                'nip' => $nip,
                'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'pegawai',
                'status' => 'pending',
                'permissions' => json_encode([], JSON_THROW_ON_ERROR),
            ], true);
            if (! $userId) {
                throw new \RuntimeException('Akun gagal dibuat.');
            }
            $employeeModel->update($employeeId, [
                'user_id' => $userId,
                'email' => $email,
                'employee_status' => 'Menunggu Verifikasi',
            ]);
            (new EmployeeDocumentModel())->seedCatalog($employeeId);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Pendaftaran gagal disimpan.');
            }
            $db->transCommit();
        } catch (\Throwable $exception) {
            $db->transRollback();
            log_message('error', 'Employee registration failed: {message}', ['message' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Pendaftaran gagal. Pastikan username, email, dan NIP belum pernah dipakai.');
        }

        return redirect()->to('/login')->with('success', 'Pendaftaran tersimpan. Akun akan aktif setelah disetujui Super Admin.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda sudah keluar.');
    }
}
