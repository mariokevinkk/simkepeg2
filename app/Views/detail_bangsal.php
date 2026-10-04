<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="<?= base_url('/manajemen-bangsal?role='.$role) ?>" class="btn btn-outline" style="padding: 8px 12px;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        <h1 class="page-title" style="margin: 0;">
            Detail Bangsal - <?= esc($bangsal ?? 'Tidak Diketahui') ?>
        </h1>
    </div>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-outline" style="color: #10b981; border-color: #10b981;"><i class="fa-solid fa-file-excel"></i> Download Excel</button>
        <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Pegawai</button>
    </div>
</div>

<?php
$bangsalLabel = esc($bangsal ?? 'Bangsal');
// Get total from passed parameter, default to 45
$total = isset($total) && is_numeric($total) ? intval($total) : 45;

$asnTotal = (int)round($total * 0.60);
$asnCpns = (int)round($asnTotal * 0.50);
$asnPns = (int)round($asnTotal * 0.30);
$asnPppkPenuh = (int)round($asnTotal * 0.10);
$asnPppkParuh = $asnTotal - ($asnCpns + $asnPns + $asnPppkPenuh);

$bludTotal = (int)round($total * 0.30);
$bludTetap = (int)round($bludTotal * 0.60);
$bludKontrak = $bludTotal - $bludTetap;

$mitraTotal = $total - ($asnTotal + $bludTotal);
$mitraPenuh = (int)round($mitraTotal * 0.60);
$mitraParuh = $mitraTotal - $mitraPenuh;
?>

<!-- Profil Bangsal Card -->
<div class="main-card" style="margin-bottom: 24px;">
    <h3 style="font-size: 16px; margin-top: 0; margin-bottom: 16px; color: var(--text-main); border-bottom: 1px solid var(--border); padding-bottom: 12px;">
        <i class="fa-solid fa-circle-info" style="color: var(--primary); margin-right: 8px;"></i> Profil Informasi Bangsal
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Nama Bangsal / Unit</div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text-main);"><?= $bangsalLabel ?></div>
        </div>
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Admin Bangsal</div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text-main);">Ns. Rini Setyowati, S.Kep</div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text-main); margin-top: 4px;">Budi Santoso, AMK</div>
        </div>
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Ekstensi Telepon</div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text-main);">105</div>
        </div>
        <div style="grid-column: 1 / -1; margin-top: 8px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Fokus Layanan & Deskripsi</div>
            <div style="font-size: 14px; color: var(--text-main); line-height: 1.6;">Layanan observasi dan perawatan intensif untuk pasien dengan penyakit dalam. Didukung oleh fasilitas monitoring 24 jam dan tenaga medis spesialis yang berjaga.</div>
        </div>
    </div>
</div>

<!-- Dashboard Summary Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <!-- Total Card -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Total Pegawai</span>
            <div class="card-icon icon-blue">
                <i class="fa-solid fa-users-viewfinder"></i>
            </div>
        </div>
        <div class="card-value">
            <?= $total ?>
            <span class="card-percentage perc-blue">100%</span>
        </div>
        <div class="card-details" style="border:none;">
            <div class="detail-row" style="color:var(--text-muted); justify-content:center; text-align:center;">
                Total keseluruhan pegawai <br> di <?= $bangsalLabel ?>
            </div>
        </div>
    </div>

    <!-- ASN Card -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Total ASN</span>
            <div class="card-icon icon-green">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>
        <div class="card-value">
            <?= $asnTotal ?>
            <span class="card-percentage perc-green">60%</span>
        </div>
        <div class="card-details">
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> CPNS</span>
                <span><?= $asnCpns ?></span>
            </div>
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> PNS</span>
                <span><?= $asnPns ?></span>
            </div>
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> PPPK</span>
                <span><?= $asnPppkPenuh + $asnPppkParuh ?></span>
            </div>
        </div>
    </div>

    <!-- BLUD Card -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Total BLUD</span>
            <div class="card-icon icon-purple">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
        </div>
        <div class="card-value">
            <?= $bludTotal ?>
            <span class="card-percentage perc-purple">30%</span>
        </div>
        <div class="card-details">
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#8b5cf6;margin-right:6px;"></i> Tetap</span>
                <span><?= $bludTetap ?></span>
            </div>
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#8b5cf6;margin-right:6px;"></i> Kontrak</span>
                <span><?= $bludKontrak ?></span>
            </div>
        </div>
    </div>

    <!-- Mitra Card -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Total Mitra</span>
            <div class="card-icon icon-orange">
                <i class="fa-solid fa-handshake"></i>
            </div>
        </div>
        <div class="card-value">
            <?= $mitraTotal ?>
            <span class="card-percentage perc-orange">10%</span>
        </div>
        <div class="card-details">
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#f59e0b;margin-right:6px;"></i> Penuh Waktu</span>
                <span><?= $mitraPenuh ?></span>
            </div>
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#f59e0b;margin-right:6px;"></i> Paruh Waktu</span>
                <span><?= $mitraParuh ?></span>
            </div>
        </div>
    </div>
</div>

<div class="main-card" style="padding: 0; overflow: hidden;">
    <div class="filter-row" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
        <div class="search-bar" style="width: 100%; max-width: 300px; background: var(--bg-surface); border: 1px solid var(--border);">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
            <input type="text" placeholder="Cari nama atau NIP...">
        </div>
        <button class="btn btn-outline"><i class="fa-solid fa-filter"></i> Filter Data</button>
    </div>
    
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NAMA & NIP</th>
                    <th>BANGSAL / UNIT</th>
                    <th>JENIS PEGAWAI</th>
                    <th>STATUS KEPEGAWAIAN</th>
                    <th>NAMA JABATAN</th>
                    <th>JENJANG JABATAN</th>
                    <th>JENIS JENJANG</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dummy_data = [
                    ['nama' => 'Ahmad Hidayat', 'nip' => '198501012010011001', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Terampil', 'jenis_jenjang' => 'Terampil'],
                    ['nama' => 'Siti Aminah', 'nip' => '199003022015022002', 'bangsal' => 'IGD', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Mahir', 'jenis_jenjang' => 'Terampil'],
                    ['nama' => 'Sri Mulyani', 'nip' => '197508261998032001', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Penyelia', 'jenis_jenjang' => 'Terampil'],
                    ['nama' => 'Budi Santoso', 'nip' => '198210102021211005', 'bangsal' => 'IGD', 'jenis' => 'ASN', 'status_kep' => 'PPPK Penuh Waktu', 'jabatan' => 'Perawat', 'jenjang' => 'Ahli Pertama', 'jenis_jenjang' => 'Ahli'],
                    ['nama' => 'Eko Prasetyo', 'nip' => '198805122018031003', 'bangsal' => 'ICU', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Ahli Muda', 'jenis_jenjang' => 'Ahli'],
                    ['nama' => 'Dewi Lestari', 'nip' => '197012121995032001', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Ahli Madya', 'jenis_jenjang' => 'Ahli'],
                    ['nama' => 'Rina Wati', 'nip' => '196501011990012002', 'bangsal' => 'IGD', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Ahli Utama', 'jenis_jenjang' => 'Ahli'],
                    ['nama' => 'Joko Widodo', 'nip' => 'BLUD2023055', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'BLUD', 'status_kep' => 'Tetap', 'jabatan' => 'Asisten Apoteker', 'jenjang' => '-', 'jenis_jenjang' => '-'],
                    ['nama' => 'Agus Harimurti', 'nip' => 'MTR2023011', 'bangsal' => 'Poli Umum', 'jenis' => 'Mitra', 'status_kep' => 'Paruh Waktu', 'jabatan' => 'Petugas Kebersihan', 'jenjang' => '-', 'jenis_jenjang' => '-'],
                    ['nama' => 'Susi Pudjiastuti', 'nip' => 'BLUD2020002', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'BLUD', 'status_kep' => 'Kontrak', 'jabatan' => 'Apoteker', 'jenjang' => '-', 'jenis_jenjang' => '-'],
                ];

                if(isset($bangsal) && !empty($bangsal)) {
                    $dummy_data = array_filter($dummy_data, function($d) use ($bangsal) {
                        return strtolower($d['bangsal']) === strtolower(urldecode($bangsal));
                    });
                }

                foreach($dummy_data as $row):
                ?>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);"><?= $row['nama'] ?></div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><?= $row['nip'] ?></div>
                    </td>
                    <td><span style="font-weight: 500; color: var(--text-muted);"><?= $row['bangsal'] ?></span></td>
                    <td>
                        <?php if($row['jenis'] === 'ASN'): ?>
                            <span style="background: #dcfce7; color: #10b981; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">ASN</span>
                        <?php elseif($row['jenis'] === 'BLUD'): ?>
                            <span style="background: #f3e8ff; color: #8b5cf6; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">BLUD</span>
                        <?php else: ?>
                            <span style="background: #fef3c7; color: #f59e0b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Mitra</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['status_kep'] ?></td>
                    <td><span style="font-weight: 500;"><?= $row['jabatan'] ?></span></td>
                    <td><?= $row['jenjang'] ?></td>
                    <td><?= $row['jenis_jenjang'] ?></td>
                    <td>
                        <span class="status-badge status-active">Aktif</span>
                    </td>
                    <td>
                        <button class="btn btn-outline" style="padding: 6px 10px; font-size: 12px;"><i class="fa-solid fa-eye"></i></button>
                        <button class="btn btn-outline" style="padding: 6px 10px; font-size: 12px; color: var(--primary); border-color: var(--primary);"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if(empty($dummy_data)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: var(--text-muted);">Tidak ada data pegawai di bangsal ini.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
