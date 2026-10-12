<?php

namespace App\Controllers;

use App\Libraries\EmployeeAccess;
use App\Libraries\EmployeeProfileData;
use App\Models\EmployeeModel;
use App\Models\EmployeeDocumentModel;
use App\Models\UnitModel;

class Home extends BaseController
{
    private function user(): array
    {
        return EmployeeAccess::user();
    }

    private function role(): string
    {
        return ($this->user()['role'] ?? '') === 'admin_unit' ? 'admin' : ($this->user()['role'] ?? 'pegawai');
    }

    private function employeeModel(): EmployeeModel
    {
        return new EmployeeModel();
    }

    private function employmentCategory(string $value): string
    {
        $value = mb_strtolower(trim($value));
        if (str_contains($value, 'cpns')) {
            return 'cpns';
        }
        if (str_contains($value, 'pppk')) {
            return str_contains($value, 'paruh') ? 'pppk_part' : 'pppk_full';
        }
        if (str_contains($value, 'pns')) {
            return 'pns';
        }
        if (str_contains($value, 'blud')) {
            return str_contains($value, 'kontrak') ? 'blud_contract' : 'blud_permanent';
        }
        if (str_contains($value, 'mitra')) {
            return str_contains($value, 'paruh') ? 'mitra_part' : 'mitra_full';
        }

        return 'other';
    }

    private function dashboardJobLabel(string $title): string
    {
        $normalized = mb_strtolower(trim($title));
        if (str_contains($normalized, 'dokter')) {
            return 'Dokter';
        }
        if (str_contains($normalized, 'perawat')) {
            return 'Perawat';
        }
        if (str_contains($normalized, 'bidan')) {
            return 'Bidan';
        }
        if (str_contains($normalized, 'asisten apoteker')) {
            return 'Asisten Apoteker';
        }
        if (str_contains($normalized, 'apoteker')) {
            return 'Apoteker';
        }

        return trim($title) !== '' ? trim($title) : 'Belum diisi';
    }

    private function dashboardSeniorityCategory(string $jobTitle, string $level): string
    {
        $level = mb_strtolower(trim($level));
        $jobTitle = mb_strtolower(trim($jobTitle));

        if (str_contains($jobTitle, 'spesialis')) {
            return 'expert';
        }
        if (str_contains($jobTitle, 'dokter umum')) {
            return 'skilled';
        }

        if ($level === '' || $level === '-') {
            return 'specific';
        }

        return str_contains($level, 'ahli') || str_contains($level, 'spesialis')
            ? 'expert'
            : 'skilled';
    }

    private function requestedEmployeeId(): int
    {
        $user = $this->user();
        if (($user['role'] ?? '') === 'pegawai') {
            return (int) ($user['employee_id'] ?? 0);
        }

        $requested = (int) $this->request->getGet('id');
        if ($requested > 0) {
            return $requested;
        }

        $visible = $this->employeeModel()->visibleTo($user);
        return (int) ($visible[0]['id'] ?? 0);
    }

    private function employeePageData(string $activeMenu, bool $editable = false, ?string $page = null): array
    {
        // Admin pages pick the part of the form with ?bagian=, pegawai pages are fixed by the menu.
        $pages = EmployeeProfileData::profilePages();
        $page ??= (string) $this->request->getGet('bagian');
        if (! isset($pages[$page])) {
            $page = 'profil-pegawai';
        }
        $user = $this->user();
        $employeeId = $this->requestedEmployeeId();
        $employee = $employeeId > 0 ? $this->employeeModel()->findVisibleById($employeeId, $user) : null;
        if ($employee === null) {
            return [
                'active_menu' => $activeMenu,
                'role' => $this->role(),
                'employee_profile' => null,
                'profile_error' => 'Data pegawai tidak ditemukan atau akun Anda tidak memiliki akses ke data ini.',
            ];
        }

        $canView = EmployeeAccess::can('employees.view', $employee);
        $canEdit = EmployeeAccess::can(($user['role'] ?? '') === 'pegawai' ? 'profile.update' : 'employees.update', $employee);
        if (! $canView && ! $canEdit) {
            return [
                'active_menu' => $activeMenu,
                'role' => $this->role(),
                'employee_profile' => null,
                'profile_error' => 'Akun Anda tidak memiliki hak untuk melihat data pegawai ini.',
            ];
        }
        if ($editable && ! $canEdit) {
            return [
                'active_menu' => $activeMenu,
                'role' => $this->role(),
                'employee_profile' => null,
                'profile_error' => 'Akun Anda tidak memiliki hak untuk mengubah data pegawai ini.',
            ];
        }

        $canManageDocuments = EmployeeAccess::can('documents.manage', $employee);
        return [
            'active_menu' => $activeMenu,
            'role' => $this->role(),
            'employee_profile' => $this->employeeModel()->bundle($employeeId, $editable && $canManageDocuments),
            'employee_id' => $employeeId,
            'can_edit' => $canEdit,
            'can_manage_documents' => $canManageDocuments,
            'editable_page' => $editable,
            'profile_page' => $page,
        ];
    }

    public function index()
    {
        if (($this->user()['role'] ?? '') === 'pegawai') {
            return redirect()->to('/profil-pegawai');
        }

        $user = $this->user();
        $canViewEmployees = EmployeeAccess::can('employees.view');
        $employees = $canViewEmployees ? $this->employeeModel()->visibleTo($user) : [];
        $units = new UnitModel();
        $unitOptions = ($user['role'] ?? '') === 'superadmin'
            ? $units->activeUnits()
            : $units->namesByIds($user['unit_ids'] ?? []);
        $selectedUnit = trim((string) $this->request->getGet('filter_bangsal'));
        $allowedNames = array_column($unitOptions, 'name');
        if ($selectedUnit !== '' && ! in_array($selectedUnit, $allowedNames, true)) {
            $selectedUnit = '';
        }
        if ($selectedUnit !== '') {
            $employees = array_values(array_filter($employees, static fn (array $employee): bool => $employee['unit_name'] === $selectedUnit));
        }

        $employmentCounts = [
            'cpns' => 0,
            'pns' => 0,
            'pppk_full' => 0,
            'pppk_part' => 0,
            'blud_permanent' => 0,
            'blud_contract' => 0,
            'mitra_full' => 0,
            'mitra_part' => 0,
            'other' => 0,
        ];
        foreach ($employees as $employee) {
            $category = $this->employmentCategory((string) ($employee['jenis_kepegawaian'] ?? ''));
            $employmentCounts[$category]++;
        }

        $jobGroups = [];
        foreach ($employees as $employee) {
            $sourceJob = trim((string) ($employee['jabatan_nama'] ?? ''));
            if ($sourceJob === '') {
                $sourceJob = trim((string) ($employee['jenis_ketenagaan'] ?? ''));
            }
            $jobName = $this->dashboardJobLabel($sourceJob);
            $jobKey = mb_strtolower($jobName);
            $seniority = $this->dashboardSeniorityCategory(
                $sourceJob,
                (string) ($employee['jenjang_jabatan'] ?? ''),
            );

            if (! isset($jobGroups[$jobKey])) {
                $jobGroups[$jobKey] = [
                    'name' => $jobName,
                    'total' => 0,
                    'expert' => 0,
                    'skilled' => 0,
                    'specific' => 0,
                    'subroles' => [],
                ];
            }

            $jobGroups[$jobKey]['total']++;
            $jobGroups[$jobKey][$seniority]++;

            if ($jobName === 'Dokter') {
                $sourceLower = mb_strtolower($sourceJob);
                $subrole = str_contains($sourceLower, 'spesialis')
                    ? 'Dokter Spesialis'
                    : (str_contains($sourceLower, 'umum') ? 'Dokter Umum' : 'Dokter');
                $jobGroups[$jobKey]['subroles'][$subrole] = ($jobGroups[$jobKey]['subroles'][$subrole] ?? 0) + 1;
            }
        }

        $preferredJobOrder = [
            'Dokter' => 0,
            'Perawat' => 1,
            'Bidan' => 2,
            'Apoteker' => 3,
            'Asisten Apoteker' => 4,
            'Analis SDM Aparatur' => 5,
            'Pranata Komputer' => 6,
            'Pengelola Keuangan' => 7,
            'Petugas Kebersihan' => 8,
        ];
        $jobBreakdown = array_values($jobGroups);
        foreach ($jobBreakdown as &$job) {
            $job['details'] = [];
            if ($job['name'] === 'Dokter' && $job['subroles'] !== []) {
                $subroleOrder = ['Dokter Umum' => 0, 'Dokter Spesialis' => 1, 'Dokter' => 2];
                uksort($job['subroles'], static fn (string $a, string $b): int => ($subroleOrder[$a] ?? 99) <=> ($subroleOrder[$b] ?? 99));
                foreach ($job['subroles'] as $label => $count) {
                    $job['details'][] = ['label' => $label, 'count' => $count];
                }
            } else {
                if ($job['expert'] > 0) {
                    $job['details'][] = [
                        'label' => $job['name'] === 'Perawat' ? 'Kategori Ahli (Profesi)' : 'Kategori Ahli',
                        'count' => $job['expert'],
                    ];
                }
                if ($job['skilled'] > 0) {
                    $job['details'][] = [
                        'label' => $job['name'] === 'Perawat' ? 'Kategori Terampil (Vokasi)' : 'Kategori Terampil',
                        'count' => $job['skilled'],
                    ];
                }
            }
            unset($job['subroles']);
        }
        unset($job);
        usort($jobBreakdown, static function (array $a, array $b) use ($preferredJobOrder): int {
            $aOrder = $preferredJobOrder[$a['name']] ?? PHP_INT_MAX;
            $bOrder = $preferredJobOrder[$b['name']] ?? PHP_INT_MAX;

            return $aOrder === $bOrder ? strcasecmp($a['name'], $b['name']) : $aOrder <=> $bOrder;
        });

        return view('dashboard', [
            'active_menu' => 'dashboard',
            'role' => $this->role(),
            'employee_count' => count($employees),
            'selectedUnit' => $selectedUnit,
            'unitOptions' => $unitOptions,
            'employmentCounts' => $employmentCounts,
            'jobBreakdown' => $jobBreakdown,
            'can_view_employees' => $canViewEmployees,
        ]);
    }

    public function dataPegawai()
    {
        $canView = EmployeeAccess::can('employees.view');
        $canCreate = EmployeeAccess::can('employees.create');
        if (! $canView && ! $canCreate) {
            return redirect()->to('/profil-pegawai')->with('error', 'Akun Anda tidak memiliki akses ke daftar pegawai.');
        }

        $user = $this->user();
        $filters = ['q' => trim((string) $this->request->getGet('q'))];
        foreach (array_keys(EmployeeModel::LIST_FILTER_COLUMNS) as $key) {
            $filters[$key] = trim((string) $this->request->getGet($key));
        }
        $filters['filter_jpl'] = in_array($this->request->getGet('filter_jpl'), ['kurang', 'terpenuhi'], true)
            ? (string) $this->request->getGet('filter_jpl')
            : '';
        $filters['filter_dokumen'] = in_array($this->request->getGet('filter_dokumen'), ['lengkap', 'belum'], true)
            ? (string) $this->request->getGet('filter_dokumen')
            : '';

        $filterOptions = $canView ? $this->employeeModel()->filterOptions($user) : array_fill_keys(array_keys(EmployeeModel::LIST_FILTER_COLUMNS), []);
        $employees = $canView ? $this->employeeModel()->visibleTo($user, $filters) : [];
        $checklists = (new EmployeeDocumentModel())->checklistStatus($employees);
        $list = [];
        foreach ($employees as $employee) {
            $checklist = $checklists[(int) $employee['id']] ?? ['entries' => [], 'summary' => ['total' => 0, 'uploaded' => 0, 'pending' => 0, 'missing' => [], 'complete' => true]];
            if ($filters['filter_dokumen'] !== '' && $checklist['summary']['complete'] !== ($filters['filter_dokumen'] === 'lengkap')) {
                continue;
            }
            // STR & SIP/SIK due date comes from the uploaded document (only for health workers).
            $strSip = null;
            foreach ($checklist['entries'] as $entry) {
                if ($entry['item']['id'] === 'str-sip') {
                    $strSip = ['required' => $entry['required'], 'status' => $entry['status'], 'expires_at' => $entry['row']['expires_at'] ?? null];
                }
            }
            $row = [
                'id' => (int) $employee['id'],
                'nama' => $employee['nama'],
                'nip' => $employee['nip'],
                'bangsal' => $employee['unit_name'] ?? '',
                'jenis' => $employee['jenis_ketenagaan'] ?: $employee['jenis_kepegawaian'],
                'status_kep' => $employee['jenis_kepegawaian'],
                'status' => $employee['employee_status'],
                'jabatan' => $employee['jabatan_nama'],
                'jenjang' => $employee['jenjang_jabatan'],
                'jenis_jenjang' => str_contains(strtolower($employee['jenjang_jabatan']), 'ahli') ? 'Ahli' : ($employee['jenjang_jabatan'] !== '' ? 'Terampil' : '-'),
                'str_sip' => $strSip,
                'capaian_jpl' => (int) $employee['jpl_completed'],
                'dokumen' => $checklist['summary'],
            ];
            $list[] = $row;
        }

        return view('data_pegawai', [
            'active_menu' => 'data-pegawai',
            'role' => $this->role(),
            'pegawaiList' => $list,
            'can_update' => EmployeeAccess::can('employees.update'),
            'can_create' => $canCreate,
            'unitOptions' => $filterOptions['filter_bangsal'],
            'statusOptions' => $filterOptions['filter_status'],
            'ketenagaanOptions' => $filterOptions['filter_ketenagaan'],
            'statusDataOptions' => $filterOptions['filter_status_data'],
            'jabatanOptions' => $filterOptions['filter_jabatan'],
            'jenjangOptions' => $filterOptions['filter_jenjang'],
            'has_filter' => array_filter($filters) !== [],
        ] + $filters);
    }

    public function editPegawai()
    {
        $data = $this->employeePageData('data-pegawai', true);
        if ($data['employee_profile'] === null) {
            return redirect()->to('/data-pegawai')->with('error', $data['profile_error']);
        }
        return view('employee/profile', $data);
    }

    public function viewPegawai()
    {
        $data = $this->employeePageData('data-pegawai');
        if ($data['employee_profile'] === null) {
            return redirect()->to('/data-pegawai')->with('error', $data['profile_error']);
        }
        return view('employee/profile', $data);
    }

    public function laporan()
    {
        if (! EmployeeAccess::can('reports.view')) {
            return redirect()->to('/');
        }
        $employees = $this->employeeModel()->visibleTo($this->user());
        $unitCounts = [];
        $statusCounts = [];
        foreach ($employees as $employee) {
            $unit = $employee['unit_name'] ?: 'Tanpa unit';
            $status = $employee['jenis_kepegawaian'] ?: 'Belum diisi';
            $unitCounts[$unit] = ($unitCounts[$unit] ?? 0) + 1;
            $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
        }
        return view('laporan', [
            'active_menu' => 'laporan',
            'role' => $this->role(),
            'employee_count' => count($employees),
            'unitCounts' => $unitCounts,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function pengaturan()
    {
        return view('pengaturan', ['active_menu' => 'pengaturan', 'role' => $this->role()]);
    }

    public function pensiun()
    {
        if (! EmployeeAccess::can('employees.view')) {
            return redirect()->to('/');
        }
        $employees = array_values(array_filter(
            $this->employeeModel()->visibleTo($this->user()),
            static fn (array $employee): bool => str_contains(mb_strtolower($employee['employee_status'] . ' ' . $employee['kedudukan']), 'pensiun'),
        ));
        return view('pensiun', ['active_menu' => 'pensiun', 'role' => $this->role(), 'retiredEmployees' => $employees]);
    }

    public function manajemenBangsal()
    {
        if (($this->user()['role'] ?? '') !== 'superadmin') {
            return redirect()->to('/');
        }
        return view('manajemen_bangsal', ['active_menu' => 'manajemen-bangsal', 'role' => $this->role(), 'units' => (new UnitModel())->withEmployeeCounts()]);
    }

    public function detailBangsal()
    {
        return redirect()->to('/data-pegawai?filter_bangsal=' . rawurlencode((string) $this->request->getGet('bangsal')));
    }

    public function profilPegawai()
    {
        $data = $this->employeePageData('profil-pegawai', true, 'profil-pegawai');
        if ($data['employee_profile'] === null) {
            return redirect()->to('/')->with('error', $data['profile_error']);
        }
        return view('employee/profile', $data);
    }

    public function riwayatKepangkatan()
    {
        $data = $this->employeePageData('riwayat-kepangkatan', $this->isPegawai(), 'riwayat-kepangkatan');
        return $data['employee_profile'] === null ? redirect()->to('/')->with('error', $data['profile_error']) : view('employee/profile', $data);
    }

    public function riwayatJabatan()
    {
        $data = $this->employeePageData('riwayat-jabatan', $this->isPegawai(), 'riwayat-jabatan');
        return $data['employee_profile'] === null ? redirect()->to('/')->with('error', $data['profile_error']) : view('employee/profile', $data);
    }

    public function riwayatPendidikan()
    {
        $data = $this->employeePageData('riwayat-pendidikan', $this->isPegawai(), 'riwayat-pendidikan');
        return $data['employee_profile'] === null ? redirect()->to('/')->with('error', $data['profile_error']) : view('employee/profile', $data);
    }

    public function dataKeluarga()
    {
        $data = $this->employeePageData('data-keluarga', $this->isPegawai(), 'data-keluarga');
        return $data['employee_profile'] === null ? redirect()->to('/')->with('error', $data['profile_error']) : view('employee/profile', $data);
    }

    public function dataLainnya()
    {
        $data = $this->employeePageData('data-lainnya', $this->isPegawai(), 'data-lainnya');
        return $data['employee_profile'] === null ? redirect()->to('/')->with('error', $data['profile_error']) : view('employee/profile', $data);
    }

    public function dokumenPegawai()
    {
        $data = $this->employeePageData('dokumen-pegawai', $this->isPegawai(), 'dokumen-pegawai');
        return $data['employee_profile'] === null ? redirect()->to('/')->with('error', $data['profile_error']) : view('employee/profile', $data);
    }

    private function isPegawai(): bool
    {
        return ($this->user()['role'] ?? '') === 'pegawai';
    }

    public function aktivitas()
    {
        if (! EmployeeAccess::can('documents.manage')) {
            return redirect()->to('/')->with('error', 'Akun Anda tidak memiliki hak untuk memverifikasi dokumen.');
        }
        return view('aktivitas', [
            'active_menu' => 'aktivitas',
            'role' => $this->role(),
            'pendingDocuments' => (new EmployeeDocumentModel())->pendingUploadsFor($this->user()),
        ]);
    }
}
