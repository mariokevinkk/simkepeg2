<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<style>
    .employee-filter { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; align-items:end; margin:18px 0; }
    .employee-filter .search-wide { grid-column:span 2; }
    .employee-filter-field { display:flex; flex-direction:column; gap:6px; min-width:0; }
    .employee-filter-field label { color:var(--text-main); font-size:12px; font-weight:600; line-height:1.35; }
    .employee-filter-field .form-control { width:100%; }
    .employee-filter-actions { display:flex; flex-wrap:wrap; gap:8px; }
    .employee-table-wrap { margin-left:-12px; overflow:auto; width:calc(100% + 24px); }
    .employee-table-wrap .data-table { min-width:1080px; }
    .employee-table-wrap .data-table td { white-space:normal; }
    .employee-actions { display:flex; gap:7px; }
    .employee-count { color:var(--text-muted); font-size:13px; }
    @media(max-width:1050px) { .employee-filter { grid-template-columns:repeat(3,minmax(0,1fr)); } }
    @media(max-width:650px) { .employee-filter { grid-template-columns:1fr 1fr; } .employee-filter .search-wide { grid-column:1/-1; } .employee-table-wrap { margin-left:0; width:100%; } }
    @media(max-width:420px) { .employee-filter { grid-template-columns:1fr; } .employee-filter .search-wide { grid-column:auto; } }
    .employee-pagination { align-items:center; display:flex; flex-wrap:wrap; gap:12px; justify-content:space-between; margin:14px 0 0; }
    .employee-pages { display:flex; flex-wrap:wrap; gap:4px; }
    .employee-pages a, .employee-pages span { align-items:center; border:1px solid var(--border); border-radius:8px; color:var(--text-main); display:inline-flex; font-size:13px; height:34px; justify-content:center; min-width:34px; padding:0 8px; text-decoration:none; background:var(--bg-surface); }
    .employee-pages a:hover { border-color:var(--primary); color:var(--primary); }
    .employee-pages .is-current { background:var(--primary); border-color:var(--primary); color:#fff; font-weight:700; }
    .employee-pages .is-disabled { color:var(--text-muted); opacity:.5; }
    .employee-pages .is-gap { border-color:transparent; background:transparent; }
    .employee-per-page { align-items:center; display:flex; gap:8px; white-space:nowrap; }
    .employee-per-page select { width:auto; padding:6px 10px; }
    @media(max-width:650px) { .employee-pagination { justify-content:center; } }
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
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <?php $exportQuery = array_filter(service('request')->getGet() ?? [], static fn ($value, $key): bool => is_scalar($value) && $value !== '' && ! in_array($key, ['page', 'per_page', 'filter_status_data'], true), ARRAY_FILTER_USE_BOTH); ?>
        <a class="btn btn-outline" href="<?= esc(base_url('/data-pegawai/excel') . ($exportQuery !== [] ? '?' . http_build_query($exportQuery) : '')) ?>" style="text-decoration:none;" title="Semua pegawai yang cocok dengan filter, beserta seluruh riwayatnya"><i class="fa-solid fa-file-excel"></i> Unduh Excel</a>
        <?php if (! empty($can_create)): ?><a class="btn btn-primary" href="<?= base_url('/employees/new') ?>" style="text-decoration:none;"><i class="fa-solid fa-user-plus"></i> Tambah pegawai</a><?php endif; ?>
    </div>
</div>
<?php if ($error = session()->getFlashdata('error')): ?><div class="main-card" style="margin-top:16px;color:var(--danger);padding:12px 16px;"><?= esc($error) ?></div><?php endif; ?>
<?php if ($success = session()->getFlashdata('success')): ?><div class="main-card" style="margin-top:16px;color:var(--success);padding:12px 16px;"><?= esc($success) ?></div><?php endif; ?>
<form class="employee-filter" method="get" action="<?= base_url('/data-pegawai') ?>">
    <input type="hidden" name="per_page" value="<?= (int) $pagination['per_page'] ?>">
    <div class="employee-filter-field search-wide">
        <label for="employee-filter-search">Cari pegawai</label>
        <input class="form-control" id="employee-filter-search" type="search" name="q" value="<?= esc($q ?? '') ?>" placeholder="Cari nama atau NIP">
    </div>
    <?php
    $selects = [
        'filter_bangsal' => ['Unit', 'Semua unit', $unitOptions],
    ];
    ?>
    <div class="employee-filter-field">
        <label for="filter-status-group">Kelompok status kepegawaian</label>
        <select class="form-control" id="filter-status-group" name="filter_status_group">
            <option value="">Semua kelompok status kepegawaian</option>
            <?php foreach ($statusGroupOptions as $groupKey => $groupLabel): ?>
                <option value="<?= esc($groupKey) ?>" <?= ($filter_status_group ?? '') === $groupKey ? 'selected' : '' ?>><?= esc($groupLabel) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="employee-filter-field">
        <label for="filter-status-detail">Detail status kepegawaian</label>
        <select class="form-control" id="filter-status-detail" name="filter_status" <?= ($filter_status_group ?? '') === '' ? 'disabled' : '' ?>>
            <option value=""><?= ($filter_status_group ?? '') === '' ? 'Pilih kelompok terlebih dahulu' : 'Semua detail status' ?></option>
            <?php foreach ($statusOptions as $option): ?>
                <?php
                $optionGroup = 'other';
                foreach (\App\Models\EmployeeModel::EMPLOYMENT_STATUS_GROUPS as $groupKey => $types) {
                    if (in_array($option, $types, true)) { $optionGroup = $groupKey; break; }
                }
                ?>
                <option value="<?= esc($option) ?>" data-status-group="<?= esc($optionGroup) ?>" <?= ($filter_status ?? '') === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php foreach ($selects as $name => [$label, $placeholder, $options]): ?>
        <div class="employee-filter-field">
            <label for="<?= esc($name) ?>"><?= esc($label) ?></label>
            <select class="form-control" id="<?= esc($name) ?>" name="<?= esc($name) ?>"><option value=""><?= esc($placeholder) ?></option><?php foreach ($options as $option): ?><option value="<?= esc($option) ?>" <?= ($$name ?? '') === $option ? 'selected' : '' ?>><?= esc($option) ?></option><?php endforeach; ?></select>
        </div>
    <?php endforeach; ?>
    <div class="employee-filter-field">
        <label for="filter_ketenagaan">Jenis ketenagaan</label>
        <select class="form-control" id="filter_ketenagaan" name="filter_ketenagaan">
            <option value="">Semua jenis ketenagaan</option>
            <?php foreach ($ketenagaanOptions as $option): ?>
                <option value="<?= esc($option) ?>" <?= ($filter_ketenagaan ?? '') === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="employee-filter-field">
        <label for="filter_jabatan">Jabatan</label>
        <select class="form-control" id="filter_jabatan" name="filter_jabatan" <?= ($filter_ketenagaan ?? '') === '' ? 'disabled' : '' ?>>
            <option value=""><?= ($filter_ketenagaan ?? '') === '' ? 'Pilih jenis ketenagaan dahulu' : 'Semua jabatan' ?></option>
            <?php foreach ($jobOptionsByKetenagaan as $group => $titles): ?>
                <?php foreach ($titles as $title): ?>
                    <option value="<?= esc($title) ?>" data-ketenagaan="<?= esc($group) ?>" <?= ($filter_ketenagaan ?? '') === $group && ($filter_jabatan ?? '') === $title ? 'selected' : '' ?>><?= esc($title) ?></option>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="employee-filter-field">
        <label for="filter_jenjang">Jenjang</label>
        <select class="form-control" id="filter_jenjang" name="filter_jenjang"><option value="">Semua jenjang</option><?php foreach ($jenjangOptions as $option): ?><option value="<?= esc($option) ?>" <?= ($filter_jenjang ?? '') === $option ? 'selected' : '' ?>><?= esc($option) ?></option><?php endforeach; ?></select>
    </div>
    <div class="employee-filter-field">
        <label for="filter_dokumen">Kelengkapan dokumen</label>
        <select class="form-control" id="filter_dokumen" name="filter_dokumen"><option value="">Semua kelengkapan dokumen</option><option value="belum" <?= ($filter_dokumen ?? '') === 'belum' ? 'selected' : '' ?>>Dokumen wajib belum lengkap</option><option value="lengkap" <?= ($filter_dokumen ?? '') === 'lengkap' ? 'selected' : '' ?>>Dokumen wajib lengkap</option></select>
    </div>
    <div class="employee-filter-field">
        <label for="filter_jpl">Capaian JPL</label>
        <select class="form-control" id="filter_jpl" name="filter_jpl"><option value="">Semua JPL</option><option value="kurang" <?= ($filter_jpl ?? '') === 'kurang' ? 'selected' : '' ?>>Kurang dari 20</option><option value="terpenuhi" <?= ($filter_jpl ?? '') === 'terpenuhi' ? 'selected' : '' ?>>20 atau lebih</option></select>
    </div>
    <div class="employee-filter-actions">
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter"></i> Filter</button>
        <?php if (! empty($has_filter)): ?><a class="btn btn-outline" style="text-decoration:none;" href="<?= base_url('/data-pegawai') ?>"><i class="fa-solid fa-rotate-left"></i> Reset</a><?php endif; ?>
    </div>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const groupSelect = document.getElementById('filter-status-group');
    const detailSelect = document.getElementById('filter-status-detail');
    if (groupSelect && detailSelect) {
        function updateStatusDetails(resetSelection) {
            const group = groupSelect.value;
            detailSelect.disabled = group === '';
            Array.from(detailSelect.options).forEach(function (option) {
                if (option.value === '') {
                    option.hidden = false;
                    option.textContent = group === '' ? 'Pilih kelompok terlebih dahulu' : 'Semua detail status';
                    return;
                }
                option.hidden = group !== '' && option.dataset.statusGroup !== group;
            });
            if (resetSelection || (detailSelect.selectedOptions[0]?.dataset.statusGroup !== group && detailSelect.value !== '')) {
                detailSelect.value = '';
            }
        }

        updateStatusDetails(false);
        groupSelect.addEventListener('change', function () { updateStatusDetails(true); });
    }

    const workforceSelect = document.getElementById('filter_ketenagaan');
    const jobSelect = document.getElementById('filter_jabatan');
    if (workforceSelect && jobSelect) {
        function updateJobOptions(resetSelection) {
            const workforce = workforceSelect.value;
            let hasOptions = false;
            Array.from(jobSelect.options).forEach(function (option) {
                if (option.value === '') return;
                const matches = option.dataset.ketenagaan === workforce;
                option.hidden = !matches;
                option.disabled = !matches;
                hasOptions ||= matches;
            });
            if (resetSelection || jobSelect.selectedOptions[0]?.dataset.ketenagaan !== workforce) {
                jobSelect.value = '';
            }
            jobSelect.disabled = workforce === '' || !hasOptions;
            jobSelect.options[0].textContent = workforce === ''
                ? 'Pilih jenis ketenagaan dahulu'
                : (hasOptions ? 'Semua jabatan' : 'Belum ada jabatan');
        }

        updateJobOptions(false);
        workforceSelect.addEventListener('change', function () { updateJobOptions(true); });
    }
});
</script>
<?php
$firstRow = $pagination['total'] === 0 ? 0 : ($pagination['page'] - 1) * $pagination['per_page'] + 1;
$lastRow = min($pagination['total'], $pagination['page'] * $pagination['per_page']);
// Link to another page with every current filter kept.
$pageUrl = static function (int $page, ?int $perPage = null): string {
    $query = array_filter(service('request')->getGet() ?? [], static fn ($value, $key): bool => is_scalar($value) && $value !== '' && $key !== 'filter_status_data', ARRAY_FILTER_USE_BOTH);
    $query['page'] = $page;
    if ($perPage !== null) {
        $query['per_page'] = $perPage;
    }
    return base_url('/data-pegawai') . '?' . http_build_query($query);
};
// Page numbers to show: first, last, and two on each side of the current page.
$pageNumbers = [];
for ($number = 1; $number <= $pagination['pages']; $number++) {
    if ($number === 1 || $number === $pagination['pages'] || abs($number - $pagination['page']) <= 2) {
        $pageNumbers[] = $number;
    }
}
?>
<p class="employee-count" style="margin:0 0 12px;"><?= $pagination['total'] ?> pegawai<?= $pagination['total'] > 0 ? ' · menampilkan ' . $firstRow . '–' . $lastRow : '' ?></p>
<div class="main-card employee-table-wrap" style="padding:0;">
    <table class="data-table">
        <thead><tr><th>Nama / NIP</th><th>Unit</th><th>Jenis ketenagaan</th><th>Status kepegawaian</th><th>Jabatan / Jenjang</th><th>STR &amp; SIP</th><th>Dokumen wajib</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if ($pegawaiList === []): ?><tr><td colspan="8" style="text-align:center;padding:32px;color:var(--text-muted);">Belum ada data pegawai yang cocok.</td></tr><?php endif; ?>
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
                <td><strong><?= esc($row['jabatan'] ?: '-') ?></strong><div style="font-size:11px;color:var(--text-muted);margin-top:4px;"> <?php if (! empty($row['jenjang'])): ?>Jenjang <?= esc($row['jenjang']) ?> | <?php endif; ?>JPL <?= (int) $row['capaian_jpl'] ?></div></td>
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
                    <?php if (! empty($docs['revisions'])): ?>
                        <details style="margin-top:6px;font-size:11px;color:#b91c1c;">
                            <summary style="cursor:pointer;font-weight:700;"><?= count($docs['revisions']) ?> dokumen perlu perbaikan</summary>
                            <div style="margin-top:5px;max-width:240px;white-space:normal;">
                                <?php foreach ($docs['revisions'] as $revision): ?>
                                    <div style="padding:5px 0;border-top:1px solid #fecaca;"><strong><?= esc($revision['title']) ?></strong><?php if ($revision['note'] !== ''): ?><br><?= nl2br(esc($revision['note'])) ?><?php endif; ?></div>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    <?php endif; ?>
                </td>
                <td><div class="employee-actions"><a class="btn btn-outline btn-sm" style="text-decoration:none;" href="<?= base_url('/view-pegawai?id=' . (int) $row['id']) ?>" title="Lihat data"><i class="fa-solid fa-eye"></i></a><?php if (! empty($can_update)): ?><a class="btn btn-outline btn-sm" style="text-decoration:none;" href="<?= base_url('/edit-pegawai?id=' . (int) $row['id']) ?>" title="Ubah data"><i class="fa-solid fa-pen"></i></a><?php endif; ?></div></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php if ($pagination['total'] > 0): ?>
<div class="employee-pagination">
    <span class="employee-count">Menampilkan <?= $firstRow ?>–<?= $lastRow ?> dari <?= $pagination['total'] ?> pegawai</span>
    <nav class="employee-pages" aria-label="Halaman data pegawai">
        <?php if ($pagination['page'] > 1): ?>
            <a href="<?= esc($pageUrl($pagination['page'] - 1)) ?>" aria-label="Halaman sebelumnya"><i class="fa-solid fa-chevron-left"></i></a>
        <?php else: ?>
            <span class="is-disabled" aria-hidden="true"><i class="fa-solid fa-chevron-left"></i></span>
        <?php endif; ?>
        <?php $previous = 0; foreach ($pageNumbers as $number): ?>
            <?php if ($number - $previous > 1): ?><span class="is-gap">…</span><?php endif; ?>
            <?php if ($number === $pagination['page']): ?>
                <span class="is-current" aria-current="page"><?= $number ?></span>
            <?php else: ?>
                <a href="<?= esc($pageUrl($number)) ?>"><?= $number ?></a>
            <?php endif; ?>
        <?php $previous = $number; endforeach; ?>
        <?php if ($pagination['page'] < $pagination['pages']): ?>
            <a href="<?= esc($pageUrl($pagination['page'] + 1)) ?>" aria-label="Halaman berikutnya"><i class="fa-solid fa-chevron-right"></i></a>
        <?php else: ?>
            <span class="is-disabled" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>
        <?php endif; ?>
    </nav>
    <label class="employee-count employee-per-page">Tampilkan
        <select class="form-control" onchange="window.location.href = this.value" aria-label="Jumlah baris per halaman">
            <?php foreach ($pagination['per_page_options'] as $option): ?>
                <option value="<?= esc($pageUrl(1, $option)) ?>" <?= $option === $pagination['per_page'] ? 'selected' : '' ?>><?= $option ?></option>
            <?php endforeach; ?>
        </select>
        per halaman
    </label>
</div>
<?php endif; ?>
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
