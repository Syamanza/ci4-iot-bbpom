<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Monitoring IoT SPBE BBPOM Bandung - Dashboard monitoring suhu, kelembapan, dan kualitas udara real-time.">
    <meta name="author" content="BBPOM di Bandung">
    <meta name="theme-color" content="#f0f4f8">
    <title>Monitoring IoT — BBPOM Bandung</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
    <style>
        /* Override: no sidebar, full width */
        .main-content { margin-left: 0 !important; }
    </style>
</head>
<body>

    <!-- ============================================================
         MAIN CONTENT (No Sidebar)
         ============================================================ -->
    <main class="main-content" id="mainContent">

        <!-- Header Bar -->
        <header class="top-header">
            <div class="header-left">
                <h1 id="pageTitle">Monitoring Lingkungan IoT</h1>
                <p class="header-subtitle" id="pageSubtitle">Sistem Peringatan Dini Suhu &amp; Kelembapan Ruang Server BBPOM Bandung</p>
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
             IoT DASHBOARD
             ======================================================== -->
        <section class="page active" id="page-iot">

            <!-- ================================================
                 TAB NAVIGATION
                 ================================================ -->
            <div class="iot-tabs-container">
                <button class="iot-tab-btn active" data-target="tab-keuangan">Ruang Keuangan</button>
                <button class="iot-tab-btn" data-target="tab-lab">Ruang Lab Reagen</button>
            </div>

            <!-- TAB CONTENT: KEUANGAN -->
            <div class="iot-tab-pane active" id="tab-keuangan">


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
                    <h3>Alat 1 &mdash; Ruang Keuangan (AC)</h3>
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

                        
            <!-- Daily Recap Keuangan -->
            <article class="glass-card" id="cardDailyRecapKeuangan" style="margin-top: 8px;">
                <div class="iot-card-header">
                    <h3 class="iot-card-title">
                        <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Rekap Harian Ruang Keuangan &mdash; <span id="recapDateKeu">Hari Ini</span>
                    </h3>
                </div>
                <table class="recap-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Status / Durasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>⚡ Total AC Menyala</td>
                            <td id="recapAcOnKeu" style="font-weight: bold; color: var(--bbpom-green);">0j 0m</td>
                        </tr>
                        <tr>
                            <td>⏸️ Total AC Mati</td>
                            <td id="recapAcOffKeu" style="font-weight: bold; color: var(--text-secondary);">0j 0m</td>
                        </tr>
                        <tr>
                            <td>🌡️ Rata-rata Suhu Ruangan</td>
                            <td id="recapAvgSuhuKeu" style="font-weight: bold;">-- &deg;C</td>
                        </tr>
                    </tbody>
                </table>
                <div class="recap-footer">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="date" id="pdfDateKeu" class="pdf-date-picker" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <button class="btn-generate-pdf" id="btnGeneratePDFKeu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        Download Laporan PDF
                    </button>
                    </div>
                </div>
            </article>
</div> <!-- End Tab Keuangan -->

            <!-- TAB CONTENT: LAB REAGEN -->
            <div class="iot-tab-pane" id="tab-lab">
            <!-- ================================================
                 ALAT 1 — RUANG LAB REAGEN (AC)
                 ================================================ -->
            <div class="room-section-header" style="margin-top: 24px;">
                <div class="room-icon lab">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M9 3h6v7l4 9H5l4-9V3z"/>
                        <line x1="9" y1="3" x2="15" y2="3"/>
                    </svg>
                </div>
                <div class="room-info">
                    <h3>Alat 1 &mdash; Ruang LAB Reagen (AC)</h3>
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
            
<!-- ALAT 2 — RUANG LAB REAGEN (Non-AC) -->
            <div class="room-section-header" style="margin-top: 24px;">
                <div class="room-icon lab">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M9 3h6v7l4 9H5l4-9V3z"/>
                        <line x1="9" y1="3" x2="15" y2="3"/>
                    </svg>
                </div>
                <div class="room-info">
                    <h3>Alat 2 &mdash; Ruang LAB Reagen (Non-AC)</h3>
                    <p>Monitoring suhu, kelembapan & kualitas udara untuk penjagaan kualitas obat</p>
                </div>
            </div>

            <!-- Alert Suhu Lab Alat 2 -->
            <div class="alert-banner safe" id="alertSuhuBanner_2">
                <div class="alert-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 9v4M12 17h.01"/>
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div class="alert-text">
                    <h4 id="alertSuhuTitle_2">✅ Suhu Lab Dalam Batas Aman</h4>
                    <p id="alertSuhuDesc_2">Rentang aman: 15°C — 25°C | Suhu saat ini: <strong id="alertSuhuCurrent_2">24.5°C</strong></p>
                </div>
            </div>

            <!-- Suhu, Kelembapan, Kualitas Udara Alat 2 -->
            <div class="section-grid iot-grid-3">
                <article class="glass-card iot-card" id="cardSuhu_2">
                    <div class="iot-card-header">
                        <h3 class="iot-card-title">
                            <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                                <path d="M14 14.76V3.5a2.5 2.5 0 00-5 0v11.26a4.5 4.5 0 105 0z"/>
                            </svg>
                            Suhu Ruang Lab
                        </h3>
                        <span class="iot-status normal" id="suhuStatus_2">Aman</span>
                    </div>
                    <div class="iot-value-display">
                        <span class="iot-big-value" id="suhuValue_2">--</span>
                        <span class="iot-unit">°C</span>
                    </div>
                    <div class="iot-chart-container">
                        <canvas id="chartSuhu_2" width="400" height="140"></canvas>
                    </div>
                    <div class="iot-meta">
                        <span>Sensor: DHT22</span>
                        <span>Node: Lab Mikrobiologi</span>
                    </div>
                </article>

                <article class="glass-card iot-card" id="cardKelembapan_2">
                    <div class="iot-card-header">
                        <h3 class="iot-card-title">
                            <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#00d4ff" stroke-width="2">
                                <path d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/>
                            </svg>
                            Kelembapan Ruang Lab
                        </h3>
                        <span class="iot-status normal" id="humStatus_2">Aman</span>
                    </div>
                    <div class="iot-value-display">
                        <span class="iot-big-value" id="humValue_2">--</span>
                        <span class="iot-unit">%RH</span>
                    </div>
                    <div class="iot-chart-container">
                        <canvas id="chartHum_2" width="400" height="140"></canvas>
                    </div>
                    <div class="iot-meta">
                        <span>Sensor: DHT22</span>
                        <span>Node: Lab Mikrobiologi</span>
                    </div>
                </article>

                <article class="glass-card iot-card" id="cardMQ_2">
                    <div class="iot-card-header">
                        <h3 class="iot-card-title">
                            <svg class="heading-icon small" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="2">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                            Kualitas Udara (MQ)
                        </h3>
                        <span class="iot-status normal" id="mqStatus_2">Aman</span>
                    </div>
                    <div class="iot-value-display">
                        <span class="iot-big-value" id="mqValue_2">--</span>
                        <span class="iot-unit">PPM</span>
                    </div>
                    <div class="iot-chart-container">
                        <canvas id="chartMQ_2" width="400" height="140"></canvas>
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
                        <tr style="background-color: rgba(21,101,192,0.05); font-weight: 600;">
                            <td colspan="5" style="color: #1565C0;">🖥️ Alat 1 (AC)</td>
                        </tr>
                        <tr>
                            <td>🌡️ Suhu</td>
                            <td id="recapAvgSuhu">--</td>
                            <td id="recapMinSuhu">--</td>
                            <td id="recapMaxSuhu">--</td>
                            <td id="recapSamples" rowspan="3" style="vertical-align: middle; text-align: center; font-weight: 700; color: var(--bbpom-blue);">0</td>
                        </tr>
                        <tr>
                            <td>💧 Kelembapan</td>
                            <td id="recapAvgHum">--</td>
                            <td id="recapMinHum">--</td>
                            <td id="recapMaxHum">--</td>
                        </tr>
                        <tr>
                            <td>🌫️ Kualitas Udara</td>
                            <td id="recapAvgMQ">--</td>
                            <td id="recapMinMQ">--</td>
                            <td id="recapMaxMQ">--</td>
                        </tr>
                        
                        <tr style="background-color: rgba(21,101,192,0.05); font-weight: 600;">
                            <td colspan="5" style="color: #1565C0;">🖥️ Alat 2 (Non-AC)</td>
                        </tr>
                        <tr>
                            <td>🌡️ Suhu</td>
                            <td id="recapAvgSuhu_2">--</td>
                            <td id="recapMinSuhu_2">--</td>
                            <td id="recapMaxSuhu_2">--</td>
                            <td id="recapSamples_2" rowspan="3" style="vertical-align: middle; text-align: center; font-weight: 700; color: var(--bbpom-blue);">0</td>
                        </tr>
                        <tr>
                            <td>💧 Kelembapan</td>
                            <td id="recapAvgHum_2">--</td>
                            <td id="recapMinHum_2">--</td>
                            <td id="recapMaxHum_2">--</td>
                        </tr>
                        <tr>
                            <td>🌫️ Kualitas Udara</td>
                            <td id="recapAvgMQ_2">--</td>
                            <td id="recapMinMQ_2">--</td>
                            <td id="recapMaxMQ_2">--</td>
                        </tr>
                    </tbody>
                </table>
                <div class="recap-footer">
                    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                        <span class="recap-stat">
                            <span class="stat-dot red"></span>
                            Alert Suhu: <strong id="recapAlerts" class="alert-badge">0</strong> kali (Alat 1) | <strong id="recapAlerts_2" class="alert-badge">0</strong> kali (Alat 2)
                        </span>
                        
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="date" id="pdfDateLab" class="pdf-date-picker" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
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
                </div>
            </article>
            </div> <!-- End Tab Lab Reagen -->


        </section>
        </main>
    <script>
        const API_BASE = '/api';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script charset="utf-8" src="app.js?v=18"></script>
</body>
</html>
