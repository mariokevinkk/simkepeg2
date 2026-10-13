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
</style>
<div style="margin-bottom:20px;"><h1 class="page-title" style="margin:0;">Verifikasi Dokumen</h1><p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;">Unggahan pegawai dalam cakupan unit Anda yang menunggu verifikasi. Jika dokumen perlu diperbaiki, tuliskan catatannya; pegawai akan melihat catatan itu pada dokumennya.</p></div>
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
