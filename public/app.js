/**
 * ============================================================
 * COMMAND CENTER SPBE BBPOM BANDUNG
 * Main Application Script (app.js)
 * ============================================================
 */

;(function () {
    'use strict';

    // ============================================================
    // UTILITY FUNCTIONS
    // ============================================================

    const $ = (sel) => document.querySelector(sel);
    const $$ = (sel) => document.querySelectorAll(sel);

    function formatDateID(date) {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return `${days[date.getDay()]}, ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    function formatTime(date) {
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }

    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    // ============================================================
    // INITIALIZATION
    // ============================================================

    if (typeof window.API_BASE === 'undefined') {
        // Will be set by <script> in dashboard.php via base_url
    }

    document.addEventListener('DOMContentLoaded', async () => {
        initIoTTabs();

        if (typeof API_BASE === 'undefined' || !API_BASE) {
            window.API_BASE = window.location.pathname.replace(/\/+$/, '').replace('/public', '/public/api');
        }

        initHeader();
        bindPdfButtons();
        initGauges();
        loadLabSettings();
        initCustomIoTManager();

        // Draw initial empty charts, then start fetching
        await loadCustomIoTConfig();
        initIoTCharts();
        startRealtimeSimulation();
    });

    function initHeader() {
        const dateEl = $('#headerDate');
        if (dateEl) dateEl.textContent = formatDateID(new Date());
    }

    // ============================================================
    // IoT DATA STORE
    // ============================================================

    const iotData = {
        suhu: Array(20).fill(0),
        kelembapan: Array(20).fill(0),
        mq: Array(20).fill(0),
        suhu_2: Array(20).fill(0),
        kelembapan_2: Array(20).fill(0),
        mq_2: Array(20).fill(0),
        lumensKeu: [365, 370, 375, 380, 385, 378, 382, 390, 384, 376, 380, 385, 388, 379, 382, 386, 381, 377, 383, 380],
        voltage: 220,
        ampere: 4.5,
        watt: 990,
        acStatus: 'OFF',
        keuanganKehadiran: 0
    };

    // State Store for Light Intensity (Lumens) & Servo Lamp Switch Devices
    const lightServoStore = {
        'ALAT_2_CAHAYA': {
            mode: 'auto',           // 'auto' (Otomatis Sensor Cahaya) | 'manual' (Switch Manual Web)
            lampStatus: 'ON',       // 'ON' (Menyala) | 'OFF' (Sebagian Dimatikan)
            servoAngle: 90,         // 90° (Tekan Saklar ON) | 0° (Tekan Saklar OFF)
            lumens: 380,
            minLumens: 250,         // < 250 lm = Ruangan Terlalu Gelap -> Servo Hidupkan Lampu
            maxLumens: 500,         // > 500 lm = Ruangan Terlalu Terang -> Servo Matikan Sebagian Lampu
            servoActionsToday: 4,
            lightOffCountToday: 2,
            simOverrideLumens: null
        }
    };

    // Store for dynamically added rooms and devices
    let customConfig = {
        rooms: [],
        devices: []
    };

    // Runtime time-series buffer for custom devices
    const customRuntimeData = {};

    // Per-room realtime vs history filter state
    const roomRealtimeState = {
        'tab-lab': true,
        'tab-keuangan': true
    };

    // ============================================================
    // CANVAS LINE CHARTS
    // ============================================================

    function initIoTCharts() {
        redrawAllCharts();
    }

    function redrawAllCharts() {
        if (isRealtime) {
            drawLineChart('chartSuhu', iotData.suhu, '#C62828', 'rgba(198, 40, 40, 0.08)', 10, 40, 10);
            drawLineChart('chartKelembapan', iotData.kelembapan, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80, 10);
            drawLineChart('chartMQ', iotData.mq, '#a855f7', 'rgba(168, 85, 247, 0.08)', 500, 2500, 500);

            drawLineChart('chartSuhu_2', iotData.suhu_2, '#C62828', 'rgba(198, 40, 40, 0.08)', 10, 40, 10);
            drawLineChart('chartHum_2', iotData.kelembapan_2, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80, 10);
            drawLineChart('chartMQ_2', iotData.mq_2, '#a855f7', 'rgba(168, 85, 247, 0.08)', 500, 2500, 500);
        }

        if (roomRealtimeState['tab-keuangan'] !== false) {
            drawLineChart('chartCahayaKeu', iotData.lumensKeu, '#f59e0b', 'rgba(245, 158, 11, 0.12)', 0, 800, 200);
        }

        // Redraw custom device charts
        redrawCustomDeviceCharts();
    }

    function drawLineChart(canvasId, data, lineColor, fillColor, baseMinY, baseMaxY, stepY) {
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

        // Calculate dynamic Y range based on stepY and minimum baseMaxY
        let minY = baseMinY;
        let maxY = baseMaxY;
        
        const realValues = data.filter(v => v > 0);
        if (realValues.length > 0) {
            const dataMax = Math.max(...realValues);
            
            // Adjust maxY upwards if data goes above baseMaxY
            while (dataMax > maxY) {
                maxY += stepY;
            }
        }

        // Grid lines (horizontal)
        const gridLines = Math.max(1, Math.round((maxY - minY) / stepY));
        ctx.strokeStyle = 'rgba(200, 200, 200, 0.3)';
        ctx.lineWidth = 1;
        for (let i = 0; i <= gridLines; i++) {
            const y = padding.top + (chartHeight / gridLines) * i;
            ctx.beginPath();
            ctx.moveTo(padding.left, y);
            ctx.lineTo(displayWidth - padding.right, y);
            ctx.stroke();

            // Y-axis labels
            const val = maxY - stepY * i;
            ctx.fillStyle = 'rgba(71, 85, 105, 0.7)';
            ctx.font = '500 11px "Public Sans", Inter, sans-serif';
            ctx.textAlign = 'right';
            ctx.fillText(val.toFixed(0), padding.left - 6, y + 4);
        }

        if (data.length < 2) return;

        // Clamp data points to chart area so lines don't bleed outside
        const clamp = (val) => Math.max(minY, Math.min(maxY, val));

        // Calculate points
        const points = data.map((val, idx) => ({
            x: padding.left + (idx / (data.length - 1)) * chartWidth,
            y: padding.top + chartHeight - ((clamp(val) - minY) / (maxY - minY)) * chartHeight
        }));

        // Save context and clip to chart area so nothing draws outside
        ctx.save();
        ctx.beginPath();
        ctx.rect(padding.left, padding.top, chartWidth, chartHeight);
        ctx.clip();

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

        // Restore context (removes clip)
        ctx.restore();

        // Draw last point indicator (outside clip so it's always visible)
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
        redrawAllCharts();
    }
    window.resizeIoTCharts = resizeIoTCharts;

    // ============================================================
    // ELECTRIC GAUGES (SVG)
    // ============================================================

    function initGauges() {
        const circumference = 2 * Math.PI * 42;
        $$('.gauge-fill').forEach(gauge => {
            gauge.style.strokeDasharray = circumference;
            const targetValue = parseInt(gauge.getAttribute('data-value'));
            const offset = circumference - (targetValue / 100) * circumference;
            setTimeout(() => { gauge.style.strokeDashoffset = offset; }, 600);
        });
    }

    function updateGauges() {
        const voltageNorm = ((iotData.voltage - 200) / 40) * 100;
        const ampereNorm = (iotData.ampere / 10) * 100;
        const wattNorm = (iotData.watt / 1500) * 100;
        const circumference = 2 * Math.PI * 42;

        const voltageFill = $('.voltage-fill');
        const ampereFill = $('.ampere-fill');
        const wattFill = $('.watt-fill');

        if (voltageFill) voltageFill.style.strokeDashoffset = circumference - (voltageNorm / 100) * circumference;
        if (ampereFill) ampereFill.style.strokeDashoffset = circumference - (ampereNorm / 100) * circumference;
        if (wattFill) wattFill.style.strokeDashoffset = circumference - (wattNorm / 100) * circumference;

        const vEl = $('#voltageValue');
        const aEl = $('#ampereValue');
        const wEl = $('#wattValue');
        if (vEl) vEl.textContent = iotData.voltage.toFixed(0);
        if (aEl) aEl.textContent = iotData.ampere.toFixed(1);
        if (wEl) wEl.textContent = iotData.watt.toFixed(0);
    }

    // ============================================================
    // BADGE & ALERT HELPERS (shared by realtime + history)
    // ============================================================

    function updateBadge(val, type, statusElId, customMin, customMax) {
        const el = $(`#${statusElId}`);
        if (!el) return;

        if (val === 0 && type === 'mq') {
            el.textContent = 'MENUNGGU DATA';
            el.className = 'iot-status';
            return;
        }

        let isDanger = false;
        let isWarning = false;

        if (type === 'suhu') {
            const minLim = customMin !== undefined ? customMin : 15.0;
            const maxLim = customMax !== undefined ? customMax : 25.0;
            if (val >= maxLim) isDanger = true;
            else if (val <= minLim && val > 0) isWarning = true;
        } else if (type === 'hum') {
            if (val >= 75 || val <= 45) isWarning = true;
        } else if (type === 'mq') {
            if (val > 1500) isDanger = true;
            else if (val > 800) isWarning = true;
        } else if (type === 'custom') {
            if (customMax !== undefined && val >= customMax) isDanger = true;
            else if (customMax !== undefined && val >= customMax * 0.85) isWarning = true;
        }

        if (isDanger) {
            el.textContent = 'Bahaya';
            el.className = 'iot-status danger';
        } else if (isWarning) {
            el.textContent = 'Waspada';
            el.className = 'iot-status warning';
        } else {
            el.textContent = 'Aman';
            el.className = 'iot-status normal';
        }
    }

    function updateAlertBanner(suhu, bannerId, titleId, descId, currentId, minLim, maxLim) {
        const banner = $(`#${bannerId}`);
        const title = $(`#${titleId}`);
        const desc = $(`#${descId}`);
        const curr = $(`#${currentId}`);

        const sMin = minLim !== undefined ? minLim : labSettings.suhuMin;
        const sMax = maxLim !== undefined ? maxLim : labSettings.suhuMax;

        if (banner && title) {
            if (suhu >= sMax) {
                banner.className = 'alert-banner danger';
                title.innerHTML = '<i class="ti ti-alert-triangle-filled"></i> PERINGATAN: SUHU TERLALU TINGGI!';
            } else if (suhu <= sMin && suhu > 0) {
                banner.className = 'alert-banner danger';
                title.innerHTML = '<i class="ti ti-alert-triangle-filled"></i> PERINGATAN: SUHU TERLALU RENDAH!';
            } else {
                banner.className = 'alert-banner safe';
                title.innerHTML = '<i class="ti ti-circle-check-filled"></i> Suhu Ruangan Dalam Batas Aman';
            }
        }

        if (desc) {
            desc.innerHTML = `Rentang aman: ${sMin}&deg;C &mdash; ${sMax}&deg;C | Suhu saat ini: <strong>${suhu.toFixed(1)}&deg;C</strong>`;
        }
        if (curr) {
            curr.innerHTML = `${suhu.toFixed(1)}&deg;C`;
        }
    }

    // ============================================================
    // UPDATE IoT DISPLAY (values, badges, alerts)
    // ============================================================

    function updateIoTDisplay() {
        const currentSuhu = iotData.suhu[iotData.suhu.length - 1] || 0;
        const currentKelembapan = iotData.kelembapan[iotData.kelembapan.length - 1] || 0;
        const currentMq = iotData.mq[iotData.mq.length - 1] || 0;

        const currentSuhu2 = iotData.suhu_2[iotData.suhu_2.length - 1] || 0;
        const currentKelembapan2 = iotData.kelembapan_2[iotData.kelembapan_2.length - 1] || 0;
        const currentMq2 = iotData.mq_2[iotData.mq_2.length - 1] || 0;

        // Update value displays - Alat 1
        const suhuEl = $('#suhuValue');
        const kelembapanEl = $('#kelembapanValue');
        const mqEl = $('#mqValue');
        if (suhuEl) suhuEl.textContent = currentSuhu.toFixed(1);
        if (kelembapanEl) kelembapanEl.textContent = currentKelembapan.toFixed(0);
        if (mqEl) mqEl.textContent = currentMq > 0 ? currentMq.toFixed(0) : '--';

        // Update value displays - Alat 2
        const suhuEl2 = $('#suhuValue_2');
        const humEl2 = $('#humValue_2');
        const mqEl2 = $('#mqValue_2');
        if (suhuEl2) suhuEl2.textContent = currentSuhu2.toFixed(1);
        if (humEl2) humEl2.textContent = currentKelembapan2.toFixed(0);
        if (mqEl2) mqEl2.textContent = currentMq2 > 0 ? currentMq2.toFixed(0) : '--';

        // Update badges - Alat 1
        updateBadge(currentSuhu, 'suhu', 'suhuStatus');
        updateBadge(currentKelembapan, 'hum', 'humStatus');
        updateBadge(currentMq, 'mq', 'mqStatus');

        // Update badges - Alat 2
        updateBadge(currentSuhu2, 'suhu', 'suhuStatus_2');
        updateBadge(currentKelembapan2, 'hum', 'humStatus_2');
        updateBadge(currentMq2, 'mq', 'mqStatus_2');

        // Update alert banners
        updateAlertBanner(currentSuhu, 'alertSuhuBanner', 'alertSuhuTitle', 'alertSuhuDesc', 'alertSuhuCurrent');
        updateAlertBanner(currentSuhu2, 'alertSuhuBanner_2', 'alertSuhuTitle_2', 'alertSuhuDesc_2', 'alertSuhuCurrent_2');
    }

    // ============================================================
    // REAL-TIME DATA FETCHING
    // ============================================================

    let isRealtime = true;

    function startRealtimeSimulation() {
        fetchRealtimeData();
        setInterval(fetchRealtimeData, 10000);
    }

    async function fetchRealtimeData() {
        let apiCustomDevices = {};

        try {
            const res = await fetch(`${API_BASE}/data?key=sensorData`);
            if (res.ok) {
                const dataObj = await res.json();

                if (isRealtime) {
                    // --- ALAT 1 (AC) ---
                    if (dataObj.lab_ac && dataObj.lab_ac.length > 0) {
                        const lab1 = dataObj.lab_ac;
                        const mqValues = lab1.map(d => d.mq || 0);
                        const suhuValues = lab1.map(d => d.suhu || 0);
                        const humValues = lab1.map(d => d.hum || 0);

                        const padVal = (arr) => { while (arr.length < 20) arr.unshift(arr[0] || 0); return arr.slice(-20); };
                        iotData.mq = padVal(mqValues);
                        iotData.suhu = padVal(suhuValues);
                        iotData.kelembapan = padVal(humValues);
                    }

                    // --- ALAT 2 (NON-AC) ---
                    if (dataObj.lab_non_ac && dataObj.lab_non_ac.length > 0) {
                        const lab2 = dataObj.lab_non_ac;
                        const mqValues2 = lab2.map(d => d.mq || 0);
                        const suhuValues2 = lab2.map(d => d.suhu || 0);
                        const humValues2 = lab2.map(d => d.hum || 0);

                        const padVal = (arr) => { while (arr.length < 20) arr.unshift(arr[0] || 0); return arr.slice(-20); };
                        iotData.mq_2 = padVal(mqValues2);
                        iotData.suhu_2 = padVal(suhuValues2);
                        iotData.kelembapan_2 = padVal(humValues2);
                    }
                }

                // --- KEUANGAN (ALAT 1 - AC) ---
                if (roomRealtimeState['tab-keuangan'] !== false && dataObj.keuangan && dataObj.keuangan.length > 0) {
                    const currentKeu = dataObj.keuangan[dataObj.keuangan.length - 1];
                    iotData.acStatus = currentKeu.acStatus ? 'ON' : 'OFF';
                    iotData.keuanganKehadiran = currentKeu.kehadiran || 0;
                    const kTempEl = $('#keuanganTemp');
                    const kHumEl = $('#keuanganHum');
                    if (kTempEl && currentKeu.suhu !== undefined) kTempEl.innerHTML = `${Number(currentKeu.suhu).toFixed(1)}&deg;C`;
                    if (kHumEl && currentKeu.hum !== undefined) kHumEl.textContent = `${Number(currentKeu.hum).toFixed(0)}%`;
                }

                // --- KEUANGAN ALAT 2 (SENSOR CAHAYA LUMENS & SERVO SAKLAR LAMPU) ---
                if (dataObj.light_servo_state && dataObj.light_servo_state.ALAT_2_CAHAYA) {
                    const srv = dataObj.light_servo_state.ALAT_2_CAHAYA;
                    const ls = lightServoStore['ALAT_2_CAHAYA'];
                    if (!ls._localModifiedAt || (Date.now() - ls._localModifiedAt > 15000)) {
                        ls.mode = srv.mode || ls.mode;
                        ls.lampStatus = srv.lampStatus || ls.lampStatus;
                        ls.servoAngle = srv.servoAngle !== undefined ? srv.servoAngle : ls.servoAngle;
                    }
                }
                if (roomRealtimeState['tab-keuangan'] !== false) {
                    const padVal = (arr) => { while (arr.length < 20) arr.unshift(arr[0] || 380); return arr.slice(-20); };
                    if (dataObj.keuangan_cahaya && dataObj.keuangan_cahaya.length > 0) {
                        iotData.lumensKeu = padVal(dataObj.keuangan_cahaya.map(d => d.lumens || 380));
                        const lastC = dataObj.keuangan_cahaya[dataObj.keuangan_cahaya.length - 1];
                        lightServoStore['ALAT_2_CAHAYA'].lumens = lastC.lumens || 380;
                    } else {
                        // Simulasi halus saat sensor masih tahap perakitan
                        const ls = lightServoStore['ALAT_2_CAHAYA'];
                        const baseLumens = ls.simOverrideLumens !== null ? ls.simOverrideLumens : 380;
                        const nextLumens = Math.round(Math.max(50, Math.min(780, baseLumens + (Math.random() * 18 - 9))));
                        ls.lumens = nextLumens;
                        iotData.lumensKeu.push(nextLumens);
                        if (iotData.lumensKeu.length > 20) iotData.lumensKeu.shift();
                    }
                }

                // --- CUSTOM DEVICES ---
                if (dataObj.custom_devices) {
                    apiCustomDevices = dataObj.custom_devices;
                }
            }
        } catch (err) {
            console.error("Gagal fetch sensorData", err);
        }

        // Simulated electrical data
        iotData.voltage = parseFloat((220 + (Math.random() * 10 - 5)).toFixed(0));
        iotData.ampere = parseFloat((4.5 + (Math.random() * 1.6 - 0.8)).toFixed(1));
        iotData.watt = parseFloat((iotData.voltage * iotData.ampere).toFixed(0));

        // Update all displays
        if (isRealtime) {
            updateIoTDisplay();
        }
        updateGauges();
        updateCustomDevicesRealtime(apiCustomDevices);

        // Update AC status & Alat 2 Sensor Cahaya + Servo in Ruang Keuangan
        if (roomRealtimeState['tab-keuangan'] !== false) {
            const acStatusEl = $('#acStatusValue');
            if (acStatusEl) {
                acStatusEl.textContent = iotData.acStatus;
                acStatusEl.className = iotData.acStatus === 'ON' ? 'fw-bold mb-0 text-success' : 'fw-bold mb-0 text-danger';
            }

            const occEl = $('#occupancyValue');
            if (occEl) occEl.textContent = iotData.keuanganKehadiran + ' people';

            updateKeuanganCahayaServoUI();
        }

        // Redraw charts
        redrawAllCharts();

        // Update recap & timestamp
        updateDailyRecap();
        updateTimestamp();
    }

    // ============================================================
    // SENSOR INTENSITAS CAHAYA (LUMENS) & SERVO SAKLAR LAMPU ENGINE
    // ============================================================

    async function saveLightServoState() {
        try {
            await fetch(`${API_BASE}/data?key=lightServoControl`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(lightServoStore)
            });
        } catch (e) {
            console.warn("Gagal menyimpan state servo lampu ke server", e);
        }
    }

    function evaluateAutoLightServo(devKey) {
        const ls = lightServoStore[devKey];
        if (!ls || ls.mode !== 'auto') return;

        const prevLamp = ls.lampStatus;
        if (ls.lumens > ls.maxLumens) {
            // Ruangan terlalu terang -> Servo mematikan sebagian lampu
            ls.lampStatus = 'OFF';
            ls.servoAngle = 0;
            if (prevLamp !== 'OFF') {
                ls.servoActionsToday = (ls.servoActionsToday || 0) + 1;
                ls.lightOffCountToday = (ls.lightOffCountToday || 0) + 1;
            }
        } else if (ls.lumens < ls.minLumens) {
            // Ruangan terlalu gelap -> Servo menghidupkan lampu
            ls.lampStatus = 'ON';
            ls.servoAngle = 90;
            if (prevLamp !== 'ON') {
                ls.servoActionsToday = (ls.servoActionsToday || 0) + 1;
            }
        }
    }

    function updateKeuanganCahayaServoUI() {
        const ls = lightServoStore['ALAT_2_CAHAYA'];
        if (!ls) return;

        if (ls.mode === 'auto') {
            evaluateAutoLightServo('ALAT_2_CAHAYA');
        }

        const isLampOn = ls.lampStatus === 'ON';
        const curLumens = ls.lumens || 380;

        // 1. Update Big Lumens Display & Status Badge
        const valEl = $('#cahayaValueKeu');
        if (valEl) valEl.textContent = curLumens;

        const statusBadge = $('#cahayaStatusKeu');
        if (statusBadge) {
            if (curLumens > ls.maxLumens) {
                statusBadge.textContent = 'Terlalu Terang';
                statusBadge.className = 'iot-status warning';
            } else if (curLumens < ls.minLumens) {
                statusBadge.textContent = 'Terlalu Gelap';
                statusBadge.className = 'iot-status danger';
            } else {
                statusBadge.textContent = 'Ideal';
                statusBadge.className = 'iot-status normal';
            }
        }

        // 2. Update Banner Status Pencahayaan & Servo
        const banner = $('#alertCahayaBanner');
        const bTitle = $('#alertCahayaTitle');
        const bDesc = $('#alertCahayaDesc');
        if (banner && bTitle && bDesc) {
            if (curLumens > ls.maxLumens) {
                banner.className = 'alert-banner danger';
                bTitle.innerHTML = `<i class="ti ti-sun-high"></i> Ruangan Terlalu Terang (${curLumens} lm) &mdash; ${ls.mode === 'auto' ? 'Servo Otomatis Mematikan Sebagian Lampu' : 'Mode Manual Aktif'}`;
            } else if (curLumens < ls.minLumens) {
                banner.className = 'alert-banner danger';
                bTitle.innerHTML = `<i class="ti ti-moon-stars"></i> Ruangan Terlalu Gelap (${curLumens} lm) &mdash; ${ls.mode === 'auto' ? 'Servo Otomatis Menghidupkan Saklar Lampu' : 'Mode Manual Aktif'}`;
            } else {
                banner.className = 'alert-banner safe';
                bTitle.innerHTML = `<i class="ti ti-circle-check-filled"></i> Intensitas Cahaya Ruangan Ideal (${curLumens} lm) &mdash; Pencahayaan &amp; Servo Stabil`;
            }
            bDesc.innerHTML = `Batas Gelap: &lt; ${ls.minLumens} lm (Servo ON) | Batas Terang: &gt; ${ls.maxLumens} lm (Servo OFF) | Saat ini: <strong>${curLumens} lm</strong>`;
        }

        // 3. Update Visual Bulb, Servo Angle, Mode Buttons & Manual Switch
        const lampIcon = $('#lampVisualIcon');
        const lampVal = $('#lampStatusValue');
        const servoBadge = $('#servoAngleBadge');
        const reasonEl = $('#lampActionReason');
        const modeBadge = $('#servoModeBadge');
        const btnAuto = $('#btnModeAutoKeu');
        const btnManual = $('#btnModeManualKeu');
        const switchInput = $('#switchManualLampKeu');
        const btnOn = $('#btnForceLampOnKeu');
        const btnOff = $('#btnForceLampOffKeu');
        const counterEl = $('#servoActionCounter');

        if (lampIcon) {
            if (isLampOn) {
                lampIcon.className = 'ti ti-bulb-filled';
                lampIcon.style.color = '#f59e0b';
                lampIcon.style.filter = 'drop-shadow(0 0 14px rgba(245, 158, 11, 0.55))';
            } else {
                lampIcon.className = 'ti ti-bulb-off';
                lampIcon.style.color = '#94a3b8';
                lampIcon.style.filter = 'none';
            }
        }

        if (lampVal) {
            lampVal.textContent = isLampOn ? 'LAMPU ON' : 'SEBAGIAN OFF';
            lampVal.className = isLampOn ? 'fw-bold mb-1 text-success' : 'fw-bold mb-1 text-secondary';
        }

        if (servoBadge) {
            servoBadge.innerHTML = isLampOn
                ? `<i class="ti ti-settings-automation me-1"></i>Servo: ${ls.servoAngle}&deg; (Tekan Saklar ON)`
                : `<i class="ti ti-settings-automation me-1"></i>Servo: ${ls.servoAngle}&deg; (Tekan Saklar OFF)`;
            servoBadge.className = isLampOn ? 'badge bg-label-success mt-1' : 'badge bg-label-warning mt-1';
        }

        if (reasonEl) {
            if (ls.mode === 'manual') {
                reasonEl.textContent = `Dikendalikan via Switch Manual Web (${isLampOn ? 'ON' : 'OFF'})`;
            } else if (curLumens > ls.maxLumens) {
                reasonEl.textContent = 'Ruangan terlalu terang -> Servo mematikan sebagian lampu';
            } else if (curLumens < ls.minLumens) {
                reasonEl.textContent = 'Ruangan terlalu gelap -> Servo menghidupkan lampu';
            } else {
                reasonEl.textContent = 'Cahaya ruangan ideal / dipertahankan';
            }
        }

        if (modeBadge) {
            modeBadge.textContent = ls.mode === 'auto' ? 'Mode: Otomatis Sensor' : 'Mode: Manual Switch Web';
            modeBadge.className = ls.mode === 'auto' ? 'iot-status normal' : 'iot-status warning';
        }

        if (btnAuto && btnManual) {
            if (ls.mode === 'auto') {
                btnAuto.className = 'btn btn-primary';
                btnAuto.style.backgroundColor = '#3b327b';
                btnAuto.style.borderColor = '#3b327b';
                btnManual.className = 'btn btn-outline-secondary';
                btnManual.style.backgroundColor = '';
                btnManual.style.borderColor = '';
            } else {
                btnManual.className = 'btn btn-primary';
                btnManual.style.backgroundColor = '#3b327b';
                btnManual.style.borderColor = '#3b327b';
                btnAuto.className = 'btn btn-outline-secondary';
                btnAuto.style.backgroundColor = '';
                btnAuto.style.borderColor = '';
            }
        }

        if (switchInput) switchInput.checked = isLampOn;
        if (btnOn && btnOff) {
            btnOn.className = isLampOn
                ? 'btn btn-sm btn-success flex-fill d-flex align-items-center justify-content-center gap-1'
                : 'btn btn-sm btn-outline-success flex-fill d-flex align-items-center justify-content-center gap-1';
            btnOff.className = !isLampOn
                ? 'btn btn-sm btn-danger flex-fill d-flex align-items-center justify-content-center gap-1'
                : 'btn btn-sm btn-outline-danger flex-fill d-flex align-items-center justify-content-center gap-1';
        }

        if (counterEl) {
            counterEl.innerHTML = `<i class="ti ti-rotate-clockwise me-1"></i>Aksi Servo Hari Ini: ${ls.servoActionsToday || 4}x`;
        }

        // 4. Update Rekap Harian Alat 2 Ruang Keuangan
        const validLumens = iotData.lumensKeu.filter(v => v > 0);
        const avgL = validLumens.length ? Math.round(validLumens.reduce((a, b) => a + b, 0) / validLumens.length) : curLumens;
        const minL = validLumens.length ? Math.min(...validLumens) : curLumens;
        const maxL = validLumens.length ? Math.max(...validLumens) : curLumens;

        const setTxt = (sel, txt) => { const el = $(sel); if (el) el.textContent = txt; };
        setTxt('#recapAvgLumensKeu', `${avgL} lm`);
        setTxt('#recapMinLumensKeu', `${minL} lm`);
        setTxt('#recapMaxLumensKeu', `${maxL} lm`);
        setTxt('#recapSamplesLumensKeu', validLumens.length || 20);
        setTxt('#recapServoStatusKeu', isLampOn ? `Lampu ON (Servo ${ls.servoAngle}°)` : `Sebagian OFF (Servo ${ls.servoAngle}°)`);
        setTxt('#recapServoModeKeu', ls.mode === 'auto' ? 'Otomatis Sensor' : 'Manual Switch Web');
        setTxt('#recapServoActionsKeu', `${ls.servoActionsToday || 4}x Penggerak`);
        setTxt('#recapLightOffCountKeu', ls.lightOffCountToday || 2);
    }

    window.setLightServoMode = function(deviceId, mode) {
        if (!lightServoStore[deviceId]) {
            lightServoStore[deviceId] = { mode: 'auto', lampStatus: 'ON', servoAngle: 90, lumens: 380, minLumens: 250, maxLumens: 500, servoActionsToday: 1, lightOffCountToday: 0, simOverrideLumens: null };
        }
        const ls = lightServoStore[deviceId];
        ls.mode = mode;
        ls._localModifiedAt = Date.now();
        if (mode === 'auto') {
            evaluateAutoLightServo(deviceId);
        }
        if (deviceId === 'ALAT_2_CAHAYA') {
            updateKeuanganCahayaServoUI();
        } else {
            updateCustomLightServoUI(deviceId);
        }
        saveLightServoState();
    };

    window.toggleLightServoManual = function(deviceId, turnOn) {
        if (!lightServoStore[deviceId]) {
            lightServoStore[deviceId] = { mode: 'manual', lampStatus: 'ON', servoAngle: 90, lumens: 380, minLumens: 250, maxLumens: 500, servoActionsToday: 1, lightOffCountToday: 0, simOverrideLumens: null };
        }
        const ls = lightServoStore[deviceId];
        ls.mode = 'manual';
        ls.lampStatus = turnOn ? 'ON' : 'OFF';
        ls.servoAngle = turnOn ? 90 : 0;
        ls.servoActionsToday = (ls.servoActionsToday || 0) + 1;
        if (!turnOn) ls.lightOffCountToday = (ls.lightOffCountToday || 0) + 1;
        ls._localModifiedAt = Date.now();

        if (deviceId === 'ALAT_2_CAHAYA') {
            updateKeuanganCahayaServoUI();
        } else {
            updateCustomLightServoUI(deviceId);
        }
        saveLightServoState();
    };

    window.simulateLumensCondition = function(deviceId, targetLumens) {
        if (!lightServoStore[deviceId]) return;
        const ls = lightServoStore[deviceId];
        ls.mode = 'auto';
        ls.simOverrideLumens = targetLumens;
        ls.lumens = targetLumens;
        ls._localModifiedAt = Date.now();

        if (deviceId === 'ALAT_2_CAHAYA') {
            iotData.lumensKeu.push(targetLumens);
            if (iotData.lumensKeu.length > 20) iotData.lumensKeu.shift();
            evaluateAutoLightServo('ALAT_2_CAHAYA');
            updateKeuanganCahayaServoUI();
            drawLineChart('chartCahayaKeu', iotData.lumensKeu, '#f59e0b', 'rgba(245, 158, 11, 0.12)', 0, 800, 200);
        } else {
            const devObj = customConfig.devices.find(d => d.deviceId === deviceId || d.id === deviceId);
            if (devObj && customRuntimeData[devObj.id]) {
                if (!customRuntimeData[devObj.id].lumens) customRuntimeData[devObj.id].lumens = Array(20).fill(380);
                customRuntimeData[devObj.id].lumens.push(targetLumens);
                if (customRuntimeData[devObj.id].lumens.length > 20) customRuntimeData[devObj.id].lumens.shift();
                evaluateAutoLightServo(deviceId);
                updateCustomLightServoUI(deviceId);
                drawLineChart(`chartLight_${devObj.id}`, customRuntimeData[devObj.id].lumens, '#f59e0b', 'rgba(245, 158, 11, 0.12)', 0, 800, 200);
            }
        }
        saveLightServoState();
    };

    // ============================================================
    // DYNAMIC ROOMS & DEVICES ENGINE (TAMBAH RUANGAN & ALAT DI WEB)
    // ============================================================

    const STORAGE_KEY_CUSTOM = 'geulis_custom_iot_config_v1';

    async function loadCustomIoTConfig() {
        // 1. Load from localStorage first for instant UI
        try {
            const localRaw = localStorage.getItem(STORAGE_KEY_CUSTOM);
            if (localRaw) {
                const parsed = JSON.parse(localRaw);
                if (parsed && Array.isArray(parsed.rooms) && Array.isArray(parsed.devices)) {
                    customConfig = parsed;
                }
            }
        } catch (e) {}

        // 2. Sync from backend database (spbe_json_store)
        try {
            const res = await fetch(`${API_BASE}/data?key=customConfig`);
            if (res.ok) {
                const data = await res.json();
                if (data && data.config && Array.isArray(data.config.rooms) && Array.isArray(data.config.devices)) {
                    // Use server config if it has items, or sync local to server
                    if (data.config.rooms.length > 0 || data.config.devices.length > 0) {
                        customConfig = data.config;
                        localStorage.setItem(STORAGE_KEY_CUSTOM, JSON.stringify(customConfig));
                    } else if (customConfig.rooms.length > 0 || customConfig.devices.length > 0) {
                        await saveCustomIoTConfig();
                    }
                }
            }
        } catch (e) {
            console.warn("Menggunakan konfigurasi lokal", e);
        }

        renderCustomRoomsAndDevices();
    }

    async function saveCustomIoTConfig() {
        try {
            localStorage.setItem(STORAGE_KEY_CUSTOM, JSON.stringify(customConfig));
        } catch (e) {}

        try {
            await fetch(`${API_BASE}/data?key=customConfig`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(customConfig)
            });
        } catch (e) {
            console.warn("Gagal menyimpan customConfig ke server, tersimpan di browser.", e);
        }
    }

    function initCustomIoTManager() {
        const formRoom = $('#formTambahRuangan');
        if (formRoom) {
            formRoom.addEventListener('submit', async (e) => {
                e.preventDefault();
                const name = ($('#inputRoomName').value || '').trim();
                const icon = $('#inputRoomIcon').value || 'ti-flask';
                const desc = ($('#inputRoomDesc').value || '').trim();

                if (!name) return;

                const roomId = 'room_' + Date.now();
                customConfig.rooms.push({
                    id: roomId,
                    name: name,
                    icon: icon,
                    desc: desc || 'Monitoring lingkungan ruangan secara real-time'
                });

                await saveCustomIoTConfig();
                renderCustomRoomsAndDevices();

                formRoom.reset();
                const modalEl = document.getElementById('modalTambahRuangan');
                if (modalEl && window.bootstrap) {
                    const modalInst = bootstrap.Modal.getInstance(modalEl);
                    if (modalInst) modalInst.hide();
                }

                // Switch to the newly created room tab
                setTimeout(() => {
                    const newTabBtn = document.querySelector(`button[data-bs-target="#tab-${roomId}"]`);
                    if (newTabBtn && window.bootstrap) {
                        new bootstrap.Tab(newTabBtn).show();
                    }
                }, 200);
            });
        }

        const formDevice = $('#formTambahAlat');
        if (formDevice) {
            formDevice.addEventListener('submit', async (e) => {
                e.preventDefault();
                const roomId = $('#inputDeviceRoom').value;
                const name = ($('#inputDeviceName').value || '').trim();
                const rawDevId = ($('#inputDeviceId').value || '').trim().toUpperCase().replace(/\s+/g, '_');
                const node = ($('#inputDeviceNode').value || '').trim() || 'Node Standar';

                const hasSuhu = $('#chkSensorSuhu').checked;
                const hasHum = $('#chkSensorHum').checked;
                const hasMQ = $('#chkSensorMQ').checked;
                const hasAC = $('#chkSensorAC').checked;
                const hasLight = $('#chkSensorLight') ? $('#chkSensorLight').checked : false;
                const suhuMin = parseFloat($('#inputSuhuMin').value) || 15;
                const suhuMax = parseFloat($('#inputSuhuMax').value) || 25;
                const lumensMin = parseFloat($('#inputLumensMin')?.value) || 250;
                const lumensMax = parseFloat($('#inputLumensMax')?.value) || 500;
                const simulate = $('#chkSimulateMode').checked;

                // Collect custom sensor parameters
                const customSensors = [];
                document.querySelectorAll('.custom-sensor-row').forEach(row => {
                    const cName = (row.querySelector('.cs-name').value || '').trim();
                    const cKey = (row.querySelector('.cs-key').value || '').trim().toLowerCase().replace(/\s+/g, '_');
                    const cUnit = (row.querySelector('.cs-unit').value || '').trim();
                    const cMax = parseFloat(row.querySelector('.cs-max').value) || 100;
                    if (cName && cKey) {
                        customSensors.push({ name: cName, key: cKey, unit: cUnit, maxVal: cMax });
                    }
                });

                if (!hasSuhu && !hasHum && !hasMQ && !hasAC && !hasLight && customSensors.length === 0) {
                    return alert('Pilih minimal 1 jenis sensor yang terpasang pada alat ini!');
                }

                const internalId = 'dev_' + Date.now();
                customConfig.devices.push({
                    id: internalId,
                    roomId: roomId,
                    name: name,
                    deviceId: rawDevId,
                    node: node,
                    suhuMin: suhuMin,
                    suhuMax: suhuMax,
                    lumensMin: lumensMin,
                    lumensMax: lumensMax,
                    simulate: simulate,
                    sensors: {
                        suhu: hasSuhu,
                        hum: hasHum,
                        mq: hasMQ,
                        ac: hasAC,
                        light: hasLight
                    },
                    customSensors: customSensors
                });

                await saveCustomIoTConfig();
                renderCustomRoomsAndDevices();
                updateCustomDevicesRealtime({});
                redrawCustomDeviceCharts();

                formDevice.reset();
                $('#customSensorRowsContainer').innerHTML = '';
                const modalEl = document.getElementById('modalTambahAlat');
                if (modalEl && window.bootstrap) {
                    const modalInst = bootstrap.Modal.getInstance(modalEl);
                    if (modalInst) modalInst.hide();
                }

                // Activate the room tab where the device was added
                setTimeout(() => {
                    const targetSelector = (roomId === 'tab-lab' || roomId === 'tab-keuangan') ? `#${roomId}` : `#tab-${roomId}`;
                    const tabBtn = document.querySelector(`button[data-bs-target="${targetSelector}"]`);
                    if (tabBtn && window.bootstrap) {
                        new bootstrap.Tab(tabBtn).show();
                    }
                    setTimeout(() => resizeIoTCharts(), 150);
                }, 200);
            });
        }
    }

    function populateRoomSelectDropdown(preselectRoomId) {
        const select = $('#inputDeviceRoom');
        if (!select) return;
        select.innerHTML = `
            <option value="tab-lab">Ruang Lab Reagen</option>
            <option value="tab-keuangan">Ruang Keuangan</option>
        `;
        customConfig.rooms.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = r.name;
            select.appendChild(opt);
        });
        if (preselectRoomId) {
            select.value = preselectRoomId;
        }
    }

    window.openModalTambahRuangan = function() {
        const modalEl = document.getElementById('modalTambahRuangan');
        if (modalEl && window.bootstrap) {
            new bootstrap.Modal(modalEl).show();
        }
    };

    window.openModalTambahAlat = function(preselectRoomId) {
        populateRoomSelectDropdown(preselectRoomId);
        // Auto-suggest next device ID
        const count = customConfig.devices.length + 3;
        const devIdInput = $('#inputDeviceId');
        if (devIdInput && !devIdInput.value) {
            devIdInput.value = `ALAT_${count}_IOT`;
        }
        const modalEl = document.getElementById('modalTambahAlat');
        if (modalEl && window.bootstrap) {
            new bootstrap.Modal(modalEl).show();
        }
    };

    window.openModalKelola = function() {
        renderManageModalLists();
        const modalEl = document.getElementById('modalKelolaIoT');
        if (modalEl && window.bootstrap) {
            new bootstrap.Modal(modalEl).show();
        }
    };

    window.addCustomSensorRow = function() {
        const container = $('#customSensorRowsContainer');
        if (!container) return;
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center mb-2 custom-sensor-row bg-light p-2 rounded border';
        row.innerHTML = `
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm cs-name" placeholder="Nama Sensor (misal: Daya Listrik)" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm cs-key" placeholder="Key JSON (misal: watt)" required>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control form-control-sm cs-unit" placeholder="Satuan (W/Lux)" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control form-control-sm cs-max" placeholder="Batas Max" value="1000">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" onclick="this.closest('.custom-sensor-row').remove()">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
    };

    window.deleteCustomRoom = async function(roomId) {
        if (!confirm('Hapus ruangan ini beserta seluruh tampilan alat di dalamnya?')) return;
        customConfig.rooms = customConfig.rooms.filter(r => r.id !== roomId);
        customConfig.devices = customConfig.devices.filter(d => d.roomId !== roomId);
        await saveCustomIoTConfig();
        renderCustomRoomsAndDevices();
        renderManageModalLists();

        const firstTab = document.querySelector('#mainRoomTabs button[data-bs-target="#tab-lab"]');
        if (firstTab && window.bootstrap) {
            new bootstrap.Tab(firstTab).show();
        }
    };

    window.deleteCustomDevice = async function(devInternalId) {
        if (!confirm('Hapus alat sensor ini dari dasbor?')) return;
        customConfig.devices = customConfig.devices.filter(d => d.id !== devInternalId);
        delete customRuntimeData[devInternalId];
        await saveCustomIoTConfig();
        renderCustomRoomsAndDevices();
        updateCustomDevicesRealtime({});
        redrawCustomDeviceCharts();
        renderManageModalLists();
    };

    window.toggleDeviceSimulation = async function(devInternalId) {
        const dev = customConfig.devices.find(d => d.id === devInternalId);
        if (!dev) return;
        dev.simulate = !dev.simulate;
        await saveCustomIoTConfig();
        renderCustomRoomsAndDevices();
        updateCustomDevicesRealtime({});
        redrawCustomDeviceCharts();
        renderManageModalLists();
    };

    window.sendSampleToDatabase = async function(devInternalId) {
        const dev = customConfig.devices.find(d => d.id === devInternalId);
        if (!dev) return;
        const rt = customRuntimeData[dev.id] || {};
        const lastSuhu = rt.suhu ? rt.suhu[rt.suhu.length - 1] : 23.2;
        const lastHum = rt.hum ? rt.hum[rt.hum.length - 1] : 56;
        const lastMQ = rt.mq ? rt.mq[rt.mq.length - 1] : 640;

        try {
            const res = await fetch(`${API_BASE}/data?key=simulateDeviceData`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    deviceId: dev.deviceId,
                    suhu: parseFloat(lastSuhu.toFixed(1)),
                    hum: parseFloat(lastHum.toFixed(0)),
                    mq: Math.round(lastMQ),
                    ac: rt.acStatus === 'ON' ? 1 : 0,
                    occ: rt.kehadiran || 1
                })
            });
            if (res.ok) {
                alert(`Sampel data untuk ${dev.name} (${dev.deviceId}) berhasil disimpan ke tabel Database MySQL!`);
                fetchRealtimeData();
            }
        } catch (e) {
            alert('Gagal mengirim sampel ke database.');
        }
    };

    function renderManageModalLists() {
        const roomsEl = $('#manageRoomsList');
        const devsEl = $('#manageDevicesList');

        if (roomsEl) {
            const builtInRoomsHtml = `
                <div class="d-flex justify-content-between align-items-center p-2 mb-2 border rounded bg-light">
                    <div>
                        <i class="ti ti-flask text-primary me-1"></i>
                        <strong>Ruang Lab Reagen</strong>
                        <small class="text-muted ms-2">Monitoring suhu, kelembapan &amp; gas reagen</small>
                    </div>
                    <span class="badge bg-label-secondary">Ruangan Bawaan</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 mb-2 border rounded bg-light">
                    <div>
                        <i class="ti ti-Cash text-primary me-1"></i>
                        <strong>Ruang Keuangan</strong>
                        <small class="text-muted ms-2">Otomasi AC &amp; kehadiran staf</small>
                    </div>
                    <span class="badge bg-label-secondary">Ruangan Bawaan</span>
                </div>
            `;
            const customRoomsHtml = customConfig.rooms.map(r => `
                <div class="d-flex justify-content-between align-items-center p-2 mb-2 border rounded bg-white">
                    <div>
                        <i class="ti ${r.icon} text-primary me-1"></i>
                        <strong>${r.name}</strong>
                        <small class="text-muted ms-2">${r.desc || ''}</small>
                    </div>
                    <button class="btn btn-sm btn-outline-danger py-1 px-2" onclick="deleteCustomRoom('${r.id}')">
                        <i class="ti ti-trash"></i> Hapus
                    </button>
                </div>
            `).join('');
            roomsEl.innerHTML = builtInRoomsHtml + customRoomsHtml;
        }

        if (devsEl) {
            const builtInDevsHtml = `
                <div class="d-flex flex-wrap justify-content-between align-items-center p-2 mb-2 border rounded bg-light gap-2">
                    <div>
                        <div class="fw-bold" style="color: #3b327b;">Alat 1 &mdash; Ruang Lab Reagen (AC) <span class="badge bg-label-primary ms-1">Kode Alat: ALAT_1_AC (ID JSON: LAB_AC)</span></div>
                        <div class="small text-muted">Ruangan: <strong>Ruang Lab Reagen</strong> | Sensor: Suhu, Kelembapan, Gas MQ | Node: Lab Reagen 1</div>
                    </div>
                    <span class="badge bg-label-success">Aktif (Bawaan)</span>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center p-2 mb-2 border rounded bg-light gap-2">
                    <div>
                        <div class="fw-bold" style="color: #3b327b;">Alat 2 &mdash; Ruang Lab Reagen (Non-AC) <span class="badge bg-label-primary ms-1">Kode Alat: ALAT_2_NON_AC (ID JSON: LAB_NON_AC)</span></div>
                        <div class="small text-muted">Ruangan: <strong>Ruang Lab Reagen</strong> | Sensor: Suhu, Kelembapan, Gas MQ | Node: Lab Reagen 2</div>
                    </div>
                    <span class="badge bg-label-success">Aktif (Bawaan)</span>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center p-2 mb-2 border rounded bg-light gap-2">
                    <div>
                        <div class="fw-bold" style="color: #3b327b;">Alat 1 &mdash; Ruang Keuangan <span class="badge bg-label-primary ms-1">Kode Alat: ALAT_1 (ID JSON: KEUANGAN)</span></div>
                        <div class="small text-muted">Ruangan: <strong>Ruang Keuangan</strong> | Sensor: Otomasi AC, Kehadiran, Suhu, Kelembapan | Node: R. Keuangan</div>
                    </div>
                    <span class="badge bg-label-success">Aktif (Bawaan)</span>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center p-2 mb-2 border rounded bg-light gap-2">
                    <div>
                        <div class="fw-bold" style="color: #3b327b;">Alat 2 &mdash; Ruang Keuangan (Sensor Intensitas Cahaya &amp; Saklar Servo) <span class="badge bg-label-primary ms-1">Kode Alat: ALAT_2_CAHAYA (ID JSON: KEUANGAN_CAHAYA)</span></div>
                        <div class="small text-muted">Ruangan: <strong>Ruang Keuangan</strong> | Sensor: Intensitas Cahaya (Lumens) &amp; Saklar Lampu (Motor Servo) | Node: Saklar Lampu R. Keuangan</div>
                    </div>
                    <span class="badge bg-label-warning">Tahap Perakitan (Bawaan)</span>
                </div>
            `;

            const customDevsHtml = customConfig.devices.map(d => {
                const roomObj = customConfig.rooms.find(r => r.id === d.roomId);
                const roomLabel = d.roomId === 'tab-lab' ? 'Ruang Lab Reagen' : (d.roomId === 'tab-keuangan' ? 'Ruang Keuangan' : (roomObj ? roomObj.name : 'Ruangan'));
                const badgeList = [];
                if (d.sensors?.suhu) badgeList.push('Suhu');
                if (d.sensors?.hum) badgeList.push('Kelembapan');
                if (d.sensors?.mq) badgeList.push('Gas MQ');
                if (d.sensors?.ac) badgeList.push('Otomasi AC');
                if (d.sensors?.light) badgeList.push('Intensitas Cahaya (Lumens) & Saklar Servo');
                if (d.customSensors?.length) d.customSensors.forEach(cs => badgeList.push(cs.name));

                return `
                    <div class="d-flex flex-wrap justify-content-between align-items-center p-2 mb-2 border rounded bg-white gap-2">
                        <div>
                            <div class="fw-bold" style="color: #3b327b;">${d.name} <span class="badge bg-label-primary ms-1">Kode Alat: ${d.deviceId}</span></div>
                            <div class="small text-muted">Ruangan: <strong>${roomLabel}</strong> | Sensor: ${badgeList.join(', ')} | Node: ${d.node}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm ${d.simulate ? 'btn-warning' : 'btn-outline-secondary'} py-1 px-2" onclick="toggleDeviceSimulation('${d.id}')" title="Aktif/Nonaktifkan Simulasi">
                                <i class="ti ti-activity"></i> ${d.simulate ? 'Simulasi: ON' : 'Simulasi: OFF'}
                            </button>
                            <button class="btn btn-sm btn-outline-danger py-1 px-2" onclick="deleteCustomDevice('${d.id}')">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            devsEl.innerHTML = builtInDevsHtml + customDevsHtml;
        }
    }

    function renderCustomRoomsAndDevices() {
        const tabsUl = $('#mainRoomTabs');
        const tabContent = $('#mainRoomTabContent');
        if (!tabsUl || !tabContent) return;

        const todayStr = new Date().toLocaleDateString('en-CA');

        // 1. Remove old dynamic room tabs and panes
        tabsUl.querySelectorAll('.custom-room-nav').forEach(el => el.remove());
        tabContent.querySelectorAll('.custom-room-pane').forEach(el => el.remove());

        // 2. Render custom room tabs and panes (with History Filter Bar in every room)
        customConfig.rooms.forEach(room => {
            if (roomRealtimeState[room.id] === undefined) {
                roomRealtimeState[room.id] = true;
            }

            const li = document.createElement('li');
            li.className = 'nav-item custom-room-nav';
            li.setAttribute('role', 'presentation');
            li.innerHTML = `
                <button class="nav-link shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-${room.id}" type="button" role="tab">
                    <i class="ti ${room.icon || 'ti-building'} me-1"></i> ${room.name}
                </button>
            `;
            tabsUl.appendChild(li);

            // Bind tab shown listener to resize charts
            const btn = li.querySelector('button');
            if (btn) {
                btn.addEventListener('shown.bs.tab', () => {
                    resizeIoTCharts();
                });
            }

            const pane = document.createElement('div');
            pane.className = 'tab-pane fade custom-room-pane';
            pane.id = `tab-${room.id}`;
            pane.setAttribute('role', 'tabpanel');
            pane.innerHTML = `
                <!-- FILTER CONTROLS RUANGAN (IDENTIK DENGAN RUANGAN BAWAAN) -->
                <div class="card mb-4 border-0 shadow-sm" id="historyBar_${room.id}">
                    <div class="card-body d-flex flex-wrap align-items-center gap-3">
                        <span class="fw-bold text-muted"><i class="ti ti-filter"></i> Filter:</span>
                        <button class="history-btn active" onclick="setRoomHistoryRange('${room.id}', 'realtime', this)">
                            <i class="ti ti-circle-filled text-danger" style="font-size: 8px;"></i> Real-time
                        </button>
                        <button class="history-btn" onclick="setRoomHistoryRange('${room.id}', '1h', this)">1 Jam</button>
                        <button class="history-btn" onclick="setRoomHistoryRange('${room.id}', '6h', this)">6 Jam</button>
                        <button class="history-btn" onclick="setRoomHistoryRange('${room.id}', '24h', this)">Hari Ini</button>

                        <span class="text-muted mx-2">|</span>

                        <div class="d-flex align-items-center gap-2">
                            <label class="text-muted small mb-0">Dari:</label>
                            <input type="date" id="historyFrom_${room.id}" class="form-control form-control-sm">
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="text-muted small mb-0">Sampai:</label>
                            <input type="date" id="historyTo_${room.id}" class="form-control form-control-sm">
                        </div>
                        <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" onclick="setRoomHistoryDateRange('${room.id}')" style="background-color: #3b327b; border-color: #3b327b;">
                            <i class="ti ti-search" style="font-size: 14px;"></i> Terapkan
                        </button>

                        <span id="historyInfo_${room.id}" class="ms-auto small text-muted"></span>

                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" onclick="openModalTambahAlat('${room.id}')">
                                <i class="ti ti-plus"></i> Tambah Alat
                            </button>
                            <button class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" onclick="deleteCustomRoom('${room.id}')" title="Hapus Ruangan">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="customDevices_${room.id}"></div>
            `;
            tabContent.appendChild(pane);
        });

        // 3. Clear device containers
        const labContainer = $('#customDevices_lab');
        const keuContainer = $('#customDevices_keuangan');
        if (labContainer) labContainer.innerHTML = '';
        if (keuContainer) keuContainer.innerHTML = '';

        // 4. Render devices into their respective rooms
        customConfig.devices.forEach((dev, idx) => {
            let container = null;
            if (dev.roomId === 'tab-lab') container = labContainer;
            else if (dev.roomId === 'tab-keuangan') container = keuContainer;
            else container = $(`#customDevices_${dev.roomId}`);

            if (!container) return;

            const roomDevs = customConfig.devices.filter(d => d.roomId === dev.roomId);
            const isLastInCustomRoom = (dev.roomId !== 'tab-lab' && dev.roomId !== 'tab-keuangan') && (roomDevs[roomDevs.length - 1]?.id === dev.id);

            // Initialize runtime data buffer if not yet created
            if (!customRuntimeData[dev.id]) {
                const midSuhu = ((dev.suhuMin || 15) + (dev.suhuMax || 25)) / 2;
                customRuntimeData[dev.id] = {
                    suhu: Array(20).fill(dev.simulate ? midSuhu : 0),
                    hum: Array(20).fill(dev.simulate ? 56 : 0),
                    mq: Array(20).fill(dev.simulate ? 650 : 0),
                    lumens: Array(20).fill(380),
                    acStatus: dev.simulate ? 'ON' : 'OFF',
                    kehadiran: dev.simulate ? 2 : 0,
                    custom: {},
                    lastRecap: null
                };
                if (Array.isArray(dev.customSensors)) {
                    dev.customSensors.forEach(cs => {
                        customRuntimeData[dev.id].custom[cs.key] = Array(20).fill(dev.simulate ? Math.round((cs.maxVal || 100) * 0.5) : 0);
                    });
                }
            }
            if (!customRuntimeData[dev.id].lumens) {
                customRuntimeData[dev.id].lumens = Array(20).fill(380);
            }

            if (dev.sensors?.light && !lightServoStore[dev.deviceId]) {
                lightServoStore[dev.deviceId] = {
                    mode: 'auto',
                    lampStatus: 'ON',
                    servoAngle: 90,
                    lumens: 380,
                    minLumens: dev.lumensMin || 250,
                    maxLumens: dev.lumensMax || 500,
                    servoActionsToday: 2,
                    lightOffCountToday: 1,
                    simOverrideLumens: null
                };
            }

            // Count how many sensor cards this device has to set responsive grid columns
            let cardCount = 0;
            if (dev.sensors?.suhu) cardCount++;
            if (dev.sensors?.hum) cardCount++;
            if (dev.sensors?.mq) cardCount++;
            if (Array.isArray(dev.customSensors)) cardCount += dev.customSensors.length;

            const colClass = cardCount === 1 ? 'col-md-12' : (cardCount === 2 ? 'col-md-6' : 'col-md-4');

            let cardsHtml = '';

            // Card: Suhu
            if (dev.sensors?.suhu) {
                cardsHtml += `
                    <div class="${colClass}">
                        <div class="iot-card">
                            <div class="iot-card-header">
                                <h6 class="iot-card-title"><i class="ti ti-temperature text-danger fs-4"></i> Suhu Ruangan</h6>
                                <span class="iot-status normal" id="suhuStatus_${dev.id}">Aman</span>
                            </div>
                            <div class="iot-value-display">
                                <span class="iot-big-value" id="suhuValue_${dev.id}">--</span>
                                <span class="iot-unit">&deg;C</span>
                            </div>
                            <div><canvas id="chartSuhu_${dev.id}" style="width: 100%; height: 150px; display: block;"></canvas></div>
                            <div class="iot-meta"><span>Sensor: DHT22</span><span>Kode: ${dev.deviceId}</span></div>
                        </div>
                    </div>
                `;
            }

            // Card: Kelembapan
            if (dev.sensors?.hum) {
                cardsHtml += `
                    <div class="${colClass}">
                        <div class="iot-card">
                            <div class="iot-card-header">
                                <h6 class="iot-card-title"><i class="ti ti-droplet text-primary fs-4"></i> Kelembapan</h6>
                                <span class="iot-status normal" id="humStatus_${dev.id}">Aman</span>
                            </div>
                            <div class="iot-value-display">
                                <span class="iot-big-value" id="humValue_${dev.id}">--</span>
                                <span class="iot-unit">%RH</span>
                            </div>
                            <div><canvas id="chartHum_${dev.id}" style="width: 100%; height: 150px; display: block;"></canvas></div>
                            <div class="iot-meta"><span>Sensor: DHT22</span><span>Kode: ${dev.deviceId}</span></div>
                        </div>
                    </div>
                `;
            }

            // Card: Kualitas Udara (MQ)
            if (dev.sensors?.mq) {
                cardsHtml += `
                    <div class="${colClass}">
                        <div class="iot-card">
                            <div class="iot-card-header">
                                <h6 class="iot-card-title"><i class="ti ti-wind text-warning fs-4"></i> Kualitas Udara</h6>
                                <span class="iot-status normal" id="mqStatus_${dev.id}">Aman</span>
                            </div>
                            <div class="iot-value-display">
                                <span class="iot-big-value" id="mqValue_${dev.id}">--</span>
                                <span class="iot-unit">PPM</span>
                            </div>
                            <div><canvas id="chartMQ_${dev.id}" style="width: 100%; height: 150px; display: block;"></canvas></div>
                            <div class="iot-meta"><span>Sensor: MQ-2/MQ-135</span><span>Kode: ${dev.deviceId}</span></div>
                        </div>
                    </div>
                `;
            }

            // Cards: Custom Sensors (New Sensor Models)
            if (Array.isArray(dev.customSensors)) {
                dev.customSensors.forEach(cs => {
                    cardsHtml += `
                        <div class="${colClass}">
                            <div class="iot-card">
                                <div class="iot-card-header">
                                    <h6 class="iot-card-title"><i class="ti ti-Cpu text-info fs-4"></i> ${cs.name}</h6>
                                    <span class="iot-status normal" id="customStatus_${dev.id}_${cs.key}">Aman</span>
                                </div>
                                <div class="iot-value-display">
                                    <span class="iot-big-value" id="customValue_${dev.id}_${cs.key}">--</span>
                                    <span class="iot-unit">${cs.unit}</span>
                                </div>
                                <div><canvas id="chartCustom_${dev.id}_${cs.key}" style="width: 100%; height: 150px; display: block;"></canvas></div>
                                <div class="iot-meta"><span>Key JSON: "${cs.key}"</span><span>Kode: ${dev.deviceId}</span></div>
                            </div>
                        </div>
                    `;
                });
            }

            // Card: Otomasi AC & Kehadiran (Identik dengan Ruang Keuangan)
            let acCardHtml = '';
            if (dev.sensors?.ac) {
                acCardHtml = `
                    <div class="row">
                        <div class="col-12">
                            <div class="iot-card">
                                <div class="iot-card-header border-bottom pb-3 mb-3">
                                    <h6 class="iot-card-title"><i class="ti ti-device-remote text-primary fs-4"></i> Otomasi AC &mdash; ${dev.name}</h6>
                                    <span class="iot-status normal">Otomasi Aktif</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-center border-end">
                                        <div class="text-center p-3">
                                            <div class="mb-3">
                                                <i class="ti ti-wind" style="font-size: 4.5rem; color: #3b327b;"></i>
                                            </div>
                                            <h4 id="acStatus_${dev.id}" class="fw-bold mb-0 text-success" style="font-size: 2rem;">ON</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-8 px-4 py-2">
                                        <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.8rem; letter-spacing: 0.5px;"><i class="ti ti-activity text-danger"></i> Kondisi Ruangan</h6>
                                        <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
                                            <span class="text-muted fs-6">Occupancy</span>
                                            <span class="fw-bold text-primary fs-6" id="occValue_${dev.id}">0 people</span>
                                        </div>
                                        <div class="d-flex justify-content-between pb-2 mb-2">
                                            <span class="text-muted fs-6">Lokasi Node &amp; Kode Alat</span>
                                            <span class="fw-bold fs-6">${dev.node} (${dev.deviceId})</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="iot-meta mt-4 border-top pt-3 text-muted">
                                    <span><i class="ti ti-map-pin me-1"></i>Node: ${dev.node} (Kode: ${dev.deviceId})</span>
                                    <span class="fw-medium"><i class="ti ti-clock me-1"></i>Monitoring Otomasi Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Card: Sensor Intensitas Cahaya (Lumens) & Saklar Lampu (Motor Servo)
            let lightServoHtml = '';
            if (dev.sensors?.light) {
                const lMin = dev.lumensMin || 250;
                const lMax = dev.lumensMax || 500;
                lightServoHtml = `
                    <div class="alert-banner safe" id="alertLightBanner_${dev.id}">
                        <div style="margin-top: 2px;"><i class="ti ti-sun-high ti-lg"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold" id="alertLightTitle_${dev.id}">Intensitas Cahaya Ruangan Ideal &mdash; Pencahayaan &amp; Servo Stabil</h6>
                            <small id="alertLightDesc_${dev.id}">Batas Gelap: &lt; ${lMin} lm (Servo ON) | Batas Terang: &gt; ${lMax} lm (Servo OFF) | Saat ini: <strong id="alertLightCurr_${dev.id}">380 lm</strong></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="iot-card" id="cardServo_${dev.id}">
                                <div class="iot-card-header border-bottom pb-3 mb-3">
                                    <h6 class="iot-card-title"><i class="ti ti-bulb text-warning fs-4"></i> Saklar Lampu (Servo)</h6>
                                    <span class="iot-status normal" id="servoModeBadge_${dev.id}">Mode: Otomatis</span>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-5 text-center border-end">
                                        <div class="py-2">
                                            <i class="ti ti-bulb-filled" id="lampVisualIcon_${dev.id}" style="font-size: 4rem; color: #f59e0b; filter: drop-shadow(0 0 12px rgba(245, 158, 11, 0.45));"></i>
                                            <h5 id="lampStatusValue_${dev.id}" class="fw-bold mb-1 text-success">LAMPU ON</h5>
                                            <span class="badge bg-label-primary" id="servoAngleBadge_${dev.id}">Servo: 90&deg; (ON)</span>
                                        </div>
                                    </div>
                                    <div class="col-7 px-3">
                                        <div class="mb-2">
                                            <label class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 0.72rem;">Mode Penggerak Servo</label>
                                            <div class="btn-group w-100 btn-group-sm" role="group">
                                                <button type="button" class="btn btn-primary" id="btnModeAuto_${dev.id}" onclick="setLightServoMode('${dev.deviceId}', 'auto')" style="background-color: #3b327b; border-color: #3b327b;">Otomatis</button>
                                                <button type="button" class="btn btn-outline-secondary" id="btnModeManual_${dev.id}" onclick="setLightServoMode('${dev.deviceId}', 'manual')">Manual Switch</button>
                                            </div>
                                        </div>
                                        <div class="p-2 rounded border bg-light mb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold small" style="color: #3b327b;">Switch Manual</span>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="switchManualLamp_${dev.id}" checked onchange="toggleLightServoManual('${dev.deviceId}', this.checked)" style="cursor: pointer;">
                                                </div>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-xs btn-success flex-fill py-1" onclick="toggleLightServoManual('${dev.deviceId}', true)">Servo ON</button>
                                                <button type="button" class="btn btn-xs btn-outline-danger flex-fill py-1" onclick="toggleLightServoManual('${dev.deviceId}', false)">Servo OFF</button>
                                            </div>
                                        </div>
                                        <div class="p-2 rounded border" style="background: #f8fafc;">
                                            <div class="small fw-bold text-muted mb-1" style="font-size: 0.7rem;">Uji Respons (Tahap Perakitan):</div>
                                            <div class="d-flex flex-wrap gap-1">
                                                <button type="button" class="btn btn-xs btn-outline-dark py-0 px-2" style="font-size: 0.7rem;" onclick="simulateLumensCondition('${dev.deviceId}', 140)">Gelap (140 lm)</button>
                                                <button type="button" class="btn btn-xs btn-outline-success py-0 px-2" style="font-size: 0.7rem;" onclick="simulateLumensCondition('${dev.deviceId}', 380)">Ideal (380 lm)</button>
                                                <button type="button" class="btn btn-xs btn-outline-warning py-0 px-2" style="font-size: 0.7rem;" onclick="simulateLumensCondition('${dev.deviceId}', 640)">Terang (640 lm)</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="iot-meta mt-3 border-top pt-2">
                                    <span id="lampActionReason_${dev.id}">Cahaya ruangan ideal</span>
                                    <span>Kode: ${dev.deviceId}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="iot-card" id="cardLight_${dev.id}">
                                <div class="iot-card-header">
                                    <h6 class="iot-card-title"><i class="ti ti-sun text-warning fs-4"></i> Intensitas Cahaya (${dev.name})</h6>
                                    <span class="iot-status normal" id="lightStatus_${dev.id}">Ideal</span>
                                </div>
                                <div class="iot-value-display">
                                    <span class="iot-big-value" id="lightValue_${dev.id}">380</span>
                                    <span class="iot-unit">Lumens (lm)</span>
                                </div>
                                <div><canvas id="chartLight_${dev.id}" style="width: 100%; height: 155px; display: block;"></canvas></div>
                                <div class="iot-meta">
                                    <span>&lt; ${lMin} lm (Gelap &rarr; ON) | &gt; ${lMax} lm (Terang &rarr; OFF)</span>
                                    <span>Kode: ${dev.deviceId}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Alert Banner if Suhu is enabled
            let alertBannerHtml = '';
            if (dev.sensors?.suhu) {
                alertBannerHtml = `
                    <div class="alert-banner safe" id="alertBanner_${dev.id}">
                        <div style="margin-top: 2px;"><i class="ti ti-circle-check-filled ti-lg"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold" id="alertTitle_${dev.id}">Suhu Ruangan Dalam Batas Aman</h6>
                            <small id="alertDesc_${dev.id}">Rentang aman: ${dev.suhuMin}&deg;C &mdash; ${dev.suhuMax}&deg;C | Suhu saat ini: <strong id="alertCurr_${dev.id}">--&deg;C</strong></small>
                        </div>
                    </div>
                `;
            }

            // Build Per-Device Daily Recap Table Rows (Identik dengan tabel Rekap Harian Ruangan Bawaan)
            const rowDefs = [];
            if (dev.sensors?.suhu) {
                rowDefs.push(`
                    <td><i class="ti ti-temperature text-muted me-2"></i>Suhu</td>
                    <td id="recapAvgSuhu_${dev.id}">--</td>
                    <td id="recapMinSuhu_${dev.id}">--</td>
                    <td id="recapMaxSuhu_${dev.id}">--</td>
                `);
            }
            if (dev.sensors?.hum) {
                rowDefs.push(`
                    <td><i class="ti ti-droplet text-muted me-2"></i>Kelembapan</td>
                    <td id="recapAvgHum_${dev.id}">--</td>
                    <td id="recapMinHum_${dev.id}">--</td>
                    <td id="recapMaxHum_${dev.id}">--</td>
                `);
            }
            if (dev.sensors?.mq) {
                rowDefs.push(`
                    <td><i class="ti ti-wind text-muted me-2"></i>Udara (PPM)</td>
                    <td id="recapAvgMQ_${dev.id}">--</td>
                    <td id="recapMinMQ_${dev.id}">--</td>
                    <td id="recapMaxMQ_${dev.id}">--</td>
                `);
            }
            if (dev.sensors?.light) {
                rowDefs.push(`
                    <td><i class="ti ti-sun text-warning me-2"></i>Intensitas Cahaya (Lumens)</td>
                    <td id="recapAvgLight_${dev.id}">380 lm</td>
                    <td id="recapMinLight_${dev.id}">380 lm</td>
                    <td id="recapMaxLight_${dev.id}">380 lm</td>
                `);
                rowDefs.push(`
                    <td><i class="ti ti-bulb text-primary me-2"></i>Saklar Lampu (Servo)</td>
                    <td id="recapServoStatus_${dev.id}">Lampu ON (90&deg;)</td>
                    <td id="recapServoMode_${dev.id}">Otomatis Sensor</td>
                    <td id="recapServoAct_${dev.id}">2x Penggerak</td>
                `);
            }
            if (dev.sensors?.ac) {
                rowDefs.push(`
                    <td><i class="ti ti-device-remote text-muted me-2"></i>Otomasi AC &amp; Kehadiran</td>
                    <td id="recapAcOn_${dev.id}">ON</td>
                    <td id="recapOcc_${dev.id}">-- orang</td>
                    <td>-</td>
                `);
            }
            if (Array.isArray(dev.customSensors)) {
                dev.customSensors.forEach(cs => {
                    rowDefs.push(`
                        <td><i class="ti ti-Cpu text-muted me-2"></i>${cs.name}</td>
                        <td id="recapAvgCustom_${dev.id}_${cs.key}">--</td>
                        <td id="recapMinCustom_${dev.id}_${cs.key}">--</td>
                        <td id="recapMaxCustom_${dev.id}_${cs.key}">--</td>
                    `);
                });
            }

            const totalRows = Math.max(1, rowDefs.length);
            const recapRowsHtml = rowDefs.map((cols, rIdx) => {
                if (rIdx === 0) {
                    return `<tr>${cols}<td id="recapSamples_${dev.id}" rowspan="${totalRows}" class="align-middle text-center fw-bold fs-4" style="color: #3b327b;">0</td></tr>`;
                }
                return `<tr>${cols}</tr>`;
            }).join('');

            const combinedRoomPdfBtnHtml = isLastInCustomRoom && roomDevs.length > 1
                ? `<button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" onclick="generateCustomRoomPDF('${dev.roomId}')">
                       <i class="ti ti-files"></i> Export PDF Gabungan Ruangan
                   </button>`
                : '';

            // Per-Device Recap & Export PDF Card (Identik dengan Ruangan Bawaan)
            const recapCardHtml = `
                <div class="card mt-1 mb-4 border-0 shadow-sm">
                    <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h6 class="card-title mb-0 fw-bold" style="color: #3b327b;">
                            <i class="ti ti-table text-primary me-1"></i> Rekap Harian ${dev.name} &mdash; <span id="recapDate_${dev.id}">Hari Ini</span>
                            <span class="badge bg-label-primary ms-1">${dev.deviceId}</span>
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <label class="text-muted small mb-0">Pilih Tanggal:</label>
                            <input type="date" id="pdfDate_${dev.id}" class="form-control form-control-sm" style="width: auto;" value="${todayStr}" max="${todayStr}" onchange="fetchCustomDeviceRecap('${dev.id}', this.value)">
                            <button type="button" class="btn btn-success btn-sm d-flex align-items-center gap-1" onclick="generateCustomDevicePDF('${dev.id}')">
                                <i class="ti ti-file-download"></i> Export PDF ${dev.name}
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover recap-table m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Parameter</th>
                                    <th>Rata-rata</th>
                                    <th>Min</th>
                                    <th>Max</th>
                                    <th>Sampel</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${recapRowsHtml}
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted small">
                            <span class="badge bg-label-danger me-1"><i class="ti ti-alert-circle"></i></span>
                            Alert Suhu ${dev.name}: <strong id="recapAlerts_${dev.id}">0</strong> kali melampaui batas aman
                        </div>
                        ${combinedRoomPdfBtnHtml}
                    </div>
                </div>
            `;

            const wrapper = document.createElement('div');
            wrapper.className = 'custom-device-section mt-4 mb-2';
            wrapper.id = `deviceSection_${dev.id}`;
            wrapper.innerHTML = `
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="background: #3b327b; padding: 12px; border-radius: 10px; color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(59,50,123,0.2);">
                            <i class="ti ti-device-analytics ti-md"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: #3b327b;">
                                ${dev.name}
                                <span class="badge bg-label-primary ms-2" style="font-size: 0.72rem;">Kode Alat: ${dev.deviceId}</span>
                            </h5>
                            <small class="text-muted">Monitoring sensor lingkungan real-time | Node: ${dev.node}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-xs btn-outline-danger btn-sm py-1 px-2" onclick="deleteCustomDevice('${dev.id}')" title="Hapus Alat">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                ${alertBannerHtml}
                ${acCardHtml}
                ${lightServoHtml}
                <div class="row">
                    ${cardsHtml}
                </div>
                ${recapCardHtml}
            `;
            container.appendChild(wrapper);

            // Populate initial daily recap for this device
            fetchCustomDeviceRecap(dev.id, todayStr);
        });

        // 5. Show empty state in custom rooms that have no devices yet
        customConfig.rooms.forEach(room => {
            const roomDevs = customConfig.devices.filter(d => d.roomId === room.id);
            const container = $(`#customDevices_${room.id}`);
            if (container && roomDevs.length === 0) {
                container.innerHTML = `
                    <div class="card border-0 shadow-sm text-center p-5 my-3">
                        <div class="mb-3"><i class="ti ti-device-analytics text-muted" style="font-size: 3.5rem;"></i></div>
                        <h5 class="fw-bold" style="color: #3b327b;">Belum Ada Alat Sensor di ${room.name}</h5>
                        <p class="text-muted mb-4">Tambahkan alat sensor baru dan centang jenis sensor yang digunakan pada ruangan ini.</p>
                        <div>
                            <button class="btn btn-geulis-primary" onclick="openModalTambahAlat('${room.id}')">
                                <i class="ti ti-plus me-1"></i> Tambah Alat Pertama di ${room.name}
                            </button>
                        </div>
                    </div>
                `;
            }
        });
    }

    function isRoomInRealtime(roomId) {
        if (roomId === 'tab-lab') return isRealtime;
        return roomRealtimeState[roomId] !== false;
    }

    function updateCustomLightServoUI(deviceId) {
        const dev = customConfig.devices.find(d => d.deviceId === deviceId || d.id === deviceId);
        if (!dev || !dev.sensors?.light) return;
        const ls = lightServoStore[dev.deviceId];
        if (!ls) return;

        if (ls.mode === 'auto') {
            evaluateAutoLightServo(dev.deviceId);
        }

        const isLampOn = ls.lampStatus === 'ON';
        const curLumens = ls.lumens || 380;
        const lMin = ls.minLumens || dev.lumensMin || 250;
        const lMax = ls.maxLumens || dev.lumensMax || 500;

        const valEl = $(`#lightValue_${dev.id}`);
        if (valEl) valEl.textContent = curLumens;

        const stEl = $(`#lightStatus_${dev.id}`);
        if (stEl) {
            if (curLumens > lMax) {
                stEl.textContent = 'Terlalu Terang';
                stEl.className = 'iot-status warning';
            } else if (curLumens < lMin) {
                stEl.textContent = 'Terlalu Gelap';
                stEl.className = 'iot-status danger';
            } else {
                stEl.textContent = 'Ideal';
                stEl.className = 'iot-status normal';
            }
        }

        const banner = $(`#alertLightBanner_${dev.id}`);
        const bTitle = $(`#alertLightTitle_${dev.id}`);
        const bDesc = $(`#alertLightDesc_${dev.id}`);
        if (banner && bTitle && bDesc) {
            if (curLumens > lMax) {
                banner.className = 'alert-banner danger';
                bTitle.innerHTML = `<i class="ti ti-sun-high"></i> Ruangan Terlalu Terang (${curLumens} lm) &mdash; ${ls.mode === 'auto' ? 'Servo Otomatis Mematikan Sebagian Lampu' : 'Mode Manual Aktif'}`;
            } else if (curLumens < lMin) {
                banner.className = 'alert-banner danger';
                bTitle.innerHTML = `<i class="ti ti-moon-stars"></i> Ruangan Terlalu Gelap (${curLumens} lm) &mdash; ${ls.mode === 'auto' ? 'Servo Otomatis Menghidupkan Saklar Lampu' : 'Mode Manual Aktif'}`;
            } else {
                banner.className = 'alert-banner safe';
                bTitle.innerHTML = `<i class="ti ti-circle-check-filled"></i> Intensitas Cahaya Ruangan Ideal (${curLumens} lm) &mdash; Pencahayaan &amp; Servo Stabil`;
            }
            bDesc.innerHTML = `Batas Gelap: &lt; ${lMin} lm (Servo ON) | Batas Terang: &gt; ${lMax} lm (Servo OFF) | Saat ini: <strong>${curLumens} lm</strong>`;
        }

        const iconEl = $(`#lampVisualIcon_${dev.id}`);
        const lampVal = $(`#lampStatusValue_${dev.id}`);
        const servoBadge = $(`#servoAngleBadge_${dev.id}`);
        const modeBadge = $(`#servoModeBadge_${dev.id}`);
        const btnAuto = $(`#btnModeAuto_${dev.id}`);
        const btnManual = $(`#btnModeManual_${dev.id}`);
        const swEl = $(`#switchManualLamp_${dev.id}`);
        const reasonEl = $(`#lampActionReason_${dev.id}`);

        if (iconEl) {
            iconEl.className = isLampOn ? 'ti ti-bulb-filled' : 'ti ti-bulb-off';
            iconEl.style.color = isLampOn ? '#f59e0b' : '#94a3b8';
            iconEl.style.filter = isLampOn ? 'drop-shadow(0 0 12px rgba(245, 158, 11, 0.45))' : 'none';
        }
        if (lampVal) {
            lampVal.textContent = isLampOn ? 'LAMPU ON' : 'SEBAGIAN OFF';
            lampVal.className = isLampOn ? 'fw-bold mb-1 text-success' : 'fw-bold mb-1 text-secondary';
        }
        if (servoBadge) {
            servoBadge.innerHTML = isLampOn ? `Servo: ${ls.servoAngle}&deg; (ON)` : `Servo: ${ls.servoAngle}&deg; (OFF)`;
            servoBadge.className = isLampOn ? 'badge bg-label-success' : 'badge bg-label-warning';
        }
        if (modeBadge) {
            modeBadge.textContent = ls.mode === 'auto' ? 'Mode: Otomatis' : 'Mode: Manual';
            modeBadge.className = ls.mode === 'auto' ? 'iot-status normal' : 'iot-status warning';
        }
        if (btnAuto && btnManual) {
            if (ls.mode === 'auto') {
                btnAuto.className = 'btn btn-primary';
                btnAuto.style.backgroundColor = '#3b327b';
                btnManual.className = 'btn btn-outline-secondary';
                btnManual.style.backgroundColor = '';
            } else {
                btnManual.className = 'btn btn-primary';
                btnManual.style.backgroundColor = '#3b327b';
                btnAuto.className = 'btn btn-outline-secondary';
                btnAuto.style.backgroundColor = '';
            }
        }
        if (swEl) swEl.checked = isLampOn;
        if (reasonEl) {
            reasonEl.textContent = ls.mode === 'manual'
                ? `Switch Manual Web (${isLampOn ? 'ON' : 'OFF'})`
                : (curLumens > lMax ? 'Terlalu terang -> Servo OFF sebagian lampu' : (curLumens < lMin ? 'Terlalu gelap -> Servo ON lampu' : 'Cahaya ruangan ideal'));
        }
    }

    function updateCustomDevicesRealtime(apiCustomData) {
        const padVal = (arr) => { while (arr.length < 20) arr.unshift(arr[0] || 0); return arr.slice(-20); };
        const todayStr = new Date().toLocaleDateString('en-CA');

        customConfig.devices.forEach(dev => {
            if (!customRuntimeData[dev.id]) return;
            // Skip overwriting if the room is currently displaying a History Filter
            if (!isRoomInRealtime(dev.roomId)) return;

            const rt = customRuntimeData[dev.id];
            const serverDev = apiCustomData && apiCustomData[dev.deviceId];

            if (serverDev && Array.isArray(serverDev.logs) && serverDev.logs.length > 0) {
                // Real data from MySQL database exists!
                rt.suhu = padVal(serverDev.logs.map(d => d.suhu || 0));
                rt.hum = padVal(serverDev.logs.map(d => d.hum || 0));
                rt.mq = padVal(serverDev.logs.map(d => d.mq || 0));
                rt.acStatus = serverDev.acStatus ? 'ON' : 'OFF';
                rt.kehadiran = serverDev.kehadiran || 0;

                const modeBadge = $(`#modeBadge_${dev.id}`);
                if (modeBadge) {
                    modeBadge.className = 'badge bg-label-success ms-1';
                    modeBadge.textContent = `Database Aktif (${serverDev.logs.length} sampel)`;
                }
            } else if (dev.simulate) {
                // Generate smooth simulated sensor readings around the safe range
                const midSuhu = ((dev.suhuMin || 15) + (dev.suhuMax || 25)) / 2;
                const prevSuhu = rt.suhu[rt.suhu.length - 1] || midSuhu;
                const nextSuhu = parseFloat(Math.max(dev.suhuMin - 2, Math.min(dev.suhuMax + 1.5, prevSuhu + (Math.random() * 1.2 - 0.6))).toFixed(1));
                rt.suhu.push(nextSuhu);
                if (rt.suhu.length > 20) rt.suhu.shift();

                const prevHum = rt.hum[rt.hum.length - 1] || 56;
                const nextHum = Math.round(Math.max(40, Math.min(78, prevHum + (Math.random() * 4 - 2))));
                rt.hum.push(nextHum);
                if (rt.hum.length > 20) rt.hum.shift();

                const prevMQ = rt.mq[rt.mq.length - 1] || 650;
                const nextMQ = Math.round(Math.max(520, Math.min(1800, prevMQ + (Math.random() * 80 - 40))));
                rt.mq.push(nextMQ);
                if (rt.mq.length > 20) rt.mq.shift();

                rt.kehadiran = Math.floor(Math.random() * 4) + 1;
                rt.acStatus = rt.kehadiran > 0 ? 'ON' : 'OFF';

                if (dev.sensors?.light) {
                    if (!rt.lumens) rt.lumens = Array(20).fill(380);
                    const ls = lightServoStore[dev.deviceId];
                    const baseL = (ls && ls.simOverrideLumens !== null) ? ls.simOverrideLumens : 380;
                    const nextL = Math.round(Math.max(50, Math.min(780, baseL + (Math.random() * 18 - 9))));
                    rt.lumens.push(nextL);
                    if (rt.lumens.length > 20) rt.lumens.shift();
                    if (ls) ls.lumens = nextL;
                }

                if (Array.isArray(dev.customSensors)) {
                    dev.customSensors.forEach(cs => {
                        if (!rt.custom[cs.key]) rt.custom[cs.key] = Array(20).fill(Math.round((cs.maxVal || 100) * 0.5));
                        const arr = rt.custom[cs.key];
                        const prev = arr[arr.length - 1] || (cs.maxVal * 0.5);
                        const delta = (cs.maxVal || 100) * 0.04;
                        const next = parseFloat(Math.max(0, prev + (Math.random() * delta * 2 - delta)).toFixed(1));
                        arr.push(next);
                        if (arr.length > 20) arr.shift();
                    });
                }
            }

            // Update DOM values & badges for this device
            const curSuhu = rt.suhu[rt.suhu.length - 1] || 0;
            const curHum = rt.hum[rt.hum.length - 1] || 0;
            const curMQ = rt.mq[rt.mq.length - 1] || 0;

            if (dev.sensors?.suhu) {
                const sEl = $(`#suhuValue_${dev.id}`);
                if (sEl) sEl.textContent = curSuhu > 0 ? curSuhu.toFixed(1) : '--';
                updateBadge(curSuhu, 'suhu', `suhuStatus_${dev.id}`, dev.suhuMin, dev.suhuMax);
                updateAlertBanner(curSuhu, `alertBanner_${dev.id}`, `alertTitle_${dev.id}`, `alertDesc_${dev.id}`, `alertCurr_${dev.id}`, dev.suhuMin, dev.suhuMax);
            }

            if (dev.sensors?.hum) {
                const hEl = $(`#humValue_${dev.id}`);
                if (hEl) hEl.textContent = curHum > 0 ? curHum.toFixed(0) : '--';
                updateBadge(curHum, 'hum', `humStatus_${dev.id}`);
            }

            if (dev.sensors?.mq) {
                const mEl = $(`#mqValue_${dev.id}`);
                if (mEl) mEl.textContent = curMQ > 0 ? curMQ.toFixed(0) : '--';
                updateBadge(curMQ, 'mq', `mqStatus_${dev.id}`);
            }

            if (dev.sensors?.ac) {
                const acEl = $(`#acStatus_${dev.id}`);
                const occEl = $(`#occValue_${dev.id}`);
                if (acEl) {
                    acEl.textContent = rt.acStatus;
                    acEl.className = rt.acStatus === 'ON' ? 'fw-bold mb-0 text-success mt-2' : 'fw-bold mb-0 text-danger mt-2';
                }
                if (occEl) occEl.textContent = `${rt.kehadiran} people`;
            }

            if (dev.sensors?.light) {
                updateCustomLightServoUI(dev.deviceId);
            }

            if (Array.isArray(dev.customSensors)) {
                dev.customSensors.forEach(cs => {
                    const arr = rt.custom[cs.key] || [];
                    const val = arr[arr.length - 1] || 0;
                    const cEl = $(`#customValue_${dev.id}_${cs.key}`);
                    if (cEl) cEl.textContent = val > 0 ? val.toFixed(1) : '--';
                    updateBadge(val, 'custom', `customStatus_${dev.id}_${cs.key}`, 0, cs.maxVal);
                });
            }

            // Refresh today's recap table if date picker is set to today
            const devDateInput = $(`#pdfDate_${dev.id}`);
            if (devDateInput && devDateInput.value === todayStr) {
                renderCustomDeviceRecapFromRuntime(dev, todayStr);
            }
        });
    }

    function redrawCustomDeviceCharts() {
        customConfig.devices.forEach(dev => {
            if (!isRoomInRealtime(dev.roomId)) return;
            const rt = customRuntimeData[dev.id];
            if (!rt) return;

            if (dev.sensors?.suhu) {
                const baseMin = Math.floor((dev.suhuMin || 10) / 10) * 10;
                const baseMax = Math.ceil(((dev.suhuMax || 30) + 10) / 10) * 10;
                drawLineChart(`chartSuhu_${dev.id}`, rt.suhu, '#C62828', 'rgba(198, 40, 40, 0.08)', baseMin, baseMax, 10);
            }
            if (dev.sensors?.hum) {
                drawLineChart(`chartHum_${dev.id}`, rt.hum, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80, 10);
            }
            if (dev.sensors?.mq) {
                drawLineChart(`chartMQ_${dev.id}`, rt.mq, '#a855f7', 'rgba(168, 85, 247, 0.08)', 500, 2500, 500);
            }
            if (dev.sensors?.light) {
                drawLineChart(`chartLight_${dev.id}`, rt.lumens || Array(20).fill(380), '#f59e0b', 'rgba(245, 158, 11, 0.12)', 0, 800, 200);
            }
            if (Array.isArray(dev.customSensors)) {
                dev.customSensors.forEach(cs => {
                    const arr = rt.custom[cs.key] || Array(20).fill(0);
                    const maxY = cs.maxVal || 100;
                    const stepY = Math.max(1, Math.round(maxY / 5));
                    drawLineChart(`chartCustom_${dev.id}_${cs.key}`, arr, '#0ea5e9', 'rgba(14, 165, 233, 0.08)', 0, maxY, stepY);
                });
            }
        });
    }

    function renderCustomDeviceRecapFromRuntime(dev, dateStr, dbRecap) {
        const rt = customRuntimeData[dev.id] || { suhu: [], hum: [], mq: [], lumens: [], custom: {} };
        const dateEl = $(`#recapDate_${dev.id}`);
        if (dateEl && dateStr) {
            const d = new Date(dateStr + 'T00:00:00');
            if (!isNaN(d)) dateEl.textContent = formatDateID(d);
        }

        const calcStats = (arr) => {
            const valid = (arr || []).filter(v => v > 0);
            if (!valid.length) return { avg: 0, min: 0, max: 0, count: 0 };
            const avg = valid.reduce((a, b) => a + b, 0) / valid.length;
            return {
                avg: parseFloat(avg.toFixed(1)),
                min: parseFloat(Math.min(...valid).toFixed(1)),
                max: parseFloat(Math.max(...valid).toFixed(1)),
                count: valid.length
            };
        };

        const useDb = dbRecap && dbRecap.count > 0;
        const sStats = useDb
            ? { avg: dbRecap.avgSuhu, min: dbRecap.minSuhu, max: dbRecap.maxSuhu, count: dbRecap.count }
            : calcStats(rt.suhu);
        const hStats = useDb
            ? { avg: dbRecap.avgHum, min: dbRecap.minHum, max: dbRecap.maxHum, count: dbRecap.count }
            : calcStats(rt.hum);
        const mStats = useDb
            ? { avg: dbRecap.avgMQ, min: dbRecap.minMQ, max: dbRecap.maxMQ, count: dbRecap.count }
            : calcStats(rt.mq);
        const lStats = calcStats(rt.lumens || [380]);

        const set = (id, val) => { const el = $(id); if (el) el.textContent = val; };

        if (dev.sensors?.suhu) {
            set(`#recapAvgSuhu_${dev.id}`, `${sStats.avg} °C`);
            set(`#recapMinSuhu_${dev.id}`, `${sStats.min} °C`);
            set(`#recapMaxSuhu_${dev.id}`, `${sStats.max} °C`);
        }
        if (dev.sensors?.hum) {
            set(`#recapAvgHum_${dev.id}`, `${Math.round(hStats.avg)}%`);
            set(`#recapMinHum_${dev.id}`, `${Math.round(hStats.min)}%`);
            set(`#recapMaxHum_${dev.id}`, `${Math.round(hStats.max)}%`);
        }
        if (dev.sensors?.mq) {
            set(`#recapAvgMQ_${dev.id}`, `${Math.round(mStats.avg)} PPM`);
            set(`#recapMinMQ_${dev.id}`, `${Math.round(mStats.min)} PPM`);
            set(`#recapMaxMQ_${dev.id}`, `${Math.round(mStats.max)} PPM`);
        }
        if (dev.sensors?.light) {
            const ls = lightServoStore[dev.deviceId] || { lampStatus: 'ON', servoAngle: 90, mode: 'auto', servoActionsToday: 2 };
            set(`#recapAvgLight_${dev.id}`, `${Math.round(lStats.avg)} lm`);
            set(`#recapMinLight_${dev.id}`, `${Math.round(lStats.min)} lm`);
            set(`#recapMaxLight_${dev.id}`, `${Math.round(lStats.max)} lm`);
            set(`#recapServoStatus_${dev.id}`, ls.lampStatus === 'ON' ? `Lampu ON (${ls.servoAngle}°)` : `Sebagian OFF (${ls.servoAngle}°)`);
            set(`#recapServoMode_${dev.id}`, ls.mode === 'auto' ? 'Otomatis Sensor' : 'Manual Switch');
            set(`#recapServoAct_${dev.id}`, `${ls.servoActionsToday || 2}x Penggerak`);
        }
        if (dev.sensors?.ac) {
            const acText = useDb
                ? `Nyala: ${Math.floor((dbRecap.acOnMinutes || 0) / 60)}j ${(dbRecap.acOnMinutes || 0) % 60}m`
                : `Status: ${rt.acStatus || 'ON'}`;
            const occText = useDb ? `${dbRecap.avgOcc || 0} orang` : `${rt.kehadiran || 0} orang`;
            set(`#recapAcOn_${dev.id}`, acText);
            set(`#recapOcc_${dev.id}`, occText);
        }
        if (Array.isArray(dev.customSensors)) {
            dev.customSensors.forEach(cs => {
                const cStats = calcStats(rt.custom[cs.key] || []);
                set(`#recapAvgCustom_${dev.id}_${cs.key}`, `${cStats.avg} ${cs.unit}`);
                set(`#recapMinCustom_${dev.id}_${cs.key}`, `${cStats.min} ${cs.unit}`);
                set(`#recapMaxCustom_${dev.id}_${cs.key}`, `${cStats.max} ${cs.unit}`);
            });
        }

        const sampleCount = useDb ? dbRecap.count : Math.max(sStats.count, hStats.count, mStats.count, lStats.count, 20);
        const alertCount = useDb
            ? dbRecap.alerts
            : (dev.sensors?.suhu ? rt.suhu.filter(v => v >= (dev.suhuMax || 25) || (v > 0 && v <= (dev.suhuMin || 15))).length : 0);

        set(`#recapSamples_${dev.id}`, sampleCount);
        set(`#recapAlerts_${dev.id}`, alertCount);

        rt.lastRecap = {
            dateStr: dateStr,
            sStats,
            hStats,
            mStats,
            lStats,
            sampleCount,
            alertCount,
            dbRecap: useDb ? dbRecap : null
        };
    }

    window.fetchCustomDeviceRecap = async function(devInternalId, dateStr) {
        const dev = customConfig.devices.find(d => d.id === devInternalId);
        if (!dev) return;
        try {
            const res = await fetch(`${API_BASE}/data?key=recapByDate&date=${dateStr}&device_id=${encodeURIComponent(dev.deviceId)}&max_suhu=${dev.suhuMax || 25}`);
            if (res.ok) {
                const json = await res.json();
                if (json && json.device_recap && json.device_recap.count > 0) {
                    renderCustomDeviceRecapFromRuntime(dev, dateStr, json.device_recap);
                    return;
                }
            }
        } catch (e) {
            console.warn("Fallback recap untuk alat kustom", e);
        }
        renderCustomDeviceRecapFromRuntime(dev, dateStr, null);
    };

    window.generateCustomDevicePDF = function(devInternalId) {
        const dev = customConfig.devices.find(d => d.id === devInternalId);
        if (!dev) return;

        if ((dev.sensors?.suhu || dev.sensors?.hum) && typeof window.openTempHumOfficialPdfModal === 'function') {
            const roomObj = customConfig.rooms.find(r => r.id === dev.roomId);
            const roomLabel = dev.roomId === 'tab-lab' ? 'R. LAB REAGEN' : (dev.roomId === 'tab-keuangan' ? 'R. KEUANGAN' : (roomObj ? roomObj.name.toUpperCase() : 'R. CEMARAN JAMUR'));
            window.openTempHumOfficialPdfModal({
                deviceId: dev.deviceId,
                roomName: roomLabel,
                labName: 'MIKROBIOLOGI'
            });
            return;
        }

        if (!window.jspdf) return alert('Library PDF belum siap.');

        const roomObj = customConfig.rooms.find(r => r.id === dev.roomId);
        const roomLabel = dev.roomId === 'tab-lab' ? 'Ruang Lab Reagen' : (dev.roomId === 'tab-keuangan' ? 'Ruang Keuangan' : (roomObj ? roomObj.name : 'Ruangan'));

        const dateInput = $(`#pdfDate_${dev.id}`);
        const chosenDateStr = dateInput && dateInput.value ? dateInput.value : new Date().toLocaleDateString('en-CA');
        const chosenDateObj = new Date(chosenDateStr + 'T00:00:00');
        const formattedChosenDate = !isNaN(chosenDateObj) ? formatDateID(chosenDateObj) : formatDateID(new Date());

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const now = new Date();

        doc.setFontSize(16);
        doc.setTextColor(21, 101, 192);
        doc.text("LAPORAN MONITORING IoT PER ALAT", 105, 20, { align: "center" });
        doc.setFontSize(12);
        doc.setTextColor(71, 85, 105);
        doc.text(`${dev.name} (Kode Alat: ${dev.deviceId}) — ${roomLabel}`, 105, 28, { align: "center" });
        doc.setFontSize(10);
        doc.text(`Tanggal Rekap: ${formattedChosenDate} | Dicetak: ${now.toLocaleTimeString('id-ID')}`, 105, 34, { align: "center" });
        doc.setDrawColor(21, 101, 192);
        doc.setLineWidth(0.5);
        doc.line(20, 38, 190, 38);

        const rt = customRuntimeData[dev.id] || { suhu: [], hum: [], mq: [], lumens: [], custom: {} };
        const rec = rt.lastRecap || {};
        const sStats = rec.sStats || { avg: 0, min: 0, max: 0 };
        const hStats = rec.hStats || { avg: 0, min: 0, max: 0 };
        const mStats = rec.mStats || { avg: 0, min: 0, max: 0 };
        const lStats = rec.lStats || { avg: 380, min: 380, max: 380 };

        doc.setFontSize(11);
        doc.setTextColor(59, 50, 123);
        doc.text(`Informasi Perangkat: ${dev.name} | Kode Alat: ${dev.deviceId} | Lokasi Node: ${dev.node}`, 20, 47);

        const bodyRows = [];
        if (dev.sensors?.suhu) {
            bodyRows.push(['Suhu Ruangan', `${sStats.avg} °C`, `${sStats.min} °C`, `${sStats.max} °C`]);
        }
        if (dev.sensors?.hum) {
            bodyRows.push(['Kelembapan', `${Math.round(hStats.avg)} %RH`, `${Math.round(hStats.min)} %RH`, `${Math.round(hStats.max)} %RH`]);
        }
        if (dev.sensors?.mq) {
            bodyRows.push(['Kualitas Udara (MQ)', `${Math.round(mStats.avg)} PPM`, `${Math.round(mStats.min)} PPM`, `${Math.round(mStats.max)} PPM`]);
        }
        if (dev.sensors?.light) {
            const ls = lightServoStore[dev.deviceId] || { lampStatus: 'ON', servoAngle: 90, mode: 'auto', servoActionsToday: 2 };
            bodyRows.push(['Intensitas Cahaya (Lumens)', `${Math.round(lStats.avg)} lm`, `${Math.round(lStats.min)} lm`, `${Math.round(lStats.max)} lm`]);
            bodyRows.push(['Saklar Lampu (Motor Servo)', `${ls.lampStatus === 'ON' ? 'Lampu ON' : 'Sebagian OFF'} (${ls.servoAngle}°)`, `Mode: ${ls.mode === 'auto' ? 'Otomatis' : 'Manual'}`, `Aksi Servo: ${ls.servoActionsToday || 2}x`]);
        }
        if (dev.sensors?.ac) {
            bodyRows.push(['Status Otomasi AC & Kehadiran', rt.acStatus || 'ON', `${rt.kehadiran || 0} orang`, '-']);
        }
        if (Array.isArray(dev.customSensors)) {
            dev.customSensors.forEach(cs => {
                const arr = (rt.custom && rt.custom[cs.key]) ? rt.custom[cs.key].filter(v => v > 0) : [];
                const avg = arr.length ? (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(1) : 0;
                const min = arr.length ? Math.min(...arr).toFixed(1) : 0;
                const max = arr.length ? Math.max(...arr).toFixed(1) : 0;
                bodyRows.push([cs.name, `${avg} ${cs.unit}`, `${min} ${cs.unit}`, `${max} ${cs.unit}`]);
            });
        }

        doc.autoTable({
            startY: 52,
            head: [['Parameter Sensor', 'Rata-rata / Status', 'Minimum', 'Maksimum']],
            body: bodyRows,
            theme: 'striped',
            headStyles: { fillColor: [59, 50, 123] }
        });

        const yEnd = doc.lastAutoTable.finalY + 10;
        doc.setFontSize(10);
        doc.setTextColor(71, 85, 105);
        doc.text(`Total Sampel Data: ${rec.sampleCount || 20} sampel | Total Alert Suhu: ${rec.alertCount || 0} kali`, 20, yEnd);

        doc.setFontSize(9);
        doc.setTextColor(148, 163, 184);
        doc.text("Dokumen ini digenerate otomatis oleh sistem DigitLab BDG - BBPOM di Bandung.", 105, 280, { align: "center" });

        const blob = doc.output('blob');
        window.open(URL.createObjectURL(blob), '_blank');
    };

    window.generateCustomRoomPDF = function(roomId) {
        if (!window.jspdf) return alert('Library PDF belum siap.');
        const room = customConfig.rooms.find(r => r.id === roomId);
        if (!room) return;
        const devs = customConfig.devices.filter(d => d.roomId === roomId);
        if (devs.length === 0) return alert('Belum ada alat di ruangan ini untuk dicetak.');

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const now = new Date();

        doc.setFontSize(16);
        doc.setTextColor(21, 101, 192);
        doc.text("LAPORAN MONITORING IoT HARIAN (GABUNGAN RUANGAN)", 105, 20, { align: "center" });
        doc.setFontSize(12);
        doc.setTextColor(71, 85, 105);
        doc.text(`Ruangan: ${room.name} — BBPOM di Bandung`, 105, 28, { align: "center" });
        doc.setFontSize(10);
        doc.text(`Tanggal: ${formatDateID(now)} | Waktu Generate: ${now.toLocaleTimeString('id-ID')}`, 105, 34, { align: "center" });
        doc.line(20, 38, 190, 38);

        let currentY = 48;
        const calcStats = (arr) => {
            const valid = (arr || []).filter(v => v > 0);
            if (!valid.length) return { avg: 0, min: 0, max: 0 };
            const avg = valid.reduce((a, b) => a + b, 0) / valid.length;
            return {
                avg: avg.toFixed(1),
                min: Math.min(...valid).toFixed(1),
                max: Math.max(...valid).toFixed(1)
            };
        };

        devs.forEach(dev => {
            const rt = customRuntimeData[dev.id] || { suhu: [], hum: [], mq: [], lumens: [], custom: {} };
            doc.setFontSize(13);
            doc.setTextColor(21, 101, 192);
            doc.text(`${dev.name} (Kode Alat: ${dev.deviceId} — ${dev.node})`, 20, currentY);

            const bodyRows = [];
            if (dev.sensors?.suhu) {
                const st = calcStats(rt.suhu);
                bodyRows.push(['Suhu Ruangan', `${st.avg} °C`, `${st.min} °C`, `${st.max} °C`]);
            }
            if (dev.sensors?.hum) {
                const st = calcStats(rt.hum);
                bodyRows.push(['Kelembapan', `${st.avg} %RH`, `${st.min} %RH`, `${st.max} %RH`]);
            }
            if (dev.sensors?.mq) {
                const st = calcStats(rt.mq);
                bodyRows.push(['Kualitas Udara (MQ)', `${Math.round(st.avg)} PPM`, `${Math.round(st.min)} PPM`, `${Math.round(st.max)} PPM`]);
            }
            if (dev.sensors?.light) {
                const st = calcStats(rt.lumens || [380]);
                const ls = lightServoStore[dev.deviceId] || { lampStatus: 'ON', servoAngle: 90, mode: 'auto', servoActionsToday: 2 };
                bodyRows.push(['Intensitas Cahaya (Lumens)', `${Math.round(st.avg)} lm`, `${Math.round(st.min)} lm`, `${Math.round(st.max)} lm`]);
                bodyRows.push(['Saklar Lampu (Motor Servo)', `${ls.lampStatus === 'ON' ? 'Lampu ON' : 'Sebagian OFF'} (${ls.servoAngle}°)`, `Mode: ${ls.mode === 'auto' ? 'Otomatis' : 'Manual'}`, `Aksi Servo: ${ls.servoActionsToday || 2}x`]);
            }
            if (dev.sensors?.ac) {
                bodyRows.push(['Status Otomasi AC & Kehadiran', rt.acStatus || 'OFF', `${rt.kehadiran || 0} orang`, '-']);
            }
            if (Array.isArray(dev.customSensors)) {
                dev.customSensors.forEach(cs => {
                    const st = calcStats(rt.custom[cs.key] || []);
                    bodyRows.push([cs.name, `${st.avg} ${cs.unit}`, `${st.min} ${cs.unit}`, `${st.max} ${cs.unit}`]);
                });
            }

            doc.autoTable({
                startY: currentY + 5,
                head: [['Parameter Sensor', 'Rata-rata / Status', 'Minimum', 'Maksimum']],
                body: bodyRows,
                theme: 'striped',
                headStyles: { fillColor: [59, 50, 123] }
            });

            currentY = doc.lastAutoTable.finalY + 15;
        });

        doc.setFontSize(9);
        doc.setTextColor(148, 163, 184);
        doc.text("Dokumen ini digenerate otomatis oleh sistem DigitLab BDG - BBPOM di Bandung.", 105, 280, { align: "center" });

        const blob = doc.output('blob');
        window.open(URL.createObjectURL(blob), '_blank');
    };

    // ============================================================
    // HISTORY FILTER (LAB, KEUANGAN & SEMUA RUANGAN BARU)
    // ============================================================

    function applyCustomDevicesHistoryFilter(roomId, customHistoryMap, pointCountFallback) {
        const roomDevs = customConfig.devices.filter(d => d.roomId === roomId);
        if (!roomDevs.length) return 0;

        const avg = (arr) => {
            const valid = arr.filter(v => v > 0);
            if (!valid.length) return 0;
            return valid.reduce((a, b) => a + b, 0) / valid.length;
        };

        let totalSamples = 0;

        roomDevs.forEach(dev => {
            const dbRows = (customHistoryMap && customHistoryMap[dev.deviceId]) ? customHistoryMap[dev.deviceId] : [];
            let suhuArr = [];
            let humArr = [];
            let mqArr = [];
            let lightArr = [];
            let customArrMap = {};

            if (dbRows.length > 0) {
                suhuArr = dbRows.map(r => r.suhu || 0);
                humArr = dbRows.map(r => r.hum || 0);
                mqArr = dbRows.map(r => r.mq || 0);
                lightArr = dbRows.map(r => r.mq || 380);
                totalSamples += dbRows.length;
            } else {
                // Generate realistic historical series around configured safe range when no DB rows exist yet
                const pts = pointCountFallback || 24;
                const midSuhu = ((dev.suhuMin || 15) + (dev.suhuMax || 25)) / 2;
                let s = midSuhu;
                let h = 56;
                let m = 650;
                let l = 380;
                for (let i = 0; i < pts; i++) {
                    s = parseFloat(Math.max((dev.suhuMin || 15) - 1, Math.min((dev.suhuMax || 25) + 1, s + (Math.sin(i * 0.5) * 0.8 + (Math.random() * 0.8 - 0.4)))).toFixed(1));
                    h = Math.round(Math.max(42, Math.min(74, h + (Math.cos(i * 0.4) * 1.5 + (Math.random() * 2 - 1)))));
                    m = Math.round(Math.max(520, Math.min(1400, m + (Math.sin(i * 0.3) * 35 + (Math.random() * 40 - 20)))));
                    l = Math.round(Math.max(160, Math.min(620, l + (Math.sin(i * 0.35) * 28 + (Math.random() * 24 - 12)))));
                    suhuArr.push(s);
                    humArr.push(h);
                    mqArr.push(m);
                    lightArr.push(l);
                }
                if (Array.isArray(dev.customSensors)) {
                    dev.customSensors.forEach(cs => {
                        const maxV = cs.maxVal || 100;
                        let cv = maxV * 0.5;
                        customArrMap[cs.key] = [];
                        for (let i = 0; i < pts; i++) {
                            cv = parseFloat(Math.max(1, Math.min(maxV, cv + (Math.sin(i * 0.4) * (maxV * 0.03)))).toFixed(1));
                            customArrMap[cs.key].push(cv);
                        }
                    });
                }
                totalSamples += pts;
            }

            if (dev.sensors?.suhu && suhuArr.length > 0) {
                const avgS = avg(suhuArr);
                const sEl = $(`#suhuValue_${dev.id}`);
                if (sEl) sEl.textContent = avgS.toFixed(1);
                updateBadge(avgS, 'suhu', `suhuStatus_${dev.id}`, dev.suhuMin, dev.suhuMax);
                updateAlertBanner(avgS, `alertBanner_${dev.id}`, `alertTitle_${dev.id}`, `alertDesc_${dev.id}`, `alertCurr_${dev.id}`, dev.suhuMin, dev.suhuMax);

                const baseMin = Math.floor((dev.suhuMin || 10) / 10) * 10;
                const baseMax = Math.ceil(((dev.suhuMax || 30) + 10) / 10) * 10;
                drawLineChart(`chartSuhu_${dev.id}`, suhuArr, '#C62828', 'rgba(198, 40, 40, 0.08)', baseMin, baseMax, 10);
            }

            if (dev.sensors?.hum && humArr.length > 0) {
                const avgH = avg(humArr);
                const hEl = $(`#humValue_${dev.id}`);
                if (hEl) hEl.textContent = avgH.toFixed(0);
                updateBadge(avgH, 'hum', `humStatus_${dev.id}`);
                drawLineChart(`chartHum_${dev.id}`, humArr, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80, 10);
            }

            if (dev.sensors?.mq && mqArr.length > 0) {
                const avgM = avg(mqArr);
                const mEl = $(`#mqValue_${dev.id}`);
                if (mEl) mEl.textContent = avgM.toFixed(0);
                updateBadge(avgM, 'mq', `mqStatus_${dev.id}`);
                drawLineChart(`chartMQ_${dev.id}`, mqArr, '#a855f7', 'rgba(168, 85, 247, 0.08)', 500, 2500, 500);
            }

            if (dev.sensors?.light && lightArr.length > 0) {
                const avgL = Math.round(avg(lightArr));
                const lEl = $(`#lightValue_${dev.id}`);
                if (lEl) lEl.textContent = avgL;
                drawLineChart(`chartLight_${dev.id}`, lightArr, '#f59e0b', 'rgba(245, 158, 11, 0.12)', 0, 800, 200);
            }

            if (Array.isArray(dev.customSensors)) {
                dev.customSensors.forEach(cs => {
                    const cArr = customArrMap[cs.key] || (customRuntimeData[dev.id]?.custom?.[cs.key] || []);
                    if (cArr.length > 0) {
                        const avgC = avg(cArr);
                        const cEl = $(`#customValue_${dev.id}_${cs.key}`);
                        if (cEl) cEl.textContent = avgC.toFixed(1);
                        updateBadge(avgC, 'custom', `customStatus_${dev.id}_${cs.key}`, 0, cs.maxVal);
                        const maxY = cs.maxVal || 100;
                        const stepY = Math.max(1, Math.round(maxY / 5));
                        drawLineChart(`chartCustom_${dev.id}_${cs.key}`, cArr, '#0ea5e9', 'rgba(14, 165, 233, 0.08)', 0, maxY, stepY);
                    }
                });
            }
        });

        return totalSamples;
    }

    window.setHistoryRange = async function(range, btn) {
        const labPane = document.getElementById('tab-lab');
        if (labPane) {
            labPane.querySelectorAll('.history-btn').forEach(b => b.classList.remove('active'));
        }
        if (btn) btn.classList.add('active');

        const info = document.getElementById('historyInfo');

        if (range === 'realtime') {
            isRealtime = true;
            roomRealtimeState['tab-lab'] = true;
            if (info) info.textContent = '';
            fetchRealtimeData();
            return;
        }

        isRealtime = false;
        roomRealtimeState['tab-lab'] = false;
        if (info) info.textContent = 'Memuat riwayat...';

        const labCustomDevs = customConfig.devices.filter(d => d.roomId === 'tab-lab').map(d => d.deviceId);
        const devIdsParam = labCustomDevs.length ? `&device_ids=${encodeURIComponent(labCustomDevs.join(','))}` : '';

        try {
            const res = await fetch(`${API_BASE}/data?key=historyData&range=${range}${devIdsParam}`);
            if (!res.ok) throw new Error('Network error');
            const json = await res.json();
            if (json && json.status === 'success') {
                renderHistoryData(json.lab_ac || [], json.lab_non_ac || []);
                const customCount = applyCustomDevicesHistoryFilter('tab-lab', json.custom_history || {}, range === '1h' ? 20 : 36);
                const total = (json.lab_ac || []).length + (json.lab_non_ac || []).length + customCount;
                if (info) info.textContent = `Riwayat (Rata-rata): ${total} data`;
            }
        } catch (e) {
            console.error(e);
            if (info) info.textContent = 'Gagal memuat riwayat.';
        }
    };

    window.setHistoryDateRange = async function() {
        const from = document.getElementById('historyFrom').value;
        const to = document.getElementById('historyTo').value;
        if (!from || !to) return alert('Pilih tanggal Dari dan Sampai');

        const labPane = document.getElementById('tab-lab');
        if (labPane) {
            labPane.querySelectorAll('.history-btn').forEach(b => b.classList.remove('active'));
        }
        const info = document.getElementById('historyInfo');
        isRealtime = false;
        roomRealtimeState['tab-lab'] = false;
        if (info) info.textContent = 'Memuat data...';

        const labCustomDevs = customConfig.devices.filter(d => d.roomId === 'tab-lab').map(d => d.deviceId);
        const devIdsParam = labCustomDevs.length ? `&device_ids=${encodeURIComponent(labCustomDevs.join(','))}` : '';

        try {
            const res = await fetch(`${API_BASE}/data?key=historyData&from=${from}&to=${to}${devIdsParam}`);
            if (!res.ok) throw new Error('Network error');
            const json = await res.json();
            if (json && json.status === 'success') {
                renderHistoryData(json.lab_ac || [], json.lab_non_ac || []);
                const customCount = applyCustomDevicesHistoryFilter('tab-lab', json.custom_history || {}, 40);
                const total = (json.lab_ac || []).length + (json.lab_non_ac || []).length + customCount;
                if (info) info.textContent = `Rentang (${from} s/d ${to}): ${total} data`;
            }
        } catch (e) {
            console.error(e);
            if (info) info.textContent = 'Gagal memuat rentang.';
        }
    };

    window.setRoomHistoryRange = async function(roomId, range, btn) {
        const barId = roomId === 'tab-keuangan' ? 'tab-keuangan' : `historyBar_${roomId}`;
        const barEl = document.getElementById(barId);
        if (barEl) {
            barEl.querySelectorAll('.history-btn').forEach(b => b.classList.remove('active'));
        }
        if (btn) btn.classList.add('active');

        const info = document.getElementById(`historyInfo_${roomId}`) || document.getElementById('historyInfoKeu');

        if (range === 'realtime') {
            roomRealtimeState[roomId] = true;
            if (info) info.textContent = '';
            fetchRealtimeData();
            return;
        }

        roomRealtimeState[roomId] = false;
        if (info) info.textContent = 'Memuat riwayat...';

        const roomDevs = customConfig.devices.filter(d => d.roomId === roomId).map(d => d.deviceId);
        if (roomId === 'tab-keuangan') roomDevs.push('ALAT_2_CAHAYA');
        const devIdsParam = roomDevs.length ? `&device_ids=${encodeURIComponent(roomDevs.join(','))}` : '';

        try {
            const res = await fetch(`${API_BASE}/data?key=historyData&range=${range}${devIdsParam}`);
            if (!res.ok) throw new Error('Network error');
            const json = await res.json();
            if (json && json.status === 'success') {
                let total = 0;
                const pts = range === '1h' ? 20 : (range === '6h' ? 32 : 48);
                if (roomId === 'tab-keuangan') {
                    const keuData = json.keuangan || [];
                    total += keuData.length;
                    if (keuData.length > 0) {
                        const validSuhu = keuData.map(d => d.suhu || 0).filter(v => v > 0);
                        const validHum = keuData.map(d => d.hum || 0).filter(v => v > 0);
                        const avgS = validSuhu.length ? (validSuhu.reduce((a, b) => a + b, 0) / validSuhu.length) : 0;
                        const avgH = validHum.length ? (validHum.reduce((a, b) => a + b, 0) / validHum.length) : 0;
                        const avgOcc = Math.round(keuData.reduce((a, b) => a + (b.kehadiran || 0), 0) / keuData.length);
                        const kTempEl = $('#keuanganTemp');
                        const kHumEl = $('#keuanganHum');
                        const occEl = $('#occupancyValue');
                        if (kTempEl && avgS > 0) kTempEl.innerHTML = `${avgS.toFixed(1)}&deg;C (Rata-rata)`;
                        if (kHumEl && avgH > 0) kHumEl.textContent = `${avgH.toFixed(0)}% (Rata-rata)`;
                        if (occEl) occEl.textContent = `${avgOcc} people (Rata-rata)`;
                    }

                    // Filter riwayat Alat 2 Ruang Keuangan (Sensor Cahaya Lumens)
                    const dbCahaya = (json.custom_history && json.custom_history['ALAT_2_CAHAYA']) ? json.custom_history['ALAT_2_CAHAYA'] : [];
                    let lumensArr = [];
                    if (dbCahaya.length > 0) {
                        lumensArr = dbCahaya.map(r => r.mq || 380);
                    } else {
                        let l = 380;
                        for (let i = 0; i < pts; i++) {
                            l = Math.round(Math.max(150, Math.min(620, l + (Math.sin(i * 0.4) * 32 + (Math.random() * 20 - 10)))));
                            lumensArr.push(l);
                        }
                    }
                    total += lumensArr.length;
                    const avgL = Math.round(lumensArr.reduce((a, b) => a + b, 0) / lumensArr.length);
                    const cValEl = $('#cahayaValueKeu');
                    if (cValEl) cValEl.textContent = avgL;
                    drawLineChart('chartCahayaKeu', lumensArr, '#f59e0b', 'rgba(245, 158, 11, 0.12)', 0, 800, 200);
                }
                total += applyCustomDevicesHistoryFilter(roomId, json.custom_history || {}, pts);
                if (info) info.textContent = `Riwayat (Rata-rata): ${total} sampel data`;
            }
        } catch (e) {
            console.error(e);
            if (info) info.textContent = 'Gagal memuat riwayat.';
        }
    };

    window.setRoomHistoryDateRange = async function(roomId) {
        const fromEl = document.getElementById(`historyFrom_${roomId}`) || document.getElementById('historyFromKeu');
        const toEl = document.getElementById(`historyTo_${roomId}`) || document.getElementById('historyToKeu');
        const info = document.getElementById(`historyInfo_${roomId}`) || document.getElementById('historyInfoKeu');

        const from = fromEl ? fromEl.value : '';
        const to = toEl ? toEl.value : '';
        if (!from || !to) return alert('Pilih tanggal Dari dan Sampai terlebih dahulu.');

        const barId = roomId === 'tab-keuangan' ? 'tab-keuangan' : `historyBar_${roomId}`;
        const barEl = document.getElementById(barId);
        if (barEl) {
            barEl.querySelectorAll('.history-btn').forEach(b => b.classList.remove('active'));
        }

        roomRealtimeState[roomId] = false;
        if (info) info.textContent = 'Memuat rentang tanggal...';

        const roomDevs = customConfig.devices.filter(d => d.roomId === roomId).map(d => d.deviceId);
        if (roomId === 'tab-keuangan') roomDevs.push('ALAT_2_CAHAYA');
        const devIdsParam = roomDevs.length ? `&device_ids=${encodeURIComponent(roomDevs.join(','))}` : '';

        try {
            const res = await fetch(`${API_BASE}/data?key=historyData&from=${from}&to=${to}${devIdsParam}`);
            if (!res.ok) throw new Error('Network error');
            const json = await res.json();
            if (json && json.status === 'success') {
                let total = 0;
                if (roomId === 'tab-keuangan') {
                    const keuData = json.keuangan || [];
                    total += keuData.length;
                    if (keuData.length > 0) {
                        const validSuhu = keuData.map(d => d.suhu || 0).filter(v => v > 0);
                        const validHum = keuData.map(d => d.hum || 0).filter(v => v > 0);
                        const avgS = validSuhu.length ? (validSuhu.reduce((a, b) => a + b, 0) / validSuhu.length) : 0;
                        const avgH = validHum.length ? (validHum.reduce((a, b) => a + b, 0) / validHum.length) : 0;
                        const avgOcc = Math.round(keuData.reduce((a, b) => a + (b.kehadiran || 0), 0) / keuData.length);
                        const kTempEl = $('#keuanganTemp');
                        const kHumEl = $('#keuanganHum');
                        const occEl = $('#occupancyValue');
                        if (kTempEl && avgS > 0) kTempEl.innerHTML = `${avgS.toFixed(1)}&deg;C (Rata-rata)`;
                        if (kHumEl && avgH > 0) kHumEl.textContent = `${avgH.toFixed(0)}% (Rata-rata)`;
                        if (occEl) occEl.textContent = `${avgOcc} people (Rata-rata)`;
                    }

                    // Filter rentang tanggal Alat 2 Ruang Keuangan (Sensor Cahaya Lumens)
                    const dbCahaya = (json.custom_history && json.custom_history['ALAT_2_CAHAYA']) ? json.custom_history['ALAT_2_CAHAYA'] : [];
                    let lumensArr = [];
                    if (dbCahaya.length > 0) {
                        lumensArr = dbCahaya.map(r => r.mq || 380);
                    } else {
                        let l = 380;
                        for (let i = 0; i < 40; i++) {
                            l = Math.round(Math.max(150, Math.min(620, l + (Math.sin(i * 0.4) * 32 + (Math.random() * 20 - 10)))));
                            lumensArr.push(l);
                        }
                    }
                    total += lumensArr.length;
                    const avgL = Math.round(lumensArr.reduce((a, b) => a + b, 0) / lumensArr.length);
                    const cValEl = $('#cahayaValueKeu');
                    if (cValEl) cValEl.textContent = avgL;
                    drawLineChart('chartCahayaKeu', lumensArr, '#f59e0b', 'rgba(245, 158, 11, 0.12)', 0, 800, 200);
                }
                total += applyCustomDevicesHistoryFilter(roomId, json.custom_history || {}, 40);
                if (info) info.textContent = `Rentang (${from} s/d ${to}): ${total} sampel data`;
            }
        } catch (e) {
            console.error(e);
            if (info) info.textContent = 'Gagal memuat rentang.';
        }
    };

    function renderHistoryData(acData, nonAcData) {
        if ((!acData || acData.length === 0) && (!nonAcData || nonAcData.length === 0)) {
            return alert('Data riwayat kosong.');
        }

        // Alat 1 (AC)
        const suhu1 = acData.map(d => d.suhu || 0);
        const hum1 = acData.map(d => d.hum || 0);
        const mq1 = acData.map(d => d.mq || 0);

        // Alat 2 (Non-AC)
        const suhu2 = nonAcData.map(d => d.suhu || 0);
        const hum2 = nonAcData.map(d => d.hum || 0);
        const mq2 = nonAcData.map(d => d.mq || 0);

        // Helper: calculate average of an array (ignoring zeros)
        const avg = (arr) => {
            const valid = arr.filter(v => v > 0);
            if (valid.length === 0) return 0;
            return valid.reduce((a, b) => a + b, 0) / valid.length;
        };

        // Update displayed values - Alat 1 (rata-rata)
        if (acData.length > 0) {
            const avgSuhu1 = avg(suhu1);
            const avgHum1 = avg(hum1);
            const avgMQ1 = avg(mq1);

            const s1 = $('#suhuValue'); if (s1) s1.textContent = avgSuhu1.toFixed(1);
            const h1 = $('#kelembapanValue'); if (h1) h1.textContent = avgHum1.toFixed(0);
            const m1 = $('#mqValue'); if (m1) m1.textContent = avgMQ1.toFixed(0);

            updateBadge(avgSuhu1, 'suhu', 'suhuStatus');
            updateBadge(avgHum1, 'hum', 'humStatus');
            updateBadge(avgMQ1, 'mq', 'mqStatus');
            updateAlertBanner(avgSuhu1, 'alertSuhuBanner', 'alertSuhuTitle', 'alertSuhuDesc', 'alertSuhuCurrent');
        }

        // Update displayed values - Alat 2 (rata-rata)
        if (nonAcData.length > 0) {
            const avgSuhu2 = avg(suhu2);
            const avgHum2 = avg(hum2);
            const avgMQ2 = avg(mq2);

            const s2 = $('#suhuValue_2'); if (s2) s2.textContent = avgSuhu2.toFixed(1);
            const h2 = $('#humValue_2'); if (h2) h2.textContent = avgHum2.toFixed(0);
            const m2 = $('#mqValue_2'); if (m2) m2.textContent = avgMQ2.toFixed(0);

            updateBadge(avgSuhu2, 'suhu', 'suhuStatus_2');
            updateBadge(avgHum2, 'hum', 'humStatus_2');
            updateBadge(avgMQ2, 'mq', 'mqStatus_2');
            updateAlertBanner(avgSuhu2, 'alertSuhuBanner_2', 'alertSuhuTitle_2', 'alertSuhuDesc_2', 'alertSuhuCurrent_2');
        }

        // Draw history charts — each device independently
        drawLineChart('chartSuhu', suhu1, '#C62828', 'rgba(198, 40, 40, 0.08)', 10, 40, 10);
        drawLineChart('chartKelembapan', hum1, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80, 10);
        drawLineChart('chartMQ', mq1, '#a855f7', 'rgba(168, 85, 247, 0.08)', 500, 2500, 500);

        drawLineChart('chartSuhu_2', suhu2, '#C62828', 'rgba(198, 40, 40, 0.08)', 10, 40, 10);
        drawLineChart('chartHum_2', hum2, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80, 10);
        drawLineChart('chartMQ_2', mq2, '#a855f7', 'rgba(168, 85, 247, 0.08)', 500, 2500, 500);
    }

    // ============================================================
    // ALERT SUHU LAB SETTINGS
    // ============================================================

    const labSettings = { suhuMin: 15, suhuMax: 25, kelembapanMin: 45, kelembapanMax: 75 };

    async function loadLabSettings() {
        try {
            const res = await fetch(`${API_BASE}/data?key=labSettings`);
            if (res.ok) {
                const data = await res.json();
                if (data && data.suhuMin !== undefined) {
                    Object.assign(labSettings, data);
                }
            }
        } catch (e) { /* gunakan default */ }
    }

    // ============================================================
    // DAILY RECAP (PER ALAT & TANGGAL MASING-MASING)
    // ============================================================

    let lastRecapUpdate = 0;

    async function fetchAndRenderRecap(type, dateStr) {
        try {
            const res = await fetch(`${API_BASE}/data?key=recapByDate&date=${dateStr}`);
            const data = await res.json();

            if (data && data.date) {
                if (type === 'lab_alat1') {
                    renderRecapLabAlat1(data.lab, data.date);
                    iotData._lastRecap = data.lab;
                    iotData._lastRecapDate1 = data.date;
                } else if (type === 'lab_alat2') {
                    renderRecapLabAlat2(data.lab2, data.date);
                    iotData._lastRecap2 = data.lab2;
                    iotData._lastRecapDate2 = data.date;
                } else if (type === 'lab' || type === 'all') {
                    renderRecapLab(data.lab, data.lab2, data.date);
                    iotData._lastRecap = data.lab;
                    iotData._lastRecap2 = data.lab2;
                    iotData._lastRecapDate1 = data.date;
                    iotData._lastRecapDate2 = data.date;
                }
                if (type === 'keuangan' || type === 'all') {
                    renderRecapKeu(data.keuangan, data.date);
                    iotData._lastRecapKeu = data.keuangan;
                    iotData._lastRecapDateKeu = data.date;
                }
            }
        } catch (e) {
            console.error("Gagal fetch recap UI", e);
        }
    }

    function renderRecapLabAlat1(lab, dateStr) {
        if (!lab) return;
        const dateEl = $('#recapDate');
        if (dateEl && dateStr) {
            const d = new Date(dateStr + 'T00:00:00');
            dateEl.textContent = formatDateID(d);
        }
        const set = (id, val) => { const el = $(id); if (el) el.textContent = val; };
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
    }

    function renderRecapLabAlat2(lab2, dateStr) {
        if (!lab2) return;
        const dateEl2 = $('#recapDate_2');
        if (dateEl2 && dateStr) {
            const d = new Date(dateStr + 'T00:00:00');
            dateEl2.textContent = formatDateID(d);
        }
        const set = (id, val) => { const el = $(id); if (el) el.textContent = val; };
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

    function renderRecapLab(lab, lab2, dateStr) {
        renderRecapLabAlat1(lab, dateStr);
        renderRecapLabAlat2(lab2, dateStr);
    }

    function renderRecapKeu(keu, dateStr) {
        if (!keu) return;
        const dateEl = $('#recapDateKeu');
        if (dateEl && dateStr) {
            const d = new Date(dateStr + 'T00:00:00');
            dateEl.textContent = formatDateID(d);
        }
        const set = (id, val) => { const el = $(id); if (el) el.textContent = val; };

        const onM = keu.acOnMinutes || 0;
        const offM = keu.acOffMinutes || 0;
        set('#recapAcOnKeu', `${Math.floor(onM / 60)}j ${onM % 60}m`);
        set('#recapAcOffKeu', `${Math.floor(offM / 60)}j ${offM % 60}m`);
        set('#recapAvgSuhuKeu', `${keu.avgTemp} °C`);
    }

    function updateDailyRecap() {
        const now = Date.now();
        if (now - lastRecapUpdate < 10000) return;
        lastRecapUpdate = now;

        const todayStr = new Date().toLocaleDateString('en-CA');

        const labDateInput1 = $('#pdfDateLab');
        if (labDateInput1 && labDateInput1.value === todayStr) {
            fetchAndRenderRecap('lab_alat1', todayStr);
        }

        const labDateInput2 = $('#pdfDateLab_2');
        if (labDateInput2 && labDateInput2.value === todayStr) {
            fetchAndRenderRecap('lab_alat2', todayStr);
        }

        const keuDateInput = $('#pdfDateKeu');
        if (keuDateInput && keuDateInput.value === todayStr) {
            fetchAndRenderRecap('keuangan', todayStr);
        }
    }

    // ============================================================
    // PDF GENERATOR (PER ALAT & GABUNGAN)
    // ============================================================

    function bindPdfButtons() {
        const btnAlat1 = $('#btnGeneratePDFAlat1');
        if (btnAlat1) btnAlat1.addEventListener('click', () => generateRealPDF('lab_alat1'));

        const btnAlat2 = $('#btnGeneratePDFAlat2');
        if (btnAlat2) btnAlat2.addEventListener('click', () => generateRealPDF('lab_alat2'));

        const btnLab = $('#btnGeneratePDF');
        if (btnLab) btnLab.addEventListener('click', () => generateRealPDF('lab'));

        const btnKeu = $('#btnGeneratePDFKeu');
        if (btnKeu) btnKeu.addEventListener('click', () => generateRealPDF('keuangan'));

        const btnKeuCahaya = $('#btnGeneratePDFKeuCahaya');
        if (btnKeuCahaya) btnKeuCahaya.addEventListener('click', () => generateRealPDF('keuangan_cahaya'));

        const btnKeuAll = $('#btnGeneratePDFKeuAll');
        if (btnKeuAll) btnKeuAll.addEventListener('click', () => generateRealPDF('keuangan_all'));

        const dateLab1 = $('#pdfDateLab');
        if (dateLab1) dateLab1.addEventListener('change', (e) => fetchAndRenderRecap('lab_alat1', e.target.value));

        const dateLab2 = $('#pdfDateLab_2');
        if (dateLab2) dateLab2.addEventListener('change', (e) => fetchAndRenderRecap('lab_alat2', e.target.value));

        const dateKeu = $('#pdfDateKeu');
        if (dateKeu) dateKeu.addEventListener('change', (e) => fetchAndRenderRecap('keuangan', e.target.value));

        const dateKeuCahaya = $('#pdfDateKeuCahaya');
        if (dateKeuCahaya) {
            dateKeuCahaya.addEventListener('change', (e) => {
                const d = new Date(e.target.value + 'T00:00:00');
                const dateEl = $('#recapDateKeuCahaya');
                if (dateEl && !isNaN(d)) dateEl.textContent = formatDateID(d);
            });
        }

        // Initial load for today
        const todayStr = new Date().toLocaleDateString('en-CA');
        fetchAndRenderRecap('all', todayStr);
        const dateElCahaya = $('#recapDateKeuCahaya');
        if (dateElCahaya) dateElCahaya.textContent = formatDateID(new Date());
    }

    function generateRealPDF(type) {
        if ((type === 'lab_alat1' || type === 'lab_alat2' || type === 'lab') && typeof window.openTempHumOfficialPdfModal === 'function') {
            window.openTempHumOfficialPdfModal({
                deviceId: type === 'lab_alat2' ? 'ALAT_2_NON_AC' : 'ALAT_1_AC',
                roomName: 'R. CEMARAN JAMUR',
                labName: 'MIKROBIOLOGI'
            });
            return;
        }

        if (!window.jspdf) {
            alert('Library PDF belum termuat sempurna. Silakan tunggu sebentar.');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        const now = new Date();
        let chosenDateStr = now.toLocaleDateString('en-CA');
        if (type === 'lab_alat1' && $('#pdfDateLab')?.value) chosenDateStr = $('#pdfDateLab').value;
        else if (type === 'lab_alat2' && $('#pdfDateLab_2')?.value) chosenDateStr = $('#pdfDateLab_2').value;
        else if (type === 'lab' && $('#pdfDateLab')?.value) chosenDateStr = $('#pdfDateLab').value;
        else if (type === 'keuangan' && $('#pdfDateKeu')?.value) chosenDateStr = $('#pdfDateKeu').value;
        else if ((type === 'keuangan_cahaya' || type === 'keuangan_all') && $('#pdfDateKeuCahaya')?.value) chosenDateStr = $('#pdfDateKeuCahaya').value;

        const chosenDateObj = new Date(chosenDateStr + 'T00:00:00');
        const dateStr = !isNaN(chosenDateObj) ? formatDateID(chosenDateObj) : formatDateID(now);

        // Header
        doc.setFontSize(16);
        doc.setTextColor(21, 101, 192);
        doc.text("LAPORAN MONITORING IoT HARIAN", 105, 20, { align: "center" });
        doc.setFontSize(12);
        doc.setTextColor(71, 85, 105);
        doc.text("Command Center SPBE - BBPOM di Bandung", 105, 28, { align: "center" });
        doc.setFontSize(10);
        doc.text(`Tanggal Rekap: ${dateStr} | Waktu Generate: ${now.toLocaleTimeString('id-ID')}`, 105, 34, { align: "center" });

        doc.setLineWidth(0.5);
        doc.setDrawColor(21, 101, 192);
        doc.line(20, 38, 190, 38);

        if (type === 'keuangan') {
            doc.setFontSize(14);
            doc.setTextColor(21, 101, 192);
            doc.text("Alat 1 — Ruang Keuangan (Kode Alat: ALAT_1 / ID JSON: KEUANGAN)", 20, 50);

            const keuRecap = iotData._lastRecapKeu || {};
            const onM = Math.round(keuRecap.acOnMinutes !== undefined ? keuRecap.acOnMinutes : (iotData.acOnMinutes || 0));
            const offM = Math.round(keuRecap.acOffMinutes !== undefined ? keuRecap.acOffMinutes : (iotData.acOffMinutes || 0));
            const avgTempKeu = keuRecap.avgTemp !== undefined ? keuRecap.avgTemp : '--';

            doc.autoTable({
                startY: 55,
                head: [['Parameter', 'Status / Nilai Rekap']],
                body: [
                    ['Kode Alat & Lokasi Node', 'ALAT_1 (ID JSON: KEUANGAN) — R. Keuangan'],
                    ['Status AC Saat Ini', iotData.acStatus === 'ON' ? 'NYALA' : 'MATI'],
                    ['Deteksi Kehadiran', iotData.keuanganKehadiran > 0 ? `Ada Orang (${iotData.keuanganKehadiran} people)` : 'Kosong'],
                    ['Suhu Rata-rata Ruangan', `${avgTempKeu} °C`],
                    ['Total Durasi AC Menyala', `${Math.floor(onM / 60)}j ${onM % 60}m`],
                    ['Total Durasi AC Mati (Penghematan)', `${Math.floor(offM / 60)}j ${offM % 60}m`]
                ],
                theme: 'striped',
                headStyles: { fillColor: [21, 101, 192] }
            });
        } else if (type === 'keuangan_cahaya') {
            const ls = lightServoStore['ALAT_2_CAHAYA'] || { lampStatus: 'ON', servoAngle: 90, mode: 'auto', lumens: 380, minLumens: 250, maxLumens: 500, servoActionsToday: 4, lightOffCountToday: 2 };
            const validLumens = (iotData.lumensKeu || [380]).filter(v => v > 0);
            const avgL = validLumens.length ? Math.round(validLumens.reduce((a, b) => a + b, 0) / validLumens.length) : ls.lumens;
            const minL = validLumens.length ? Math.min(...validLumens) : ls.lumens;
            const maxL = validLumens.length ? Math.max(...validLumens) : ls.lumens;

            doc.setFontSize(13);
            doc.setTextColor(21, 101, 192);
            doc.text("Alat 2 — Ruang Keuangan (Sensor Intensitas Cahaya & Saklar Servo)", 20, 48);
            doc.setFontSize(10);
            doc.setTextColor(71, 85, 105);
            doc.text("Kode Alat: ALAT_2_CAHAYA (ID JSON: KEUANGAN_CAHAYA) | Node: Saklar Lampu R. Keuangan", 20, 54);

            doc.autoTable({
                startY: 59,
                head: [['Parameter Sensor & Aktuator', 'Rata-rata / Status', 'Minimum', 'Maksimum']],
                body: [
                    ['Intensitas Cahaya (Lumens)', `${avgL} lm`, `${minL} lm`, `${maxL} lm`],
                    ['Batas Otomasi Cahaya', `Gelap < ${ls.minLumens} lm (ON)`, `Ideal ${ls.minLumens}-${ls.maxLumens} lm`, `Terang > ${ls.maxLumens} lm (OFF)`],
                    ['Status Saklar Lampu (Motor Servo)', ls.lampStatus === 'ON' ? `LAMPU ON (Servo ${ls.servoAngle}°)` : `SEBAGIAN OFF (Servo ${ls.servoAngle}°)`, `Mode: ${ls.mode === 'auto' ? 'Otomatis Sensor' : 'Manual Switch Web'}`, `Aksi Servo: ${ls.servoActionsToday || 4}x`]
                ],
                theme: 'striped',
                headStyles: { fillColor: [21, 101, 192] }
            });

            const yAfterC = doc.lastAutoTable.finalY + 8;
            doc.setFontSize(10);
            doc.setTextColor(22, 163, 74);
            doc.text(`Total Sampel: ${validLumens.length} data | Penghematan Cahaya Berlebih (Servo OFF Otomatis): ${ls.lightOffCountToday || 2} kali.`, 20, yAfterC);
        } else if (type === 'keuangan_all') {
            doc.setFontSize(13);
            doc.setTextColor(21, 101, 192);
            doc.text("Alat 1 — Ruang Keuangan (Otomasi AC) | Kode Alat: ALAT_1 (ID JSON: KEUANGAN)", 20, 48);

            const keuRecap = iotData._lastRecapKeu || {};
            const onM = Math.round(keuRecap.acOnMinutes !== undefined ? keuRecap.acOnMinutes : (iotData.acOnMinutes || 0));
            const offM = Math.round(keuRecap.acOffMinutes !== undefined ? keuRecap.acOffMinutes : (iotData.acOffMinutes || 0));
            const avgTempKeu = keuRecap.avgTemp !== undefined ? keuRecap.avgTemp : '--';

            doc.autoTable({
                startY: 53,
                head: [['Parameter', 'Status / Nilai Rekap']],
                body: [
                    ['Status AC & Kehadiran', `${iotData.acStatus === 'ON' ? 'NYALA' : 'MATI'} (${iotData.keuanganKehadiran || 0} people)`],
                    ['Suhu Rata-rata Ruangan', `${avgTempKeu} °C`],
                    ['Durasi AC Menyala / Mati', `Nyala: ${Math.floor(onM / 60)}j ${onM % 60}m | Mati: ${Math.floor(offM / 60)}j ${offM % 60}m`]
                ],
                theme: 'striped',
                headStyles: { fillColor: [21, 101, 192] }
            });

            const yAlat2Keu = doc.lastAutoTable.finalY + 14;
            const ls = lightServoStore['ALAT_2_CAHAYA'] || { lampStatus: 'ON', servoAngle: 90, mode: 'auto', lumens: 380, minLumens: 250, maxLumens: 500, servoActionsToday: 4, lightOffCountToday: 2 };
            const validLumens = (iotData.lumensKeu || [380]).filter(v => v > 0);
            const avgL = validLumens.length ? Math.round(validLumens.reduce((a, b) => a + b, 0) / validLumens.length) : ls.lumens;
            const minL = validLumens.length ? Math.min(...validLumens) : ls.lumens;
            const maxL = validLumens.length ? Math.max(...validLumens) : ls.lumens;

            doc.setFontSize(13);
            doc.setTextColor(21, 101, 192);
            doc.text("Alat 2 — Ruang Keuangan (Sensor Cahaya & Servo) | Kode Alat: ALAT_2_CAHAYA", 20, yAlat2Keu);

            doc.autoTable({
                startY: yAlat2Keu + 5,
                head: [['Parameter Sensor & Aktuator', 'Rata-rata / Status', 'Minimum', 'Maksimum']],
                body: [
                    ['Intensitas Cahaya (Lumens)', `${avgL} lm`, `${minL} lm`, `${maxL} lm`],
                    ['Status Saklar Lampu (Motor Servo)', ls.lampStatus === 'ON' ? `LAMPU ON (Servo ${ls.servoAngle}°)` : `SEBAGIAN OFF (Servo ${ls.servoAngle}°)`, `Mode: ${ls.mode === 'auto' ? 'Otomatis Sensor' : 'Manual Switch Web'}`, `Aksi Servo: ${ls.servoActionsToday || 4}x`]
                ],
                theme: 'striped',
                headStyles: { fillColor: [21, 101, 192] }
            });
        } else if (type === 'lab_alat1') {
            const recap = iotData._lastRecap;
            if (!recap) return alert('Data rekap Alat 1 belum tersedia.');

            doc.setFontSize(13);
            doc.setTextColor(21, 101, 192);
            doc.text("Alat 1 — Ruang Lab Reagen (AC) | Kode Alat: ALAT_1_AC (ID JSON: LAB_AC)", 20, 50);

            doc.autoTable({
                startY: 55,
                head: [['Parameter Sensor', 'Rata-rata', 'Minimum', 'Maksimum']],
                body: [
                    ['Suhu Ruangan', `${recap.avgSuhu} °C`, `${recap.minSuhu} °C`, `${recap.maxSuhu} °C`],
                    ['Kelembapan', `${recap.avgHum}%`, `${recap.minHum}%`, `${recap.maxHum}%`],
                    ['Kualitas Udara (MQ)', `${recap.avgMQ} PPM`, `${recap.minMQ} PPM`, `${recap.maxMQ} PPM`]
                ],
                theme: 'striped',
                headStyles: { fillColor: [21, 101, 192] }
            });

            const yAfter = doc.lastAutoTable.finalY + 8;
            doc.setFontSize(10);
            doc.setTextColor(recap.alerts > 0 ? 198 : 22, recap.alerts > 0 ? 40 : 163, recap.alerts > 0 ? 40 : 74);
            doc.text(`Total Sampel: ${recap.count} data | Status Alert Suhu Alat 1: ${recap.alerts} kali melampaui batas aman.`, 20, yAfter);
        } else if (type === 'lab_alat2') {
            const recap2 = iotData._lastRecap2;
            if (!recap2) return alert('Data rekap Alat 2 belum tersedia.');

            doc.setFontSize(13);
            doc.setTextColor(21, 101, 192);
            doc.text("Alat 2 — Ruang Lab Reagen (Non-AC) | Kode Alat: ALAT_2_NON_AC (ID JSON: LAB_NON_AC)", 20, 50);

            doc.autoTable({
                startY: 55,
                head: [['Parameter Sensor', 'Rata-rata', 'Minimum', 'Maksimum']],
                body: [
                    ['Suhu Ruangan', `${recap2.avgSuhu} °C`, `${recap2.minSuhu} °C`, `${recap2.maxSuhu} °C`],
                    ['Kelembapan', `${recap2.avgHum}%`, `${recap2.minHum}%`, `${recap2.maxHum}%`],
                    ['Kualitas Udara (MQ)', `${recap2.avgMQ} PPM`, `${recap2.minMQ} PPM`, `${recap2.maxMQ} PPM`]
                ],
                theme: 'striped',
                headStyles: { fillColor: [21, 101, 192] }
            });

            const yAfter2 = doc.lastAutoTable.finalY + 8;
            doc.setFontSize(10);
            doc.setTextColor(recap2.alerts > 0 ? 198 : 22, recap2.alerts > 0 ? 40 : 163, recap2.alerts > 0 ? 40 : 74);
            doc.text(`Total Sampel: ${recap2.count} data | Status Alert Suhu Alat 2: ${recap2.alerts} kali melampaui batas aman.`, 20, yAfter2);
        } else if (type === 'lab') {
            const recap = iotData._lastRecap;
            const recap2 = iotData._lastRecap2;
            if (!recap) {
                alert('Data rekap lab belum tersedia.');
                return;
            }

            // === Alat 1 (AC) ===
            doc.setFontSize(13);
            doc.setTextColor(21, 101, 192);
            doc.text("Alat 1 — Ruang Lab Reagen (AC) | Kode Alat: ALAT_1_AC (ID: LAB_AC)", 20, 50);

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

            let yAfterTable1 = doc.lastAutoTable.finalY + 8;
            doc.setFontSize(10);
            doc.setTextColor(recap.alerts > 0 ? 198 : 22, recap.alerts > 0 ? 40 : 163, recap.alerts > 0 ? 40 : 74);
            doc.text(`Status Alert Suhu Alat 1: ${recap.alerts} kali melampaui batas aman.`, 20, yAfterTable1);

            // === Alat 2 (Non-AC) ===
            if (recap2) {
                let yAlat2 = yAfterTable1 + 15;
                doc.setFontSize(13);
                doc.setTextColor(21, 101, 192);
                doc.text("Alat 2 — Ruang Lab Reagen (Non-AC) | Kode Alat: ALAT_2_NON_AC (ID: LAB_NON_AC)", 20, yAlat2);

                doc.autoTable({
                    startY: yAlat2 + 5,
                    head: [['Parameter', 'Rata-rata', 'Min', 'Max']],
                    body: [
                        ['Suhu', `${recap2.avgSuhu} °C`, `${recap2.minSuhu} °C`, `${recap2.maxSuhu} °C`],
                        ['Kelembapan', `${recap2.avgHum}%`, `${recap2.minHum}%`, `${recap2.maxHum}%`],
                        ['Kualitas Udara (MQ)', `${recap2.avgMQ} PPM`, `${recap2.minMQ} PPM`, `${recap2.maxMQ} PPM`]
                    ],
                    theme: 'striped',
                    headStyles: { fillColor: [21, 101, 192] }
                });

                let yAfterTable2 = doc.lastAutoTable.finalY + 8;
                doc.setFontSize(10);
                doc.setTextColor(recap2.alerts > 0 ? 198 : 22, recap2.alerts > 0 ? 40 : 163, recap2.alerts > 0 ? 40 : 74);
                doc.text(`Status Alert Suhu Alat 2: ${recap2.alerts} kali melampaui batas aman.`, 20, yAfterTable2);
            }
        }

        doc.setFontSize(9);
        doc.setTextColor(148, 163, 184);
        doc.text("Dokumen ini digenerate otomatis oleh sistem DigitLab BDG - BBPOM di Bandung.", 105, 280, { align: "center" });

        const blob = doc.output('blob');
        const url = URL.createObjectURL(blob);
        window.open(url, '_blank');
    }

    // ============================================================
    // CSV EXPORT
    // ============================================================

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
        link.setAttribute('download', `IoT_Log_${new Date().toISOString().slice(0, 10)}.csv`);
        link.style.visibility = 'hidden';

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }

    // ============================================================
    // TIMESTAMP
    // ============================================================

    function updateTimestamp() {
        const t = $('#iotTimestamp');
        if (t) {
            const date = new Date();
            t.textContent = 'Terakhir update: ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
    }

    // ============================================================
    // TAB MANAGEMENT
    // ============================================================

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
                    resizeIoTCharts();
                }
            });
        });
    }

    // ============================================================
    // WINDOW RESIZE
    // ============================================================

    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            resizeIoTCharts();
        }, 250);
    });

})();
