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
    [
        'title' => 'Total Lainnya',
        'total' => $otherCount,
        'share' => $percent($otherCount, $total),
        'icon' => 'fa-user-group',
        'icon_color' => 'icon-blue',
        'badge_color' => 'perc-blue',
        'dot_color' => '#64748b',
        'rows' => [['label' => 'Belum dikelompokkan', 'count' => $otherCount]],
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
$scopeLabels = ['asn' => 'ASN', 'blud' => 'BLUD', 'mitra' => 'Mitra', 'other' => 'Lainnya'];
$activeScopeLabel = $scopeLabels[$dashboardScope ?? 'all'] ?? '';
$scopeFilterUrl = static function (string $scope) use ($selectedUnit, $dashboardScope): string {
    $query = http_build_query(array_filter([
        'filter_bangsal' => $selectedUnit ?? '',
        'scope' => ($dashboardScope ?? 'all') === $scope ? null : $scope,
    ], static fn ($value): bool => $value !== null && $value !== ''));
    return base_url('/') . ($query !== '' ? '?' . $query : '');
};
?>
<style>
    .dashboard-title-row { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:24px; }
    .dashboard-title-row .page-title { margin:0; }
    .dashboard-title-row select { min-width:198px; }
    .dashboard-title-row { animation:dashboard-fade-up .38s ease-out both; }
    .dashboard-overview { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:24px; align-items:stretch; margin-bottom:24px; }
    .dashboard-overview { animation:dashboard-fade-up .48s .08s ease-out both; }
    .dashboard-summary-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); grid-template-rows:minmax(315px,auto) minmax(254px,auto); gap:24px; }
    .dashboard-summary-grid .card { height:100%; min-height:254px; transition:opacity .32s ease; }
    .dashboard-summary-grid .card[hidden], .dashboard-filter-banner[hidden] { display:none !important; }
    .dashboard-summary-grid .card:nth-child(-n+2) { min-height:315px; }
    .dashboard-overview.is-filtered { --focused-dashboard-height:340px; align-items:start; }
    .dashboard-overview.is-filtered .dashboard-summary-grid { align-self:start; grid-template-columns:minmax(0,1fr); grid-template-rows:minmax(0,1fr); height:var(--focused-dashboard-height); }
    .dashboard-overview.is-filtered .dashboard-summary-grid .card { box-sizing:border-box; height:var(--focused-dashboard-height); min-height:0; }
    .dashboard-overview.is-filtered .dashboard-distribution-card { align-self:start; box-sizing:border-box; height:var(--focused-dashboard-height); max-height:var(--focused-dashboard-height); min-height:0; overflow:hidden; }
    .dashboard-overview.is-filtered .dashboard-distribution-chart { height:auto; min-height:0; }
    .dashboard-filter-card { color:inherit; text-decoration:none; transition:box-shadow .18s ease, transform .18s ease; }
    .dashboard-filter-card:hover, .dashboard-filter-card:focus-visible { box-shadow:0 8px 24px rgba(15,23,42,.12); outline:2px solid var(--primary); outline-offset:2px; transform:translateY(-2px); }
    .dashboard-distribution-card { min-height:594px; display:flex; flex-direction:column; }
    .dashboard-distribution-card .card-heading { flex:0 0 auto; }
    .dashboard-distribution-chart { height:auto; min-height:0; flex:1; }
    .job-summary-card { padding:26px 28px; }
    .job-summary-card { animation:dashboard-fade-up .48s .16s ease-out both; }
    @keyframes dashboard-fade-up { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
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
        .dashboard-overview.is-filtered { --focused-dashboard-height:320px; }
        .dashboard-summary-grid .card:nth-child(-n+2) { min-height:280px; }
        .dashboard-distribution-card { min-height:500px; }
        .dashboard-distribution-chart { min-height:400px; }
    }
    @media(max-width:700px) {
        .dashboard-title-row { align-items:stretch; flex-direction:column; }
        .dashboard-title-row select { width:100%; }
        .dashboard-summary-grid { grid-template-columns:1fr; grid-template-rows:none; }
        .dashboard-overview.is-filtered { --focused-dashboard-height:280px; }
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
    @media(prefers-reduced-motion:reduce) {
        .dashboard-title-row, .dashboard-overview, .job-summary-card { animation:none; }
    }
</style>

<div class="dashboard-title-row">
    <h1 class="page-title">Dashboard Utama</h1>
    <?php if (! empty($unitOptions)): ?>
        <form method="get" action="<?= base_url('/') ?>">
            <?php if (($dashboardScope ?? 'all') !== 'all'): ?><input type="hidden" name="scope" value="<?= esc($dashboardScope) ?>"><?php endif; ?>
            <select class="form-control" name="filter_bangsal" onchange="this.form.submit()" aria-label="Filter bangsal atau unit">
                <option value="">Semua Bangsal/Unit</option>
                <?php foreach ($unitOptions as $unit): ?>
                    <option value="<?= esc($unit['name']) ?>" <?= ($selectedUnit ?? '') === $unit['name'] ? 'selected' : '' ?>><?= esc($unit['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    <?php endif; ?>
</div>

    <div class="main-card dashboard-filter-banner" id="dashboard-filter-banner" <?= ($dashboardScope ?? 'all') === 'all' ? 'hidden' : '' ?> style="margin-bottom:24px;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <span><i class="fa-solid fa-filter" style="color:var(--primary);margin-right:6px;"></i>Dashboard sedang menampilkan data <?= esc($activeScopeLabel) ?> saja.</span>
        <a class="btn btn-outline btn-sm" id="show-all-dashboard" href="<?= base_url('/' . (($selectedUnit ?? '') !== '' ? '?' . http_build_query(['filter_bangsal' => $selectedUnit]) : '')) ?>">Tampilkan semua pegawai</a>
    </div>

<?php if (empty($can_view_employees)): ?>
    <div class="main-card" style="margin-bottom:24px;color:var(--text-muted);">Akun Anda belum memiliki hak untuk melihat data pegawai pada dashboard.</div>
<?php endif; ?>

<div class="dashboard-overview <?= ($dashboardScope ?? 'all') !== 'all' ? 'is-filtered' : '' ?>">
    <div class="dashboard-summary-grid">
        <div class="card" id="total-employee-card" <?= ($dashboardScope ?? 'all') !== 'all' ? 'hidden' : '' ?>>
            <div class="card-header">
                <span class="card-title">Total Pegawai</span>
                <div class="card-icon icon-blue"><i class="fa-solid fa-users-viewfinder"></i></div>
            </div>
            <div class="card-value">
                <span class="dashboard-count"><?= number_format($total) ?></span>
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
            <?php $groupScope = ['Total ASN' => 'asn', 'Total BLUD' => 'blud', 'Total Mitra' => 'mitra', 'Total Lainnya' => 'other'][$group['title']] ?? null; ?>
            <?php if ($groupScope !== null): ?>
            <a class="card dashboard-filter-card dashboard-group-card" data-scope="<?= esc($groupScope) ?>" href="<?= esc($scopeFilterUrl($groupScope)) ?>" aria-label="Tampilkan dashboard <?= esc($group['title']) ?> saja" title="Klik untuk memfilter dashboard ke <?= esc($group['title']) ?>" <?= ($dashboardScope ?? 'all') !== 'all' && $dashboardScope !== $groupScope ? 'hidden' : '' ?>>
            <?php else: ?>
            <div class="card">
            <?php endif; ?>
                <div class="card-header">
                    <span class="card-title"><?= esc($group['title']) ?></span>
                    <div class="card-icon <?= esc($group['icon_color']) ?>"><i class="fa-solid <?= esc($group['icon']) ?>"></i></div>
                </div>
                <div class="card-value">
                    <span class="dashboard-count"><?= number_format($group['total']) ?></span>
                    <span class="card-percentage <?= esc($group['badge_color']) ?>"><?= $group['share'] ?>%</span>
                </div>
                <div class="card-details">
                    <?php foreach ($group['rows'] as $row): ?>
                        <div class="detail-row" data-employment-category="<?= esc(['CPNS' => 'cpns', 'PNS' => 'pns', 'PPPK (Penuh)' => 'pppk_full', 'PPPK (Paruh)' => 'pppk_part', 'Tetap' => 'blud_permanent', 'Kontrak' => 'blud_contract', 'Penuh Waktu' => 'mitra_full', 'Paruh Waktu' => 'mitra_part', 'Belum dikelompokkan' => 'other'][$row['label']] ?? '') ?>">
                            <span><i class="fa-solid fa-circle" style="font-size:8px;color:<?= esc($group['dot_color']) ?>;margin-right:6px;"></i><?= esc($row['label']) ?></span>
                            <span><?= number_format($row['count']) ?> <small>(<?= $percent($row['count'], $group['total']) ?>%)</small></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php if ($groupScope !== null): ?>
            </a>
            <?php else: ?>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <section class="main-card dashboard-distribution-card">
        <div class="card-heading">Distribusi Pegawai Kerja<?= $activeScopeLabel !== '' ? ' (' . esc($activeScopeLabel) . ')' : '' ?></div>
        <div class="chart-container dashboard-distribution-chart"><canvas id="employeeChart"></canvas></div>
    </section>
</div>

<section class="main-card job-summary-card">
    <div class="job-summary-heading">
        <h2>Rincian Pegawai Berdasarkan Jabatan &amp; Jenjang<?= $activeScopeLabel !== '' ? ' (' . esc($activeScopeLabel) . ')' : '' ?></h2>
        <label class="job-search" for="jobSearch">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="jobSearch" type="search" placeholder="Cari nama jabatan..." autocomplete="off">
        </label>
    </div>

        <p class="dashboard-empty" id="jobBreakdownEmpty" <?= $jobBreakdown !== [] ? 'hidden' : '' ?>>Belum ada data jabatan yang dapat ditampilkan.</p>
        <div class="job-chart-container" id="jobBreakdownChartWrap" <?= $jobBreakdown === [] ? 'hidden' : '' ?>><canvas id="jobBreakdownChart"></canvas></div>
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
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const scopeData = <?= json_encode($scopedDashboardData, $safeJsonFlags) ?>;
    const scopeLabels = { all: 'Semua Pegawai', asn: 'ASN', blud: 'BLUD', mitra: 'Mitra', other: 'Lainnya' };
    const categoriesByScope = {
        asn: ['cpns', 'pns', 'pppk_full', 'pppk_part'],
        blud: ['blud_permanent', 'blud_contract'],
        mitra: ['mitra_full', 'mitra_part'],
        other: ['other']
    };
    const categoryLabels = {
        cpns: 'CPNS', pns: 'PNS', pppk_full: 'PPPK (Penuh)', pppk_part: 'PPPK (Paruh)',
        blud_permanent: 'BLUD Tetap', blud_contract: 'BLUD Kontrak',
        mitra_full: 'Mitra (Penuh)', mitra_part: 'Mitra (Paruh)', other: 'Lainnya'
    };
    const categoryColors = {
        cpns: 'rgba(16, 185, 129, 0.9)', pns: 'rgba(16, 185, 129, 0.75)',
        pppk_full: 'rgba(16, 185, 129, 0.55)', pppk_part: 'rgba(16, 185, 129, 0.35)',
        blud_permanent: 'rgba(139, 92, 246, 0.9)', blud_contract: 'rgba(139, 92, 246, 0.65)',
        mitra_full: 'rgba(245, 158, 11, 0.9)', mitra_part: 'rgba(245, 158, 11, 0.6)',
        other: 'rgba(100, 116, 139, 0.65)'
    };
    let currentScope = <?= json_encode($dashboardScope, $safeJsonFlags) ?>;
    const employeeCanvas = document.getElementById('employeeChart');
    let employeeChart = null;
    if (employeeCanvas && typeof Chart !== 'undefined') {
        employeeChart = new Chart(employeeCanvas.getContext('2d'), {
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
                animation: { duration: 750, easing: 'easeOutQuart' },
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
    let jobs = <?= json_encode($jobBreakdown, $safeJsonFlags) ?>;
    let jobChart = null;
    if (jobCanvas && typeof Chart !== 'undefined') jobChart = new Chart(jobCanvas.getContext('2d'), {
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
            animation: { duration: 750, easing: 'easeOutQuart' },
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

    const jobCards = document.getElementById('jobBreakdownCards');
    function renderJobCards(items) {
        if (!jobCards) return;
        jobCards.replaceChildren();
        items.forEach(function (job) {
            const card = document.createElement('article');
            card.className = 'job-card';
            card.dataset.jobName = job.name.toLocaleLowerCase('id');
            const title = document.createElement('h3'); title.textContent = job.name; card.appendChild(title);
            const total = document.createElement('div'); total.className = 'job-card-total';
            const number = document.createElement('strong'); number.textContent = Number(job.total).toLocaleString('id-ID');
            const label = document.createElement('span'); label.textContent = 'Orang'; total.append(number, label); card.appendChild(total);
            if (job.details && job.details.length) {
                const details = document.createElement('div'); details.className = 'job-card-details';
                job.details.forEach(function (item) {
                    const row = document.createElement('div'); row.className = 'job-card-detail';
                    const name = document.createElement('span'); name.textContent = item.label;
                    const count = document.createElement('strong'); count.textContent = Number(item.count).toLocaleString('id-ID');
                    row.append(name, count); details.appendChild(row);
                });
                card.appendChild(details);
            }
            if (search && search.value && !card.dataset.jobName.includes(search.value.trim().toLocaleLowerCase('id'))) card.hidden = true;
            jobCards.appendChild(card);
        });
    }
    function updateJobChart(items) {
        jobs = items;
        const empty = document.getElementById('jobBreakdownEmpty');
        const chartWrap = document.getElementById('jobBreakdownChartWrap');
        if (empty) empty.hidden = items.length !== 0;
        if (chartWrap) chartWrap.hidden = items.length === 0;
        renderJobCards(items);
        if (!jobChart) return;
        const filteredJobs = search && search.value ? items.filter(job => job.name.toLocaleLowerCase('id').includes(search.value.trim().toLocaleLowerCase('id'))) : items;
        jobChart.data.labels = filteredJobs.map(job => job.name);
        jobChart.data.datasets[0].data = filteredJobs.map(job => job.expert);
        jobChart.data.datasets[1].data = filteredJobs.map(job => job.skilled);
        jobChart.data.datasets[2].data = filteredJobs.map(job => job.specific);
        jobChart.update('active');
    }
    function applyScope(scope, updateHistory) {
        const data = scopeData[scope] || scopeData.all;
        const overview = document.querySelector('.dashboard-overview');
        const cards = Array.from(document.querySelectorAll('.dashboard-group-card'));
        const totalCard = document.getElementById('total-employee-card');
        const banner = document.getElementById('dashboard-filter-banner');
        currentScope = scope;
        overview.classList.toggle('is-filtered', scope !== 'all');
        if (totalCard) totalCard.hidden = scope !== 'all';
        cards.forEach(card => {
            card.hidden = scope !== 'all' && card.dataset.scope !== scope;
        });
        // Let the browser apply the focused layout before Chart.js reads its container size.
        // Without this, the existing chart can keep the old tall canvas height until a refresh.
        void overview.offsetHeight;
        if (banner) {
            banner.hidden = scope === 'all';
            const label = banner.querySelector('span');
            if (label) label.innerHTML = '<i class="fa-solid fa-filter" style="color:var(--primary);margin-right:6px;"></i>Dashboard sedang menampilkan data ' + scopeLabels[scope] + ' saja.';
        }
        document.querySelectorAll('.dashboard-summary-grid .dashboard-count').forEach(el => { el.textContent = Number(data.employee_count).toLocaleString('id-ID'); });
        const overallPercentage = totalCard && totalCard.querySelector('.card-percentage');
        if (overallPercentage) overallPercentage.textContent = data.employee_count > 0 ? '100%' : '0%';
        cards.forEach(card => {
            const cardScope = card.dataset.scope;
            const counts = scopeData[cardScope].employment_counts;
            const groupTotal = (categoriesByScope[cardScope] || []).reduce((sum, category) => sum + Number(counts[category] || 0), 0);
            const count = card.querySelector('.dashboard-count');
            const percentage = card.querySelector('.card-percentage');
            if (count) count.textContent = Number(groupTotal).toLocaleString('id-ID');
            if (percentage) percentage.textContent = (data.employee_count > 0 ? Math.round(groupTotal / data.employee_count * 100) : 0) + '%';
            card.querySelectorAll('[data-employment-category]').forEach(row => {
                const key = row.dataset.employmentCategory;
                const value = Number(counts[key] || 0);
                const valueNode = row.querySelector('span:last-child');
                if (valueNode) valueNode.innerHTML = value.toLocaleString('id-ID') + ' <small>(' + (groupTotal > 0 ? Math.round(value / groupTotal * 100) : 0) + '%)</small>';
            });
        });
        const chartLabels = Object.keys(categoryLabels).filter(key => Number(data.employment_counts[key] || 0) > 0 || (key === 'other' && Number(data.employment_counts.other || 0) > 0));
        if (employeeChart) {
            employeeChart.data.labels = chartLabels.map(key => categoryLabels[key]);
            employeeChart.data.datasets[0].data = chartLabels.map(key => Number(data.employment_counts[key] || 0));
            employeeChart.data.datasets[0].backgroundColor = chartLabels.map(key => categoryColors[key]);
            employeeChart.update('active');
        }
        const activeLabel = scopeLabels[scope] === 'Semua Pegawai' ? '' : ' (' + scopeLabels[scope] + ')';
        const distributionTitle = document.querySelector('.dashboard-distribution-card .card-heading');
        const jobTitle = document.querySelector('.job-summary-heading h2');
        if (distributionTitle) distributionTitle.textContent = 'Distribusi Pegawai Kerja' + activeLabel;
        if (jobTitle) jobTitle.textContent = 'Rincian Pegawai Berdasarkan Jabatan & Jenjang' + activeLabel;
        updateJobChart(data.job_breakdown || []);
        window.requestAnimationFrame(function () {
            window.requestAnimationFrame(function () {
                if (employeeChart) {
                    employeeChart.resize();
                    employeeChart.update('none');
                }
                if (jobChart) {
                    jobChart.resize();
                    jobChart.update('none');
                }
            });
        });
        if (updateHistory) {
            const url = new URL(window.location.href);
            if (scope === 'all') url.searchParams.delete('scope'); else url.searchParams.set('scope', scope);
            history.pushState({ scope }, '', url);
        }
    }
    document.querySelectorAll('.dashboard-group-card').forEach(card => card.addEventListener('click', function (event) {
        event.preventDefault();
        applyScope(currentScope === this.dataset.scope ? 'all' : this.dataset.scope, true);
    }));
    const resetScope = document.getElementById('show-all-dashboard');
    if (resetScope) resetScope.addEventListener('click', function (event) { event.preventDefault(); applyScope('all', true); });
    window.addEventListener('popstate', function () {
        const scope = new URL(window.location.href).searchParams.get('scope') || 'all';
        applyScope(scopeData[scope] ? scope : 'all', false);
    });

    if (search) {
        search.addEventListener('input', function () {
            const query = search.value.trim().toLocaleLowerCase('id');
            const filteredJobs = jobs.filter(job => job.name.toLocaleLowerCase('id').includes(query));
            document.querySelectorAll('#jobBreakdownCards .job-card').forEach(function (card) {
                card.hidden = !card.dataset.jobName.includes(query);
            });
            if (jobChart) {
                jobChart.data.labels = filteredJobs.map(job => job.name);
                jobChart.data.datasets[0].data = filteredJobs.map(job => job.expert);
                jobChart.data.datasets[1].data = filteredJobs.map(job => job.skilled);
                jobChart.data.datasets[2].data = filteredJobs.map(job => job.specific);
                jobChart.update('active');
            }
        });
    }
    if (currentScope !== 'all') applyScope(currentScope, false);
});
</script>
<?= $this->endSection() ?>
