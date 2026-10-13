<?php

use CodeIgniter\Database\Migration;

/**
 * Catatan perbaikan dari admin saat dokumen ditandai "perlu perbaikan", beserta siapa dan kapan
 * dokumen terakhir diverifikasi.
 */
class AddDocumentVerificationNote extends Migration
{
    public function up()
    {
        $this->forge->addColumn('employee_documents', [
            'verification_note' => ['type' => 'TEXT', 'null' => true, 'after' => 'verification_status'],
            'verified_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true, 'after' => 'verification_note'],
            'verified_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'verified_by'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('employee_documents', ['verification_note', 'verified_by', 'verified_at']);
    }
}
