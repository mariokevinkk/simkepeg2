<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .bangsal-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }
    .bangsal-card {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        transition: all 0.2s ease;
    }
    .bangsal-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.05);
        border-color: var(--primary-light);
    }
    .bangsal-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }
    .bangsal-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .bangsal-info h3 {
        margin: 0 0 4px 0;
        font-size: 18px;
        color: var(--text-main);
    }
    .bangsal-info p {
        margin: 0;
        font-size: 13px;
        color: var(--text-muted);
    }
    .bangsal-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        padding: 12px;
        background: var(--bg-body);
        border-radius: 8px;
    }
    .stat-item {
        text-align: center;
        flex: 1;
    }
    .stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
    }
    .stat-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }
    .bangsal-actions {
        display: flex;
        gap: 12px;
    }
    .bangsal-actions .btn {
        flex: 1;
        justify-content: center;
    }
</style>

<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 class="page-title" style="margin: 0;">Manajemen Bangsal / Unit</h1>
        <p style="margin: 4px 0 0 0; color: var(--text-muted); font-size: 14px;">Kelola informasi dan formasi karyawan pada tiap bangsal.</p>
    </div>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Bangsal</button>
    </div>
</div>

<div class="bangsal-grid">
    <?php
    $dummy_bangsal = [
        ['nama' => 'Bangsal Anggrek', 'tipe' => 'Rawat Inap', 'icon' => 'fa-bed-pulse', 'admin' => ['Ns. Ratna Sari, S.Kep', 'Budi Santoso, AMK'], 'jml_pegawai' => 45, 'kapasitas' => 50],
        ['nama' => 'IGD', 'tipe' => 'Gawat Darurat', 'icon' => 'fa-truck-medical', 'admin' => ['dr. Andi Pratama', 'Ns. Siti Aminah, S.Kep'], 'jml_pegawai' => 60, 'kapasitas' => 30],
        ['nama' => 'ICU', 'tipe' => 'Intensive Care', 'icon' => 'fa-heart-pulse', 'admin' => ['dr. Budi Santoso, Sp.An'], 'jml_pegawai' => 25, 'kapasitas' => 15],
        ['nama' => 'Poli Umum', 'tipe' => 'Rawat Jalan', 'icon' => 'fa-stethoscope', 'admin' => ['dr. Siti Aminah', 'Sri Mulyani'], 'jml_pegawai' => 12, 'kapasitas' => 0],
        ['nama' => 'Bangsal Mawar', 'tipe' => 'Rawat Inap', 'icon' => 'fa-bed', 'admin' => ['Ns. Dewi Lestari, S.Kep'], 'jml_pegawai' => 38, 'kapasitas' => 40],
        ['nama' => 'Instalasi Farmasi', 'tipe' => 'Penunjang Medis', 'icon' => 'fa-pills', 'admin' => ['Dra. Susi Pudjiastuti, Apt'], 'jml_pegawai' => 20, 'kapasitas' => 0],
    ];

    foreach($dummy_bangsal as $b):
    ?>
    <div class="bangsal-card">
        <div class="bangsal-header">
            <div class="bangsal-icon">
                <i class="fa-solid <?= $b['icon'] ?>"></i>
            </div>
            <div class="bangsal-info">
                <h3><?= $b['nama'] ?></h3>
                <p><i class="fa-solid fa-layer-group" style="margin-right: 4px;"></i> <?= $b['tipe'] ?></p>
            </div>
        </div>
        
        <div style="font-size: 13px; color: var(--text-main); margin-bottom: 16px; display: flex; align-items: flex-start; gap: 8px;">
            <i class="fa-solid fa-user-shield" style="color: var(--text-muted); margin-top: 3px;"></i>
            <div>
                <span style="color: var(--text-muted);">Admin:</span>
                <div style="display: flex; flex-direction: column; gap: 2px; margin-top: 2px;">
                    <?php foreach($b['admin'] as $adm): ?>
                        <strong><?= $adm ?></strong>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="bangsal-stats">
            <div class="stat-item">
                <div class="stat-value"><?= $b['jml_pegawai'] ?></div>
                <div class="stat-label">Total Pegawai</div>
            </div>
        </div>

        <div class="bangsal-actions">
            <button class="btn btn-outline" style="flex: 0 0 auto; width: 42px; padding: 0;" title="Pengaturan Bangsal"><i class="fa-solid fa-gear"></i></button>
            <a href="<?= base_url('/detail-bangsal?role=superadmin&bangsal=' . urlencode($b['nama']) . '&total=' . $b['jml_pegawai']) ?>" class="btn btn-primary" style="text-decoration: none;">
                <i class="fa-solid fa-users"></i> Lihat Karyawan
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
