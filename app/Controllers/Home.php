<?php

namespace App\Controllers;

use App\Libraries\EmployeeAccess;
use App\Libraries\EmployeeDocxExporter;
use App\Libraries\EmployeeProfileData;
use App\Libraries\XlsxWriter;
use App\Models\EmployeeEducationModel;
use App\Models\EmployeeHistoryModel;
use App\Models\EmployeeModel;
use App\Models\EmployeeDocumentModel;
use App\Models\JobTitleModel;
use App\Models\RetirementBupModel;
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

    private function isInactiveEmployee(array $employee): bool
    {
        $inactiveStatuses = EmployeeModel::INACTIVE_EMPLOYEE_STATUSES;
        return in_array(mb_strtolower(trim((string) ($employee['employee_status'] ?? ''))), $inactiveStatuses, true)
            || in_array(mb_strtolower(trim((string) ($employee['kedudukan'] ?? ''))), $inactiveStatuses, true);
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

    private function dashboardAggregate(array $employees): array
    {
        $employmentCounts = array_fill_keys([
            'cpns', 'pns', 'pppk_full', 'pppk_part', 'blud_permanent', 'blud_contract',
            'mitra_full', 'mitra_part', 'other',
        ], 0);
        $jobGroups = [];
        foreach ($employees as $employee) {
            $category = $this->employmentCategory((string) ($employee['jenis_kepegawaian'] ?? ''));
            $employmentCounts[$category]++;
            $sourceJob = trim((string) ($employee['jabatan_nama'] ?? ''));
            if ($sourceJob === '') {
                $sourceJob = trim((string) ($employee['jenis_ketenagaan'] ?? ''));
            }
            $jobName = $this->dashboardJobLabel($sourceJob);
            $jobKey = mb_strtolower($jobName);
            $seniority = $this->dashboardSeniorityCategory($sourceJob, (string) ($employee['jenjang_jabatan'] ?? ''));
            if (! isset($jobGroups[$jobKey])) {
                $jobGroups[$jobKey] = ['name' => $jobName, 'total' => 0, 'expert' => 0, 'skilled' => 0, 'specific' => 0, 'subroles' => []];
            }
            $jobGroups[$jobKey]['total']++;
            $jobGroups[$jobKey][$seniority]++;
            if ($jobName === 'Dokter') {
                $sourceLower = mb_strtolower($sourceJob);
                $subrole = str_contains($sourceLower, 'spesialis') ? 'Dokter Spesialis' : (str_contains($sourceLower, 'umum') ? 'Dokter Umum' : 'Dokter');
                $jobGroups[$jobKey]['subroles'][$subrole] = ($jobGroups[$jobKey]['subroles'][$subrole] ?? 0) + 1;
            }
        }

        $preferredJobOrder = ['Dokter' => 0, 'Perawat' => 1, 'Bidan' => 2, 'Apoteker' => 3, 'Asisten Apoteker' => 4, 'Analis SDM Aparatur' => 5, 'Pranata Komputer' => 6, 'Pengelola Keuangan' => 7, 'Petugas Kebersihan' => 8];
        $jobBreakdown = array_values($jobGroups);
        foreach ($jobBreakdown as &$job) {
            $job['details'] = [];
            if ($job['name'] === 'Dokter' && $job['subroles'] !== []) {
                $subroleOrder = ['Dokter Umum' => 0, 'Dokter Spesialis' => 1, 'Dokter' => 2];
                uksort($job['subroles'], static fn (string $a, string $b): int => ($subroleOrder[$a] ?? 99) <=> ($subroleOrder[$b] ?? 99));
                foreach ($job['subroles'] as $label => $count) { $job['details'][] = ['label' => $label, 'count' => $count]; }
            } else {
                if ($job['expert'] > 0) { $job['details'][] = ['label' => $job['name'] === 'Perawat' ? 'Kategori Ahli (Profesi)' : 'Kategori Ahli', 'count' => $job['expert']]; }
                if ($job['skilled'] > 0) { $job['details'][] = ['label' => $job['name'] === 'Perawat' ? 'Kategori Terampil (Vokasi)' : 'Kategori Terampil', 'count' => $job['skilled']]; }
            }
            unset($job['subroles']);
        }
        unset($job);
        usort($jobBreakdown, static function (array $a, array $b) use ($preferredJobOrder): int {
            $aOrder = $preferredJobOrder[$a['name']] ?? PHP_INT_MAX;
            $bOrder = $preferredJobOrder[$b['name']] ?? PHP_INT_MAX;
            return $aOrder === $bOrder ? strcasecmp($a['name'], $b['name']) : $aOrder <=> $bOrder;
        });
        return ['employee_count' => count($employees), 'employment_counts' => $employmentCounts, 'job_breakdown' => $jobBreakdown];
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
        $employees = $canViewEmployees
            ? array_values(array_filter($this->employeeModel()->visibleTo($user), fn (array $employee): bool => ! $this->isInactiveEmployee($employee)))
            : [];
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
        $dashboardScope = (string) $this->request->getGet('scope');
        $scopeCategories = [
            'asn' => ['cpns', 'pns', 'pppk_full', 'pppk_part'],
            'blud' => ['blud_permanent', 'blud_contract'],
            'mitra' => ['mitra_full', 'mitra_part'],
            'other' => ['other'],
        ];
        if (! isset($scopeCategories[$dashboardScope])) {
            $dashboardScope = 'all';
        }
        $scopedDashboardData = ['all' => $this->dashboardAggregate($employees)];
        foreach ($scopeCategories as $scope => $categories) {
            $scopedEmployees = array_values(array_filter($employees, fn (array $employee): bool => in_array(
                $this->employmentCategory((string) ($employee['jenis_kepegawaian'] ?? '')),
                $categories,
                true,
            )));
            $scopedDashboardData[$scope] = $this->dashboardAggregate($scopedEmployees);
        }
        $currentDashboardData = $scopedDashboardData[$dashboardScope] ?? $scopedDashboardData['all'];
        $employmentCounts = $currentDashboardData['employment_counts'];
        $jobBreakdown = $currentDashboardData['job_breakdown'];

        return view('dashboard', [
            'active_menu' => 'dashboard',
            'role' => $this->role(),
            'employee_count' => $currentDashboardData['employee_count'],
            'selectedUnit' => $selectedUnit,
            'dashboardScope' => $dashboardScope,
            'unitOptions' => $unitOptions,
            'employmentCounts' => $employmentCounts,
            'jobBreakdown' => $jobBreakdown,
            'scopedDashboardData' => $scopedDashboardData,
            'can_view_employees' => $canViewEmployees,
        ]);
    }

    /** Filters of the employee list, read from the query string (shared by the list and its Excel export). */
    private function listFilters(): array
    {
        $filters = ['q' => trim((string) $this->request->getGet('q')), 'active_only' => true];
        foreach (array_keys(EmployeeModel::LIST_FILTER_COLUMNS) as $key) {
            $filters[$key] = trim((string) $this->request->getGet($key));
        }
        $jobCatalog = new JobTitleModel();
        if ($filters['filter_ketenagaan'] !== '') {
            $filters['filter_ketenagaan'] = $jobCatalog->canonicalGroup($filters['filter_ketenagaan']) ?? '';
        }
        if ($filters['filter_ketenagaan'] === '' || ($filters['filter_jabatan'] !== '' && ! $jobCatalog->isValidPair($filters['filter_ketenagaan'], $filters['filter_jabatan']))) {
            $filters['filter_jabatan'] = '';
        }
        $statusGroups = array_merge(array_keys(EmployeeModel::EMPLOYMENT_STATUS_GROUPS), ['other']);
        $filters['filter_status_group'] = in_array($this->request->getGet('filter_status_group'), $statusGroups, true)
            ? (string) $this->request->getGet('filter_status_group')
            : '';
        if ($filters['filter_status_group'] === '' && $filters['filter_status'] !== '') {
            foreach (EmployeeModel::EMPLOYMENT_STATUS_GROUPS as $group => $statuses) {
                if (in_array($filters['filter_status'], $statuses, true)) {
                    $filters['filter_status_group'] = $group;
                    break;
                }
            }
            if ($filters['filter_status_group'] === '') {
                $filters['filter_status_group'] = 'other';
            }
        }
        $filters['filter_jpl'] = in_array($this->request->getGet('filter_jpl'), ['kurang', 'terpenuhi'], true)
            ? (string) $this->request->getGet('filter_jpl')
            : '';
        $filters['filter_dokumen'] = in_array($this->request->getGet('filter_dokumen'), ['lengkap', 'belum'], true)
            ? (string) $this->request->getGet('filter_dokumen')
            : '';

        return $filters;
    }

    /**
     * Excel export of every employee matching the current list filters: one sheet, one row per
     * employee; sections A-F one field per column, each repeatable section G-X in a single column.
     */
    public function exportExcel()
    {
        if (! EmployeeAccess::can('employees.view') || $this->isPegawai()) {
            return redirect()->to('/data-pegawai')->with('error', 'Akun Anda tidak memiliki akses untuk mengunduh data pegawai.');
        }

        $filters = $this->listFilters();
        $employees = $this->employeeModel()->visibleTo($this->user(), $filters);
        $checklists = (new EmployeeDocumentModel())->checklistStatus($employees);
        if ($filters['filter_dokumen'] !== '') {
            $wantComplete = $filters['filter_dokumen'] === 'lengkap';
            $employees = array_values(array_filter(
                $employees,
                static fn (array $employee): bool => ($checklists[(int) $employee['id']]['summary']['complete'] ?? true) === $wantComplete,
            ));
        }

        helper('employee_form');
        $plain = static fn ($value, array $field): string => html_entity_decode(strip_tags(employee_field_display($value, $field)), ENT_QUOTES, 'UTF-8');
        $plainOrEmpty = static fn ($value, array $field): string => ($text = $plain($value, $field)) === '—' ? '' : $text;

        // Columns: sections A-F one field per column; a label used in several sections gets its section's subject.
        $coreSections = array_merge(EmployeeProfileData::officialCoreSections(), [EmployeeProfileData::officialLatestEducationSection()]);
        $subjects = ['A' => 'Identitas', 'B' => 'CPNS', 'C' => 'PNS', 'D' => 'Pangkat', 'E' => 'Jabatan', 'F' => 'Pendidikan'];
        $labelCount = [];
        foreach ($coreSections as $section) {
            foreach ($section['fields'] as $field) {
                $labelCount[$field[1]] = ($labelCount[$field[1]] ?? 0) + 1;
            }
        }
        $headers = ['No', 'Unit'];
        foreach ($coreSections as $section) {
            foreach ($section['fields'] as $field) {
                $headers[] = $labelCount[$field[1]] > 1 ? $field[1] . ' (' . $subjects[$section['letter']] . ')' : $field[1];
            }
        }

        // Repeatable sections G-X: one column each, every record written on the same line.
        $repeatSections = [];
        foreach (EmployeeProfileData::officialHistorySections() as $category => $section) {
            $repeatSections[$section['letter']] = ['key' => $category, 'title' => $section['title'], 'fields' => $section['fields']];
        }
        $repeatSections['J'] = ['key' => 'education', 'title' => 'RIWAYAT PENDIDIKAN', 'fields' => EmployeeProfileData::educationFields()];
        ksort($repeatSections);
        foreach ($repeatSections as $section) {
            $headers[] = ucwords(mb_strtolower($section['title']), " /-");
        }
        array_push($headers, 'Dokumen wajib terunggah', 'Dokumen wajib kurang');

        $ids = array_map(static fn (array $employee): int => (int) $employee['id'], $employees);
        $records = [];
        if ($ids !== []) {
            foreach ((new EmployeeHistoryModel())->whereIn('employee_id', $ids)->orderBy('employee_id')->orderBy('sort_order')->findAll() as $history) {
                $records[(int) $history['employee_id']][$history['category']][] = json_decode((string) $history['data'], true) ?: [];
            }
            foreach ((new EmployeeEducationModel())->whereIn('employee_id', $ids)->orderBy('employee_id')->orderBy('tanggal_ijazah')->findAll() as $education) {
                $records[(int) $education['employee_id']]['education'][] = $education;
            }
        }
        $oneLine = static fn (string $text): string => trim(preg_replace('/\s+/', ' ', $text) ?? '');

        $rows = [];
        foreach ($employees as $index => $employee) {
            $row = [(string) ($index + 1), (string) ($employee['unit_name'] ?? '')];
            foreach ($coreSections as $section) {
                foreach ($section['fields'] as $field) {
                    $row[] = $oneLine($plainOrEmpty($employee[$field[0]] ?? '', $field));
                }
            }
            foreach ($repeatSections as $section) {
                $entries = [];
                foreach ($records[(int) $employee['id']][$section['key']] ?? [] as $number => $record) {
                    $parts = [];
                    foreach ($section['fields'] as $field) {
                        $value = $oneLine($plainOrEmpty($record[$field[0]] ?? '', $field));
                        if ($value !== '') {
                            $parts[] = $value;
                        }
                    }
                    if ($parts !== []) {
                        $entries[] = ($number + 1) . ') ' . implode(', ', $parts);
                    }
                }
                $row[] = implode('; ', $entries);
            }
            $summary = $checklists[(int) $employee['id']]['summary'] ?? ['uploaded' => 0, 'total' => 0, 'missing' => []];
            $row[] = $summary['uploaded'] . ' / ' . $summary['total'];
            $row[] = implode(', ', $summary['missing']);
            $rows[] = $row;
        }

        $writer = (new XlsxWriter())->addSheet('Data Pegawai', $headers, $rows);

        $temp = tempnam(sys_get_temp_dir(), 'xlsx');
        try {
            $writer->save($temp);
            $contents = (string) file_get_contents($temp);
        } finally {
            @unlink($temp);
        }

        return $this->response->download('data-pegawai-' . date('Y-m-d') . '.xlsx', $contents)
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** Word download of one employee, filled into the official "Data Pegawai" form. */
    public function exportWord()
    {
        $user = $this->user();
        $employeeId = $this->requestedEmployeeId();
        $employee = $employeeId > 0 ? $this->employeeModel()->findVisibleById($employeeId, $user) : null;
        if ($employee === null || ! EmployeeAccess::can('employees.view', $employee)) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan atau akun Anda tidak memiliki akses.');
        }

        $bundle = $this->employeeModel()->bundle($employeeId);
        $contents = (new EmployeeDocxExporter())->build($bundle);
        $name = trim(preg_replace('/[^A-Za-z0-9 ._-]+/', '', $employee['nama'] . ' ' . $employee['nip']) ?? 'pegawai');

        return $this->response->download('Data Pegawai - ' . $name . '.docx', $contents)
            ->setContentType('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    }

    public function dataPegawai()
    {
        $canView = EmployeeAccess::can('employees.view');
        $canCreate = EmployeeAccess::can('employees.create');
        if (! $canView && ! $canCreate) {
            return redirect()->to('/profil-pegawai')->with('error', 'Akun Anda tidak memiliki akses ke daftar pegawai.');
        }

        $user = $this->user();
        $filters = $this->listFilters();

        $filterOptions = $canView ? $this->employeeModel()->filterOptions($user) : array_fill_keys(array_keys(EmployeeModel::LIST_FILTER_COLUMNS), []);
        $jobCatalog = new JobTitleModel();
        $jobOptionsByKetenagaan = $jobCatalog->allGrouped();
        foreach ($jobOptionsByKetenagaan as &$titles) {
            sort($titles, SORT_NATURAL | SORT_FLAG_CASE);
        }
        unset($titles);
        uksort($jobOptionsByKetenagaan, 'strnatcasecmp');
        $ketenagaanOptions = array_keys($jobOptionsByKetenagaan);
        sort($ketenagaanOptions, SORT_NATURAL | SORT_FLAG_CASE);
        // Pagination: page size from ?per_page (10/25/50/100), page number from ?page.
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $this->request->getGet('per_page');
        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }
        $page = max(1, (int) $this->request->getGet('page'));
        $emptySummary = ['entries' => [], 'summary' => ['total' => 0, 'uploaded' => 0, 'pending' => 0, 'missing' => [], 'revisions' => [], 'complete' => true]];
        $documentModel = new EmployeeDocumentModel();

        if (! $canView) {
            $total = 0;
            $pageEmployees = [];
            $checklists = [];
        } elseif ($filters['filter_dokumen'] === '') {
            // Only the rows of the current page are read from the database.
            $total = $this->employeeModel()->countVisibleTo($user, $filters);
            $page = min($page, max(1, (int) ceil($total / $perPage)));
            $pageEmployees = $this->employeeModel()->visibleTo($user, $filters, $perPage, ($page - 1) * $perPage);
            $checklists = $documentModel->checklistStatus($pageEmployees);
        } else {
            // Document completeness is computed, not stored, so filter first and page afterwards.
            $matching = $this->employeeModel()->visibleTo($user, $filters);
            $checklists = $documentModel->checklistStatus($matching);
            $wantComplete = $filters['filter_dokumen'] === 'lengkap';
            $matching = array_values(array_filter(
                $matching,
                static fn (array $employee): bool => ($checklists[(int) $employee['id']] ?? $emptySummary)['summary']['complete'] === $wantComplete,
            ));
            $total = count($matching);
            $page = min($page, max(1, (int) ceil($total / $perPage)));
            $pageEmployees = array_slice($matching, ($page - 1) * $perPage, $perPage);
        }

        $list = [];
        foreach ($pageEmployees as $employee) {
            $checklist = $checklists[(int) $employee['id']] ?? $emptySummary;
            $workforce = trim((string) ($employee['jenis_ketenagaan'] ?? ''));
            // Use separate STR/SIP entries, while the list keeps one compact combined status.
            $licenses = ['str' => null, 'sip' => null];
            foreach ($checklist['entries'] as $entry) {
                $licenseKey = match ($entry['item']['id']) {
                    'str-sip' => 'str',
                    'sip' => 'sip',
                    default => null,
                };
                if ($licenseKey === null) {
                    continue;
                }
                $dateField = $licenseKey . '_berlaku';
                $licenses[$licenseKey] = [
                    'required' => $entry['required'],
                    'status' => $entry['status'],
                    'expires_at' => trim((string) ($employee[$dateField] ?? '')) !== ''
                        ? $employee[$dateField]
                        : ($entry['row']['expires_at'] ?? null),
                ];
            }
            $visibleLicenses = array_values(array_filter(
                $licenses,
                static fn ($license): bool => is_array($license) && (! empty($license['required']) || ($license['status'] ?? 'missing') !== 'missing'),
            ));
            $expiryDates = array_values(array_filter(array_column($visibleLicenses, 'expires_at')));
            sort($expiryDates);
            $hasRequiredMissingLicense = count(array_filter(
                $visibleLicenses,
                static fn (array $license): bool => ! empty($license['required']) && ($license['status'] ?? '') === 'missing',
            )) > 0;
            $hasExpiredLicense = count(array_filter(
                $visibleLicenses,
                static fn (array $license): bool => ($license['status'] ?? '') === 'expired',
            )) > 0;
            $hasPendingLicense = count(array_filter(
                $visibleLicenses,
                static fn (array $license): bool => ($license['status'] ?? '') === 'pending',
            )) > 0;
            $strSip = $visibleLicenses === [] ? null : [
                'required' => count(array_filter($visibleLicenses, static fn (array $license): bool => ! empty($license['required']))) > 0,
                'status' => $hasRequiredMissingLicense ? 'missing' : ($hasExpiredLicense ? 'expired' : ($hasPendingLicense ? 'pending' : 'verified')),
                'expires_at' => $expiryDates[0] ?? null,
            ];
            $row = [
                'id' => (int) $employee['id'],
                'nama' => $employee['nama'],
                'nip' => $employee['nip'],
                'bangsal' => $employee['unit_name'] ?? '',
                'jenis' => $workforce !== '' ? ($jobCatalog->canonicalGroup($workforce) ?? $workforce) : $employee['jenis_kepegawaian'],
                'status_kep' => $employee['jenis_kepegawaian'],
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
            'statusGroupOptions' => [
                'asn' => 'ASN',
                'blud' => 'BLUD',
                'mitra' => 'Mitra',
                'other' => 'Lainnya',
            ],
            'ketenagaanOptions' => $ketenagaanOptions,
            'jobOptionsByKetenagaan' => $jobOptionsByKetenagaan,
            'jenjangOptions' => $filterOptions['filter_jenjang'],
            'has_filter' => array_filter($filters, static fn ($value, $key): bool => $key !== 'active_only' && $value !== '', ARRAY_FILTER_USE_BOTH) !== [],
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'per_page_options' => $perPageOptions,
                'total' => $total,
                'pages' => max(1, (int) ceil($total / $perPage)),
            ],
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
        // Range for "akan pensiun", in months ahead; overdue employees are always listed.
        $windows = [6 => '6 bulan', 12 => '1 tahun', 24 => '2 tahun', 60 => '5 tahun'];
        $window = (int) $this->request->getGet('rentang');
        if (! isset($windows[$window])) {
            $window = 24;
        }

        $bupAges = (new RetirementBupModel())->ages();
        $upcoming = [];
        foreach ($this->employeeModel()->visibleTo($this->user()) as $employee) {
            if ($this->isInactiveEmployee($employee)) {
                continue;
            }
            $info = EmployeeProfileData::retirementInfo($employee, null, $bupAges);
            if ($info !== null && $info['months_left'] <= $window) {
                $upcoming[] = $employee + ['pensiun' => $info];
            }
        }
        usort($upcoming, static fn (array $a, array $b): int => strcmp($a['pensiun']['tmt'], $b['pensiun']['tmt']));

        return view('pensiun', [
            'active_menu' => 'pensiun',
            'role' => $this->role(),
            'upcomingEmployees' => $upcoming,
            'windows' => $windows,
            'window' => $window,
            'can_retire' => EmployeeAccess::can('employees.update'),
        ]);
    }

    public function pegawaiNonaktif()
    {
        if (! EmployeeAccess::can('employees.view')) {
            return redirect()->to('/');
        }
        $categories = [
            'pensiun' => 'Pensiun',
            'mutasi-keluar' => 'Mutasi Keluar',
            'habis-kontrak' => 'Habis Kontrak',
            'mengundurkan-diri' => 'Mengundurkan Diri',
        ];
        $employees = $this->employeeModel()->visibleTo($this->user());
        $bupSettings = (new RetirementBupModel())->settings();
        $bupAges = array_map(static fn (array $setting): int => (int) $setting['age'], $bupSettings);
        $inactiveStatuses = ['Pensiun', ...array_values($categories), 'Nonaktif'];
        $inactiveEmployees = array_values(array_filter(
            $employees,
            fn (array $employee): bool => $this->isInactiveEmployee($employee),
        ));
        $letterRows = (new EmployeeDocumentModel())
            ->whereIn('employee_id', array_map(static fn (array $employee): int => (int) $employee['id'], $inactiveEmployees) ?: [0])
            ->where('document_key', 'surat-nonaktif')
            ->findAll();
        $lettersByEmployee = [];
        foreach ($letterRows as $letterRow) {
            $lettersByEmployee[(int) $letterRow['employee_id']] = (int) $letterRow['id'];
        }
        foreach ($inactiveEmployees as &$inactiveEmployee) {
            $inactiveEmployee['surat_id'] = $lettersByEmployee[(int) $inactiveEmployee['id']] ?? null;
            $inactiveEmployee['status_nonaktif'] = in_array($inactiveEmployee['employee_status'] ?? '', $inactiveStatuses, true)
                ? $inactiveEmployee['employee_status']
                : ($inactiveEmployee['kedudukan'] ?? 'Nonaktif');
        }
        unset($inactiveEmployee);

        $statusCounts = array_fill_keys(array_values($categories), 0);
        foreach ($inactiveEmployees as $inactiveEmployee) {
            if (isset($statusCounts[$inactiveEmployee['status_nonaktif']])) {
                $statusCounts[$inactiveEmployee['status_nonaktif']]++;
            }
        }

        $inactiveTotal = count($inactiveEmployees);
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $this->request->getGet('per_page');
        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }
        $page = max(1, (int) $this->request->getGet('page'));
        $pages = max(1, (int) ceil($inactiveTotal / $perPage));
        $page = min($page, $pages);
        $inactiveEmployees = array_slice($inactiveEmployees, ($page - 1) * $perPage, $perPage);

        $pensionCandidates = [];
        foreach ($employees as $employee) {
            if ($this->isInactiveEmployee($employee)) {
                continue;
            }
            $retirementInfo = EmployeeProfileData::retirementInfo($employee, null, $bupAges);
            if ($retirementInfo !== null && $retirementInfo['months_left'] <= 24) {
                $pensionCandidates[] = $employee + ['pensiun' => $retirementInfo];
            }
        }
        usort($pensionCandidates, static fn (array $first, array $second): int => strcmp($first['pensiun']['tmt'], $second['pensiun']['tmt']));
        $pensionCandidateIds = array_fill_keys(array_map('intval', array_column($pensionCandidates, 'id')), true);

        $eligibleEmployees = array_values(array_filter(
            $employees,
            fn (array $employee): bool => ! $this->isInactiveEmployee($employee),
        ));

        return view('pegawai_nonaktif', [
            'active_menu' => 'pegawai-nonaktif',
            'role' => $this->role(),
            'categories' => $categories,
            'inactiveEmployees' => $inactiveEmployees,
            'inactiveStatusCounts' => $statusCounts,
            'inactivePagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'per_page_options' => $perPageOptions,
                'total' => $inactiveTotal,
                'pages' => $pages,
            ],
            'eligibleEmployees' => $eligibleEmployees,
            'pensionCandidates' => $pensionCandidates,
            'pensionCandidateIds' => $pensionCandidateIds,
            'bupSettings' => $bupSettings,
            'can_manage_bup' => ($this->user()['role'] ?? '') === 'superadmin',
            'can_update' => EmployeeAccess::can('employees.update'),
        ]);
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

    public function sip()
    {
        $data = $this->employeePageData('sip', $this->isPegawai(), 'sip');
        return $data['employee_profile'] === null ? redirect()->to('/')->with('error', $data['profile_error']) : view('employee/profile', $data);
    }

    public function str()
    {
        $data = $this->employeePageData('str', $this->isPegawai(), 'str');
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
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $this->request->getGet('per_page');
        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }
        $page = max(1, (int) $this->request->getGet('page'));
        $documents = new EmployeeDocumentModel();
        $total = $documents->countPendingUploadsFor($this->user());
        $pages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $pages);

        return view('aktivitas', [
            'active_menu' => 'aktivitas',
            'role' => $this->role(),
            'pendingDocuments' => $documents->pendingUploadsFor($this->user(), $perPage, ($page - 1) * $perPage),
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'per_page_options' => $perPageOptions,
                'total' => $total,
                'pages' => $pages,
            ],
        ]);
    }
}
