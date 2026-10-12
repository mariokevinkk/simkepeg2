<?php
// Save bar that stays at the bottom of the screen while a form is being edited.
$saveLabel = $save_label ?? 'Simpan perubahan';
$cancelUrl = $cancel_url ?? '';
$formId = $form_id ?? '';
?>
<?php if (! defined('EMPLOYEE_SAVE_BAR_STYLES')): define('EMPLOYEE_SAVE_BAR_STYLES', true); ?>
<style>
    .employee-save-bar { align-items:center; background:var(--bg-surface); border:1px solid var(--border); border-radius:12px; bottom:16px; box-shadow:0 10px 30px rgba(15,23,42,.14); display:flex; flex-wrap:wrap; gap:12px; justify-content:space-between; margin:20px 0 0; padding:12px 16px; position:sticky; z-index:50; }
    .employee-save-bar-status { color:var(--text-muted); font-size:13px; }
    .employee-save-bar.is-dirty .employee-save-bar-status { color:#a16207; font-weight:600; }
    .employee-save-bar-actions { display:flex; gap:10px; margin-left:auto; }
    .employee-save-bar .btn { min-height:42px; padding-left:20px; padding-right:20px; text-decoration:none; }
    @media(max-width:600px) { .employee-save-bar-actions { width:100%; } .employee-save-bar-actions .btn { flex:1; justify-content:center; } }
</style>
<?php endif; ?>
<div class="employee-save-bar" data-save-bar<?= $formId !== '' ? ' data-form="' . esc($formId) . '"' : '' ?>>
    <span class="employee-save-bar-status" data-save-status><i class="fa-solid fa-circle-info"></i> Simpan setelah selesai mengubah data di halaman ini.</span>
    <div class="employee-save-bar-actions">
        <?php if ($cancelUrl !== ''): ?><a class="btn btn-outline" href="<?= esc($cancelUrl) ?>">Batal</a><?php endif; ?>
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> <?= esc($saveLabel) ?></button>
    </div>
</div>
<script>
(function () {
    const bar = document.currentScript.previousElementSibling;
    const form = bar.closest('form');
    if (!form) return;
    let dirty = false;
    const markDirty = function () {
        if (dirty) return;
        dirty = true;
        bar.classList.add('is-dirty');
        bar.querySelector('[data-save-status]').innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Ada perubahan yang belum disimpan.';
    };
    const onEdit = function (event) {
        // Document files and their due dates are saved on their own; they do not need the save button.
        if (event.target.type === 'file' || event.target.matches('[data-inline-document-expiry]')) return;
        markDirty();
    };
    form.addEventListener('input', onEdit);
    form.addEventListener('change', onEdit);
    form.addEventListener('click', function (event) {
        if (event.target.closest('[data-add-history], [data-remove-history], #add-education-row, [data-remove-education]')) markDirty();
    });
    form.addEventListener('submit', function () { dirty = false; });
    window.addEventListener('beforeunload', function (event) {
        if (!dirty) return;
        event.preventDefault();
        event.returnValue = '';
    });
})();
</script>
