<?php

use App\Libraries\JobTitleCatalog;
use CodeIgniter\Database\Migration;

class CreateJobTitles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'group_name' => ['type' => 'VARCHAR', 'constraint' => 80],
            'title' => ['type' => 'VARCHAR', 'constraint' => 180],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'sort_order' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('title');
        $this->forge->addKey('group_name');
        $this->forge->createTable('job_titles', true);

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

    public function down()
    {
        $this->forge->dropTable('job_titles', true);
    }
}
