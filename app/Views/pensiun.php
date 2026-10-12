<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div style="margin-bottom:20px;"><h1 class="page-title" style="margin:0;">Data Pensiun</h1><p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;">Daftar ini hanya menampilkan pegawai yang status pensiunnya sudah dicatat di database.</p></div>
<div class="main-card" style="padding:0;overflow:auto;">
    <table class="data-table">
        <thead><tr><th>Nama / NIP</th><th>Unit</th><th>Jabatan terakhir</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if ($retiredEmployees === []): ?><tr><td colspan="5" style="text-align:center;padding:30px;color:var(--text-muted);">Belum ada pegawai dengan status pensiun yang tersimpan.</td></tr><?php endif; ?>
        <?php foreach ($retiredEmployees as $employee): ?><tr>
            <td><strong><?= esc($employee['nama']) ?></strong><div style="font-size:12px;color:var(--text-muted);margin-top:4px;"><?= esc($employee['nip']) ?></div></td>
            <td><?= esc($employee['unit_name'] ?? '—') ?></td><td><?= esc($employee['jabatan_nama'] ?: '—') ?></td>
            <td><span class="status-badge"><?= esc($employee['employee_status']) ?></span></td>
            <td><a class="btn btn-outline btn-sm" style="text-decoration:none;" href="<?= base_url('/view-pegawai?id=' . (int) $employee['id']) ?>"><i class="fa-solid fa-eye"></i> Lihat</a></td>
        </tr><?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
