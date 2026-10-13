<?php

use CodeIgniter\Database\Migration;

class CreateRetirementBupSettings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'level_key' => ['type' => 'VARCHAR', 'constraint' => 40],
            'level_name' => ['type' => 'VARCHAR', 'constraint' => 80],
            'age' => ['type' => 'TINYINT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('level_key');
        $this->forge->createTable('retirement_bup_settings', true);
    }

    public function down()
    {
        $this->forge->dropTable('retirement_bup_settings', true);
    }
}
