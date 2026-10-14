<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php helper(['form', 'security']); ?>
<div style="margin-bottom:24px;">
    <h1 class="page-title" style="margin:0;">Manajemen Akun</h1>
    <p style="color:var(--text-muted); font-size:13px; margin:6px 0 0;">Daftar akun pegawai beserta peran dan unit yang ditetapkan.</p>
</div>
<?php if ($error = session()->getFlashdata('error')): ?><div class="main-card" style="margin-bottom:16px; color:var(--danger);"><?= esc($error) ?></div><?php endif; ?>
<?php if ($success = session()->getFlashdata('success')): ?><div class="main-card" style="margin-bottom:16px; color:var(--success);"><?= esc($success) ?></div><?php endif; ?>
<div class="main-card" style="padding:0; overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>NAMA</th><th>USERNAME / NIP</th><th>ROLE</th><th>UNIT</th><th>TERAKHIR LOGIN</th></tr></thead>
            <tbody>
            <?php if ($accounts === []): ?><tr><td colspan="5" style="text-align:center; padding:28px; color:var(--text-muted);">Belum ada akun.</td></tr><?php endif; ?>
            <?php foreach ($accounts as $account): ?>
                <tr>
                    <td><strong><?= esc($account['name']) ?></strong><div style="font-size:12px; color:var(--text-muted);"><?= esc($account['email']) ?></div></td>
                    <td><?= esc($account['username']) ?><?= $account['nip'] ? '<br><small>' . esc($account['nip']) . '</small>' : '' ?></td>
                    <td><?= esc(match ($account['role']) { 'superadmin' => 'Super Admin', 'admin_unit' => 'Admin Unit', default => 'Pegawai' }) ?></td>
                    <td><?= esc(($account['assigned_units'] ?? '') ?: ($account['unit_name'] ?? '-')) ?></td>
                    <td><?= $account['last_login'] ? esc(date('d-m-Y H:i', strtotime($account['last_login']))) : '-' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div style="padding:16px 20px; border-top:1px solid var(--border); color:var(--text-muted); font-size:13px;">
        <?= count($accounts) ?> akun ditampilkan.
    </div>
</div>
<?= $this->endSection() ?>
