<?php
$attachments = $attachments ?? [];
$attachmentMode = $attachment_mode ?? 'detail';

if ($attachments === []) {
    return;
}
?>
<?php if (! defined('INLINE_DOCUMENT_GROUP_STYLES')): define('INLINE_DOCUMENT_GROUP_STYLES', true); ?>
<style>
    .inline-document-group {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 340px), 1fr));
        gap: 12px;
        margin: 0 0 24px;
    }
    .inline-document-group .inline-document-card {
        margin-top: 0;
    }
    @media (max-width: 760px) {
        .inline-document-group {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php endif; ?>
<div class="inline-document-group">
    <?php foreach ($attachments as $attachment): ?>
        <?= view('inline_document', ['attachment' => $attachment, 'attachment_mode' => $attachmentMode]) ?>
    <?php endforeach; ?>
</div>
