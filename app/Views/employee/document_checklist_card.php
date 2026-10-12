<?php
$checklist = $checklist ?? [];
$summary = ($checklist['summary'] ?? []) + ['total' => 0, 'uploaded' => 0, 'verified' => 0, 'pending' => 0, 'missing' => [], 'complete' => true];
$otherDocuments = $checklist['other'] ?? ['wajib' => [], 'tambahan' => []];
$documentMode = $document_mode ?? 'detail';
$groupTitles = ['wajib' => 'File wajib', 'tambahan' => 'File tambahan'];
?>
<section class="employee-section" id="checklist-dokumen">
    <h2>Checklist Dokumen</h2>
    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:14px;">
        <span class="status-badge <?= $summary['complete'] ? 'status-active' : '' ?>" style="<?= $summary['complete'] ? '' : 'background:#fee2e2;color:#b91c1c;' ?>">
            <?= $summary['complete'] ? 'Dokumen wajib lengkap' : count($summary['missing']) . ' dokumen wajib belum lengkap' ?>
        </span>
        <span style="font-size:13px;color:var(--text-muted);align-self:center;">
            <?= (int) $summary['uploaded'] ?> dari <?= (int) $summary['total'] ?> dokumen wajib sudah diunggah · <?= (int) $summary['verified'] ?> terverifikasi · <?= (int) $summary['pending'] ?> menunggu verifikasi
        </span>
    </div>
    <?php if ($summary['missing'] !== []): ?>
        <div style="padding:12px 16px;border-radius:9px;background:#fef2f2;border:1px solid #fecaca;margin-bottom:18px;">
            <strong style="color:#991b1b;font-size:13px;">Kurang dokumen wajib:</strong>
            <ul style="margin:6px 0 0;padding-left:18px;color:#991b1b;font-size:13px;">
                <?php foreach ($summary['missing'] as $missing): ?><li><?= esc($missing) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <p style="font-size:12px;color:var(--text-muted);margin:0 0 6px;">Dokumen yang berkaitan dengan isian ditempel langsung pada bagiannya (misalnya SK Penugasan pada bagian E, ijazah pada setiap baris riwayat pendidikan J). Dokumen di bawah ini tidak memiliki isian terkait.</p>
    <?php foreach ($groupTitles as $group => $groupTitle): ?>
        <?php $attachments = $otherDocuments[$group] ?? []; if ($attachments === []) { continue; } ?>
        <h3 style="font-size:14px;margin:18px 0 10px;"><?= esc($groupTitle) ?></h3>
        <?= view('inline_document_group', ['attachments' => $attachments, 'attachment_mode' => $documentMode]) ?>
    <?php endforeach; ?>
</section>
