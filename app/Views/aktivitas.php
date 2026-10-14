<?php helper(['form', 'security']); ?>
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<style>
    .revision-previous { background:#fef3c7; border-radius:8px; color:#92400e; font-size:12px; margin-top:6px; padding:6px 9px; max-width:320px; white-space:normal; }
    .revision-modal { align-items:center; background:rgba(15,23,42,.55); display:none; inset:0; justify-content:center; padding:16px; position:fixed; z-index:1300; }
    .revision-modal.is-open { display:flex; }
    .revision-dialog { background:var(--bg-surface); border-radius:14px; box-shadow:0 24px 60px rgba(15,23,42,.35); max-width:520px; padding:22px; width:100%; }
    .revision-dialog h2 { font-size:17px; margin:0 0 4px; }
    .revision-dialog textarea { min-height:110px; resize:vertical; width:100%; }
    .revision-quick { display:flex; flex-wrap:wrap; gap:6px; margin:10px 0 4px; }
    .revision-quick button { background:var(--bg-body); border:1px solid var(--border); border-radius:999px; color:var(--text-main); cursor:pointer; font-size:12px; padding:4px 10px; }
    .revision-quick button:hover { border-color:var(--primary); color:var(--primary); }
    .activity-pagination { align-items:center; display:flex; flex-wrap:wrap; gap:12px; justify-content:space-between; margin:14px 0 0; }
    .activity-pages { display:flex; flex-wrap:wrap; gap:4px; }
    .activity-pages a, .activity-pages span { align-items:center; background:var(--bg-surface); border:1px solid var(--border); border-radius:8px; color:var(--text-main); display:inline-flex; font-size:13px; height:34px; justify-content:center; min-width:34px; padding:0 8px; text-decoration:none; }
    .activity-pages a:hover { border-color:var(--primary); color:var(--primary); }
    .activity-pages .is-current { background:var(--primary); border-color:var(--primary); color:#fff; font-weight:700; }
    .activity-pages .is-disabled { color:var(--text-muted); opacity:.5; }
    .activity-pages .is-gap { background:transparent; border-color:transparent; }
    .activity-per-page { align-items:center; color:var(--text-muted); display:flex; gap:8px; white-space:nowrap; }
    .activity-per-page select { padding:6px 10px; width:auto; }
    @media(max-width:650px) { .activity-pagination { justify-content:center; } }
</style>
<div style="margin-bottom:20px;"><h1 class="page-title" style="margin:0;">Verifikasi Dokumen</h1><p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;">Unggahan pegawai dalam cakupan unit Anda yang menunggu verifikasi. Jika dokumen perlu diperbaiki, tuliskan catatannya; pegawai akan melihat catatan itu pada dokumennya. Daftar diurutkan dari unggahan paling lama ke terbaru.</p></div>
<?php if ($error = session()->getFlashdata('error')): ?><div class="main-card" style="margin-bottom:14px;color:var(--danger);padding:12px 16px;"><?= esc($error) ?></div><?php endif; ?>
<?php if ($success = session()->getFlashdata('success')): ?><div class="main-card" style="margin-bottom:14px;color:var(--success);padding:12px 16px;"><?= esc($success) ?></div><?php endif; ?>
<div class="main-card" style="padding:0;overflow:hidden;">
    <div style="overflow:auto;"><table class="data-table">
        <thead><tr><th>Waktu unggah</th><th>Pegawai</th><th>Unit</th><th>Dokumen</th><th>File</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if ($pendingDocuments === []): ?><tr><td colspan="6" style="padding:30px;text-align:center;color:var(--text-muted);">Tidak ada dokumen yang menunggu verifikasi.</td></tr><?php endif; ?>
        <?php foreach ($pendingDocuments as $document): ?>
            <tr>
                <td><?= esc(date('d-m-Y H:i', strtotime($document['updated_at']))) ?></td>
                <td><strong><?= esc($document['employee_name']) ?></strong><div style="font-size:12px;color:var(--text-muted);">NIP <?= esc($document['nip']) ?></div></td>
                <td><?= esc($document['unit_name'] ?? '—') ?></td>
                <td>
                    <?= esc($document['title']) ?>
                    <?php if (! empty($document['verification_note'])): ?>
                        <div class="revision-previous"><strong>Unggahan ulang.</strong> Catatan perbaikan sebelumnya: <?= esc($document['verification_note']) ?></div>
                    <?php endif; ?>
                </td>
                <td><?= esc($document['original_name']) ?> <a href="<?= base_url('/documents/' . (int) $document['id']) ?>" target="_blank" rel="noopener">Lihat file</a></td>
                <td style="white-space:nowrap;">
                    <form method="post" action="<?= base_url('/documents/' . (int) $document['id'] . '/verification') ?>" style="display:inline-flex;gap:6px;">
                        <?= csrf_field() ?>
                        <button class="btn btn-primary btn-sm" type="submit" name="verification_status" value="verified" style="background:var(--success);"><i class="fa-solid fa-check"></i> Verifikasi</button>
                        <button class="btn btn-outline btn-sm" type="button" style="color:var(--danger);" data-revision-open
                            data-action="<?= base_url('/documents/' . (int) $document['id'] . '/verification') ?>"
                            data-document="<?= esc($document['title']) ?>" data-employee="<?= esc($document['employee_name']) ?>"><i class="fa-solid fa-rotate-left"></i> Perlu perbaikan</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</div>
<?php
$firstRow = $pagination['total'] === 0 ? 0 : ($pagination['page'] - 1) * $pagination['per_page'] + 1;
$lastRow = min($pagination['total'], $pagination['page'] * $pagination['per_page']);
$pageUrl = static function (int $page, ?int $perPage = null): string {
    $query = array_filter(service('request')->getGet() ?? [], static fn ($value): bool => is_scalar($value) && $value !== '');
    $query['page'] = $page;
    if ($perPage !== null) {
        $query['per_page'] = $perPage;
    }
    return base_url('/aktivitas') . '?' . http_build_query($query);
};
$pageNumbers = [];
for ($number = 1; $number <= $pagination['pages']; $number++) {
    if ($number === 1 || $number === $pagination['pages'] || abs($number - $pagination['page']) <= 2) {
        $pageNumbers[] = $number;
    }
}
?>
<?php if ($pagination['total'] > 0): ?>
<div class="activity-pagination">
    <span style="color:var(--text-muted);font-size:13px;">Menampilkan <?= $firstRow ?>–<?= $lastRow ?> dari <?= $pagination['total'] ?> unggahan</span>
    <nav class="activity-pages" aria-label="Halaman aktivitas">
        <?php if ($pagination['page'] > 1): ?><a href="<?= esc($pageUrl($pagination['page'] - 1)) ?>" aria-label="Halaman sebelumnya"><i class="fa-solid fa-chevron-left"></i></a><?php else: ?><span class="is-disabled" aria-hidden="true"><i class="fa-solid fa-chevron-left"></i></span><?php endif; ?>
        <?php $previous = 0; foreach ($pageNumbers as $number): ?>
            <?php if ($number - $previous > 1): ?><span class="is-gap">…</span><?php endif; ?>
            <?php if ($number === $pagination['page']): ?><span class="is-current" aria-current="page"><?= $number ?></span><?php else: ?><a href="<?= esc($pageUrl($number)) ?>"><?= $number ?></a><?php endif; ?>
        <?php $previous = $number; endforeach; ?>
        <?php if ($pagination['page'] < $pagination['pages']): ?><a href="<?= esc($pageUrl($pagination['page'] + 1)) ?>" aria-label="Halaman berikutnya"><i class="fa-solid fa-chevron-right"></i></a><?php else: ?><span class="is-disabled" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span><?php endif; ?>
    </nav>
    <label class="activity-per-page">Tampilkan
        <select class="form-control" onchange="window.location.href = this.value" aria-label="Jumlah aktivitas per halaman">
            <?php foreach ($pagination['per_page_options'] as $option): ?><option value="<?= esc($pageUrl(1, $option)) ?>" <?= $option === $pagination['per_page'] ? 'selected' : '' ?>><?= $option ?></option><?php endforeach; ?>
        </select>
        per halaman
    </label>
</div>
<?php endif; ?>

<div class="revision-modal" id="revision-modal" role="dialog" aria-modal="true" aria-labelledby="revision-title">
    <form class="revision-dialog" method="post" action="">
        <?= csrf_field() ?>
        <input type="hidden" name="verification_status" value="rejected">
        <h2 id="revision-title">Minta perbaikan dokumen</h2>
        <div style="color:var(--text-muted);font-size:13px;" data-revision-subject></div>
        <label class="form-label" for="verification_note" style="margin-top:14px;display:block;">Catatan perbaikan untuk pegawai</label>
        <div class="revision-quick" aria-label="Contoh catatan">
            <button type="button" data-note="File tidak terbaca / buram, mohon unggah hasil scan yang jelas.">File buram</button>
            <button type="button" data-note="Dokumen yang diunggah tidak sesuai, mohon unggah dokumen yang benar.">Dokumen salah</button>
            <button type="button" data-note="Dokumen sudah tidak berlaku, mohon unggah dokumen terbaru.">Sudah tidak berlaku</button>
            <button type="button" data-note="Halaman dokumen belum lengkap, mohon unggah semua halaman.">Halaman kurang</button>
        </div>
        <textarea class="form-control" id="verification_note" name="verification_note" required minlength="5" maxlength="1000" placeholder="Contoh: Tanggal berlaku STR tidak terlihat, mohon unggah ulang halaman depan."></textarea>
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px;">
            <button type="button" class="btn btn-outline" data-revision-close>Batal</button>
            <button type="submit" class="btn btn-primary" style="background:var(--danger);border-color:var(--danger);"><i class="fa-solid fa-paper-plane"></i> Kirim catatan perbaikan</button>
        </div>
    </form>
</div>
<script>
(function () {
    const modal = document.getElementById('revision-modal');
    const form = modal.querySelector('form');
    const note = form.querySelector('textarea');
    const close = function () { modal.classList.remove('is-open'); };
    document.querySelectorAll('[data-revision-open]').forEach(function (button) {
        button.addEventListener('click', function () {
            form.action = button.dataset.action;
            modal.querySelector('[data-revision-subject]').textContent = button.dataset.document + ' — ' + button.dataset.employee;
            note.value = '';
            modal.classList.add('is-open');
            note.focus();
        });
    });
    form.querySelectorAll('[data-note]').forEach(function (chip) {
        chip.addEventListener('click', function () {
            note.value = note.value.trim() === '' ? chip.dataset.note : note.value.trim() + ' ' + chip.dataset.note;
            note.focus();
        });
    });
    modal.querySelector('[data-revision-close]').addEventListener('click', close);
    modal.addEventListener('click', function (event) { if (event.target === modal) close(); });
    document.addEventListener('keydown', function (event) { if (event.key === 'Escape') close(); });
})();
</script>
<?= $this->endSection() ?>
