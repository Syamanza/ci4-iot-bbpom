<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Command Center SPBE BBPOM Bandung - Dashboard monitoring evaluasi SPBE, aset infrastruktur TI, dan IoT real-time.">
    <meta name="author" content="BBPOM di Bandung">
    <meta name="theme-color" content="#f0f4f8">
    <title>Command Center SPBE — BBPOM Bandung</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- ============================================================
         SIDEBAR NAVIGATION
         ============================================================ -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <!-- Logo / Branding -->
            <div class="sidebar-logo">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                    <rect width="36" height="36" rx="10" fill="url(#logoGrad)"/>
                    <path d="M10 18L16 12L22 18L16 24L10 18Z" fill="white" opacity="0.9"/>
                    <path d="M16 12L22 18L28 12L22 6L16 12Z" fill="white" opacity="0.6"/>
                    <path d="M16 24L22 18L28 24L22 30L16 24Z" fill="white" opacity="0.4"/>
                    <defs>
                        <linearGradient id="logoGrad" x1="0" y1="0" x2="36" y2="36">
                            <stop stop-color="#1565C0"/>
                            <stop offset="1" stop-color="#2E7D32"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="sidebar-title">
                    <span class="title-main">SPBE</span>
                    <span class="title-sub">BBPOM Bandung</span>
                </div>
            </div>
        </div>

        <ul class="sidebar-menu" role="menu">
            <!-- Menu 1: Evaluasi SPBE -->
            <li>
                <a href="#" class="menu-item active" data-page="page-evaluasi">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Evaluasi SPBE
                </a>
            </li>

            <!-- Menu 2: Aset & Infrastruktur TI -->
            <li role="menuitem">
                <a href="#" class="menu-item" data-page="page-aset" id="menu-aset">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/>
                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"/>
                        <line x1="6" y1="6" x2="6.01" y2="6"/>
                        <line x1="6" y1="18" x2="6.01" y2="18"/>
                    </svg>
                    <span>Aset & Infrastruktur TI</span>
                </a>
            </li>

            <!-- Menu 3: Monitoring IoT -->
            <li>
                <a href="#" class="menu-item" data-page="page-iot">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                    Monitoring IoT
                </a>
            </li>

            <!-- Menu 4: Topografi Jaringan -->
            <li>
                <a href="#" class="menu-item" data-page="page-topografi">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="5" r="3"></circle>
                        <line x1="12" y1="22" x2="12" y2="8"></line>
                        <path d="M5 12H2a10 10 0 0 0 20 0h-3"></path>
                    </svg>
                    Topografi Jaringan
                </a>
            </li>

            <!-- Menu 5: Pemeliharaan TIK -->
            <li>
                <a href="#" class="menu-item" data-page="page-pemeliharaan">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                    Pemeliharaan TIK
                </a>
            </li>

            <!-- Menu 6: Perencanaan TIK -->
            <li>
                <a href="#" class="menu-item" data-page="page-perencanaan">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    Perencanaan TIK
                </a>
            </li>

            <!-- Menu 7: Pengembangan TIK -->
            <li>
                <a href="#" class="menu-item" data-page="page-pengembangan">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                    Pengembangan TIK
                </a>
            </li>

            <!-- Admin Link -->
            <li style="margin-top: auto; padding-top: 20px;">
                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin-bottom: 10px;">
                <a href="/admin" class="menu-item" style="color: #42A5F5;">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Admin Panel
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="sidebar-status">
                <span class="status-dot"></span>
                <span>Sistem Aktif</span>
            </div>
        </div>
    </nav>

    <!-- Mobile Hamburger Toggle -->
    <button class="hamburger" id="hamburger" aria-label="Toggle Menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ============================================================
         MAIN CONTENT
         ============================================================ -->
    <main class="main-content" id="mainContent">

        <!-- Header Bar -->
        <header class="top-header">
            <div class="header-left">
                <h1 id="pageTitle">Evaluasi SPBE</h1>
                <p class="header-subtitle" id="pageSubtitle">Dashboard Sistem Pemerintahan Berbasis Elektronik</p>
            </div>
            <div class="header-right">
                <div class="header-date" id="headerDate"></div>
                <div class="header-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
            </div>
        </header>

        <!-- ========================================================
             PAGE 1: EVALUASI SPBE
             ======================================================== -->
        <section class="page active" id="page-evaluasi">

            <!-- Summary Cards -->
            <div class="section-grid summary-cards">
                <article class="glass-card summary-card" id="cardSisaHari">
                    <div class="card-icon icon-calendar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Sisa Hari Kalender</span>
                        <span class="card-value" id="sisaHariValue">148</span>
                        <span class="card-subtitle">Menuju Akhir Tahun Anggaran 2026</span>
                    </div>
                </article>

                <article class="glass-card summary-card" id="cardRataCapaian">
                    <div class="card-icon icon-chart">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Rata-rata Capaian</span>
                        <span class="card-value" id="rataCapaianValue">67.5<small>%</small></span>
                        <div class="mini-progress">
                            <div class="mini-progress-bar" id="miniProgressBar" style="width: 67.5%"></div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Circular Progress Bars - Capaian per Kategori -->
            <section class="section-block">
                <h2 class="section-title">Capaian per Kategori SPBE</h2>
                <div class="progress-grid">
                    <!-- SVG Circular Progress: Kebijakan -->
                    <article class="glass-card progress-card" id="progressKebijakan">
                        <div class="circular-progress" data-value="75" data-color="#00d4ff">
                            <svg viewBox="0 0 120 120">
                                <defs>
                                    <linearGradient id="grad-kebijakan" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#1565C0"/>
                                        <stop offset="100%" stop-color="#0D47A1"/>
                                    </linearGradient>
                                </defs>
                                <circle class="progress-bg" cx="60" cy="60" r="52"/>
                                <circle class="progress-ring" cx="60" cy="60" r="52" stroke="url(#grad-kebijakan)" data-target="75"/>
                                <text class="progress-text" x="60" y="56" text-anchor="middle">75%</text>
                                <text class="progress-subtext" x="60" y="72" text-anchor="middle">Indeks</text>
                            </svg>
                        </div>
                        <h3 class="progress-label">Kebijakan SPBE</h3>
                    </article>

                    <!-- SVG Circular Progress: Tata Kelola -->
                    <article class="glass-card progress-card" id="progressTataKelola">
                        <div class="circular-progress" data-value="60" data-color="#7c3aed">
                            <svg viewBox="0 0 120 120">
                                <defs>
                                    <linearGradient id="grad-tatakelola" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#2E7D32"/>
                                        <stop offset="100%" stop-color="#1B5E20"/>
                                    </linearGradient>
                                </defs>
                                <circle class="progress-bg" cx="60" cy="60" r="52"/>
                                <circle class="progress-ring" cx="60" cy="60" r="52" stroke="url(#grad-tatakelola)" data-target="60"/>
                                <text class="progress-text" x="60" y="56" text-anchor="middle">60%</text>
                                <text class="progress-subtext" x="60" y="72" text-anchor="middle">Indeks</text>
                            </svg>
                        </div>
                        <h3 class="progress-label">Tata Kelola SPBE</h3>
                    </article>

                    <!-- SVG Circular Progress: Layanan -->
                    <article class="glass-card progress-card" id="progressLayanan">
                        <div class="circular-progress" data-value="80" data-color="#10b981">
                            <svg viewBox="0 0 120 120">
                                <defs>
                                    <linearGradient id="grad-layanan" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#1565C0"/>
                                        <stop offset="100%" stop-color="#42A5F5"/>
                                    </linearGradient>
                                </defs>
                                <circle class="progress-bg" cx="60" cy="60" r="52"/>
                                <circle class="progress-ring" cx="60" cy="60" r="52" stroke="url(#grad-layanan)" data-target="80"/>
                                <text class="progress-text" x="60" y="56" text-anchor="middle">80%</text>
                                <text class="progress-subtext" x="60" y="72" text-anchor="middle">Indeks</text>
                            </svg>
                        </div>
                        <h3 class="progress-label">Layanan SPBE</h3>
                    </article>

                    <!-- SVG Circular Progress: Manajemen -->
                    <article class="glass-card progress-card" id="progressManajemen">
                        <div class="circular-progress" data-value="55" data-color="#f59e0b">
                            <svg viewBox="0 0 120 120">
                                <defs>
                                    <linearGradient id="grad-manajemen" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#E65100"/>
                                        <stop offset="100%" stop-color="#BF360C"/>
                                    </linearGradient>
                                </defs>
                                <circle class="progress-bg" cx="60" cy="60" r="52"/>
                                <circle class="progress-ring" cx="60" cy="60" r="52" stroke="url(#grad-manajemen)" data-target="55"/>
                                <text class="progress-text" x="60" y="56" text-anchor="middle">55%</text>
                                <text class="progress-subtext" x="60" y="72" text-anchor="middle">Indeks</text>
                            </svg>
                        </div>
                        <h3 class="progress-label">Manajemen SPBE</h3>
                    </article>
                </div>
            </section>

            <!-- Kendala & Rekomendasi -->
            <section class="section-block">
                <div class="two-column-grid">
                    <!-- Daftar Kendala -->
                    <article class="glass-card list-card" id="cardKendala">
                        <h2 class="card-heading">
                            <svg class="heading-icon" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            Daftar Kendala
                        </h2>
                        <ul class="styled-list kendala-list">
                            <!-- Di-render oleh JavaScript (Data API) -->
                            <li class="text-muted" style="font-size: 0.8rem;">Memuat data...</li>
                        </ul>
                    </article>

                    <!-- Rekomendasi Tindak Lanjut -->
                    <article class="glass-card list-card" id="cardRekomendasi">
                        <h2 class="card-heading">
                            <svg class="heading-icon" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Rekomendasi Tindak Lanjut
                        </h2>
                        <ul class="styled-list rekomendasi-list">
                            <!-- Di-render oleh JavaScript (Data API) -->
                            <li class="text-muted" style="font-size: 0.8rem;">Memuat data...</li>
                        </ul>
                    </article>
                </div>
            </section>

        </section>

        <!-- ========================================================
             PAGE 2: ASET & INFRASTRUKTUR TI
             ======================================================== -->
        <section class="page" id="page-aset">

            <!-- Top Row: Donut Chart + Gantt Chart -->
            <div class="two-column-grid">
                <!-- Donut Chart BMN -->
                <article class="glass-card" id="cardBMN">
                    <h2 class="card-heading">
                        <svg class="heading-icon" viewBox="0 0 24 24" fill="none" stroke="#00d4ff" stroke-width="2">
                            <path d="M21.21 15.89A10 10 0 118 2.83"/>
                            <path d="M22 12A10 10 0 0012 2v10z"/>
                        </svg>
                        Status BMN (Barang Milik Negara)
                    </h2>
                    <div class="chart-container">
                        <canvas id="chartBMN" width="280" height="280"></canvas>
                        <div class="chart-legend" id="legendBMN">
                            <!-- Di-render dinamis oleh app.js -->
                        </div>
                    </div>
                </article>

                <!-- Jadwal Pemeliharaan Server (Gantt Chart) -->
                <article class="glass-card" id="cardGantt">
                    <h2 class="card-heading">
                        <svg class="heading-icon" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Jadwal Pemeliharaan Server — Agustus 2026
                    </h2>
                    <div class="gantt-chart" id="ganttChart">
                        <!-- Gantt header (days) akan di-render oleh JS -->
                        <div class="gantt-header" id="ganttHeader"></div>
                        <div class="gantt-body" id="ganttBody"></div>
                    </div>
                </article>
            </div>

            <!-- Daftar Inventaris Aset BMN -->
            <section class="section-block">
                <article class="glass-card table-card" id="cardMatriksBmn">
                    <div class="table-header-row">
                        <h2 class="card-heading">
                            <svg class="heading-icon" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                <line x1="8" y1="21" x2="16" y2="21"/>
                                <line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                            Daftar Inventaris Aset BMN
                        </h2>
                        <div class="search-container">
                            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input type="text" class="search-input" id="searchBmn" placeholder="Cari nama/merk/ruangan..." autocomplete="off">
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table class="data-table" id="tableBmn">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis BMN</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Merk / Tipe</th>
                                    <th>Lokasi / Ruangan</th>
                                    <th>Kondisi</th>
                                    <th>Status BMN</th>
                                    <th>Intra/Extra</th>
                                    <th>Tgl Peroleh</th>
                                    <th>Nilai Peroleh</th>
                                    <th>Nilai Susut</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyBmn">
                                <!-- Data akan di-render oleh JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="table-footer" id="tableFooterBmn">
                        <span id="tableInfoBmn">Menampilkan data BMN...</span>
                    </div>
                </article>
            </section>

            <!-- Matriks Akses Sistem Nasional -->
            <section class="section-block">
                <article class="glass-card table-card" id="cardMatriks">
                    <div class="table-header-row">
                        <h2 class="card-heading">
                            <svg class="heading-icon" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                                <path d="M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                            Matriks Akses Sistem Nasional Pegawai
                        </h2>
                        <div class="search-container">
                            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input type="text" class="search-input" id="searchMatriks" placeholder="Cari pegawai..." autocomplete="off">
                        </div>
                    </div>
                    <div class="table-wrapper" style="overflow-x: auto; overflow-y: auto; max-width: 100%; max-height: 70vh;">
                        <table class="data-table" id="tableMatriks" style="min-width: 3800px;">
                            <thead id="tableMatriksHead">
                                <!-- Di-render dinamis oleh app.js -->
                            </thead>
                            <tbody id="tableBody">
                                <!-- Data akan di-render oleh JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="table-footer" id="tableFooter">
                        <span id="tableInfo">Menampilkan 10 dari 10 pegawai</span>
                    </div>
                </article>
            </section>

        </section>

        <!-- ========================================================
             PAGE 3: MONITORING IoT (REAL-TIME)
             ======================================================== -->
        <section class="page" id="page-iot">

            <!-- ================================================
                 ALAT 1 — RUANG KEUANGAN (Otomasi AC)
                 ================================================ -->
            <div class="room-section-header">
                <div class="room-icon keuangan">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                        <line x1="8" y1="21" x2="16" y2="21"/>
                        <line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </div>
                <div class="room-info">
                    <h3>Alat 1 — Ruang Keuangan</h3>
                    <p>Otomasi AC berbasis sensor kehadiran</p>
                </div>
            </div>

            <div class="section-grid" style="grid-template-columns: 1fr;">
                <article class="glass-card iot-card" id="cardAC">
                    <div class="iot-card-header">
                        <h3 class="iot-card-title">
                            <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                                <path d="M8 16a4 4 0 0 1 0-8h8a4 4 0 0 1 0 8"/>
                                <path d="M8 8a4 4 0 0 1 0 8"/>
                                <path d="M2 12h2M20 12h2M12 2v2M12 20v2"/>
                            </svg>
                            Otomasi AC — Ruang Keuangan
                        </h3>
                        <span class="iot-status normal" id="acAutomasiStatus">Otomasi Aktif</span>
                    </div>
                    <div class="ac-status-card">
                        <!-- Visual AC Status -->
                        <div class="ac-visual on" id="acVisual">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M8 16a4 4 0 0 1 0-8h8a4 4 0 0 1 0 8"/>
                                <path d="M8 8a4 4 0 0 1 0 8"/>
                                <path d="M5 19l1.5-1.5M18.5 5.5L17 7M5 5l1.5 1.5M18.5 18.5L17 17"/>
                                <path d="M12 2v3M12 19v3"/>
                            </svg>
                        </div>
                        <!-- Detail Info -->
                        <!-- Detail Info -->
                        <div class="ac-details" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; width: 100%;">
                            <!-- Lingkungan Ruangan -->
                            <div>
                                <h4 style="font-size: 0.75rem; color: var(--bbpom-blue); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Ruangan Keuangan</h4>
                                <div class="ac-detail-row">
                                    <span class="label">Temperature</span>
                                    <span class="value" id="keuanganTemp">--°C</span>
                                </div>
                                <div class="ac-detail-row">
                                    <span class="label">Humidity</span>
                                    <span class="value" id="keuanganHum">--%</span>
                                </div>
                                <div class="ac-detail-row">
                                    <span class="label">Occupancy</span>
                                    <span class="value" id="kehadiranText">0 people</span>
                                </div>
                            </div>
                            <!-- Pengaturan AC -->
                            <div>
                                <h4 style="font-size: 0.75rem; color: var(--bbpom-green); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Pengaturan AC</h4>
                                <div class="ac-detail-row">
                                    <span class="label">AC Status</span>
                                    <span class="value off" id="acStatusText">OFF</span>
                                </div>
                                <div class="ac-detail-row">
                                    <span class="label">Set Temp</span>
                                    <span class="value">24°C</span>
                                </div>
                                <div class="ac-detail-row">
                                    <span class="label">Mode</span>
                                    <span class="value">COOL</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="iot-meta">
                        <span>Node: Ruang Keuangan Lt. 1</span>
                        <span id="acDuration">Nyala: 0j 0m | Mati: 0j 0m</span>
                    </div>
                </article>
            </div>

            <!-- ================================================
                 ALAT 2 — RUANG LAB REAGEN (Monitoring Lingkungan)
                 ================================================ -->
            <div class="room-section-header" style="margin-top: 24px;">
                <div class="room-icon lab">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M9 3h6v7l4 9H5l4-9V3z"/>
                        <line x1="9" y1="3" x2="15" y2="3"/>
                    </svg>
                </div>
                <div class="room-info">
                    <h3>Alat 2 — Ruang LAB Reagen</h3>
                    <p>Monitoring suhu, kelembapan & kualitas udara untuk penjagaan kualitas obat</p>
                </div>
            </div>

            <!-- Alert Suhu Lab -->
            <div class="alert-banner safe" id="alertSuhuBanner">
                <div class="alert-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 9v4M12 17h.01"/>
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div class="alert-text">
                    <h4 id="alertSuhuTitle">✅ Suhu Lab Dalam Batas Aman</h4>
                    <p id="alertSuhuDesc">Rentang aman: 15°C — 25°C | Suhu saat ini: <strong id="alertSuhuCurrent">24.5°C</strong></p>
                </div>
            </div>

            <!-- Suhu, Kelembapan, Kualitas Udara -->
            <div class="section-grid iot-grid-3">
                <article class="glass-card iot-card" id="cardSuhu">
                    <div class="iot-card-header">
                        <h3 class="iot-card-title">
                            <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                                <path d="M14 14.76V3.5a2.5 2.5 0 00-5 0v11.26a4.5 4.5 0 105 0z"/>
                            </svg>
                            Suhu Ruang Lab
                        </h3>
                        <span class="iot-trend up" id="suhuTrend">▲ 0.2°C</span>
                    </div>
                    <div class="iot-value-display">
                        <span class="iot-big-value" id="suhuValue">24.5</span>
                        <span class="iot-unit">°C</span>
                    </div>
                    <div class="iot-chart-container">
                        <canvas id="chartSuhu" width="400" height="140"></canvas>
                    </div>
                    <div class="iot-meta">
                        <span>Sensor: DHT22</span>
                        <span>Node: Lab Mikrobiologi</span>
                    </div>
                </article>

                <article class="glass-card iot-card" id="cardKelembapan">
                    <div class="iot-card-header">
                        <h3 class="iot-card-title">
                            <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#00d4ff" stroke-width="2">
                                <path d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/>
                            </svg>
                            Kelembapan Ruang Lab
                        </h3>
                        <span class="iot-trend down" id="kelembapanTrend">▼ 1%</span>
                    </div>
                    <div class="iot-value-display">
                        <span class="iot-big-value" id="kelembapanValue">62</span>
                        <span class="iot-unit">%RH</span>
                    </div>
                    <div class="iot-chart-container">
                        <canvas id="chartKelembapan" width="400" height="140"></canvas>
                    </div>
                    <div class="iot-meta">
                        <span>Sensor: DHT22</span>
                        <span>Node: Lab Mikrobiologi</span>
                    </div>
                </article>

                <article class="glass-card iot-card" id="cardMQ">
                    <div class="iot-card-header">
                        <h3 class="iot-card-title">
                            <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="2">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                            Kualitas Udara (MQ)
                        </h3>
                        <span class="iot-status normal" id="mqStatus">Aman</span>
                    </div>
                    <div class="iot-value-display">
                        <span class="iot-big-value" id="mqValue">--</span>
                        <span class="iot-unit">PPM</span>
                    </div>
                    <div class="iot-chart-container">
                        <canvas id="chartMQ" width="400" height="140"></canvas>
                    </div>
                    <div class="iot-meta">
                        <span>Sensor: MQ-2/MQ-135</span>
                        <span>Node: Lab Mikrobiologi</span>
                    </div>
                </article>
            </div>

            <!-- Daily Recap Monitoring -->
            <article class="glass-card" id="cardDailyRecap" style="margin-top: 8px;">
                <div class="iot-card-header">
                    <h3 class="iot-card-title">
                        <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Rekap Harian Monitoring — <span id="recapDate">Hari Ini</span>
                    </h3>
                </div>
                <table class="recap-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Rata-rata</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Sampel</th>
                        </tr>
                    </thead>
                    <tbody id="recapTableBody">
                        <tr>
                            <td>🌡️ Suhu</td>
                            <td id="recapAvgSuhu">—</td>
                            <td id="recapMinSuhu">—</td>
                            <td id="recapMaxSuhu">—</td>
                            <td id="recapSamples" rowspan="3" style="vertical-align: middle; text-align: center; font-weight: 700; color: var(--bbpom-blue);">0</td>
                        </tr>
                        <tr>
                            <td>💧 Kelembapan</td>
                            <td id="recapAvgHum">—</td>
                            <td id="recapMinHum">—</td>
                            <td id="recapMaxHum">—</td>
                        </tr>
                        <tr>
                            <td>🌫️ Kualitas Udara</td>
                            <td id="recapAvgMQ">—</td>
                            <td id="recapMinMQ">—</td>
                            <td id="recapMaxMQ">—</td>
                        </tr>
                    </tbody>
                </table>
                <div class="recap-footer">
                    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                        <span class="recap-stat">
                            <span class="stat-dot red"></span>
                            Alert Suhu: <strong id="recapAlerts" class="alert-badge">0</strong> kali
                        </span>
                        <span class="recap-stat">
                            <span class="stat-dot green"></span>
                            AC Keuangan ON: <strong id="recapAcOn">0j 0m</strong> | OFF: <strong id="recapAcOff">0j 0m</strong>
                        </span>
                    </div>
                    <button class="btn-generate-pdf" id="btnGeneratePDF">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        Download PDF Laporan
                    </button>
                </div>
            </article>

            <!-- Pemakaian Listrik & Download -->
            <div class="section-grid" style="grid-template-columns: 1fr; margin-top: 8px;">
                <article class="glass-card iot-card iot-card-wide" id="cardListrik">
                    <div class="iot-card-header">
                        <h3 class="iot-card-title">
                            <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                            </svg>
                            Pemakaian Listrik
                        </h3>
                    </div>
                    <div class="electric-gauges">
                        <div class="gauge-item" id="gaugeVoltage">
                            <div class="gauge-ring">
                                <svg viewBox="0 0 100 100">
                                    <circle class="gauge-bg" cx="50" cy="50" r="42"/>
                                    <circle class="gauge-fill voltage-fill" cx="50" cy="50" r="42" data-value="92"/>
                                    <text class="gauge-value" x="50" y="46" text-anchor="middle" id="voltageValue">220</text>
                                    <text class="gauge-unit" x="50" y="62" text-anchor="middle">Volt</text>
                                </svg>
                            </div>
                            <span class="gauge-label">Tegangan</span>
                        </div>
                        <div class="gauge-item" id="gaugeAmpere">
                            <div class="gauge-ring">
                                <svg viewBox="0 0 100 100">
                                    <circle class="gauge-bg" cx="50" cy="50" r="42"/>
                                    <circle class="gauge-fill ampere-fill" cx="50" cy="50" r="42" data-value="45"/>
                                    <text class="gauge-value" x="50" y="46" text-anchor="middle" id="ampereValue">4.5</text>
                                    <text class="gauge-unit" x="50" y="62" text-anchor="middle">Ampere</text>
                                </svg>
                            </div>
                            <span class="gauge-label">Arus</span>
                        </div>
                        <div class="gauge-item" id="gaugeWatt">
                            <div class="gauge-ring">
                                <svg viewBox="0 0 100 100">
                                    <circle class="gauge-bg" cx="50" cy="50" r="42"/>
                                    <circle class="gauge-fill watt-fill" cx="50" cy="50" r="42" data-value="66"/>
                                    <text class="gauge-value" x="50" y="46" text-anchor="middle" id="wattValue">990</text>
                                    <text class="gauge-unit" x="50" y="62" text-anchor="middle">Watt</text>
                                </svg>
                            </div>
                            <span class="gauge-label">Daya</span>
                        </div>
                    </div>

                    <!-- Download Buttons -->
                    <div class="download-section">
                        <h4 class="download-title">Download Laporan Harian</h4>
                        <div class="download-buttons">
                            <button class="btn btn-download btn-pdf" id="btnDownloadPDF">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10 9 9 9 8 9"/>
                                </svg>
                                Download PDF
                            </button>
                            <button class="btn btn-download btn-csv" id="btnDownloadCSV">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Download CSV
                            </button>
                        </div>
                    </div>
                </article>
            </div>

        </section>

        <!-- ========================================================
             PAGE 4: TOPOGRAFI JARINGAN
             ======================================================== -->
        <section class="page" id="page-topografi">
            <div class="section-block" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 70vh;">
                <h2 class="section-title">Peta Topografi Jaringan LAN & WAN BBPOM</h2>
                <div class="glass-card" style="width: 100%; max-width: 1200px; padding: 20px; text-align: center; overflow: hidden; border-radius: 12px;">
                    <img id="topografiImage" src="" alt="Topografi Jaringan BBPOM" style="width: 100%; max-height: 80vh; object-fit: contain; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                </div>
            </div>
        </section>

        <!-- ========================================================
             PAGE 5: PEMELIHARAAN TIK
             ======================================================== -->
        <section class="page" id="page-pemeliharaan">
            <div class="section-block">
                <div class="glass-card" style="padding: 24px; margin-bottom: 24px;">
                    <div class="section-header" style="margin-bottom: 20px;">
                        <h2 class="section-title" style="margin-bottom: 0;">Jadwal Pemeliharaan Infrastruktur TIK</h2>
                        <span class="table-info" id="infoPemeliharaan">Menampilkan data tahun 2026</span>
                    </div>
                    <div class="table-wrapper">
                        <table class="data-table" style="min-width: 900px;">
                            <thead>
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th style="min-width: 250px;">Aktivitas Pemeliharaan</th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>Mei</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Agu</th>
                                <th>Sep</th>
                                <th>Okt</th>
                                <th>Nov</th>
                                <th>Des</th>
                            </tr>
                        </thead>
                        <tbody id="tablePemeliharaanBody">
                            <!-- Di-render oleh JS -->
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </section>

        <!-- ========================================================
             PAGE 6: PERENCANAAN TIK
             ======================================================== -->
        <section class="page" id="page-perencanaan">
            <div class="section-block">
                <div class="glass-card" style="padding: 24px; margin-bottom: 24px;">
                    <div class="section-header" style="margin-bottom: 20px;">
                        <h2 class="section-title" style="margin-bottom: 0;">Perencanaan Anggaran Pengadaan TIK</h2>
                        <span class="table-info" id="infoPerencanaanTotal">Total Anggaran: Rp 0</span>
                    </div>
                    <div class="table-wrapper">
                        <table class="data-table" style="min-width: 900px;">
                            <thead>
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th style="min-width: 250px;">Uraian Kebutuhan</th>
                                    <th>Volume</th>
                                    <th>Satuan</th>
                                    <th>Harga Satuan (Rp)</th>
                                    <th>Jumlah Anggaran (Rp)</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="tablePerencanaanBody">
                                <!-- Di-render oleh JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================
             PAGE 7: PENGEMBANGAN TIK
             ======================================================== -->
        <section class="page" id="page-pengembangan">
            <div class="section-block">
                <h2 class="section-title">Monitoring Progres Pengembangan TIK (Aplikasi)</h2>
                <div class="section-grid" id="gridPengembangan" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <!-- Di-render oleh JS -->
                </div>
            </div>
        </section>

    </main>
    <script>
        const API_BASE = '<?= base_url('api') ?>';
    </script>
    <script src="app.js?v=<?= time() ?>"></script>
</body>
</html>







