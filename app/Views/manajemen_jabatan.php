<?php helper(['form', 'security']); ?>
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$jobCount = array_sum(array_map('count', $groups));
$activeCount = 0;
foreach ($groups as $jobs) {
    foreach ($jobs as $job) {
        $activeCount += (int) $job['is_active'];
    }
}
?>
<style>
    .job-page-head { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom:20px; }
    .job-page-head p { color:var(--text-muted); font-size:13px; margin:6px 0 0; }
    .job-count { background:var(--primary-light); border-radius:999px; color:var(--primary); font-size:12px; font-weight:700; padding:8px 12px; white-space:nowrap; }
    .job-create { padding:22px; margin-bottom:20px; }
    .job-create h2, .job-list h2 { font-size:17px; margin:0 0 6px; }
    .job-create p, .job-list p { color:var(--text-muted); font-size:13px; margin:0 0 18px; }
    .job-create-form { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1.6fr) auto; gap:14px; align-items:end; }
    .job-create-form > div { min-width:0; }
    .job-create-form .btn { min-height:44px; }
    .job-list { padding:22px; }
    .job-list-head { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:14px; }
    .job-list-head p { margin:0; }
    .job-search { max-width:310px; width:100%; }
    .job-groups { display:grid; gap:10px; }
    .job-group { border:1px solid var(--border); border-radius:11px; overflow:hidden; }
    .job-group > summary { align-items:center; cursor:pointer; display:flex; gap:12px; justify-content:space-between; list-style:none; padding:14px 16px; }
    .job-group > summary::-webkit-details-marker { display:none; }
    .job-group > summary::after { color:var(--text-muted); content:'+'; font-size:20px; line-height:1; }
    .job-group[open] > summary::after { content:'−'; }
    .job-group-name { font-weight:700; }
    .job-group-count { color:var(--text-muted); font-size:12px; font-weight:500; margin-left:7px; }
    .job-rows { border-top:1px solid var(--border); padding:5px 16px; }
    .job-row { align-items:center; display:flex; gap:12px; justify-content:space-between; min-height:46px; padding:8px 0; }
    .job-row + .job-row { border-top:1px solid var(--border); }
    .job-row[hidden], .job-group[hidden] { display:none !important; }
    .job-row.inactive .job-title { color:var(--text-muted); }
    .job-row-actions { align-items:center; display:flex; flex-shrink:0; gap:9px; }
    .job-row-actions .btn { padding:6px 10px; }
    .job-empty-search { color:var(--text-muted); display:none; font-size:13px; padding:18px 0 2px; }
    .job-empty-search.show { display:block; }
    @media(max-width:760px) { .job-create-form { grid-template-columns:1fr; } .job-create-form .btn { justify-self:start; } }
    @media(max-width:520px) { .job-page-head { flex-wrap:wrap; } .job-row { align-items:flex-start; flex-direction:column; } }
</style>

<div class="job-page-head">
    <div>
        <h1 class="page-title" style="margin:0;">Manajemen Jabatan</h1>
        <p>Kelompok jenis ketenagaan menentukan pilihan jabatan pada data pegawai.</p>
    </div>
    <span class="job-count"><?= $activeCount ?> jabatan aktif</span>
</div>

<?php if ($error = session()->getFlashdata('error')): ?><div class="main-card" style="margin-bottom:14px;color:var(--danger);padding:12px 16px;"><?= esc($error) ?></div><?php endif; ?>
<?php if ($success = session()->getFlashdata('success')): ?><div class="main-card" style="margin-bottom:14px;color:var(--success);padding:12px 16px;"><?= esc($success) ?></div><?php endif; ?>

<section class="main-card job-create">
    <h2>Tambah nama jabatan</h2>
    <p>Pilih jenis ketenagaan yang ada atau ketik jenis baru, lalu isi nama jabatan lengkap.</p>
    <form class="job-create-form" method="post" action="<?= base_url('/manajemen-jabatan') ?>">
        <?= csrf_field() ?>
        <div>
            <label class="form-label" for="job-group-name">Jenis ketenagaan</label>
            <input class="form-control" id="job-group-name" name="group_name" list="job-group-options" value="<?= esc(old('group_name')) ?>" maxlength="80" placeholder="Contoh: Dokter" required>
            <datalist id="job-group-options"><?php foreach (array_keys($groups) as $groupName): ?><option value="<?= esc($groupName) ?>"></option><?php endforeach; ?></datalist>
        </div>
        <div>
            <label class="form-label" for="job-title-name">Nama jabatan</label>
            <input class="form-control" id="job-title-name" name="title" value="<?= esc(old('title')) ?>" maxlength="180" placeholder="Contoh: Dokter Ahli Pertama" required>
        </div>
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-plus"></i> Tambah jabatan</button>
    </form>
</section>

<section class="main-card job-list" id="daftar-jabatan">
    <div class="job-list-head">
        <div><h2>Daftar jabatan</h2><p><?= $jobCount ?> jabatan dalam <?= count($groups) ?> jenis ketenagaan</p></div>
        <input class="form-control job-search" id="job-search" type="search" placeholder="Cari jenis atau nama jabatan" aria-label="Cari jenis atau nama jabatan">
    </div>
    <div class="job-groups">
        <?php foreach ($groups as $groupName => $jobs): ?>
            <?php $groupActive = count(array_filter($jobs, static fn (array $job): bool => (int) $job['is_active'] === 1)); ?>
            <details class="job-group" data-job-group data-name="<?= esc(mb_strtolower($groupName)) ?>">
                <summary><span><span class="job-group-name"><?= esc($groupName) ?></span><span class="job-group-count"><?= $groupActive ?> aktif dari <?= count($jobs) ?></span></span></summary>
                <div class="job-rows">
                    <?php foreach ($jobs as $job): ?>
                        <div class="job-row <?= (int) $job['is_active'] === 1 ? '' : 'inactive' ?>" data-job-row data-name="<?= esc(mb_strtolower($job['title'])) ?>">
                            <span class="job-title"><?= esc($job['title']) ?></span>
                            <div class="job-row-actions">
                                <span class="status-badge <?= (int) $job['is_active'] === 1 ? 'status-active' : '' ?>"><?= (int) $job['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?></span>
                                <?php if ($can_manage_status): ?>
                                    <form method="post" action="<?= base_url('/manajemen-jabatan/' . (int) $job['id'] . '/status') ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="is_active" value="<?= (int) $job['is_active'] === 1 ? '0' : '1' ?>">
                                        <button class="btn btn-outline btn-sm" type="submit"><?= (int) $job['is_active'] === 1 ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>
        <?php endforeach; ?>
    </div>
    <div class="job-empty-search" id="job-empty-search">Tidak ada jabatan yang cocok.</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('job-search');
    const groups = document.querySelectorAll('[data-job-group]');
    const empty = document.getElementById('job-empty-search');
    if (!search) return;
    search.addEventListener('input', function () {
        const query = search.value.trim().toLocaleLowerCase();
        let visible = 0;
        groups.forEach(function (group) {
            const groupMatches = group.dataset.name.includes(query);
            let groupVisible = 0;
            group.querySelectorAll('[data-job-row]').forEach(function (row) {
                const matches = groupMatches || row.dataset.name.includes(query);
                row.hidden = !matches;
                if (matches) groupVisible++;
            });
            group.hidden = groupVisible === 0;
            if (query && groupVisible > 0) group.open = true;
            visible += groupVisible;
        });
        empty.classList.toggle('show', visible === 0);
    });
});
</script>
<?= $this->endSection() ?>
