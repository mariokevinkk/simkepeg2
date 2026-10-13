<?php

namespace App\Controllers;

use App\Libraries\EmployeeAccess;
use App\Models\JobTitleModel;

class JobTitleController extends BaseController
{
    private function requireAdmin()
    {
        if (! in_array(EmployeeAccess::user()['role'] ?? '', ['superadmin', 'admin_unit'], true)) {
            return redirect()->to('/');
        }

        return null;
    }

    public function index()
    {
        if ($denied = $this->requireAdmin()) {
            return $denied;
        }

        $user = EmployeeAccess::user();
        $model = new JobTitleModel();
        $groups = [];
        foreach ($model->allOptions(true) as $job) {
            $groups[$job['group_name']][] = $job;
        }

        return view('manajemen_jabatan', [
            'active_menu' => 'manajemen-jabatan',
            'role' => $user['role'] === 'superadmin' ? 'superadmin' : 'admin',
            'groups' => $groups,
            'can_manage_status' => $user['role'] === 'superadmin',
        ]);
    }

    public function create()
    {
        if ($denied = $this->requireAdmin()) {
            return $denied;
        }

        $model = new JobTitleModel();
        $group = trim(preg_replace('/\s+/u', ' ', (string) $this->request->getPost('group_name')) ?? '');
        $title = trim(preg_replace('/\s+/u', ' ', (string) $this->request->getPost('title')) ?? '');
        if ($group === '' || $title === '' || mb_strlen($group) > 80 || mb_strlen($title) > 180) {
            return redirect()->back()->withInput()->with('error', 'Isi jenis ketenagaan dan nama jabatan yang valid.');
        }

        $group = $model->canonicalGroup($group) ?? $group;
        $existing = $model->allOptions(true);
        foreach ($existing as $job) {
            if (mb_strtolower($job['title']) === mb_strtolower($title)) {
                return redirect()->back()->withInput()->with('error', 'Nama jabatan sudah tersedia dalam katalog.');
            }
        }

        $sortOrder = $existing === [] ? 1 : max(array_column($existing, 'sort_order')) + 1;
        $model->insert([
            'group_name' => $group,
            'title' => $title,
            'is_active' => 1,
            'sort_order' => $sortOrder,
        ]);

        return redirect()->to('/manajemen-jabatan#daftar-jabatan')->with('success', 'Nama jabatan berhasil ditambahkan.');
    }

    public function updateStatus(int $jobId)
    {
        if ($denied = $this->requireAdmin()) {
            return $denied;
        }
        if ((EmployeeAccess::user()['role'] ?? '') !== 'superadmin') {
            return redirect()->to('/');
        }

        $model = new JobTitleModel();
        $job = $model->find($jobId);
        $isActive = (string) $this->request->getPost('is_active');
        if ($job === null || ! in_array($isActive, ['0', '1'], true)) {
            return redirect()->to('/manajemen-jabatan')->with('error', 'Jabatan atau status tidak valid.');
        }

        $model->update($jobId, ['is_active' => (int) $isActive]);

        return redirect()->to('/manajemen-jabatan#daftar-jabatan')->with('success', 'Status jabatan berhasil diperbarui.');
    }
}
