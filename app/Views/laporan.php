<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div style="margin-bottom:20px;"><h1 class="page-title" style="margin:0;">Laporan Kepegawaian</h1><p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;">Ringkasan berdasarkan data database dalam cakupan akses akun.</p></div>
<div class="dashboard-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">
    <div class="main-card" style="padding:20px;"><span style="color:var(--text-muted);font-size:13px;">Total pegawai</span><strong style="display:block;font-size:30px;margin-top:8px;"><?= (int) $employee_count ?></strong></div>
    <div class="main-card" style="padding:20px;"><span style="color:var(--text-muted);font-size:13px;">Unit tercatat</span><strong style="display:block;font-size:30px;margin-top:8px;"><?= count($unitCounts) ?></strong></div>
    <div class="main-card" style="padding:20px;"><span style="color:var(--text-muted);font-size:13px;">Kategori kepegawaian</span><strong style="display:block;font-size:30px;margin-top:8px;"><?= count($statusCounts) ?></strong></div>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;margin-top:16px;">
    <section class="main-card" style="padding:20px;"><h2 style="font-size:16px;margin:0 0 12px;">Pegawai per unit</h2><?php if ($unitCounts === []): ?><p style="color:var(--text-muted);">Belum ada data.</p><?php endif; ?><?php foreach ($unitCounts as $label => $count): ?><div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);"><span><?= esc($label) ?></span><strong><?= (int) $count ?></strong></div><?php endforeach; ?></section>
    <section class="main-card" style="padding:20px;"><h2 style="font-size:16px;margin:0 0 12px;">Jenis kepegawaian</h2><?php if ($statusCounts === []): ?><p style="color:var(--text-muted);">Belum ada data.</p><?php endif; ?><?php foreach ($statusCounts as $label => $count): ?><div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);"><span><?= esc($label) ?></span><strong><?= (int) $count ?></strong></div><?php endforeach; ?></section>
</div>
<?= $this->endSection() ?>
