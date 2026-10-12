<?php
$documentMode = $document_mode ?? 'detail';
$isEditable   = $documentMode === 'edit';

$requiredDocuments = [
    [
        'id' => 'formulir-riwayat-hidup',
        'name' => 'Formulir Isian Pegawai / Daftar Riwayat Hidup',
        'linked_data' => 'A-F dan G-X Data Pegawai',
        'linked_tab' => 'tab-utama',
        'file' => 'formulir-isian-pegawai.pdf',
        'meta' => 'Diperbarui 12 Jun 2026',
        'status' => 'valid',
        'status_label' => 'Terverifikasi',
    ],
    [
        'id' => 'ijazah-transkrip',
        'name' => 'Ijazah dan Transkrip',
        'linked_data' => 'F dan J Pendidikan',
        'linked_tab' => 'tab-pendidikan',
        'file' => 'ijazah-s1-dan-transkrip.pdf',
        'meta' => 'Sesuai No. Ijazah IJZ/2007/001',
        'status' => 'valid',
        'status_label' => 'Terverifikasi',
    ],
    [
        'id' => 'sk-penugasan-rs',
        'name' => 'SK Penugasan di Rumah Sakit',
        'linked_data' => 'E Jabatan Terakhir dan H Riwayat Jabatan',
        'linked_tab' => 'tab-jabatan',
        'file' => 'sk-penugasan-rsud.pdf',
        'meta' => 'Perlu konfirmasi unit kerja',
        'status' => 'review',
        'status_label' => 'Perlu verifikasi',
    ],
    [
        'id' => 'uraian-tugas',
        'name' => 'Uraian Tugas',
        'linked_data' => 'E Jabatan Terakhir dan I Tugas Tambahan',
        'linked_tab' => 'tab-jabatan',
        'file' => 'uraian-tugas-perawat.pdf',
        'meta' => 'Ditetapkan 02 Jan 2026',
        'status' => 'valid',
        'status_label' => 'Terverifikasi',
    ],
    [
        'id' => 'ba-kredensial',
        'name' => 'Berita Acara Kredensial dan/atau Rekredensial',
        'linked_data' => 'Profil Klinis dan Kewenangan Klinis',
        'linked_tab' => 'tab-utama',
        'file' => 'ba-rekredensial-2025.pdf',
        'meta' => 'Masa berlaku hingga 13 Apr 2027',
        'status' => 'valid',
        'status_label' => 'Terverifikasi',
    ],
    [
        'id' => 'kewenangan-klinis',
        'name' => 'Kewenangan Klinis',
        'linked_data' => 'Profil Klinis dan Kewenangan Klinis',
        'linked_tab' => 'tab-utama',
        'file' => 'kewenangan-klinis-pk-iii.pdf',
        'meta' => 'Level PK III',
        'status' => 'valid',
        'status_label' => 'Terverifikasi',
    ],
    [
        'id' => 'penugasan-klinis',
        'name' => 'Penugasan Klinis / Clinical Appointment',
        'linked_data' => 'Profil Klinis dan Unit Kerja',
        'linked_tab' => 'tab-utama',
        'file' => 'clinical-appointment-anggrek.pdf',
        'meta' => 'Bangsal Anggrek',
        'status' => 'valid',
        'status_label' => 'Terverifikasi',
    ],
    [
        'id' => 'evaluasi-kinerja-klinis',
        'name' => 'Evaluasi Kinerja Klinis',
        'linked_data' => 'Profil Klinis dan Penilaian Kinerja',
        'linked_tab' => 'tab-utama',
        'file' => 'evaluasi-kinerja-klinis-2025.pdf',
        'meta' => 'Evaluasi berikutnya: Des 2026',
        'status' => 'review',
        'status_label' => 'Perlu verifikasi',
    ],
    [
        'id' => 'str-sip-sik',
        'name' => 'STR dan SIP/SIK',
        'linked_data' => 'Legalitas Profesi',
        'linked_tab' => 'tab-utama',
        'file' => 'str-dan-sip.pdf',
        'meta' => 'SIP berakhir 25 Agu 2026',
        'status' => 'attention',
        'status_label' => 'Perlu diperbarui',
    ],
    [
        'id' => 'sertifikat-kompetensi',
        'name' => 'Sertifikat Kompetensi',
        'linked_data' => 'Profil Klinis dan Kompetensi',
        'linked_tab' => 'tab-utama',
        'file' => 'sertifikat-kompetensi-perawat.pdf',
        'meta' => 'Kompetensi Perawat',
        'status' => 'valid',
        'status_label' => 'Terverifikasi',
    ],
    [
        'id' => 'sertifikat-pelatihan',
        'name' => 'Sertifikat Pelatihan',
        'linked_data' => 'J Pendidikan dan Pelatihan',
        'linked_tab' => 'tab-pendidikan',
        'file' => 'sertifikat-pelatihan-2026.pdf',
        'meta' => 'Capaian 15 dari 20 JPL',
        'status' => 'attention',
        'status_label' => 'Belum memenuhi JPL',
    ],
    [
        'id' => 'penilaian-kinerja-skp',
        'name' => 'Penilaian Kinerja SKP',
        'linked_data' => 'Profil Klinis dan Riwayat Jabatan',
        'linked_tab' => 'tab-jabatan',
        'file' => 'skp-2025.pdf',
        'meta' => 'Nilai Baik',
        'status' => 'valid',
        'status_label' => 'Terverifikasi',
    ],
    [
        'id' => 'verifikasi-ijazah',
        'name' => 'Bukti Verifikasi Ijazah',
        'linked_data' => 'F dan J Pendidikan',
        'linked_tab' => 'tab-pendidikan',
        'file' => 'verifikasi-ijazah-s1.pdf',
        'meta' => 'Terverifikasi oleh kepegawaian',
        'status' => 'valid',
        'status_label' => 'Terverifikasi',
    ],
];

$additionalDocuments = [
    [
        'id' => 'sk-kepegawaian',
        'name' => 'SK Kepegawaian',
        'linked_data' => 'B-E serta G-I Riwayat Kepegawaian',
        'linked_tab' => 'tab-pangkat',
        'file' => 'sk-cpns-pns-pangkat-jabatan.pdf',
        'meta' => 'CPNS, PNS, pangkat, jabatan, fungsional, KGB, PMK, dan PUPNS',
        'status' => 'valid',
        'status_label' => 'Lengkap',
    ],
    [
        'id' => 'kartu-kepegawaian',
        'name' => 'KARPEG, KTP, KARIS/KARSU, TASPEN, ASKES/BPJS',
        'linked_data' => 'A Identitas Pegawai',
        'linked_tab' => 'tab-utama',
        'file' => 'kartu-identitas-kepegawaian.pdf',
        'meta' => '4 dari 5 kartu terunggah',
        'status' => 'review',
        'status_label' => 'Perlu dilengkapi',
    ],
    [
        'id' => 'riwayat-pekerjaan',
        'name' => 'Daftar Riwayat Pekerjaan',
        'linked_data' => 'P Pengalaman Kerja',
        'linked_tab' => 'tab-lainnya',
        'file' => '',
        'meta' => 'Belum ada berkas',
        'status' => 'missing',
        'status_label' => 'Belum diunggah',
    ],
    [
        'id' => 'sk-hukuman-disiplin',
        'name' => 'SK Hukuman Disiplin',
        'linked_data' => 'R Riwayat Sanksi Administratif',
        'linked_tab' => 'tab-lainnya',
        'file' => '',
        'meta' => 'Tidak ada catatan sanksi',
        'status' => 'valid',
        'status_label' => 'Tidak ada catatan',
    ],
    [
        'id' => 'ijazah-sttpl-piagam',
        'name' => 'Ijazah STTPL / Piagam',
        'linked_data' => 'J Pendidikan, K Tanda Jasa, dan L Prestasi',
        'linked_tab' => 'tab-pendidikan',
        'file' => 'piagam-dan-sertifikat-pendidikan.pdf',
        'meta' => 'Satu berkas terverifikasi',
        'status' => 'valid',
        'status_label' => 'Lengkap',
    ],
    [
        'id' => 'kp4-c1',
        'name' => 'Daftar Keluarga KP4 dan C1',
        'linked_data' => 'T-X Data Keluarga',
        'linked_tab' => 'tab-keluarga',
        'file' => 'kp4-dan-c1.pdf',
        'meta' => 'Menunggu verifikasi keluarga',
        'status' => 'review',
        'status_label' => 'Perlu verifikasi',
    ],
    [
        'id' => 'perubahan-keluarga',
        'name' => 'Akta Nikah, Kelahiran, dan Kematian',
        'linked_data' => 'T-X Data Keluarga',
        'linked_tab' => 'tab-keluarga',
        'file' => 'akta-nikah-dan-kelahiran.pdf',
        'meta' => 'Dokumen perubahan susunan keluarga',
        'status' => 'valid',
        'status_label' => 'Lengkap',
    ],
];

$verifiedRequired = count(array_filter($requiredDocuments, static fn (array $document): bool => $document['status'] === 'valid'));
$followUpRequired = count($requiredDocuments) - $verifiedRequired;
?>

<style>
    .document-hub {
        color: var(--text-main);
    }
    .document-hero {
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.12), rgba(14, 165, 233, 0.06));
        border: 1px solid rgba(67, 97, 238, 0.16);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
    }
    .document-hero h3 {
        margin: 0 0 6px;
        color: var(--text-main);
        font-size: 20px;
    }
    .document-hero p {
        margin: 0;
        color: var(--text-muted);
        font-size: 13px;
        line-height: 1.6;
        max-width: 680px;
    }
    .document-hero-icon {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--primary);
        color: #fff;
        margin-right: 14px;
        font-size: 19px;
        flex: 0 0 auto;
    }
    .document-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }
    .document-summary-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px;
        background: var(--bg-surface);
    }
    .document-summary-card .document-summary-label {
        color: var(--text-muted);
        font-size: 12px;
        display: block;
        margin-bottom: 8px;
    }
    .document-summary-card strong {
        font-size: 22px;
        line-height: 1;
        color: var(--text-main);
    }
    .document-summary-card .document-summary-detail {
        display: block;
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 7px;
    }
    .document-summary-card.is-success { border-top: 3px solid var(--success); }
    .document-summary-card.is-warning { border-top: 3px solid var(--warning); }
    .document-summary-card.is-danger { border-top: 3px solid var(--danger); }
    .document-section {
        margin-top: 28px;
    }
    .document-section-header {
        display: flex;
        gap: 14px;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .document-section-header h4 {
        margin: 0;
        font-size: 16px;
        color: var(--text-main);
    }
    .document-section-header p {
        margin: 5px 0 0;
        font-size: 12px;
        line-height: 1.5;
        color: var(--text-muted);
    }
    .document-filter {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }
    .document-filter-button {
        border: 1px solid var(--border);
        border-radius: 20px;
        background: var(--bg-surface);
        color: var(--text-muted);
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        padding: 7px 12px;
    }
    .document-filter-button.active,
    .document-filter-button:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }
    .document-table-wrap {
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow-x: auto;
        background: var(--bg-surface);
    }
    .document-table {
        width: 100%;
        min-width: 920px;
        border-collapse: collapse;
    }
    .document-table th,
    .document-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        text-align: left;
    }
    .document-table th {
        background: var(--bg-body);
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .document-table tbody tr:last-child td {
        border-bottom: 0;
    }
    .document-table tbody tr:hover td {
        background: rgba(67, 97, 238, 0.03);
    }
    .document-index {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: var(--primary-light);
        color: var(--primary);
        font-size: 12px;
        font-weight: 700;
    }
    .document-name {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.4;
    }
    .document-meta {
        display: block;
        color: var(--text-muted);
        font-size: 11px;
        line-height: 1.45;
        margin-top: 4px;
    }
    .document-link {
        padding: 0;
        border: 0;
        background: transparent;
        color: var(--primary);
        font-size: 12px;
        line-height: 1.45;
        cursor: pointer;
        font-weight: 600;
        text-align: left;
    }
    .document-link:hover {
        text-decoration: underline;
    }
    .document-file {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        color: var(--text-main);
    }
    .document-file i {
        color: var(--danger);
        font-size: 15px;
    }
    .document-file.is-empty {
        color: var(--text-muted);
    }
    .document-file.is-empty i {
        color: var(--text-muted);
    }
    .document-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 999px;
        padding: 5px 9px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }
    .document-status.valid {
        color: #047857;
        background: #d1fae5;
    }
    .document-status.review {
        color: #a16207;
        background: #fef3c7;
    }
    .document-status.attention,
    .document-status.missing {
        color: #b91c1c;
        background: #fee2e2;
    }
    .document-action {
        white-space: nowrap;
    }
    .document-action-button,
    .document-upload-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 32px;
        padding: 7px 10px;
        border: 1px solid var(--border);
        border-radius: 7px;
        background: var(--bg-surface);
        color: var(--primary);
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
    }
    .document-action-button:hover,
    .document-upload-button:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }
    .document-upload-button input {
        display: none;
    }
    .document-upload-note {
        font-size: 11px;
        color: var(--text-muted);
        margin: 9px 0 0;
    }
    @media (max-width: 900px) {
        .document-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 600px) {
        .document-hero {
            align-items: flex-start;
            flex-direction: column;
        }
        .document-summary {
            grid-template-columns: 1fr;
        }
        .document-section-header {
            flex-direction: column;
        }
    }
</style>

<div class="document-hub">
    <section class="document-hero">
        <div style="display: flex; align-items: flex-start;">
            <span class="document-hero-icon"><i class="fa-solid fa-folder-tree"></i></span>
            <div>
                <h3>Dokumen Pegawai dan Verifikasi</h3>
                <p>Setiap berkas dihubungkan ke bagian isian pegawai yang menjadi sumbernya. Checklist ini berlaku untuk tenaga medis, perawat, dan tenaga kesehatan lain.</p>
            </div>
        </div>
        <?php if ($isEditable): ?>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('dokumen-wajib').scrollIntoView({behavior: 'smooth'})">
                <i class="fa-solid fa-cloud-arrow-up"></i> Unggah Berkas
            </button>
        <?php else: ?>
            <a class="btn btn-primary" href="<?= base_url('/edit-pegawai?role=' . (isset($role) ? $role : '') . '&tab=utama') ?>" style="text-decoration: none;">
                <i class="fa-solid fa-pen-to-square"></i> Kelola Dokumen
            </a>
        <?php endif; ?>
    </section>

    <div class="document-summary">
        <div class="document-summary-card">
            <span class="document-summary-label">Dokumen wajib</span>
            <strong><?= count($requiredDocuments) ?></strong>
            <span class="document-summary-detail">untuk tenaga kesehatan</span>
        </div>
        <div class="document-summary-card is-success">
            <span class="document-summary-label">Terverifikasi</span>
            <strong><?= $verifiedRequired ?></strong>
            <span class="document-summary-detail">dokumen wajib lengkap</span>
        </div>
        <div class="document-summary-card is-warning">
            <span class="document-summary-label">Perlu tindak lanjut</span>
            <strong><?= $followUpRequired ?></strong>
            <span class="document-summary-detail">verifikasi atau pembaruan</span>
        </div>
        <div class="document-summary-card is-danger">
            <span class="document-summary-label">Legalitas profesi</span>
            <strong>1</strong>
            <span class="document-summary-detail">SIP perlu diperbarui</span>
        </div>
    </div>

    <section class="document-section" id="dokumen-wajib">
        <div class="document-section-header">
            <div>
                <h4><i class="fa-solid fa-shield-heart" style="color: var(--primary);"></i> File wajib</h4>
                <p>Dokumen inti yang mendukung identitas, pendidikan, penugasan, kredensial, kewenangan klinis, dan legalitas profesi.</p>
            </div>
        </div>
        <div class="document-filter" aria-label="Filter status dokumen">
            <button type="button" class="document-filter-button active" onclick="filterEmployeeDocuments('all', this)">Semua</button>
            <button type="button" class="document-filter-button" onclick="filterEmployeeDocuments('valid', this)">Terverifikasi</button>
            <button type="button" class="document-filter-button" onclick="filterEmployeeDocuments('review', this)">Perlu verifikasi</button>
            <button type="button" class="document-filter-button" onclick="filterEmployeeDocuments('attention', this)">Perlu diperbarui</button>
        </div>
        <div class="document-table-wrap">
            <table class="document-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Dokumen</th>
                        <th>Terhubung ke isian</th>
                        <th>Berkas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requiredDocuments as $index => $document): ?>
                        <tr class="document-row" id="document-<?= esc($document['id']) ?>" data-document-status="<?= esc($document['status']) ?>">
                            <td><span class="document-index"><?= $index + 1 ?></span></td>
                            <td>
                                <span class="document-name"><?= esc($document['name']) ?></span>
                                <span class="document-meta"><?= esc($document['meta']) ?></span>
                            </td>
                            <td>
                                <button type="button" class="document-link" onclick="openEmployeeDataSection('<?= esc($document['linked_tab']) ?>')">
                                    <i class="fa-solid fa-link"></i> <?= esc($document['linked_data']) ?>
                                </button>
                            </td>
                            <td>
                                <span class="document-file <?= $document['file'] === '' ? 'is-empty' : '' ?>" data-document-file>
                                    <i class="fa-solid <?= $document['file'] === '' ? 'fa-file-circle-xmark' : 'fa-file-pdf' ?>"></i>
                                    <span><?= $document['file'] === '' ? 'Belum ada berkas' : esc($document['file']) ?></span>
                                </span>
                            </td>
                            <td>
                                <span class="document-status <?= esc($document['status']) ?>" data-document-status-label>
                                    <i class="fa-solid <?= $document['status'] === 'valid' ? 'fa-circle-check' : 'fa-circle-exclamation' ?>"></i>
                                    <?= esc($document['status_label']) ?>
                                </span>
                            </td>
                            <td class="document-action">
                                <?php if ($isEditable): ?>
                                    <label class="document-upload-button">
                                        <input type="file" accept=".pdf,.jpg,.jpeg,.png" onchange="registerDocumentSelection(this, '<?= esc($document['id']) ?>')">
                                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Pilih file
                                    </label>
                                <?php else: ?>
                                    <button type="button" class="document-action-button" onclick="openEmployeeDataSection('<?= esc($document['linked_tab']) ?>')">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka data
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($isEditable): ?>
            <p class="document-upload-note">Format yang dapat dipilih: PDF, JPG, atau PNG. Berkas yang baru dipilih akan ditandai untuk verifikasi saat data pegawai disimpan.</p>
        <?php endif; ?>
    </section>

    <section class="document-section">
        <div class="document-section-header">
            <div>
                <h4><i class="fa-solid fa-folder-plus" style="color: var(--primary);"></i> File tambahan</h4>
                <p>Berkas pendukung kepegawaian, riwayat pekerjaan, dan perubahan data keluarga.</p>
            </div>
        </div>
        <div class="document-table-wrap">
            <table class="document-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Dokumen</th>
                        <th>Terhubung ke isian</th>
                        <th>Berkas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($additionalDocuments as $index => $document): ?>
                        <tr class="document-row" id="document-<?= esc($document['id']) ?>" data-document-status="<?= esc($document['status']) ?>">
                            <td><span class="document-index"><?= $index + 1 ?></span></td>
                            <td>
                                <span class="document-name"><?= esc($document['name']) ?></span>
                                <span class="document-meta"><?= esc($document['meta']) ?></span>
                            </td>
                            <td>
                                <button type="button" class="document-link" onclick="openEmployeeDataSection('<?= esc($document['linked_tab']) ?>')">
                                    <i class="fa-solid fa-link"></i> <?= esc($document['linked_data']) ?>
                                </button>
                            </td>
                            <td>
                                <span class="document-file <?= $document['file'] === '' ? 'is-empty' : '' ?>" data-document-file>
                                    <i class="fa-solid <?= $document['file'] === '' ? 'fa-file-circle-xmark' : 'fa-file-pdf' ?>"></i>
                                    <span><?= $document['file'] === '' ? 'Belum ada berkas' : esc($document['file']) ?></span>
                                </span>
                            </td>
                            <td>
                                <span class="document-status <?= esc($document['status']) ?>" data-document-status-label>
                                    <i class="fa-solid <?= $document['status'] === 'valid' ? 'fa-circle-check' : 'fa-circle-exclamation' ?>"></i>
                                    <?= esc($document['status_label']) ?>
                                </span>
                            </td>
                            <td class="document-action">
                                <?php if ($isEditable): ?>
                                    <label class="document-upload-button">
                                        <input type="file" accept=".pdf,.jpg,.jpeg,.png" onchange="registerDocumentSelection(this, '<?= esc($document['id']) ?>')">
                                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Pilih file
                                    </label>
                                <?php else: ?>
                                    <button type="button" class="document-action-button" onclick="openEmployeeDataSection('<?= esc($document['linked_tab']) ?>')">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka data
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    function filterEmployeeDocuments(status, button) {
        document.querySelectorAll('.document-row').forEach(function (row) {
            row.hidden = status !== 'all' && row.dataset.documentStatus !== status;
        });

        document.querySelectorAll('.document-filter-button').forEach(function (filterButton) {
            filterButton.classList.remove('active');
        });
        button.classList.add('active');
    }

    function openEmployeeDataSection(tabId) {
        const tab = document.getElementById(tabId);
        const tabButton = document.querySelector('[data-employee-tab="' + tabId + '"]');

        if (tab && typeof switchTab === 'function') {
            switchTab(tabId, tabButton);
            tab.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function registerDocumentSelection(input, documentId) {
        if (!input.files || input.files.length === 0) {
            return;
        }

        const row = document.getElementById('document-' + documentId);
        if (!row) {
            return;
        }

        const filename = input.files[0].name;
        const file = row.querySelector('[data-document-file]');
        const filenameElement = file.querySelector('span');
        const status = row.querySelector('[data-document-status-label]');

        file.classList.remove('is-empty');
        file.querySelector('i').className = 'fa-solid fa-file-arrow-up';
        filenameElement.textContent = filename;
        status.className = 'document-status review';
        status.innerHTML = '<i class="fa-solid fa-clock"></i> Menunggu simpan';
        row.dataset.documentStatus = 'review';
    }
</script>
