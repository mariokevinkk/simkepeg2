<?php

namespace App\Controllers;

class Home extends BaseController
{
    private function getRole() {
        return $this->request->getGet('role') ?? 'superadmin';
    }

    public function index(): string
    {
        $role = $this->getRole();
        if ($role === 'pegawai') {
            return $this->profilPegawai();
        }
        $data = [
            'active_menu' => 'dashboard', 
            'role' => $role,
            'filter_bangsal' => $this->request->getGet('filter_bangsal')
        ];
        return view('dashboard', $data);
    }

    public function dataPegawai(): string
    {
        $data = [
            'active_menu' => 'data-pegawai', 
            'role' => $this->getRole(),
            'filter_bangsal' => $this->request->getGet('filter_bangsal'),
            'filter_status' => $this->request->getGet('filter_status'),
            'filter_jabatan' => $this->request->getGet('filter_jabatan'),
            'filter_jenjang' => $this->request->getGet('filter_jenjang'),
            'filter_jpl' => $this->request->getGet('filter_jpl')
        ];
        return view('data_pegawai', $data);
    }

    public function editPegawai(): string
    {
        $data = ['active_menu' => 'data-pegawai', 'role' => $this->getRole()];
        return view('edit_pegawai', $data);
    }

    public function viewPegawai(): string
    {
        $data = ['active_menu' => 'data-pegawai', 'role' => $this->getRole()];
        return view('view_pegawai', $data);
    }

    public function laporan(): string
    {
        $data = ['active_menu' => 'laporan', 'role' => $this->getRole()];
        return view('laporan', $data);
    }

    public function pengaturan(): string
    {
        $data = ['active_menu' => 'pengaturan', 'role' => $this->getRole()];
        return view('pengaturan', $data);
    }

    public function pensiun(): string
    {
        $data = ['active_menu' => 'pensiun', 'role' => $this->getRole()];
        return view('pensiun', $data);
    }

    public function manajemenAkun(): string
    {
        $data = ['active_menu' => 'manajemen-akun', 'role' => $this->getRole()];
        return view('manajemen_akun', $data);
    }

    public function manajemenAdmin(): string
    {
        $data = ['active_menu' => 'manajemen-admin', 'role' => $this->getRole()];
        return view('manajemen_admin', $data);
    }

    public function manajemenBangsal(): string
    {
        $data = ['active_menu' => 'manajemen-bangsal', 'role' => $this->getRole()];
        return view('manajemen_bangsal', $data);
    }

    public function detailBangsal(): string
    {
        $data = [
            'active_menu' => 'manajemen-bangsal',
            'role' => $this->getRole(),
            'bangsal' => $this->request->getGet('bangsal'),
            'total' => $this->request->getGet('total')
        ];
        return view('detail_bangsal', $data);
    }

    public function profilPegawai(): string
    {
        $data = ['active_menu' => 'profil-pegawai', 'role' => $this->getRole()];
        return view('pegawai/profil_pegawai', $data);
    }

    public function riwayatKepangkatan(): string
    {
        $data = ['active_menu' => 'riwayat-kepangkatan', 'role' => $this->getRole()];
        return view('pegawai/riwayat_kepangkatan', $data);
    }

    public function riwayatJabatan(): string
    {
        $data = ['active_menu' => 'riwayat-jabatan', 'role' => $this->getRole()];
        return view('pegawai/riwayat_jabatan', $data);
    }

    public function riwayatPendidikan(): string
    {
        $data = ['active_menu' => 'riwayat-pendidikan', 'role' => $this->getRole()];
        return view('pegawai/riwayat_pendidikan', $data);
    }

    public function dataKeluarga(): string
    {
        $data = ['active_menu' => 'data-keluarga', 'role' => $this->getRole()];
        return view('pegawai/data_keluarga', $data);
    }

    public function dataLainnya(): string
    {
        $data = ['active_menu' => 'data-lainnya', 'role' => $this->getRole()];
        return view('pegawai/data_lainnya', $data);
    }

    public function aktivitas(): string
    {
        $data = ['active_menu' => 'aktivitas', 'role' => $this->getRole()];
        return view('aktivitas', $data);
    }
}
