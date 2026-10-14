<?php

namespace App\Models;

use App\Libraries\EmployeeProfileData;
use CodeIgniter\Model;

class EmployeeDocumentModel extends Model
{
    protected $table = 'employee_documents';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'employee_id',
        'education_id',
        'group_key',
        'document_key',
        'title',
        'description',
        'original_name',
        'stored_path',
        'mime_type',
        'file_size',
        'is_required',
        'verification_status',
        'verification_note',
        'verified_by',
        'verified_at',
        'expires_at',
        'uploaded_by',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    /** Backwards-compatible name used by registration and older callers. */
    public function seedCatalog(int $employeeId, ?int $educationId = null, ?int $uploadedBy = null): void
    {
        $this->syncCatalog($employeeId, $uploadedBy);
    }

    /**
     * Makes sure every applicable checklist item has a row for the employee (one
     * ijazah row per education record) and refreshes the required flag, which
     * depends on the employee's job and employment type.
     */
    public function syncCatalog(int $employeeId, ?int $uploadedBy = null): void
    {
        $employee = $this->db->table('employees')->where('id', $employeeId)->get()->getRowArray();
        if ($employee === null) {
            return;
        }
        $educationIds = array_map('intval', array_column(
            $this->db->table('employee_educations')->select('id')->where('employee_id', $employeeId)->get()->getResultArray(),
            'id',
        ));
        $existing = [];
        foreach ($this->where('employee_id', $employeeId)->where('group_key !=', 'anak')->findAll() as $row) {
            $existing[$row['document_key'] . '#' . ($row['education_id'] ?? '')] = $row;
        }

        foreach (EmployeeProfileData::documentCatalog() as $item) {
            $requirement = EmployeeProfileData::documentRequirement($item, $employee);
            $targets = ! empty($item['per_education']) ? $educationIds : [null];
            foreach ($targets as $educationId) {
                $row = $existing[$item['id'] . '#' . ($educationId ?? '')] ?? null;
                if ($requirement === 'tidak_berlaku') {
                    if ($row !== null && $row['stored_path'] === '') {
                        $this->delete((int) $row['id']);
                    } elseif ($row !== null && (int) $row['is_required'] !== 0) {
                        $this->update((int) $row['id'], ['is_required' => 0]);
                    }
                    continue;
                }

                $isRequired = $requirement === 'wajib' ? 1 : 0;
                if ($row === null) {
                    $this->insert([
                        'employee_id' => $employeeId,
                        'education_id' => $educationId,
                        'group_key' => $item['category'],
                        'document_key' => $item['id'],
                        'title' => $item['title'],
                        'description' => $item['description'] ?? '',
                        'is_required' => $isRequired,
                        'verification_status' => 'missing',
                        'uploaded_by' => $uploadedBy,
                    ]);
                } elseif ((int) $row['is_required'] !== $isRequired || $row['group_key'] !== $item['category'] || $row['title'] !== $item['title']) {
                    $this->update((int) $row['id'], [
                        'is_required' => $isRequired,
                        'group_key' => $item['category'],
                        'title' => $item['title'],
                        'description' => $item['description'] ?? '',
                    ]);
                }
            }
        }
    }

    /** Status of one stored document: missing, rejected, expired, pending, or verified. */
    private static function entryStatus(?array $row): string
    {
        if ($row === null || ($row['stored_path'] ?? '') === '') {
            return 'missing';
        }
        if ($row['verification_status'] === 'rejected') {
            return 'rejected';
        }
        if (! empty($row['expires_at']) && $row['expires_at'] < date('Y-m-d')) {
            return 'expired';
        }

        return $row['verification_status'] === 'verified' ? 'verified' : 'pending';
    }

    /**
     * Checklist status for several employees at once, computed from the catalog
     * rules so it is correct even before rows are synced. Each employee gets
     * `entries` (one per checklist line) and a `summary` of the required items.
     */
    public function checklistStatus(array $employees): array
    {
        $employeeIds = array_values(array_filter(array_map(static fn (array $e): int => (int) $e['id'], $employees)));
        if ($employeeIds === []) {
            return [];
        }

        $rowsByEmployee = [];
        foreach ($this->whereIn('employee_id', $employeeIds)->where('group_key !=', 'anak')->findAll() as $row) {
            $rowsByEmployee[(int) $row['employee_id']][$row['document_key'] . '#' . ($row['education_id'] ?? '')] = $row;
        }
        $educationsByEmployee = [];
        $educations = $this->db->table('employee_educations')
            ->select('id, employee_id, tingkat, nama')
            ->whereIn('employee_id', $employeeIds)
            ->orderBy('is_current', 'DESC')
            ->orderBy('tanggal_ijazah', 'DESC')
            ->get()->getResultArray();
        foreach ($educations as $education) {
            $educationsByEmployee[(int) $education['employee_id']][] = $education;
        }

        $result = [];
        foreach ($employees as $employee) {
            $employeeId = (int) $employee['id'];
            $rows = $rowsByEmployee[$employeeId] ?? [];
            $entries = [];
            foreach (EmployeeProfileData::documentCatalog() as $item) {
                $requirement = EmployeeProfileData::documentRequirement($item, $employee);
                $profileRow = $rows[$item['id'] . '#'] ?? null;
                $expiryField = match ($item['id']) {
                    'str-sip' => 'str_berlaku',
                    'sip' => 'sip_berlaku',
                    default => null,
                };
                $profileExpiry = $expiryField === null ? '' : trim((string) ($employee[$expiryField] ?? ''));
                if ($profileRow !== null && $profileExpiry !== '') {
                    $profileRow['expires_at'] = $profileExpiry;
                }
                if ($requirement === 'tidak_berlaku' && ($profileRow === null || $profileRow['stored_path'] === '')) {
                    continue;
                }
                $required = $requirement === 'wajib';

                if (! empty($item['per_education'])) {
                    $employeeEducations = $educationsByEmployee[$employeeId] ?? [];
                    if ($employeeEducations === []) {
                        $entries[] = ['item' => $item, 'row' => null, 'education' => null, 'required' => $required,
                            'label' => $item['title'], 'status' => 'missing'];
                    }
                    foreach ($employeeEducations as $education) {
                        $row = $rows[$item['id'] . '#' . $education['id']] ?? null;
                        $entries[] = ['item' => $item, 'row' => $row, 'education' => $education, 'required' => $required,
                            'label' => $item['title'] . ' (' . trim($education['tingkat'] . ' ' . $education['nama']) . ')',
                            'status' => self::entryStatus($row)];
                    }
                    continue;
                }

                $entries[] = ['item' => $item, 'row' => $profileRow, 'education' => null, 'required' => $required,
                    'label' => $item['title'], 'status' => self::entryStatus($profileRow)];
            }

            $summary = ['total' => 0, 'uploaded' => 0, 'verified' => 0, 'pending' => 0, 'missing' => [], 'revisions' => []];
            foreach ($entries as $entry) {
                if ($entry['status'] === 'rejected') {
                    $summary['revisions'][] = [
                        'title' => $entry['label'],
                        'note' => trim((string) ($entry['row']['verification_note'] ?? '')),
                    ];
                }
                if (! $entry['required']) {
                    continue;
                }
                $summary['total']++;
                if (in_array($entry['status'], ['verified', 'pending'], true)) {
                    $summary['uploaded']++;
                    $summary[$entry['status']]++;
                } else {
                    $summary['missing'][] = $entry['label']
                        . (! empty($entry['item']['section']) ? ' – bagian ' . $entry['item']['section'] : '')
                        . match ($entry['status']) {
                        'expired' => ' (kedaluwarsa)',
                        'rejected' => ' (perlu perbaikan)',
                        default => '',
                    };
                }
            }
            $summary['complete'] = $summary['missing'] === [];
            $result[$employeeId] = ['entries' => $entries, 'summary' => $summary];
        }

        return $result;
    }

    /** Checklist for the employee profile page, grouped per category with upload controls. */
    public function checklistFor(array $employee, bool $editable = false): array
    {
        $employeeId = (int) $employee['id'];
        $this->syncCatalog($employeeId);
        $status = $this->checklistStatus([$employee])[$employeeId] ?? ['entries' => [], 'summary' => []];

        $groups = ['wajib' => [], 'tambahan' => []];
        $other = ['wajib' => [], 'tambahan' => []];
        $bySection = [];
        $byEducation = [];
        foreach ($status['entries'] as $entry) {
            $item = $entry['item'];
            $row = $entry['row'];
            $educationId = $entry['education']['id'] ?? null;
            $hasFile = $row !== null && $row['stored_path'] !== '';
            $attachment = [
                'id' => $row !== null ? 'document-' . $row['id'] : $item['id'] . '-' . ($educationId ?? 'profile'),
                'document_key' => $item['id'],
                'letter' => $item['letter'],
                'title' => $entry['label'],
                'description' => $entry['education'] === null && ! empty($item['per_education'])
                    ? 'Isi riwayat pendidikan (J) terlebih dahulu, lalu unggah ijazah setiap jenjang.'
                    : ($item['description'] ?? ''),
                'file' => $hasFile ? $row['original_name'] : '',
                'required' => $entry['required'],
                'verification_status' => $row['verification_status'] ?? 'missing',
                'verification_note' => $row['verification_note'] ?? null,
                'verified_at' => $row['verified_at'] ?? null,
                'status' => $entry['status'],
                'has_expiry' => ! empty($item['has_expiry']),
                'expires_at' => $row['expires_at'] ?? null,
            ];
            if ($hasFile) {
                $attachment['url'] = base_url('/documents/' . $row['id']);
            }
            if ($editable && $row !== null) {
                $attachment['upload_url'] = base_url(
                    '/employees/' . $employeeId . '/documents/' . rawurlencode($item['id'])
                    . ($educationId === null ? '' : '?education_id=' . $educationId),
                );
            }
            $groups[$item['category']][] = $attachment;
            if ($educationId !== null) {
                $byEducation[(int) $educationId] = $attachment;
            } elseif (! empty($item['section'])) {
                $bySection[$item['section']][] = $attachment;
            } else {
                $other[$item['category']][] = $attachment;
            }
        }

        return [
            'groups' => $groups,
            'by_section' => $bySection,
            'by_education' => $byEducation,
            'other' => $other,
            'summary' => $status['summary'],
        ];
    }

    public function historyAttachmentFor(int $employeeId, int $historyId, bool $editable = false): array
    {
        $documentKey = 'anak-' . $historyId . '-file';
        $record = $this->where('employee_id', $employeeId)
            ->where('education_id', null)
            ->where('group_key', 'anak')
            ->where('document_key', $documentKey)
            ->first();

        if ($record === null) {
            $documentId = (int) $this->insert([
                'employee_id' => $employeeId,
                'education_id' => null,
                'group_key' => 'anak',
                'document_key' => $documentKey,
                'title' => 'File Upload',
                'description' => 'Berkas yang dilampirkan pada data anak ini.',
                'is_required' => 0,
                'verification_status' => 'missing',
            ], true);
            $record = $documentId > 0 ? $this->find($documentId) : null;
        }

        if ($record === null) {
            return [];
        }

        $hasFile = ($record['stored_path'] ?? '') !== '';
        $attachment = [
            'id' => 'document-' . $record['id'],
            'document_key' => $documentKey,
            'title' => 'File Upload',
            'description' => 'Berkas yang dilampirkan pada data anak ini.',
            'file' => $hasFile ? $record['original_name'] : '',
            'required' => false,
            'verification_status' => $record['verification_status'],
            'verification_note' => $record['verification_note'] ?? null,
            'verified_at' => $record['verified_at'] ?? null,
        ];
        if ($hasFile) {
            $attachment['url'] = base_url('/documents/' . $record['id']);
        }
        if ($editable) {
            $attachment['upload_url'] = base_url(
                '/employees/' . $employeeId . '/documents/' . rawurlencode($documentKey)
                . '?history_id=' . $historyId,
            );
        }

        return $attachment;
    }

    /** Removes the document rows and stored files attached to the given education records. */
    public function deleteForEducations(int $employeeId, array $educationIds): void
    {
        if ($educationIds === []) {
            return;
        }
        $rows = $this->where('employee_id', $employeeId)->whereIn('education_id', $educationIds)->findAll();
        $uploadDirectory = realpath(WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'employee-documents' . DIRECTORY_SEPARATOR . $employeeId);
        foreach ($rows as $row) {
            $storedName = basename((string) $row['stored_path']);
            if ($uploadDirectory !== false && $storedName !== '' && is_file($uploadDirectory . DIRECTORY_SEPARATOR . $storedName)) {
                @unlink($uploadDirectory . DIRECTORY_SEPARATOR . $storedName);
            }
        }
        if ($rows !== []) {
            $this->whereIn('id', array_column($rows, 'id'))->delete();
        }
    }

    public function uploadTarget(int $employeeId, string $documentKey, ?int $educationId): ?array
    {
        $query = $this->where('employee_id', $employeeId)
            ->where('document_key', $documentKey);
        $educationId === null
            ? $query->where('education_id', null)
            : $query->where('education_id', $educationId);

        return $query->first();
    }

    public function verifiedRequiredCount(int $employeeId): array
    {
        $required = $this->where('employee_id', $employeeId)->where('is_required', 1);
        $total = $required->countAllResults(false);
        $verified = $required->where('verification_status', 'verified')->countAllResults();

        return ['verified' => $verified, 'total' => $total, 'follow_up' => max(0, $total - $verified)];
    }

    private function pendingUploadsQuery(array $user)
    {
        $builder = $this->db->table('employee_documents')
            ->select('employee_documents.*, employees.nama AS employee_name, employees.nip, employees.unit_id, units.name AS unit_name')
            ->join('employees', 'employees.id = employee_documents.employee_id')
            ->join('units', 'units.id = employees.unit_id', 'left')
            ->where('employee_documents.verification_status', 'pending')
            ->where('employee_documents.stored_path !=', '');

        if (($user['role'] ?? '') === 'admin_unit') {
            $unitIds = array_map('intval', $user['unit_ids'] ?? []);
            if ($unitIds === []) {
                return null;
            }
            $builder->whereIn('employees.unit_id', $unitIds);
        } elseif (($user['role'] ?? '') === 'pegawai') {
            $builder->where('employees.user_id', (int) ($user['id'] ?? 0));
        }

        return $builder;
    }

    public function countPendingUploadsFor(array $user): int
    {
        $builder = $this->pendingUploadsQuery($user);

        return $builder === null ? 0 : (int) $builder->countAllResults();
    }

    public function pendingUploadsFor(array $user, ?int $limit = null, int $offset = 0): array
    {
        $builder = $this->pendingUploadsQuery($user);
        if ($builder === null) {
            return [];
        }

        // Keep the activity log chronological: older uploads appear first and newer uploads last.
        $builder->orderBy('employee_documents.updated_at', 'ASC')
            ->orderBy('employee_documents.id', 'ASC');
        $query = $limit === null ? $builder->get() : $builder->get($limit, max(0, $offset));

        return $query->getResultArray();
    }
}
