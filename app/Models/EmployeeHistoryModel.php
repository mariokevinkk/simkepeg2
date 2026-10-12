<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeHistoryModel extends Model
{
    protected $table = 'employee_histories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['employee_id', 'category', 'data', 'sort_order'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function forEmployee(int $employeeId, string $category): array
    {
        $history = $this->where('employee_id', $employeeId)
            ->where('category', $category)
            ->orderBy('sort_order')
            ->findAll();

        return array_map(static function (array $row): array {
            $payload = json_decode($row['data'] ?? '', true);
            return is_array($payload) ? ['id' => (int) $row['id']] + $payload : ['id' => (int) $row['id']];
        }, $history);
    }

    public function replaceForEmployee(int $employeeId, string $category, array $records): void
    {
        $this->db->transStart();
        $this->where('employee_id', $employeeId)->where('category', $category)->delete();
        foreach (array_values($records) as $index => $record) {
            if (! is_array($record)) {
                continue;
            }
            $this->insert([
                'employee_id' => $employeeId,
                'category' => $category,
                'data' => json_encode($record, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'sort_order' => $index,
            ]);
        }
        $this->db->transComplete();
    }

    public function saveForEmployee(int $employeeId, string $category, array $records): void
    {
        $existingRows = $this->where('employee_id', $employeeId)
            ->where('category', $category)
            ->findAll();
        $existingIds = array_fill_keys(array_map(static fn (array $row): int => (int) $row['id'], $existingRows), true);
        $savedIds = [];

        foreach (array_values($records) as $sortOrder => $record) {
            if (! is_array($record)) {
                continue;
            }
            $recordId = (int) ($record['id'] ?? 0);
            unset($record['id']);
            $hasData = count(array_filter(
                $record,
                static fn ($value): bool => $value !== null && trim((string) $value) !== '',
            )) > 0;
            if (! $hasData) {
                continue;
            }

            $payload = [
                'data' => json_encode($record, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'sort_order' => $sortOrder,
            ];
            if ($recordId > 0) {
                if (! isset($existingIds[$recordId])) {
                    throw new \RuntimeException('Riwayat pegawai tidak sesuai dengan bagian yang disimpan.');
                }
                $this->update($recordId, $payload);
                $savedIds[] = $recordId;
                continue;
            }

            $newId = (int) $this->insert([
                'employee_id' => $employeeId,
                'category' => $category,
            ] + $payload, true);
            if ($newId < 1) {
                throw new \RuntimeException('Riwayat ' . $category . ' gagal disimpan.');
            }
            $savedIds[] = $newId;
        }

        $deletedIds = array_values(array_diff(array_keys($existingIds), $savedIds));
        if ($deletedIds !== []) {
            if ($category === 'anak') {
                $documentKeys = array_map(static fn (int $id): string => 'anak-' . $id . '-file', $deletedIds);
                $documents = $this->db->table('employee_documents')
                    ->select('stored_path')
                    ->where('employee_id', $employeeId)
                    ->whereIn('document_key', $documentKeys)
                    ->get()
                    ->getResultArray();
                $uploadDirectory = realpath(WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'employee-documents' . DIRECTORY_SEPARATOR . $employeeId);
                if ($uploadDirectory !== false) {
                    foreach ($documents as $document) {
                        $storedName = basename((string) ($document['stored_path'] ?? ''));
                        $filePath = $uploadDirectory . DIRECTORY_SEPARATOR . $storedName;
                        if ($storedName !== '' && is_file($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }
                $this->db->table('employee_documents')
                    ->where('employee_id', $employeeId)
                    ->whereIn('document_key', $documentKeys)
                    ->delete();
            }
            $this->where('employee_id', $employeeId)
                ->where('category', $category)
                ->whereIn('id', $deletedIds)
                ->delete();
        }
    }
}
