<?php
$fieldName = $fieldName ?? 'jenis_kepegawaian';
$fieldId = $fieldId ?? $fieldName;
$selectedValue = (string) ($value ?? '');
$employmentGroups = [
    'ASN' => ['CPNS', 'PNS', 'PPPK', 'PPPK Paruh Waktu'],
    'BLUD' => ['BLUD Tetap', 'BLUD Kontrak'],
    'Mitra' => ['Mitra Penuh Waktu', 'Mitra Paruh Waktu'],
];
$knownTypes = array_merge(...array_values($employmentGroups));
?>
<select class="form-control" id="<?= esc($fieldId) ?>" name="<?= esc($fieldName) ?>">
    <option value="" <?= $selectedValue === '' ? 'selected' : '' ?>>Pilih jenis kepegawaian</option>
    <?php if ($selectedValue !== '' && ! in_array($selectedValue, $knownTypes, true)): ?>
        <optgroup label="Data tersimpan">
            <option value="<?= esc($selectedValue) ?>" selected><?= esc($selectedValue) ?></option>
        </optgroup>
    <?php endif; ?>
    <?php foreach ($employmentGroups as $group => $types): ?>
        <optgroup label="<?= esc($group) ?>">
            <?php foreach ($types as $type): ?>
                <option value="<?= esc($type) ?>" <?= $selectedValue === $type ? 'selected' : '' ?>><?= esc($type) ?></option>
            <?php endforeach; ?>
        </optgroup>
    <?php endforeach; ?>
</select>
