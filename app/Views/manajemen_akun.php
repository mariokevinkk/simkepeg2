<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php helper(['form', 'security']); ?>
<div style="margin-bottom:24px;">
    <h1 class="page-title" style="margin:0;">Manajemen Akun</h1>
    <p style="color:var(--text-muted); font-size:13px; margin:6px 0 0;">Tinjau pendaftaran pegawai dan kelola status akun.</p>
</div>
<?php if ($error = session()->getFlashdata('error')): ?><div class="main-card" style="margin-bottom:16px; color:var(--danger);"><?= esc($error) ?></div><?php endif; ?>
<?php if ($success = session()->getFlashdata('success')): ?><div class="main-card" style="margin-bottom:16px; color:var(--success);"><?= esc($success) ?></div><?php endif; ?>
<div class="main-card" style="padding:0; overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>NAMA</th><th>USERNAME / NIP</th><th>ROLE</th><th>UNIT</th><th>STATUS</th><th>TERAKHIR LOGIN</th><th>AKSI</th></tr></thead>
            <tbody>
            <?php if ($accounts === []): ?><tr><td colspan="7" style="text-align:center; padding:28px; color:var(--text-muted);">Belum ada akun.</td></tr><?php endif; ?>
            <?php foreach ($accounts as $account): ?>
                <tr>
                    <td><strong><?= esc($account['name']) ?></strong><div style="font-size:12px; color:var(--text-muted);"><?= esc($account['email']) ?></div></td>
                    <td><?= esc($account['username']) ?><?= $account['nip'] ? '<br><small>' . esc($account['nip']) . '</small>' : '' ?></td>
                    <td><?= esc(match ($account['role']) { 'superadmin' => 'Super Admin', 'admin_unit' => 'Admin Unit', default => 'Pegawai' }) ?></td>
                    <td><?= esc(($account['assigned_units'] ?? '') ?: ($account['unit_name'] ?? '-')) ?></td>
                    <td><?= $account['status'] === 'active' ? '<span class="status-badge status-active">Aktif</span>' : '<span class="status-badge">' . esc($account['status'] === 'pending' ? 'Menunggu persetujuan' : 'Nonaktif') . '</span>' ?></td>
                    <td><?= $account['last_login'] ? esc(date('d-m-Y H:i', strtotime($account['last_login']))) : '-' ?></td>
                    <td>
                        <?php if ($account['role'] !== 'superadmin'): ?>
                            <form method="post" action="<?= base_url('/manajemen-akun/' . (int) $account['id'] . '/status') ?>" style="display:flex; gap:7px; align-items:center;">
                                <?= csrf_field() ?>
                                <select class="form-control" name="status" style="min-width:120px;">
                                    <option value="active" <?= $account['status'] === 'active' ? 'selected' : '' ?>>Aktifkan</option>
                                    <option value="disabled" <?= $account['status'] === 'disabled' ? 'selected' : '' ?>>Nonaktifkan</option>
                                </select>
                                <button class="btn btn-outline btn-sm" type="submit">Simpan</button>
                            </form>
                        <?php else: ?>-
                        <?php endif; ?>
                    </td>
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
