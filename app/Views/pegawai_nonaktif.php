<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$categoryNames = array_values($categories);
$statusCounts = array_fill_keys($categoryNames, 0);
foreach ($inactiveEmployees as $inactiveEmployee) {
    if (isset($statusCounts[$inactiveEmployee['status_nonaktif']])) {
        $statusCounts[$inactiveEmployee['status_nonaktif']]++;
    }
}
$statusBadgeClass = static fn (string $status): string => match ($status) {
    'Pensiun' => 'is-pension',
    'Mutasi Keluar' => 'is-transfer',
    'Habis Kontrak' => 'is-contract',
    'Mengundurkan Diri' => 'is-resigned',
    default => 'is-other',
};
$countdown = static function (array $retirement): string {
    if ($retirement['past_due']) {
        return 'Lewat BUP';
    }
    $years = intdiv((int) $retirement['months_left'], 12);
    $months = (int) $retirement['months_left'] % 12;
    return trim(($years > 0 ? $years . ' tahun ' : '') . ($months > 0 ? $months . ' bulan' : ($years > 0 ? '' : 'Bulan ini')));
};
?>
<style>
    .inactive-intro { align-items:center; background:linear-gradient(120deg,var(--bg-surface),var(--primary-light)); border:1px solid var(--border); border-radius:14px; display:flex; gap:14px; margin-bottom:18px; padding:20px 22px; }
    .inactive-intro-icon { align-items:center; background:var(--primary); border-radius:12px; color:#fff; display:flex; flex:0 0 46px; font-size:20px; height:46px; justify-content:center; }
    .inactive-intro p { color:var(--text-muted); font-size:13px; line-height:1.5; margin:5px 0 0; }
    .inactive-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; margin-bottom:18px; }
    .inactive-summary-card { align-items:center; background:var(--bg-surface); border:1px solid var(--border); border-radius:12px; display:flex; gap:11px; min-width:0; padding:14px; }
    .inactive-summary-icon { align-items:center; background:var(--bg-body); border-radius:10px; color:var(--primary); display:flex; flex:0 0 38px; height:38px; justify-content:center; }
    .inactive-summary-label { color:var(--text-muted); font-size:11px; line-height:1.3; }
    .inactive-summary-value { color:var(--text-main); font-size:20px; font-weight:750; line-height:1.2; margin-top:3px; }
    .inactive-panel { border:1px solid var(--border); margin-bottom:18px; padding:20px; }
    .inactive-panel-heading { align-items:center; display:flex; gap:11px; margin-bottom:17px; }
    .inactive-panel-heading-icon { align-items:center; background:var(--primary-light); border-radius:10px; color:var(--primary); display:flex; height:38px; justify-content:center; width:38px; }
    .inactive-panel-heading h2 { color:var(--text-main); font-size:16px; margin:0; }
    .inactive-panel-heading p { color:var(--text-muted); font-size:12px; margin:4px 0 0; }
    .inactive-options { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; margin-bottom:17px; }
    .inactive-option { align-items:center; background:var(--bg-body); border:1px solid var(--border); border-radius:10px; cursor:pointer; display:flex; gap:9px; min-height:47px; padding:10px 12px; transition:border-color .15s,background .15s,box-shadow .15s; }
    .inactive-option:has(input:checked) { background:var(--primary-light); border-color:var(--primary); box-shadow:0 0 0 2px color-mix(in srgb,var(--primary) 12%,transparent); }
    .inactive-option input { accent-color:var(--primary); height:16px; margin:0; width:16px; }
    .inactive-option span { color:var(--text-main); font-size:12px; font-weight:650; }
    .inactive-form-fields { align-items:end; display:grid; grid-template-columns:minmax(220px,1.2fr) minmax(220px,1fr) auto; gap:12px; }
    .inactive-form-fields .btn { justify-content:center; min-height:43px; }
    .inactive-employee-picker { align-items:center; display:flex; flex-wrap:wrap; gap:9px; }
    .inactive-selected-employee { color:var(--text-muted); font-size:12px; }
    .employee-picker-modal { align-items:center; background:rgba(15,23,42,.58); display:none; inset:0; justify-content:center; padding:18px; position:fixed; z-index:1500; }
    .employee-picker-modal.is-open { display:flex; }
    .employee-picker-dialog { background:var(--bg-surface); border:1px solid var(--border); border-radius:15px; box-shadow:0 24px 70px rgba(15,23,42,.3); display:flex; flex-direction:column; max-height:min(82vh,760px); max-width:650px; overflow:hidden; width:100%; }
    .employee-picker-head { align-items:center; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; padding:17px 20px; }
    .employee-picker-head h3 { color:var(--text-main); font-size:16px; margin:0; }
    .employee-picker-close { background:transparent; border:0; color:var(--text-muted); cursor:pointer; font-size:18px; padding:5px; }
    .employee-picker-search { border-bottom:1px solid var(--border); padding:14px 20px; }
    .employee-picker-search-wrap { position:relative; }
    .employee-picker-search-wrap i { color:var(--text-muted); left:12px; position:absolute; top:13px; }
    .employee-picker-search-wrap input { padding-left:36px; }
    .employee-picker-list { overflow:auto; padding:8px 12px; }
    .employee-picker-row { align-items:center; border-bottom:1px solid var(--border); display:flex; gap:12px; justify-content:space-between; padding:11px 8px; }
    .employee-picker-row:last-child { border-bottom:0; }
    .employee-picker-row[hidden] { display:none; }
    .employee-picker-name { color:var(--text-main); font-size:13px; font-weight:700; }
    .employee-picker-meta { color:var(--text-muted); font-size:11px; margin-top:4px; }
    .employee-picker-empty { color:var(--text-muted); display:none; padding:28px 12px; text-align:center; }
    .employee-picker-empty.is-visible { display:block; }
    .inactive-letter-hint { color:var(--text-muted); display:block; font-size:11px; margin-top:5px; }
    .inactive-table-wrap { border:1px solid var(--border); overflow:auto; }
    .inactive-table-title { align-items:center; display:flex; flex-wrap:wrap; gap:10px; justify-content:space-between; padding:17px 20px; }
    .inactive-table-title h2 { color:var(--text-main); font-size:16px; margin:0; }
    .inactive-total { background:var(--primary-light); border-radius:999px; color:var(--primary); font-size:12px; font-weight:700; padding:5px 10px; }
    .inactive-table { min-width:760px; }
    .inactive-table td { vertical-align:middle; }
    .inactive-person { align-items:center; display:flex; gap:10px; }
    .inactive-person-icon { align-items:center; background:var(--bg-body); border-radius:50%; color:var(--text-muted); display:flex; flex:0 0 36px; height:36px; justify-content:center; }
    .inactive-person-name { color:var(--text-main); font-weight:700; }
    .inactive-person-nip { color:var(--text-muted); font-size:11px; margin-top:3px; }
    .inactive-badge { border-radius:999px; display:inline-flex; font-size:11px; font-weight:700; padding:6px 10px; white-space:nowrap; }
    .inactive-badge.is-pension { background:#ede9fe; color:#6d28d9; }
    .inactive-badge.is-transfer { background:#dbeafe; color:#1d4ed8; }
    .inactive-badge.is-contract { background:#fef3c7; color:#a16207; }
    .inactive-badge.is-resigned { background:#fce7f3; color:#be185d; }
    .inactive-badge.is-other { background:var(--bg-body); color:var(--text-muted); }
    .inactive-empty { color:var(--text-muted); padding:42px 20px !important; text-align:center !important; white-space:normal !important; }
    .inactive-empty-icon { color:var(--success); display:block; font-size:25px; margin-bottom:9px; }
    .inactive-alert { align-items:flex-start; border-radius:10px; display:flex; gap:9px; margin-bottom:14px; padding:12px 15px; }
    .inactive-alert.success { background:#ecfdf5; border:1px solid #a7f3d0; color:#047857; }
    .inactive-alert.error { background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; }
    .pension-planning { background:var(--bg-surface); border:1px solid var(--border); border-radius:12px; margin:18px 0; padding:18px; }
    .pension-planning[hidden] { display:none; }
    .pension-planning-heading { align-items:flex-start; display:flex; gap:10px; justify-content:space-between; margin-bottom:14px; }
    .pension-planning-heading h3 { color:var(--text-main); font-size:15px; margin:0; }
    .pension-planning-heading p { color:var(--text-muted); font-size:12px; line-height:1.5; margin:4px 0 0; }
    .bup-settings { background:var(--bg-body); border:1px solid var(--border); border-radius:10px; margin-bottom:16px; padding:14px; }
    .bup-settings[hidden] { display:none; }
    .bup-settings h4 { color:var(--text-main); font-size:13px; margin:0 0 10px; }
    .bup-settings-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; }
    .bup-setting-item { min-width:0; }
    .bup-setting-item label { color:var(--text-muted); display:block; font-size:11px; margin-bottom:5px; }
    .bup-setting-item .form-control { padding:8px 10px; }
    .bup-settings-footer { align-items:center; display:flex; flex-wrap:wrap; gap:10px; justify-content:space-between; margin-top:12px; }
    .bup-settings-note { color:var(--text-muted); font-size:11px; }
    .pension-candidate-table { min-width:1000px; }
    .countdown-badge { background:#fef3c7; border-radius:999px; color:#a16207; display:inline-block; font-size:11px; font-weight:700; padding:6px 10px; white-space:nowrap; }
    .countdown-badge.overdue { background:#fee2e2; color:#b91c1c; }
    .pension-empty { color:var(--text-muted); padding:32px 15px !important; text-align:center !important; white-space:normal !important; }
    @media(max-width:1000px) { .inactive-summary { grid-template-columns:repeat(2,minmax(0,1fr)); } .inactive-options { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:760px) { .inactive-form-fields { grid-template-columns:1fr; } .inactive-form-fields .btn { width:100%; } .inactive-intro { align-items:flex-start; padding:17px; } }
    @media(max-width:700px) { .bup-settings-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:500px) { .inactive-summary { gap:8px; } .inactive-summary-card { gap:8px; padding:11px; } .inactive-summary-icon { flex-basis:32px; height:32px; width:32px; } .inactive-summary-value { font-size:18px; } .inactive-options { grid-template-columns:1fr; } .inactive-panel { padding:15px; } .inactive-table-title { padding:14px 15px; } .bup-settings-grid { grid-template-columns:1fr 1fr; } }
</style>

<div class="inactive-intro">
    <div class="inactive-intro-icon"><i class="fa-solid fa-user-slash"></i></div>
    <div><h1 class="page-title" style="margin:0;">Data Pegawai Nonaktif</h1><p>Semua pegawai nonaktif tercatat dalam satu daftar. Jenis status dan surat pendukung terlihat pada setiap baris.</p></div>
</div>
<?php if ($error = session()->getFlashdata('error')): ?><div class="inactive-alert error"><i class="fa-solid fa-circle-exclamation"></i><span><?= esc($error) ?></span></div><?php endif; ?>
<?php if ($success = session()->getFlashdata('success')): ?><div class="inactive-alert success"><i class="fa-solid fa-circle-check"></i><span><?= esc($success) ?></span></div><?php endif; ?>

<div class="inactive-summary">
    <?php foreach ($categoryNames as $categoryName): ?>
        <?php $icon = match ($categoryName) { 'Pensiun' => 'fa-user-clock', 'Mutasi Keluar' => 'fa-right-left', 'Habis Kontrak' => 'fa-file-circle-xmark', default => 'fa-person-walking-arrow-right' }; ?>
        <div class="inactive-summary-card"><div class="inactive-summary-icon"><i class="fa-solid <?= $icon ?>"></i></div><div><div class="inactive-summary-label"><?= esc($categoryName) ?></div><div class="inactive-summary-value"><?= (int) $statusCounts[$categoryName] ?></div></div></div>
    <?php endforeach; ?>
</div>

<?php if ($can_update): ?>
<section class="main-card inactive-panel">
    <div class="inactive-panel-heading"><div class="inactive-panel-heading-icon"><i class="fa-solid fa-user-minus"></i></div><div><h2>Nonaktifkan pegawai</h2><p>Pilih status dan pegawai, lalu unggah surat resmi PDF untuk semua status.</p></div></div>
    <form method="post" action="<?= base_url('/pegawai-nonaktif') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="inactive-options">
            <?php foreach ($categoryNames as $index => $categoryName): ?><label class="inactive-option"><input type="radio" name="kategori" value="<?= esc($categoryName) ?>" <?= $index === 0 ? 'checked' : '' ?> required><span><?= esc($categoryName) ?></span></label><?php endforeach; ?>
        </div>
        <div class="inactive-form-fields">
            <div><label class="form-label">Pegawai</label><div class="inactive-employee-picker"><input type="hidden" name="employee_id" id="employee_id" value=""><button type="button" class="btn btn-outline" data-open-employee-picker><i class="fa-solid fa-magnifying-glass"></i> Pilih pegawai</button><span class="inactive-selected-employee" data-selected-employee>Belum ada pegawai dipilih</span></div></div>
            <div id="inactive-letter-field"><label class="form-label" for="surat">Surat resmi</label><input class="form-control" type="file" id="surat" name="surat" accept="application/pdf,.pdf" required><small class="inactive-letter-hint">PDF maksimal 10 MB. Wajib untuk semua status, termasuk Pensiun.</small></div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-user-minus"></i> Simpan status</button>
        </div>
    </form>
</section>
<div class="employee-picker-modal" id="employee-picker-modal" aria-hidden="true">
    <div class="employee-picker-dialog" role="dialog" aria-modal="true" aria-labelledby="employee-picker-title">
        <div class="employee-picker-head"><h3 id="employee-picker-title">Pilih pegawai</h3><button type="button" class="employee-picker-close" data-close-employee-picker aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button></div>
        <div class="employee-picker-search"><div class="employee-picker-search-wrap"><i class="fa-solid fa-magnifying-glass"></i><input type="search" class="form-control" id="employee-picker-search" placeholder="Cari nama atau NIP" autocomplete="off"></div></div>
        <div class="employee-picker-list" id="employee-picker-list">
            <?php foreach ($eligibleEmployees as $employee): ?>
                <div class="employee-picker-row" data-employee-row data-employee-id="<?= (int) $employee['id'] ?>" data-employee-name="<?= esc($employee['nama']) ?>" data-employee-nip="<?= esc($employee['nip']) ?>" data-search="<?= esc(mb_strtolower($employee['nama'] . ' ' . $employee['nip'])) ?>">
                    <div><div class="employee-picker-name"><?= esc($employee['nama']) ?></div><div class="employee-picker-meta">NIP <?= esc($employee['nip']) ?> · <?= esc($employee['unit_name'] ?? 'Tanpa unit') ?></div></div>
                    <button type="button" class="btn btn-primary btn-sm" data-choose-employee>Pilih</button>
                </div>
            <?php endforeach; ?>
            <div class="employee-picker-empty <?= $eligibleEmployees === [] ? 'is-visible' : '' ?>" id="employee-picker-empty">Tidak ada pegawai yang cocok.</div>
        </div>
    </div>
</div>
<?php endif; ?>

<section class="pension-planning" id="pension-planning" <?= $can_update ? '' : 'hidden' ?>>
    <div class="pension-planning-heading"><div><h3><i class="fa-solid fa-hourglass-half" style="color:var(--primary);margin-right:5px;"></i> Pegawai yang akan pensiun</h3><p>Daftar pegawai aktif dengan perkiraan TMT pensiun dalam 24 bulan atau sudah melewati BUP.</p></div><div style="align-items:center;display:flex;flex-wrap:wrap;gap:8px;"><span class="inactive-total"><?= count($pensionCandidates) ?> pegawai</span><?php if ($can_manage_bup): ?><button type="button" class="btn btn-outline btn-sm" id="toggle-bup-settings" aria-expanded="false" aria-controls="bup-settings-panel"><i class="fa-solid fa-gear"></i> Pengaturan BUP</button><?php endif; ?></div></div>
    <?php if ($can_manage_bup): ?>
    <form class="bup-settings" id="bup-settings-panel" hidden method="post" action="<?= base_url('/pegawai-nonaktif/bup') ?>">
        <?= csrf_field() ?>
        <h4>Pengaturan Batas Usia Pensiun (BUP)</h4>
        <div class="bup-settings-grid">
            <?php foreach ($bupSettings as $key => $setting): ?>
                <div class="bup-setting-item"><label for="bup-<?= esc($key) ?>"><?= esc($setting['level_name']) ?></label><input class="form-control" type="number" min="50" max="70" id="bup-<?= esc($key) ?>" name="bup[<?= esc($key) ?>]" value="<?= (int) $setting['age'] ?>" required></div>
            <?php endforeach; ?>
        </div>
        <div class="bup-settings-footer"><span class="bup-settings-note">Nilai 50–70 tahun. Perubahan berlaku untuk hitung mundur berikutnya.</span><button class="btn btn-primary btn-sm" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan BUP</button></div>
    </form>
    <?php endif; ?>
    <div class="inactive-table-wrap" style="border-radius:9px;">
        <table class="data-table pension-candidate-table">
            <thead><tr><th>Nama / NIP</th><th>Unit</th><th>Jabatan / Jenjang</th><th>Tanggal lahir</th><th>Usia sekarang</th><th>BUP</th><th>TMT pensiun</th><th>Hitung mundur</th><?php if ($can_update): ?><th>Aksi</th><?php endif; ?></tr></thead>
            <tbody>
                <?php if ($pensionCandidates === []): ?><tr><td colspan="<?= $can_update ? 9 : 8 ?>" class="pension-empty"><i class="fa-solid fa-circle-check" style="color:var(--success);margin-right:5px;"></i> Tidak ada pegawai aktif yang akan pensiun dalam 24 bulan.</td></tr><?php endif; ?>
                <?php foreach ($pensionCandidates as $employee): ?>
                    <?php $retirement = $employee['pensiun']; ?>
                    <tr>
                        <td><strong><?= esc($employee['nama']) ?></strong><div style="font-size:11px;color:var(--text-muted);margin-top:3px;">NIP <?= esc($employee['nip']) ?></div></td>
                        <td><?= esc($employee['unit_name'] ?? '—') ?></td>
                        <td><?= esc(trim(($employee['jabatan_nama'] ?? '') . ' ' . ($employee['jenjang_jabatan'] ?? '')) ?: '—') ?></td>
                        <td><?= esc(date('d-m-Y', strtotime($employee['tanggal_lahir']))) ?></td>
                        <td><?= (int) $retirement['age_now'] ?> tahun</td>
                        <td><strong><?= (int) $retirement['bup'] ?> tahun</strong></td>
                        <td><?= esc(date('d-m-Y', strtotime($retirement['tmt']))) ?></td>
                        <td><span class="countdown-badge <?= $retirement['past_due'] ? 'overdue' : '' ?>"><?= esc($countdown($retirement)) ?></span></td>
                        <?php if ($can_update): ?><td><button type="button" class="btn btn-outline btn-sm" data-select-pension-employee="<?= (int) $employee['id'] ?>" data-employee-name="<?= esc($employee['nama']) ?>" data-employee-nip="<?= esc($employee['nip']) ?>"><i class="fa-solid fa-arrow-up"></i> Pilih</button></td><?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    (function () {
        const radios = document.querySelectorAll('input[name="kategori"]');
        const pensionPanel = document.getElementById('pension-planning');
        if (radios.length === 0) return;
        const employeeIdInput = document.getElementById('employee_id');
        const selectedEmployeeLabel = document.querySelector('[data-selected-employee]');
        const employeePicker = document.getElementById('employee-picker-modal');
        const employeePickerSearch = document.getElementById('employee-picker-search');
        const employeePickerRows = Array.from(document.querySelectorAll('[data-employee-row]'));
        const employeePickerEmpty = document.getElementById('employee-picker-empty');
        const bupSettingsToggle = document.getElementById('toggle-bup-settings');
        const bupSettingsPanel = document.getElementById('bup-settings-panel');
        if (bupSettingsToggle && bupSettingsPanel) {
            bupSettingsToggle.addEventListener('click', function () {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', String(!isExpanded));
                bupSettingsPanel.hidden = isExpanded;
            });
        }
        function selectEmployee(id, name, nip) {
            employeeIdInput.value = id;
            selectedEmployeeLabel.textContent = name + ' · NIP ' + nip;
            employeePicker.classList.remove('is-open');
            employeePicker.setAttribute('aria-hidden', 'true');
        }
        function closeEmployeePicker() {
            employeePicker.classList.remove('is-open');
            employeePicker.setAttribute('aria-hidden', 'true');
        }
        document.querySelector('[data-open-employee-picker]').addEventListener('click', function () {
            employeePicker.classList.add('is-open');
            employeePicker.setAttribute('aria-hidden', 'false');
            employeePickerSearch.value = '';
            employeePickerRows.forEach((row) => { row.hidden = false; });
            employeePickerEmpty.classList.toggle('is-visible', employeePickerRows.length === 0);
            employeePickerSearch.focus();
        });
        document.querySelectorAll('[data-close-employee-picker]').forEach((button) => button.addEventListener('click', closeEmployeePicker));
        employeePicker.addEventListener('click', (event) => { if (event.target === employeePicker) closeEmployeePicker(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeEmployeePicker(); });
        employeePickerSearch.addEventListener('input', function () {
            const query = this.value.trim().toLocaleLowerCase();
            let visibleCount = 0;
            employeePickerRows.forEach((row) => {
                row.hidden = !row.dataset.search.includes(query);
                if (!row.hidden) visibleCount++;
            });
            employeePickerEmpty.classList.toggle('is-visible', visibleCount === 0);
        });
        employeePickerRows.forEach((row) => row.querySelector('[data-choose-employee]').addEventListener('click', () => {
            selectEmployee(row.dataset.employeeId, row.dataset.employeeName, row.dataset.employeeNip);
        }));
        document.querySelector('.inactive-panel form').addEventListener('submit', function (event) {
            if (employeeIdInput.value === '') {
                event.preventDefault();
                employeePicker.classList.add('is-open');
                employeePicker.setAttribute('aria-hidden', 'false');
                employeePickerSearch.focus();
            }
        });
        function updateFormMode() {
            const pension = document.querySelector('input[name="kategori"]:checked')?.value === 'Pensiun';
            pensionPanel.hidden = !pension;
        }
        radios.forEach((radio) => radio.addEventListener('change', updateFormMode));
        document.querySelectorAll('[data-select-pension-employee]').forEach((button) => button.addEventListener('click', function () {
            document.querySelector('input[name="kategori"][value="Pensiun"]').checked = true;
            updateFormMode();
            selectEmployee(button.dataset.selectPensionEmployee, button.dataset.employeeName, button.dataset.employeeNip);
            document.querySelector('.inactive-panel').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }));
        updateFormMode();
    }());
</script>

<section class="main-card inactive-table-wrap" style="padding:0;">
    <div class="inactive-table-title"><h2>Daftar pegawai nonaktif</h2><span class="inactive-total"><?= count($inactiveEmployees) ?> pegawai</span></div>
    <table class="data-table inactive-table">
        <thead><tr><th>Nama / NIP</th><th>Unit</th><th>Status</th><th>Surat</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if ($inactiveEmployees === []): ?><tr><td colspan="5" class="inactive-empty"><i class="fa-solid fa-circle-check inactive-empty-icon"></i><strong>Belum ada pegawai nonaktif</strong><div style="font-size:12px;margin-top:5px;">Pegawai yang dinonaktifkan akan tercatat di daftar ini.</div></td></tr><?php endif; ?>
        <?php foreach ($inactiveEmployees as $employee): ?>
            <tr>
                <td><div class="inactive-person"><span class="inactive-person-icon"><i class="fa-solid fa-user"></i></span><div><div class="inactive-person-name"><?= esc($employee['nama']) ?></div><div class="inactive-person-nip">NIP <?= esc($employee['nip']) ?></div></div></div></td>
                <td><?= esc($employee['unit_name'] ?? '—') ?></td>
                <td><span class="inactive-badge <?= $statusBadgeClass($employee['status_nonaktif']) ?>"><?= esc($employee['status_nonaktif']) ?></span></td>
                <td><?php if (! empty($employee['surat_id'])): ?><a class="btn btn-outline btn-sm" href="<?= base_url('/documents/' . (int) $employee['surat_id']) ?>" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf"></i> Lihat surat</a><?php elseif (in_array($employee['status_nonaktif'], ['Pensiun', 'Mutasi Keluar', 'Habis Kontrak', 'Mengundurkan Diri'], true)): ?><span style="color:var(--danger);font-size:12px;">Surat belum tercatat</span><?php else: ?><span style="color:var(--text-muted);">—</span><?php endif; ?></td>
                <td><a class="btn btn-outline btn-sm" href="<?= base_url('/view-pegawai?id=' . (int) $employee['id']) ?>" title="Lihat data pegawai"><i class="fa-solid fa-eye"></i></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?= $this->endSection() ?>
