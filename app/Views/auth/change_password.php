<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .password-alert { border-radius: 9px; font-size: 13px; line-height: 1.5; margin: 0 0 16px; padding: 11px 13px; }
    .password-alert.error { background: #fff1f0; border: 1px solid #ffc9c3; color: #a4261d; }
    .password-alert.success { background: #edfbf4; border: 1px solid #b7e7ca; color: #176b3a; }
    .password-alert ul { margin: 0; padding-left: 18px; }
</style>
<div style="margin-bottom:20px;">
    <h1 class="page-title" style="margin:0;">Ubah Password</h1>
    <p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;">Perbarui password akun Anda.</p>
</div>

<section class="main-card" style="max-width:640px;">
    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="password-alert error"><?= esc($error) ?></div>
    <?php endif; ?>
    <?php if ($success = session()->getFlashdata('success')): ?>
        <div class="password-alert success"><?= esc($success) ?></div>
    <?php endif; ?>
    <?php $errors = session()->getFlashdata('errors') ?? []; ?>
    <?php if ($errors !== []): ?>
        <div class="password-alert error">
            <ul><?php foreach ($errors as $message): ?><li><?= esc($message) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/ubah-password') ?>" style="display:grid;gap:18px;max-width:480px;">
        <?= csrf_field() ?>
        <div>
            <label class="form-label" for="current_password">Password saat ini</label>
            <input class="form-control" id="current_password" name="current_password" type="password" autocomplete="current-password" required>
        </div>
        <div>
            <label class="form-label" for="new_password">Password baru</label>
            <input class="form-control" id="new_password" name="new_password" type="password" minlength="10" maxlength="255" autocomplete="new-password" required>
            <small style="display:block;color:var(--text-muted);margin-top:6px;">Gunakan minimal 10 karakter.</small>
        </div>
        <div>
            <label class="form-label" for="new_password_confirm">Konfirmasi password baru</label>
            <input class="form-control" id="new_password_confirm" name="new_password_confirm" type="password" minlength="10" maxlength="255" autocomplete="new-password" required>
        </div>
        <div>
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-key"></i> Simpan Password</button>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
