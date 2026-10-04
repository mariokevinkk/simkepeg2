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
    <h1 class="page-title" style="margin: 0;">Riwayat Jabatan</h1>
</div>

<div class="main-card" style="padding: 32px; min-height: 500px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-briefcase"></i> H. Riwayat Jabatan</h4>
        <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Riwayat</button>
    </div>
    
    <div style="overflow-x: auto; margin-bottom: 32px;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th style="min-width: 200px;">NAMA JABATAN / UNIT KERJA</th>
                    <th>JENIS PERUBAHAN</th>
                    <th>ESELON</th>
                    <th>GOL/RUANG</th>
                    <th>KREDIT</th>
                    <th>NO SK, TGL SK, PEJABAT</th>
                    <th>TMT</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <b>Analis Sumber Daya Manusia Aparatur Ahli Pertama</b><br>
                        <span style="font-size: 12px; color: var(--text-muted);">BAGIAN UMUM, RSUD KOTA YOGYAKARTA</span>
                    </td>
                    <td>PENGANGKATAN JABATAN FUNGSIONAL</td>
                    <td>-</td>
                    <td>Penata Muda, III/a</td>
                    <td>4.498</td>
                    <td>
                        101/Pem.D/BP/D.4/XII/2023<br>
                        <span style="font-size: 12px; color: var(--text-muted);">27-12-2023, WALI KOTA YOGYAKARTA</span>
                    </td>
                    <td>27-12-2023</td>
                    <td style="white-space: nowrap;">
                        <a href="#" style="color: var(--primary); margin-right: 8px;" title="Edit"><i class="fa-solid fa-pen"></i></a>
                        <a href="#" style="color: var(--danger);" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <b>Analis Kepegawaian Pertama / Ahli Pertama</b><br>
                        <span style="font-size: 12px; color: var(--text-muted);">BAGIAN UMUM, RSUD KOTA YOGYAKARTA</span>
                    </td>
                    <td>PENATAAN PEGAWAI</td>
                    <td>-</td>
                    <td>Penata Muda, III/a</td>
                    <td>-</td>
                    <td>
                        64/Pem.D/BP/D.4/VII/2023<br>
                        <span style="font-size: 12px; color: var(--text-muted);">31-07-2023, Pj. WALI KOTA YOGYAKARTA</span>
                    </td>
                    <td>01-08-2023</td>
                    <td style="white-space: nowrap;">
                        <a href="#" style="color: var(--primary); margin-right: 8px;" title="Edit"><i class="fa-solid fa-pen"></i></a>
                        <a href="#" style="color: var(--danger);" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-clipboard-list"></i> I. Riwayat Tugas Tambahan</h4>
        <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Riwayat</button>
    </div>
    
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th style="min-width: 200px;">NAMA JABATAN / UNIT KERJA</th>
                    <th>JENIS PERUBAHAN</th>
                    <th>ESELON</th>
                    <th>GOL/RUANG</th>
                    <th>KREDIT</th>
                    <th>NO SK, TGL SK, PEJABAT</th>
                    <th>TMT</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada riwayat tugas tambahan</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
