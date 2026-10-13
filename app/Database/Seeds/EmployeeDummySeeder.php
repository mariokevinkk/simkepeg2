<?php

namespace App\Database\Seeds;

use App\Models\EmployeeDocumentModel;
use App\Models\EmployeeEducationModel;
use App\Models\EmployeeHistoryModel;
use App\Models\EmployeeModel;
use App\Models\UnitModel;
use App\Models\UserModel;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class EmployeeDummySeeder extends Seeder
{
    private array $namesByUnit = [
        'Bangsal Anggrek' => ['Aditya Pratama', 'Rani Wulandari', 'Fajar Nugroho', 'Maya Puspitasari', 'Rizky Kurniawan'],
        'Bangsal Mawar' => ['Bima Saputra', 'Lestari Ayu', 'Dwi Cahyani', 'Yanuar Setiawan', 'Intan Maharani'],
        'IGD' => ['Gilang Ramadhan', 'Anisa Putri', 'Bayu Firmansyah', 'Melati Safitri', 'Yoga Prakoso'],
        'ICU' => ['Arif Wicaksono', 'Nadia Permata', 'Rendra Mahardika', 'Tika Handayani', 'Satrio Wijaya'],
        'Poli Umum' => ['Farhan Akbar', 'Vina Oktaviani', 'Reza Maulana', 'Fitri Amalia', 'Danu Prabowo'],
        'Instalasi Farmasi' => ['Nanda Kusuma', 'Ayu Laksmi', 'Bagas Pamungkas', 'Sheila Nirmala', 'Tegar Adinata'],
    ];

    public function run()
    {
        $units = (new UnitModel())->activeUnits();
        if ($units === []) {
            CLI::write('Tidak ada unit aktif. Jalankan InitialSeeder terlebih dahulu.', 'yellow');
            return;
        }

        $employeeModel = new EmployeeModel();
        $educationModel = new EmployeeEducationModel();
        $documentModel = new EmployeeDocumentModel();
        $userModel = new UserModel();
        $db = db_connect();
        $db->transBegin();
        $added = 0;
        $refreshed = 0;
        $linked = 0;

        try {
            foreach ($units as $unit) {
                $unitId = (int) $unit['id'];
                $unitName = (string) $unit['name'];
                $names = $this->namesByUnit[$unitName] ?? array_map(
                    static fn (int $number): string => 'Pegawai Contoh ' . $number . ' Unit ' . $unitId,
                    range(1, 5),
                );
                $jobs = $this->jobsForUnit($unitName);

                for ($index = 0; $index < 5; $index++) {
                    $sequence = $index + 1;
                    $nip = sprintf('DUMMY-%04d-%02d', $unitId, $sequence);
                    $employee = $employeeModel->where('nip', $nip)->first();
                    $position = $jobs[$index];
                    $job = $position['title'];
                    $workforce = $position['group'];
                    $employmentType = $this->employmentTypeFor($unitId, $index);
                    $seniorityLevel = $this->seniorityLevelFor($job);
                    $birthDate = $this->birthDate($unitId, $index);
                    $email = sprintf('pegawai.dummy.%04d.%02d@example.test', $unitId, $sequence);

                    if ($employee === null) {
                        $employeeId = (int) $employeeModel->insert([
                            'unit_id' => $unitId,
                            'nip' => $nip,
                            'nama' => $names[$index],
                            'email' => $email,
                            'tempat_lahir' => 'Yogyakarta',
                            'tanggal_lahir' => $birthDate,
                            'jenis_kelamin' => $index % 2 === 0 ? 'Laki-laki' : 'Perempuan',
                            'agama' => ['Islam', 'Islam', 'Kristen', 'Katolik', 'Islam'][$index],
                            'status_perkawinan' => $index < 2 ? 'Belum Kawin' : 'Kawin',
                            'jenis_kepegawaian' => $employmentType,
                            'employee_status' => 'Aktif',
                            'kedudukan' => 'Aktif',
                            'jalan' => 'Jl. Contoh ' . $unitName,
                            'kelurahan' => 'Kelurahan Contoh',
                            'kecamatan' => 'Umbulharjo',
                            'kabupaten' => 'Kota Yogyakarta',
                            'provinsi' => 'DI Yogyakarta',
                            'jenis_ketenagaan' => $workforce,
                            'jabatan_nama' => $job,
                            'jenjang_jabatan' => $seniorityLevel,
                            'unit_kerja' => $unitName,
                        ], true);

                        if ($employeeId < 1) {
                            throw new \RuntimeException('Gagal membuat data contoh pegawai ' . $nip . '.');
                        }

                        $added++;
                    } else {
                        $employeeId = (int) $employee['id'];
                        if (
                            ($employee['jenis_kepegawaian'] ?? '') !== $employmentType
                            || ($employee['jenjang_jabatan'] ?? '') !== $seniorityLevel
                            || ($employee['jenis_ketenagaan'] ?? '') !== $workforce
                            || ($employee['jabatan_nama'] ?? '') !== $job
                        ) {
                            $employeeModel->update($employeeId, [
                                'jenis_kepegawaian' => $employmentType,
                                'jenjang_jabatan' => $seniorityLevel,
                                'jenis_ketenagaan' => $workforce,
                                'jabatan_nama' => $job,
                            ]);
                            $refreshed++;
                        }
                    }

                    $linkedUser = $userModel->where('employee_id', $employeeId)->first();
                    if ($linkedUser === null) {
                        $linkedUser = $userModel->where('nip', $nip)->first();
                    }
                    if ($linkedUser !== null && (int) ($linkedUser['employee_id'] ?? 0) > 0 && (int) $linkedUser['employee_id'] !== $employeeId) {
                        throw new \RuntimeException('NIP dummy ' . $nip . ' sudah terhubung ke akun lain.');
                    }

                    if ($linkedUser === null) {
                        if ($userModel->findForLogin($nip) !== null || $userModel->findForLogin($email) !== null) {
                            throw new \RuntimeException('Username, NIP, atau email untuk pegawai dummy ' . $nip . ' sudah dipakai akun lain.');
                        }
                        $birthDateValue = \DateTimeImmutable::createFromFormat('!Y-m-d', $birthDate);
                        if ($birthDateValue === false) {
                            throw new \RuntimeException('Tanggal lahir pegawai dummy ' . $nip . ' tidak valid.');
                        }

                        $userId = (int) $userModel->insert([
                            'employee_id' => $employeeId,
                            'name' => $names[$index],
                            'username' => $nip,
                            'email' => $email,
                            'nip' => $nip,
                            'password_hash' => password_hash($birthDateValue->format('dmY'), PASSWORD_DEFAULT),
                            'role' => 'pegawai',
                            'status' => 'active',
                            'permissions' => json_encode([], JSON_THROW_ON_ERROR),
                        ], true);
                        if ($userId < 1) {
                            throw new \RuntimeException('Akun pegawai dummy ' . $nip . ' gagal dibuat.');
                        }
                    } else {
                        $userId = (int) $linkedUser['id'];
                        if ((int) ($linkedUser['employee_id'] ?? 0) === 0) {
                            $userModel->update($userId, ['employee_id' => $employeeId]);
                        }
                    }

                    if ((int) ($employee['user_id'] ?? 0) !== $userId) {
                        if (! $employeeModel->update($employeeId, ['user_id' => $userId])) {
                            throw new \RuntimeException('Akun pegawai dummy ' . $nip . ' gagal ditautkan.');
                        }
                        $linked++;
                    }

                    $this->seedEducations($educationModel, $employeeId, $job, $unitId, $index);
                    $this->fillOfficialProfile($employeeModel, $employeeId, $unitName, $job, $employmentType, $unitId, $index);
                    $this->seedHistories($employeeId, $employmentType, $unitName, $job, $index);
                    // D/E/F follow the newest rows of G/H/J.
                    $employeeModel->syncSummaries($employeeId, ['D', 'E', 'F'], false);
                    $documentModel->syncCatalog($employeeId);
                    $this->seedDocuments($documentModel, $employeeId, $index);
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Penyimpanan data dummy pegawai gagal.');
            }
            $db->transCommit();
        } catch (\Throwable $exception) {
            $db->transRollback();
            throw $exception;
        }

        CLI::write(sprintf(
            'Seeder selesai: %d pegawai ditambahkan, %d data diperbarui, dan %d pegawai ditautkan ke akun login untuk %d unit aktif.',
            $added,
            $refreshed,
            $linked,
            count($units),
        ), 'green');
        CLI::write('Akun pegawai dummy memakai NIP sebagai username dan tanggal lahir berformat ddmmyyyy sebagai password awal.', 'light_gray');
    }

    /** Two education rows (SLTA + vocational/university) so section J shows multiple entries. */
    private function seedEducations(EmployeeEducationModel $educationModel, int $employeeId, string $job, int $unitId, int $index): void
    {
        $existing = $educationModel->where('employee_id', $employeeId)->findAll();
        $tingkatExisting = array_map(static fn (array $row): string => $row['tingkat'], $existing);
        $sequence = $index + 1;
        $graduation = $this->graduationDate($unitId, $index);
        $higher = $this->educationForJob($job);
        $rows = [
            [
                'tingkat' => 'SLTA', 'nama' => 'IPA', 'instansi' => 'SMA Negeri ' . (($unitId + $index) % 11 + 1) . ' Yogyakarta',
                'fakultas' => '', 'tempat' => 'Yogyakarta', 'nomor_ijazah' => sprintf('DUMMY-SMA-%04d-%02d', $unitId, $sequence),
                'tanggal_ijazah' => date('Y-m-d', strtotime($graduation . ' -4 years')), 'nilai' => '85,50', 'status' => 'Lulus',
            ],
            $higher + [
                'tempat' => 'Yogyakarta', 'nomor_ijazah' => sprintf('DUMMY-IJZ-%04d-%02d', $unitId, $sequence),
                'tanggal_ijazah' => $graduation, 'nilai' => number_format(3.10 + (($unitId + $index) % 9) / 10, 2, '.', ''),
                'status' => 'Lulus',
            ],
        ];
        foreach ($rows as $row) {
            if (! in_array($row['tingkat'], $tingkatExisting, true)) {
                $educationModel->insert(['employee_id' => $employeeId] + $row);
            }
        }
        $educationModel->setLatestCurrent($employeeId);
    }

    /** Fills sections A-F of the official form; values already filled in are kept. */
    private function fillOfficialProfile(EmployeeModel $employeeModel, int $employeeId, string $unitName, string $job, string $employmentType, int $unitId, int $index): void
    {
        $employee = $employeeModel->find($employeeId);
        $sequence = $index + 1;
        $isAsn = in_array($employmentType, ['CPNS', 'PNS'], true);
        $isPns = $employmentType === 'PNS';
        $married = ($employee['status_perkawinan'] ?? '') === 'Kawin';
        $latestEducation = $this->educationForJob($job);
        $tmtCpns = sprintf('%04d-%02d-01', 2010 + (($unitId + $index) % 12), (($unitId + $index) % 12) + 1);
        $values = [
            'nip_lama' => $isAsn ? sprintf('%09d', 100000000 + $unitId * 1000 + $sequence) : '-',
            'tempat_lahir' => 'Yogyakarta',
            'alamat_rumah' => sprintf('Jl. Contoh No. %d, RT %02d/RW %02d, Kel. Warungboto, Kec. Umbulharjo, Kota Yogyakarta, DI Yogyakarta', $unitId * 10 + $sequence, $sequence, $unitId),
            'telepon' => sprintf('0812%04d%04d', $unitId, $sequence * 111),
            'telepon_darurat' => sprintf('0813%04d%04d', $unitId, $sequence * 222),
            'telepon_darurat_hubungan' => $married ? 'Suami/Istri' : 'Orang Tua',
            'karpeg' => $isAsn ? sprintf('B %06d', $unitId * 100 + $sequence) : '-',
            'kartu_asn' => $isAsn || str_contains($employmentType, 'PPPK') ? sprintf('ASN-%04d-%02d', $unitId, $sequence) : '-',
            'bpjs' => sprintf('0001%04d%05d', $unitId, $sequence),
            'taspen' => $isAsn ? sprintf('TSP%04d%03d', $unitId, $sequence) : '-',
            'karis_karsu' => $isAsn && $married ? sprintf('KR%04d%03d', $unitId, $sequence) : '-',
            'npwp' => sprintf('%02d.%03d.%03d.%d-541.000', $unitId, $sequence * 7, $unitId * 13, $sequence),
            'nik' => sprintf('347101%04d%06d', $unitId, $sequence),
            'cpns_nomor_nota' => $isAsn ? sprintf('NT/%04d/%02d/BKN', $unitId, $sequence) : '',
            'cpns_tanggal_nota' => $isAsn ? date('Y-m-d', strtotime($tmtCpns . ' -2 months')) : null,
            'cpns_nomor_sk' => $isAsn ? sprintf('%d/Pem.D/BP/D.2', 500 + $unitId * 10 + $sequence) : '',
            'cpns_tanggal_sk' => $isAsn ? date('Y-m-d', strtotime($tmtCpns . ' +27 days')) : null,
            'cpns_pejabat' => $isAsn ? 'Wali Kota Yogyakarta' : '',
            'cpns_tmt' => $isAsn ? $tmtCpns : null,
            'cpns_pangkat_golongan' => $isAsn ? 'Penata Muda, III/a' : '',
            'pns_nomor_sk' => $isPns ? sprintf('%d/Pem.D/BP/D.4', 10 + $unitId * 10 + $sequence) : '',
            'pns_tanggal_sk' => $isPns ? date('Y-m-d', strtotime($tmtCpns . ' +14 months')) : null,
            'pns_pejabat' => $isPns ? 'Wali Kota Yogyakarta' : '',
            'pns_tmt' => $isPns ? date('Y-m-d', strtotime($tmtCpns . ' +15 months')) : null,
            'pns_pangkat_golongan' => $isPns ? 'Penata Muda, III/a' : '',
            'pns_sumpah_janji' => $isPns ? 'Sudah' : '',
            'pangkat_nomor' => $isAsn ? sprintf('%d/Pem.D/BP/D.4/%d', 60 + $sequence, 2020 + $unitId % 4) : '',
            'pangkat_tanggal' => $isAsn ? sprintf('%04d-03-28', 2020 + $unitId % 4) : null,
            'pangkat_pejabat' => $isAsn ? 'Wali Kota Yogyakarta' : '',
            'pangkat_tmt' => $isAsn ? sprintf('%04d-04-01', 2020 + $unitId % 4) : null,
            'pangkat_nama' => $isAsn ? ($isPns ? 'Penata Muda Tingkat I, III/b' : 'Penata Muda, III/a') : '',
            'masa_kerja' => $isAsn ? sprintf('%d tahun %d bulan', 4 + $index, $unitId % 12) : '',
            'jabatan_nomor' => sprintf('%d/RSUD/KP/%d', 100 + $unitId * 10 + $sequence, 2023),
            'jabatan_tanggal' => '2023-12-27',
            'jabatan_pejabat' => $isAsn ? 'Wali Kota Yogyakarta' : 'Direktur RSUD Kota Yogyakarta',
            'jabatan_tmt' => '2024-01-01',
            'unit_kerja' => $unitName,
            'eselon' => '-',
            'pendidikan_terakhir_tingkat' => $latestEducation['tingkat'],
            'pendidikan_terakhir_nama' => $latestEducation['nama'],
            'pendidikan_terakhir_fakultas' => $latestEducation['fakultas'],
            'pendidikan_terakhir_instansi' => $latestEducation['instansi'],
            'pendidikan_terakhir_status' => 'Lulus',
            'pendidikan_terakhir_tempat' => 'Yogyakarta',
            'pendidikan_terakhir_pejabat' => 'Rektor/Direktur ' . $latestEducation['instansi'],
            'pendidikan_terakhir_nomor_ijazah' => sprintf('DUMMY-IJZ-%04d-%02d', $unitId, $sequence),
            'pendidikan_terakhir_tanggal_ijazah' => $this->graduationDate($unitId, $index),
            'pendidikan_terakhir_nilai' => number_format(3.10 + (($unitId + $index) % 9) / 10, 2, '.', ''),
        ];

        $changes = [];
        foreach ($values as $field => $value) {
            $current = $employee[$field] ?? null;
            if (($current === null || trim((string) $current) === '') && $value !== null && $value !== '') {
                $changes[$field] = $value;
            }
        }
        if ($changes !== []) {
            $employeeModel->update($employeeId, $changes);
        }
    }

    /** Repeatable sections G-X; several rows where the form allows more than one. Skipped once filled. */
    private function seedHistories(int $employeeId, string $employmentType, string $unitName, string $job, int $index): void
    {
        $historyModel = new EmployeeHistoryModel();
        if ($historyModel->where('employee_id', $employeeId)->countAllResults() > 0) {
            return;
        }
        $employee = (new EmployeeModel())->find($employeeId);
        $married = ($employee['status_perkawinan'] ?? '') === 'Kawin';
        $isAsn = in_array($employmentType, ['CPNS', 'PNS'], true);
        $surname = explode(' ', (string) $employee['nama'])[1] ?? 'Santoso';
        $family = static fn (string $name, string $status, string $birthPlace, string $birthDate, string $maritalStatus, ?string $weddingDate, string $job, string $note = ''): array => [
            'nama' => $name, 'status_keluarga' => $status, 'tempat_lahir' => $birthPlace, 'tanggal_lahir' => $birthDate,
            'status_perkawinan' => $maritalStatus, 'tanggal_pernikahan' => $weddingDate, 'pekerjaan' => $job, 'nip_nrp' => '-', 'keterangan' => $note,
        ];

        $sections = [
            'jabatan' => [
                ['nama_jabatan' => 'Struktural', 'sub_unit_kerja' => $unitName . ', RSUD Kota Yogyakarta', 'jenis_perubahan_jabatan' => $isAsn ? 'Pengangkatan CPNS' : 'Pengangkatan Pegawai', 'eselon' => '-', 'pangkat_gol_ruang' => $isAsn ? 'Penata Muda, III/a' : '', 'jumlah_kredit' => '', 'nomor_sk' => '550/Pem.D/BP/D.2', 'tanggal_sk' => '2020-12-28', 'pejabat_pengesah' => 'Wali Kota Yogyakarta', 'tmt' => '2020-12-01', 'tanggal_pelantikan' => null],
                ['nama_jabatan' => $job, 'sub_unit_kerja' => $unitName . ', RSUD Kota Yogyakarta', 'jenis_perubahan_jabatan' => 'Pengangkatan Jabatan Fungsional', 'eselon' => '-', 'pangkat_gol_ruang' => $isAsn ? 'Penata Muda, III/a' : '', 'jumlah_kredit' => (string) (50 + $index * 10), 'nomor_sk' => '101/Pem.D/BP/D.4/XII/2023', 'tanggal_sk' => '2023-12-27', 'pejabat_pengesah' => 'Wali Kota Yogyakarta', 'tmt' => '2024-01-01', 'tanggal_pelantikan' => '2024-01-19'],
            ],
            'orang_tua' => [
                $family('Bapak ' . $surname, 'Ayah Kandung', 'Sleman', '1955-03-12', 'Kawin', '1979-06-20', 'Pensiunan'),
                $family('Ibu ' . $surname, 'Ibu Kandung', 'Bantul', '1958-08-02', 'Kawin', '1979-06-20', 'Ibu Rumah Tangga'),
            ],
            'saudara' => [
                $family('Saudara ' . $surname, 'Saudara Kandung', 'Yogyakarta', '1990-11-05', 'Belum Kawin', null, 'Karyawan Swasta'),
            ],
            'pengalaman_kerja' => [
                ['instansi' => 'Klinik Pratama Contoh', 'jabatan' => $job, 'tahun' => '2', 'bulan' => '6'],
            ],
            'penguasaan_bahasa' => [
                ['nama_bahasa' => 'Bahasa Indonesia', 'kemampuan' => 'Aktif'],
                ['nama_bahasa' => 'Bahasa Inggris', 'kemampuan' => $index % 2 === 0 ? 'Aktif' : 'Pasif'],
            ],
            'organisasi' => [
                ['jenis_organisasi' => 'Profesi', 'nama_organisasi' => str_contains(mb_strtolower($job), 'perawat') ? 'PPNI' : 'Ikatan Profesi Kesehatan', 'jabatan' => 'Anggota', 'tanggal_mulai' => '2019-01-01', 'tanggal_selesai' => null, 'tempat' => 'Yogyakarta'],
            ],
        ];
        if ($isAsn) {
            $sections['kepangkatan'] = [
                ['pangkat_gol_ruang' => 'Penata Muda, III/a', 'jenis_kenaikan' => 'Gol. dari Pengadaan CPNS/PNS', 'tmt' => '2020-12-01', 'tanggal_sk' => '2020-12-28', 'nomor_sk' => '550/Pem.D/BP/D.2', 'masa_kerja' => '0 tahun 0 bulan', 'jumlah_kredit' => '0'],
            ];
            if ($employmentType === 'PNS') {
                $sections['kepangkatan'][] = ['pangkat_gol_ruang' => 'Penata Muda Tingkat I, III/b', 'jenis_kenaikan' => 'Reguler', 'tmt' => '2024-04-01', 'tanggal_sk' => '2024-03-28', 'nomor_sk' => '64/Pem.D/BP/D.4/2024', 'masa_kerja' => '3 tahun 4 bulan', 'jumlah_kredit' => '100'];
            }
        }
        if ($married) {
            $partnerStatus = ($employee['jenis_kelamin'] ?? '') === 'Perempuan' ? 'Suami' : 'Istri';
            $sections['pasangan'] = [
                $family($partnerStatus . ' ' . $employee['nama'], $partnerStatus, 'Yogyakarta', '1988-04-17', 'Kawin', '2015-10-20', 'Wiraswasta'),
            ];
            $sections['anak'] = [
                $family('Anak Pertama ' . $surname, 'Anak Kandung', 'Yogyakarta', '2016-09-01', 'Belum Kawin', null, 'Pelajar'),
                $family('Anak Kedua ' . $surname, 'Anak Kandung', 'Yogyakarta', '2019-02-14', 'Belum Kawin', null, 'Pelajar'),
            ];
            $sections['mertua'] = [
                $family('Bapak Mertua', 'Ayah Mertua', 'Klaten', '1956-01-30', 'Kawin', '1983-05-01', 'Petani'),
                $family('Ibu Mertua', 'Ibu Mertua', 'Klaten', '1960-07-21', 'Kawin', '1983-05-01', 'Ibu Rumah Tangga'),
            ];
        }

        foreach ($sections as $category => $records) {
            $historyModel->saveForEmployee($employeeId, $category, $records);
        }
    }

    /**
     * Gives the dummy employees a mix of document completeness: index 0 complete and verified,
     * 1 complete but waiting for verification, 2 missing SKP and ijazah verification,
     * 3 with an expired STR & SIP (or missing job description), 4 nothing uploaded yet.
     */
    private function seedDocuments(EmployeeDocumentModel $documentModel, int $employeeId, int $index): void
    {
        $rows = $documentModel->where('employee_id', $employeeId)->whereIn('group_key', ['wajib', 'tambahan'])->findAll();
        if ($index === 4 || $rows === []) {
            return;
        }
        $directory = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'employee-documents' . DIRECTORY_SEPARATOR . $employeeId;
        if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
            throw new \RuntimeException('Folder dokumen dummy tidak dapat dibuat.');
        }

        foreach ($rows as $row) {
            if ($row['stored_path'] !== '') {
                continue;
            }
            $key = $row['document_key'];
            $upload = (int) $row['is_required'] === 1 || in_array($key, ['kartu-kepegawaian', 'kp4-c1'], true);
            if ($index === 2 && in_array($key, ['skp', 'verifikasi-ijazah'], true)) {
                $upload = false;
            }
            if ($index === 3 && $key === 'uraian-tugas' && (int) $row['is_required'] === 1 && ! $this->hasRequiredDocument($rows, 'str-sip')) {
                $upload = false;
            }
            if (! $upload) {
                continue;
            }

            $storedName = 'contoh-' . $key . '-' . $row['id'] . '.pdf';
            file_put_contents($directory . DIRECTORY_SEPARATOR . $storedName, $this->placeholderPdf($row['title']));
            $documentModel->update((int) $row['id'], [
                'original_name' => $key . '.pdf',
                'stored_path' => 'employee-documents/' . $employeeId . '/' . $storedName,
                'mime_type' => 'application/pdf',
                'file_size' => filesize($directory . DIRECTORY_SEPARATOR . $storedName),
                'verification_status' => $index === 1 ? 'pending' : 'verified',
                'expires_at' => in_array($key, ['str-sip', 'sip'], true) ? ($index === 3 ? '2026-01-15' : '2028-06-30') : null,
            ]);
        }
    }

    private function hasRequiredDocument(array $rows, string $key): bool
    {
        foreach ($rows as $row) {
            if ($row['document_key'] === $key && (int) $row['is_required'] === 1) {
                return true;
            }
        }

        return false;
    }

    /** Minimal one-page PDF so the dummy documents can be previewed. */
    private function placeholderPdf(string $title): string
    {
        $text = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], 'Dokumen contoh: ' . $title);
        $stream = "BT /F1 14 Tf 60 760 Td ({$text}) Tj ET";
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>',
            '<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objects as $number => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($number + 1) . " 0 obj\n" . $object . "\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= 'xref' . "\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        return $pdf . 'trailer << /Size ' . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n" . $xref . "\n%%EOF";
    }

    private function employmentTypeFor(int $unitId, int $index): string
    {
        $types = [
            'CPNS', 'PNS', 'PPPK', 'PPPK Paruh Waktu',
            'BLUD Tetap', 'BLUD Kontrak', 'Mitra Penuh Waktu', 'Mitra Paruh Waktu',
        ];
        $typeIndex = (($unitId - 1) * 5 + $index) % count($types);

        return $types[$typeIndex];
    }

    private function seniorityLevelFor(string $job): string
    {
        foreach (['Ahli Utama', 'Ahli Madya', 'Ahli Muda', 'Ahli Pertama', 'Penyelia', 'Mahir', 'Terampil'] as $level) {
            if (str_ends_with(mb_strtolower($job), mb_strtolower($level))) {
                return $level;
            }
        }

        return '';
    }

    private function jobsForUnit(string $unitName): array
    {
        $unitName = mb_strtolower($unitName);
        if (str_contains($unitName, 'farmasi')) {
            return [
                ['group' => 'Apoteker', 'title' => 'Apoteker Ahli Pertama'],
                ['group' => 'Asisten Apoteker', 'title' => 'Asisten Apoteker Terampil'],
                ['group' => 'Apoteker', 'title' => 'Apoteker Ahli Muda'],
                ['group' => 'Asisten Apoteker', 'title' => 'Asisten Apoteker Mahir'],
                ['group' => 'Tenaga Umum Non Kesehatan', 'title' => 'Pengadministrasi Perkantoran'],
            ];
        }
        if (str_contains($unitName, 'icu')) {
            return [
                ['group' => 'Dokter', 'title' => 'Dokter Ahli Madya'],
                ['group' => 'Perawat', 'title' => 'Perawat Ahli Muda'],
                ['group' => 'Perawat', 'title' => 'Perawat Terampil'],
                ['group' => 'Perawat', 'title' => 'Perawat Penyelia'],
                ['group' => 'Perawat', 'title' => 'Perawat Ahli Pertama'],
            ];
        }
        if (str_contains($unitName, 'igd')) {
            return [
                ['group' => 'Dokter', 'title' => 'Dokter Ahli Pertama'],
                ['group' => 'Perawat', 'title' => 'Perawat Terampil'],
                ['group' => 'Bidan', 'title' => 'Bidan Terampil'],
                ['group' => 'Perawat', 'title' => 'Perawat Ahli Muda'],
                ['group' => 'Bidan', 'title' => 'Bidan Ahli Pertama'],
            ];
        }
        if (str_contains($unitName, 'poli')) {
            return [
                ['group' => 'Dokter', 'title' => 'Dokter Ahli Muda'],
                ['group' => 'Perawat', 'title' => 'Perawat Ahli Pertama'],
                ['group' => 'Bidan', 'title' => 'Bidan Ahli Muda'],
                ['group' => 'Perekam Medis', 'title' => 'Perekam Medis Terampil'],
                ['group' => 'Dokter Gigi', 'title' => 'Dokter Gigi Ahli Pertama'],
            ];
        }

        return [
            ['group' => 'Perawat', 'title' => 'Perawat Terampil'],
            ['group' => 'Bidan', 'title' => 'Bidan Terampil'],
            ['group' => 'Dokter', 'title' => 'Dokter Ahli Pertama'],
            ['group' => 'Tenaga Umum Non Kesehatan', 'title' => 'Operator Layanan Operasional'],
            ['group' => 'Perawat', 'title' => 'Perawat Ahli Muda'],
        ];
    }

    private function educationForJob(string $job): array
    {
        if (str_contains(mb_strtolower($job), 'farmasi') || str_contains(mb_strtolower($job), 'apoteker')) {
            return ['tingkat' => 'S1', 'nama' => 'Farmasi', 'instansi' => 'Universitas Contoh Yogyakarta', 'fakultas' => 'Farmasi'];
        }
        if (str_contains(mb_strtolower($job), 'dokter')) {
            return ['tingkat' => 'S1', 'nama' => 'Kedokteran', 'instansi' => 'Universitas Contoh Yogyakarta', 'fakultas' => 'Kedokteran'];
        }
        if (str_contains(mb_strtolower($job), 'administrasi') || str_contains(mb_strtolower($job), 'perekam')) {
            return ['tingkat' => 'D3', 'nama' => 'Administrasi Kesehatan', 'instansi' => 'Politeknik Contoh Yogyakarta', 'fakultas' => 'Kesehatan'];
        }

        return ['tingkat' => 'S1', 'nama' => 'Ilmu Keperawatan', 'instansi' => 'Universitas Contoh Yogyakarta', 'fakultas' => 'Keperawatan'];
    }

    private function birthDate(int $unitId, int $index): string
    {
        $year = 1978 + (($unitId * 3 + $index * 4) % 18);
        $month = (($unitId + $index) % 12) + 1;
        $day = (($unitId * 5 + $index * 7) % 27) + 1;

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    private function graduationDate(int $unitId, int $index): string
    {
        $year = 2000 + (($unitId * 2 + $index * 3) % 23);
        $month = (($unitId + $index * 2) % 12) + 1;

        return sprintf('%04d-%02d-15', $year, $month);
    }
}
