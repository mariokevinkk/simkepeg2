<?php

use CodeIgniter\Database\Migration;

class CreateEmployeeAuthSchema extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 120],
            'code' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'type' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'unit'],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('units', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'unit_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'nip' => ['type' => 'VARCHAR', 'constraint' => 40],
            'nip_lama' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => ''],
            'nik' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => ''],
            'gelar_depan' => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => ''],
            'gelar_belakang' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 150],
            'tempat_lahir' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'tanggal_lahir' => ['type' => 'DATE', 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 190, 'default' => ''],
            'jenis_kelamin' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => ''],
            'agama' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => ''],
            'status_perkawinan' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => ''],
            'jenis_kepegawaian' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => ''],
            'employee_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'Aktif'],
            'kedudukan' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Aktif'],
            'jalan' => ['type' => 'VARCHAR', 'constraint' => 180, 'default' => ''],
            'rt_rw' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => ''],
            'kelurahan' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'kecamatan' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'kabupaten' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'provinsi' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'telepon' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => ''],
            'telepon_darurat' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => ''],
            'karpeg' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => ''],
            'kartu_asn' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => ''],
            'bpjs' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => ''],
            'taspen' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => ''],
            'karis_karsu' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => ''],
            'npwp' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => ''],
            'jenis_ketenagaan' => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => ''],
            'sip_nomor' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'sip_terbit' => ['type' => 'DATE', 'null' => true],
            'sip_berlaku' => ['type' => 'DATE', 'null' => true],
            'str_nomor' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'str_terbit' => ['type' => 'DATE', 'null' => true],
            'str_berlaku' => ['type' => 'DATE', 'null' => true],
            'kredensial_nomor' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'kredensial_berlaku' => ['type' => 'DATE', 'null' => true],
            'kewenangan' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'penugasan_klinis' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'evaluasi_terakhir' => ['type' => 'DATE', 'null' => true],
            'kompetensi' => ['type' => 'VARCHAR', 'constraint' => 180, 'default' => ''],
            'pelatihan_jpl' => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => ''],
            'jpl_completed' => ['type' => 'INT', 'default' => 0],
            'nilai_skp' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'jenjang_jabatan' => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => ''],
            'pangkat_nomor' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'pangkat_tanggal' => ['type' => 'DATE', 'null' => true],
            'pangkat_tmt' => ['type' => 'DATE', 'null' => true],
            'pangkat_nama' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'masa_kerja' => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => ''],
            'pangkat_pejabat' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'jabatan_nomor' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'jabatan_tanggal' => ['type' => 'DATE', 'null' => true],
            'jabatan_tmt' => ['type' => 'DATE', 'null' => true],
            'jabatan_nama' => ['type' => 'VARCHAR', 'constraint' => 180, 'default' => ''],
            'unit_kerja' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'eselon' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => ''],
            'jabatan_pejabat' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nip');
        $this->forge->addUniqueKey('user_id');
        $this->forge->addKey('unit_id');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('employees', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'username' => ['type' => 'VARCHAR', 'constraint' => 80],
            'email' => ['type' => 'VARCHAR', 'constraint' => 190],
            'nip' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pegawai'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
            'permissions' => ['type' => 'TEXT', 'null' => true],
            'last_login' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('nip');
        $this->forge->addKey('employee_id');
        $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('users', true);

        $this->forge->addField([
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'unit_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey(['user_id', 'unit_id'], true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_units', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'tingkat' => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => ''],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'instansi' => ['type' => 'VARCHAR', 'constraint' => 180, 'default' => ''],
            'fakultas' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'tempat' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'nomor_ijazah' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'tanggal_ijazah' => ['type' => 'DATE', 'null' => true],
            'nilai' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => ''],
            'status' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => ''],
            'pejabat' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => ''],
            'is_current' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['employee_id', 'is_current']);
        $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employee_educations', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'education_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'group_key' => ['type' => 'VARCHAR', 'constraint' => 40],
            'document_key' => ['type' => 'VARCHAR', 'constraint' => 80],
            'title' => ['type' => 'VARCHAR', 'constraint' => 180],
            'description' => ['type' => 'TEXT', 'null' => true],
            'original_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
            'stored_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
            'mime_type' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'file_size' => ['type' => 'BIGINT', 'unsigned' => true, 'default' => 0],
            'is_required' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'verification_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'missing'],
            'uploaded_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['employee_id', 'group_key']);
        $this->forge->addKey('education_id');
        $this->forge->addUniqueKey(['employee_id', 'education_id', 'group_key', 'document_key']);
        $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('education_id', 'employee_educations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('uploaded_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('employee_documents', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => 40],
            'data' => ['type' => 'TEXT'],
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['employee_id', 'category', 'sort_order']);
        $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employee_histories', true);
    }

    public function down()
    {
        $this->forge->dropTable('employee_histories', true);
        $this->forge->dropTable('employee_documents', true);
        $this->forge->dropTable('employee_educations', true);
        $this->forge->dropTable('user_units', true);
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('employees', true);
        $this->forge->dropTable('units', true);
    }
}
