<?php
helper('security');
$authUser = session()->get('auth_user') ?? [];
$authDisplayName = $authUser['name'] ?? 'Pengguna';
$authRoleLabel = match ($authUser['role'] ?? '') {
    'pegawai' => 'Pegawai',
    'admin_unit' => 'Admin Unit',
    default => 'Super Admin',
};
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>" data-hash="<?= csrf_hash() ?>">
    <title>SIM Kepegawaian</title>
    <link rel="icon" type="image/png" href="<?= base_url('favicon-rsud.png') ?>">
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" sizes="any">
    <link rel="apple-touch-icon" href="<?= base_url('apple-touch-icon.png') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #e0e8ff;
            --primary-dark: #3a56d4;
            --secondary: #3f3f46;
            --bg-body: #f4f7fe;
            --bg-surface: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --sidebar-width: 260px;
        }

        /* Dark Theme Variables */
        [data-theme="dark"] {
            --bg-body: #0f172a;
            --bg-surface: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --primary-light: #1e3a8a;
        }
        
        [data-theme="dark"] .icon-blue { background: #1e3a8a; color: #60a5fa; }
        [data-theme="dark"] .icon-green { background: #064e3b; color: #34d399; }
        [data-theme="dark"] .icon-purple { background: #4c1d95; color: #a78bfa; }
        [data-theme="dark"] .icon-orange { background: #78350f; color: #fbbf24; }
        
        [data-theme="dark"] .perc-blue { background: #1e3a8a; color: #60a5fa; }
        [data-theme="dark"] .perc-green { background: #064e3b; color: #34d399; }
        [data-theme="dark"] .perc-purple { background: #4c1d95; color: #a78bfa; }
        [data-theme="dark"] .perc-orange { background: #78350f; color: #fbbf24; }

        [data-theme="dark"] .search-bar { background: #334155; }
        [data-theme="dark"] .search-bar:focus-within { background: #1e293b; }
        
        [data-theme="dark"] .profile-dropdown-menu { background: #1e293b; border-color: var(--border); }
        [data-theme="dark"] .dropdown-header { background: #0f172a; border-color: var(--border); }
        [data-theme="dark"] .dropdown-item:hover { background: #334155; }
        
        [data-theme="dark"] .nav-item:hover { background: #334155; }
        [data-theme="dark"] .data-table th { background-color: #334155; color: #f8fafc; }
        [data-theme="dark"] .data-table tr:hover td { background-color: #334155; }
        [data-theme="dark"] .form-control { background-color: #1e293b; border-color: #334155; color: #f8fafc; }
        [data-theme="dark"] .profile-btn:hover { background: #334155; }
        [data-theme="dark"] .btn-outline { border-color: #334155; color: #f8fafc; }
        [data-theme="dark"] .btn-outline:hover { background: #334155; }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 9;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
            transition: transform 0.3s ease;
        }

        .logo {
            padding: 24px;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
        }
        
        .logo i {
            background: var(--primary);
            color: white;
            padding: 8px;
            border-radius: 10px;
            font-size: 18px;
        }

        .nav-menu {
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
            margin-top: 16px;
            margin-bottom: 8px;
            padding-left: 16px;
            letter-spacing: 0.5px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .nav-item.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }
        
        .nav-item i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-width));
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        .header {
            height: 80px;
            background-color: var(--bg-surface);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 5;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
        }

        .mobile-menu-btn {
            display: none;
            font-size: 22px;
            cursor: pointer;
            color: var(--text-main);
            background: none;
            border: none;
            padding: 8px;
        }

        .search-bar {
            background: #f1f5f9;
            border-radius: 20px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            max-width: 350px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }
        
        .search-bar:focus-within {
            background: var(--bg-surface);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .search-bar input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            color: var(--text-main);
            font-size: 14px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .action-icons {
            display: flex;
            gap: 16px;
            color: var(--text-muted);
        }
        
        .action-icons i {
            font-size: 18px;
            cursor: pointer;
            transition: color 0.2s ease;
            position: relative;
        }
        
        .action-icons i:hover {
            color: var(--primary);
        }
        
        .notification-dot::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid white;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        
        .profile-btn:hover {
            background: #f8fafc;
        }

        .profile-btn img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .profile-info {
            display: flex;
            flex-direction: column;
        }
        
        .profile-name {
            font-size: 14px;
            font-weight: 600;
        }
        
        .profile-role {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Profile Dropdown */
        .profile-dropdown-container {
            position: relative;
        }
        .profile-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 220px;
            margin-top: 10px;
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 50;
        }
        .profile-dropdown-menu.show {
            display: flex;
        }
        .dropdown-item {
            padding: 12px 16px;
            color: var(--text-main);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: background 0.2s;
        }
        .dropdown-item:hover {
            background: #f8fafc;
            color: var(--primary);
        }
        .dropdown-header {
            padding: 12px 16px;
            background: #f8fafc;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .dashboard-container {
            padding: 32px;
            width: 100%;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
            align-items: start;
        }

        .card {
            background-color: var(--bg-surface);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -4px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            color: var(--text-muted);
            font-size: 15px;
            font-weight: 600;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-blue { background: #e0e8ff; color: #4361ee; }
        .icon-green { background: #dcfce7; color: #10b981; }
        .icon-purple { background: #f3e8ff; color: #9333ea; }
        .icon-orange { background: #fef3c7; color: #f59e0b; }

        .card-value {
            font-size: 34px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .card-percentage {
            font-size: 14px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 20px;
        }
        
        .perc-blue { background: #e0e8ff; color: #4361ee; }
        .perc-green { background: #dcfce7; color: #10b981; }
        .perc-purple { background: #f3e8ff; color: #9333ea; }
        .perc-orange { background: #fef3c7; color: #f59e0b; }

        /* Detail Rows inside Cards */
        .card-details {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px dashed var(--border);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--text-main);
            font-weight: 500;
            align-items: center;
        }

        .detail-row span:last-child {
            font-weight: 700;
            font-size: 14px;
        }

        .detail-row small {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 11px;
            margin-left: 4px;
        }

        /* Chart Area */
        .main-card {
            background-color: var(--bg-surface);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            width: 100%;
        }

        .card-heading {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .chart-container {
            position: relative;
            height: 380px;
            width: 100%;
        }

        /* UI Elements */
        .btn {
            padding: 10px 18px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-outline { border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); }
        .btn-outline:hover { background: var(--bg-body); }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .data-table th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table tr:hover td {
            background-color: #f8fafc;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-active { background: #dcfce7; color: #10b981; }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: Inter;
            font-size: 14px;
            color: var(--text-main);
            outline: none;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary);
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .settings-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Responsive Media Queries */
        @media (max-width: 900px) {
            .mobile-menu-btn {
                display: block;
            }
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .header {
                padding: 0 20px;
                left: 0;
            }
            .welcome-text {
                display: none;
            }/* Compact header profile */
            .profile-info, .profile-btn .fa-chevron-down {
                display: none;
            }
            .profile-btn {
                padding: 6px;
                border-radius: 50%;
            }

            .settings-grid-2 {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 600px) {
            .dashboard-container {
                padding: 16px;
            }
            .search-bar {
                display: none; /* Hide on very small screens to save space */
            }
            .action-icons {
                gap: 12px;
            }
            .page-title {
                font-size: 20px;
            }
            /* Stack action header */
            .flex-mobile-stack {
                flex-direction: column;
                align-items: stretch !important;
                gap: 16px;
            }
            .flex-mobile-stack .btn {
                width: 100%;
                justify-content: center;
            }
            .filter-row {
                flex-direction: column;
                gap: 16px;
            }
            .filter-row .search-bar {
                display: flex;
                max-width: 100%;
            }
            .filter-row .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <img src="<?= base_url('logo-rs.png') ?>" alt="Logo RS Jogja" style="height: 40px; width: auto; object-fit: contain;"> 
            <div style="line-height: 1.2;">
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600;">SIM Kepegawaian</div>
                <div style="font-size: 16px; color: var(--text-main);">RSUD Kota Yogyakarta</div>
            </div>
        </div>
        
        <div class="nav-menu">
            <div class="nav-label">Main Menu</div>
            
            <?php if(isset($role) && $role === 'pegawai'): ?>
            <?php foreach (\App\Libraries\EmployeeProfileData::profilePages() as $pageKey => $pageInfo): ?>
            <a href="<?= base_url('/' . $pageKey) ?>" class="nav-item <?= ($active_menu == $pageKey) ? 'active' : '' ?>">
                <i class="fa-solid <?= esc($pageInfo['icon']) ?>"></i> <?= esc($pageInfo['title']) ?>
            </a>
            <?php endforeach; ?>
            <?php else: ?>
            <a href="<?= base_url('/?role=' . (isset($role) ? $role : 'superadmin')) ?>" class="nav-item <?= ($active_menu == 'dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            
            <?php if((!isset($role) || $role === 'superadmin') || !empty($authUser['permissions']['documents.manage'])): ?>
            <a href="<?= base_url('/aktivitas?role=superadmin') ?>" class="nav-item <?= ($active_menu == 'aktivitas') ? 'active' : '' ?>" style="display: flex; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <i class="fa-solid fa-clipboard-check" style="font-size: 18px; width: 24px; text-align: center;"></i> 
                    <span>Aktivitas</span>
                </div>
            </a>
            <?php endif; ?>

            <?php if($role !== 'direktur'): ?>
            <a href="<?= base_url('/data-pegawai?role='.$role) ?>" class="nav-item <?= ($active_menu == 'data-pegawai') ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i> Data Pegawai
            </a>
            <?php endif; ?>
            
            <?php if($role !== 'pegawai' && $role !== 'direktur'): ?>
            <a href="<?= base_url('/pegawai-nonaktif') ?>" class="nav-item <?= in_array($active_menu ?? '', ['pegawai-nonaktif', 'pensiun'], true) ? 'active' : '' ?>">
                <i class="fa-solid fa-user-slash"></i> Data Pegawai Nonaktif
            </a>
            <?php endif; ?>
            
            <div class="nav-label">Settings</div>
            <?php if(! isset($role) || $role === 'superadmin'): ?>
            <a href="<?= base_url('/manajemen-jabatan') ?>" class="nav-item <?= ($active_menu == 'manajemen-jabatan') ? 'active' : '' ?>">
                <i class="fa-solid fa-briefcase-medical"></i> Manajemen Jabatan
            </a>
            <?php endif; ?>
            <?php if(!isset($role) || $role === 'superadmin'): ?>
            <a href="<?= base_url('/manajemen-bangsal?role='.$role) ?>" class="nav-item <?= ($active_menu == 'manajemen-bangsal') ? 'active' : '' ?>">
                <i class="fa-solid fa-hospital"></i> Manajemen Unit
            </a>
            <a href="<?= base_url('/manajemen-admin?role='.$role) ?>" class="nav-item <?= ($active_menu == 'manajemen-admin') ? 'active' : '' ?>">
                <i class="fa-solid fa-user-shield"></i> Manajemen Admin
            </a>
            <a href="<?= base_url('/manajemen-akun?role='.$role) ?>" class="nav-item <?= ($active_menu == 'manajemen-akun') ? 'active' : '' ?>">
                <i class="fa-solid fa-users-gear"></i> Manajemen Akun
            </a>
            <?php endif; ?>
            <?php if($role !== 'direktur'): ?>
            <a href="<?= base_url('/pengaturan?role='.$role) ?>" class="nav-item <?= ($active_menu == 'pengaturan') ? 'active' : '' ?>">
                <i class="fa-solid fa-gear"></i> <?= (isset($role) && $role === 'admin') ? 'Profil Bangsal' : 'Pengaturan' ?>
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="header">
            <div class="header-left" style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="welcome-text" style="font-size: 15px; font-weight: 500; color: var(--text-main);">
                    Halo, Selamat Datang <strong><?= esc($authDisplayName) ?></strong>
                    <span style="color: var(--text-muted); font-size: 13px;">(<?= esc($authRoleLabel) ?>)</span>
                </div>
                
            </div>
            
            <div class="user-profile">

                <button class="theme-toggle" id="themeToggle" style="background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 20px; margin-right: 8px;">
                    <i class="fa-solid fa-moon"></i>
                </button>
                <div class="profile-dropdown-container">
                    <div class="profile-btn" id="profileDropdownBtn" style="cursor: pointer;">
                        <img src="https://ui-avatars.com/api/?name=<?= rawurlencode($authDisplayName) ?>&background=4361ee&color=fff" alt="User">
                        <div class="profile-info">
                            <span class="profile-name"><?= esc($authDisplayName) ?></span>
                            <span class="profile-role"><?= esc($authRoleLabel) ?></span>
                        </div>
                        <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                    </div>
                    
                    <div class="profile-dropdown-menu" id="profileDropdownMenu">
                        <div class="dropdown-header"><?= esc($authDisplayName) ?></div>
                        <a class="dropdown-item" href="<?= base_url('/ubah-password') ?>">
                            <i class="fa-solid fa-key"></i> Ubah Password
                        </a>
                        <form method="post" action="<?= base_url('/logout') ?>" style="padding: 10px 16px;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline" style="width: 100%; justify-content: center;">
                                <i class="fa-solid fa-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Views Container -->
        <div class="dashboard-container">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <script>
        // Set initial theme before page load prevents flickering
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }

        // --- Mobile Menu Logic ---
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const body = document.body;

        function toggleMenu() {
            console.log("toggleMenu called!");
            if(sidebar) sidebar.classList.toggle('show');
            if(sidebarOverlay) sidebarOverlay.classList.toggle('show');
            if(sidebar && body) body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }

        if(mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleMenu);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleMenu);

        // --- Profile Dropdown Logic ---
        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdownMenu = document.getElementById('profileDropdownMenu');
        
        if (profileDropdownBtn && profileDropdownMenu) {
            profileDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdownMenu.classList.toggle('show');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            if (profileDropdownMenu) profileDropdownMenu.classList.remove('show');
        });

        // Theme Toggle Functionality
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = themeToggle.querySelector('i');
            
            // Set initial icon based on theme
            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }

            themeToggle.addEventListener('click', () => {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                if (isDark) {
                    document.documentElement.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                } else {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                }
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
