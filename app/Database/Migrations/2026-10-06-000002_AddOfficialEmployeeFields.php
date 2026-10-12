<?php

use CodeIgniter\Database\Migration;

class AddOfficialEmployeeFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('employees', [
            'alamat_rumah' => ['type' => 'TEXT', 'null' => true],
            'cpns_nomor_nota' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'cpns_tanggal_nota' => ['type' => 'DATE', 'null' => true],
            'cpns_nomor_sk' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'cpns_tanggal_sk' => ['type' => 'DATE', 'null' => true],
            'cpns_pejabat' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'cpns_tmt' => ['type' => 'DATE', 'null' => true],
            'cpns_pangkat_golongan' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'cpns_tmt_honorer' => ['type' => 'DATE', 'null' => true],
            'pns_nomor_sk' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'pns_tanggal_sk' => ['type' => 'DATE', 'null' => true],
            'pns_pejabat' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'pns_tmt' => ['type' => 'DATE', 'null' => true],
            'pns_pangkat_golongan' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'pns_sumpah_janji' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('employees', [
            'alamat_rumah',
            'cpns_nomor_nota', 'cpns_tanggal_nota', 'cpns_nomor_sk', 'cpns_tanggal_sk',
            'cpns_pejabat', 'cpns_tmt', 'cpns_pangkat_golongan', 'cpns_tmt_honorer',
            'pns_nomor_sk', 'pns_tanggal_sk', 'pns_pejabat', 'pns_tmt', 'pns_pangkat_golongan', 'pns_sumpah_janji',
        ]);
    }
}
