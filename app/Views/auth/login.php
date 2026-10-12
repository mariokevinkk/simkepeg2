<?php helper(['form', 'security']); ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SIM Kepegawaian RSUD Kota Yogyakarta</title>
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
        <h1>Masuk ke akun</h1>
        <p class="auth-subtitle">Gunakan username, email, atau NIP yang terdaftar.</p>
        <?php if ($error = session()->getFlashdata('error')): ?><div class="auth-alert error"><?= esc($error) ?></div><?php endif; ?>
        <?php if ($success = session()->getFlashdata('success')): ?><div class="auth-alert success"><?= esc($success) ?></div><?php endif; ?>
        <?php $errors = session()->getFlashdata('errors') ?? []; ?>
        <?php if ($errors !== []): ?><div class="auth-alert error"><ul><?php foreach ($errors as $message): ?><li><?= esc($message) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <form method="post" action="<?= base_url('/login') ?>">
            <?= csrf_field() ?>
            <div class="auth-field">
                <label for="identity">Username / Email / NIP</label>
                <input id="identity" name="identity" value="<?= esc(old('identity')) ?>" autocomplete="username" required autofocus>
            </div>
            <div class="auth-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password" required>
            </div>
            <button class="auth-button" type="submit">Masuk</button>
        </form>
        <p class="auth-footer">Belum punya akun? <a class="auth-link" href="<?= base_url('/register') ?>">Daftar sebagai pegawai</a></p>
    </main>
</body>
</html>
