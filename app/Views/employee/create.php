<?php helper(['form', 'security', 'employee_form']); ?>
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php $coreSections = array_merge(\App\Libraries\EmployeeProfileData::officialCoreSections(), [\App\Libraries\EmployeeProfileData::officialLatestEducationSection()]); ?>
<style>
    .create-employee-sections { max-width:1100px; margin:0 auto; }
    .create-employee-section { margin:24px 0; padding:24px; }
    .create-employee-section h2 { margin:0 0 18px; font-size:18px; }
    .create-employee-fields { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; }
    @media(max-width:900px) { .create-employee-fields { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:600px) { .create-employee-fields { grid-template-columns:1fr; } }
    .employee-summary-note { background:var(--primary-light); border-radius:8px; color:var(--text-main); font-size:12px; margin:-6px 0 14px; padding:9px 12px; }
    .employee-summary-note i { color:var(--primary); margin-right:4px; }
    .employee-auto-value { min-height:44px; padding:12px 14px; border:1px dashed var(--border); border-radius:9px; color:var(--text-muted); font-size:13px; }
    .employee-fields { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; }
    .employee-field { min-width:0; }
    .employee-section h2 { font-size:18px; margin:0 0 18px; }
    @media(max-width:900px) { .employee-fields { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:600px) { .employee-fields { grid-template-columns:1fr; } }
</style>
<div class="create-employee-sections">
    <div style="margin-bottom:20px;"><h1 class="page-title" style="margin:0;">Tambah Pegawai</h1><p style="color:var(--text-muted);font-size:13px;">Isi data sesuai formulir Data Pegawai Kota Yogyakarta. Bagian riwayat (G–X) dapat diisi lebih dari satu baris dengan tombol Tambah data. Dokumen diunggah setelah data pegawai disimpan.</p></div>
    <?php if ($error = session()->getFlashdata('error')): ?><div class="main-card" style="margin-bottom:14px;color:var(--danger);padding:12px 16px;"><?= esc($error) ?></div><?php endif; ?>
    <?php $errors = session()->getFlashdata('errors') ?? []; if ($errors !== []): ?><div class="main-card" style="margin-bottom:14px;color:var(--danger);"><ul><?php foreach ($errors as $message): ?><li><?= esc($message) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post" action="<?= base_url('/employees') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="unit_id" id="unit_id" value="<?= esc(old('unit_id', '')) ?>" required>
        <section class="main-card create-employee-section">
            <h2>Akun Login</h2>
            <div class="create-employee-fields">
                <div><label class="form-label" for="username">Username</label><input class="form-control" id="username" name="username" value="<?= esc(old('username')) ?>" minlength="4" maxlength="80" autocomplete="username" required></div>
                <div><label class="form-label">Password Awal</label><div style="min-height:44px;padding:12px 14px;border:1px solid var(--border);border-radius:9px;color:var(--text-muted);">Tanggal lahir berformat ddmmyyyy</div><small style="display:block;color:var(--text-muted);margin-top:6px;">Pegawai dapat mengubah password setelah login.</small></div>
            </div>
        </section>
        <?php foreach ($coreSections as $section): ?>
            <section class="main-card create-employee-section">
                <h2><?= esc($section['letter'] . '. ' . $section['title']) ?></h2>
                <?= view('employee/summary_note', ['summary_letter' => $section['letter']]) ?>
                <div class="create-employee-fields">
                    <?php foreach ($section['fields'] as $fieldDefinition): ?>
                        <?php [$fieldKey, $label, $type] = array_pad($fieldDefinition, 3, 'text'); ?>
                        <div<?= $type === 'textarea' ? ' style="grid-column:1/-1;"' : '' ?>>
                            <label class="form-label" for="<?= esc($fieldKey) ?>"><?= esc($label) ?></label>
                            <?php if (\App\Libraries\EmployeeProfileData::summarySourceFor($fieldKey) !== null): ?>
                                <div class="employee-auto-value">Otomatis dari riwayat</div>
                            <?php elseif ($fieldKey === 'jenis_kepegawaian'): ?>
                                <?= view('employee/employment_type_select', ['fieldName' => $fieldKey, 'value' => old($fieldKey, '')]) ?>
                            <?php elseif ($type === 'unit'): ?>
                                <select class="form-control" id="unit_kerja" name="unit_kerja" data-unit-select required><option value="">Pilih unit kerja</option><?php foreach ($units as $unit): ?><option value="<?= esc($unit['name']) ?>" data-unit-id="<?= (int) $unit['id'] ?>" <?= old('unit_kerja') === $unit['name'] || (string) old('unit_id') === (string) $unit['id'] ? 'selected' : '' ?>><?= esc($unit['name']) ?></option><?php endforeach; ?></select>
                            <?php else: ?>
                                <?= employee_field_input($fieldDefinition, $fieldKey, old($fieldKey, ''), ['id' => $fieldKey, 'required' => in_array($fieldKey, ['nip', 'nama', 'tanggal_lahir'], true)]) ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
        <?php $historySections = \App\Libraries\EmployeeProfileData::officialHistorySections(); ?>
        <?php foreach ($historySections as $category => $section): ?>
            <?php if (in_array($category, ['kepangkatan', 'jabatan', 'tugas_tambahan'], true)): ?>
                <div class="main-card create-employee-section"><?= view('employee/history_repeater', ['section_key' => $category, 'history_section' => $section, 'history_records' => [], 'editable_history' => true]) ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="main-card create-employee-section"><?= view('employee/education_section', ['education_records' => [], 'editable_education' => true]) ?></div>
        <?php foreach ($historySections as $category => $section): ?>
            <?php if (! in_array($category, ['kepangkatan', 'jabatan', 'tugas_tambahan'], true)): ?>
                <div class="main-card create-employee-section"><?= view('employee/history_repeater', ['section_key' => $category, 'history_section' => $section, 'history_records' => [], 'editable_history' => true]) ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
        <?= view('employee/save_bar', ['save_label' => 'Simpan pegawai', 'cancel_url' => base_url('/data-pegawai')]) ?>
    </form>
</div>
<?= view('employee/repeater_script') ?>
<script>
(function () {
    const unitSelect = document.querySelector('[data-unit-select]');
    const unitId = document.getElementById('unit_id');
    if (!unitSelect || !unitId) return;
    const syncUnit = function () {
        unitId.value = unitSelect.selectedOptions[0]?.dataset.unitId || '';
    };
    unitSelect.addEventListener('change', syncUnit);
    syncUnit();
})();
</script>
<?= $this->endSection() ?>
