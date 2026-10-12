<?php

namespace App\Models;

use App\Libraries\EmployeeProfileData;
use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'unit_id', 'user_id', 'nip', 'nip_lama', 'nik', 'gelar_depan', 'gelar_belakang',
        'nama', 'tempat_lahir', 'tanggal_lahir', 'email', 'jenis_kelamin', 'agama',
        'status_perkawinan', 'jenis_kepegawaian', 'employee_status', 'kedudukan', 'jalan',
        'rt_rw', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi', 'telepon', 'telepon_darurat_hubungan',
        'telepon_darurat', 'karpeg', 'kartu_asn', 'bpjs', 'taspen', 'karis_karsu', 'npwp',
        'alamat_rumah', 'cpns_nomor_nota', 'cpns_tanggal_nota', 'cpns_nomor_sk', 'cpns_tanggal_sk',
        'cpns_pejabat', 'cpns_tmt', 'cpns_pangkat_golongan', 'cpns_tmt_honorer', 'pns_nomor_sk',
        'pns_tanggal_sk', 'pns_pejabat', 'pns_tmt', 'pns_pangkat_golongan', 'pns_sumpah_janji',
        'pendidikan_terakhir_tingkat', 'pendidikan_terakhir_nama', 'pendidikan_terakhir_fakultas',
        'pendidikan_terakhir_instansi', 'pendidikan_terakhir_status', 'pendidikan_terakhir_tempat',
        'pendidikan_terakhir_pejabat', 'pendidikan_terakhir_nomor_ijazah', 'pendidikan_terakhir_tanggal_ijazah',
        'pendidikan_terakhir_nilai',
        'jenis_ketenagaan', 'sip_nomor', 'sip_terbit', 'sip_berlaku', 'str_nomor',
        'str_terbit', 'str_berlaku', 'kredensial_nomor', 'kredensial_berlaku',
        'kewenangan', 'penugasan_klinis', 'evaluasi_terakhir', 'kompetensi',
        'pelatihan_jpl', 'jpl_completed', 'nilai_skp', 'jenjang_jabatan', 'pangkat_nomor',
        'pangkat_tanggal', 'pangkat_tmt', 'pangkat_nama', 'masa_kerja', 'pangkat_pejabat',
        'jabatan_nomor', 'jabatan_tanggal', 'jabatan_tmt', 'jabatan_nama', 'unit_kerja',
        'eselon', 'jabatan_pejabat',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    /**
     * Column filters supported by visibleTo(), keyed by request parameter.
     */
    public const LIST_FILTER_COLUMNS = [
        'filter_bangsal' => 'units.name',
        'filter_status' => 'employees.jenis_kepegawaian',
        'filter_ketenagaan' => 'employees.jenis_ketenagaan',
        'filter_status_data' => 'employees.employee_status',
        'filter_jabatan' => 'employees.jabatan_nama',
        'filter_jenjang' => 'employees.jenjang_jabatan',
    ];

    /**
     * Restricts a query builder to the employees the user may see.
     * Returns false when the user can see nobody.
     */
    private function scopeVisibleTo($builder, array $user): bool
    {
        if (($user['role'] ?? '') === 'superadmin') {
            return true;
        }
        if (($user['role'] ?? '') === 'admin_unit') {
            $unitIds = array_map('intval', $user['unit_ids'] ?? []);
            if ($unitIds === []) {
                return false;
            }
            $builder->whereIn('employees.unit_id', $unitIds);
            return true;
        }

        $employeeId = (int) ($user['employee_id'] ?? 0);
        if ($employeeId === 0) {
            return false;
        }
        $builder->where('employees.id', $employeeId);
        return true;
    }

    public function visibleTo(array $user, array $filters = []): array
    {
        $builder = $this->select('employees.*, units.name AS unit_name')
            ->join('units', 'units.id = employees.unit_id', 'left')
            ->orderBy('employees.nama');

        if (! $this->scopeVisibleTo($builder, $user)) {
            $builder->resetQuery();
            return [];
        }

        $q = trim((string) ($filters['q'] ?? ''));
        if ($q !== '') {
            $builder->groupStart()
                ->like('employees.nama', $q)
                ->orLike('employees.nip', $q)
                ->groupEnd();
        }
        foreach (self::LIST_FILTER_COLUMNS as $key => $column) {
            $value = trim((string) ($filters[$key] ?? ''));
            if ($value !== '') {
                $builder->where($column, $value);
            }
        }
        $jpl = $filters['filter_jpl'] ?? '';
        if ($jpl === 'kurang') {
            $builder->groupStart()->where('employees.jpl_completed <', 20)->orWhere('employees.jpl_completed', null)->groupEnd();
        } elseif ($jpl === 'terpenuhi') {
            $builder->where('employees.jpl_completed >=', 20);
        }

        return $builder->findAll();
    }

    /**
     * Distinct non-empty values per filter column, limited to employees the user may see.
     */
    public function filterOptions(array $user): array
    {
        $options = [];
        foreach (self::LIST_FILTER_COLUMNS as $key => $column) {
            $builder = $this->db->table('employees')
                ->distinct()
                ->select($column . ' AS value')
                ->join('units', 'units.id = employees.unit_id', 'left')
                ->where($column . ' IS NOT NULL')
                ->where($column . ' !=', '')
                ->orderBy($column);
            $options[$key] = $this->scopeVisibleTo($builder, $user)
                ? array_column($builder->get()->getResultArray(), 'value')
                : [];
        }

        return $options;
    }

    public function findVisibleById(int $employeeId, array $user): ?array
    {
        foreach ($this->visibleTo($user) as $employee) {
            if ((int) $employee['id'] === $employeeId) {
                return $employee;
            }
        }

        return null;
    }

    public function bundle(int $employeeId, bool $editable = false): ?array
    {
        $row = $this->select('employees.*, units.name AS unit_name')
            ->join('units', 'units.id = employees.unit_id', 'left')
            ->find($employeeId);
        if ($row === null) {
            return null;
        }

        $employee = $row;
        $employee['status'] = $row['employee_status'];
        $employee['alamat_rumah'] = trim((string) ($row['alamat_rumah'] ?? '')) !== ''
            ? $row['alamat_rumah']
            : implode(', ', array_filter([
                $row['jalan'] ?? '',
                ($row['rt_rw'] ?? '') === '' ? '' : 'RT/RW ' . $row['rt_rw'],
                $row['kelurahan'] ?? '',
                $row['kecamatan'] ?? '',
                $row['kabupaten'] ?? '',
                $row['provinsi'] ?? '',
            ]));
        $employee['alamat'] = $employee['alamat_rumah'];
        $employee['unit_kerja'] = trim((string) ($row['unit_kerja'] ?? '')) !== '' ? $row['unit_kerja'] : ($row['unit_name'] ?? '');
        $educationFieldMap = [
            'pendidikan_terakhir_tingkat' => 'tingkat',
            'pendidikan_terakhir_nama' => 'nama',
            'pendidikan_terakhir_fakultas' => 'fakultas',
            'pendidikan_terakhir_instansi' => 'instansi',
            'pendidikan_terakhir_status' => 'status',
            'pendidikan_terakhir_tempat' => 'tempat',
            'pendidikan_terakhir_pejabat' => 'pejabat',
            'pendidikan_terakhir_nomor_ijazah' => 'nomor_ijazah',
            'pendidikan_terakhir_tanggal_ijazah' => 'tanggal_ijazah',
            'pendidikan_terakhir_nilai' => 'nilai',
        ];

        $professionKeys = [
            'sip_nomor', 'sip_terbit', 'sip_berlaku', 'str_nomor', 'str_terbit',
            'str_berlaku', 'kredensial_nomor', 'kredensial_berlaku', 'kewenangan',
            'penugasan_klinis', 'evaluasi_terakhir', 'kompetensi', 'pelatihan_jpl', 'jpl_completed', 'nilai_skp',
        ];
        $careerKeys = [
            'jenis_ketenagaan', 'jenjang_jabatan', 'pangkat_nomor', 'pangkat_tanggal',
            'pangkat_tmt', 'pangkat_nama', 'masa_kerja', 'pangkat_pejabat',
            'jabatan_nomor', 'jabatan_tanggal', 'jabatan_tmt', 'jabatan_nama',
            'unit_kerja', 'eselon', 'jabatan_pejabat',
        ];
        $profession = array_intersect_key($row, array_flip($professionKeys));
        $career = array_intersect_key($row, array_flip($careerKeys));

        $educationModel = new EmployeeEducationModel();
        $documents = new EmployeeDocumentModel();
        $educationRecords = $educationModel->forEmployee($employeeId);
        if ($educationRecords !== []) {
            $current = $educationRecords[0];
            foreach ($educationFieldMap as $employeeKey => $educationKey) {
                if (trim((string) ($employee[$employeeKey] ?? '')) === '') {
                    $employee[$employeeKey] = $current[$educationKey] ?? '';
                }
            }
        }
        $employee['pendidikan_terakhir'] = $employee['pendidikan_terakhir_tingkat'] ?? '';
        $employee['nama_pendidikan'] = $employee['pendidikan_terakhir_nama'] ?? '';
        $employee['institusi_pendidikan'] = $employee['pendidikan_terakhir_instansi'] ?? '';

        $officialHistories = [];
        $history = new EmployeeHistoryModel();
        foreach (EmployeeProfileData::officialHistorySections() as $category => $section) {
            $records = $history->forEmployee($employeeId, $category);
            if ($category === 'anak') {
                foreach ($records as &$record) {
                    $record['dokumen'] = $documents->historyAttachmentFor($employeeId, (int) $record['id'], $editable);
                }
                unset($record);
            }
            $officialHistories[$category] = $records;
        }


        return [
            'id' => $employeeId,
            'pegawai' => $employee,
            'karir' => $career,
            'profesi' => $profession,
            'pendidikan' => $educationRecords,
            'histories' => $officialHistories,
            'checklist' => $documents->checklistFor($row, $editable),
            'riwayat_kepangkatan' => $history->forEmployee($employeeId, 'kepangkatan'),
            'riwayat_jabatan' => $history->forEmployee($employeeId, 'jabatan'),
            'keluarga' => $history->forEmployee($employeeId, 'keluarga'),
            'pengalaman' => $history->forEmployee($employeeId, 'pengalaman'),
            'sanksi' => $history->forEmployee($employeeId, 'sanksi'),
        ];
    }

    /**
     * Copies the newest row of G/H/J into sections D/E/F (see EmployeeProfileData::summarySources()).
     * With $clearWhenEmpty, an empty history also empties the summary; otherwise it is left as is.
     */
    public function syncSummaries(int $employeeId, array $letters = ['D', 'E', 'F'], bool $clearWhenEmpty = true): void
    {
        $history = new EmployeeHistoryModel();
        $changes = [];
        foreach (EmployeeProfileData::summarySources() as $letter => $source) {
            if (! in_array($letter, $letters, true)) {
                continue;
            }
            $latest = $source['history'] === 'education'
                ? EmployeeProfileData::latestEducationRow((new EmployeeEducationModel())->where('employee_id', $employeeId)->findAll())
                : EmployeeProfileData::latestHistoryRow($history->forEmployee($employeeId, $source['history']));
            if ($latest === null && ! $clearWhenEmpty) {
                continue;
            }
            foreach ($source['map'] as $summaryField => $historyField) {
                $value = $latest[$historyField] ?? null;
                $value = $value === null ? '' : trim((string) $value);
                $isDate = str_contains($summaryField, 'tanggal') || str_contains($summaryField, 'tmt');
                $changes[$summaryField] = $isDate && $value === '' ? null : $value;
            }
        }
        if ($changes !== []) {
            $this->update($employeeId, $changes);
        }
    }

    public function updateFromForm(int $employeeId, array $input): bool
    {
        $fields = array_fill_keys($this->allowedFields, true);
        unset($fields['unit_id'], $fields['user_id']);
        // Summary fields (D/E/F) only change through their history tables.
        foreach (EmployeeProfileData::summarySources() as $source) {
            foreach (array_keys($source['map']) as $summaryField) {
                unset($fields[$summaryField]);
            }
        }
        $payload = array_intersect_key($input, $fields);
        if ($payload === []) {
            return true;
        }

        return $this->update($employeeId, $payload);
    }

    public function registerProfile(array $data): int
    {
        $data['employee_status'] = $data['employee_status'] ?? 'Aktif';
        $data['kedudukan'] = $data['kedudukan'] ?? 'Aktif';
        return (int) $this->insert($data, true);
    }

    public function documentSummary(int $employeeId): array
    {
        return (new EmployeeDocumentModel())->verifiedRequiredCount($employeeId);
    }
}
