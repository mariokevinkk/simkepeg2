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
    <h1 class="page-title" style="margin: 0;">Data Keluarga</h1>
</div>

<div class="main-card" style="padding: 32px; min-height: 500px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-ring"></i> T. Data Isteri/Suami</h4>
        <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
    <div style="overflow-x: auto; margin-bottom: 32px;">
        <table class="data-table">
            <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
            <tbody>
                <tr>
                    <td>1</td><td>Siti Aminah</td><td>ISTRI</td><td>Yogyakarta, 12-05-1988</td><td>NIKAH</td><td>20-10-2019</td><td>Wiraswasta</td><td>-</td><td>-</td>
                    <td style="white-space: nowrap;"><a href="#" style="color: var(--primary); margin-right: 8px;"><i class="fa-solid fa-pen"></i></a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-child"></i> U. Data Anak</h4>
        <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
    <div style="overflow-x: auto; margin-bottom: 32px;">
        <table class="data-table">
            <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>FILE</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
            <tbody>
                <tr>
                    <td>1</td><td>Budi Hidayat</td><td>ANAK KANDUNG</td><td>-</td><td>Yogyakarta, 01-01-2020</td><td>BELUM KAWIN</td><td>-</td><td>Belum Bekerja</td><td>-</td><td>-</td>
                    <td style="white-space: nowrap;"><a href="#" style="color: var(--primary); margin-right: 8px;"><i class="fa-solid fa-pen"></i></a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4 class="section-title"><i class="fa-solid fa-person-cane"></i> V. Data Orang Tua Kandung</h4>
    <table class="data-table" style="margin-bottom: 32px;">
        <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>

    <h4 class="section-title"><i class="fa-solid fa-person-dress"></i> W. Data Mertua</h4>
    <table class="data-table" style="margin-bottom: 32px;">
        <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>

    <h4 class="section-title"><i class="fa-solid fa-people-group"></i> X. Data Saudara</h4>
    <table class="data-table">
        <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
        <tbody><tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
    </table>
    <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'keluarga', 'document_mode' => 'edit']) ?>
</div>
<?= $this->endSection() ?>
