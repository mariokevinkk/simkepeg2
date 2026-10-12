<?php
$employeeProfile = $employee_profile ?? \App\Libraries\EmployeeProfileData::get();
$group = $document_group ?? '';
$attachments = $group === 'pendidikan'
    ? ($employeeProfile['pendidikan'][0]['dokumen'] ?? [])
    : ($employeeProfile['dokumen'][$group] ?? []);
?>
<?= view('inline_document_group', [
    'attachments' => $attachments,
    'attachment_mode' => $document_mode ?? 'detail',
]) ?>
