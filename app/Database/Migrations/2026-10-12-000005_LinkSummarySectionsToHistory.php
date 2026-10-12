<?php

use App\Models\EmployeeModel;
use CodeIgniter\Database\Migration;

/**
 * Riwayat Jabatan / Tugas Tambahan: "Nama Jabatan, Sub Unit Kerja, Unit Kerja" becomes two fields
 * (nama_jabatan, sub_unit_kerja), so Jabatan Terakhir (E) can be filled from the newest row.
 * Afterwards D, E, and F are filled from the newest rows of G, H, and J.
 */
class LinkSummarySectionsToHistory extends Migration
{
    public function up()
    {
        $this->transformPositions(static function (array $data): array {
            if (! array_key_exists('nama_jabatan_sub_unit_unit', $data)) {
                return $data;
            }
            $parts = array_map('trim', explode(',', (string) $data['nama_jabatan_sub_unit_unit'], 2));
            unset($data['nama_jabatan_sub_unit_unit']);

            return ['nama_jabatan' => $parts[0], 'sub_unit_kerja' => $parts[1] ?? ''] + $data;
        });

        // Keep existing D/E/F values for employees that have no history rows yet.
        $employees = new EmployeeModel();
        foreach ($this->db->table('employees')->select('id')->get()->getResultArray() as $employee) {
            $employees->syncSummaries((int) $employee['id'], ['D', 'E', 'F'], false);
        }
    }

    public function down()
    {
        $this->transformPositions(static function (array $data): array {
            if (! array_key_exists('nama_jabatan', $data)) {
                return $data;
            }
            $joined = implode(', ', array_filter([trim((string) $data['nama_jabatan']), trim((string) ($data['sub_unit_kerja'] ?? ''))]));
            unset($data['nama_jabatan'], $data['sub_unit_kerja']);

            return ['nama_jabatan_sub_unit_unit' => $joined] + $data;
        });
    }

    private function transformPositions(callable $transform): void
    {
        $rows = $this->db->table('employee_histories')
            ->whereIn('category', ['jabatan', 'tugas_tambahan'])
            ->get()->getResultArray();
        foreach ($rows as $row) {
            $data = json_decode((string) $row['data'], true);
            if (! is_array($data)) {
                continue;
            }
            $this->db->table('employee_histories')->where('id', $row['id'])->update([
                'data' => json_encode($transform($data), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            ]);
        }
    }
}
