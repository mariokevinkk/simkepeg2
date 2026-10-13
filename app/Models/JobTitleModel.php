<?php

namespace App\Models;

use CodeIgniter\Model;

class JobTitleModel extends Model
{
    protected $table = 'job_titles';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['group_name', 'title', 'is_active', 'sort_order'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    private array $optionCache = [];

    /** Semua jabatan katalog, dalam urutan daftar awal lalu tambahan admin. */
    public function allOptions(bool $includeInactive = false): array
    {
        $cacheKey = $includeInactive ? 'all' : 'active';
        if (isset($this->optionCache[$cacheKey])) {
            return $this->optionCache[$cacheKey];
        }
        $query = $this->select('id, group_name, title, is_active, sort_order');
        if (! $includeInactive) {
            $query->where('is_active', 1);
        }

        return $this->optionCache[$cacheKey] = $query->orderBy('sort_order')->orderBy('id')->findAll();
    }

    /** Peta jenis ketenagaan ke daftar nama jabatan. */
    public function allGrouped(bool $includeInactive = false): array
    {
        $groups = [];
        foreach ($this->allOptions($includeInactive) as $row) {
            $groups[$row['group_name']][] = $row['title'];
        }

        return $groups;
    }

    public function groupNames(bool $includeInactive = false): array
    {
        return array_keys($this->allGrouped($includeInactive));
    }

    /** Menyamakan ejaan data lama, termasuk nama jabatan penuh, dengan jenis katalog. */
    public function canonicalGroup(string $group): ?string
    {
        $group = trim(preg_replace('/\s+/u', ' ', $group) ?? $group);
        $value = mb_strtolower($group);
        if ($value === '') {
            return null;
        }
        if ($value === 'dokter umum' || str_starts_with($value, 'dokter umum ')) {
            return 'Dokter';
        }

        $options = $this->allOptions(true);
        foreach ($options as $job) {
            if (mb_strtolower($job['title']) === $value) {
                return $job['group_name'];
            }
        }

        $groups = array_values(array_unique(array_column($options, 'group_name')));
        usort($groups, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));
        foreach ($groups as $name) {
            $prefix = mb_strtolower($name);
            if ($value === $prefix || str_starts_with($value, $prefix . ' ')) {
                return $name;
            }
        }

        return null;
    }

    public function isValidPair(string $group, string $title, bool $includeInactive = false): bool
    {
        $group = mb_strtolower($this->canonicalGroup($group) ?? '');
        $title = mb_strtolower(trim($title));
        if ($group === '' || $title === '') {
            return false;
        }
        foreach ($this->allGrouped($includeInactive) as $groupName => $titles) {
            if (mb_strtolower($groupName) === $group) {
                foreach ($titles as $option) {
                    if (mb_strtolower($option) === $title) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
