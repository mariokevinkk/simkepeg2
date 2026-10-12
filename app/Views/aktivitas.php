<?php helper(['form', 'security']); ?>
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div style="margin-bottom:20px;"><h1 class="page-title" style="margin:0;">Verifikasi Dokumen</h1><p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;">Unggahan pegawai dalam cakupan unit Anda yang menunggu verifikasi.</p></div>
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
                <td><?= esc($document['title']) ?></td>
                <td><?= esc($document['original_name']) ?> <a href="<?= base_url('/documents/' . (int) $document['id']) ?>" target="_blank" rel="noopener">Lihat file</a></td>
                <td style="white-space:nowrap;">
                    <form method="post" action="<?= base_url('/documents/' . (int) $document['id'] . '/verification') ?>" style="display:inline-flex;gap:6px;">
                        <?= csrf_field() ?>
                        <button class="btn btn-primary btn-sm" type="submit" name="verification_status" value="verified" style="background:var(--success);"><i class="fa-solid fa-check"></i> Verifikasi</button>
                        <button class="btn btn-outline btn-sm" type="submit" name="verification_status" value="rejected" style="color:var(--danger);"><i class="fa-solid fa-rotate-left"></i> Perlu perbaikan</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</div>
<?= $this->endSection() ?>
