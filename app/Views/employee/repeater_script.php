<?php // Add/remove behaviour for repeatable sections (G-X) and education rows (J). ?>
<script>
(function () {
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
