<?php

use CodeIgniter\Database\Migration;

class AddDocumentChecklistFields extends Migration
{
    public function up()
    {
        // Masa berlaku dokumen (jatuh tempo), dipakai untuk STR & SIP/SIK.
        $this->forge->addColumn('employee_documents', [
            'expires_at' => ['type' => 'DATE', 'null' => true, 'after' => 'verification_status'],
        ]);
        // Isian "Hubungan Keluarga" untuk No. Darurat pada formulir data pegawai.
        $this->forge->addColumn('employees', [
            'telepon_darurat_hubungan' => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => '', 'after' => 'telepon_darurat'],
        ]);

        // Dokumen lama dikelompokkan ulang sesuai checklist (wajib / tambahan).
        $this->db->table('employee_documents')
            ->whereIn('document_key', ['formulir-riwayat-hidup', 'ijazah-transkrip', 'sk-penugasan-rs'])
            ->update(['group_key' => 'wajib', 'is_required' => 1]);
        $this->db->table('employee_documents')
            ->whereIn('document_key', ['kartu-kepegawaian', 'sk-kepegawaian', 'kp4-c1', 'perubahan-keluarga', 'riwayat-pekerjaan', 'sk-hukuman-disiplin'])
            ->update(['group_key' => 'tambahan', 'is_required' => 0]);
    }

    public function down()
    {
        $this->forge->dropColumn('employee_documents', 'expires_at');
        $this->forge->dropColumn('employees', 'telepon_darurat_hubungan');
    }
}
