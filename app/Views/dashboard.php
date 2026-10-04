<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$selectedBangsal = isset($filter_bangsal) && $filter_bangsal !== '' ? $filter_bangsal : '';
if (empty($selectedBangsal) && isset($role) && $role === 'admin') {
    $selectedBangsal = 'Bangsal Anggrek';
}

$isFiltered = $selectedBangsal !== '';
$isAdmin = $isFiltered;

$total = $isFiltered ? 45 : 600;
$asnTotal = $isFiltered ? 27 : 360;
$asnCpns = $isFiltered ? 13 : 180;
$asnPns = $isFiltered ? 8 : 108;
$asnPppkPenuh = $isFiltered ? 3 : 36;
$asnPppkParuh = $isFiltered ? 3 : 36;

$bludTotal = $isFiltered ? 14 : 180;
$bludTetap = $isFiltered ? 8 : 108;
$bludKontrak = $isFiltered ? 6 : 72;

$mitraTotal = $isFiltered ? 4 : 60;
$mitraPenuh = $isFiltered ? 2 : 36;
$mitraParuh = $isFiltered ? 2 : 24;

$bangsalLabel = $isFiltered ? $selectedBangsal : 'lingkup instansi';
?>
<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <h1 class="page-title" style="margin: 0;">Dashboard Utama</h1>
    <form method="GET" style="display: flex; gap: 10px; align-items: center;">
        <?php if(isset($role)): ?>
        <input type="hidden" name="role" value="<?= esc($role) ?>">
        <?php endif; ?>
        <?php if(!isset($role) || $role !== 'admin'): ?>
        <select name="filter_bangsal" onchange="this.form.submit()" class="form-select" style="padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; cursor: pointer;">
            <option value="">Semua Bangsal/Unit</option>
            <option value="Bangsal Anggrek" <?= ($selectedBangsal === 'Bangsal Anggrek') ? 'selected' : '' ?>>Bangsal Anggrek</option>
            <option value="IGD" <?= ($selectedBangsal === 'IGD') ? 'selected' : '' ?>>IGD</option>
            <option value="ICU" <?= ($selectedBangsal === 'ICU') ? 'selected' : '' ?>>ICU</option>
            <option value="Poli Umum" <?= ($selectedBangsal === 'Poli Umum') ? 'selected' : '' ?>>Poli Umum</option>
        </select>
        <?php else: ?>
        <select class="form-select" disabled style="padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; cursor: not-allowed; opacity: 0.7;">
            <option selected>Bangsal Anggrek</option>
        </select>
        <?php endif; ?>
    </form>
</div>

<!-- Top Section: Summary & Distribution -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <!-- Left: Summary Cards -->
    <div class="summary-grid" style="margin-bottom: 0;">
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
        <!-- Detailed Breakdown Inside -->
        <div class="card-details">
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> CPNS</span>
                <span><?= $asnCpns ?> <small>(50%)</small></span>
            </div>
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> PNS</span>
                <span><?= $asnPns ?> <small>(30%)</small></span>
            </div>
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> PPPK (Penuh)</span>
                <span><?= $asnPppkPenuh ?> <small>(10%)</small></span>
            </div>
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> PPPK (Paruh)</span>
                <span><?= $asnPppkParuh ?> <small>(10%)</small></span>
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
        <!-- Detailed Breakdown Inside -->
        <div class="card-details">
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#8b5cf6;margin-right:6px;"></i> Tetap</span>
                <span><?= $bludTetap ?> <small>(60%)</small></span>
            </div>
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#8b5cf6;margin-right:6px;"></i> Kontrak</span>
                <span><?= $bludKontrak ?> <small>(40%)</small></span>
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
        <!-- Detailed Breakdown Inside -->
        <div class="card-details">
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#f59e0b;margin-right:6px;"></i> Penuh Waktu</span>
                <span><?= $mitraPenuh ?> <small>(60%)</small></span>
            </div>
            <div class="detail-row">
                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#f59e0b;margin-right:6px;"></i> Paruh Waktu</span>
                <span><?= $mitraParuh ?> <small>(40%)</small></span>
            </div>
        </div>
        </div>
    </div>
    
    <!-- Right: Distribusi Pegawai Kerja Chart -->
    <div class="card" style="height: 100%; min-height: 400px;">
        <h2 style="font-size: 16px; margin-top: 0; margin-bottom: 20px; color: var(--text-main);">Distribusi Pegawai Kerja</h2>
        <div class="chart-container" style="flex: 1; position: relative;">
            <canvas id="distributionChart"></canvas>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 24px; height: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
        <h2 style="font-size: 16px; margin-top: 0; margin-bottom: 0; color: var(--text-main);">Rincian Pegawai Berdasarkan Jabatan & Jenjang</h2>
        <div style="position: relative;">
            <i class="fa-solid fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 14px;"></i>
            <input type="text" id="searchJabatan" placeholder="Cari nama jabatan..." class="form-control" style="padding-left: 36px; width: 250px; font-size: 14px;">
        </div>
    </div>
    
    <div class="chart-container" style="margin-bottom: 24px; height: 300px;">
        <canvas id="jabatanChart"></canvas>
    </div>

    <div id="jabatanGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; align-items: start;">
        <!-- Dokter -->
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: var(--primary);">Dokter</div>
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 12px;"><?= $isAdmin ? 4 : 45 ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">Orang</span></div>
            <div style="font-size: 13px; color: var(--text-muted); display: flex; justify-content: space-between;">
                <span>Dokter Umum</span>
                <span style="font-weight: 500; color: var(--text-main);"><?= $isAdmin ? 2 : 25 ?></span>
            </div>
            <div style="font-size: 13px; color: var(--text-muted); display: flex; justify-content: space-between; margin-top: 4px;">
                <span>Dokter Spesialis</span>
                <span style="font-weight: 500; color: var(--text-main);"><?= $isAdmin ? 2 : 20 ?></span>
            </div>
        </div>

        <!-- Perawat -->
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: var(--primary);">Perawat</div>
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 12px;"><?= $isAdmin ? 20 : 250 ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">Orang</span></div>
            <div style="font-size: 13px; color: var(--text-muted); display: flex; justify-content: space-between;">
                <span>Kategori Terampil (Vokasi)</span>
                <span style="font-weight: 500; color: var(--text-main);"><?= $isAdmin ? 12 : 150 ?></span>
            </div>
            <div style="font-size: 13px; color: var(--text-muted); display: flex; justify-content: space-between; margin-top: 4px;">
                <span>Kategori Ahli (Profesi)</span>
                <span style="font-weight: 500; color: var(--text-main);"><?= $isAdmin ? 8 : 100 ?></span>
            </div>
        </div>

        <!-- Bidan -->
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: var(--primary);">Bidan</div>
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 12px;"><?= $isAdmin ? 8 : 120 ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">Orang</span></div>
            <div style="font-size: 13px; color: var(--text-muted); display: flex; justify-content: space-between;">
                <span>Kategori Terampil</span>
                <span style="font-weight: 500; color: var(--text-main);"><?= $isAdmin ? 5 : 80 ?></span>
            </div>
            <div style="font-size: 13px; color: var(--text-muted); display: flex; justify-content: space-between; margin-top: 4px;">
                <span>Kategori Ahli</span>
                <span style="font-weight: 500; color: var(--text-main);"><?= $isAdmin ? 3 : 40 ?></span>
            </div>
        </div>

        <!-- Apoteker -->
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: var(--primary);">Apoteker</div>
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 12px;"><?= $isAdmin ? 1 : 15 ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">Orang</span></div>
        </div>

        <!-- Asisten Apoteker -->
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: var(--primary);">Asisten Apoteker</div>
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 12px;"><?= $isAdmin ? 2 : 30 ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">Orang</span></div>
        </div>
        
        <!-- Analis SDM Aparatur -->
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: var(--primary);">Analis SDM Aparatur</div>
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 12px;"><?= $isAdmin ? 2 : 15 ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">Orang</span></div>
        </div>

        <!-- Pranata Komputer -->
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: var(--primary);">Pranata Komputer</div>
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 12px;"><?= $isAdmin ? 2 : 12 ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">Orang</span></div>
        </div>

        <!-- Pengelola Keuangan -->
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: var(--primary);">Pengelola Keuangan</div>
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 12px;"><?= $isAdmin ? 2 : 18 ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">Orang</span></div>
        </div>

        <!-- Petugas Kebersihan -->
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: var(--primary);">Petugas Kebersihan</div>
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 12px;"><?= $isAdmin ? 6 : 40 ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">Orang</span></div>
        </div>
    </div>
</div>

<!-- Removed Distribution Chart from here -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const ctxDist = document.getElementById('distributionChart').getContext('2d');
    new Chart(ctxDist, {
        type: 'bar',
        data: {
            labels: ['CPNS', 'PNS', 'PPPK (Penuh)', 'PPPK (Paruh)', 'BLUD Tetap', 'BLUD Kontrak', 'Mitra (Penuh)', 'Mitra (Paruh)'],
            datasets: [{
                label: 'Jumlah Pegawai',
                data: [
                    <?= $asnCpns ?>, <?= $asnPns ?>, <?= $asnPppkPenuh ?>, <?= $asnPppkParuh ?>,
                    <?= $bludTetap ?>, <?= $bludKontrak ?>,
                    <?= $mitraPenuh ?>, <?= $mitraParuh ?>
                ],
                backgroundColor: [
                    '#10b981', '#10b981', '#10b981', '#10b981',
                    '#8b5cf6', '#8b5cf6',
                    '#f59e0b', '#f59e0b'
                ],
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    // Data dari UI mockup untuk Jabatan Chart
    const ctxJabatan = document.getElementById('jabatanChart').getContext('2d');
    const jabatanChart = new Chart(ctxJabatan, {
        type: 'bar',
        data: {
            labels: [
                'Dokter', 'Perawat', 'Bidan', 'Apoteker', 'Asisten Apoteker', 
                'Analis SDM', 'Pranata Komputer', 'Pengelola Keuangan', 'Petugas Kebersihan'
            ],
            datasets: [
                {
                    label: 'Ahli / Spesialis',
                    data: [
                        <?= $isAdmin ? 2 : 20 ?>, // Dokter Spesialis
                        <?= $isAdmin ? 8 : 100 ?>, // Perawat Ahli
                        <?= $isAdmin ? 3 : 40 ?>, // Bidan Ahli
                        0, 0, 0, 0, 0, 0
                    ],
                    backgroundColor: '#8b5cf6' // Purple
                },
                {
                    label: 'Terampil / Umum',
                    data: [
                        <?= $isAdmin ? 2 : 25 ?>, // Dokter Umum
                        <?= $isAdmin ? 12 : 150 ?>, // Perawat Terampil
                        <?= $isAdmin ? 5 : 80 ?>, // Bidan Terampil
                        0, 0, 0, 0, 0, 0
                    ],
                    backgroundColor: '#10b981' // Green
                },
                {
                    label: 'Tanpa Jenjang Spesifik',
                    data: [
                        0, 0, 0,
                        <?= $isAdmin ? 1 : 15 ?>, // Apoteker
                        <?= $isAdmin ? 2 : 30 ?>, // Asisten Apoteker
                        <?= $isAdmin ? 2 : 15 ?>, // Analis SDM
                        <?= $isAdmin ? 2 : 12 ?>, // Pranata Komputer
                        <?= $isAdmin ? 2 : 18 ?>, // Keuangan
                        <?= $isAdmin ? 6 : 40 ?>  // Kebersihan
                    ],
                    backgroundColor: '#3b82f6' // Blue
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });

    // Fitur pencarian jabatan (dengan pembaruan chart)
    const searchInput = document.getElementById('searchJabatan');
    const jabatanGrid = document.getElementById('jabatanGrid');
    
    if (searchInput && jabatanGrid) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = jabatanGrid.children;
            
            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                // Mengambil judul jabatan dari elemen pertama di dalam card
                const titleElement = card.querySelector('div:first-child');
                
                if (titleElement) {
                    const title = titleElement.textContent.toLowerCase();
                    if (title.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                }
            }
        });
    }
</script>
<?= $this->endSection() ?>
