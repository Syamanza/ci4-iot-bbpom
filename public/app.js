/**
 * ============================================================
 * COMMAND CENTER SPBE BBPOM BANDUNG
 * Main Application Script (app.js)
 * ============================================================
 * 
 * Modul utama yang mengelola:
 * - Navigasi sidebar & perpindahan halaman
 * - SVG Circular Progress Bars (animasi)
 * - Canvas Donut Chart (BMN)
 * - Canvas Line Charts (Suhu & Kelembapan)
 * - Gantt Chart (Jadwal Pemeliharaan)
 * - Data Table dengan fitur pencarian
 * - Simulasi data IoT real-time
 * - Fitur Download (PDF/CSV)
 */

;(function () {
    'use strict';

    // ============================================================
    // UTILITY FUNCTIONS
    // ============================================================

    /**
     * Shortcut untuk querySelector
     */
    const $ = (sel) => document.querySelector(sel);
    const $$ = (sel) => document.querySelectorAll(sel);

    /**
     * Format tanggal ke Bahasa Indonesia
     */
    function formatDateID(date) {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return `${days[date.getDay()]}, ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    /**
     * Format waktu HH:MM:SS
     */
    function formatTime(date) {
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }

    /**
     * Random number dalam range
     */
    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    // ============================================================
    // INITIALIZATION
    // Use the global API_BASE set by the PHP view, or fallback
    if (typeof window.API_BASE === 'undefined') {
        // Will be set by <script> in dashboard.php via base_url
    }

    document.addEventListener('DOMContentLoaded', async () => {
        initIoTTabs();
        // Use global API_BASE if available, otherwise detect from current URL
        if (typeof API_BASE === 'undefined' || !API_BASE) {
            window.API_BASE = window.location.pathname.replace(/\/+$/, '').replace('/public', '/public/api');
        }

        initHeader();
        bindPdfButtons();
        initPDFButton();

        initIoTCharts();
        initGauges();
        startRealtimeSimulation();
    });






    function initHeader() {
        const dateEl = $('#headerDate');
        if (dateEl) dateEl.textContent = formatDateID(new Date());
    }
    // ============================================================
    // IoT LINE CHARTS (Canvas) - Suhu & Kelembapan
    // ============================================================

    const iotData = {
        suhu: Array(20).fill(0),
        kelembapan: Array(20).fill(0),
        mq: Array(20).fill(0),
        suhu_2: Array(20).fill(0),
        kelembapan_2: Array(20).fill(0),
        mq_2: Array(20).fill(0),
        voltage: 220,
        ampere: 4.5,
        watt: 990,
        acStatus: 'OFF',
        keuanganKehadiran: 0
    };

    function initIoTCharts() {
        drawLineChart('chartSuhu', iotData.suhu, '#C62828', 'rgba(198, 40, 40, 0.08)', 20, 30);
        drawLineChart('chartKelembapan', iotData.kelembapan, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80);
        drawLineChart('chartMQ', iotData.mq, '#a855f7', 'rgba(168, 85, 247, 0.08)', 300, 2500);

        drawLineChart('chartSuhu_2', iotData.suhu_2, '#C62828', 'rgba(198, 40, 40, 0.08)', 20, 30);
        drawLineChart('chartHum_2', iotData.kelembapan_2, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80);
        drawLineChart('chartMQ_2', iotData.mq_2, '#a855f7', 'rgba(168, 85, 247, 0.08)', 300, 2500);
    }

    function drawLineChart(canvasId, data, lineColor, fillColor, minY, maxY) {
        const canvas = $(`#${canvasId}`);
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const dpr = window.devicePixelRatio || 1;

        const displayWidth = canvas.clientWidth || 400;
        const displayHeight = canvas.clientHeight || 140;
        canvas.width = displayWidth * dpr;
        canvas.height = displayHeight * dpr;
        ctx.scale(dpr, dpr);

        const padding = { top: 10, right: 10, bottom: 20, left: 35 };
        const chartWidth = displayWidth - padding.left - padding.right;
        const chartHeight = displayHeight - padding.top - padding.bottom;

        ctx.clearRect(0, 0, displayWidth, displayHeight);

        // Grid lines (horizontal)
        const gridLines = 4;
        ctx.strokeStyle = 'rgba(21, 101, 192, 0.08)';
        ctx.lineWidth = 1;
        for (let i = 0; i <= gridLines; i++) {
            const y = padding.top + (chartHeight / gridLines) * i;
            ctx.beginPath();
            ctx.moveTo(padding.left, y);
            ctx.lineTo(displayWidth - padding.right, y);
            ctx.stroke();

            // Y-axis labels
            const val = maxY - ((maxY - minY) / gridLines) * i;
            ctx.fillStyle = 'rgba(71, 85, 105, 0.7)';
            ctx.font = '500 0.55rem Inter, sans-serif';
            ctx.textAlign = 'right';
            ctx.fillText(val.toFixed(0), padding.left - 6, y + 4);
        }

        if (data.length < 2) return;

        // Calculate points
        const points = data.map((val, idx) => ({
            x: padding.left + (idx / (data.length - 1)) * chartWidth,
            y: padding.top + chartHeight - ((val - minY) / (maxY - minY)) * chartHeight
        }));

        // Draw gradient fill
        const gradient = ctx.createLinearGradient(0, padding.top, 0, displayHeight - padding.bottom);
        gradient.addColorStop(0, fillColor);
        gradient.addColorStop(1, 'rgba(0, 0, 0, 0)');

        ctx.beginPath();
        ctx.moveTo(points[0].x, displayHeight - padding.bottom);
        points.forEach((p, i) => {
            if (i === 0) {
                ctx.lineTo(p.x, p.y);
            } else {
                // Smooth curve menggunakan bezier
                const prev = points[i - 1];
                const cpx = (prev.x + p.x) / 2;
                ctx.bezierCurveTo(cpx, prev.y, cpx, p.y, p.x, p.y);
            }
        });
        ctx.lineTo(points[points.length - 1].x, displayHeight - padding.bottom);
        ctx.closePath();
        ctx.fillStyle = gradient;
        ctx.fill();

        // Draw line
        ctx.beginPath();
        points.forEach((p, i) => {
            if (i === 0) {
                ctx.moveTo(p.x, p.y);
            } else {
                const prev = points[i - 1];
                const cpx = (prev.x + p.x) / 2;
                ctx.bezierCurveTo(cpx, prev.y, cpx, p.y, p.x, p.y);
            }
        });
        ctx.strokeStyle = lineColor;
        ctx.lineWidth = 2;
        ctx.stroke();

        // Draw last point (current value indicator)
        const lastPoint = points[points.length - 1];
        ctx.beginPath();
        ctx.arc(lastPoint.x, lastPoint.y, 4, 0, Math.PI * 2);
        ctx.fillStyle = lineColor;
        ctx.fill();
        ctx.beginPath();
        ctx.arc(lastPoint.x, lastPoint.y, 7, 0, Math.PI * 2);
        ctx.strokeStyle = lineColor;
        ctx.lineWidth = 1.5;
        ctx.globalAlpha = 0.3;
        ctx.stroke();
        ctx.globalAlpha = 1;
    }

    function resizeIoTCharts() {
        if ($('#tab-lab').classList.contains('active')) {
            drawLineChart('chartSuhu', iotData.suhu, '#C62828', 'rgba(198, 40, 40, 0.08)', 20, 30);
            drawLineChart('chartKelembapan', iotData.kelembapan, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80);
            drawLineChart('chartMQ', iotData.mq, '#a855f7', 'rgba(168, 85, 247, 0.08)', 300, 2500);

            drawLineChart('chartSuhu_2', iotData.suhu_2, '#C62828', 'rgba(198, 40, 40, 0.08)', 20, 30);
            drawLineChart('chartHum_2', iotData.kelembapan_2, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80);
            drawLineChart('chartMQ_2', iotData.mq_2, '#a855f7', 'rgba(168, 85, 247, 0.08)', 300, 2500);
        }
    }

    // ============================================================
    // (Fungsi renderSparkline dihapus karena diganti Line Chart MQ)
    // ============================================================

    // ============================================================
    // ELECTRIC GAUGES (SVG)
    // ============================================================

    function initGauges() {
        const circumference = 2 * Math.PI * 42; // r=42

        $$('.gauge-fill').forEach(gauge => {
            gauge.style.strokeDasharray = circumference;
            const targetValue = parseInt(gauge.getAttribute('data-value'));
            const offset = circumference - (targetValue / 100) * circumference;

            setTimeout(() => {
                gauge.style.strokeDashoffset = offset;
            }, 600);
        });
    }

    function updateGauges() {
        // Voltage gauge (range 200-240, normalized to 0-100)
        const voltageNorm = ((iotData.voltage - 200) / 40) * 100;
        // Ampere gauge (range 0-10, normalized)
        const ampereNorm = (iotData.ampere / 10) * 100;
        // Watt gauge (range 0-1500, normalized)
        const wattNorm = (iotData.watt / 1500) * 100;

        const circumference = 2 * Math.PI * 42;

        const voltageFill = $('.voltage-fill');
        const ampereFill = $('.ampere-fill');
        const wattFill = $('.watt-fill');

        if (voltageFill) voltageFill.style.strokeDashoffset = circumference - (voltageNorm / 100) * circumference;
        if (ampereFill) ampereFill.style.strokeDashoffset = circumference - (ampereNorm / 100) * circumference;
        if (wattFill) wattFill.style.strokeDashoffset = circumference - (wattNorm / 100) * circumference;

        // Update text values
        const vEl = $('#voltageValue');
        const aEl = $('#ampereValue');
        const wEl = $('#wattValue');
        if (vEl) vEl.textContent = iotData.voltage.toFixed(0);
        if (aEl) aEl.textContent = iotData.ampere.toFixed(1);
        if (wEl) wEl.textContent = iotData.watt.toFixed(0);
    }

    // ============================================================
    // REAL-TIME IoT SIMULATION
    // ============================================================

    function startRealtimeSimulation() {
        // Initial call
        fetchRealtimeData();
        
        // Loop setiap 10 detik
        setInterval(fetchRealtimeData, 10000);
    }

    async function fetchRealtimeData() {
        try {
            const res = await fetch(`${API_BASE}/data?key=sensorData`);
            if (res.ok) {
                const dataObj = await res.json();
                
                // --- ALAT 1 (AC) ---
                if (dataObj.lab_ac && dataObj.lab_ac.length > 0) {
                    const lab1 = dataObj.lab_ac;
                    const mqValues = lab1.map(d => d.mq || 0);
                    const suhuValues = lab1.map(d => d.suhu || 0);
                    const humValues = lab1.map(d => d.hum || 0);

                    while (mqValues.length < 20) mqValues.unshift(0);
                    while (suhuValues.length < 20) suhuValues.unshift(0);
                    while (humValues.length < 20) humValues.unshift(0);

                    iotData.mq = mqValues;
                    iotData.suhu = suhuValues;
                    iotData.kelembapan = humValues;
                }

                // --- ALAT 2 (NON-AC) ---
                if (dataObj.lab_non_ac && dataObj.lab_non_ac.length > 0) {
                    const lab2 = dataObj.lab_non_ac;
                    const mqValues2 = lab2.map(d => d.mq || 0);
                    const suhuValues2 = lab2.map(d => d.suhu || 0);
                    const humValues2 = lab2.map(d => d.hum || 0);

                    while (mqValues2.length < 20) mqValues2.unshift(0);
                    while (suhuValues2.length < 20) suhuValues2.unshift(0);
                    while (humValues2.length < 20) humValues2.unshift(0);

                    iotData.mq_2 = mqValues2;
                    iotData.suhu_2 = suhuValues2;
                    iotData.kelembapan_2 = humValues2;
                }

                // --- KEUANGAN ---
                if (dataObj.keuangan && dataObj.keuangan.length > 0) {
                    const currentKeu = dataObj.keuangan[dataObj.keuangan.length - 1];
                    iotData.acStatus = currentKeu.acStatus ? 'ON' : 'OFF';
                    iotData.keuanganKehadiran = currentKeu.kehadiran || 0;
                }
            }
        } catch (err) {
            console.error("Gagal fetch sensorData", err);
        }

        iotData.voltage = parseFloat((220 + (Math.random() * 10 - 5)).toFixed(0));
        iotData.ampere = parseFloat((4.5 + (Math.random() * 1.6 - 0.8)).toFixed(1));
        iotData.watt = parseFloat((iotData.voltage * iotData.ampere).toFixed(0));

        updateIoTDisplay();
        
        updateGauge('gaugeVoltage', iotData.voltage, 250);
        updateGauge('gaugeAmpere', iotData.ampere, 10);
        updateGauge('gaugeWatt', iotData.watt, 2200);

        const acStatusEl = document.querySelector('#acStatusValue');
        if (acStatusEl) {
            acStatusEl.textContent = iotData.acStatus;
            acStatusEl.className = iotData.acStatus === 'ON' ? 'stat-value green' : 'stat-value red';
        }
        
        const occEl = document.querySelector('#occupancyValue');
        if (occEl) occEl.textContent = iotData.keuanganKehadiran + ' people';

        updateDailyRecap();
        updateTimestamp();
        
        // Redraw charts explicitly for both devices
        if (typeof drawLineChart === 'function') {
            drawLineChart('chartSuhu', iotData.suhu, '#C62828', 'rgba(198, 40, 40, 0.08)', 20, 30);
            drawLineChart('chartKelembapan', iotData.kelembapan, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80);
            drawLineChart('chartMQ', iotData.mq, '#a855f7', 'rgba(168, 85, 247, 0.08)', 300, 2500);
            
            drawLineChart('chartSuhu_2', iotData.suhu_2, '#C62828', 'rgba(198, 40, 40, 0.08)', 20, 30);
            drawLineChart('chartHum_2', iotData.kelembapan_2, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80);
            drawLineChart('chartMQ_2', iotData.mq_2, '#a855f7', 'rgba(168, 85, 247, 0.08)', 300, 2500);
        }
    }

    function updateIoTDisplay() {
        const currentSuhu = iotData.suhu[iotData.suhu.length - 1];
        const currentKelembapan = iotData.kelembapan[iotData.kelembapan.length - 1];
        const currentMq = iotData.mq[iotData.mq.length - 1];

        const currentSuhu2 = iotData.suhu_2[iotData.suhu_2.length - 1];
        const currentKelembapan2 = iotData.kelembapan_2[iotData.kelembapan_2.length - 1];
        const currentMq2 = iotData.mq_2[iotData.mq_2.length - 1];

        const suhuEl = $('#suhuValue');
        const kelembapanEl = $('#kelembapanValue');
        const mqEl = $('#mqValue');

        if (suhuEl) suhuEl.textContent = currentSuhu.toFixed(1);
        if (kelembapanEl) kelembapanEl.textContent = currentKelembapan.toFixed(0);
        if (mqEl) mqEl.textContent = currentMq > 0 ? currentMq.toFixed(0) : '--';
        
        const suhuEl2 = $('#suhuValue_2');
        const kelembapanEl2 = $('#humValue_2');
        const mqEl2 = $('#mqValue_2');

        if (suhuEl2) suhuEl2.textContent = currentSuhu2.toFixed(1);
        if (kelembapanEl2) kelembapanEl2.textContent = currentKelembapan2.toFixed(0);
        if (mqEl2) mqEl2.textContent = currentMq2 > 0 ? currentMq2.toFixed(0) : '--';

        const updateStatus = (mq, statusElId) => {
            const el = $(`#${statusElId}`);
            if (el) {
                if (mq === 0) {
                    el.textContent = 'MENUNGGU DATA';
                    el.className = 'iot-status';
                } else if (mq > 1500) {
                    el.textContent = 'Bahaya';
                    el.className = 'iot-status danger';
                } else if (mq > 800) {
                    el.textContent = 'Waspada';
                    el.className = 'iot-status warning';
                } else {
                    el.textContent = 'Aman';
                    el.className = 'iot-status normal';
                }
            }
        };
        updateStatus(currentMq, 'mqStatus');
        updateStatus(currentMq2, 'mqStatus_2');

        const updateAlert = (suhu, bannerId, titleId, currentId) => {
            const banner = $(`#${bannerId}`);
            const title = $(`#${titleId}`);
            const curr = $(`#${currentId}`);
            if (banner && title && curr) {
                if (suhu >= 25.0) {
                    banner.className = 'alert-banner danger';
                    title.innerHTML = '❌ PERINGATAN: SUHU TERLALU TINGGI!';
                } else {
                    banner.className = 'alert-banner safe';
                    title.innerHTML = '✅ Suhu Lab Dalam Batas Aman';
                }
                curr.innerHTML = `${suhu.toFixed(1)}&deg;C`;
            }
        };
        updateAlert(currentSuhu, 'alertSuhuBanner', 'alertSuhuTitle', 'alertSuhuCurrent');
        updateAlert(currentSuhu2, 'alertSuhuBanner_2', 'alertSuhuTitle_2', 'alertSuhuCurrent_2');
    }

    // ============================================================
    // ALERT SUHU LAB
    // ============================================================

    const labSettings = { suhuMin: 15, suhuMax: 25, kelembapanMin: 45, kelembapanMax: 75 };

    // Fetch labSettings dari server
    (async function loadLabSettings() {
        try {
            const res = await fetch(`${API_BASE}/data?key=labSettings`);
            if (res.ok) {
                const data = await res.json();
                if (data && data.suhuMin !== undefined) {
                    Object.assign(labSettings, data);
                }
            }
        } catch(e) { /* gunakan default */ }
    })();
    
    


    


    function updateAlertSuhu(currentSuhu) {
        const banner = $('#alertSuhuBanner');
        const title = $('#alertSuhuTitle');
        const desc = $('#alertSuhuDesc');
        const currentEl = $('#alertSuhuCurrent');

        if (!banner) return;

        const isSafe = currentSuhu >= labSettings.suhuMin && currentSuhu <= labSettings.suhuMax;

        if (isSafe) {
            banner.className = 'alert-banner safe';
            if (title) title.textContent = '✅ Suhu Lab Dalam Batas Aman';
        } else {
            banner.className = 'alert-banner danger';
            if (currentSuhu < labSettings.suhuMin) {
                if (title) title.textContent = '❌ PERINGATAN: Suhu Terlalu Rendah!';
            } else {
                if (title) title.textContent = '❌ PERINGATAN: Suhu Terlalu Tinggi!';
            }
        }

        if (desc) desc.innerHTML = `Rentang aman: ${labSettings.suhuMin}°C — ${labSettings.suhuMax}°C | Suhu saat ini: <strong>${currentSuhu.toFixed(1)}°C</strong>`;
        if (currentEl) currentEl.textContent = `${currentSuhu.toFixed(1)}°C`;
    }

    // ============================================================
    // DAILY RECAP
    // ============================================================

    
    let lastRecapUpdate = 0;

    // Fungsi fetch UI
    async function fetchAndRenderRecap(type, dateStr) {
        try {
            const res = await fetch(`${API_BASE}/data?key=recapByDate&date=${dateStr}`);
            const data = await res.json();
            
            if (data && data.date) {
                if (type === 'lab' || type === 'all') {
                    renderRecapLab(data.lab, data.lab2, data.date);
                    iotData._lastRecap = data.lab;
                    iotData._lastRecap2 = data.lab2;
                }
                if (type === 'keuangan' || type === 'all') {
                    renderRecapKeu(data.keuangan, data.date);
                }
            }
        } catch (e) {
            console.error("Gagal fetch recap UI", e);
        }
    }

    function renderRecapLab(lab, lab2, dateStr) {
        const dateEl = $('#recapDate');
        if (dateEl) {
            const d = new Date(dateStr + 'T00:00:00');
            dateEl.textContent = formatDateID(d);
        }
        const set = (id, val) => { const el = $(id); if (el) el.textContent = val; };
        
        // Alat 1
        set('#recapAvgSuhu', `${lab.avgSuhu} °C`);
        set('#recapMinSuhu', `${lab.minSuhu} °C`);
        set('#recapMaxSuhu', `${lab.maxSuhu} °C`);
        set('#recapAvgHum', `${lab.avgHum}%`);
        set('#recapMinHum', `${lab.minHum}%`);
        set('#recapMaxHum', `${lab.maxHum}%`);
        set('#recapAvgMQ', lab.avgMQ);
        set('#recapMinMQ', lab.minMQ);
        set('#recapMaxMQ', lab.maxMQ);
        set('#recapSamples', lab.count);
        set('#recapAlerts', lab.alerts);
        
        // Alat 2
        if (lab2) {
            set('#recapAvgSuhu_2', `${lab2.avgSuhu} °C`);
            set('#recapMinSuhu_2', `${lab2.minSuhu} °C`);
            set('#recapMaxSuhu_2', `${lab2.maxSuhu} °C`);
            set('#recapAvgHum_2', `${lab2.avgHum}%`);
            set('#recapMinHum_2', `${lab2.minHum}%`);
            set('#recapMaxHum_2', `${lab2.maxHum}%`);
            set('#recapAvgMQ_2', lab2.avgMQ);
            set('#recapMinMQ_2', lab2.minMQ);
            set('#recapMaxMQ_2', lab2.maxMQ);
            set('#recapSamples_2', lab2.count);
            set('#recapAlerts_2', lab2.alerts);
        }
    }

    function renderRecapKeu(keu, dateStr) {
        const dateEl = $('#recapDateKeu');
        if (dateEl) {
            const d = new Date(dateStr + 'T00:00:00');
            dateEl.textContent = formatDateID(d);
        }
        const set = (id, val) => { const el = $(id); if (el) el.textContent = val; };
        
        const onM = keu.acOnMinutes;
        const offM = keu.acOffMinutes;
        set('#recapAcOnKeu', `${Math.floor(onM/60)}j ${onM%60}m`);
        set('#recapAcOffKeu', `${Math.floor(offM/60)}j ${offM%60}m`);
        set('#recapAvgSuhuKeu', `${keu.avgTemp} °C`);
    }

    function updateDailyRecap() {
        // Auto-update dipanggil oleh updateRealtimeData setiap 10 detik.
        // HANYA update tabel jika tanggal yang dipilih adalah HARI INI.
        const now = Date.now();
        if (now - lastRecapUpdate < 10000) return;
        lastRecapUpdate = now;

        const todayStr = new Date().toLocaleDateString('en-CA'); // format YYYY-MM-DD
        
        const labDateInput = $('#pdfDateLab');
        if (labDateInput && labDateInput.value === todayStr) {
            fetchAndRenderRecap('lab', todayStr);
        }
        
        const keuDateInput = $('#pdfDateKeu');
        if (keuDateInput && keuDateInput.value === todayStr) {
            fetchAndRenderRecap('keuangan', todayStr);
        }
    }


    // ============================================================
    // PDF GENERATOR (Client-side, tanpa library)
    // ============================================================

    function initPDFButton() {
        
        
        
    }

    

    // ============================================================
    // DOWNLOAD HANDLERS
    // ============================================================

    
    // Bind buttons
    function bindPdfButtons() {
        const btnLab = $('#btnGeneratePDF');
        if (btnLab) btnLab.addEventListener('click', () => generateRealPDF('lab'));
        
        const btnKeu = $('#btnGeneratePDFKeu');
        if (btnKeu) btnKeu.addEventListener('click', () => generateRealPDF('keuangan'));
        
        const dateLab = $('#pdfDateLab');
        if (dateLab) dateLab.addEventListener('change', (e) => fetchAndRenderRecap('lab', e.target.value));
        
        const dateKeu = $('#pdfDateKeu');
        if (dateKeu) dateKeu.addEventListener('change', (e) => fetchAndRenderRecap('keuangan', e.target.value));
        
        // Initial load for today
        const todayStr = new Date().toLocaleDateString('en-CA');
        fetchAndRenderRecap('all', todayStr);
    }

    function generateRealPDF(type) {
        if (!window.jspdf) {
            alert('Library PDF belum termuat sempurna. Silakan tunggu sebentar.');
            return;
        }
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        const now = new Date();
        const dateStr = formatDateID(now);
        
        // Header
        doc.setFontSize(16);
        doc.setTextColor(21, 101, 192);
        doc.text("LAPORAN MONITORING IoT HARIAN", 105, 20, { align: "center" });
        doc.setFontSize(12);
        doc.setTextColor(71, 85, 105);
        doc.text("Command Center SPBE - BBPOM di Bandung", 105, 28, { align: "center" });
        doc.setFontSize(10);
        doc.text(`Tanggal: ${dateStr} | Waktu Generate: ${now.toLocaleTimeString('id-ID')}`, 105, 34, { align: "center" });
        
        doc.setLineWidth(0.5);
        doc.setDrawColor(21, 101, 192);
        doc.line(20, 38, 190, 38);

        if (type === 'keuangan') {
            doc.setFontSize(14);
            doc.setTextColor(21, 101, 192);
            doc.text("Ruang Keuangan (Otomasi AC)", 20, 50);
            
            const onM = Math.round(iotData.acOnMinutes || 0);
            const offM = Math.round(iotData.acOffMinutes || 0);
            
            doc.autoTable({
                startY: 55,
                head: [['Parameter', 'Status / Nilai']],
                body: [
                    ['Status AC Saat Ini', iotData.acStatus ? 'NYALA' : 'MATI'],
                    ['Deteksi Kehadiran', iotData.kehadiran ? 'Ada Orang' : 'Kosong'],
                    ['Rata-rata Suhu Ruangan', iotData.keuanganTemp ? iotData.keuanganTemp.toFixed(1) + ' °C' : '-- °C'],
                    ['Total Durasi AC Menyala', `${Math.floor(onM/60)}j ${onM%60}m`],
                    ['Total Durasi AC Mati', `${Math.floor(offM/60)}j ${offM%60}m`]
                ],
                theme: 'striped',
                headStyles: { fillColor: [21, 101, 192] }
            });
        } else if (type === 'lab') {
            const recap = iotData._lastRecap;
            if (!recap) {
                alert('Data rekap lab belum tersedia.');
                return;
            }
            
            doc.setFontSize(14);
            doc.setTextColor(21, 101, 192);
            doc.text("Ruang Lab Reagen (Monitoring Lingkungan)", 20, 50);
            
            doc.autoTable({
                startY: 55,
                head: [['Parameter', 'Rata-rata', 'Min', 'Max']],
                body: [
                    ['Suhu', `${recap.avgSuhu} °C`, `${recap.minSuhu} °C`, `${recap.maxSuhu} °C`],
                    ['Kelembapan', `${recap.avgHum}%`, `${recap.minHum}%`, `${recap.maxHum}%`],
                    ['Kualitas Udara (MQ)', `${recap.avgMQ} PPM`, `${recap.minMQ} PPM`, `${recap.maxMQ} PPM`]
                ],
                theme: 'striped',
                headStyles: { fillColor: [21, 101, 192] }
            });
            
            doc.text("Status Alert Suhu Hari Ini:", 20, doc.lastAutoTable.finalY + 15);
            doc.setTextColor(recap.alerts > 0 ? 153 : 22, 27, 101); // Red or Green
            doc.text(`${recap.alerts} kali melampaui batas aman.`, 20, doc.lastAutoTable.finalY + 22);
        }
        
        doc.setFontSize(9);
        doc.setTextColor(148, 163, 184);
        doc.text("Dokumen ini digenerate otomatis oleh sistem IoT BBPOM Bandung.", 105, 280, { align: "center" });
        
        
        const blob = doc.output('blob');
        const url = URL.createObjectURL(blob);
        window.open(url, '_blank');

    }


    /**
     * Generate dan download file CSV dari data IoT
     */
    function generateCSV() {
        if (!iotData || !iotData.suhu) {
            alert('Data tidak tersedia');
            return;
        }

        let csv = 'Waktu,Suhu (C),Kelembapan (%),MQ (PPM),Voltage (V),Ampere (A),Watt (W)\n';
        
        for (let i = 0; i < 20; i++) {
            const time = new Date(Date.now() - (20 - i) * 3000).toLocaleTimeString('id-ID');
            const row = [
                time,
                iotData.suhu[i] || iotData.suhu[0],
                iotData.kelembapan[i] || iotData.kelembapan[0],
                iotData.mq[i] || 0,
                iotData.voltage,
                iotData.ampere,
                iotData.watt
            ];
            csv += row.join(',') + '\n';
        }

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', `IoT_Log_${new Date().toISOString().slice(0,10)}.csv`);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }

    // ============================================================
    // WINDOW RESIZE HANDLER
    // ============================================================

    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            // Re-draw charts yang visible
            const activePage = $('.page.active');
            if (activePage) {
                if (activePage.id === 'page-iot') {
                    resizeIoTCharts();
                }
                if (activePage.id === 'page-aset') {
                    drawDonutChart();
                }
            }
        }, 250);
    });





    function updateTimestamp() {
        const t = document.querySelector('#iotTimestamp');
        if (t) {
            const date = new Date();
            t.textContent = 'Terakhir update: ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
    }


    


    






    function initIoTTabs() {
        const tabBtns = document.querySelectorAll('.iot-tab-btn');
        const tabPanes = document.querySelectorAll('.iot-tab-pane');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));

                btn.classList.add('active');
                const targetId = btn.getAttribute('data-target');
                const targetPane = document.getElementById(targetId);
                if (targetPane) {
                    targetPane.classList.add('active');
                    if (typeof resizeIoTCharts === 'function') {
                        resizeIoTCharts();
                    }
                    if (window.Chart) {
                        for (let id in Chart.instances) {
                            Chart.instances[id].resize();
                        }
                    }
                }
            });
        });
    }

})();
