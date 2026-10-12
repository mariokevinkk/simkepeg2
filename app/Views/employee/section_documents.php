<?php
// Checklist files that belong to one form section, shown right under its fields.
$sectionDocuments = $section_documents ?? [];
if ($sectionDocuments === []) {
    return;
}
?>
<h3 style="font-size:13px;margin:18px 0 8px;color:var(--text-muted);"><i class="fa-solid fa-paperclip"></i> Dokumen pendukung</h3>
<?= view('inline_document_group', ['attachments' => $sectionDocuments, 'attachment_mode' => $document_mode ?? 'detail']) ?>
