<?php

namespace App\Database\Seeds;

use App\Libraries\EmployeeProfileData;
use App\Models\EmployeeDocumentModel;
use App\Models\EmployeeHistoryModel;
use App\Models\EmployeeModel;
use App\Models\UnitModel;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

/**
 * Data uji fitur pensiun. Tanggal lahir dihitung dari hari ini, sehingga setiap pegawai uji
 * selalu jatuh pada rentang yang sama (lewat BUP, 1 bulan lagi, ..., di luar 5 tahun).
 * Aman dijalankan ulang: pegawai uji dikenali dari email pensiun.test.NN@example.test.
 *
 *   php spark db:seed PensiunTestSeeder
 */
class PensiunTestSeeder extends Seeder
{
    /** months = bulan sampai TMT pensiun (negatif = sudah lewat); null = sudah berstatus pensiun. */
    private array $people = [
        ['nama' => 'Sutrisno Hadi', 'jk' => 'Laki-laki', 'jabatan' => 'Pengadministrasi Umum', 'jenjang' => '', 'jenis' => 'PNS', 'months' => -1],
        ['nama' => 'Sri Rahayu', 'jk' => 'Perempuan', 'jabatan' => 'Perawat Terampil', 'jenjang' => 'Terampil', 'jenis' => 'PNS', 'months' => 1],
        ['nama' => 'Bambang Wijayanto', 'jk' => 'Laki-laki', 'jabatan' => 'Pengelola Keuangan', 'jenjang' => '', 'jenis' => 'PNS', 'months' => 4],
        ['nama' => 'Endang Purwaningsih', 'jk' => 'Perempuan', 'jabatan' => 'Perawat Ahli Madya', 'jenjang' => 'Ahli Madya', 'jenis' => 'PNS', 'months' => 9],
        ['nama' => 'Agus Setiawan', 'jk' => 'Laki-laki', 'jabatan' => 'Pranata Laboratorium Kesehatan Terampil', 'jenjang' => 'Terampil', 'jenis' => 'BLUD Tetap', 'months' => 18],
        ['nama' => 'Hartono', 'jk' => 'Laki-laki', 'jabatan' => 'Dokter Ahli Utama', 'jenjang' => 'Ahli Utama', 'jenis' => 'PNS', 'months' => 36],
        ['nama' => 'Yuliana Kartika', 'jk' => 'Perempuan', 'jabatan' => 'Bidan Ahli Muda', 'jenjang' => 'Ahli Muda', 'jenis' => 'PNS', 'months' => 54],
        ['nama' => 'Heru Santoso', 'jk' => 'Laki-laki', 'jabatan' => 'Sanitarian Terampil', 'jenjang' => 'Terampil', 'jenis' => 'PNS', 'months' => 84],
        ['nama' => 'Suharti', 'jk' => 'Perempuan', 'jabatan' => 'Perawat Terampil', 'jenjang' => 'Terampil', 'jenis' => 'PNS', 'months' => null],
        ['nama' => 'Wahyudi', 'jk' => 'Laki-laki', 'jabatan' => 'Pengadministrasi Umum', 'jenjang' => '', 'jenis' => 'PNS', 'months' => null],
    ];

    public function run()
    {
        $units = (new UnitModel())->activeUnits();
        if ($units === []) {
            CLI::write('Tidak ada unit aktif. Jalankan InitialSeeder terlebih dahulu.', 'yellow');
            return;
        }

        $employees = new EmployeeModel();
        $histories = new EmployeeHistoryModel();
        $documents = new EmployeeDocumentModel();
        $today = new \DateTimeImmutable('today');
        $db = db_connect();
        $db->transBegin();

        try {
            foreach ($this->people as $index => $person) {
                $sequence = $index + 1;
                $unit = $units[$index % count($units)];
                $retired = $person['months'] === null;
                $bup = EmployeeProfileData::retirementAge(['jabatan_nama' => $person['jabatan'], 'jenjang_jabatan' => $person['jenjang']]);

                // TMT pensiun = tanggal 1 bulan setelah BUP tercapai, jadi BUP tercapai pada bulan (TMT - 1).
                $monthsToTmt = $retired ? -14 - $sequence : $person['months'];
                $birth = $today->modify('first day of this month')
                    ->modify(sprintf('%+d months', $monthsToTmt - 1))
                    ->modify('-' . $bup . ' years')
                    ->modify('+14 days');
                $cpnsTmt = $birth->modify('+25 years')->format('Ym');
                $nip = $birth->format('Ymd') . $cpnsTmt . ($person['jk'] === 'Perempuan' ? '2' : '1') . sprintf('%03d', 900 + $sequence);
                $email = sprintf('pensiun.test.%02d@example.test', $sequence);

                $data = [
                    'unit_id' => (int) $unit['id'],
                    'nip' => $nip,
                    'nama' => $person['nama'],
                    'email' => $email,
                    'tempat_lahir' => 'Yogyakarta',
                    'tanggal_lahir' => $birth->format('Y-m-d'),
                    'jenis_kelamin' => $person['jk'],
                    'agama' => 'Islam',
                    'status_perkawinan' => 'Kawin',
                    'jenis_kepegawaian' => $person['jenis'],
                    'employee_status' => $retired ? 'Pensiun' : 'Aktif',
                    'kedudukan' => $retired ? 'Pensiun' : 'Aktif',
                    'jenis_ketenagaan' => mb_strtoupper($person['jabatan']),
                    'jenjang_jabatan' => $person['jenjang'],
                    'unit_kerja' => $unit['name'],
                ];

                $existing = $employees->where('email', $email)->first();
                if ($existing === null) {
                    $employeeId = (int) $employees->insert($data, true);
                } else {
                    $employeeId = (int) $existing['id'];
                    $employees->update($employeeId, $data);
                }
                if ($employeeId < 1) {
                    throw new \RuntimeException('Gagal menyimpan pegawai uji ' . $person['nama'] . '.');
                }

                // Jabatan terakhir (E) mengikuti baris terbaru Riwayat Jabatan (H).
                $histories->saveForEmployee($employeeId, 'jabatan', [[
                    'nama_jabatan' => $person['jabatan'],
                    'sub_unit_kerja' => $unit['name'] . ', RSUD Kota Yogyakarta',
                    'jenis_perubahan_jabatan' => 'Pengangkatan Jabatan Fungsional',
                    'eselon' => '-',
                    'nomor_sk' => sprintf('800/%03d/RSUD/%d', 900 + $sequence, (int) $today->format('Y') - 6),
                    'tanggal_sk' => $today->modify('-6 years')->format('Y-m-d'),
                    'pejabat_pengesah' => 'Wali Kota Yogyakarta',
                    'tmt' => $today->modify('-6 years')->modify('first day of next month')->format('Y-m-d'),
                ]]);
                $employees->syncSummaries($employeeId, ['E'], false);
                $documents->syncCatalog($employeeId);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Penyimpanan data uji pensiun gagal.');
            }
            $db->transCommit();
        } catch (\Throwable $exception) {
            $db->transRollback();
            throw $exception;
        }

        CLI::write(count($this->people) . ' pegawai uji pensiun siap (email pensiun.test.NN@example.test).', 'green');
    }
}
