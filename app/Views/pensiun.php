<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$formatDate = static fn (string $date): string => date('d-m-Y', strtotime($date));
$remaining = static function (array $info): string {
    if ($info['past_due']) {
        return 'Sudah lewat BUP';
    }
    $years = intdiv($info['months_left'], 12);
    $months = $info['months_left'] % 12;
    return trim(($years > 0 ? $years . ' tahun ' : '') . ($months > 0 ? $months . ' bulan' : ($years > 0 ? '' : 'bulan ini')));
};
$overdue = count(array_filter($upcomingEmployees, static fn (array $e): bool => $e['pensiun']['past_due']));
$withinSix = count(array_filter($upcomingEmployees, static fn (array $e): bool => ! $e['pensiun']['past_due'] && $e['pensiun']['months_left'] <= 6));
?>
<style>
    .pension-cards { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:16px; margin:20px 0 8px; }
    .pension-cards .card-note { color:var(--text-muted); font-size:13px; margin-top:6px; }
    .pension-cards .icon-red { background:#fee2e2; color:#dc2626; }
    [data-theme="dark"] .pension-cards .icon-red { background:#7f1d1d; color:#fca5a5; }
    @media(max-width:1100px) { .pension-cards { grid-template-columns:repeat(2, minmax(0, 1fr)); } }
    @media(max-width:600px) { .pension-cards { grid-template-columns:1fr; } }
    .pension-badge { display:inline-block; border-radius:999px; font-size:11px; font-weight:700; padding:3px 9px; white-space:nowrap; }
    .pension-badge.overdue { background:#fee2e2; color:#b91c1c; }
    .pension-badge.soon { background:#fef3c7; color:#a16207; }
    .pension-badge.later { background:var(--bg-body); color:var(--text-muted); }
    .pension-table-wrap { overflow:auto; }
</style>
<div class="flex-mobile-stack" style="display:flex;align-items:flex-end;justify-content:space-between;gap:14px;">
    <div>
        <h1 class="page-title" style="margin:0;">Data Pensiun</h1>
        <p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;">Perkiraan dihitung dari tanggal lahir dan batas usia pensiun (BUP): 65 tahun ahli utama, 60 tahun ahli madya/pimpinan tinggi, 58 tahun lainnya. TMT pensiun = tanggal 1 bulan berikutnya.</p>
    </div>
    <form method="get" action="<?= base_url('/pensiun') ?>" style="display:flex;gap:8px;align-items:center;flex-shrink:0;">
        <label for="rentang" style="font-size:13px;color:var(--text-muted);white-space:nowrap;">Pensiun dalam</label>
        <select class="form-control" id="rentang" name="rentang" onchange="this.form.submit()" style="width:auto;min-width:120px;">
            <?php foreach ($windows as $months => $label): ?><option value="<?= $months ?>" <?= $months === $window ? 'selected' : '' ?>><?= esc($label) ?></option><?php endforeach; ?>
        </select>
    </form>
</div>

<?php if ($message = session()->getFlashdata('success')): ?><div class="main-card" style="margin-top:16px;color:var(--success);padding:12px 16px;"><?= esc($message) ?></div><?php endif; ?>
<?php if ($message = session()->getFlashdata('error')): ?><div class="main-card" style="margin-top:16px;color:var(--danger);padding:12px 16px;"><?= esc($message) ?></div><?php endif; ?>
<div class="pension-cards">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Pegawai aktif yang akan pensiun</span>
            <div class="card-icon icon-blue"><i class="fa-solid fa-user-clock"></i></div>
        </div>
        <div class="card-value"><?= number_format(count($upcomingEmployees) - $overdue) ?></div>
        <div class="card-note"><?= esc('dalam ' . $windows[$window] . ' ke depan') ?></div>
    </div>
    <div class="card">
        <div class="card-header">
            <span class="card-title">Dalam 6 bulan</span>
            <div class="card-icon icon-orange"><i class="fa-solid fa-hourglass-half"></i></div>
        </div>
        <div class="card-value"><?= number_format($withinSix) ?></div>
        <div class="card-note"><?= esc('perlu segera disiapkan') ?></div>
    </div>
    <div class="card">
        <div class="card-header">
            <span class="card-title">Lewat BUP</span>
            <div class="card-icon icon-red"><i class="fa-solid fa-triangle-exclamation"></i></div>
        </div>
        <div class="card-value"><?= number_format($overdue) ?></div>
        <div class="card-note"><?= esc('status belum diubah menjadi pensiun') ?></div>
    </div>
</div>

<h2 style="font-size:16px;margin:24px 0 10px;">Akan pensiun</h2>
<div class="main-card pension-table-wrap" style="padding:0;">
    <table class="data-table">
        <thead><tr><th>Nama / NIP</th><th>Unit</th><th>Jabatan terakhir</th><th>Tanggal lahir</th><th>Usia</th><th>BUP</th><th>TMT pensiun</th><th>Sisa waktu</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if ($upcomingEmployees === []): ?><tr><td colspan="9" style="text-align:center;padding:30px;color:var(--text-muted);">Tidak ada pegawai yang pensiun dalam <?= esc($windows[$window]) ?>.</td></tr><?php endif; ?>
        <?php foreach ($upcomingEmployees as $employee): ?>
            <?php $info = $employee['pensiun']; $level = $info['past_due'] ? 'overdue' : ($info['months_left'] <= 6 ? 'soon' : 'later'); ?>
            <tr>
                <td><strong><?= esc($employee['nama']) ?></strong><div style="font-size:12px;color:var(--text-muted);margin-top:4px;"><?= esc($employee['nip']) ?></div></td>
                <td><?= esc($employee['unit_name'] ?? '—') ?></td>
                <td><?= esc($employee['jabatan_nama'] ?: '—') ?></td>
                <td style="white-space:nowrap;"><?= esc($formatDate($employee['tanggal_lahir'])) ?></td>
                <td><?= (int) $info['age_now'] ?> th</td>
                <td><?= (int) $info['bup'] ?> th</td>
                <td style="white-space:nowrap;"><strong><?= esc($formatDate($info['tmt'])) ?></strong></td>
                <td><span class="pension-badge <?= $level ?>"><?= esc($remaining($info)) ?></span></td>
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        <a class="btn btn-outline btn-sm" style="text-decoration:none;" href="<?= base_url('/view-pegawai?id=' . (int) $employee['id']) ?>"><i class="fa-solid fa-eye"></i> Lihat</a>
                        <?php if (! empty($can_retire) && $info['past_due']): ?>
                            <form method="post" action="<?= base_url('/pensiun/' . (int) $employee['id'] . '/tetapkan') ?>" onsubmit="return confirm('Tetapkan <?= esc($employee['nama'], 'js') ?> pensiun per <?= esc($formatDate($info['tmt']), 'js') ?>? Status menjadi Pensiun dan akun login dinonaktifkan.');">
                                <?= csrf_field() ?>
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fa-solid fa-user-clock"></i> Tetapkan pensiun</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
