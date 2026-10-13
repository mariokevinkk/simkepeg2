<?php

namespace App\Libraries;

final class EmployeeProfileData
{
    public static function get(): array
    {
        $educationDocuments = [
            // Keep the old key so existing uploaded ijazah files remain linked.
            ['id' => 'ijazah-transkrip', 'title' => 'Ijazah', 'description' => 'Unggah ijazah untuk jenjang pendidikan pada isian ini.', 'file' => '', 'required' => true],
        ];

        return [
            'pegawai' => [
                'nama' => 'Ahmad Hidayat',
                'gelar_depan' => '',
                'gelar_belakang' => 'S.Kep., Ns.',
                'nip_lama' => '098765432',
                'nip' => '198501012010011001',
                'nik' => '3471101010850001',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1985-01-01',
                'email' => 'ahmad.hidayat@example.com',
                'pendidikan_terakhir' => 'S1',
                'nama_pendidikan' => 'Ilmu Keperawatan',
                'institusi_pendidikan' => 'Universitas Gadjah Mada',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'status_perkawinan' => 'Kawin',
                'jenis_kepegawaian' => 'PNS',
                'status' => 'Aktif',
                'kedudukan' => 'Aktif',
                'alamat' => 'Jl. Kaliurang Km 5.5, Depok, Sleman, DI Yogyakarta',
                'jalan' => 'Jl. Kaliurang Km 5.5',
                'rt_rw' => '005/002',
                'kelurahan' => 'Caturtunggal',
                'telepon' => '081234567890',
                'telepon_darurat' => '081298765432',
                'karpeg' => 'B 123456',
                'kartu_asn' => 'ASN-123-456',
                'bpjs' => '000123456789',
                'taspen' => '123456789',
                'karis_karsu' => '12345',
                'npwp' => '12.345.678.9-123.000',
                'kecamatan' => 'Depok',
                'kabupaten' => 'Sleman',
                'provinsi' => 'DI Yogyakarta',
            ],
            'karir' => [
                'jenis_ketenagaan' => 'PERAWAT',
                'jenjang_jabatan' => 'Ahli Pertama',
                'pangkat_nomor' => '550/Pem.D/BP/D.2',
                'pangkat_tanggal' => '2020-12-28',
                'pangkat_tmt' => '2020-12-01',
                'pangkat_nama' => 'Penata Muda, III/a',
                'masa_kerja' => '0 tahun 0 bulan',
                'pangkat_pejabat' => 'Walikota Yogyakarta',
                'jabatan_nomor' => '101/Pem.D/BP/D.4/XII/2023',
                'jabatan_tanggal' => '2023-12-27',
                'jabatan_tmt' => '2023-12-27',
                'jabatan_nama' => 'Perawat Ahli Pertama',
                'unit_kerja' => 'Bangsal Anggrek',
                'eselon' => '-',
                'jabatan_pejabat' => 'Walikota Yogyakarta',
            ],
            'profesi' => [
                'sip_nomor' => 'SIP/123/2021',
                'sip_terbit' => '2021-01-10',
                'sip_berlaku' => '2026-08-25',
                'str_nomor' => 'STR/987/2021',
                'str_terbit' => '2021-01-15',
                'str_berlaku' => '2026-01-15',
                'kredensial_nomor' => 'BAK/RSUD/024/2025',
                'kredensial_berlaku' => '2027-04-13',
                'kewenangan' => 'Perawat Klinis III',
                'penugasan_klinis' => 'Bangsal Anggrek',
                'evaluasi_terakhir' => '2025-12-15',
                'kompetensi' => 'Kompetensi Perawat',
                'pelatihan_jpl' => '15 dari 20 JPL',
                'nilai_skp' => 'Baik - Tahun 2025',
            ],
            'pendidikan' => [
                [
                    'tingkat' => 'S1',
                    'nama' => 'Ilmu Keperawatan',
                    'instansi' => 'Universitas Gadjah Mada',
                    'fakultas' => 'Keperawatan',
                    'tempat' => 'Yogyakarta',
                    'nomor_ijazah' => 'IJZ/2007/001',
                    'tanggal_ijazah' => '2007-08-15',
                    'nilai' => '3.75',
                    'status' => 'Lulus',
                    'pejabat' => 'Prof. Dr. dr. X',
                    'dokumen' => $educationDocuments,
                ],
            ],
            'dokumen' => [
                'identitas' => [
                    ['id' => 'formulir-riwayat-hidup', 'title' => 'Formulir Isian Pegawai / Daftar Riwayat Hidup', 'description' => 'Berkas pendukung data utama pegawai.', 'file' => 'formulir-isian-pegawai.pdf', 'required' => true],
                    ['id' => 'kartu-kepegawaian', 'title' => 'Kartu Kepegawaian dan Identitas', 'description' => 'KARPEG, KTP, KARIS/KARSU, TASPEN, dan ASKES/BPJS.', 'file' => 'kartu-identitas-kepegawaian.pdf'],
                ],
                'kepangkatan' => [
                    ['id' => 'sk-kepegawaian', 'title' => 'SK Kepegawaian', 'description' => 'SK CPNS/PNS, pangkat, jabatan, fungsional, KGB, PMK, dan PUPNS.', 'file' => 'sk-cpns-pns-pangkat-jabatan.pdf'],
                ],
                'jabatan' => [
                    ['id' => 'sk-penugasan-rs', 'title' => 'SK Jabatan Terakhir', 'description' => 'SK yang menjadi dasar isian jabatan terakhir.', 'file' => 'sk-penugasan-rsud.pdf'],
                ],
                'keluarga' => [
                    ['id' => 'kp4-c1', 'title' => 'Daftar Keluarga KP4 dan C1', 'description' => 'Berkas pendukung data pasangan dan anak.', 'file' => 'kp4-dan-c1.pdf'],
                    ['id' => 'perubahan-keluarga', 'title' => 'Akta Nikah, Kelahiran, dan Kematian', 'description' => 'Berkas perubahan susunan keluarga.', 'file' => 'akta-nikah-dan-kelahiran.pdf'],
                ],
                'pengalaman' => [
                    ['id' => 'riwayat-pekerjaan', 'title' => 'Daftar Riwayat Pekerjaan', 'description' => 'Berkas pendukung pengalaman kerja pegawai.', 'file' => ''],
                ],
                'sanksi' => [
                    ['id' => 'sk-hukuman-disiplin', 'title' => 'SK Hukuman Disiplin', 'description' => 'Unggah berkas jika terdapat riwayat sanksi administratif.', 'file' => ''],
                ],
            ],
        ];
    }

    /**
     * Checklist file pegawai (tenaga medis, perawat, dan tenaga kesehatan lain).
     * `rule` decides when an item is required: all, nakes, skp, or pns (only shown to PNS/CPNS).
     * `section` is the form section (A-X) the file is shown with; items without one
     * are listed separately below the form. The ijazah item is attached per education record (J).
     */
    public static function documentChecklist(): array
    {
        return [
            'wajib' => [
                ['id' => 'formulir-riwayat-hidup', 'section' => 'A', 'letter' => 'a', 'title' => 'Formulir Isian Pegawai / Daftar Riwayat Hidup', 'description' => 'Formulir isian pegawai yang sudah ditandatangani.', 'rule' => 'all'],
                ['id' => 'ijazah-transkrip', 'section' => 'J', 'letter' => 'b', 'title' => 'Ijazah & Transkrip', 'description' => 'Diunggah untuk setiap jenjang pada riwayat pendidikan (J).', 'rule' => 'all', 'per_education' => true],
                ['id' => 'sk-penugasan-rs', 'section' => 'E', 'letter' => 'c', 'title' => 'SK Penugasan di Rumah Sakit', 'description' => 'SK penempatan/penugasan di RSUD.', 'rule' => 'all'],
                ['id' => 'uraian-tugas', 'section' => 'E', 'letter' => 'd', 'title' => 'Uraian Tugas', 'description' => 'Uraian tugas sesuai jabatan saat ini.', 'rule' => 'all'],
                ['id' => 'ba-kredensial', 'letter' => 'e', 'title' => 'Berita Acara Kredensial dan/atau Rekredensial', 'description' => 'Wajib untuk tenaga kesehatan.', 'rule' => 'nakes'],
                ['id' => 'kewenangan-klinis', 'letter' => 'f', 'title' => 'Kewenangan Klinis', 'description' => 'Wajib untuk tenaga kesehatan.', 'rule' => 'nakes'],
                ['id' => 'penugasan-klinis', 'letter' => 'g', 'title' => 'Penugasan Klinis / Clinical Appointment', 'description' => 'Wajib untuk tenaga kesehatan.', 'rule' => 'nakes'],
                ['id' => 'evaluasi-kinerja-klinis', 'letter' => 'h', 'title' => 'Evaluasi Kinerja Klinis', 'description' => 'Wajib untuk tenaga kesehatan.', 'rule' => 'nakes'],
                ['id' => 'str-sip', 'section' => 'STR', 'letter' => 'STR', 'title' => 'Surat Tanda Registrasi (STR)', 'description' => 'Unggah dokumen STR di halaman STR. Jika sebelumnya memakai berkas gabungan, unggah ulang STR dan SIP secara terpisah.', 'rule' => 'nakes'],
                ['id' => 'sip', 'section' => 'SIP', 'letter' => 'SIP', 'title' => 'Surat Izin Praktik (SIP)', 'description' => 'Unggah dokumen SIP pada halaman SIP.', 'rule' => 'nakes'],
                ['id' => 'sertifikat-kompetensi', 'letter' => 'j', 'title' => 'Sertifikat Kompetensi', 'description' => 'Sertifikat diklat/kompetensi.', 'rule' => 'all'],
                ['id' => 'sertifikat-pelatihan', 'letter' => 'k', 'title' => 'Sertifikat Pelatihan', 'description' => 'Sertifikat diklat/pelatihan (JPL).', 'rule' => 'all'],
                ['id' => 'skp', 'letter' => 'l', 'title' => 'Penilaian Kinerja (SKP)', 'description' => 'Wajib untuk PNS, CPNS, PPPK, BLUD Tetap, dan BLUD Kontrak.', 'rule' => 'skp'],
                ['id' => 'verifikasi-ijazah', 'section' => 'F', 'letter' => 'm', 'title' => 'Bukti Verifikasi Ijazah', 'description' => 'Bukti verifikasi keabsahan ijazah.', 'rule' => 'all'],
            ],
            'tambahan' => [
                ['id' => 'sk-kepegawaian', 'section' => 'D', 'letter' => 'a', 'title' => 'SK (CPNS, PNS, Kenaikan Pangkat, Kenaikan Jabatan, Fungsional, Inpassing, PMK, KGB, PUPNS)', 'description' => 'Gabungkan SK kepegawaian dalam satu berkas.'],
                ['id' => 'kartu-kepegawaian', 'section' => 'A', 'letter' => 'b', 'title' => 'KARPEG, KTP, KARIS / KARSU, TASPEN, ASKES/BPJS', 'description' => 'Kartu identitas dan kepegawaian.'],
                ['id' => 'riwayat-pekerjaan', 'section' => 'P', 'letter' => 'c', 'title' => 'Daftar Riwayat Pekerjaan', 'description' => 'Termasuk SK mutasi.'],
                ['id' => 'sk-hukuman-disiplin', 'section' => 'R', 'letter' => 'd', 'title' => 'SK Hukuman Disiplin', 'description' => 'Hukuman disiplin ASN, jika ada.'],
                ['id' => 'ijazah-sttpl', 'section' => 'C', 'letter' => 'e', 'title' => 'Ijazah STTPL / Piagam', 'description' => 'Khusus PNS/CPNS.', 'rule' => 'pns'],
                ['id' => 'kp4-c1', 'section' => 'T', 'letter' => 'f', 'title' => 'Daftar Keluarga (KP4) dan C1', 'description' => 'Termasuk Kartu Keluarga (KK).'],
                ['id' => 'perubahan-keluarga', 'section' => 'U', 'letter' => 'g', 'title' => 'Perubahan Susunan Keluarga', 'description' => 'Akte nikah, kelahiran, kematian.'],
            ],
        ];
    }

    /** Checklist items keyed by document id, each with its category (wajib/tambahan). */
    public static function documentCatalog(): array
    {
        $catalog = [];
        foreach (self::documentChecklist() as $category => $items) {
            foreach ($items as $item) {
                $catalog[$item['id']] = $item + ['category' => $category, 'rule' => 'optional'];
            }
        }

        return $catalog;
    }

    public static function isHealthWorker(array $employee): bool
    {
        $job = mb_strtolower(trim(($employee['jabatan_nama'] ?? '') . ' ' . ($employee['jenis_ketenagaan'] ?? '')));
        if ($job === '' || str_contains($job, 'administrasi')) {
            return false;
        }
        foreach ([
            'dokter', 'perawat', 'ners', 'bidan', 'apoteker', 'kefarmasian', 'farmasi', 'analis kesehatan',
            'laboran', 'radiografer', 'fisioterapi', 'nutrisionis', 'gizi', 'sanitarian', 'perekam medis',
            'terapis', 'elektromedis', 'anestesi', 'psikolog klinis', 'okupasi', 'kesehatan',
        ] as $keyword) {
            if (str_contains($job, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /** Returns 'wajib', 'tidak_wajib', or 'tidak_berlaku' for a checklist item and employee. */
    public static function documentRequirement(array $item, array $employee): string
    {
        $employmentType = mb_strtolower((string) ($employee['jenis_kepegawaian'] ?? ''));

        return match ($item['rule'] ?? 'optional') {
            'all' => 'wajib',
            'nakes' => self::isHealthWorker($employee) ? 'wajib' : 'tidak_wajib',
            'skp' => str_contains($employmentType, 'mitra') ? 'tidak_wajib' : 'wajib',
            'pns' => str_contains($employmentType, 'pns') ? 'tidak_wajib' : 'tidak_berlaku',
            default => 'tidak_wajib',
        };
    }

    /** Choices for `select` fields. Values saved earlier that are not listed stay selectable. */
    public static function fieldOptions(string $set): array
    {
        $pangkat = [
            'Juru Muda, I/a', 'Juru Muda Tingkat I, I/b', 'Juru, I/c', 'Juru Tingkat I, I/d',
            'Pengatur Muda, II/a', 'Pengatur Muda Tingkat I, II/b', 'Pengatur, II/c', 'Pengatur Tingkat I, II/d',
            'Penata Muda, III/a', 'Penata Muda Tingkat I, III/b', 'Penata, III/c', 'Penata Tingkat I, III/d',
            'Pembina, IV/a', 'Pembina Tingkat I, IV/b', 'Pembina Utama Muda, IV/c', 'Pembina Utama Madya, IV/d', 'Pembina Utama, IV/e',
        ];

        return match ($set) {
            'jenis_kelamin' => ['Laki-laki', 'Perempuan'],
            'agama' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'],
            'status_pegawai' => ['Aktif', 'Cuti', 'Tugas Belajar', 'Nonaktif', 'Pensiun', 'Menunggu Verifikasi'],
            'kedudukan' => ['Aktif', 'Cuti di Luar Tanggungan Negara', 'Tugas Belajar', 'Pemberhentian Sementara', 'Pensiun', 'Meninggal Dunia'],
            'status_perkawinan' => ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'],
            'hubungan_darurat' => ['Suami/Istri', 'Orang Tua', 'Anak', 'Saudara', 'Lainnya'],
            'pangkat' => $pangkat,
            'sumpah' => ['Sudah', 'Belum'],
            'eselon' => ['-', 'I.a', 'I.b', 'II.a', 'II.b', 'III.a', 'III.b', 'IV.a', 'IV.b', 'V.a'],
            'tingkat_pendidikan' => ['SD', 'SLTP', 'SLTA', 'D1', 'D2', 'D3', 'D4', 'S1', 'Profesi', 'S2', 'Spesialis', 'S3'],
            'status_pendidikan' => ['Lulus', 'Belum Lulus'],
            'jenis_kenaikan' => ['Gol. dari Pengadaan CPNS/PNS', 'Reguler', 'Pilihan (Jabatan Struktural)', 'Pilihan (Jabatan Fungsional)', 'Penyesuaian Ijazah', 'Pengabdian', 'Anumerta'],
            'jenis_perubahan_jabatan' => ['Pengangkatan CPNS', 'Pengangkatan PNS', 'Pengangkatan Pegawai', 'Pengangkatan Jabatan Fungsional', 'Kenaikan Jenjang Jabatan', 'Mutasi', 'Penataan Pegawai', 'Pengangkatan Tugas Tambahan', 'Pemberhentian'],
            'jenjang_prestasi' => ['Kabupaten/Kota', 'Provinsi', 'Nasional', 'Internasional'],
            'kemampuan_bahasa' => ['Aktif', 'Pasif'],
            'hukuman' => ['Ringan', 'Sedang', 'Berat'],
            'status_pasangan' => ['Suami', 'Istri'],
            'status_anak' => ['Anak Kandung', 'Anak Tiri', 'Anak Angkat'],
            'status_orang_tua' => ['Ayah Kandung', 'Ibu Kandung'],
            'status_mertua' => ['Ayah Mertua', 'Ibu Mertua'],
            'status_saudara' => ['Saudara Kandung', 'Saudara Tiri'],
            default => [],
        };
    }

    /**
     * Resolve BUP by functional level, with optional persisted Super Admin settings.
     */
    public static function retirementAge(array $employee, ?array $bupAges = null): int
    {
        $job = mb_strtolower(($employee['jabatan_nama'] ?? '') . ' ' . ($employee['jenjang_jabatan'] ?? ''));
        $levelKey = match (true) {
            str_contains($job, 'utama') => 'ahli_utama',
            str_contains($job, 'madya'), str_contains($job, 'pimpinan tinggi'), str_contains($job, 'direktur') => 'ahli_madya',
            str_contains($job, 'muda') => 'ahli_muda',
            str_contains($job, 'pertama') => 'ahli_pertama',
            str_contains($job, 'penyelia') => 'keterampilan_penyelia',
            str_contains($job, 'mahir') => 'keterampilan_mahir',
            str_contains($job, 'terampil') => 'keterampilan_terampil',
            default => null,
        };
        if ($levelKey === null) {
            return 58;
        }
        $defaults = [
            'ahli_utama' => 65,
            'ahli_madya' => 60,
            'ahli_muda' => 58,
            'ahli_pertama' => 58,
            'keterampilan_penyelia' => 58,
            'keterampilan_mahir' => 58,
            'keterampilan_terampil' => 58,
        ];

        return (int) ($bupAges[$levelKey] ?? $defaults[$levelKey]);
    }

    /**
     * Retirement figures for one employee, or null without a valid birth date:
     * the BUP, the date it is reached, and the TMT pensiun (first day of the following month).
     */
    public static function retirementInfo(array $employee, ?\DateTimeImmutable $today = null, ?array $bupAges = null): ?array
    {
        $birth = \DateTimeImmutable::createFromFormat('!Y-m-d', (string) ($employee['tanggal_lahir'] ?? ''));
        if ($birth === false) {
            return null;
        }
        $today ??= new \DateTimeImmutable('today');
        $age = self::retirementAge($employee, $bupAges);
        $reached = $birth->modify('+' . $age . ' years');
        $tmt = $reached->modify('first day of next month');
        $months = ((int) $tmt->format('Y') - (int) $today->format('Y')) * 12 + (int) $tmt->format('n') - (int) $today->format('n');

        return [
            'bup' => $age,
            'age_now' => $birth->diff($today)->y,
            'reached' => $reached->format('Y-m-d'),
            'tmt' => $tmt->format('Y-m-d'),
            'months_left' => $months,
            'past_due' => $tmt <= $today,
        ];
    }

    /**
     * The employee form split into pages (sidebar menu for pegawai, tabs for admins).
     * `sections` are form letters or standalone profile sections; DOKUMEN is the checklist card.
     */
    public static function profilePages(): array
    {
        return [
            'profil-pegawai' => ['title' => 'Data Utama', 'icon' => 'fa-user', 'sections' => ['A']],
            'riwayat-kepangkatan' => ['title' => 'Riwayat Kepangkatan', 'icon' => 'fa-chart-line', 'sections' => ['B', 'C', 'D', 'G']],
            'riwayat-jabatan' => ['title' => 'Riwayat Jabatan', 'icon' => 'fa-briefcase', 'sections' => ['E', 'H', 'I']],
            'riwayat-pendidikan' => ['title' => 'Pendidikan & Pelatihan', 'icon' => 'fa-graduation-cap', 'sections' => ['F', 'J']],
            'data-keluarga' => ['title' => 'Data Keluarga', 'icon' => 'fa-users', 'sections' => ['T', 'U', 'V', 'W', 'X']],
            'data-lainnya' => ['title' => 'Lainnya', 'icon' => 'fa-ellipsis', 'sections' => ['K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S']],
            'str' => ['title' => 'STR', 'icon' => 'fa-id-card', 'sections' => ['STR']],
            'sip' => ['title' => 'SIP', 'icon' => 'fa-file-medical', 'sections' => ['SIP']],
            'dokumen-pegawai' => ['title' => 'Dokumen', 'icon' => 'fa-folder-open', 'sections' => ['DOKUMEN']],
        ];
    }

    /** Standalone professional credential pages, backed by existing employees columns. */
    public static function professionalLicenseSections(): array
    {
        return [
            'sip' => [
                'section' => 'SIP',
                'title' => 'SURAT IZIN PRAKTIK (SIP)',
                'fields' => [
                    ['sip_nomor', 'Nomor SIP'],
                    ['sip_terbit', 'Tanggal Mulai Berlaku SIP', 'date'],
                    ['sip_berlaku', 'Tanggal Selesai Berlaku SIP', 'date'],
                ],
            ],
            'str' => [
                'section' => 'STR',
                'title' => 'SURAT TANDA REGISTRASI (STR)',
                'fields' => [
                    ['str_nomor', 'Nomor STR'],
                    ['str_terbit', 'Tanggal Mulai Berlaku STR', 'date'],
                    ['str_berlaku', 'Tanggal Selesai Berlaku STR', 'date'],
                ],
            ],
        ];
    }

    /** Fields that appear once in the official employee form, sections A-E. */
    public static function officialCoreSections(): array
    {
        return [
            [
                'letter' => 'A', 'title' => 'IDENTITAS PEGAWAI', 'fields' => [
                    ['nip_lama', 'NIP Lama', 'numeric', 20], ['nip', 'NIP Baru', 'numeric', 40], ['nama', 'Nama Pegawai'],
                    ['tempat_lahir', 'Tempat Lahir'], ['tanggal_lahir', 'Tanggal Lahir', 'date'], ['tanggal_masuk', 'Tanggal Masuk', 'date'],
                    ['jenis_kelamin', 'Jenis Kelamin', 'select', 'jenis_kelamin'], ['agama', 'Agama', 'select', 'agama'],
                    ['employee_status', 'Status Pegawai', 'select', 'status_pegawai'],
                    ['jenis_kepegawaian', 'Jenis Kepegawaian'], ['status_perkawinan', 'Status Perkawinan', 'select', 'status_perkawinan'],
                    ['kedudukan', 'Kedudukan Pegawai', 'select', 'kedudukan'], ['alamat_rumah', 'Alamat Rumah', 'textarea'],
                    ['telepon', 'No. Telepon / HP', 'tel'], ['telepon_darurat', 'No. Darurat', 'tel'],
                    ['telepon_darurat_hubungan', 'Hubungan Keluarga (No. Darurat)', 'select', 'hubungan_darurat'], ['karpeg', 'No. Karpeg'],
                    ['kartu_asn', 'Kartu ASN Virtual'], ['bpjs', 'No. ASKES / BPJS', 'numeric', 20], ['taspen', 'No. Kartu Taspen'],
                    ['karis_karsu', 'No. Karis / Karsu'], ['npwp', 'NPWP'], ['nik', 'NIK', 'numeric', 16], ['email', 'Email', 'email'],
                ],
            ],
            [
                'letter' => 'B', 'title' => 'PENGANGKATAN SEBAGAI CPNS', 'fields' => [
                    ['cpns_nomor_nota', 'Nomor Nota'], ['cpns_tanggal_nota', 'Tanggal Nota', 'date'],
                    ['cpns_nomor_sk', 'Nomor SK CPNS'], ['cpns_tanggal_sk', 'Tanggal SK CPNS', 'date'],
                    ['cpns_pejabat', 'Pejabat Pengesah'], ['cpns_tmt', 'TMT CPNS', 'date'],
                    ['cpns_pangkat_golongan', 'Pangkat, Golongan/Ruang', 'select', 'pangkat'], ['cpns_tmt_honorer', 'TMT Honorer', 'date'],
                ],
            ],
            [
                'letter' => 'C', 'title' => 'PENGANGKATAN SEBAGAI PNS', 'fields' => [
                    ['pns_nomor_sk', 'Nomor SK PNS'], ['pns_tanggal_sk', 'Tanggal SK PNS', 'date'],
                    ['pns_pejabat', 'Pejabat Pengesah'], ['pns_tmt', 'TMT PNS', 'date'],
                    ['pns_pangkat_golongan', 'Pangkat, Golongan/Ruang', 'select', 'pangkat'], ['pns_sumpah_janji', 'Sumpah / Janji', 'select', 'sumpah'],
                ],
            ],
            [
                'letter' => 'D', 'title' => 'PANGKAT, GOLONGAN / RUANG TERAKHIR', 'fields' => [
                    ['pangkat_nomor', 'Nomor SK'], ['pangkat_tanggal', 'Tanggal SK', 'date'],
                    ['pangkat_pejabat', 'Pejabat Pengesah'], ['pangkat_tmt', 'TMT Pangkat, Golongan/Ruang', 'date'],
                    ['pangkat_nama', 'Pangkat, Golongan/Ruang', 'select', 'pangkat'], ['masa_kerja', 'Masa Kerja'],
                ],
            ],
            [
                'letter' => 'E', 'title' => 'JABATAN TERAKHIR', 'fields' => [
                    ['jabatan_nomor', 'Nomor SK'], ['jabatan_tanggal', 'Tanggal SK', 'date'],
                    ['jabatan_pejabat', 'Pejabat Pengesah'], ['jabatan_tmt', 'TMT Jabatan', 'date'],
                    ['jabatan_nama', 'Jabatan Terakhir'], ['unit_kerja', 'Unit Kerja', 'unit'], ['eselon', 'Eselon', 'select', 'eselon'],
                ],
            ],
        ];
    }

    public static function officialLatestEducationSection(): array
    {
        return [
            'letter' => 'F', 'title' => 'PENDIDIKAN UMUM TERAKHIR', 'fields' => [
                ['pendidikan_terakhir_tingkat', 'Tingkat', 'select', 'tingkat_pendidikan'], ['pendidikan_terakhir_nama', 'Nama Pendidikan'],
                ['pendidikan_terakhir_fakultas', 'Fakultas'], ['pendidikan_terakhir_instansi', 'Instansi'],
                ['pendidikan_terakhir_status', 'Status Pendidikan', 'select', 'status_pendidikan'], ['pendidikan_terakhir_tempat', 'Tempat'],
                ['pendidikan_terakhir_pejabat', 'Kasek/Rektor/Dir'], ['pendidikan_terakhir_nomor_ijazah', 'No. Ijazah'],
                ['pendidikan_terakhir_tanggal_ijazah', 'Tanggal Ijazah', 'date'], ['pendidikan_terakhir_nilai', 'Nilai'],
            ],
        ];
    }

    /**
     * Sections D, E, and F are summaries of the newest row of G, H, and J. Fields in `map`
     * (summary field => history field) are filled automatically and are read-only in the form.
     */
    public static function summarySources(): array
    {
        return [
            'D' => ['history' => 'kepangkatan', 'source' => 'Riwayat Kepangkatan (G)', 'map' => [
                'pangkat_nomor' => 'nomor_sk', 'pangkat_tanggal' => 'tanggal_sk', 'pangkat_tmt' => 'tmt',
                'pangkat_nama' => 'pangkat_gol_ruang', 'masa_kerja' => 'masa_kerja',
            ]],
            'E' => ['history' => 'jabatan', 'source' => 'Riwayat Jabatan (H)', 'map' => [
                'jabatan_nomor' => 'nomor_sk', 'jabatan_tanggal' => 'tanggal_sk', 'jabatan_pejabat' => 'pejabat_pengesah',
                'jabatan_tmt' => 'tmt', 'jabatan_nama' => 'nama_jabatan', 'eselon' => 'eselon',
            ]],
            'F' => ['history' => 'education', 'source' => 'Riwayat Pendidikan (J)', 'map' => [
                'pendidikan_terakhir_tingkat' => 'tingkat', 'pendidikan_terakhir_nama' => 'nama',
                'pendidikan_terakhir_fakultas' => 'fakultas', 'pendidikan_terakhir_instansi' => 'instansi',
                'pendidikan_terakhir_status' => 'status', 'pendidikan_terakhir_tempat' => 'tempat',
                'pendidikan_terakhir_nomor_ijazah' => 'nomor_ijazah', 'pendidikan_terakhir_tanggal_ijazah' => 'tanggal_ijazah',
                'pendidikan_terakhir_nilai' => 'nilai',
            ]],
        ];
    }

    /** Summary section info when the field is filled from a history table, otherwise null. */
    public static function summarySourceFor(string $fieldKey): ?array
    {
        foreach (self::summarySources() as $letter => $source) {
            if (isset($source['map'][$fieldKey])) {
                return ['letter' => $letter] + $source;
            }
        }

        return null;
    }

    /** Newest rank/position row: latest TMT, then latest SK date, then the row entered last. */
    public static function latestHistoryRow(array $rows): ?array
    {
        $latest = null;
        $latestKey = null;
        foreach (array_values($rows) as $index => $row) {
            $key = [(string) ($row['tmt'] ?? ''), (string) ($row['tanggal_sk'] ?? ''), $index];
            if ($latestKey === null || $key > $latestKey) {
                [$latest, $latestKey] = [$row, $key];
            }
        }

        return $latest;
    }

    /** Highest education level; same level is decided by the latest ijazah date. */
    public static function latestEducationRow(array $rows): ?array
    {
        $levels = array_flip(array_map('strtolower', self::fieldOptions('tingkat_pendidikan')));
        $latest = null;
        $latestKey = null;
        foreach (array_values($rows) as $index => $row) {
            $key = [$levels[strtolower(trim((string) ($row['tingkat'] ?? '')))] ?? -1, (string) ($row['tanggal_ijazah'] ?? ''), $index];
            if ($latestKey === null || $key > $latestKey) {
                [$latest, $latestKey] = [$row, $key];
            }
        }

        return $latest;
    }

    /** Rows of section J (riwayat pendidikan); the keys are columns of employee_educations. */
    public static function educationFields(): array
    {
        return [
            ['tingkat', 'Tingkat', 'select', 'tingkat_pendidikan'],
            ['nama', 'Nama Pendidikan', 'text', null, 'Nama Pendidikan, Instansi, Fakultas'],
            ['instansi', 'Instansi', 'text', null, 'Nama Pendidikan, Instansi, Fakultas'],
            ['fakultas', 'Fakultas', 'text', null, 'Nama Pendidikan, Instansi, Fakultas'],
            ['tempat', 'Tempat'],
            ['nomor_ijazah', 'Nomor Ijazah', 'text', null, 'Nomor Ijazah, Tanggal Ijazah, Nilai'],
            ['tanggal_ijazah', 'Tanggal Ijazah', 'date', null, 'Nomor Ijazah, Tanggal Ijazah, Nilai'],
            ['nilai', 'Nilai', 'text', null, 'Nomor Ijazah, Tanggal Ijazah, Nilai'],
            ['status', 'Status', 'select', 'status_pendidikan'],
        ];
    }

    /** Repeatable sections G-I and K-X from the supplied employee form. */
    public static function officialHistorySections(): array
    {
        $familyFields = static fn (string $statusOptions): array => [
            ['nama', 'Nama'], ['status_keluarga', 'Status Keluarga', 'select', $statusOptions],
            ['tempat_lahir', 'Tempat Lahir', 'text', null, 'Tempat, Tgl Lahir'], ['tanggal_lahir', 'Tanggal Lahir', 'date', null, 'Tempat, Tgl Lahir'],
            ['status_perkawinan', 'Status Perkawinan', 'select', 'status_perkawinan'],
            ['tanggal_pernikahan', 'Tanggal Pernikahan', 'date'], ['pekerjaan', 'Pekerjaan'],
            ['nip_nrp', 'NIP/NRP'], ['keterangan', 'Ket'],
        ];
        $positionFields = [
            ['nama_jabatan', 'Nama Jabatan', 'text', null, 'Nama Jabatan, Sub Unit Kerja, Unit Kerja'],
            ['sub_unit_kerja', 'Sub Unit Kerja, Unit Kerja', 'text', null, 'Nama Jabatan, Sub Unit Kerja, Unit Kerja'],
            ['jenis_perubahan_jabatan', 'Jenis Perubahan Jabatan', 'select', 'jenis_perubahan_jabatan'], ['eselon', 'Eselon', 'select', 'eselon'],
            ['pangkat_gol_ruang', 'Pangkat, Gol/Ruang', 'select', 'pangkat'], ['jumlah_kredit', 'Jumlah Kredit', 'number'],
            ['nomor_sk', 'No SK', 'text', null, 'No SK, Tanggal SK, Pejabat Pengesah'], ['tanggal_sk', 'Tanggal SK', 'date', null, 'No SK, Tanggal SK, Pejabat Pengesah'],
            ['pejabat_pengesah', 'Pejabat Pengesah', 'text', null, 'No SK, Tanggal SK, Pejabat Pengesah'],
            ['tmt', 'TMT', 'date'], ['tanggal_pelantikan', 'Tanggal Pelantikan', 'date'],
        ];

        return [
            'kepangkatan' => ['letter' => 'G', 'title' => 'RIWAYAT KEPANGKATAN', 'fields' => [
                ['pangkat_gol_ruang', 'Pangkat, Gol/Ruang', 'select', 'pangkat'], ['jenis_kenaikan', 'Jenis Kenaikan', 'select', 'jenis_kenaikan'],
                ['tmt', 'TMT', 'date'], ['tanggal_sk', 'Tgl SK', 'date'], ['nomor_sk', 'No SK'],
                ['masa_kerja', 'Masa Kerja'], ['jumlah_kredit', 'Jumlah Kredit', 'number'],
            ]],
            'jabatan' => ['letter' => 'H', 'title' => 'RIWAYAT JABATAN', 'fields' => array_merge([
                ['jenis_ketenagaan', 'Jenis Ketenagaan', 'select', 'jenis_ketenagaan'],
            ], $positionFields)],
            'tugas_tambahan' => ['letter' => 'I', 'title' => 'RIWAYAT TUGAS TAMBAHAN', 'fields' => $positionFields],
            'tanda_jasa' => ['letter' => 'K', 'title' => 'TANDA JASA / SATYA LENCANA', 'fields' => [
                ['nama_tanda_jasa_satya_lencana', 'Nama Tanda Jasa / Satya Lencana'],
                ['nomor_sk_tanggal', 'Nomor SK, Tanggal'], ['tahun', 'Tahun', 'year'], ['asal', 'Asal'],
            ]],
            'prestasi' => ['letter' => 'L', 'title' => 'PRESTASI', 'fields' => [
                ['judul_penghargaan', 'Judul Penghargaan'], ['jenjang', 'Jenjang', 'select', 'jenjang_prestasi'], ['kategori', 'Kategori'], ['juara', 'Juara'],
            ]],
            'karya_ilmiah' => ['letter' => 'M', 'title' => 'KARYA ILMIAH', 'fields' => [
                ['judul', 'Judul'], ['nomor_isbn', 'Nomor ISBN'], ['tahun_publikasi', 'Tahun Publikasi', 'year'],
            ]],
            'kunjungan_luar_negeri' => ['letter' => 'N', 'title' => 'PENGALAMAN KUNJUNGAN KE LUAR NEGERI', 'fields' => [
                ['negara_tujuan', 'Negara Tujuan'], ['jenis_penugasan', 'Jenis Penugasan'], ['keterangan', 'Keterangan'],
                ['surat_penugasan_yang_menetapkan', 'Yang Menetapkan', 'text', null, 'Surat Penugasan'], ['nomor_sk', 'Nomor SK', 'text', null, 'Surat Penugasan'],
                ['tanggal', 'Tanggal', 'date', null, 'Surat Penugasan'],
                ['tgl_mulai', 'Tgl. Mulai', 'date', null, 'Lama Penugasan'], ['tgl_selesai', 'Tgl. Selesai', 'date', null, 'Lama Penugasan'],
            ]],
            'organisasi' => ['letter' => 'O', 'title' => 'KEANGGOTAAN ORGANISASI', 'fields' => [
                ['jenis_organisasi', 'Jenis Organisasi'], ['nama_organisasi', 'Nama Organisasi'], ['jabatan', 'Jabatan'],
                ['tanggal_mulai', 'Tanggal Mulai', 'date', null, 'Tanggal Mulai, Selesai'], ['tanggal_selesai', 'Tanggal Selesai', 'date', null, 'Tanggal Mulai, Selesai'], ['tempat', 'Tempat'],
            ]],
            'pengalaman_kerja' => ['letter' => 'P', 'title' => 'PENGALAMAN KERJA', 'fields' => [
                ['instansi', 'Instansi'], ['jabatan', 'Jabatan'], ['tahun', 'Lama (Tahun)', 'number'], ['bulan', 'Lama (Bulan)', 'number'],
            ]],
            'penguasaan_bahasa' => ['letter' => 'Q', 'title' => 'PENGUASAAN BAHASA', 'fields' => [
                ['nama_bahasa', 'Nama Bahasa'], ['kemampuan', 'Kemampuan', 'select', 'kemampuan_bahasa'],
            ]],
            'sanksi' => ['letter' => 'R', 'title' => 'RIWAYAT SANKSI ADMINISTRATIF', 'fields' => [
                ['hukuman_tingkat', 'Hukuman / Tingkat', 'select', 'hukuman'],
                ['nomor', 'Nomor', 'text', null, 'Nomor, Tanggal, Mulai, Selesai'], ['tanggal', 'Tanggal', 'date', null, 'Nomor, Tanggal, Mulai, Selesai'],
                ['mulai', 'Mulai', 'date', null, 'Nomor, Tanggal, Mulai, Selesai'], ['selesai', 'Selesai', 'date', null, 'Nomor, Tanggal, Mulai, Selesai'],
                ['pejabat_pengesah', 'Pejabat Pengesah'], ['keterangan', 'Keterangan'],
            ]],
            'lain_lain' => ['letter' => 'S', 'title' => 'LAIN-LAIN', 'fields' => [
                ['nama', 'Nama', 'text', null, 'Nama, Tempat'], ['tempat', 'Tempat', 'text', null, 'Nama, Tempat'],
                ['nomor', 'Nomor', 'text', null, 'Nomor, Tanggal, Mulai, Selesai'], ['tanggal', 'Tanggal', 'date', null, 'Nomor, Tanggal, Mulai, Selesai'],
                ['mulai', 'Mulai', 'date', null, 'Nomor, Tanggal, Mulai, Selesai'], ['selesai', 'Selesai', 'date', null, 'Nomor, Tanggal, Mulai, Selesai'],
                ['keterangan', 'Keterangan'],
            ]],
            'pasangan' => ['letter' => 'T', 'title' => 'DATA ISTERI/SUAMI', 'fields' => $familyFields('status_pasangan')],
            'anak' => ['letter' => 'U', 'title' => 'DATA ANAK', 'fields' => $familyFields('status_anak')],
            'orang_tua' => ['letter' => 'V', 'title' => 'DATA ORANG TUA KANDUNG', 'fields' => $familyFields('status_orang_tua')],
            'mertua' => ['letter' => 'W', 'title' => 'DATA MERTUA', 'fields' => $familyFields('status_mertua')],
            'saudara' => ['letter' => 'X', 'title' => 'DATA SAUDARA', 'fields' => $familyFields('status_saudara')],
        ];
    }
}
