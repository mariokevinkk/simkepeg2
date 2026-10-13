<?php

namespace App\Models;

use CodeIgniter\Model;

class RetirementBupModel extends Model
{
    protected $table = 'retirement_bup_settings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['level_key', 'level_name', 'age'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public static function defaults(): array
    {
        return [
            'ahli_utama' => ['name' => 'Keahlian Utama', 'age' => 65],
            'ahli_madya' => ['name' => 'Keahlian Madya', 'age' => 60],
            'ahli_muda' => ['name' => 'Keahlian Muda', 'age' => 58],
            'ahli_pertama' => ['name' => 'Keahlian Pertama', 'age' => 58],
            'keterampilan_penyelia' => ['name' => 'Keterampilan Penyelia', 'age' => 58],
            'keterampilan_mahir' => ['name' => 'Keterampilan Mahir', 'age' => 58],
            'keterampilan_terampil' => ['name' => 'Keterampilan Terampil', 'age' => 58],
        ];
    }

    /** Ensure defaults exist and return settings keyed by level key. */
    public function settings(): array
    {
        $stored = [];
        foreach ($this->findAll() as $row) {
            $stored[$row['level_key']] = $row;
        }
        foreach (self::defaults() as $key => $default) {
            if (! isset($stored[$key])) {
                $id = $this->insert(['level_key' => $key, 'level_name' => $default['name'], 'age' => $default['age']], true);
                $stored[$key] = ['id' => (int) $id, 'level_key' => $key, 'level_name' => $default['name'], 'age' => $default['age']];
            }
        }

        return $stored;
    }

    public function ages(): array
    {
        $ages = [];
        foreach ($this->settings() as $key => $setting) {
            $ages[$key] = (int) $setting['age'];
        }
        return $ages;
    }

    public function saveAges(array $input): bool
    {
        $settings = $this->settings();
        $validatedAges = [];
        foreach (self::defaults() as $key => $default) {
            $age = filter_var($input[$key] ?? null, FILTER_VALIDATE_INT);
            if ($age === false || $age < 50 || $age > 70) {
                return false;
            }
            $validatedAges[$key] = $age;
        }
        $this->db->transStart();
        foreach ($validatedAges as $key => $age) {
            $this->update((int) $settings[$key]['id'], ['age' => $age]);
        }
        $this->db->transComplete();

        return $this->db->transStatus() !== false;
    }
}
