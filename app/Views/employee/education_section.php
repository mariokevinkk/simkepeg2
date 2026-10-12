<?php
helper('employee_form');
$educationRecords = $education_records ?? [];
$editable = ! empty($editable_education);
$educationDocuments = $education_documents ?? [];
$documentMode = $document_mode ?? 'detail';
$columns = employee_table_columns(\App\Libraries\EmployeeProfileData::educationFields());
$hasFileColumn = $educationRecords !== [] || $editable;
$columnCount = 1 + count($columns) + ($hasFileColumn ? 1 : 0) + ($editable ? 1 : 0);
$fileNote = 'Simpan data pendidikan ini terlebih dahulu, lalu unggah ijazah di sini.';
?>
<?= view('employee/repeat_table_styles') ?>
<section class="employee-section">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border);">
        <h2 style="margin:0;padding:0;border:0;">J. RIWAYAT PENDIDIKAN</h2>
        <?php if ($editable): ?><button class="btn btn-outline btn-sm" id="add-education-row" type="button"><i class="fa-solid fa-plus"></i> Tambah data</button><?php endif; ?>
    </div>
    <?php if ($editable): ?><input type="hidden" name="education_section" value="1"><?php endif; ?>
    <div class="repeat-table-wrap">
        <table class="repeat-table">
            <thead>
                <tr>
                    <th>No</th>
                    <?php foreach ($columns as $column): ?><th><?= esc($column['title']) ?></th><?php endforeach; ?>
                    <?php if ($hasFileColumn): ?><th>Ijazah &amp; Transkrip</th><?php endif; ?>
                    <?php if ($editable): ?><th></th><?php endif; ?>
                </tr>
            </thead>
            <tbody id="education-rows" data-next-index="<?= count($educationRecords) ?>">
                <?php if ($educationRecords === []): ?>
                    <tr data-empty-row><td class="repeat-empty" colspan="<?= $columnCount ?>">Belum ada data<?= $editable ? '. Klik "Tambah data" untuk mengisi.' : '' ?></td></tr>
                <?php endif; ?>
                <?php foreach ($educationRecords as $index => $education): ?>
                    <tr class="education-entry">
                        <td class="repeat-no">
                            <?= $index + 1 ?>
                            <?php if ($editable): ?><input type="hidden" name="education[<?= $index ?>][id]" value="<?= (int) $education['id'] ?>"><?php endif; ?>
                        </td>
                        <?php foreach ($columns as $column): ?>
                            <td><?= employee_table_cell($column, $education, $editable, 'education[' . $index . ']') ?></td>
                        <?php endforeach; ?>
                        <td class="repeat-file">
                            <?php if (isset($educationDocuments[(int) $education['id']])): ?>
                                <?= view('inline_document', ['attachment' => $educationDocuments[(int) $education['id']], 'attachment_mode' => $documentMode]) ?>
                            <?php else: ?>
                                <span class="repeat-note"><?= esc($fileNote) ?></span>
                            <?php endif; ?>
                        </td>
                        <?php if ($editable): ?>
                            <td class="repeat-action"><button class="btn btn-outline btn-sm" type="button" data-remove-education title="Hapus baris"><i class="fa-solid fa-trash"></i> Hapus</button></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($editable): ?>
        <template id="education-template">
            <tr class="education-entry">
                <td class="repeat-no">Baru<input type="hidden" name="education[__INDEX__][id]" value="0"></td>
                <?php foreach ($columns as $column): ?>
                    <td><?= employee_table_cell($column, [], true, 'education[__INDEX__]') ?></td>
                <?php endforeach; ?>
                <td class="repeat-file"><span class="repeat-note"><?= esc($fileNote) ?></span></td>
                <td class="repeat-action"><button class="btn btn-outline btn-sm" type="button" data-remove-education title="Hapus baris"><i class="fa-solid fa-trash"></i> Hapus</button></td>
            </tr>
        </template>
    <?php endif; ?>
    <?php if ($educationRecords === []): ?>
        <?= view('employee/section_documents', ['section_documents' => $section_documents ?? [], 'document_mode' => $documentMode]) ?>
    <?php endif; ?>
</section>
