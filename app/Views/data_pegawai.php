<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<style>
    .employee-filter { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; align-items:center; margin:18px 0; }
    .employee-filter .search-wide { grid-column:span 2; }
    .employee-filter-actions { display:flex; gap:8px; }
    .employee-table-wrap { overflow:auto; }
    .employee-actions { display:flex; gap:7px; }
    .employee-count { color:var(--text-muted); font-size:13px; }
    @media(max-width:1050px) { .employee-filter { grid-template-columns:repeat(3,minmax(0,1fr)); } }
    @media(max-width:650px) { .employee-filter { grid-template-columns:1fr 1fr; } .employee-filter .search-wide { grid-column:1/-1; } }
    .missing-docs-button { align-items:center; background:#fef2f2; border:1px solid #fecaca; border-radius:999px; color:#b91c1c; cursor:pointer; display:inline-flex; font-size:11px; font-weight:700; gap:5px; margin-top:5px; padding:4px 10px; white-space:nowrap; }
    .missing-docs-button:hover { background:#fee2e2; }
    .missing-docs-modal { align-items:center; background:rgba(15,23,42,.55); display:none; inset:0; justify-content:center; padding:16px; position:fixed; z-index:1300; }
    .missing-docs-modal.is-open { display:flex; }
    .missing-docs-dialog { background:var(--bg-surface); border-radius:14px; box-shadow:0 24px 60px rgba(15,23,42,.35); max-height:85vh; max-width:480px; overflow:auto; padding:20px; width:100%; }
    .missing-docs-dialog h2 { font-size:16px; margin:0 0 4px; }
    .missing-docs-dialog ul { color:var(--text-main); font-size:13px; margin:14px 0 18px; padding-left:20px; }
    .missing-docs-dialog li { margin-bottom:6px; }
</style>
<div class="flex-mobile-stack" style="display:flex;align-items:center;justify-content:space-between;gap:14px;">
    <div><h1 class="page-title" style="margin:0;">Data Pegawai</h1><p class="employee-count" style="margin:5px 0 0;">Daftar sesuai unit yang dapat diakses akun Anda.</p></div>
    <?php if (! empty($can_create)): ?><a class="btn btn-primary" href="<?= base_url('/employees/new') ?>" style="text-decoration:none;"><i class="fa-solid fa-user-plus"></i> Tambah pegawai</a><?php endif; ?>
</div>
<?php if ($error = session()->getFlashdata('error')): ?><div class="main-card" style="margin-top:16px;color:var(--danger);padding:12px 16px;"><?= esc($error) ?></div><?php endif; ?>
<?php if ($success = session()->getFlashdata('success')): ?><div class="main-card" style="margin-top:16px;color:var(--success);padding:12px 16px;"><?= esc($success) ?></div><?php endif; ?>
<form class="employee-filter" method="get" action="<?= base_url('/data-pegawai') ?>">
    <input class="form-control search-wide" type="search" name="q" value="<?= esc($q ?? '') ?>" placeholder="Cari nama atau NIP">
    <?php
    $selects = [
        'filter_bangsal' => ['Semua unit', $unitOptions],
        'filter_ketenagaan' => ['Semua jenis ketenagaan', $ketenagaanOptions],
        'filter_status' => ['Semua status kepegawaian', $statusOptions],
        'filter_status_data' => ['Semua status data', $statusDataOptions],
        'filter_jabatan' => ['Semua jabatan', $jabatanOptions],
        'filter_jenjang' => ['Semua jenjang', $jenjangOptions],
    ];
    ?>
    <?php foreach ($selects as $name => [$placeholder, $options]): ?>
        <select class="form-control" name="<?= $name ?>" aria-label="<?= esc($placeholder) ?>"><option value=""><?= esc($placeholder) ?></option><?php foreach ($options as $option): ?><option value="<?= esc($option) ?>" <?= ($$name ?? '') === $option ? 'selected' : '' ?>><?= esc($option) ?></option><?php endforeach; ?></select>
    <?php endforeach; ?>
    <select class="form-control" name="filter_dokumen" aria-label="Kelengkapan dokumen"><option value="">Semua kelengkapan dokumen</option><option value="belum" <?= ($filter_dokumen ?? '') === 'belum' ? 'selected' : '' ?>>Dokumen wajib belum lengkap</option><option value="lengkap" <?= ($filter_dokumen ?? '') === 'lengkap' ? 'selected' : '' ?>>Dokumen wajib lengkap</option></select>
    <select class="form-control" name="filter_jpl" aria-label="Semua JPL"><option value="">Semua JPL</option><option value="kurang" <?= ($filter_jpl ?? '') === 'kurang' ? 'selected' : '' ?>>Kurang dari 20</option><option value="terpenuhi" <?= ($filter_jpl ?? '') === 'terpenuhi' ? 'selected' : '' ?>>20 atau lebih</option></select>
    <div class="employee-filter-actions">
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter"></i> Filter</button>
        <?php if (! empty($has_filter)): ?><a class="btn btn-outline" style="text-decoration:none;" href="<?= base_url('/data-pegawai') ?>"><i class="fa-solid fa-rotate-left"></i> Reset</a><?php endif; ?>
    </div>
</form>
<p class="employee-count" style="margin:0 0 12px;"><?= count($pegawaiList) ?> pegawai</p>
<div class="main-card employee-table-wrap" style="padding:0;">
    <table class="data-table">
        <thead><tr><th>Nama / NIP</th><th>Unit</th><th>Jenis ketenagaan</th><th>Status kepegawaian</th><th>Status data</th><th>Jabatan</th><th>STR &amp; SIP</th><th>Dokumen wajib</th><th>Jenjang</th><th>JPL</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if ($pegawaiList === []): ?><tr><td colspan="11" style="text-align:center;padding:32px;color:var(--text-muted);">Belum ada data pegawai yang cocok.</td></tr><?php endif; ?>
        <?php foreach ($pegawaiList as $row): ?>
            <?php
            $docs = $row['dokumen'];
            $missingDocs = $docs['missing'];
            $strSip = $row['str_sip'];
            $expiry = ! empty($strSip['expires_at']) ? strtotime($strSip['expires_at']) : false;
            $daysLeft = $expiry === false ? null : (int) floor(($expiry - strtotime(date('Y-m-d'))) / 86400);
            ?>
            <tr>
                <td><strong><?= esc($row['nama']) ?></strong><div style="font-size:12px;color:var(--text-muted);margin-top:4px;"><?= esc($row['nip']) ?></div></td>
                <td><?= esc($row['bangsal'] ?: '—') ?></td>
                <td><?= esc($row['jenis'] ?: '—') ?></td>
                <td><?= esc($row['status_kep'] ?: '—') ?></td>
                <td><?= esc($row['status'] ?: '—') ?></td>
                <td><?= esc($row['jabatan'] ?: '—') ?></td>
                <td>
                    <?php if ($strSip === null || (! $strSip['required'] && $strSip['status'] === 'missing')): ?>—
                    <?php elseif ($strSip['status'] === 'missing'): ?><span class="status-badge" style="background:#fee2e2;color:#b91c1c;">Belum diunggah</span>
                    <?php elseif ($daysLeft !== null): ?>
                        <div style="font-size:11px;color:var(--text-muted);">Jatuh tempo <?= esc(date('d-m-Y', $expiry)) ?></div>
                        <span class="status-badge <?= $daysLeft < 0 || $daysLeft <= 30 ? '' : 'status-active' ?>" style="display:inline-block;margin-top:5px;<?= $daysLeft < 0 ? 'background:#fee2e2;color:#b91c1c;' : ($daysLeft <= 30 ? 'background:#fef3c7;color:#a16207;' : '') ?>"><?= $daysLeft < 0 ? 'Kedaluwarsa' : ($daysLeft <= 30 ? 'Segera diperbarui' : 'Aktif') ?></span>
                    <?php else: ?><span style="font-size:11px;color:var(--text-muted);">Diunggah, tanpa tanggal berlaku</span><?php endif; ?>
                </td>
                <td>
                    <div style="font-weight:700;white-space:nowrap;color:<?= $docs['complete'] ? 'var(--success)' : 'var(--danger)' ?>;"><i class="fa-solid fa-paperclip"></i> <?= (int) $docs['uploaded'] ?> / <?= (int) $docs['total'] ?></div>
                    <?php if ($missingDocs === []): ?>
                        <div style="font-size:11px;color:var(--success);margin-top:3px;white-space:nowrap;">Lengkap<?= (int) $docs['pending'] > 0 ? ' · ' . (int) $docs['pending'] . ' menunggu verifikasi' : '' ?></div>
                    <?php else: ?>
                        <button type="button" class="missing-docs-button" data-missing-docs="<?= esc(json_encode(array_values($missingDocs), JSON_UNESCAPED_UNICODE)) ?>" data-employee-name="<?= esc($row['nama']) ?>" data-documents-url="<?= base_url('/view-pegawai?id=' . (int) $row['id'] . '&bagian=dokumen-pegawai') ?>">
                            <i class="fa-solid fa-circle-exclamation"></i> <?= count($missingDocs) ?> kurang
                        </button>
                    <?php endif; ?>
                </td>
                <td><?= esc($row['jenjang'] ?: '—') ?></td>
                <td><?= (int) $row['capaian_jpl'] ?></td>
                <td><div class="employee-actions"><a class="btn btn-outline btn-sm" style="text-decoration:none;" href="<?= base_url('/view-pegawai?id=' . (int) $row['id']) ?>" title="Lihat data"><i class="fa-solid fa-eye"></i></a><?php if (! empty($can_update)): ?><a class="btn btn-outline btn-sm" style="text-decoration:none;" href="<?= base_url('/edit-pegawai?id=' . (int) $row['id']) ?>" title="Ubah data"><i class="fa-solid fa-pen"></i></a><?php endif; ?></div></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="missing-docs-modal" id="missing-docs-modal" role="dialog" aria-modal="true" aria-labelledby="missing-docs-title">
    <div class="missing-docs-dialog">
        <h2 id="missing-docs-title">Dokumen wajib yang belum lengkap</h2>
        <div style="color:var(--text-muted);font-size:13px;" data-missing-docs-employee></div>
        <ul data-missing-docs-list></ul>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button type="button" class="btn btn-outline" data-missing-docs-close>Tutup</button>
            <a class="btn btn-primary" style="text-decoration:none;" href="#" data-missing-docs-link><i class="fa-solid fa-folder-open"></i> Buka dokumen pegawai</a>
        </div>
    </div>
</div>
<script>
(function () {
    const modal = document.getElementById('missing-docs-modal');
    const list = modal.querySelector('[data-missing-docs-list]');
    const close = function () { modal.classList.remove('is-open'); };
    document.querySelectorAll('.missing-docs-button').forEach(function (button) {
        button.addEventListener('click', function () {
            list.replaceChildren();
            JSON.parse(button.dataset.missingDocs || '[]').forEach(function (name) {
                const item = document.createElement('li');
                item.textContent = name;
                list.appendChild(item);
            });
            modal.querySelector('[data-missing-docs-employee]').textContent = button.dataset.employeeName;
            modal.querySelector('[data-missing-docs-link]').href = button.dataset.documentsUrl;
            modal.classList.add('is-open');
            modal.querySelector('[data-missing-docs-close]').focus();
        });
    });
    modal.querySelector('[data-missing-docs-close]').addEventListener('click', close);
    modal.addEventListener('click', function (event) { if (event.target === modal) close(); });
    document.addEventListener('keydown', function (event) { if (event.key === 'Escape') close(); });
})();
</script>
<?= $this->endSection() ?>
