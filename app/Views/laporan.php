<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1 class="page-title">Laporan & Unduhan</h1>

<div class="summary-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
    <div class="card">
        <div class="card-icon icon-green" style="margin-bottom: 12px; width: 56px; height: 56px; font-size: 24px;">
            <i class="fa-solid fa-file-excel"></i>
        </div>
        <h3 style="font-size: 18px; margin-bottom: 8px;">Rekap Data Pegawai</h3>
        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.5;">Unduh rekapitulasi seluruh data pegawai (ASN, BLUD, Mitra) secara lengkap dalam format Microsoft Excel (.xlsx).</p>
        <button class="btn btn-outline" style="width: 100%; justify-content: center;">
            <i class="fa-solid fa-download"></i> Unduh Excel
        </button>
    </div>

    <div class="card">
        <div class="card-icon icon-orange" style="margin-bottom: 12px; width: 56px; height: 56px; font-size: 24px;">
            <i class="fa-solid fa-file-pdf"></i>
        </div>
        <h3 style="font-size: 18px; margin-bottom: 8px;">Laporan Statistik Distribusi</h3>
        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.5;">Cetak laporan analitik berisi grafik dan angka persebaran pegawai berdasarkan status dan posisi dalam format PDF.</p>
        <button class="btn btn-outline" style="width: 100%; justify-content: center;">
            <i class="fa-solid fa-print"></i> Cetak PDF
        </button>
    </div>
</div>
<?= $this->endSection() ?>
