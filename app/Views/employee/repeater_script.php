<?php // Add/remove behaviour for repeatable sections (G-X) and education rows (J). ?>
<script>
(function () {
    const jobTitlesByGroup = <?= json_encode($job_titles_by_group ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?>;
    const syncJobTitleSelect = function (select, discardInvalid = false) {
        const row = select.closest('.history-entry');
        const jobGroupSelect = row?.querySelector('[data-job-group-select]');
        const group = jobGroupSelect?.value.trim() || '';
        const titles = jobTitlesByGroup[group] || [];
        const previous = select.value;
        const original = select.dataset.originalJobTitle || '';
        const retainOldValue = original !== '';
        const selected = titles.includes(previous) || (!discardInvalid && retainOldValue && previous === original) ? previous : '';

        select.replaceChildren(new Option(group ? 'Pilih nama jabatan' : 'Pilih jenis ketenagaan dahulu', ''));
        titles.forEach(function (title) { select.add(new Option(title, title)); });
        if (!discardInvalid && retainOldValue && !titles.includes(original)) {
            select.add(new Option(original + ' (data lama)', original));
        }
        select.value = selected;
        select.disabled = group === '' && !retainOldValue;
    };
    const syncAllJobTitles = function () {
        document.querySelectorAll('[data-job-title-select]').forEach(syncJobTitleSelect);
    };
    document.addEventListener('change', function (event) {
        if (!event.target.matches('[data-job-group-select]')) return;
        const row = event.target.closest('.history-entry');
        row?.querySelectorAll('[data-job-title-select]').forEach(function (select) { syncJobTitleSelect(select, true); });
    });
    syncAllJobTitles();

    document.querySelectorAll('[data-history-section]').forEach(function (section) {
        const addButton = section.querySelector('[data-add-history]');
        const template = section.querySelector('template[data-history-template]');
        const entries = section.querySelector('.history-entries');
        if (!addButton || !template || !entries) return;
        let nextIndex = Number.parseInt(section.dataset.nextIndex || '0', 10);

        addButton.addEventListener('click', function () {
            const entry = template.content.firstElementChild.cloneNode(true);
            entry.querySelectorAll('[name]').forEach(function (field) {
                field.name = field.name.replace('__INDEX__', String(nextIndex));
            });
            entries.querySelector('[data-empty-row]')?.remove();
            entries.appendChild(entry);
            entry.querySelectorAll('[data-job-title-select]').forEach(syncJobTitleSelect);
            entry.querySelector('input:not([type="hidden"]), select, textarea')?.focus();
            nextIndex += 1;
            section.dataset.nextIndex = String(nextIndex);
        });

        entries.addEventListener('click', function (event) {
            const removeButton = event.target.closest('[data-remove-history]');
            if (removeButton) removeButton.closest('.history-entry')?.remove();
        });
    });

    const educationRows = document.getElementById('education-rows');
    const addEducation = document.getElementById('add-education-row');
    const educationTemplate = document.getElementById('education-template');
    if (!educationRows || !addEducation || !educationTemplate) return;
    let nextEducationIndex = Number.parseInt(educationRows.dataset.nextIndex || '0', 10);
    addEducation.addEventListener('click', function () {
        const entry = educationTemplate.content.firstElementChild.cloneNode(true);
        entry.querySelectorAll('[name]').forEach(function (field) {
            field.name = field.name.replace('__INDEX__', String(nextEducationIndex));
        });
        educationRows.querySelector('[data-empty-row]')?.remove();
        educationRows.appendChild(entry);
        entry.querySelector('input:not([type="hidden"]), select, textarea')?.focus();
        nextEducationIndex += 1;
        educationRows.dataset.nextIndex = String(nextEducationIndex);
    });
    educationRows.addEventListener('click', function (event) {
        const removeButton = event.target.closest('[data-remove-education]');
        if (removeButton) removeButton.closest('.education-entry')?.remove();
    });
})();
</script>
