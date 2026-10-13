<?php

namespace App\Database\Seeds;

use App\Libraries\JobTitleCatalog;
use CodeIgniter\Database\Seeder;

class JobTitleSeeder extends Seeder
{
    public function run()
    {
        $existing = array_column($this->db->table('job_titles')->select('title')->get()->getResultArray(), 'title');
        $known = array_fill_keys(array_map('mb_strtolower', $existing), true);
        $rows = array_values(array_filter(
            JobTitleCatalog::rows(),
            static fn (array $row): bool => ! isset($known[mb_strtolower($row['title'])]),
        ));
        if ($rows !== []) {
            $this->db->table('job_titles')->insertBatch($rows);
        }
    }
}
