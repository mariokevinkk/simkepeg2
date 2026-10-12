<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$count = static fn (string $key): int => (int) ($employmentCounts[$key] ?? 0);
$percent = static fn (int $value, int $total): int => $total > 0 ? (int) round(($value / $total) * 100) : 0;
$total = (int) $employee_count;
$asnCount = $count('cpns') + $count('pns') + $count('pppk_full') + $count('pppk_part');
$bludCount = $count('blud_permanent') + $count('blud_contract');
$mitraCount = $count('mitra_full') + $count('mitra_part');
$otherCount = $count('other');
$summaryGroups = [
    [
        'title' => 'Total ASN',
        'total' => $asnCount,
        'share' => $percent($asnCount, $total),
        'icon' => 'fa-user-tie',
        'icon_color' => 'icon-green',
        'badge_color' => 'perc-green',
        'dot_color' => '#10b981',
        'rows' => [
            ['label' => 'CPNS', 'count' => $count('cpns')],
            ['label' => 'PNS', 'count' => $count('pns')],
            ['label' => 'PPPK (Penuh)', 'count' => $count('pppk_full')],
            ['label' => 'PPPK (Paruh)', 'count' => $count('pppk_part')],
        ],
    ],
    [
        'title' => 'Total BLUD',
        'total' => $bludCount,
        'share' => $percent($bludCount, $total),
        'icon' => 'fa-user-doctor',
        'icon_color' => 'icon-purple',
        'badge_color' => 'perc-purple',
        'dot_color' => '#9333ea',
        'rows' => [
            ['label' => 'Tetap', 'count' => $count('blud_permanent')],
            ['label' => 'Kontrak', 'count' => $count('blud_contract')],
        ],
    ],
    [
        'title' => 'Total Mitra',
        'total' => $mitraCount,
        'share' => $percent($mitraCount, $total),
        'icon' => 'fa-handshake',
        'icon_color' => 'icon-orange',
        'badge_color' => 'perc-orange',
        'dot_color' => '#f59e0b',
        'rows' => [
            ['label' => 'Penuh Waktu', 'count' => $count('mitra_full')],
            ['label' => 'Paruh Waktu', 'count' => $count('mitra_part')],
        ],
    ],
];
$chartLabels = ['CPNS', 'PNS', 'PPPK (Penuh)', 'PPPK (Paruh)', 'BLUD Tetap', 'BLUD Kontrak', 'Mitra (Penuh)', 'Mitra (Paruh)'];
$chartValues = [
    $count('cpns'), $count('pns'), $count('pppk_full'), $count('pppk_part'),
    $count('blud_permanent'), $count('blud_contract'), $count('mitra_full'), $count('mitra_part'),
];
$chartColors = [
    'rgba(16, 185, 129, 0.9)', 'rgba(16, 185, 129, 0.75)', 'rgba(16, 185, 129, 0.55)', 'rgba(16, 185, 129, 0.35)',
    'rgba(139, 92, 246, 0.9)', 'rgba(139, 92, 246, 0.65)',
    'rgba(245, 158, 11, 0.9)', 'rgba(245, 158, 11, 0.6)',
];
if ($otherCount > 0) {
    $chartLabels[] = 'Lainnya';
    $chartValues[] = $otherCount;
    $chartColors[] = 'rgba(100, 116, 139, 0.65)';
}
$safeJsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
?>
<style>
    .dashboard-title-row { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:24px; }
    .dashboard-title-row .page-title { margin:0; }
    .dashboard-title-row select { min-width:198px; }
    .dashboard-overview { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:24px; align-items:stretch; margin-bottom:24px; }
    .dashboard-summary-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); grid-template-rows:minmax(315px,auto) minmax(254px,auto); gap:24px; }
    .dashboard-summary-grid .card { height:100%; min-height:254px; }
    .dashboard-summary-grid .card:nth-child(-n+2) { min-height:315px; }
    .dashboard-distribution-card { min-height:594px; display:flex; flex-direction:column; }
    .dashboard-distribution-card .card-heading { flex:0 0 auto; }
    .dashboard-distribution-chart { height:auto; min-height:0; flex:1; }
    .job-summary-card { padding:26px 28px; }
    .job-summary-heading { display:flex; align-items:center; justify-content:space-between; gap:18px; }
    .job-summary-heading h2 { font-size:18px; margin:0; }
    .job-search { width:275px; min-height:48px; border:1px solid var(--border); border-radius:10px; display:flex; align-items:center; gap:10px; padding:0 14px; color:var(--text-muted); }
    .job-search:focus-within { border-color:var(--primary); box-shadow:0 0 0 3px rgba(67,97,238,.1); }
    .job-search input { width:100%; border:0; outline:0; background:transparent; color:var(--text-main); font:inherit; font-size:14px; }
    .job-chart-container { position:relative; width:100%; height:340px; margin:22px 0 28px; }
    .job-card-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
    .job-card { min-width:0; min-height:112px; border:1px solid var(--border); border-radius:10px; padding:16px 18px; background:var(--bg-surface); }
    .job-card[hidden] { display:none; }
    .job-card h3 { color:var(--primary); font-size:16px; margin:0 0 10px; }
    .job-card-total { display:flex; align-items:baseline; gap:7px; margin-bottom:8px; }
    .job-card-total strong { color:var(--text-main); font-size:27px; line-height:1; }
    .job-card-total span { color:var(--text-muted); font-size:12px; }
    .job-card-details { display:grid; gap:4px; }
    .job-card-detail { display:flex; justify-content:space-between; gap:10px; color:var(--text-muted); font-size:12px; }
    .job-card-detail strong { color:var(--text-main); font-size:12px; }
    .dashboard-empty { color:var(--text-muted); padding:28px 0; text-align:center; }
    @media(max-width:1200px) {
        .dashboard-overview { grid-template-columns:1fr; }
        .dashboard-summary-grid { grid-template-rows:minmax(280px,auto) minmax(254px,auto); }
        .dashboard-summary-grid .card:nth-child(-n+2) { min-height:280px; }
        .dashboard-distribution-card { min-height:500px; }
        .dashboard-distribution-chart { min-height:400px; }
    }
    @media(max-width:700px) {
        .dashboard-title-row { align-items:stretch; flex-direction:column; }
        .dashboard-title-row select { width:100%; }
        .dashboard-summary-grid { grid-template-columns:1fr; grid-template-rows:none; }
        .dashboard-summary-grid .card,
        .dashboard-summary-grid .card:nth-child(-n+2) { min-height:220px; }
        .dashboard-distribution-card { min-height:400px; padding:22px; }
        .dashboard-distribution-chart { min-height:300px; }
        .job-summary-card { padding:20px; }
        .job-summary-heading { align-items:stretch; flex-direction:column; }
        .job-search { width:100%; }
        .job-chart-container { height:290px; margin:18px 0 22px; }
        .job-card-grid { grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); }
    }
</style>

<div class="dashboard-title-row">
    <h1 class="page-title">Dashboard Utama</h1>
    <?php if (! empty($unitOptions)): ?>
        <form method="get" action="<?= base_url('/') ?>">
            <select class="form-control" name="filter_bangsal" onchange="this.form.submit()" aria-label="Filter bangsal atau unit">
                <option value="">Semua Bangsal/Unit</option>
                <?php foreach ($unitOptions as $unit): ?>
                    <option value="<?= esc($unit['name']) ?>" <?= ($selectedUnit ?? '') === $unit['name'] ? 'selected' : '' ?>><?= esc($unit['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    <?php endif; ?>
</div>

<?php if (empty($can_view_employees)): ?>
    <div class="main-card" style="margin-bottom:24px;color:var(--text-muted);">Akun Anda belum memiliki hak untuk melihat data pegawai pada dashboard.</div>
<?php endif; ?>

<div class="dashboard-overview">
    <div class="dashboard-summary-grid">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Total Pegawai</span>
                <div class="card-icon icon-blue"><i class="fa-solid fa-users-viewfinder"></i></div>
            </div>
            <div class="card-value">
                <?= number_format($total) ?>
                <span class="card-percentage perc-blue"><?= $total > 0 ? '100%' : '0%' ?></span>
            </div>
            <div class="card-details" style="border:none;">
                <div class="detail-row" style="color:var(--text-muted);justify-content:center;text-align:center;">
                    Total keseluruhan pegawai <br> di lingkup instansi
                </div>
                <?php if ($otherCount > 0): ?>
                    <div class="detail-row" style="color:var(--text-muted);">
                        <span>Belum dikelompokkan</span>
                        <span><?= number_format($otherCount) ?> <small>(<?= $percent($otherCount, $total) ?>%)</small></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php foreach ($summaryGroups as $group): ?>
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><?= esc($group['title']) ?></span>
                    <div class="card-icon <?= esc($group['icon_color']) ?>"><i class="fa-solid <?= esc($group['icon']) ?>"></i></div>
                </div>
                <div class="card-value">
                    <?= number_format($group['total']) ?>
                    <span class="card-percentage <?= esc($group['badge_color']) ?>"><?= $group['share'] ?>%</span>
                </div>
                <div class="card-details">
                    <?php foreach ($group['rows'] as $row): ?>
                        <div class="detail-row">
                            <span><i class="fa-solid fa-circle" style="font-size:8px;color:<?= esc($group['dot_color']) ?>;margin-right:6px;"></i><?= esc($row['label']) ?></span>
                            <span><?= number_format($row['count']) ?> <small>(<?= $percent($row['count'], $group['total']) ?>%)</small></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <section class="main-card dashboard-distribution-card">
        <div class="card-heading">Distribusi Pegawai Kerja</div>
        <div class="chart-container dashboard-distribution-chart"><canvas id="employeeChart"></canvas></div>
    </section>
</div>

<section class="main-card job-summary-card">
    <div class="job-summary-heading">
        <h2>Rincian Pegawai Berdasarkan Jabatan &amp; Jenjang</h2>
        <label class="job-search" for="jobSearch">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="jobSearch" type="search" placeholder="Cari nama jabatan..." autocomplete="off">
        </label>
    </div>

    <?php if ($jobBreakdown === []): ?>
        <p class="dashboard-empty">Belum ada data jabatan yang dapat ditampilkan.</p>
    <?php else: ?>
        <div class="job-chart-container"><canvas id="jobBreakdownChart"></canvas></div>
        <div class="job-card-grid" id="jobBreakdownCards">
            <?php foreach ($jobBreakdown as $job): ?>
                <article class="job-card" data-job-name="<?= esc(mb_strtolower($job['name'])) ?>">
                    <h3><?= esc($job['name']) ?></h3>
                    <div class="job-card-total"><strong><?= number_format($job['total']) ?></strong><span>Orang</span></div>
                    <?php if ($job['details'] !== []): ?>
                        <div class="job-card-details">
                            <?php foreach ($job['details'] as $detail): ?>
                                <div class="job-card-detail"><span><?= esc($detail['label']) ?></span><strong><?= number_format($detail['count']) ?></strong></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const employeeCanvas = document.getElementById('employeeChart');
    if (employeeCanvas && typeof Chart !== 'undefined') {
        new Chart(employeeCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartLabels, $safeJsonFlags) ?>,
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: <?= json_encode($chartValues, $safeJsonFlags) ?>,
                    backgroundColor: <?= json_encode($chartColors, $safeJsonFlags) ?>,
                    borderRadius: 7,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        callbacks: { label: context => ` ${context.raw} Pegawai` }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e2e8f0', drawBorder: false }, ticks: { precision: 0, color: '#64748b' } },
                    x: { grid: { display: false, drawBorder: false }, ticks: { color: '#64748b', maxRotation: 16, minRotation: 0 } }
                }
            }
        });
    }

    const jobCanvas = document.getElementById('jobBreakdownChart');
    const search = document.getElementById('jobSearch');
    if (!jobCanvas || typeof Chart === 'undefined') return;

    const jobs = <?= json_encode($jobBreakdown, $safeJsonFlags) ?>;
    const jobChart = new Chart(jobCanvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels: jobs.map(job => job.name),
            datasets: [
                { label: 'Ahli / Spesialis', data: jobs.map(job => job.expert), backgroundColor: '#8b5cf6', borderRadius: 0, borderSkipped: false },
                { label: 'Terampil / Umum', data: jobs.map(job => job.skilled), backgroundColor: '#10b981', borderRadius: 0, borderSkipped: false },
                { label: 'Tanpa Jenjang Spesifik', data: jobs.map(job => job.specific), backgroundColor: '#3b82f6', borderRadius: 0, borderSkipped: false }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: false, boxWidth: 28, padding: 18 } },
                tooltip: { callbacks: { label: context => ` ${context.dataset.label}: ${context.raw} orang` } }
            },
            scales: {
                x: { stacked: true, grid: { color: '#e2e8f0' }, ticks: { maxRotation: 0, minRotation: 0 } },
                y: { stacked: true, beginAtZero: true, grid: { color: '#e2e8f0' }, ticks: { precision: 0 } }
            }
        }
    });

    if (search) {
        search.addEventListener('input', function () {
            const query = search.value.trim().toLocaleLowerCase('id');
            const filteredJobs = jobs.filter(job => job.name.toLocaleLowerCase('id').includes(query));
            document.querySelectorAll('#jobBreakdownCards .job-card').forEach(function (card) {
                card.hidden = !card.dataset.jobName.includes(query);
            });
            jobChart.data.labels = filteredJobs.map(job => job.name);
            jobChart.data.datasets[0].data = filteredJobs.map(job => job.expert);
            jobChart.data.datasets[1].data = filteredJobs.map(job => job.skilled);
            jobChart.data.datasets[2].data = filteredJobs.map(job => job.specific);
            jobChart.update();
        });
    }
});
</script>
<?= $this->endSection() ?>
