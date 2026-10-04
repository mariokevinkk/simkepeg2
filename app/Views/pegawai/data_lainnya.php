<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .section-title {
        margin: 32px 0 16px 0;
        color: var(--text-main);
        border-bottom: 1px solid var(--border);
        padding-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title:first-child {
        margin-top: 0;
    }
    .data-table th {
        background: var(--bg-body);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        color: var(--text-muted);
    }
</style>

<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 class="page-title" style="margin: 0;">Data Lainnya</h1>
</div>

<div class="main-card" style="padding: 32px; min-height: 500px;">
    <h4 class="section-title" style="margin-top: 0;"><i class="fa-solid fa-plane-departure"></i> N. Pengalaman Kunjungan Ke Luar Negeri</h4>
    <table class="data-table" style="margin-bottom: 32px;">
        <thead><tr><th>NO</th><th>NEGARA TUJUAN</th><th>JENIS PENUGASAN</th><th>LAMA</th><th>YANG MENETAPKAN</th><th>NO SK</th><th>MULAI</th><th>SELESAI</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="9" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>

    <h4 class="section-title"><i class="fa-solid fa-sitemap"></i> O. Keanggotaan Organisasi</h4>
    <table class="data-table" style="margin-bottom: 32px;">
        <thead><tr><th>NO</th><th>JENIS ORGANISASI</th><th>NAMA ORGANISASI</th><th>JABATAN</th><th>MULAI - SELESAI</th><th>TEMPAT</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>

    <h4 class="section-title"><i class="fa-solid fa-laptop-file"></i> P. Pengalaman Kerja</h4>
    <table class="data-table" style="margin-bottom: 32px;">
        <thead><tr><th>NO</th><th>INSTANSI</th><th>JABATAN</th><th>TAHUN</th><th>BULAN</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>

    <h4 class="section-title"><i class="fa-solid fa-language"></i> Q. Penguasaan Bahasa</h4>
    <table class="data-table" style="margin-bottom: 32px;">
        <thead><tr><th>NO</th><th>NAMA BAHASA</th><th>KEMAMPUAN</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>

    <h4 class="section-title"><i class="fa-solid fa-gavel"></i> R. Riwayat Sanksi Administratif</h4>
    <table class="data-table" style="margin-bottom: 32px;">
        <thead><tr><th>NO</th><th>HUKUMAN</th><th>TINGKAT</th><th>NO & TGL SK</th><th>MULAI - SELESAI</th><th>PEJABAT PENGESAH</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>

    <h4 class="section-title"><i class="fa-solid fa-ellipsis"></i> S. Lain-lain</h4>
    <table class="data-table">
        <thead><tr><th>NO</th><th>NAMA</th><th>TEMPAT</th><th>NOMOR</th><th>TANGGAL</th><th>MULAI - SELESAI</th><th>KETERANGAN</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>
</div>
<?= $this->endSection() ?>
