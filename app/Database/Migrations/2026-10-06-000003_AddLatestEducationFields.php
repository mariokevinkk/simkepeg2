<?php

use CodeIgniter\Database\Migration;

class AddLatestEducationFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('employees', [
            'pendidikan_terakhir_tingkat' => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => ''],
            'pendidikan_terakhir_nama' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'pendidikan_terakhir_fakultas' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'pendidikan_terakhir_instansi' => ['type' => 'VARCHAR', 'constraint' => 180, 'default' => ''],
            'pendidikan_terakhir_status' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => ''],
            'pendidikan_terakhir_tempat' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'pendidikan_terakhir_pejabat' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'pendidikan_terakhir_nomor_ijazah' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'pendidikan_terakhir_tanggal_ijazah' => ['type' => 'DATE', 'null' => true],
            'pendidikan_terakhir_nilai' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => ''],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('employees', [
            'pendidikan_terakhir_tingkat', 'pendidikan_terakhir_nama', 'pendidikan_terakhir_fakultas',
            'pendidikan_terakhir_instansi', 'pendidikan_terakhir_status', 'pendidikan_terakhir_tempat',
            'pendidikan_terakhir_pejabat', 'pendidikan_terakhir_nomor_ijazah',
            'pendidikan_terakhir_tanggal_ijazah', 'pendidikan_terakhir_nilai',
        ]);
    }
}
