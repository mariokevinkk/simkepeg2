<?php
helper(['form', 'security', 'employee_form']);
$profile = $employee_profile;
$employee = $profile['pegawai'];
$educationRecords = $profile['pendidikan'] ?? [];
$historyRecords = $profile['histories'] ?? [];
$employeeId = (int) $employee_id;
$editable = ! empty($editable_page) && ! empty($can_edit);
$documentMode = $editable && ! empty($can_manage_documents) ? 'edit' : 'detail';
$needsLoginAccount = $editable && ($role ?? '') !== 'pegawai' && empty($employee['user_id']);
$coreSections = \App\Libraries\EmployeeProfileData::officialCoreSections();
$historySections = \App\Libraries\EmployeeProfileData::officialHistorySections();
$profilePages = \App\Libraries\EmployeeProfileData::profilePages();
$currentPage = $profile_page ?? 'profil-pegawai';
$professionalLicenseSections = \App\Libraries\EmployeeProfileData::professionalLicenseSections();
$professionalLicense = $professionalLicenseSections[$currentPage] ?? null;
$jobTitlesByGroup = $editable ? (new \App\Models\JobTitleModel())->allGrouped() : [];
$protectedFields = ['nip', 'nip_lama', 'employee_status', 'jenis_kepegawaian', 'kedudukan'];
$latestEducationSection = \App\Libraries\EmployeeProfileData::officialLatestEducationSection();
$checklist = $profile['checklist'] ?? [];
$pageSections = $profilePages[$currentPage]['sections'] ?? ['A'];
$showSection = static fn (string $letter): bool => in_array($letter, $pageSections, true);
$isPegawaiView = ($role ?? '') === 'pegawai';
$pageUrl = static fn (string $pageKey): string => $isPegawaiView
    ? base_url('/' . $pageKey)
    : base_url(($editable ? '/edit-pegawai' : '/view-pegawai') . '?id=' . $employeeId . '&bagian=' . $pageKey);
$sectionDocuments = $checklist['by_section'] ?? [];
?>
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<style>
    .employee-fields { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; }
    .employee-field { min-width:0; }
    .employee-value { color:var(--text-main); min-height:44px; padding:12px 14px; background:var(--bg-body); border:1px solid var(--border); border-radius:9px; overflow-wrap:anywhere; white-space:pre-wrap; }
    .employee-section { margin:26px 0; }
    .employee-section h2 { font-size:17px; margin:0 0 14px; padding-bottom:10px; border-bottom:1px solid var(--border); }
    .employee-flash { padding:12px 16px; border-radius:9px; margin-bottom:16px; }
    .employee-flash.success { background:#dcfce7; color:#166534; }
    .employee-flash.error { background:#fee2e2; color:#991b1b; }
    .employee-summary-note { background:var(--primary-light); border-radius:8px; color:var(--text-main); font-size:12px; margin:0 0 14px; padding:9px 12px; }
    .employee-summary-note i { color:var(--primary); margin-right:4px; }
    .employee-tabs { display:flex; flex-wrap:wrap; gap:6px; margin:0 0 16px; }
    .employee-tabs a { display:inline-flex; align-items:center; gap:7px; padding:8px 13px; border:1px solid var(--border); border-radius:999px; color:var(--text-muted); text-decoration:none; font-size:13px; font-weight:600; background:var(--bg-surface); }
    .employee-tabs a.active, .employee-tabs a:hover { color:var(--primary); border-color:var(--primary); background:var(--primary-light); }
    @media(max-width:900px) { .employee-fields { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:600px) { .employee-fields { grid-template-columns:1fr; } }
</style>

<div class="flex-mobile-stack" style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;">
    <div>
        <h1 class="page-title" style="margin:0;"><?= esc($profilePages[$currentPage]['title'] ?? 'Data Pegawai') ?></h1>
        <div style="color:var(--text-muted);margin-top:5px;">NIP <?= esc($employee['nip'] ?? '') ?></div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="btn btn-outline" href="<?= base_url('/data-pegawai/word' . ($isPegawaiView ? '' : '?id=' . $employeeId)) ?>" style="text-decoration:none;" title="Formulir Data Pegawai Kota Yogyakarta dalam format Word"><i class="fa-solid fa-file-word"></i> Unduh Word</a>
        <?php if (! $editable && ! empty($can_edit)): ?>
            <a class="btn btn-primary" href="<?= $isPegawaiView ? base_url('/' . $currentPage) : base_url('/edit-pegawai?id=' . $employeeId . '&bagian=' . $currentPage) ?>"><i class="fa-solid fa-pen"></i> Edit data</a>
        <?php endif; ?>
    </div>
</div>
<?php if ($message = session()->getFlashdata('success')): ?><div class="employee-flash success"><?= esc($message) ?></div><?php endif; ?>
<?php if ($message = session()->getFlashdata('error')): ?><div class="employee-flash error"><?= esc($message) ?></div><?php endif; ?>
<?php $formErrors = session()->getFlashdata('errors') ?? []; if ($formErrors !== []): ?><div class="employee-flash error"><ul><?php foreach ($formErrors as $formError): ?><li><?= esc($formError) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<?php if (! $isPegawaiView): ?>
<nav class="employee-tabs" aria-label="Bagian data pegawai">
    <?php foreach ($profilePages as $pageKey => $pageInfo): ?>
        <a href="<?= $pageUrl($pageKey) ?>" class="<?= $pageKey === $currentPage ? 'active' : '' ?>"><i class="fa-solid <?= esc($pageInfo['icon']) ?>"></i> <?= esc($pageInfo['title']) ?></a>
    <?php endforeach; ?>
</nav>
<?php endif; ?>
<?php if ($editable): ?>
<form id="employee-profile-form" method="post" action="<?= base_url('/employees/' . $employeeId . '/update') ?>">
    <?= csrf_field() ?>
<?php endif; ?>
<div class="main-card" style="padding:24px;">
    <div style="display:flex;align-items:center;gap:18px;padding-bottom:18px;border-bottom:1px solid var(--border);">
        <img src="https://ui-avatars.com/api/?name=<?= rawurlencode($employee['nama'] ?? 'Pegawai') ?>&background=4361ee&color=fff&size=120" alt="" style="height:76px;width:76px;border-radius:50%;">
        <div><h2 style="font-size:20px;margin:0 0 5px;"><?= esc($employee['nama'] ?? '') ?></h2><div style="color:var(--text-muted);">NIP <?= esc($employee['nip'] ?? '') ?></div></div>
    </div>

    <?php if ($needsLoginAccount && $showSection('A')): ?>
        <section class="employee-section">
            <h2>Akun Login</h2>
            <p style="color:var(--text-muted);font-size:13px;margin:0 0 12px;">Akun belum tersedia. Isi tanggal lahir pada bagian A dan username di bawah. Password awal memakai tanggal lahir berformat ddmmyyyy.</p>
            <div style="max-width:360px;">
                <label class="form-label" for="account_username">Username</label>
                <input class="form-control" id="account_username" name="account_username" value="<?= esc(old('account_username', '')) ?>" minlength="4" maxlength="80" autocomplete="username" required>
            </div>
        </section>
    <?php endif; ?>

    <?php foreach ($coreSections as $section): ?>
        <?php if (! $showSection($section['letter'])) { continue; } ?>
        <section class="employee-section">
            <h2><?= esc($section['letter'] . '. ' . $section['title']) ?></h2>
            <?= view('employee/summary_note', ['summary_letter' => $section['letter']]) ?>
            <div class="employee-fields">
                <?php foreach ($section['fields'] as $fieldDefinition): ?>
                    <?php [$key, $label, $type] = array_pad($fieldDefinition, 3, 'text'); ?>
                    <?php
                    $value = $key === 'unit_kerja' ? ($employee[$key] ?? $employee['unit_name'] ?? '') : ($employee[$key] ?? '');
                    $canEditField = $editable && ! (($role ?? '') === 'pegawai' && in_array($key, $protectedFields, true))
                        && \App\Libraries\EmployeeProfileData::summarySourceFor($key) === null;
                    ?>
                    <div class="employee-field"<?= $type === 'textarea' ? ' style="grid-column:1/-1;"' : '' ?>>
                        <label class="form-label" for="<?= esc($key) ?>"><?= esc($label) ?></label>
                        <?php if ($canEditField): ?>
                            <?php if ($key === 'jenis_kepegawaian'): ?>
                                <?= view('employee/employment_type_select', ['fieldName' => $key, 'value' => old($key, $value)]) ?>
                            <?php else: ?>
                                <?= employee_field_input($fieldDefinition, $key, old($key, $value), [
                                    'id' => $key,
                                    'required' => $key === 'nama' || ($key === 'tanggal_lahir' && $needsLoginAccount),
                                    'minlength' => $key === 'nama' ? 3 : null,
                                ]) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="employee-value"><?= employee_field_display($value, $fieldDefinition) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?= view('employee/section_documents', ['section_documents' => $sectionDocuments[$section['letter']] ?? [], 'document_mode' => $documentMode]) ?>
        </section>
    <?php endforeach; ?>

    <?php if ($professionalLicense !== null): ?>
    <section class="employee-section">
        <h2><?= esc($professionalLicense['title']) ?></h2>
        <p style="color:var(--text-muted);font-size:13px;margin:0 0 16px;">Isi nomor dan masa berlaku, lalu unggah dokumen <?= esc($professionalLicense['section']) ?> pada bagian dokumen pendukung.</p>
        <div class="employee-fields">
            <?php foreach ($professionalLicense['fields'] as $fieldDefinition): ?>
                <?php [$key, $label, $type] = array_pad($fieldDefinition, 3, 'text'); $value = $employee[$key] ?? ''; ?>
                <div class="employee-field">
                    <label class="form-label" for="<?= esc($key) ?>"><?= esc($label) ?></label>
                    <?php if ($editable): ?>
                        <?= employee_field_input($fieldDefinition, $key, old($key, $value), ['id' => $key]) ?>
                    <?php else: ?>
                        <div class="employee-value"><?= employee_field_display($value, $fieldDefinition) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?= view('employee/section_documents', ['section_documents' => $sectionDocuments[$professionalLicense['section']] ?? [], 'document_mode' => $documentMode]) ?>
    </section>
    <?php endif; ?>

    <?php if ($showSection('F')): ?>
    <section class="employee-section">
        <h2><?= esc($latestEducationSection['letter'] . '. ' . $latestEducationSection['title']) ?></h2>
        <?= view('employee/summary_note', ['summary_letter' => $latestEducationSection['letter']]) ?>
        <div class="employee-fields">
            <?php foreach ($latestEducationSection['fields'] as $fieldDefinition): ?>
                <?php [$key, $label, $type] = array_pad($fieldDefinition, 3, 'text'); $value = $employee[$key] ?? ''; ?>
                <div class="employee-field"><label class="form-label" for="<?= esc($key) ?>"><?= esc($label) ?></label>
                    <?php if ($editable && \App\Libraries\EmployeeProfileData::summarySourceFor($key) === null): ?>
                        <?= employee_field_input($fieldDefinition, $key, old($key, $value), ['id' => $key]) ?>
                    <?php else: ?>
                        <div class="employee-value"><?= employee_field_display($value, $fieldDefinition) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?= view('employee/section_documents', ['section_documents' => $sectionDocuments[$latestEducationSection['letter']] ?? [], 'document_mode' => $documentMode]) ?>
    </section>
    <?php endif; ?>

    <?php foreach ($historySections as $category => $section): ?>
        <?php if (in_array($category, ['kepangkatan', 'jabatan', 'tugas_tambahan'], true) && $showSection($section['letter'])): ?>
            <?= view('employee/history_repeater', [
                'section_key' => $category,
                'history_section' => $section,
                'history_records' => $historyRecords[$category] ?? [],
                'editable_history' => $editable,
                'job_titles_by_group' => $jobTitlesByGroup,
                'section_documents' => $sectionDocuments[$section['letter']] ?? [],
                'document_mode' => $documentMode,
            ]) ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($showSection('J')): ?>
    <?= view('employee/education_section', [
        'education_records' => $educationRecords,
        'editable_education' => $editable,
        'education_documents' => $checklist['by_education'] ?? [],
        'section_documents' => $sectionDocuments['J'] ?? [],
        'document_mode' => $documentMode,
    ]) ?>
    <?php endif; ?>

    <?php foreach ($historySections as $category => $section): ?>
        <?php if (! in_array($category, ['kepangkatan', 'jabatan', 'tugas_tambahan'], true) && $showSection($section['letter'])): ?>
            <?= view('employee/history_repeater', [
                'section_key' => $category,
                'history_section' => $section,
                'history_records' => $historyRecords[$category] ?? [],
                'editable_history' => $editable,
                'section_documents' => $sectionDocuments[$section['letter']] ?? [],
                'document_mode' => $documentMode,
            ]) ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($showSection('DOKUMEN')): ?>
    <?= view('employee/document_checklist_card', ['checklist' => $profile['checklist'] ?? null, 'document_mode' => $documentMode]) ?>
    <?php endif; ?>
</div>
<?php if ($editable && ! $showSection('DOKUMEN')): ?>
    <?= view('employee/save_bar', [
        'cancel_url' => $isPegawaiView ? base_url('/' . $currentPage) : base_url('/view-pegawai?id=' . $employeeId . '&bagian=' . $currentPage),
    ]) ?>
<?php endif; ?>
<?php if ($editable): ?></form><?php endif; ?>
<?php if ($editable): ?><?= view('employee/repeater_script', ['job_titles_by_group' => $jobTitlesByGroup]) ?><?php endif; ?>
<?= $this->endSection() ?>
