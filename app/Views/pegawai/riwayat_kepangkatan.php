<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$employeeProfile = $employee_profile ?? \App\Libraries\EmployeeProfileData::get();
$employee = $employeeProfile['pegawai'];
$career = $employeeProfile['karir'];
?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Design Tokens & Elements */
    .hero-rank-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border-radius: 16px;
        color: white;
        padding: 28px 32px;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.35);
        position: relative;
        overflow: hidden;
    }
    .hero-rank-card::before {
        content: '';
        position: absolute;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        top: -80px;
        right: -40px;
        pointer-events: none;
    }
    .hero-rank-card::after {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        bottom: -60px;
        right: 120px;
        pointer-events: none;
    }

    .rank-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.3px;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .kp-tabs {
        display: flex;
        gap: 12px;
        border-bottom: 2px solid var(--border);
        margin-bottom: 24px;
    }
    .kp-tab-btn {
        padding: 12px 20px;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s ease;
        margin-bottom: -2px;
    }
    .kp-tab-btn:hover {
        color: var(--primary);
    }
    .kp-tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    .kp-tab-badge {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 700;
    }

    .badge-pending {
        background: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    .badge-approved {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .badge-rejected {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .pulsing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: currentColor;
        display: inline-block;
        animation: pulseAnimation 1.6s infinite;
    }
    @keyframes pulseAnimation {
        0% { transform: scale(0.95); opacity: 0.7; }
        50% { transform: scale(1.3); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.7; }
    }

    /* Upload Area Styling */
    .upload-dropzone {
        border: 2px dashed #93c5fd;
        background: #f8fafc;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    [data-theme="dark"] .upload-dropzone {
        background: #1e293b;
        border-color: #3b82f6;
    }
    .upload-dropzone:hover, .upload-dropzone.dragover {
        border-color: var(--primary);
        background: #eff6ff;
    }
    [data-theme="dark"] .upload-dropzone:hover {
        background: #1e3a8a33;
    }

    /* Modal Overlay & Card */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        padding: 20px;
        overflow-y: auto;
    }
    .custom-modal-card {
        background: var(--bg-surface);
        border-radius: 16px;
        width: 100%;
        max-width: 680px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: modalSlideUp 0.3s ease;
    }
    @keyframes modalSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .role-toggle-pill {
        display: inline-flex;
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 30px;
        padding: 4px;
        gap: 4px;
    }
    .role-toggle-btn {
        padding: 6px 14px;
        border-radius: 20px;
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .role-toggle-btn.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
    }
</style>

<!-- Top Bar: Header & Interactive Mode Toggle -->
<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 16px;">
    <div>
        <h1 class="page-title" style="margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-chart-line" style="color: var(--primary);"></i> Riwayat & Pengajuan Kepangkatan
        </h1>
        <p style="color: var(--text-muted); font-size: 13px; margin: 4px 0 0 0;">
            Kelola kenaikan pangkat, pengunggahan berkas SK, dan verifikasi persetujuan Admin Kepegawaian.
        </p>
    </div>
    
    <!-- Role View Simulator (Allows testing both Pegawai and Admin Kepegawaian perspectives) -->
    <div style="display: flex; align-items: center; gap: 10px;" class="flex-mobile-stack">
        <span style="font-size: 12px; font-weight: 600; color: var(--text-muted);"><i class="fa-solid fa-sliders"></i> Tampilan Mode:</span>
        <div class="role-toggle-pill">
            <button class="role-toggle-btn <?= (!isset($role) || $role === 'pegawai') ? 'active' : '' ?>" id="btnModePegawai" onclick="switchViewMode('pegawai')">
                <i class="fa-solid fa-user"></i> Pegawai
            </button>
            <button class="role-toggle-btn <?= (isset($role) && ($role === 'superadmin' || $role === 'admin')) ? 'active' : '' ?>" id="btnModeAdmin" onclick="switchViewMode('admin')">
                <i class="fa-solid fa-user-shield"></i> Admin Kepegawaian
            </button>
        </div>
    </div>
</div>

<!-- Mode Admin Alert Bar (Visible in Admin Kepegawaian Mode) -->
<div id="adminModeBanner" style="display: <?= (isset($role) && ($role === 'superadmin' || $role === 'admin')) ? 'flex' : 'none' ?>; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 20px; margin-bottom: 20px; align-items: center; justify-content: space-between; gap: 16px;" class="flex-mobile-stack">
    <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 36px; height: 36px; border-radius: 50%; background: #dcfce7; display: flex; justify-content: center; align-items: center; color: #16a34a; font-size: 18px; flex-shrink: 0;">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
            <div style="font-weight: 700; color: #166534; font-size: 14px;">Mode Admin Kepegawaian Aktif</div>
            <div style="font-size: 12px; color: #15803d;">Anda memiliki kewenangan untuk memverifikasi berkas SK usulan dan memberikan <strong>Persetujuan (Approve)</strong> atau <strong>Penolakan</strong> kenaikan pangkat pegawai.</div>
        </div>
    </div>
    <span class="status-badge badge-pending" style="font-size: 11px; white-space: nowrap;">
        <span class="pulsing-dot"></span> <span id="pendingBadgeCount">1 Menunggu Persetujuan</span>
    </span>
</div>

<!-- Hero Rank Status Card -->
<div class="hero-rank-card flex-mobile-stack">
    <div style="z-index: 1;">
        <div class="rank-pill" style="margin-bottom: 12px;">
            <i class="fa-solid fa-medal"></i> Pangkat & Golongan Saat Ini
        </div>
        <h2 id="currentRankTitle" style="margin: 0 0 6px 0; font-size: 26px; font-weight: 700; letter-spacing: -0.5px;"><?= esc($career['pangkat_nama']) ?></h2>
        <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 13px; opacity: 0.95; margin-top: 10px;">
            <div><i class="fa-regular fa-calendar-check"></i> <strong>TMT:</strong> <?= date('d-m-Y', strtotime($career['pangkat_tmt'])) ?></div>
            <div><i class="fa-solid fa-clock-rotate-left"></i> <strong>Masa Kerja:</strong> <?= esc($career['masa_kerja']) ?></div>
            <div><i class="fa-solid fa-hashtag"></i> <strong>No. SK Terakhir:</strong> <?= esc($career['pangkat_nomor']) ?></div>
        </div>
    </div>
    <div style="z-index: 1; display: flex; flex-direction: column; align-items: flex-end; gap: 10px;" class="flex-mobile-stack">
        <button class="btn" onclick="openPengajuanModal()" style="background: white; color: #1e3a8a; font-weight: 700; border: none; padding: 12px 20px; border-radius: 10px; box-shadow: 0 4px 14px rgba(0,0,0,0.15); display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fa-solid fa-file-circle-plus" style="color: var(--primary);"></i> Ajukan Kenaikan Pangkat
        </button>
        <span style="font-size: 12px; color: rgba(255,255,255,0.85);">
            <i class="fa-solid fa-circle-info"></i> Memerlukan unggah file SK & approval admin
        </span>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="kp-tabs">
    <button class="kp-tab-btn active" id="tabBtnPengajuan" onclick="switchKpTab('pengajuan')">
        <i class="fa-solid fa-clock"></i> Daftar Pengajuan Kenaikan Pangkat
        <span class="kp-tab-badge badge-pending" id="badgePengajuanCount">1 Menunggu</span>
    </button>
    <button class="kp-tab-btn" id="tabBtnRiwayat" onclick="switchKpTab('riwayat')">
        <i class="fa-solid fa-file-shield"></i> Riwayat Kepangkatan Sah (SK Definitif)
        <span class="kp-tab-badge badge-approved" id="badgeSahCount">1 Sah</span>
    </button>
</div>

<!-- TAB 1: DAFTAR PENGAJUAN (TRACKING & APPROVAL) -->
<div id="secPengajuan" class="main-card" style="padding: 0; overflow: hidden; margin-bottom: 32px;">
    <div class="filter-row" style="padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
        <div style="font-weight: 600; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
            <span>Tracking Status Pengajuan Kenaikan Pangkat</span>
            <span style="font-size: 12px; color: var(--text-muted); font-weight: normal;">(Data Terkini)</span>
        </div>
        <div style="font-size: 13px; color: var(--text-muted);">
            Pegawai: <strong><?= esc(trim($employee['nama'] . ', ' . $employee['gelar_belakang'], ', ')) ?></strong> (NIP: <?= esc($employee['nip']) ?>)
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO. PENGAJUAN</th>
                    <th>TGL PENGAJUAN</th>
                    <th>PANGKAT USULAN</th>
                    <th>JENIS KENAIKAN</th>
                    <th>TMT USULAN</th>
                    <th>BERKAS SK</th>
                    <th>STATUS APPROVAL</th>
                    <th style="text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody id="pengajuanTableBody">
                <!-- Row 1: Pengajuan Aktif yang Menunggu Approval -->
                <tr id="pengajuan-row-1">
                    <td>
                        <div style="font-weight: 700; color: var(--text-main); font-family: monospace;">KP-2026/09/004</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Usulan Kenaikan Pangkat</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">22 Sep 2026</div>
                        <div style="font-size: 11px; color: var(--text-muted);">14:30 WIB</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--primary);">Penata Muda Tingkat I, III/b</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Sebelumnya: Penata Muda (III/a)</div>
                    </td>
                    <td>
                        <span style="background: var(--bg-body); border: 1px solid var(--border); padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 500;">
                            Kenaikan Pangkat Reguler
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">01 Oktober 2026</div>
                    </td>
                    <td>
                        <button class="btn btn-outline" onclick="previewDokumenSK('SK_Kenaikan_Pangkat_Ahmad_2026.pdf', 'Penata Muda Tingkat I, III/b', '550/Pem.D/BP/KP/2026', '01-10-2026')" style="padding: 6px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; border-color: #fca5a5; background: #fff5f5; color: #dc2626;">
                            <i class="fa-solid fa-file-pdf"></i> SK_Kenaikan_Pangkat.pdf
                        </button>
                    </td>
                    <td id="statusCell-1">
                        <span class="status-badge badge-pending" style="display: inline-flex; align-items: center; gap: 6px;">
                            <span class="pulsing-dot"></span> Menunggu Approval Admin
                        </span>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Sedang ditinjau Admin Kepegawaian</div>
                    </td>
                    <td style="text-align: center; white-space: nowrap;" id="actionCell-1">
                        <!-- Action for Admin Kepegawaian Mode -->
                        <div class="action-admin-mode" style="display: <?= (isset($role) && ($role === 'superadmin' || $role === 'admin')) ? 'inline-flex' : 'none' ?>; gap: 8px;">
                            <button class="btn" onclick="previewDokumenSK('SK_Kenaikan_Pangkat_Ahmad_2026.pdf', 'Penata Muda Tingkat I, III/b', '550/Pem.D/BP/KP/2026', '01-10-2026')" title="Periksa Dokumen SK" style="padding: 6px 10px; font-size: 12px; background: var(--bg-body); border: 1px solid var(--border); color: var(--text-main);">
                                <i class="fa-solid fa-eye"></i> Periksa SK
                            </button>
                            <button class="btn btn-primary" onclick="approvePengajuan('pengajuan-row-1', 'Penata Muda Tingkat I, III/b', '01-10-2026')" style="padding: 6px 12px; font-size: 12px; background: var(--success); border-color: var(--success);" title="Setujui Kenaikan Pangkat">
                                <i class="fa-solid fa-check"></i> Setujui
                            </button>
                            <button class="btn btn-outline" onclick="rejectPengajuan('pengajuan-row-1')" style="padding: 6px 10px; font-size: 12px; color: var(--danger); border-color: var(--danger);" title="Tolak Pengajuan">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                        </div>

                        <!-- Action for Pegawai Mode -->
                        <div class="action-pegawai-mode" style="display: <?= (!isset($role) || $role === 'pegawai') ? 'inline-flex' : 'none' ?>; gap: 8px;">
                            <button class="btn btn-outline" onclick="previewDokumenSK('SK_Kenaikan_Pangkat_Ahmad_2026.pdf', 'Penata Muda Tingkat I, III/b', '550/Pem.D/BP/KP/2026', '01-10-2026')" style="padding: 6px 10px; font-size: 12px;" title="Lihat Berkas">
                                <i class="fa-solid fa-eye"></i> Detail & SK
                            </button>
                            <button class="btn btn-outline" onclick="cancelPengajuan('pengajuan-row-1')" style="padding: 6px 10px; font-size: 12px; color: var(--danger); border-color: var(--border);" title="Batalkan Pengajuan">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Empty Info Footer -->
    <div style="padding: 16px 24px; background: var(--bg-body); border-top: 1px solid var(--border); font-size: 12px; color: var(--text-muted); display: flex; align-items: center; justify-content: space-between;">
        <span><i class="fa-solid fa-shield-halved"></i> Setiap perubahan pangkat pegawai akan terverifikasi secara sah setelah dokumen SK disetujui oleh Admin Kepegawaian.</span>
        <span>Halaman 1 dari 1</span>
    </div>
</div>

<!-- TAB 2: RIWAYAT KEPANGKATAN SAH (SK DEFINITIF) -->
<div id="secRiwayatSah" class="main-card" style="padding: 0; overflow: hidden; display: none; margin-bottom: 32px;">
    <div class="filter-row" style="padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
        <div style="font-weight: 600; color: var(--text-main);">Daftar Riwayat Kepangkatan Resmi (SK Definitif)</div>
        <span style="font-size: 12px; color: var(--text-muted);"><i class="fa-solid fa-certificate" style="color: var(--success);"></i> Berkas Resmi Tersimpan di Arsip Kepegawaian</span>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>PANGKAT, GOL/RUANG</th>
                    <th>JENIS KENAIKAN</th>
                    <th>TMT</th>
                    <th>TGL SK</th>
                    <th>NO SK</th>
                    <th>MASA KERJA</th>
                    <th>ANGKA KREDIT</th>
                    <th>BERKAS SK</th>
                </tr>
            </thead>
            <tbody id="riwayatSahBody">
                <tr id="sah-row-1">
                    <td>1</td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);"><?= esc($career['pangkat_nama']) ?></div>
                        <span class="status-badge badge-approved" style="font-size: 10px; padding: 2px 6px;">Pangkat Aktif</span>
                    </td>
                    <td>Gol. dari Pengadaan CPNS/PNS</td>
                    <td><?= date('d-m-Y', strtotime($career['pangkat_tmt'])) ?></td>
                    <td><?= date('d-m-Y', strtotime($career['pangkat_tanggal'])) ?></td>
                    <td><span style="font-family: monospace;"><?= esc($career['pangkat_nomor']) ?></span></td>
                    <td><?= esc($career['masa_kerja']) ?></td>
                    <td>0</td>
                    <td>
                        <button class="btn btn-outline" onclick="previewDokumenSK('SK_Pengangkatan_PNS_IIIa.pdf', '<?= esc($career['pangkat_nama']) ?>', '<?= esc($career['pangkat_nomor']) ?>', '<?= date('d-m-Y', strtotime($career['pangkat_tmt'])) ?>')" style="padding: 4px 8px; font-size: 11px; color: var(--primary);">
                            <i class="fa-solid fa-file-pdf"></i> Unduh SK
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'kepangkatan', 'document_mode' => 'edit']) ?>
</div>

<!-- ========================================== -->
<!-- MODAL: FORM PENGAJUAN KENAIKAN PANGKAT     -->
<!-- ========================================== -->
<div id="modalPengajuanKP" class="custom-modal-overlay">
    <div class="custom-modal-card">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--bg-surface);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--primary-light); color: var(--primary); display: flex; justify-content: center; align-items: center; font-size: 18px;">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 17px; color: var(--text-main);">Pengajuan Kenaikan Pangkat Pegawai</h3>
                    <div style="font-size: 12px; color: var(--text-muted);">Lengkapi data usulan dan unggah berkas SK / Nota Usul</div>
                </div>
            </div>
            <button onclick="closePengajuanModal()" style="background: none; border: none; font-size: 18px; color: var(--text-muted); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="formKenaikanPangkat" onsubmit="handlePengajuanSubmit(event)" style="padding: 24px; max-height: calc(85vh - 120px); overflow-y: auto;">
            <!-- Info strip pegawai -->
            <div style="background: var(--bg-body); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 11px; text-transform: uppercase;">Nama Pegawai</span>
                    <strong style="color: var(--text-main);"><?= esc(trim($employee['nama'] . ', ' . $employee['gelar_belakang'], ', ')) ?></strong>
                    <div style="font-size: 12px; color: var(--text-muted);">NIP: <?= esc($employee['nip']) ?></div>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 11px; text-transform: uppercase;">Pangkat Saat Ini (Terkunci)</span>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                        <span class="status-badge" style="background: #e0e7ff; color: #3730a3; font-weight: 700;"><?= esc($career['pangkat_nama']) ?></span>
                        <i class="fa-solid fa-lock" style="font-size: 11px; color: var(--text-muted);"></i>
                    </div>
                </div>
            </div>

            <!-- Form fields -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <!-- Pangkat yang diajukan -->
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Pangkat & Golongan Baru yang Diajukan <span style="color: var(--danger);">*</span></label>
                    <select id="inputPangkatTujuan" class="form-control" required style="font-weight: 600;">
                        <option value="" disabled selected>-- Pilih Pangkat & Golongan Tujuan --</option>
                        <optgroup label="Golongan III (Penata)">
                            <option value="Penata Muda Tingkat I, III/b">Penata Muda Tingkat I (III/b)</option>
                            <option value="Penata, III/c">Penata (III/c)</option>
                            <option value="Penata Tingkat I, IIId">Penata Tingkat I (III/d)</option>
                        </optgroup>
                        <optgroup label="Golongan IV (Pembina)">
                            <option value="Pembina, IV/a">Pembina (IV/a)</option>
                            <option value="Pembina Tingkat I, IV/b">Pembina Tingkat I (IV/b)</option>
                            <option value="Pembina Utama Muda, IV/c">Pembina Utama Muda (IV/c)</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Jenis Kenaikan Pangkat -->
                <div>
                    <label class="form-label">Jenis Kenaikan Pangkat <span style="color: var(--danger);">*</span></label>
                    <select id="inputJenisKenaikan" class="form-control" required>
                        <option value="Kenaikan Pangkat Reguler" selected>Kenaikan Pangkat Reguler</option>
                        <option value="Pilihan (Jabatan Fungsional)">Pilihan (Jabatan Fungsional)</option>
                        <option value="Penyesuaian Ijazah">Penyesuaian Ijazah</option>
                        <option value="Pilihan (Struktural)">Pilihan (Struktural)</option>
                        <option value="Pengabdian">Pengabdian</option>
                    </select>
                </div>

                <!-- TMT yang diusulkan -->
                <div>
                    <label class="form-label">TMT Usulan <span style="color: var(--danger);">*</span></label>
                    <input type="date" id="inputTMTUsulan" class="form-control" value="2026-10-01" required />
                </div>

                <!-- Nomor SK/Usulan -->
                <div>
                    <label class="form-label">Nomor Rekomendasi / Usulan SK</label>
                    <input type="text" id="inputNoSK" class="form-control" placeholder="Contoh: 550/Pem.D/BP/KP/2026" />
                </div>

                <!-- Tanggal Rekomendasi/SK -->
                <div>
                    <label class="form-label">Tanggal Surat Rekomendasi / SK</label>
                    <input type="date" id="inputTglSK" class="form-control" value="2026-09-20" />
                </div>
            </div>

            <!-- COMPONENT: UPLOAD BERKAS SK / SURAT KEPUTUSAN -->
            <div style="margin-bottom: 20px;">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Upload Berkas SK / Dokumen Pendukung <span style="color: var(--danger);">*</span></span>
                    <span style="font-size: 11px; color: var(--text-muted);">Format: PDF, JPG, PNG (Maks 5 MB)</span>
                </label>

                <!-- Hidden native file input -->
                <input type="file" id="realFileInput" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="handleFileSelected(event)" />

                <!-- Dropzone Area -->
                <div class="upload-dropzone" id="uploadDropzone" onclick="document.getElementById('realFileInput').click()">
                    <div id="dropzonePrompt">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 38px; color: var(--primary); margin-bottom: 12px; display: block;"></i>
                        <div style="font-weight: 600; color: var(--text-main); font-size: 14px; margin-bottom: 4px;">
                            Pilih Berkas SK Kenaikan Pangkat atau Seret ke Sini
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted);">
                            Surat Keputusan (SK) Jabatan Terakhir / PAK / Usulan Kenaikan Pangkat
                        </div>
                        <button type="button" class="btn btn-outline" style="margin-top: 14px; padding: 6px 14px; font-size: 12px; pointer-events: none;">
                            <i class="fa-solid fa-folder-open"></i> Telusuri File
                        </button>
                    </div>

                    <!-- File Selected Preview Box (Hidden initially) -->
                    <div id="filePreviewContainer" style="display: none; align-items: center; justify-content: space-between; background: var(--bg-surface); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; text-align: left;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 8px; background: #fee2e2; color: #ef4444; display: flex; justify-content: center; align-items: center; font-size: 20px;">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div>
                                <div id="previewFileName" style="font-weight: 600; font-size: 13px; color: var(--text-main); max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    SK_Kenaikan_Pangkat.pdf
                                </div>
                                <div id="previewFileSize" style="font-size: 11px; color: var(--text-muted);">
                                    1.8 MB • Siap Diunggah
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="event.stopPropagation(); removeSelectedFile();" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 6px;" title="Hapus Berkas">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Catatan Pengajuan -->
            <div style="margin-bottom: 24px;">
                <label class="form-label">Catatan Tambahan untuk Admin Kepegawaian (Opsional)</label>
                <textarea id="inputCatatan" class="form-control" rows="2" placeholder="Tuliskan catatan atau keterangan tambahan terkait usulan kenaikan pangkat..."></textarea>
            </div>

            <!-- Action buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border); padding-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="closePengajuanModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitPengajuan" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: PREVIEW DOKUMEN SK                  -->
<!-- ========================================== -->
<div id="modalPreviewSK" class="custom-modal-overlay">
    <div class="custom-modal-card" style="max-width: 650px;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-file-pdf" style="color: #ef4444; font-size: 20px;"></i>
                <h4 style="margin: 0; font-size: 16px;" id="previewModalTitle">Preview Berkas SK</h4>
            </div>
            <button onclick="closePreviewSK()" style="background: none; border: none; font-size: 18px; color: var(--text-muted); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- Simulated Document Paper Sheet -->
        <div style="padding: 24px; background: var(--bg-body); max-height: calc(80vh - 120px); overflow-y: auto;">
            <div style="background: white; border: 1px solid #cbd5e1; border-radius: 8px; padding: 32px 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); color: #0f172a; font-family: 'Times New Roman', serif;">
                <!-- Kop Surat -->
                <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px;">
                    <div style="font-size: 13px; font-weight: bold; text-transform: uppercase;">Pemerintah Kota Yogyakarta</div>
                    <div style="font-size: 16px; font-weight: bold; text-transform: uppercase;">Rumah Sakit Umum Daerah Kota Yogyakarta</div>
                    <div style="font-size: 10px; font-family: Inter, sans-serif; color: #475569; margin-top: 2px;">Jl. Wirosaban No. 1, Sorosutan, Kec. Umbulharjo, Kota Yogyakarta, D.I. Yogyakarta 55162</div>
                </div>

                <!-- Judul Dokumen -->
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 14px; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KEPUTUSAN KENAIKAN PANGKAT</div>
                    <div style="font-size: 11px; font-family: Inter, sans-serif;" id="previewDocNo">NOMOR: 550/Pem.D/BP/KP/2026</div>
                </div>

                <p style="font-size: 12px; text-align: justify; line-height: 1.6; margin-bottom: 14px;">
                    Berdasarkan hasil penilaian kinerja dan pemenuhan syarat masa kerja serta angka kredit yang ditetapkan, dengan ini menerangkan bahwa:
                </p>

                <!-- Data Pegawai -->
                <table style="width: 100%; font-size: 12px; margin-bottom: 16px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 140px; padding: 4px 0;">Nama Pegawai</td>
                        <td style="width: 15px;">:</td>
                        <td><strong><?= esc(trim($employee['nama'] . ', ' . $employee['gelar_belakang'], ', ')) ?></strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">NIP</td>
                        <td>:</td>
                        <td><?= esc($employee['nip']) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Pangkat / Golongan Baru</td>
                        <td>:</td>
                        <td><strong id="previewDocPangkat" style="color: #1e3a8a;">Penata Muda Tingkat I, III/b</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Terhitung Mulai Tanggal</td>
                        <td>:</td>
                        <td id="previewDocTMT">01 Oktober 2026</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Unit Kerja</td>
                        <td>:</td>
                        <td>RSUD Kota Yogyakarta</td>
                    </tr>
                </table>

                <p style="font-size: 12px; text-align: justify; line-height: 1.6; margin-bottom: 24px;">
                    Ditetapkan di Yogyakarta untuk dipergunakan sebagaimana mestinya dan dicatatkan pada Buku Induk Pegawai RSUD Kota Yogyakarta.
                </p>

                <!-- Tanda Tangan -->
                <div style="display: flex; justify-content: flex-end;">
                    <div style="text-align: center; width: 220px; font-size: 12px;">
                        <div>Yogyakarta, 20 September 2026</div>
                        <div style="font-weight: bold; margin-bottom: 40px;">a.n. Wali Kota Yogyakarta<br>Sekretaris Daerah</div>
                        <div style="text-decoration: underline; font-weight: bold;">Drs. H. Hardono, M.Si</div>
                        <div style="font-size: 10px; font-family: Inter, sans-serif;">NIP. 196804151994031002</div>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 16px 20px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 12px; color: var(--text-muted);"><i class="fa-solid fa-lock"></i> Dokumen Resmi Terverifikasi</span>
            <div style="display: flex; gap: 8px;">
                <button class="btn btn-outline" onclick="closePreviewSK()">Tutup</button>
                <button class="btn btn-primary" onclick="alert('Mengunduh dokumen SK...')"><i class="fa-solid fa-download"></i> Unduh File PDF</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Global simulated state
    let currentRole = "<?= isset($role) ? $role : 'pegawai' ?>";
    let selectedFile = null;

    // Switch tabs
    function switchKpTab(tab) {
        const btnPengajuan = document.getElementById('tabBtnPengajuan');
        const btnRiwayat = document.getElementById('tabBtnRiwayat');
        const secPengajuan = document.getElementById('secPengajuan');
        const secRiwayatSah = document.getElementById('secRiwayatSah');

        if(tab === 'pengajuan') {
            btnPengajuan.classList.add('active');
            btnRiwayat.classList.remove('active');
            secPengajuan.style.display = 'block';
            secRiwayatSah.style.display = 'none';
        } else {
            btnRiwayat.classList.add('active');
            btnPengajuan.classList.remove('active');
            secRiwayatSah.style.display = 'block';
            secPengajuan.style.display = 'none';
        }
    }

    // Role View Simulator Switcher
    function switchViewMode(mode) {
        currentRole = mode;
        const btnPegawai = document.getElementById('btnModePegawai');
        const btnAdmin = document.getElementById('btnModeAdmin');
        const banner = document.getElementById('adminModeBanner');
        const adminActions = document.querySelectorAll('.action-admin-mode');
        const pegawaiActions = document.querySelectorAll('.action-pegawai-mode');

        if(mode === 'admin') {
            btnAdmin.classList.add('active');
            btnPegawai.classList.remove('active');
            banner.style.display = 'flex';
            adminActions.forEach(el => el.style.display = 'inline-flex');
            pegawaiActions.forEach(el => el.style.display = 'none');

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Beralih ke Tampilan Admin Kepegawaian',
                text: 'Aksi persetujuan (Approve) & Tolak sekarang aktif.',
                showConfirmButton: false,
                timer: 2500
            });
        } else {
            btnPegawai.classList.add('active');
            btnAdmin.classList.remove('active');
            banner.style.display = 'none';
            adminActions.forEach(el => el.style.display = 'none');
            pegawaiActions.forEach(el => el.style.display = 'inline-flex');

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Beralih ke Tampilan Pegawai',
                text: 'Pegawai dapat memantau status & mengajukan kenaikan pangkat.',
                showConfirmButton: false,
                timer: 2500
            });
        }
    }

    // Modal Pengajuan
    function openPengajuanModal() {
        document.getElementById('modalPengajuanKP').style.display = 'flex';
    }

    function closePengajuanModal() {
        document.getElementById('modalPengajuanKP').style.display = 'none';
    }

    // File selection & dropzone handling
    function handleFileSelected(event) {
        const file = event.target.files[0];
        if (file) {
            selectedFile = file;
            document.getElementById('dropzonePrompt').style.display = 'none';
            document.getElementById('filePreviewContainer').style.display = 'flex';
            document.getElementById('previewFileName').textContent = file.name;
            document.getElementById('previewFileSize').textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB • Berkas Terpilih';
        }
    }

    function removeSelectedFile() {
        selectedFile = null;
        document.getElementById('realFileInput').value = '';
        document.getElementById('dropzonePrompt').style.display = 'block';
        document.getElementById('filePreviewContainer').style.display = 'none';
    }

    // Drag & drop dropzone behavior
    const dropzone = document.getElementById('uploadDropzone');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(name => {
            dropzone.addEventListener(name, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('dragover');
            });
        });
        ['dragleave', 'drop'].forEach(name => {
            dropzone.addEventListener(name, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
            });
        });
        dropzone.addEventListener('drop', (e) => {
            if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                document.getElementById('realFileInput').files = e.dataTransfer.files;
                handleFileSelected({ target: { files: e.dataTransfer.files } });
            }
        });
    }

    // Submission handler
    function handlePengajuanSubmit(e) {
        e.preventDefault();

        const pangkatTujuan = document.getElementById('inputPangkatTujuan').value;
        const jenisKenaikan = document.getElementById('inputJenisKenaikan').value;
        const tmtUsulan = document.getElementById('inputTMTUsulan').value;
        const noSK = document.getElementById('inputNoSK').value || '550/Pem.D/BP/KP/2026';

        // Check if file is uploaded
        const fileName = selectedFile ? selectedFile.name : 'SK_Kenaikan_Pangkat_Ahmad.pdf';

        closePengajuanModal();

        Swal.fire({
            title: 'Pengajuan Berhasil Dikirim!',
            html: `Usulan kenaikan pangkat ke <strong>${pangkatTujuan}</strong> beserta berkas SK <strong>${fileName}</strong> telah diterima.<br><br><span style="color:#d97706; font-weight:600;"><i class="fa-solid fa-clock"></i> Status: Menunggu Persetujuan Admin Kepegawaian</span>`,
            icon: 'success',
            confirmButtonColor: '#4361ee',
            confirmButtonText: 'Oke, Mengerti'
        }).then(() => {
            // Add or prepend to table
            const tbody = document.getElementById('pengajuanTableBody');
            const newRowId = 'pengajuan-row-' + Date.now();
            const dateFormatted = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

            const newRow = document.createElement('tr');
            newRow.id = newRowId;
            newRow.innerHTML = `
                <td>
                    <div style="font-weight: 700; color: var(--text-main); font-family: monospace;">KP-2026/09/${Math.floor(100 + Math.random() * 900)}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Usulan Kenaikan Pangkat</div>
                </td>
                <td>
                    <div style="font-weight: 600; color: var(--text-main);">${dateFormatted}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Baru Saja</div>
                </td>
                <td>
                    <div style="font-weight: 700; color: var(--primary);">${pangkatTujuan}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Sebelumnya: Penata Muda (III/a)</div>
                </td>
                <td>
                    <span style="background: var(--bg-body); border: 1px solid var(--border); padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 500;">
                        ${jenisKenaikan}
                    </span>
                </td>
                <td>
                    <div style="font-weight: 600; color: var(--text-main);">${tmtUsulan}</div>
                </td>
                <td>
                    <button class="btn btn-outline" onclick="previewDokumenSK('${fileName}', '${pangkatTujuan}', '${noSK}', '${tmtUsulan}')" style="padding: 6px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; border-color: #fca5a5; background: #fff5f5; color: #dc2626;">
                        <i class="fa-solid fa-file-pdf"></i> ${fileName}
                    </button>
                </td>
                <td id="statusCell-${newRowId}">
                    <span class="status-badge badge-pending" style="display: inline-flex; align-items: center; gap: 6px;">
                        <span class="pulsing-dot"></span> Menunggu Approval Admin
                    </span>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Sedang ditinjau Admin Kepegawaian</div>
                </td>
                <td style="text-align: center; white-space: nowrap;">
                    <div class="action-admin-mode" style="display: ${currentRole === 'admin' ? 'inline-flex' : 'none'}; gap: 8px;">
                        <button class="btn" onclick="previewDokumenSK('${fileName}', '${pangkatTujuan}', '${noSK}', '${tmtUsulan}')" style="padding: 6px 10px; font-size: 12px; background: var(--bg-body); border: 1px solid var(--border);">
                            <i class="fa-solid fa-eye"></i> Periksa SK
                        </button>
                        <button class="btn btn-primary" onclick="approvePengajuan('${newRowId}', '${pangkatTujuan}', '${tmtUsulan}')" style="padding: 6px 12px; font-size: 12px; background: var(--success); border-color: var(--success);">
                            <i class="fa-solid fa-check"></i> Setujui
                        </button>
                        <button class="btn btn-outline" onclick="rejectPengajuan('${newRowId}')" style="padding: 6px 10px; font-size: 12px; color: var(--danger); border-color: var(--danger);">
                            <i class="fa-solid fa-xmark"></i> Tolak
                        </button>
                    </div>
                    <div class="action-pegawai-mode" style="display: ${currentRole === 'pegawai' ? 'inline-flex' : 'none'}; gap: 8px;">
                        <button class="btn btn-outline" onclick="previewDokumenSK('${fileName}', '${pangkatTujuan}', '${noSK}', '${tmtUsulan}')" style="padding: 6px 10px; font-size: 12px;">
                            <i class="fa-solid fa-eye"></i> Detail & SK
                        </button>
                        <button class="btn btn-outline" onclick="cancelPengajuan('${newRowId}')" style="padding: 6px 10px; font-size: 12px; color: var(--danger);">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </td>
            `;

            tbody.prepend(newRow);

            // Reset form
            document.getElementById('formKenaikanPangkat').reset();
            removeSelectedFile();
        });
    }

    // ADMIN ACTION: APPROVE PENGAJUAN
    function approvePengajuan(rowId, pangkatBaru, tmt) {
        Swal.fire({
            title: 'Setujui Kenaikan Pangkat?',
            html: `Anda akan menyetujui pengajuan kenaikan pangkat pegawai menjadi:<br><strong style="font-size: 16px; color: #16a34a;">${pangkatBaru}</strong><br><br>Dokumen SK kenaikan pangkat akan disahkan dan pangkat pegawai akan diperbarui secara otomatis.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Setujui Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Update row status
                const row = document.getElementById(rowId);
                const statusCell = row.querySelector('td:nth-child(7)');
                const actionCell = row.querySelector('td:nth-child(8)');

                if(statusCell) {
                    statusCell.innerHTML = `
                        <span class="status-badge badge-approved" style="display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-circle-check"></i> Disetujui
                        </span>
                        <div style="font-size: 11px; color: var(--success); margin-top: 4px;">SK Resmi Telah Disahkan</div>
                    `;
                }

                if(actionCell) {
                    actionCell.innerHTML = `
                        <span style="font-size: 12px; color: var(--success); font-weight: 600;">
                            <i class="fa-solid fa-check-double"></i> Telah Disetujui
                        </span>
                    `;
                }

                // Update Hero Card Current Rank Title
                const heroTitle = document.getElementById('currentRankTitle');
                if (heroTitle) {
                    heroTitle.textContent = pangkatBaru;
                }

                // Append to Riwayat Sah table
                const sahBody = document.getElementById('riwayatSahBody');
                if (sahBody) {
                    const sahCount = sahBody.children.length + 1;
                    const dateFormatted = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                    const newSahRow = document.createElement('tr');
                    newSahRow.innerHTML = `
                        <td>${sahCount}</td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-main);">${pangkatBaru}</div>
                            <span class="status-badge badge-approved" style="font-size: 10px; padding: 2px 6px;">Pangkat Aktif Baru</span>
                        </td>
                        <td>Kenaikan Pangkat Reguler</td>
                        <td>${tmt}</td>
                        <td>${dateFormatted}</td>
                        <td><span style="font-family: monospace;">823/Pem.D/KP/2026</span></td>
                        <td>0 thn 0 bln</td>
                        <td>-</td>
                        <td>
                            <button class="btn btn-outline" onclick="previewDokumenSK('SK_Kenaikan_Pangkat_Sah.pdf', '${pangkatBaru}', '823/Pem.D/KP/2026', '${tmt}')" style="padding: 4px 8px; font-size: 11px; color: var(--primary);">
                                <i class="fa-solid fa-file-pdf"></i> Unduh SK
                            </button>
                        </td>
                    `;
                    sahBody.prepend(newSahRow);
                    document.getElementById('badgeSahCount').textContent = sahCount + ' Sah';
                }

                // Update badge count
                document.getElementById('badgePengajuanCount').textContent = '0 Menunggu';
                document.getElementById('badgePengajuanCount').classList.remove('badge-pending');
                document.getElementById('badgePengajuanCount').classList.add('badge-approved');
                document.getElementById('pendingBadgeCount').textContent = '0 Menunggu Persetujuan';

                Swal.fire({
                    title: 'Berhasil Disetujui!',
                    text: `Kenaikan pangkat ${pangkatBaru} telah disahkan dan langsung dicatat ke arsip riwayat resmi pegawai.`,
                    icon: 'success',
                    confirmButtonColor: '#4361ee'
                });
            }
        });
    }

    // ADMIN ACTION: REJECT PENGAJUAN
    function rejectPengajuan(rowId) {
        Swal.fire({
            title: 'Tolak Pengajuan Kenaikan Pangkat?',
            text: 'Silakan berikan alasan penolakan untuk disampaikan kepada pegawai:',
            input: 'textarea',
            inputPlaceholder: 'Contoh: Berkas SK belum dilegalisir basah atau masa kerja belum mencukupi batas minimal.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Tolak Pengajuan',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan penolakan wajib diisi agar pegawai dapat memperbaiki usulan!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const row = document.getElementById(rowId);
                const statusCell = row.querySelector('td:nth-child(7)');
                const actionCell = row.querySelector('td:nth-child(8)');

                if(statusCell) {
                    statusCell.innerHTML = `
                        <span class="status-badge badge-rejected" style="display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-circle-xmark"></i> Ditolak
                        </span>
                        <div style="font-size: 11px; color: var(--danger); margin-top: 4px;" title="${result.value}">
                            Catatan: ${result.value}
                        </div>
                    `;
                }

                if(actionCell) {
                    actionCell.innerHTML = `
                        <button class="btn btn-outline" onclick="Swal.fire('Alasan Penolakan', '${result.value}', 'info')" style="padding: 6px 10px; font-size: 12px; color: var(--danger);">
                            <i class="fa-solid fa-circle-info"></i> Alasan
                        </button>
                    `;
                }

                document.getElementById('badgePengajuanCount').textContent = '0 Menunggu';
                document.getElementById('pendingBadgeCount').textContent = '0 Menunggu Persetujuan';

                Swal.fire({
                    title: 'Pengajuan Ditolak',
                    text: 'Status pengajuan telah diperbarui dan catatan penolakan telah diteruskan ke pegawai.',
                    icon: 'info',
                    confirmButtonColor: '#4361ee'
                });
            }
        });
    }

    // PEGAWAI ACTION: BATALKAN PENGAJUAN
    function cancelPengajuan(rowId) {
        Swal.fire({
            title: 'Batalkan Pengajuan Ini?',
            text: 'Pengajuan yang dibatalkan akan dihapus dari antrean verifikasi Admin Kepegawaian.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                const row = document.getElementById(rowId);
                row.remove();
                document.getElementById('badgePengajuanCount').textContent = '0 Menunggu';

                Swal.fire({
                    title: 'Dibatalkan',
                    text: 'Pengajuan telah berhasil dibatalkan.',
                    icon: 'success',
                    confirmButtonColor: '#4361ee'
                });
            }
        });
    }

    // MODAL: PREVIEW DOKUMEN SK
    function previewDokumenSK(fileName, pangkat, noSK, tmt) {
        document.getElementById('previewModalTitle').textContent = 'Preview Berkas: ' + fileName;
        document.getElementById('previewDocPangkat').textContent = pangkat;
        document.getElementById('previewDocNo').textContent = 'NOMOR: ' + (noSK || '550/Pem.D/BP/KP/2026');
        document.getElementById('previewDocTMT').textContent = tmt || '01 Oktober 2026';
        document.getElementById('modalPreviewSK').style.display = 'flex';
    }

    function closePreviewSK() {
        document.getElementById('modalPreviewSK').style.display = 'none';
    }

    // Auto-open modal if URL contains ?action=ajukan
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'ajukan') {
            openPengajuanModal();
        }

        // Initialize mode based on current query role
        if(currentRole === 'superadmin' || currentRole === 'admin') {
            switchViewMode('admin');
        } else {
            switchViewMode('pegawai');
        }
    });
</script>
<?= $this->endSection() ?>
