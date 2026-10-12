<?php helper(['form', 'security']); ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi | SIM Kepegawaian RSUD Kota Yogyakarta</title>
    <link rel="icon" type="image/png" href="<?= base_url('favicon-rsud.png') ?>">
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" sizes="any">
    <link rel="apple-touch-icon" href="<?= base_url('apple-touch-icon.png') ?>">
    <?= view('auth/_styles') ?>
</head>
<body>
    <main class="auth-card">
        <div class="auth-brand">
            <img src="<?= base_url('logo-rs.png') ?>" alt="">
            <div><strong>SIM Kepegawaian</strong><span>RSUD Kota Yogyakarta</span></div>
        </div>
        <h1>Daftar akun pegawai</h1>
        <p class="auth-subtitle">Pendaftaran baru akan ditinjau dan diaktifkan oleh Super Admin.</p>
        <?php if ($error = session()->getFlashdata('error')): ?><div class="auth-alert error"><?= esc($error) ?></div><?php endif; ?>
        <?php $errors = session()->getFlashdata('errors') ?? []; ?>
        <?php if ($errors !== []): ?><div class="auth-alert error"><ul><?php foreach ($errors as $message): ?><li><?= esc($message) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <form method="post" action="<?= base_url('/register') ?>">
            <?= csrf_field() ?>
            <div class="auth-field"><label for="name">Nama lengkap</label><input id="name" name="name" value="<?= esc(old('name')) ?>" autocomplete="name" required></div>
            <div class="auth-field"><label for="nip">NIP</label><input id="nip" name="nip" value="<?= esc(old('nip')) ?>" required></div>
            <div class="auth-field"><label for="username">Username</label><input id="username" name="username" value="<?= esc(old('username')) ?>" autocomplete="username" required></div>
            <div class="auth-field"><label for="email">Email</label><input id="email" type="email" name="email" value="<?= esc(old('email')) ?>" autocomplete="email" required></div>
            <div class="auth-field">
                <label for="unit_id">Unit kerja</label>
                <select id="unit_id" name="unit_id" required>
                    <option value="">Pilih unit kerja</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?= (int) $unit['id'] ?>" <?= (string) old('unit_id') === (string) $unit['id'] ? 'selected' : '' ?>><?= esc($unit['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="auth-field"><label for="password">Password (minimal 10 karakter)</label><input id="password" type="password" name="password" autocomplete="new-password" required></div>
            <div class="auth-field"><label for="password_confirm">Ulangi password</label><input id="password_confirm" type="password" name="password_confirm" autocomplete="new-password" required></div>
            <button class="auth-button" type="submit">Kirim pendaftaran</button>
        </form>
        <p class="auth-footer">Sudah punya akun? <a class="auth-link" href="<?= base_url('/login') ?>">Masuk</a></p>
    </main>
</body>
</html>
