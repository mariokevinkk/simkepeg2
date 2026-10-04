<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM Kepegawaian</title>
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
            background: white;
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

        /* Views (SPA Tab Switching) */
        .view-section {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .view-section.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* UI Elements for new views */
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
        .btn-outline { border: 1px solid var(--border); background: white; color: var(--text-main); }
        .btn-outline:hover { background: #f8fafc; }

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
            }
            
            /* Compact header profile */
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
            /* Stack action header (Data Pegawai) */
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
                display: flex; /* Show the internal one in Data Pegawai */
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
            <i class="fa-solid fa-layer-group"></i> SIM Kepegawaian
        </div>
        
        <div class="nav-menu">
            <div class="nav-label">Main Menu</div>
            <a href="#" class="nav-item active" data-target="view-dashboard">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="#" class="nav-item" data-target="view-data">
                <i class="fa-solid fa-users"></i> Data Pegawai
            </a>
            <a href="#" class="nav-item" data-target="view-laporan">
                <i class="fa-solid fa-file-lines"></i> Laporan
            </a>
            
            <div class="nav-label">Settings</div>
            <a href="#" class="nav-item" data-target="view-pengaturan">
                <i class="fa-solid fa-gear"></i> Pengaturan
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    <input type="text" placeholder="Search data...">
                </div>
            </div>
            
            <div class="user-profile">
                <div class="action-icons">
                    <i class="fa-regular fa-bell notification-dot"></i>
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <div class="profile-btn">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=4361ee&color=fff" alt="User">
                    <div class="profile-info">
                        <span class="profile-name">Moni Roy</span>
                        <span class="profile-role">Admin</span>
                    </div>
                    <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
            </div>
        </header>

        <!-- Dynamic Views Container -->
        <div class="dashboard-container">

            <!-- ==============================
                 1. DASHBOARD VIEW
            =============================== -->
            <div id="view-dashboard" class="view-section active">
                <h1 class="page-title">Dashboard</h1>
                
                <!-- Summary Cards with Breakdowns inside -->
                <div class="summary-grid">
                    <!-- Total Card -->
                    <div class="card">
                        <div class="card-header">
                            <span class="card-title">Total Pegawai</span>
                            <div class="card-icon icon-blue">
                                <i class="fa-solid fa-users-viewfinder"></i>
                            </div>
                        </div>
                        <div class="card-value">
                            600
                            <span class="card-percentage perc-blue">100%</span>
                        </div>
                        <div class="card-details" style="border:none;">
                            <div class="detail-row" style="color:var(--text-muted); justify-content:center; text-align:center;">
                                Total keseluruhan Pegawai <br> di lingkup instansi
                            </div>
                        </div>
                    </div>

                    <!-- ASN Card -->
                    <div class="card">
                        <div class="card-header">
                            <span class="card-title">Total ASN</span>
                            <div class="card-icon icon-green">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                        </div>
                        <div class="card-value">
                            360
                            <span class="card-percentage perc-green">60%</span>
                        </div>
                        <!-- Detailed Breakdown Inside -->
                        <div class="card-details">
                            <div class="detail-row">
                                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> CPNS</span>
                                <span>180 <small>(50%)</small></span>
                            </div>
                            <div class="detail-row">
                                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> PNS</span>
                                <span>108 <small>(30%)</small></span>
                            </div>
                            <div class="detail-row">
                                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> PPPK (Penuh)</span>
                                <span>36 <small>(10%)</small></span>
                            </div>
                            <div class="detail-row">
                                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#10b981;margin-right:6px;"></i> PPPK (Paruh)</span>
                                <span>36 <small>(10%)</small></span>
                            </div>
                        </div>
                    </div>

                    <!-- BLUD Card -->
                    <div class="card">
                        <div class="card-header">
                            <span class="card-title">Total BLUD</span>
                            <div class="card-icon icon-purple">
                                <i class="fa-solid fa-user-doctor"></i>
                            </div>
                        </div>
                        <div class="card-value">
                            180
                            <span class="card-percentage perc-purple">30%</span>
                        </div>
                        <!-- Detailed Breakdown Inside -->
                        <div class="card-details">
                            <div class="detail-row">
                                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#9333ea;margin-right:6px;"></i> Tetap</span>
                                <span>108 <small>(60%)</small></span>
                            </div>
                            <div class="detail-row">
                                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#9333ea;margin-right:6px;"></i> Kontrak</span>
                                <span>72 <small>(40%)</small></span>
                            </div>
                        </div>
                    </div>

                    <!-- Mitra Card -->
                    <div class="card">
                        <div class="card-header">
                            <span class="card-title">Total Mitra</span>
                            <div class="card-icon icon-orange">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                        </div>
                        <div class="card-value">
                            60
                            <span class="card-percentage perc-orange">10%</span>
                        </div>
                        <!-- Detailed Breakdown Inside -->
                        <div class="card-details">
                            <div class="detail-row">
                                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#f59e0b;margin-right:6px;"></i> Penuh Waktu</span>
                                <span>36 <small>(60%)</small></span>
                            </div>
                            <div class="detail-row">
                                <span><i class="fa-solid fa-circle" style="font-size:8px;color:#f59e0b;margin-right:6px;"></i> Paruh Waktu</span>
                                <span>24 <small>(40%)</small></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Area -->
                <div class="main-card">
                    <div class="card-heading">
                        Grafik Distribusi Pegawai
                    </div>
                    <div class="chart-container">
                        <canvas id="employeeChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- /Dashboard View -->

            <!-- ==============================
                 2. DATA PEGAWAI VIEW
            =============================== -->
            <div id="view-data" class="view-section">
                <div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h1 class="page-title" style="margin: 0;">Data Pegawai</h1>
                    <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Pegawai</button>
                </div>
                
                <div class="main-card" style="padding: 0; overflow: hidden;">
                    <div class="filter-row" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
                        <div class="search-bar" style="width: 100%; max-width: 300px; background: white; border: 1px solid var(--border);">
                            <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            <input type="text" placeholder="Cari nama atau NIP...">
                        </div>
                        <button class="btn btn-outline"><i class="fa-solid fa-filter"></i> Filter Data</button>
                    </div>
                    
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>NAMA & NIP</th>
                                    <th>JENIS PEGAWAI</th>
                                    <th>STATUS KEPEGAWAIAN</th>
                                    <th>JABATAN</th>
                                    <th>STATUS</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600; color: var(--text-main);">Ahmad Hidayat</div>
                                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">198501012010011001</div>
                                    </td>
                                    <td><span style="font-weight: 500;">ASN</span></td>
                                    <td>PNS</td>
                                    <td>Kepala Seksi Pelayanan</td>
                                    <td><span class="status-badge status-active">Aktif</span></td>
                                    <td>
                                        <i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer; margin-right: 12px;"></i>
                                        <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right: 12px;"></i>
                                        <i class="fa-solid fa-trash" style="color:var(--danger); cursor:pointer;"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600; color: var(--text-main);">Siti Aminah</div>
                                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">199003022015022002</div>
                                    </td>
                                    <td><span style="font-weight: 500;">ASN</span></td>
                                    <td>CPNS</td>
                                    <td>Staf Administrasi</td>
                                    <td><span class="status-badge status-active">Aktif</span></td>
                                    <td>
                                        <i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer; margin-right: 12px;"></i>
                                        <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right: 12px;"></i>
                                        <i class="fa-solid fa-trash" style="color:var(--danger); cursor:pointer;"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600; color: var(--text-main);">Budi Santoso</div>
                                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">BLUD2023001</div>
                                    </td>
                                    <td><span style="font-weight: 500;">BLUD</span></td>
                                    <td>Tetap</td>
                                    <td>Perawat Pelaksana</td>
                                    <td><span class="status-badge status-active">Aktif</span></td>
                                    <td>
                                        <i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer; margin-right: 12px;"></i>
                                        <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right: 12px;"></i>
                                        <i class="fa-solid fa-trash" style="color:var(--danger); cursor:pointer;"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600; color: var(--text-main);">Dewi Lestari</div>
                                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">MTR2022099</div>
                                    </td>
                                    <td><span style="font-weight: 500;">Mitra</span></td>
                                    <td>Penuh Waktu</td>
                                    <td>Petugas Keamanan</td>
                                    <td><span class="status-badge status-active">Aktif</span></td>
                                    <td>
                                        <i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer; margin-right: 12px;"></i>
                                        <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right: 12px;"></i>
                                        <i class="fa-solid fa-trash" style="color:var(--danger); cursor:pointer;"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex-mobile-stack" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); color: var(--text-muted); font-size: 13px;">
                        <span>Menampilkan 1-4 dari 600 pegawai</span>
                        <div style="display: flex; gap: 8px;">
                            <button class="btn btn-outline" style="padding: 6px 12px;">Sebelummya</button>
                            <button class="btn btn-primary" style="padding: 6px 12px;">1</button>
                            <button class="btn btn-outline" style="padding: 6px 12px;">2</button>
                            <button class="btn btn-outline" style="padding: 6px 12px;">Selanjutnya</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Data Pegawai View -->

            <!-- ==============================
                 3. LAPORAN VIEW
            =============================== -->
            <div id="view-laporan" class="view-section">
                <h1 class="page-title">Laporan & Unduhan</h1>
                
                <div class="summary-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                    <div class="card">
                        <div class="card-icon icon-green" style="margin-bottom: 12px; width: 56px; height: 56px; font-size: 24px;">
                            <i class="fa-solid fa-file-excel"></i>
                        </div>
                        <h3 style="font-size: 18px; margin-bottom: 8px;">Rekap Data Pegawai</h3>
                        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.5;">Unduh rekapitulasi seluruh data pegawai (ASN, BLUD, Mitra) secara lengkap dalam format Microsoft Excel (.xlsx).</p>
                        <button class="btn btn-outline" style="width: 100%; justify-content: center;">
                            <i class="fa-solid fa-download"></i> Unduh Excel
                        </button>
                    </div>

                    <div class="card">
                        <div class="card-icon icon-orange" style="margin-bottom: 12px; width: 56px; height: 56px; font-size: 24px;">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <h3 style="font-size: 18px; margin-bottom: 8px;">Laporan Statistik Distribusi</h3>
                        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.5;">Cetak laporan analitik berisi grafik dan angka persebaran pegawai berdasarkan status dan posisi dalam format PDF.</p>
                        <button class="btn btn-outline" style="width: 100%; justify-content: center;">
                            <i class="fa-solid fa-print"></i> Cetak PDF
                        </button>
                    </div>
                </div>
            </div>
            <!-- /Laporan View -->

            <!-- ==============================
                 4. PENGATURAN VIEW
            =============================== -->
            <div id="view-pengaturan" class="view-section">
                <h1 class="page-title">Pengaturan Sistem</h1>
                
                <div class="main-card" style="max-width: 800px;">
                    <h3 style="font-size: 18px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">Profil Instansi</h3>
                    
                    <div style="display: grid; gap: 20px;">
                        <div class="settings-grid-2">
                            <div>
                                <label class="form-label">Nama Instansi</label>
                                <input type="text" class="form-control" value="Dinas Kesehatan Daerah">
                            </div>
                            <div>
                                <label class="form-label">Kode Instansi</label>
                                <input type="text" class="form-control" value="DKD-001">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" rows="3">Jl. Merdeka No. 123, Pusat Kota, Provinsi X</textarea>
                        </div>

                        <div class="settings-grid-2">
                            <div>
                                <label class="form-label">Email Kontak</label>
                                <input type="email" class="form-control" value="info@instansi.go.id">
                            </div>
                            <div>
                                <label class="form-label">Telepon</label>
                                <input type="text" class="form-control" value="(021) 1234567">
                            </div>
                        </div>

                        <div style="margin-top: 16px; display: flex; justify-content: flex-end;">
                            <button class="btn btn-primary" style="width: 100%; max-width: 250px; justify-content: center;">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Pengaturan View -->

        </div>
    </main>

    <script>
        // --- Mobile Menu Logic ---
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleSidebar);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }

        // --- Chart Logic ---
        const chartData = {
            labels: ['CPNS', 'PNS', 'PPPK Penuh', 'PPPK Paruh', 'BLUD Tetap', 'BLUD Kontrak', 'Mitra Penuh', 'Mitra Paruh'],
            datasets: [{
                label: 'Jumlah Pegawai',
                data: [180, 108, 36, 36, 108, 72, 36, 24],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)', 'rgba(16, 185, 129, 0.6)', 'rgba(16, 185, 129, 0.4)', 'rgba(16, 185, 129, 0.2)',
                    'rgba(147, 51, 234, 0.8)', 'rgba(147, 51, 234, 0.5)',
                    'rgba(245, 158, 11, 0.8)', 'rgba(245, 158, 11, 0.5)'
                ],
                borderRadius: 8,
                borderSkipped: false,
            }]
        };

        let employeeChartInstance = null;

        function initChart() {
            const ctx = document.getElementById('employeeChart');
            if (!ctx) return;
            
            if (employeeChartInstance) {
                employeeChartInstance.destroy();
            }

            employeeChartInstance = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b', padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 14 },
                            callbacks: {
                                label: function(context) { return ` ${context.raw} Pegawai`; }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e2e8f0', drawBorder: false },
                            ticks: { font: { family: 'Inter' }, color: '#64748b' }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { family: 'Inter', size: 11 }, color: '#64748b' }
                        }
                    }
                }
            });
        }

        // Initialize Chart on first load
        initChart();

        // --- Single Page App (SPA) Tab Switching Logic ---
        document.addEventListener("DOMContentLoaded", () => {
            const navItems = document.querySelectorAll('.nav-menu .nav-item');
            const viewSections = document.querySelectorAll('.view-section');

            navItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    // On mobile, close sidebar when clicking a link
                    if (window.innerWidth <= 900) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    }
                    
                    // Remove active class from all tabs
                    navItems.forEach(nav => nav.classList.remove('active'));
                    item.classList.add('active');

                    // Hide all sections
                    viewSections.forEach(section => section.classList.remove('active'));

                    // Show target section
                    const targetId = item.getAttribute('data-target');
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        targetElement.classList.add('active');
                        
                        // Re-initialize chart if navigating back to dashboard 
                        if (targetId === 'view-dashboard') {
                            setTimeout(() => { initChart(); }, 100);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
