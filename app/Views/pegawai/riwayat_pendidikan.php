<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php $employeeProfile = $employee_profile ?? \App\Libraries\EmployeeProfileData::get(); ?>
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
    <h1 class="page-title" style="margin: 0;">Pendidikan & Pelatihan</h1>
</div>

<div class="main-card" style="padding: 32px; min-height: 500px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-graduation-cap"></i> J. Riwayat Pendidikan</h4>
        <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Pendidikan</button>
    </div>
    <div style="overflow-x: auto; margin-bottom: 32px;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TINGKAT</th>
                    <th>NAMA PENDIDIKAN</th>
                    <th>INSTANSI</th>
                    <th>FAKULTAS</th>
                    <th>TEMPAT</th>
                    <th>NOMOR IJAZAH</th>
                    <th>TANGGAL IJAZAH</th>
                    <th>NILAI</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?= view('employee_education_rows', ['employee_profile' => $employeeProfile, 'show_actions' => true, 'attachment_mode' => 'edit']) ?>
            </tbody>
        </table>
    </div>

    <h4 class="section-title"><i class="fa-solid fa-medal"></i> K. Tanda Jasa / Satya Lencana</h4>
    <table class="data-table" style="margin-bottom: 32px;">
        <thead><tr><th>NO</th><th>NAMA TANDA JASA / SATYA LENCANA</th><th>NOMOR SK, TANGGAL</th><th>TAHUN</th><th>ASAL</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>

    <h4 class="section-title"><i class="fa-solid fa-trophy"></i> L. Prestasi</h4>
    <table class="data-table" style="margin-bottom: 32px;">
        <thead><tr><th>NO</th><th>JUDUL PENGHARGAAN</th><th>JENJANG</th><th>KATEGORI</th><th>JUARA</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>

    <h4 class="section-title"><i class="fa-solid fa-book-journal-whills"></i> M. Karya Ilmiah</h4>
    <table class="data-table">
        <thead><tr><th>NO</th><th>JUDUL</th><th>NOMOR ISBN</th><th>TAHUN PUBLIKASI</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>
</div>
<?= $this->endSection() ?>
