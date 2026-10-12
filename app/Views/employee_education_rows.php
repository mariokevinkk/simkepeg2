<?php
$employeeProfile = $employee_profile ?? \App\Libraries\EmployeeProfileData::get();
$educationRecords = $employeeProfile['pendidikan'] ?? [];
$showActions = $show_actions ?? false;
$attachmentMode = $attachment_mode ?? 'detail';
$columnCount = $showActions ? 11 : 10;
?>
<?php foreach ($educationRecords as $index => $education): ?>
    <tr>
        <td><?= $index + 1 ?></td>
        <td><?= esc($education['tingkat']) ?></td>
        <td><?= esc($education['nama']) ?></td>
        <td><?= esc($education['instansi']) ?></td>
        <td><?= esc($education['fakultas']) ?></td>
        <td><?= esc($education['tempat']) ?></td>
        <td><?= esc($education['nomor_ijazah']) ?></td>
        <td><?= date('d-m-Y', strtotime($education['tanggal_ijazah'])) ?></td>
        <td><?= esc($education['nilai']) ?></td>
        <td><?= esc($education['status']) ?></td>
        <?php if ($showActions): ?>
            <td style="white-space: nowrap;">
                <a href="#" style="color: var(--primary); margin-right: 8px;" title="Edit"><i class="fa-solid fa-pen"></i></a>
                <a href="#" style="color: var(--danger);" title="Hapus"><i class="fa-solid fa-trash"></i></a>
            </td>
        <?php endif; ?>
    </tr>
    <tr>
        <td colspan="<?= $columnCount ?>" style="padding: 12px; background: var(--bg-body);">
            <?= view('inline_document_group', [
                'attachments' => $education['dokumen'] ?? [],
                'attachment_mode' => $attachmentMode,
            ]) ?>
        </td>
    </tr>
<?php endforeach; ?>
