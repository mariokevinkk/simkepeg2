<?php
$attachment = $attachment ?? [];
$attachmentId = $attachment['id'] ?? uniqid('attachment-', false);
$attachmentTitle = $attachment['title'] ?? 'Dokumen pendukung';
$attachmentDescription = $attachment['description'] ?? 'Berkas yang mendukung isian ini.';
$attachmentFile = $attachment['file'] ?? '';
$attachmentUrl = $attachment['url'] ?? '';
$attachmentUploadUrl = $attachment['upload_url'] ?? '';
$attachmentRequired = $attachment['required'] ?? false;
$verificationStatus = $attachment['verification_status'] ?? 'missing';
$verificationLabel = match ($verificationStatus) {
    'verified' => 'Sudah diverifikasi',
    'pending' => 'Menunggu verifikasi',
    'rejected' => 'Perlu perbaikan',
    default => '',
};
$attachmentMode = $attachment_mode ?? 'detail';
$attachmentEditable = $attachmentMode === 'edit';
$attachmentHasExpiry = ! empty($attachment['has_expiry']);
$attachmentExpiresAt = (string) ($attachment['expires_at'] ?? '');
if (($attachment['status'] ?? '') === 'expired') {
    $verificationStatus = 'rejected';
    $verificationLabel = 'Kedaluwarsa - unggah dokumen terbaru';
}

if (! defined('INLINE_DOCUMENT_COMPONENT_ASSETS')) {
    define('INLINE_DOCUMENT_COMPONENT_ASSETS', true);
?>
<style>
    .inline-document-card {
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px dashed var(--border);
        border-radius: 10px;
        background: var(--bg-body);
        box-sizing: border-box;
        margin-top: 12px;
        padding: 12px;
    }
    .inline-document-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #fee2e2;
        color: #dc2626;
        flex: 0 0 auto;
    }
    .inline-document-main {
        min-width: 0;
        flex: 1;
    }
    .inline-document-title {
        color: var(--text-main);
        display: block;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.35;
    }
    .inline-document-description {
        color: var(--text-muted);
        display: block;
        font-size: 11px;
        line-height: 1.45;
        margin-top: 2px;
    }
    .inline-document-file {
        color: var(--text-muted);
        display: block;
        font-size: 11px;
        line-height: 1.4;
        margin-top: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .inline-document-file.has-file {
        color: var(--text-main);
        font-weight: 600;
    }
    .inline-document-state {
        color: var(--text-muted);
        display: block;
        font-size: 10px;
        line-height: 1.35;
        margin-top: 3px;
    }
    .inline-document-state.is-saved {
        color: #047857;
        font-weight: 700;
    }
    .inline-document-state.is-pending {
        color: #a16207;
        font-weight: 700;
    }
    .inline-document-state.is-error {
        color: #b91c1c;
        font-weight: 700;
    }
    .inline-document-actions {
        align-items: center;
        display: flex;
        flex: 0 0 auto;
        gap: 7px;
    }
    .inline-document-badge {
        background: #fef3c7;
        border-radius: 999px;
        color: #a16207;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 7px;
        white-space: nowrap;
    }
    .inline-document-upload,
    .inline-document-preview {
        align-items: center;
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 7px;
        color: var(--primary);
        cursor: pointer;
        display: inline-flex;
        font-size: 11px;
        font-weight: 700;
        gap: 5px;
        justify-content: center;
        min-height: 31px;
        padding: 6px 9px;
        white-space: nowrap;
    }
    .inline-document-upload:hover,
    .inline-document-preview:hover:not(:disabled) {
        background: var(--primary-light);
        border-color: var(--primary);
    }
    .inline-document-preview:disabled {
        color: var(--text-muted);
        cursor: not-allowed;
        opacity: 0.65;
    }
    .inline-document-upload input {
        display: none;
    }
    .inline-document-expiry {
        align-items: center;
        color: var(--text-muted);
        display: flex;
        flex-wrap: wrap;
        font-size: 11px;
        gap: 6px;
        margin-top: 6px;
    }
    .inline-document-expiry input {
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 11px;
        padding: 4px 6px;
    }
    .inline-document-empty {
        color: var(--text-muted);
        font-size: 11px;
        font-style: italic;
        white-space: nowrap;
    }
    .inline-document-modal {
        align-items: center;
        background: rgba(15, 23, 42, 0.62);
        display: none;
        inset: 0;
        justify-content: center;
        padding: 20px;
        position: fixed;
        z-index: 1200;
    }
    .inline-document-modal-dialog {
        background: var(--bg-surface);
        border-radius: 14px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.35);
        display: flex;
        flex-direction: column;
        height: min(86vh, 760px);
        max-width: 980px;
        overflow: hidden;
        width: 100%;
    }
    .inline-document-modal-header {
        align-items: center;
        border-bottom: 1px solid var(--border);
        display: flex;
        gap: 16px;
        justify-content: space-between;
        padding: 15px 18px;
    }
    .inline-document-modal-title {
        color: var(--text-main);
        font-size: 14px;
        font-weight: 700;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .inline-document-modal-close {
        background: transparent;
        border: 0;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 18px;
    }
    .inline-document-modal-frame {
        border: 0;
        flex: 1;
        min-height: 0;
        width: 100%;
    }
    @media (max-width: 640px) {
        .inline-document-card {
            align-items: flex-start;
            flex-wrap: wrap;
        }
        .inline-document-actions {
            margin-left: 46px;
        }
    }
</style>
<script>
    function ensureInlineDocumentModal() {
        let modal = document.getElementById('inline-document-modal');
        if (modal) {
            return modal;
        }

        document.body.insertAdjacentHTML('beforeend', [
            '<div class="inline-document-modal" id="inline-document-modal" aria-hidden="true">',
            '  <div class="inline-document-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="inline-document-modal-title">',
            '    <div class="inline-document-modal-header">',
            '      <span class="inline-document-modal-title" id="inline-document-modal-title"></span>',
            '      <button type="button" class="inline-document-modal-close" onclick="closeInlineDocumentPreview()" aria-label="Tutup pratinjau"><i class="fa-solid fa-xmark"></i></button>',
            '    </div>',
            '    <iframe class="inline-document-modal-frame" id="inline-document-modal-frame" title="Pratinjau dokumen"></iframe>',
            '  </div>',
            '</div>'
        ].join(''));

        return document.getElementById('inline-document-modal');
    }

    function previewInlineDocument(attachmentId) {
        const attachment = document.getElementById('inline-document-' + attachmentId);
        if (!attachment || !attachment.dataset.previewUrl) {
            return;
        }

        const modal = ensureInlineDocumentModal();
        document.getElementById('inline-document-modal-title').textContent = attachment.dataset.previewName || 'Pratinjau dokumen';
        document.getElementById('inline-document-modal-frame').src = attachment.dataset.previewUrl;
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeInlineDocumentPreview() {
        const modal = document.getElementById('inline-document-modal');
        if (!modal) {
            return;
        }

        document.getElementById('inline-document-modal-frame').src = '';
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }

    function postInlineDocument(uploadUrl, formData) {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta && csrfMeta.content && csrfMeta.dataset.hash) {
            formData.append(csrfMeta.content, csrfMeta.dataset.hash);
        }

        return fetch(uploadUrl, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (response) {
            return response.json().then(function (payload) {
                if (csrfMeta && payload.csrfHash) {
                    csrfMeta.dataset.hash = payload.csrfHash;
                    document.querySelectorAll('input[type="hidden"]').forEach(function (tokenInput) {
                        if (tokenInput.name === csrfMeta.content) {
                            tokenInput.value = payload.csrfHash;
                        }
                    });
                }
                if (!response.ok) {
                    throw new Error(payload.message || 'Unggah file gagal.');
                }
                return payload;
            });
        });
    }

    function saveInlineDocumentExpiry(input, attachmentId) {
        const attachment = document.getElementById('inline-document-' + attachmentId);
        if (!attachment || !attachment.dataset.uploadUrl || attachment.dataset.hasStoredFile !== '1' || !input.value) {
            return;
        }

        const state = attachment.querySelector('[data-inline-document-state]');
        const formData = new FormData();
        formData.append('expires_at', input.value);
        state.textContent = 'Menyimpan tanggal berlaku...';
        state.className = 'inline-document-state is-pending';
        postInlineDocument(attachment.dataset.uploadUrl, formData)
            .then(function () {
                state.textContent = 'Tanggal berlaku tersimpan';
                state.className = 'inline-document-state is-saved';
            })
            .catch(function (error) {
                state.textContent = error.message || 'Tanggal berlaku belum tersimpan.';
                state.className = 'inline-document-state is-error';
            });
    }

    function selectInlineDocument(input, attachmentId) {
        if (!input.files || input.files.length === 0) {
            return;
        }

        const file = input.files[0];
        const attachment = document.getElementById('inline-document-' + attachmentId);
        if (!attachment) {
            return;
        }

        const expiryInput = attachment.querySelector('[data-inline-document-expiry]');
        if (attachment.dataset.uploadUrl && expiryInput && !expiryInput.value) {
            const expiryState = attachment.querySelector('[data-inline-document-state]');
            expiryState.textContent = 'Isi tanggal berlaku (jatuh tempo) terlebih dahulu, lalu pilih file lagi.';
            expiryState.className = 'inline-document-state is-error';
            input.value = '';
            return;
        }

        attachment.dataset.previewUrl = URL.createObjectURL(file);
        attachment.dataset.previewName = file.name;

        const fileName = attachment.querySelector('[data-inline-document-file]');
        const preview = attachment.querySelector('[data-inline-document-preview]');
        const state = attachment.querySelector('[data-inline-document-state]');
        fileName.textContent = file.name;
        fileName.classList.add('has-file');
        preview.disabled = false;
        preview.title = 'Lihat file yang dipilih';

        const uploadUrl = attachment.dataset.uploadUrl;
        if (!uploadUrl) {
            state.textContent = 'File dipilih - belum tersimpan';
            state.className = 'inline-document-state is-pending';
            return;
        }

        const upload = function () {
            state.textContent = 'Mengunggah file...';
            state.className = 'inline-document-state is-pending';
            const formData = new FormData();
            formData.append('document', file);
            if (expiryInput) {
                formData.append('expires_at', expiryInput.value);
            }

            return postInlineDocument(uploadUrl, formData)
                .then(function (payload) {
                    attachment.dataset.previewUrl = payload.document.url;
                    attachment.dataset.previewName = payload.document.name;
                    attachment.dataset.hasStoredFile = '1';
                    fileName.textContent = payload.document.name;
                    state.textContent = 'Tersimpan dan menunggu verifikasi';
                    state.className = 'inline-document-state is-saved';
                })
                .catch(function (error) {
                    state.textContent = error.message || 'File belum tersimpan.';
                    state.className = 'inline-document-state is-error';
                });
        };
        window.inlineDocumentUploadQueue = (window.inlineDocumentUploadQueue || Promise.resolve())
            .then(upload, upload);
    }
</script>
<?php } ?>

<div class="inline-document-card" id="inline-document-<?= esc($attachmentId) ?>" <?= $attachmentUrl !== '' ? 'data-preview-url="' . esc($attachmentUrl) . '" data-preview-name="' . esc($attachmentFile) . '"' : '' ?> <?= $attachmentUploadUrl !== '' ? 'data-upload-url="' . esc($attachmentUploadUrl) . '"' : '' ?> data-has-stored-file="<?= $attachmentUrl !== '' ? '1' : '0' ?>">
    <span class="inline-document-icon"><i class="fa-solid fa-file-pdf"></i></span>
    <div class="inline-document-main">
        <span class="inline-document-title"><?= esc($attachmentTitle) ?></span>
        <span class="inline-document-description"><?= esc($attachmentDescription) ?></span>
        <span class="inline-document-file <?= $attachmentFile !== '' ? 'has-file' : '' ?>" data-inline-document-file>
            <?= $attachmentFile !== '' ? esc($attachmentFile) : 'Belum ada file yang dipilih' ?>
        </span>
        <span class="inline-document-state <?= $attachmentUrl !== '' ? 'is-saved' : '' ?>" data-inline-document-state>
            <?= $attachmentUrl !== '' ? 'Tersimpan dan siap dilihat' : ($attachmentEditable ? ($attachmentUploadUrl !== '' ? 'Pilih file untuk diunggah' : 'Pilih file untuk pratinjau') : ($attachmentFile !== '' ? 'Berkas tercatat, pratinjau belum tersedia' : 'Belum ada berkas tersimpan')) ?>
        </span>
        <?php if ($attachmentHasExpiry): ?>
            <?php if ($attachmentEditable && $attachmentUploadUrl !== ''): ?>
                <label class="inline-document-expiry">Berlaku sampai (jatuh tempo)
                    <input type="date" value="<?= esc($attachmentExpiresAt) ?>" data-inline-document-expiry onchange="saveInlineDocumentExpiry(this, '<?= esc($attachmentId) ?>')">
                </label>
            <?php elseif ($attachmentExpiresAt !== ''): ?>
                <span class="inline-document-expiry">Berlaku sampai <?= esc(date('d-m-Y', strtotime($attachmentExpiresAt))) ?></span>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($attachmentUrl !== '' && $verificationLabel !== ''): ?><span class="inline-document-state <?= $verificationStatus === 'verified' ? 'is-saved' : ($verificationStatus === 'rejected' ? 'is-error' : 'is-pending') ?>"><?= esc($verificationLabel) ?></span><?php endif; ?>
        <?php if ($verificationStatus === 'rejected' && trim((string) ($attachment['verification_note'] ?? '')) !== ''): ?>
            <span class="inline-document-state is-error"><strong>Catatan perbaikan:</strong> <?= nl2br(esc($attachment['verification_note'])) ?></span>
        <?php endif; ?>
    </div>
    <div class="inline-document-actions">
        <?php if ($attachmentRequired): ?><span class="inline-document-badge">Wajib</span><?php endif; ?>
        <?php if ($attachmentEditable && $attachmentUploadUrl !== ''): ?>
            <label class="inline-document-upload">
                <input type="file" accept=".pdf,.jpg,.jpeg,.png" onchange="selectInlineDocument(this, '<?= esc($attachmentId) ?>')">
                <i class="fa-solid fa-paperclip"></i> Pilih file
            </label>
        <?php endif; ?>
        <button type="button" class="inline-document-preview" data-inline-document-preview onclick="previewInlineDocument('<?= esc($attachmentId) ?>')" <?= $attachmentUrl === '' ? 'disabled title="Berkas belum tersedia untuk pratinjau"' : '' ?>>
            <i class="fa-solid fa-eye"></i> Lihat
        </button>
    </div>
</div>
