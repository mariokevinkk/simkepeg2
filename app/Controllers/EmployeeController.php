<?php

namespace App\Controllers;

use App\Libraries\EmployeeAccess;
use App\Libraries\EmployeeProfileData;
use App\Models\EmployeeDocumentModel;
use App\Models\EmployeeEducationModel;
use App\Models\EmployeeHistoryModel;
use App\Models\EmployeeModel;
use App\Models\JobTitleModel;
use App\Models\RetirementBupModel;
use App\Models\UnitModel;
use App\Models\UserModel;

class EmployeeController extends BaseController
{
    public function createForm()
    {
        if (! EmployeeAccess::can('employees.create')) {
            return redirect()->to('/data-pegawai')->with('error', 'Akun Anda tidak memiliki hak untuk menambah pegawai.');
        }
        $user = EmployeeAccess::user();
        $unitModel = new UnitModel();
        $units = ($user['role'] ?? '') === 'superadmin'
            ? $unitModel->activeUnits()
            : $unitModel->whereIn('id', $user['unit_ids'] ?? [0])->where('is_active', 1)->orderBy('name')->findAll();

        return view('employee/create', ['active_menu' => 'data-pegawai', 'role' => ($user['role'] ?? '') === 'admin_unit' ? 'admin' : $user['role'], 'units' => $units]);
    }

    public function create()
    {
        if (! EmployeeAccess::can('employees.create')) {
            return redirect()->to('/data-pegawai')->with('error', 'Akun Anda tidak memiliki hak untuk menambah pegawai.');
        }
        $rules = [
            'nama' => 'required|min_length[3]|max_length[150]',
            'nip' => 'required|min_length[4]|max_length[40]',
            'username' => 'required|min_length[4]|max_length[80]|alpha_numeric_punct',
            'tanggal_lahir' => 'required|valid_date[Y-m-d]',
            'tanggal_masuk' => 'required|valid_date[Y-m-d]',
            'unit_id' => 'required|is_natural_no_zero',
            'email' => 'permit_empty|valid_email|max_length[190]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        if ($jobError = $this->jobSelectionError()) {
            return redirect()->back()->withInput()->with('error', $jobError);
        }

        $user = EmployeeAccess::user();
        $unitId = (int) $this->request->getPost('unit_id');
        $unitModel = new UnitModel();
        $unit = $unitModel->where('id', $unitId)->where('is_active', 1)->first();
        if ($unit === null || (($user['role'] ?? '') === 'admin_unit' && ! in_array($unitId, array_map('intval', $user['unit_ids'] ?? []), true))) {
            return redirect()->back()->withInput()->with('error', 'Unit tersebut tidak tersedia untuk akun Anda.');
        }

        $employeeModel = new EmployeeModel();
        $nip = trim((string) $this->request->getPost('nip'));
        $username = trim((string) $this->request->getPost('username'));
        $birthDate = trim((string) $this->request->getPost('tanggal_lahir'));
        $email = strtolower(trim((string) $this->request->getPost('email')));
        if ($employeeModel->where('nip', $nip)->first() !== null) {
            return redirect()->back()->withInput()->with('error', 'NIP tersebut sudah terdaftar.');
        }
        $userModel = new UserModel();
        $accountEmail = $email !== ''
            ? $email
            : 'pegawai.' . bin2hex(random_bytes(16)) . '@simkepegawaian.local';
        if (
            $userModel->findForLogin($username) !== null
            || $userModel->findForLogin($accountEmail) !== null
            || $userModel->findForLogin($nip) !== null
        ) {
            return redirect()->back()->withInput()->with('error', 'Username, email, atau NIP sudah dipakai akun lain.');
        }

        $birthDateValue = \DateTimeImmutable::createFromFormat('!Y-m-d', $birthDate);
        if ($birthDateValue === false || $birthDateValue->format('Y-m-d') !== $birthDate) {
            return redirect()->back()->withInput()->with('error', 'Tanggal lahir tidak valid.');
        }
        $initialPassword = $birthDateValue->format('dmY');

        $profileInput = [];
        foreach (array_merge(EmployeeProfileData::officialCoreSections(), [EmployeeProfileData::officialLatestEducationSection()]) as $section) {
            foreach ($section['fields'] as $fieldDefinition) {
                [$fieldKey, , $fieldType] = array_pad($fieldDefinition, 3, null);
                if (EmployeeProfileData::summarySourceFor($fieldKey) !== null) {
                    continue;
                }
                $value = $this->request->getPost($fieldKey);
                if ($value === null) {
                    continue;
                }
                $value = trim((string) $value);
                $profileInput[$fieldKey] = $fieldType === 'date' && $value === '' ? null : $value;
            }
        }
        $profileInput['employee_status'] = 'Aktif';
        $profileInput['kedudukan'] = 'Aktif';

        $db = db_connect();
        $db->transBegin();
        try {
            $employeeId = $employeeModel->registerProfile($profileInput + [
                'unit_id' => $unitId,
                'nip' => $nip,
                'nama' => trim((string) $this->request->getPost('nama')),
                'tanggal_lahir' => $birthDate,
                'email' => $email,
                'employee_status' => 'Aktif',
                'kedudukan' => 'Aktif',
            ]);
            $userId = (int) $userModel->insert([
                'employee_id' => $employeeId,
                'name' => trim((string) $this->request->getPost('nama')),
                'username' => $username,
                'email' => $accountEmail,
                'nip' => $nip,
                'password_hash' => password_hash($initialPassword, PASSWORD_DEFAULT),
                'role' => 'pegawai',
                'status' => 'active',
                'permissions' => json_encode([], JSON_THROW_ON_ERROR),
            ], true);
            if ($employeeId < 1 || $userId < 1 || ! $employeeModel->update($employeeId, ['user_id' => $userId])) {
                throw new \RuntimeException('Data pegawai dan akun tidak berhasil ditautkan.');
            }

            $this->saveRepeatableSections($employeeId);
            (new EmployeeDocumentModel())->syncCatalog($employeeId, (int) $user['id']);
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Data pegawai dan akun login gagal disimpan.');
            }
            $db->transCommit();
        } catch (\Throwable $exception) {
            $db->transRollback();
            log_message('error', 'Employee creation failed: {message}', ['message' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Data pegawai dan akun login gagal disimpan. Pastikan username, email, dan NIP belum pernah dipakai.');
        }

        $employeeAccess = ['unit_id' => $unitId];
        $target = EmployeeAccess::can('employees.update', $employeeAccess)
            ? '/edit-pegawai?id=' . $employeeId
            : (EmployeeAccess::can('employees.view', $employeeAccess) ? '/view-pegawai?id=' . $employeeId : '/data-pegawai');

        return redirect()->to($target)->with(
            'success',
            'Pegawai dan akun login berhasil dibuat. Username: ' . $username . '. Password awal: ' . $initialPassword . ' (format ddmmyyyy dari tanggal lahir). Pegawai dapat menggantinya melalui menu Ubah Password.',
        );
    }

    /** Saves education rows (J) and repeatable history sections (G-I, K-X) posted with the form. */
    private function saveRepeatableSections(int $employeeId): void
    {
        $educationModel = new EmployeeEducationModel();
        $postedEducation = (array) $this->request->getPost('education');
        $educationFields = array_flip([
            'tingkat', 'nama', 'instansi', 'fakultas', 'tempat', 'nomor_ijazah',
            'tanggal_ijazah', 'nilai', 'status',
        ]);
        $keptEducationIds = [];
        foreach ($postedEducation as $educationData) {
            if (! is_array($educationData)) {
                continue;
            }
            $educationId = (int) ($educationData['id'] ?? 0);
            unset($educationData['id']);
            $education = array_intersect_key($educationData, $educationFields);
            if (isset($education['tanggal_ijazah']) && $education['tanggal_ijazah'] === '') {
                $education['tanggal_ijazah'] = null;
            }
            $hasData = count(array_filter($education, static fn ($value, $key): bool => $key !== 'is_current' && $value !== '' && $value !== null, ARRAY_FILTER_USE_BOTH)) > 0;
            if (! $hasData) {
                continue;
            }

            if ($educationId > 0) {
                $existing = $educationModel->where('employee_id', $employeeId)->find($educationId);
                if ($existing === null) {
                    throw new \RuntimeException('Riwayat pendidikan tidak sesuai dengan pegawai.');
                }
                $educationModel->update($educationId, $education);
            } else {
                $educationId = (int) $educationModel->insert(['employee_id' => $employeeId] + $education, true);
                if ($educationId < 1) {
                    throw new \RuntimeException('Riwayat pendidikan gagal disimpan.');
                }
            }
            $keptEducationIds[] = $educationId;
        }
        if ($this->request->getPost('education_section') !== null) {
            // Rows removed from the table (section J was on the page) are deleted with their ijazah files.
            $removedIds = array_column(
                $educationModel->select('id')->where('employee_id', $employeeId)
                    ->whereNotIn('id', $keptEducationIds === [] ? [0] : $keptEducationIds)->findAll(),
                'id',
            );
            if ($removedIds !== []) {
                (new EmployeeDocumentModel())->deleteForEducations($employeeId, array_map('intval', $removedIds));
                $educationModel->where('employee_id', $employeeId)->whereIn('id', $removedIds)->delete();
            }
        }
        $educationModel->setLatestCurrent($employeeId);

        $historyModel = new EmployeeHistoryModel();
        $postedHistories = (array) $this->request->getPost('histories');
        // Each page only shows some sections; sections that were not on the page stay untouched.
        $postedSections = array_map('strval', (array) $this->request->getPost('history_sections'));
        foreach (EmployeeProfileData::officialHistorySections() as $category => $section) {
            if (! in_array($category, $postedSections, true)) {
                continue;
            }
            $normalizedRecords = [];
            foreach ((array) ($postedHistories[$category] ?? []) as $historyData) {
                if (! is_array($historyData)) {
                    continue;
                }
                $record = ['id' => (int) ($historyData['id'] ?? 0)];
                foreach ($section['fields'] as $fieldDefinition) {
                    [$fieldKey, $fieldLabel, $fieldType] = array_pad($fieldDefinition, 3, null);
                    $value = $historyData[$fieldKey] ?? '';
                    $value = is_scalar($value) ? trim((string) $value) : '';
                    $record[$fieldKey] = $fieldType === 'date' && $value === '' ? null : $value;
                }
                $normalizedRecords[] = $record;
            }
            $historyModel->saveForEmployee($employeeId, $category, $normalizedRecords);
            if ($category === 'jabatan') {
                $positions = $historyModel->forEmployee($employeeId, 'jabatan');
                $latestPosition = EmployeeProfileData::latestHistoryRow($positions);
                $currentEmployee = (new EmployeeModel())->find($employeeId);
                $workforce = trim((string) ($latestPosition['jenis_ketenagaan'] ?? ''));
                if ($workforce === '' && $latestPosition !== null) {
                    $workforce = (new JobTitleModel())->canonicalGroup(trim((string) ($latestPosition['nama_jabatan'] ?? '')))
                        ?? trim((string) ($currentEmployee['jenis_ketenagaan'] ?? ''));
                }
                (new EmployeeModel())->update($employeeId, ['jenis_ketenagaan' => $workforce]);
            }
        }

        // Refresh D/E/F from the history tables that were on the page.
        $summaryLetters = [];
        foreach (EmployeeProfileData::summarySources() as $letter => $source) {
            $posted = $source['history'] === 'education'
                ? $this->request->getPost('education_section') !== null
                : in_array($source['history'], $postedSections, true);
            if ($posted) {
                $summaryLetters[] = $letter;
            }
        }
        if ($summaryLetters !== []) {
            (new EmployeeModel())->syncSummaries($employeeId, $summaryLetters);
        }
    }

    private function visibleEmployee(int $employeeId): ?array
    {
        return (new EmployeeModel())->findVisibleById($employeeId, EmployeeAccess::user());
    }

    /** Validate newly selected positions while leaving unchanged historical values intact. */
    private function jobSelectionError(?array $employee = null): ?string
    {
        $catalog = new JobTitleModel();
        $postedSections = array_map('strval', (array) $this->request->getPost('history_sections'));
        if (! in_array('jabatan', $postedSections, true)) {
            return $employee === null ? 'Tambahkan minimal satu riwayat jabatan dengan jenis ketenagaan dan nama jabatan.' : null;
        }

        $existing = [];
        if ($employee !== null) {
            foreach ((new EmployeeHistoryModel())->forEmployee((int) $employee['id'], 'jabatan') as $record) {
                $existing[(int) $record['id']] = $record;
            }
        }
        $postedHistories = (array) $this->request->getPost('histories');
        $hasValidPosition = false;
        foreach ((array) ($postedHistories['jabatan'] ?? []) as $record) {
            if (! is_array($record)) {
                continue;
            }
            $recordId = (int) ($record['id'] ?? 0);
            $group = is_scalar($record['jenis_ketenagaan'] ?? null) ? trim((string) $record['jenis_ketenagaan']) : '';
            $title = is_scalar($record['nama_jabatan'] ?? null) ? trim((string) $record['nama_jabatan']) : '';
            $originalTitle = trim((string) ($existing[$recordId]['nama_jabatan'] ?? ''));
            $originalGroup = trim((string) ($existing[$recordId]['jenis_ketenagaan'] ?? ''));
            if ($originalGroup === '' && $originalTitle !== '') {
                $originalGroup = $catalog->canonicalGroup($originalTitle) ?? trim((string) ($employee['jenis_ketenagaan'] ?? ''));
            }
            if ($title === '') {
                $hasData = false;
                foreach ($record as $key => $value) {
                    if ($key !== 'id' && is_scalar($value) && trim((string) $value) !== '') {
                        $hasData = true;
                        break;
                    }
                }
                if ($hasData && ! isset($existing[$recordId])) {
                    return 'Isi jenis ketenagaan dan nama jabatan pada setiap riwayat jabatan baru.';
                }
                continue;
            }
            if ($catalog->isValidPair($group, $title)) {
                $hasValidPosition = true;
                continue;
            }
            if ($originalTitle !== '' && $title === $originalTitle && mb_strtolower($group) === mb_strtolower($originalGroup)) {
                $hasValidPosition = true;
                continue;
            }
            return 'Nama jabatan harus sesuai dengan jenis ketenagaan pada baris jabatan yang sama.';
        }

        if ($employee === null && ! $hasValidPosition) {
            return 'Pilih jenis ketenagaan dan nama jabatan pada bagian Riwayat Jabatan.';
        }

        return null;
    }

    public function update(int $employeeId)
    {
        $employeeModel = new EmployeeModel();
        $employee = $this->visibleEmployee($employeeId);
        $user = EmployeeAccess::user();
        if ($employee === null || ! EmployeeAccess::can(($user['role'] ?? '') === 'pegawai' ? 'profile.update' : 'employees.update', $employee)) {
            return redirect()->to('/data-pegawai')->with('error', 'Anda tidak memiliki hak untuk mengubah data pegawai ini.');
        }

        $linkedUserId = (int) ($employee['user_id'] ?? 0);
        $creatingEmployeeAccount = $linkedUserId === 0 && ($user['role'] ?? '') !== 'pegawai'
            && $this->request->getPost('account_username') !== null;
        $rules = ['email' => 'permit_empty|valid_email|max_length[190]'];
        if ($this->request->getPost('nama') !== null) {
            $rules['nama'] = 'required|min_length[3]|max_length[150]';
        }
        if ($creatingEmployeeAccount) {
            $rules['account_username'] = 'required|min_length[4]|max_length[80]|alpha_numeric_punct';
            $rules['tanggal_lahir'] = 'required|valid_date[Y-m-d]';
        }
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        if ($jobError = $this->jobSelectionError($employee)) {
            return redirect()->back()->withInput()->with('error', $jobError);
        }

        $payload = (array) $this->request->getPost();
        $accountUsername = trim((string) ($payload['account_username'] ?? ''));
        unset($payload['education'], $payload['histories'], $payload['history_sections'], $payload['csrf_test_name'], $payload['account_username']);
        if (($user['role'] ?? '') === 'pegawai') {
            unset($payload['nip'], $payload['nip_lama'], $payload['jenis_kepegawaian'], $payload['employee_status'], $payload['kedudukan']);
        }
        foreach ([
            'tanggal_lahir', 'tanggal_masuk', 'sip_terbit', 'sip_berlaku', 'str_terbit', 'str_berlaku',
            'cpns_tanggal_nota', 'cpns_tanggal_sk', 'cpns_tmt', 'cpns_tmt_honorer',
            'pns_tanggal_sk', 'pns_tmt', 'pangkat_tanggal', 'pangkat_tmt', 'jabatan_tanggal', 'jabatan_tmt',
            'pendidikan_terakhir_tanggal_ijazah',
        ] as $dateField) {
            if (array_key_exists($dateField, $payload) && $payload[$dateField] === '') {
                $payload[$dateField] = null;
            }
        }

        if (isset($payload['nip']) && $payload['nip'] !== $employee['nip'] && $employeeModel->where('nip', $payload['nip'])->where('id !=', $employeeId)->first() !== null) {
            return redirect()->back()->withInput()->with('error', 'NIP sudah digunakan pegawai lain.');
        }

        $userModel = new UserModel();
        $accountEmail = '';
        $initialPassword = '';
        if ($creatingEmployeeAccount) {
            $payload['email'] = strtolower(trim((string) ($payload['email'] ?? '')));
            $accountEmail = $payload['email'] !== ''
                ? $payload['email']
                : 'pegawai.' . bin2hex(random_bytes(16)) . '@simkepegawaian.local';
            $accountNip = trim((string) ($payload['nip'] ?? $employee['nip']));
            if (
                $userModel->findForLogin($accountUsername) !== null
                || $userModel->findForLogin($accountEmail) !== null
                || $userModel->findForLogin($accountNip) !== null
            ) {
                return redirect()->back()->withInput()->with('error', 'Username, email, atau NIP sudah dipakai akun lain.');
            }

            $birthDate = trim((string) ($payload['tanggal_lahir'] ?? ''));
            $birthDateValue = \DateTimeImmutable::createFromFormat('!Y-m-d', $birthDate);
            if ($birthDateValue === false || $birthDateValue->format('Y-m-d') !== $birthDate) {
                return redirect()->back()->withInput()->with('error', 'Tanggal lahir tidak valid.');
            }
            $initialPassword = $birthDateValue->format('dmY');
        }

        if ($linkedUserId > 0 && isset($payload['email'])) {
            $payload['email'] = strtolower(trim((string) $payload['email']));
            $existingEmail = $userModel->where('email', $payload['email'])->where('id !=', $linkedUserId)->first();
            if ($existingEmail !== null) {
                return redirect()->back()->withInput()->with('error', 'Email sudah digunakan akun lain.');
            }
        }

        $db = db_connect();
        $db->transBegin();
        try {
            if (! $employeeModel->updateFromForm($employeeId, $payload)) {
                throw new \RuntimeException('Data pegawai gagal disimpan.');
            }

            if ($creatingEmployeeAccount) {
                $updatedEmployee = $employeeModel->find($employeeId);
                $newUserId = (int) $userModel->insert([
                    'employee_id' => $employeeId,
                    'name' => $updatedEmployee['nama'],
                    'username' => $accountUsername,
                    'email' => $accountEmail,
                    'nip' => $updatedEmployee['nip'],
                    'password_hash' => password_hash($initialPassword, PASSWORD_DEFAULT),
                    'role' => 'pegawai',
                    'status' => 'active',
                    'permissions' => json_encode([], JSON_THROW_ON_ERROR),
                ], true);
                if ($newUserId < 1 || ! $employeeModel->update($employeeId, ['user_id' => $newUserId])) {
                    throw new \RuntimeException('Akun pegawai gagal dibuat atau ditautkan.');
                }
            }

            $this->saveRepeatableSections($employeeId);
            (new EmployeeDocumentModel())->syncCatalog($employeeId, (int) $user['id']);

            $updatedEmployee = $employeeModel->find($employeeId);
            if ((int) ($updatedEmployee['user_id'] ?? 0) > 0) {
                $userChanges = [
                    'name' => $updatedEmployee['nama'],
                    'nip' => $updatedEmployee['nip'],
                ];
                if (trim((string) $updatedEmployee['email']) !== '') {
                    $userChanges['email'] = strtolower(trim((string) $updatedEmployee['email']));
                }
                $userModel->update((int) $updatedEmployee['user_id'], $userChanges);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Perubahan tidak dapat disimpan.');
            }
            $db->transCommit();
        } catch (\Throwable $exception) {
            $db->transRollback();
            log_message('error', 'Employee update failed: {message}', ['message' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Data pegawai gagal disimpan. Periksa kembali isian wajib dan keunikan NIP/email.');
        }

        if ($creatingEmployeeAccount) {
            return redirect()->back()->with(
                'success',
                'Akun pegawai berhasil dibuat. Username: ' . $accountUsername . '. Password awal: ' . $initialPassword . ' (format ddmmyyyy dari tanggal lahir). Pegawai dapat menggantinya melalui menu Ubah Password.',
            );
        }

        return redirect()->back()->with('success', 'Data pegawai, riwayat, dan pendidikan berhasil disimpan.');
    }

    /**
     * Marks an employee as retired once the TMT pensiun has arrived: status and kedudukan become
     * "Pensiun" and the linked login account is disabled. The employee's data stays in place.
     */
    public function retire(int $employeeId)
    {
        $employee = $this->visibleEmployee($employeeId);
        if ($employee === null || ! EmployeeAccess::can('employees.update', $employee) || (EmployeeAccess::user()['role'] ?? '') === 'pegawai') {
            return redirect()->to('/pensiun')->with('error', 'Anda tidak memiliki hak untuk menetapkan pensiun pegawai ini.');
        }
        return redirect()->to('/pegawai-nonaktif')->with('error', 'Untuk menetapkan ' . $employee['nama'] . ' pensiun, gunakan form Data Pegawai Nonaktif dan lampirkan surat resmi.');
    }

    public function updateRetirementBup()
    {
        if ((EmployeeAccess::user()['role'] ?? '') !== 'superadmin') {
            return redirect()->to('/pegawai-nonaktif')->with('error', 'Hanya Super Admin yang dapat mengubah BUP.');
        }
        $model = new RetirementBupModel();
        if (! $model->saveAges((array) $this->request->getPost('bup'))) {
            return redirect()->to('/pegawai-nonaktif')->with('error', 'Nilai BUP harus berupa angka antara 50 sampai 70 tahun.');
        }
        return redirect()->to('/pegawai-nonaktif')->with('success', 'Pengaturan BUP berhasil diperbarui.');
    }

    public function nonaktifkan()
    {
        $categories = [
            'Pensiun' => 'pensiun',
            'Mutasi Keluar' => 'mutasi-keluar',
            'Habis Kontrak' => 'habis-kontrak',
            'Mengundurkan Diri' => 'mengundurkan-diri',
        ];
        $employeeId = (int) $this->request->getPost('employee_id');
        $category = trim((string) $this->request->getPost('kategori'));
        $employee = $this->visibleEmployee($employeeId);
        $redirectUrl = '/pegawai-nonaktif';
        if ($employee === null || ! EmployeeAccess::can('employees.update', $employee) || (EmployeeAccess::user()['role'] ?? '') === 'pegawai') {
            return redirect()->to($redirectUrl)->with('error', 'Anda tidak memiliki hak untuk menonaktifkan pegawai ini.');
        }
        if (! isset($categories[$category])) {
            return redirect()->to($redirectUrl)->with('error', 'Kategori pegawai nonaktif tidak valid.');
        }

        $file = $this->request->getFile('surat');
        $directory = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'employee-documents' . DIRECTORY_SEPARATOR . $employeeId;
        if ($file === null || ! $file->isValid() || $file->getError() !== UPLOAD_ERR_OK
            || strtolower($file->getExtension()) !== 'pdf' || $file->getMimeType() !== 'application/pdf'
            || $file->getSizeByUnit('mb') > 10) {
            return redirect()->to($redirectUrl)->with('error', 'Unggah surat resmi dalam format PDF, maksimal 10 MB.');
        }
        if (! is_dir($directory) && ! mkdir($directory, 0750, true) && ! is_dir($directory)) {
            return redirect()->to($redirectUrl)->with('error', 'Folder penyimpanan surat tidak dapat dibuat.');
        }
        $storedName = $file->getRandomName();
        $file->move($directory, $storedName);

        $documents = new EmployeeDocumentModel();
        $existing = $documents->where('employee_id', $employeeId)->where('document_key', 'surat-nonaktif')->first();
        $db = db_connect();
        $db->transStart();
        $recordData = [
            'employee_id' => $employeeId,
            'education_id' => null,
            'group_key' => 'nonaktif',
            'document_key' => 'surat-nonaktif',
            'title' => 'Surat ' . $category,
            'description' => 'Surat keterangan pegawai nonaktif.',
            'original_name' => mb_substr(basename($file->getClientName()), 0, 255),
            'stored_path' => 'employee-documents/' . $employeeId . '/' . $storedName,
            'mime_type' => 'application/pdf',
            'file_size' => $file->getSize(),
            'is_required' => 1,
            'verification_status' => 'verified',
            'verification_note' => null,
            'verified_by' => (int) (EmployeeAccess::user()['id'] ?? 0),
            'verified_at' => date('Y-m-d H:i:s'),
            'uploaded_by' => (int) (EmployeeAccess::user()['id'] ?? 0),
        ];
        if ($existing === null) {
            $documents->insert($recordData);
        } else {
            $documents->update((int) $existing['id'], $recordData);
        }
        (new EmployeeModel())->update($employeeId, [
            'employee_status' => $category,
            'kedudukan' => $category,
        ]);
        if ((int) ($employee['user_id'] ?? 0) > 0) {
            (new UserModel())->update((int) $employee['user_id'], ['status' => 'disabled']);
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            @unlink($directory . DIRECTORY_SEPARATOR . $storedName);
            return redirect()->to($redirectUrl)->with('error', 'Status nonaktif dan surat gagal disimpan.');
        }
        if ($existing !== null && ! empty($existing['stored_path'])) {
            $oldPath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $existing['stored_path']);
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return redirect()->to($redirectUrl)->with('success', $employee['nama'] . ' dicatat sebagai ' . mb_strtolower($category) . ' dan akun login dinonaktifkan.');
    }

    public function uploadDocument(int $employeeId, string $documentKey)
    {
        $employee = $this->visibleEmployee($employeeId);
        if ($employee === null || ! EmployeeAccess::can('documents.manage', $employee)) {
            return $this->json(['message' => 'Anda tidak memiliki hak untuk mengunggah dokumen.'], 403);
        }

        $educationId = $this->request->getGet('education_id') ?? $this->request->getPost('education_id');
        $educationId = $educationId !== null && $educationId !== '' ? (int) $educationId : null;
        $catalogItem = EmployeeProfileData::documentCatalog()[$documentKey] ?? null;
        $hasExpiry = ! empty($catalogItem['has_expiry']);
        $expiresAt = trim((string) $this->request->getPost('expires_at'));
        if ($expiresAt !== '') {
            $expiryDate = \DateTimeImmutable::createFromFormat('!Y-m-d', $expiresAt);
            if (! $hasExpiry || $expiryDate === false || $expiryDate->format('Y-m-d') !== $expiresAt) {
                return $this->json(['message' => 'Tanggal berlaku tidak valid.'], 422);
            }
        }

        $file = $this->request->getFile('document');
        $hasUpload = $file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE;
        if (! $hasUpload && $expiresAt !== '') {
            // Only the expiry date of an already uploaded document changes.
            $documents = new EmployeeDocumentModel();
            $record = $documents->uploadTarget($employeeId, $documentKey, $educationId);
            if ($record === null || $record['stored_path'] === '') {
                return $this->json(['message' => 'Unggah dokumen terlebih dahulu sebelum mengubah tanggal berlaku.'], 422);
            }
            $documents->update((int) $record['id'], ['expires_at' => $expiresAt]);
            return $this->json(['message' => 'Tanggal berlaku tersimpan.']);
        }
        if ($file === null || ! $file->isValid() || $file->hasMoved()) {
            return $this->json(['message' => 'Pilih file yang valid untuk diunggah.'], 422);
        }
        if ($hasExpiry && $expiresAt === '') {
            return $this->json(['message' => 'Isi tanggal berlaku (jatuh tempo) dokumen ini.'], 422);
        }
        if ($file->getSize() > 10 * 1024 * 1024) {
            return $this->json(['message' => 'Ukuran file maksimal 10 MB.'], 422);
        }

        $mimeType = $file->getMimeType();
        $extensions = [
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];
        if (! isset($extensions[$mimeType])) {
            return $this->json(['message' => 'Format yang diizinkan hanya PDF, JPG, dan PNG.'], 422);
        }

        $historyId = $this->request->getGet('history_id');
        $historyId = $historyId !== null && $historyId !== '' ? (int) $historyId : null;
        if ($historyId !== null) {
            $childHistory = (new EmployeeHistoryModel())->where('employee_id', $employeeId)
                ->where('category', 'anak')->find($historyId);
            if ($childHistory === null || $educationId !== null || $documentKey !== 'anak-' . $historyId . '-file') {
                return $this->json(['message' => 'Data anak untuk lampiran tidak ditemukan.'], 404);
            }
        } elseif (str_starts_with($documentKey, 'anak-')) {
            return $this->json(['message' => 'Data anak untuk lampiran tidak ditemukan.'], 404);
        }
        if ($educationId !== null && (new EmployeeEducationModel())->where('employee_id', $employeeId)->find($educationId) === null) {
            return $this->json(['message' => 'Riwayat pendidikan tidak ditemukan.'], 404);
        }
        $documents = new EmployeeDocumentModel();
        $record = $documents->uploadTarget($employeeId, $documentKey, $educationId);
        if ($record === null) {
            return $this->json(['message' => 'Dokumen ini tidak termasuk dalam daftar dokumen pegawai.'], 404);
        }

        $directory = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'employee-documents' . DIRECTORY_SEPARATOR . $employeeId;
        if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
            return $this->json(['message' => 'Folder penyimpanan dokumen tidak dapat dibuat.'], 500);
        }
        $storedName = bin2hex(random_bytes(20)) . '.' . $extensions[$mimeType];
        try {
            $file->move($directory, $storedName);
        } catch (\Throwable $exception) {
            log_message('error', 'Document upload failed: {message}', ['message' => $exception->getMessage()]);
            return $this->json(['message' => 'Dokumen gagal disimpan.'], 500);
        }

        $relativePath = 'employee-documents/' . $employeeId . '/' . $storedName;
        $saved = $documents->update((int) $record['id'], [
            'original_name' => basename($file->getClientName()),
            'stored_path' => $relativePath,
            'mime_type' => $mimeType,
            'file_size' => (int) $file->getSize(),
            'verification_status' => 'pending',
            'expires_at' => $hasExpiry ? $expiresAt : null,
            'uploaded_by' => (int) EmployeeAccess::user()['id'],
        ]);
        if (! $saved) {
            @unlink($directory . DIRECTORY_SEPARATOR . $storedName);
            return $this->json(['message' => 'File terunggah, tetapi catatan database gagal disimpan.'], 500);
        }

        return $this->json([
            'message' => 'Dokumen berhasil disimpan.',
            'document' => [
                'url' => base_url('/documents/' . $record['id']),
                'name' => basename($file->getClientName()),
            ],
            'csrfHash' => csrf_hash(),
        ]);
    }

    public function showDocument(int $documentId)
    {
        $record = (new EmployeeDocumentModel())->find($documentId);
        if ($record === null || ($record['stored_path'] ?? '') === '') {
            return $this->response->setStatusCode(404)->setBody('Dokumen tidak ditemukan.');
        }
        $employee = $this->visibleEmployee((int) $record['employee_id']);
        if ($employee === null || (! EmployeeAccess::can('employees.view', $employee) && ! EmployeeAccess::can('documents.manage', $employee))) {
            return $this->response->setStatusCode(403)->setBody('Akses dokumen ditolak.');
        }

        $relativePath = str_replace('\\', '/', (string) $record['stored_path']);
        if (str_contains($relativePath, '..') || ! str_starts_with($relativePath, 'employee-documents/')) {
            return $this->response->setStatusCode(404)->setBody('Dokumen tidak ditemukan.');
        }
        $path = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        if (! is_file($path) || ! is_readable($path)) {
            return $this->response->setStatusCode(404)->setBody('File dokumen tidak ditemukan.');
        }
        $contents = file_get_contents($path);
        if ($contents === false) {
            return $this->response->setStatusCode(500)->setBody('File dokumen tidak dapat dibaca.');
        }

        return $this->response
            ->setStatusCode(200)
            ->setContentType($record['mime_type'] ?: 'application/octet-stream')
            ->setHeader('Content-Disposition', "inline; filename*=UTF-8''" . rawurlencode($record['original_name']))
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'private, no-store')
            ->setBody($contents);
    }

    public function verifyDocument(int $documentId)
    {
        $documents = new EmployeeDocumentModel();
        $record = $documents->find($documentId);
        $user = EmployeeAccess::user();
        if ($record === null || $record['verification_status'] !== 'pending' || ! in_array($user['role'] ?? '', ['superadmin', 'admin_unit'], true)) {
            return redirect()->to('/aktivitas')->with('error', 'Anda tidak memiliki hak untuk memverifikasi dokumen ini.');
        }
        $employee = $this->visibleEmployee((int) $record['employee_id']);
        if ($employee === null || ! EmployeeAccess::can('documents.manage', $employee)) {
            return redirect()->to('/aktivitas')->with('error', 'Dokumen berada di luar unit yang dapat Anda kelola.');
        }

        $status = $this->request->getPost('verification_status');
        if (! in_array($status, ['verified', 'rejected'], true)) {
            return redirect()->to('/aktivitas')->with('error', 'Status verifikasi tidak valid.');
        }
        // A request for correction must say what to fix; the employee sees this note on the document.
        $note = trim((string) $this->request->getPost('verification_note'));
        if ($status === 'rejected' && mb_strlen($note) < 5) {
            return redirect()->to('/aktivitas')->with('error', 'Tulis catatan perbaikan (minimal 5 karakter) agar pegawai tahu apa yang harus diperbaiki.');
        }
        $documents->update($documentId, [
            'verification_status' => $status,
            'verification_note' => $status === 'rejected' ? mb_substr($note, 0, 1000) : null,
            'verified_by' => (int) ($user['id'] ?? 0) ?: null,
            'verified_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/aktivitas')->with('success', $status === 'verified'
            ? 'Dokumen ' . $employee['nama'] . ' berhasil diverifikasi.'
            : 'Dokumen ' . $employee['nama'] . ' ditandai perlu perbaikan; catatan dikirim ke pegawai.');
    }

    private function json(array $payload, int $status = 200)
    {
        $payload['csrfHash'] = csrf_hash();
        return $this->response->setStatusCode($status)->setJSON($payload);
    }
}
