<?php

use CodeIgniter\Database\Migration;

class AddEmployeeStartDate extends Migration
{
    public function up()
    {
        $this->forge->addColumn('employees', [
            'tanggal_masuk' => ['type' => 'DATE', 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('employees', 'tanggal_masuk');
    }
}
