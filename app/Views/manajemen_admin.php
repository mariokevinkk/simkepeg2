<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php helper(['form', 'security']); ?>
<style>
    .admin-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .access-checks { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 9px 16px; }
    .access-checks label { align-items: flex-start; color: var(--text-main); display: flex; font-size: 13px; gap: 8px; }
    .unit-checks { display: flex; flex-wrap: wrap; gap: 10px 18px; }
    .unit-checks label { align-items: center; display: inline-flex; gap: 7px; }
    .admin-edit summary { color: var(--primary); cursor: pointer; font-size: 12px; font-weight: 650; }
    .admin-edit-form { background: var(--bg-body); border: 1px solid var(--border); border-radius: 9px; margin-top: 10px; padding: 14px; min-width: 330px; }
    @media (max-width: 720px) { .admin-form-grid, .access-checks { grid-template-columns: 1fr; } }
</style>
<div class="flex-mobile-stack" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Manajemen Admin Unit</h1>
        <p style="color:var(--text-muted); margin:6px 0 0; font-size:13px;">Tentukan unit yang dapat dilihat dan tindakan yang boleh dilakukan tiap admin.</p>
    </div>
</div>

<?php if ($error = session()->getFlashdata('error')): ?><div class="main-card" style="margin-bottom:16px; color:var(--danger);"><?= esc($error) ?></div><?php endif; ?>
<?php if ($success = session()->getFlashdata('success')): ?><div class="main-card" style="margin-bottom:16px; color:var(--success);"><?= esc($success) ?></div><?php endif; ?>

<details class="main-card" style="padding:20px; margin-bottom:24px;">
    <summary style="color:var(--primary); cursor:pointer; font-weight:700;"><i class="fa-solid fa-user-plus"></i> Tambah admin unit</summary>
    <form method="post" action="<?= base_url('/manajemen-admin') ?>" style="margin-top:20px;">
        <?= csrf_field() ?>
        <div class="admin-form-grid">
            <div><label class="form-label" for="admin-name">Nama</label><input class="form-control" id="admin-name" name="name" value="<?= esc(old('name')) ?>" required></div>
            <div><label class="form-label" for="admin-username">Username</label><input class="form-control" id="admin-username" name="username" value="<?= esc(old('username')) ?>" required></div>
            <div><label class="form-label" for="admin-email">Email</label><input class="form-control" type="email" id="admin-email" name="email" value="<?= esc(old('email')) ?>" required></div>
            <div><label class="form-label" for="admin-password">Password awal</label><input class="form-control" type="password" id="admin-password" name="password" minlength="10" required><small style="color:var(--text-muted);">Minimal 10 karakter. Bagikan melalui kanal internal.</small></div>
        </div>
        <div style="margin-top:18px;">
            <label class="form-label">Unit yang dapat diakses</label>
            <div class="unit-checks">
                <?php foreach ($units as $unit): ?>
                    <label><input type="checkbox" name="unit_ids[]" value="<?= (int) $unit['id'] ?>" <?= in_array((string) $unit['id'], array_map('strval', (array) old('unit_ids')), true) ? 'checked' : '' ?>> <?= esc($unit['name']) ?></label>
                <?php endforeach; ?>
            </div>
        </div>
        <div style="margin-top:18px;">
            <label class="form-label">Hak akses</label>
            <div class="access-checks">
                <?php foreach ($permissionOptions as $key => $label): ?>
                    <label><input type="checkbox" name="permissions[]" value="<?= esc($key) ?>" <?= in_array($key, (array) old('permissions'), true) ? 'checked' : '' ?>> <?= esc($label) ?></label>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top:20px;"><i class="fa-solid fa-floppy-disk"></i> Buat admin unit</button>
    </form>
</details>

<div class="main-card" style="padding:0; overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>ADMIN</th><th>UNIT AKSES</th><th>STATUS</th><th>HAK AKSES</th><th>UBAH</th></tr></thead>
            <tbody>
            <?php if ($adminAccounts === []): ?>
                <tr><td colspan="5" style="padding:28px; text-align:center; color:var(--text-muted);">Belum ada admin unit.</td></tr>
            <?php endif; ?>
            <?php foreach ($adminAccounts as $account): ?>
                <?php $selectedUnits = array_map(static fn (array $unit): int => (int) $unit['id'], $account['units']); ?>
                <tr>
                    <td><strong><?= esc($account['name']) ?></strong><div style="font-size:12px; color:var(--text-muted);"><?= esc($account['username']) ?> · <?= esc($account['email']) ?></div></td>
                    <td><?= esc(implode(', ', array_column($account['units'], 'name')) ?: 'Belum ada unit') ?></td>
                    <td><?= $account['status'] === 'active' ? '<span class="status-badge status-active">Aktif</span>' : '<span class="status-badge">Nonaktif</span>' ?></td>
                    <td style="font-size:12px;"><?= esc(implode(', ', array_map(static fn (string $key): string => $permissionOptions[$key] ?? $key, array_keys(array_filter($account['permissions'])))) ?: 'Tidak ada') ?></td>
                    <td>
                        <details class="admin-edit">
                            <summary>Ubah akses</summary>
                            <form class="admin-edit-form" method="post" action="<?= base_url('/manajemen-admin/' . (int) $account['id'] . '/access') ?>">
                                <?= csrf_field() ?>
                                <label class="form-label">Unit</label>
                                <div class="unit-checks">
                                    <?php foreach ($units as $unit): ?>
                                        <label><input type="checkbox" name="unit_ids[]" value="<?= (int) $unit['id'] ?>" <?= in_array((int) $unit['id'], $selectedUnits, true) ? 'checked' : '' ?>> <?= esc($unit['name']) ?></label>
                                    <?php endforeach; ?>
                                </div>
                                <label class="form-label" style="margin-top:14px;">Hak akses</label>
                                <div class="access-checks">
                                    <?php foreach ($permissionOptions as $key => $label): ?>
                                        <label><input type="checkbox" name="permissions[]" value="<?= esc($key) ?>" <?= ! empty($account['permissions'][$key]) ? 'checked' : '' ?>> <?= esc($label) ?></label>
                                    <?php endforeach; ?>
                                </div>
                                <label class="form-label" style="margin-top:14px;">Status akun</label>
                                <select class="form-control" name="status"><option value="active" <?= $account['status'] === 'active' ? 'selected' : '' ?>>Aktif</option><option value="disabled" <?= $account['status'] === 'disabled' ? 'selected' : '' ?>>Nonaktif</option></select>
                                <button type="submit" class="btn btn-primary btn-sm" style="margin-top:12px;">Simpan akses</button>
                            </form>
                        </details>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
