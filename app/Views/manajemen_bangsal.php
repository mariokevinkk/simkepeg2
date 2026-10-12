<?php helper(['form', 'security']); ?>
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<style>
    .unit-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:16px; margin-top:20px; }
    .unit-card { background:var(--bg-surface); border:1px solid var(--border); border-radius:13px; padding:20px; }
    .unit-create-grid { display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:12px; align-items:end; }
    @media(max-width:760px) { .unit-create-grid { grid-template-columns:1fr; } }
</style>
<div class="flex-mobile-stack" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div><h1 class="page-title" style="margin:0;">Manajemen Unit</h1><p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;">Unit dan jumlah pegawai diambil dari database.</p></div>
</div>
<?php if ($error = session()->getFlashdata('error')): ?><div class="main-card" style="margin-bottom:14px;color:var(--danger);"> <?= esc($error) ?> </div><?php endif; ?>
<?php if ($success = session()->getFlashdata('success')): ?><div class="main-card" style="margin-bottom:14px;color:var(--success);"> <?= esc($success) ?> </div><?php endif; ?>
<div class="main-card" style="padding:20px;">
    <h2 style="font-size:16px;margin:0 0 16px;">Tambah unit</h2>
    <form method="post" action="<?= base_url('/manajemen-bangsal') ?>" class="unit-create-grid">
        <?= csrf_field() ?>
        <div><label class="form-label" for="unit-name">Nama unit</label><input class="form-control" id="unit-name" name="name" value="<?= esc(old('name')) ?>" maxlength="120" required></div>
        <div><label class="form-label" for="unit-code">Kode</label><input class="form-control" id="unit-code" name="code" value="<?= esc(old('code')) ?>" maxlength="80"></div>
        <div><label class="form-label" for="unit-type">Jenis</label><input class="form-control" id="unit-type" name="type" value="<?= esc(old('type')) ?>" maxlength="30" placeholder="unit"></div>
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-plus"></i> Tambah</button>
    </form>
</div>
<div class="unit-grid">
    <?php foreach ($units as $unit): ?>
        <article class="unit-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:14px;">
                <div><h2 style="font-size:17px;margin:0 0 5px;"><?= esc($unit['name']) ?></h2><div style="font-size:12px;color:var(--text-muted);">Kode <?= esc($unit['code'] ?: '—') ?> · <?= esc($unit['type']) ?></div></div>
                <span class="status-badge <?= (int) $unit['is_active'] === 1 ? 'status-active' : '' ?>"><?= (int) $unit['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?></span>
            </div>
            <div style="margin:18px 0;color:var(--text-muted);font-size:13px;"><strong style="font-size:22px;color:var(--text-main);"><?= (int) $unit['employee_count'] ?></strong><br>Pegawai terdaftar</div>
            <div style="display:flex;gap:9px;">
                <a class="btn btn-outline" style="text-decoration:none;" href="<?= base_url('/data-pegawai?filter_bangsal=' . rawurlencode($unit['name'])) ?>"><i class="fa-solid fa-users"></i> Lihat pegawai</a>
                <form method="post" action="<?= base_url('/manajemen-bangsal/' . (int) $unit['id'] . '/status') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="is_active" value="<?= (int) $unit['is_active'] === 1 ? '0' : '1' ?>">
                    <button class="btn btn-outline" type="submit"><?= (int) $unit['is_active'] === 1 ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                </form>
            </div>
        </article>
    <?php endforeach; ?>
    <?php if ($units === []): ?><div class="main-card" style="grid-column:1/-1;text-align:center;color:var(--text-muted);">Belum ada unit. Tambahkan unit pertama di atas.</div><?php endif; ?>
</div>
<?= $this->endSection() ?>
