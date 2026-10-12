<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userId = (int) $session->get('auth_user_id');
        if ($userId < 1) {
            return redirect()->to('/login');
        }

        $user = (new UserModel())->find($userId);
        if ($user === null || $user['status'] !== 'active') {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Akun tidak aktif. Hubungi administrator.');
        }

        if (! in_array($user['role'], ['superadmin', 'admin_unit', 'pegawai'], true)) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Role akun tidak valid.');
        }

        unset($user['password_hash']);
        $user['permissions'] = json_decode($user['permissions'] ?? '', true) ?: [];
        $user['unit_ids'] = (new UserModel())->allowedUnitIds($userId);
        $session->set('auth_user', $user);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
