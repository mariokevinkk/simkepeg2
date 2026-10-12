<?php
// Note for sections D/E/F, whose main fields are copied from the newest history row.
$summarySource = \App\Libraries\EmployeeProfileData::summarySources()[$summary_letter ?? ''] ?? null;
if ($summarySource === null) {
    return;
}
?>
<p class="employee-summary-note"><i class="fa-solid fa-link"></i> Terisi otomatis dari baris terbaru <strong><?= esc($summarySource['source']) ?></strong>. Untuk mengubahnya, tambah atau ubah data di tabel riwayat tersebut.</p>
