<?php
helper('employee_form');
$sectionKey = $section_key;
$section = $history_section;
$historyRows = $history_records ?? [];
$editable = ! empty($editable_history);
$fields = $section['fields'] ?? [];
$columns = employee_table_columns($fields);
$hasFileColumn = $sectionKey === 'anak';
// No., field columns, optional file column, and the delete button column in edit mode.
$columnCount = 1 + count($columns) + ($hasFileColumn ? 1 : 0) + ($editable ? 1 : 0);
$fileNote = 'Simpan data anak ini terlebih dahulu untuk menambahkan file.';
?>
<?= view('employee/repeat_table_styles') ?>
<section class="employee-section history-section" data-history-section="<?= esc($sectionKey) ?>" data-next-index="<?= count($historyRows) ?>">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border);">
        <h2 style="margin:0;padding:0;border:0;"><?= esc($section['letter'] . '. ' . $section['title']) ?></h2>
        <?php if ($editable): ?><button class="btn btn-outline btn-sm" type="button" data-add-history><i class="fa-solid fa-plus"></i> Tambah data</button><?php endif; ?>
    </div>
    <?php if ($editable): ?><input type="hidden" name="history_sections[]" value="<?= esc($sectionKey) ?>"><?php endif; ?>
    <div class="repeat-table-wrap">
        <table class="repeat-table">
            <thead>
                <tr>
                    <th>No</th>
                    <?php foreach ($columns as $column): ?><th><?= esc($column['title']) ?></th><?php endforeach; ?>
                    <?php if ($hasFileColumn): ?><th>File Upload</th><?php endif; ?>
                    <?php if ($editable): ?><th></th><?php endif; ?>
                </tr>
            </thead>
            <tbody class="history-entries">
                <?php if ($historyRows === []): ?>
                    <tr data-empty-row><td class="repeat-empty" colspan="<?= $columnCount ?>">Belum ada data<?= $editable ? '. Klik "Tambah data" untuk mengisi.' : '' ?></td></tr>
                <?php endif; ?>
                <?php foreach ($historyRows as $index => $record): ?>
                    <tr class="history-entry">
                        <td class="repeat-no">
                            <?= $index + 1 ?>
                            <?php if ($editable): ?><input type="hidden" name="histories[<?= esc($sectionKey) ?>][<?= $index ?>][id]" value="<?= (int) ($record['id'] ?? 0) ?>"><?php endif; ?>
                        </td>
                        <?php foreach ($columns as $column): ?>
                            <td><?= employee_table_cell($column, $record, $editable, 'histories[' . $sectionKey . '][' . $index . ']') ?></td>
                        <?php endforeach; ?>
                        <?php if ($hasFileColumn): ?>
                            <td class="repeat-file">
                                <?php if (! empty($record['dokumen'])): ?>
                                    <?= view('inline_document', ['attachment' => $record['dokumen'], 'attachment_mode' => $editable ? 'edit' : 'detail']) ?>
                                <?php else: ?>
                                    <span class="repeat-note"><?= esc($fileNote) ?></span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <?php if ($editable): ?>
                            <td class="repeat-action"><button class="btn btn-outline btn-sm" type="button" data-remove-history title="Hapus baris"><i class="fa-solid fa-trash"></i> Hapus</button></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($editable): ?>
        <template data-history-template>
            <tr class="history-entry">
                <td class="repeat-no">Baru<input type="hidden" name="histories[<?= esc($sectionKey) ?>][__INDEX__][id]" value="0"></td>
                <?php foreach ($columns as $column): ?>
                    <td><?= employee_table_cell($column, [], true, 'histories[' . $sectionKey . '][__INDEX__]') ?></td>
                <?php endforeach; ?>
                <?php if ($hasFileColumn): ?><td class="repeat-file"><span class="repeat-note"><?= esc($fileNote) ?></span></td><?php endif; ?>
                <td class="repeat-action"><button class="btn btn-outline btn-sm" type="button" data-remove-history title="Hapus baris"><i class="fa-solid fa-trash"></i> Hapus</button></td>
            </tr>
        </template>
    <?php endif; ?>
    <?= view('employee/section_documents', ['section_documents' => $section_documents ?? [], 'document_mode' => $document_mode ?? 'detail']) ?>
</section>
