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
    // ============================================================

    let db = {};
    const API_BASE = '/api';

    document.addEventListener('DOMContentLoaded', async () => {
        initHeader();
        initNavigation();
        initDownloadButtons();
        initPDFButton();

        try {
            const res = await fetch(`${API_BASE}/data`);
            db = await res.json();
            
            updateEvaluasiUI();
            updateKendalaRekomendasiUI();
            
            initCircularProgress();
            initDonutChart();
            initGanttChart();
            initDataTable();
            initBmnTable();
            initIoTCharts();
            initGauges();
            
            renderTopografi();
            renderPemeliharaan();
            renderPerencanaan();
            renderPengembangan();
        } catch (error) {
            console.error("Gagal fetch data API", error);
            // Fallback initialization
            updateSisaHari();
            initCircularProgress();
            initDonutChart();
            initGanttChart();
            initDataTable();
            initBmnTable();
            initIoTCharts();
            initGauges();
            
            renderTopografi();
            renderPemeliharaan();
            renderPerencanaan();
            renderPengembangan();
        }
    });

    function updateEvaluasiUI() {
        if (!db.evaluasi) return;
        const e = db.evaluasi;
        const sh = $('#sisaHariValue');
        if (sh) sh.textContent = e.sisaHari;
        const rc = $('#rataCapaianValue');
        if (rc) rc.innerHTML = `${e.rataCapaian}<small>%</small>`;
        const pb = $('#miniProgressBar');
        if (pb) pb.style.width = `${e.rataCapaian}%`;

        const cat = e.kategori;
        if(cat) {
            const rings = [
                { id: '#progressKebijakan', val: cat.kebijakan },
                { id: '#progressTataKelola', val: cat.tataKelola },
                { id: '#progressLayanan', val: cat.layanan },
                { id: '#progressManajemen', val: cat.manajemen }
            ];
            rings.forEach(r => {
                const el = $(`${r.id} .circular-progress`);
                const txt = $(`${r.id} .progress-text`);
                if(el) el.setAttribute('data-target', r.val);
                if(txt) txt.textContent = r.val + '%';
            });
        }
    }

    function updateKendalaRekomendasiUI() {
        if (db.kendala && Array.isArray(db.kendala)) {
            const ul = $('.kendala-list');
            if (ul) ul.innerHTML = db.kendala.map(k => `<li><span class="list-bullet red"></span>${k}</li>`).join('');
        }
        if (db.rekomendasi && Array.isArray(db.rekomendasi)) {
            const ul = $('.rekomendasi-list');
            if (ul) ul.innerHTML = db.rekomendasi.map(r => `<li><span class="list-bullet green"></span>${r}</li>`).join('');
        }
    }

    // ============================================================
    // HEADER - Tanggal & Waktu
    // ============================================================

    function initHeader() {
        const headerDate = $('#headerDate');
        if (headerDate) {
            const updateDate = () => {
                headerDate.textContent = formatDateID(new Date());
            };
            updateDate();
            // Update setiap menit
            setInterval(updateDate, 60000);
        }
    }

    /**
     * Hitung sisa hari menuju akhir tahun anggaran
     */
    function updateSisaHari() {
        const now = new Date();
        const endOfYear = new Date(now.getFullYear(), 11, 31); // 31 Desember
        const diffTime = endOfYear - now;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const el = $('#sisaHariValue');
        if (el) {
            el.textContent = diffDays;
        }
    }

    // ============================================================
    // SIDEBAR NAVIGATION
    // ============================================================

    function initNavigation() {
        const menuItems = $$('.menu-item');
        const pages = $$('.page');
        const hamburger = $('#hamburger');
        const sidebar = $('#sidebar');
        const overlay = $('#sidebarOverlay');
        const pageTitle = $('#pageTitle');
        const pageSubtitle = $('#pageSubtitle');

        // Judul untuk setiap halaman
        const pageTitles = {
            'page-evaluasi': {
                title: 'Evaluasi SPBE',
                subtitle: 'Dashboard Sistem Pemerintahan Berbasis Elektronik'
            },
            'page-aset': {
                title: 'Aset & Infrastruktur TI',
                subtitle: 'Manajemen Barang Milik Negara & Infrastruktur'
            },
            'page-iot': {
                title: 'Monitoring IoT',
                subtitle: 'Pemantauan Sensor Real-Time ESP32'
            },
            'page-topografi': {
                title: 'Topografi Jaringan',
                subtitle: 'Peta Arsitektur Jaringan LAN & WAN BBPOM'
            },
            'page-pemeliharaan': {
                title: 'Pemeliharaan TIK',
                subtitle: 'Jadwal & Status Pemeliharaan Infrastruktur'
            },
            'page-perencanaan': {
                title: 'Perencanaan TIK',
                subtitle: 'Rencana Anggaran dan Pengadaan'
            },
            'page-pengembangan': {
                title: 'Pengembangan TIK',
                subtitle: 'Monitoring Progres Aplikasi'
            }
        };

        // Handler klik menu
        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                const targetPage = item.getAttribute('data-page');
                
                // Jika tidak ada data-page (contoh: link eksternal atau admin.html), biarkan navigasi default berjalan
                if (!targetPage) return; 

                e.preventDefault();

                // Update active menu
                menuItems.forEach(m => m.classList.remove('active'));
                item.classList.add('active');

                // Switch halaman dengan animasi
                pages.forEach(p => {
                    p.classList.remove('active');
                });

                const target = $(`#${targetPage}`);
                if (target) {
                    // Small delay untuk animasi keluar-masuk
                    setTimeout(() => {
                        target.classList.add('active');
                    }, 50);
                }

                // Update header title
                if (pageTitles[targetPage]) {
                    pageTitle.textContent = pageTitles[targetPage].title;
                    pageSubtitle.textContent = pageTitles[targetPage].subtitle;
                }

                // Tutup sidebar di mobile
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }

                // Re-initialize charts ketika halaman IoT dibuka
                if (targetPage === 'page-iot') {
                    resizeIoTCharts();
                }
                // Re-draw donut chart saat halaman aset dibuka
                if (targetPage === 'page-aset') {
                    setTimeout(() => drawDonutChart(), 100);
                }
            });
        });

        // Hamburger toggle
        if (hamburger) {
            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            });
        }

        // Overlay click = close sidebar
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        function closeSidebar() {
            hamburger.classList.remove('active');
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }
    }

    // ============================================================
    // SVG CIRCULAR PROGRESS BARS
    // ============================================================

    function initCircularProgress() {
        const progressRings = $$('.progress-ring');
        const circumference = 2 * Math.PI * 52; // r=52

        // Observer untuk animasi saat visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateRing(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        progressRings.forEach(ring => {
            // Set initial state
            ring.style.strokeDasharray = circumference;
            ring.style.strokeDashoffset = circumference;
            observer.observe(ring);
        });

        function animateRing(ring) {
            const target = parseInt(ring.getAttribute('data-target'));
            const offset = circumference - (target / 100) * circumference;

            // Delay sedikit untuk efek animasi bertahap
            setTimeout(() => {
                ring.style.strokeDashoffset = offset;
            }, 200);
        }

        // Langsung animasikan yang sudah terlihat (page 1 aktif)
        progressRings.forEach(ring => {
            const target = parseInt(ring.getAttribute('data-target'));
            const offset = circumference - (target / 100) * circumference;
            setTimeout(() => {
                ring.style.strokeDashoffset = offset;
            }, 500);
        });
    }

    // ============================================================
    // DONUT CHART (Canvas) - Status BMN
    // ============================================================

    let donutDrawn = false;

    function initDonutChart() {
        // Donut chart ada di page 2 (hidden), draw saat pertama kali dibuka
        // Tapi kita juga bisa pre-draw
        setTimeout(() => drawDonutChart(), 800);
    }

    function drawDonutChart() {
        const canvas = $('#chartBMN');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const dpr = window.devicePixelRatio || 1;

        // High-DPI canvas
        const displayWidth = canvas.clientWidth || 240;
        const displayHeight = canvas.clientHeight || 240;
        canvas.width = displayWidth * dpr;
        canvas.height = displayHeight * dpr;
        ctx.scale(dpr, dpr);

        const centerX = displayWidth / 2;
        const centerY = displayHeight / 2;
        const radius = Math.min(centerX, centerY) - 20;
        const lineWidth = 30;

        // Hitung total dari data array BMN
        let sumBaik = 0, sumRingan = 0, sumBerat = 0;
        if (db.bmn && Array.isArray(db.bmn)) {
            db.bmn.forEach(item => {
                if (item.kondisi === 'Baik') sumBaik++;
                else if (item.kondisi === 'Rusak Ringan') sumRingan++;
                else if (item.kondisi === 'Rusak Berat') sumBerat++;
            });
        }
        
        // Fallback jika kosong agar chart tetap tergambar utuh
        let isDefault = false;
        if (sumBaik === 0 && sumRingan === 0 && sumBerat === 0) {
            sumBaik = 85; sumRingan = 10; sumBerat = 5;
            isDefault = true;
        }

        const data = [
            { label: 'Kondisi Baik', value: sumBaik, color: '#2E7D32' },
            { label: 'Rusak Ringan', value: sumRingan, color: '#E65100' },
            { label: 'Rusak Berat', value: sumBerat, color: '#C62828' }
        ];

        const total = data.reduce((s, d) => s + d.value, 0);
        let startAngle = -Math.PI / 2; // Mulai dari atas

        // Render Legend
        const legendEl = $('#legendBMN');
        if (legendEl) {
            legendEl.innerHTML = data.map(d => `
                <div class="legend-item">
                    <span class="legend-color" style="background: ${d.color};"></span>
                    <span>${d.label} — ${total > 0 ? Math.round((d.value/total)*100) : 0}% (${d.value})</span>
                </div>
            `).join('');
        }

        ctx.clearRect(0, 0, displayWidth, displayHeight);

        // Animasi menggambar donut
        let progress = 0;
        const animDuration = 60; // frames
        let frame = 0;

        function animate() {
            frame++;
            progress = Math.min(frame / animDuration, 1);
            // Easing
            const eased = 1 - Math.pow(1 - progress, 3);

            ctx.clearRect(0, 0, displayWidth, displayHeight);

            // Background ring
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
            ctx.strokeStyle = 'rgba(21, 101, 192, 0.08)';
            ctx.lineWidth = lineWidth;
            ctx.stroke();

            // Draw segments
            let currentAngle = -Math.PI / 2;
            data.forEach(segment => {
                const sliceAngle = (segment.value / total) * Math.PI * 2 * eased;

                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
                ctx.strokeStyle = segment.color;
                ctx.lineWidth = lineWidth;
                ctx.lineCap = 'butt';
                ctx.stroke();

                currentAngle += sliceAngle;
            });

            // Center text
            const percentageBaik = (sumBaik / total) * 100;
            ctx.fillStyle = '#1a2332';
            ctx.font = '700 1.5rem Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(`${Math.round(percentageBaik * eased)}%`, centerX, centerY - 6);

            ctx.fillStyle = '#475569';
            ctx.font = '500 0.65rem Inter, sans-serif';
            ctx.fillText('Kondisi Baik', centerX, centerY + 14);

            if (frame < animDuration) {
                requestAnimationFrame(animate);
            }
        }

        animate();
        donutDrawn = true;
    }

    // ============================================================
    // GANTT CHART - Jadwal Pemeliharaan Server
    // ============================================================

    function initGanttChart() {
        const headerEl = $('#ganttHeader');
        const bodyEl = $('#ganttBody');
        if (!headerEl || !bodyEl) return;

        // Data jadwal pemeliharaan dari backend API (fallback to local if missing)
        const scheduleData = db.jadwal && db.jadwal.length > 0 ? db.jadwal.map((j, i) => {
            const colors = [
                'linear-gradient(135deg, #1565C0, #0D47A1)',
                'linear-gradient(135deg, #2E7D32, #1B5E20)',
                'linear-gradient(135deg, #E65100, #BF360C)',
                'linear-gradient(135deg, #1565C0, #42A5F5)'
            ];
            return { ...j, color: colors[i % colors.length] };
        }) : [
            {
                label: 'Server Utama — Backup',
                startDay: 10,
                endDay: 12,
                color: 'linear-gradient(135deg, #1565C0, #0D47A1)'
            }
        ];

        const daysInMonth = 31;
        const startDisplay = 1;
        const endDisplay = 31;
        const totalDays = endDisplay - startDisplay + 1;

        // Render header (tanggal)
        headerEl.style.gridTemplateColumns = `repeat(${totalDays}, 1fr)`;
        for (let d = startDisplay; d <= endDisplay; d++) {
            const span = document.createElement('span');
            span.textContent = d;
            // Tandai hari ini
            const today = new Date();
            if (today.getMonth() === 7 && today.getDate() === d) { // Agustus = 7
                span.style.color = '#1565C0';
                span.style.fontWeight = '700';
            }
            headerEl.appendChild(span);
        }

        // Render rows
        scheduleData.forEach((task, idx) => {
            const row = document.createElement('div');
            row.className = 'gantt-row';

            const label = document.createElement('div');
            label.className = 'gantt-row-label';
            label.textContent = task.label;
            label.title = task.label;

            const track = document.createElement('div');
            track.className = 'gantt-row-track';

            const bar = document.createElement('div');
            bar.className = 'gantt-bar';
            bar.style.background = task.color;
            bar.style.animationDelay = `${idx * 0.15}s`;

            // Hitung posisi bar
            const leftPercent = ((task.startDay - startDisplay) / totalDays) * 100;
            const widthPercent = ((task.endDay - task.startDay + 1) / totalDays) * 100;
            bar.style.left = `${leftPercent}%`;
            bar.style.width = `${Math.max(widthPercent, 3)}%`; // minimum 3% width

            // Label di dalam bar
            if (task.endDay - task.startDay >= 1) {
                bar.textContent = `${task.startDay}-${task.endDay}`;
            } else {
                bar.textContent = `${task.startDay}`;
            }

            track.appendChild(bar);
            row.appendChild(label);
            row.appendChild(track);
            bodyEl.appendChild(row);
        });
    }

    // ============================================================
    // DATA TABLE - Matriks Akses Sistem Nasional
    // ============================================================

    const PEGAWAI_APPS = [
        "SRIKANDI", "SIASN", "SIMAKIN", "MENTARI", "ETUKIN", "MENTARI (2)", "SIIKA", "SISFO", 
        "DOSIER", "PENILAIAN KOMPETENSI", "PENGEMBANGAN KOMPETENSI", "SIPT PENGAWASAN PENGUJIAN", 
        "SIMETRIS", "SIROUM v3", "SAPAAPIP", "Pelaporan WBS, Benturan kepentingan, Gratifikasi", 
        "SISTEM PELAPORAN LAYANAN", "MONEV DJA", "SAKTI", "SPAN", "SPAN-EXT", "DIGIT", 
        "SIMASPATEN", "WEB GAJI", "EJAFUNG", "DIGIPAY", "MYINTRESS", "SPSE", "SIRUP", "E KATALOG v6"
    ];

    function initDataTable() {
        const thead = $('#tableMatriksHead');
        const tableBody = $('#tableBody');
        const searchInput = $('#searchMatriks');
        const tableInfo = $('#tableInfo');
        if (!tableBody || !thead) return;

        // Render Header Dinamis
        let thHtml = `<tr>
            <th style="min-width: 60px;">No</th>
            <th style="min-width: 250px;">Nama Pegawai</th>`;
        PEGAWAI_APPS.forEach(app => {
            thHtml += `<th style="min-width: 150px;">${app}</th>`;
        });
        thHtml += `</tr>`;
        thead.innerHTML = thHtml;

        // Data pegawai dari backend
        let employeeData = db.pegawai || [];
        
        // Pastikan fallback array 30 elemen ada untuk format lama
        employeeData = employeeData.map(emp => {
            if (!emp.apps) {
                const apps = Array(PEGAWAI_APPS.length).fill('');
                apps[0] = emp.srikandi ? 'User' : '';
                apps[1] = emp.siasn ? 'User' : '';
                apps[2] = emp.simpel ? 'User' : '';
                apps[3] = emp.sipnap ? 'User' : '';
                apps[4] = emp.eoffice ? 'User' : '';
                return { ...emp, apps };
            }
            return emp;
        });

        function renderTable(data) {
            tableBody.innerHTML = '';
            data.forEach((emp, idx) => {
                const tr = document.createElement('tr');
                let trHtml = `
                    <td>${idx + 1}</td>
                    <td style="color: var(--text-primary); font-weight: 500;">${emp.nama || '-'}</td>
                `;
                
                PEGAWAI_APPS.forEach((app, appIdx) => {
                    const textVal = (emp.apps && emp.apps[appIdx]) ? emp.apps[appIdx] : '—';
                    trHtml += `<td style="text-align: center; font-weight: ${textVal !== '—' ? '600' : 'normal'}; color: ${textVal !== '—' ? '#2E7D32' : 'inherit'}">${textVal}</td>`;
                });
                
                tr.innerHTML = trHtml;
                tableBody.appendChild(tr);
            });

            if (tableInfo) {
                tableInfo.textContent = `Menampilkan ${data.length} dari ${employeeData.length} pegawai`;
            }
        }

        // Initial render
        renderTable(employeeData);

        // Fitur pencarian real-time
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                if (!query) {
                    renderTable(employeeData);
                    return;
                }
                const filtered = employeeData.filter(emp =>
                    (emp.nama || '').toLowerCase().includes(query)
                );
                renderTable(filtered);
            });
        }
    }

    // ============================================================
    // DATA TABLE - Daftar Inventaris Aset BMN
    // ============================================================

    function initBmnTable() {
        const tableBody = $('#tableBodyBmn');
        const searchInput = $('#searchBmn');
        const tableInfo = $('#tableInfoBmn');
        if (!tableBody) return;

        // Ambil data BMN dari DB, atau array kosong jika tidak ada
        const bmnData = (db.bmn && Array.isArray(db.bmn)) ? db.bmn : [];

        function renderBmn(data) {
            tableBody.innerHTML = '';
            if(data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="6" style="text-align:center">Tidak ada data aset BMN</td></tr>`;
                if(tableInfo) tableInfo.textContent = 'Menampilkan 0 data';
                return;
            }

            data.forEach((item, idx) => {
                let badgeClass = 'bg-green-100 text-green-800';
                if(item.kondisi === 'Rusak Ringan') badgeClass = 'bg-yellow-100 text-yellow-800';
                if(item.kondisi === 'Rusak Berat') badgeClass = 'bg-red-100 text-red-800';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${idx + 1}</td>
                    <td>${item.kategori || '-'}</td>
                    <td>${item.kode || '-'}</td>
                    <td style="color: var(--text-primary); font-weight: 500;">${item.nama || '-'}</td>
                    <td>${item.merk || '-'}</td>
                    <td>${item.lokasi || '-'}</td>
                    <td>
                        <span style="display:inline-block; padding:2px 8px; border-radius:12px; font-size:0.75rem; font-weight:600; 
                        ${item.kondisi === 'Baik' ? 'background:#dcfce7;color:#166534;' : 
                         (item.kondisi === 'Rusak Ringan' ? 'background:#fef9c3;color:#854d0e;' : 'background:#fee2e2;color:#991b1b;')}">
                            ${item.kondisi || 'Baik'}
                        </span>
                    </td>
                    <td>${item.status_bmn || '-'}</td>
                    <td>${item.intra_extra || '-'}</td>
                    <td>${item.tgl_peroleh || '-'}</td>
                    <td>${item.nilai_peroleh ? 'Rp ' + parseInt(item.nilai_peroleh).toLocaleString('id-ID') : '-'}</td>
                    <td>${item.nilai_susut ? 'Rp ' + parseInt(item.nilai_susut).toLocaleString('id-ID') : '-'}</td>
                `;
                tableBody.appendChild(tr);
            });

            if (tableInfo) {
                tableInfo.textContent = `Menampilkan ${data.length} dari ${bmnData.length} total aset`;
            }
        }

        renderBmn(bmnData);

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                if (!query) {
                    renderBmn(bmnData);
                    return;
                }
                const filtered = bmnData.filter(item =>
                    (item.nama || '').toLowerCase().includes(query) ||
                    (item.merk || '').toLowerCase().includes(query) ||
                    (item.lokasi || '').toLowerCase().includes(query) ||
                    (item.kategori || '').toLowerCase().includes(query)
                );
                renderBmn(filtered);
            });
        }
    }


    // ============================================================
    // IoT LINE CHARTS (Canvas) - Suhu & Kelembapan
    // ============================================================

    const iotData = {
        suhu: Array(20).fill(null).map(() => randomInRange(23.5, 25.5)),
        kelembapan: Array(20).fill(null).map(() => randomInRange(58, 66)),
        mq: Array(20).fill(0), // Data MQ-2 (0-4095)

        voltage: 220,
        ampere: 4.5,
        watt: 990,
        
        kehadiran: 3, // 0 to 5 people
        acStatus: true,
        keuanganTemp: 28.5,
        keuanganHum: 63,
        acOnMinutes: 0,
        acOffMinutes: 0,
        _lastRecap: null
    };

    function initIoTCharts() {
        drawLineChart('chartSuhu', iotData.suhu, '#C62828', 'rgba(198, 40, 40, 0.08)', 20, 30);
        drawLineChart('chartKelembapan', iotData.kelembapan, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80);
        drawLineChart('chartMQ', iotData.mq, '#a855f7', 'rgba(168, 85, 247, 0.08)', 300, 2500);

        // Mulai pembaruan real-time (update setiap 3 detik)
        startRealtimeSimulation();
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
        setTimeout(() => {
            drawLineChart('chartSuhu', iotData.suhu, '#C62828', 'rgba(198, 40, 40, 0.08)', 20, 30);
            drawLineChart('chartKelembapan', iotData.kelembapan, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80);
            drawLineChart('chartMQ', iotData.mq, '#a855f7', 'rgba(168, 85, 247, 0.08)', 300, 2500);
        }, 100);
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
                const serverData = await res.json();
                if (Array.isArray(serverData) && serverData.length > 0) {
                    const mqValues = serverData.slice(0, 20).reverse().map(d => d.mq || 0);
                    const suhuValues = serverData.slice(0, 20).reverse().map(d => d.suhu || 0);
                    const humValues = serverData.slice(0, 20).reverse().map(d => d.hum || 0);
                    
                    while (mqValues.length < 20) mqValues.unshift(0);
                    while (suhuValues.length < 20) suhuValues.unshift(0);
                    while (humValues.length < 20) humValues.unshift(0);
                    
                    iotData.mq = mqValues;
                    iotData.suhu = suhuValues;
                    iotData.kelembapan = humValues;

                    const currentData = serverData[0];
                    iotData.acStatus = currentData.acStatus ? 'ON' : 'OFF';
                    iotData.keuanganKehadiran = currentData.kehadiran || 0;
                }
            }
        } catch (err) {
            console.error("Gagal fetch sensorData", err);
        }

        iotData.voltage = parseFloat((220 + randomInRange(-5, 5)).toFixed(0));
        iotData.ampere = parseFloat((4.5 + randomInRange(-0.8, 0.8)).toFixed(1));
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
    }

    function updateIoTDisplay() {
        const currentSuhu = iotData.suhu[iotData.suhu.length - 1];
        const prevSuhu = iotData.suhu[iotData.suhu.length - 2] || currentSuhu;
        const currentKelembapan = iotData.kelembapan[iotData.kelembapan.length - 1];
        const prevKelembapan = iotData.kelembapan[iotData.kelembapan.length - 2] || currentKelembapan;
        const currentMq = iotData.mq[iotData.mq.length - 1];

        // Update nilai besar
        const suhuEl = $('#suhuValue');
        const kelembapanEl = $('#kelembapanValue');
        const mqEl = $('#mqValue');

        if (suhuEl) suhuEl.textContent = currentSuhu.toFixed(1);
        if (kelembapanEl) kelembapanEl.textContent = currentKelembapan.toFixed(0);
        if (mqEl) mqEl.textContent = currentMq > 0 ? currentMq.toFixed(0) : '--';

        // Update trend indicators
        const suhuTrend = $('#suhuTrend');
        const kelembapanTrend = $('#kelembapanTrend');
        const suhuDiff = currentSuhu - prevSuhu;
        const kelembapanDiff = currentKelembapan - prevKelembapan;

        if (suhuTrend) {
            if (suhuDiff > 0) {
                suhuTrend.textContent = `▲ ${suhuDiff.toFixed(1)}°C`;
                suhuTrend.className = 'iot-trend up';
            } else if (suhuDiff < 0) {
                suhuTrend.textContent = `▼ ${Math.abs(suhuDiff).toFixed(1)}°C`;
                suhuTrend.className = 'iot-trend down';
            } else {
                suhuTrend.textContent = '— Stabil';
                suhuTrend.className = 'iot-trend stable';
            }
        }

        if (kelembapanTrend) {
            if (kelembapanDiff > 0) {
                kelembapanTrend.textContent = `▲ ${kelembapanDiff.toFixed(0)}%`;
                kelembapanTrend.className = 'iot-trend up';
            } else if (kelembapanDiff < 0) {
                kelembapanTrend.textContent = `▼ ${Math.abs(kelembapanDiff).toFixed(0)}%`;
                kelembapanTrend.className = 'iot-trend down';
            } else {
                kelembapanTrend.textContent = '— Stabil';
                kelembapanTrend.className = 'iot-trend stable';
            }
        }

        // Status MQ-2
        const mqStatus = $('#mqStatus');
        if (mqStatus) {
            if (currentMq > 1500) {
                mqStatus.textContent = 'AWAS: ASAP!';
                mqStatus.className = 'iot-status critical';
            } else if (currentMq > 800) {
                mqStatus.textContent = 'WASPADA';
                mqStatus.className = 'iot-status warning';
            } else if (currentMq > 0) {
                mqStatus.textContent = 'Aman';
                mqStatus.className = 'iot-status normal';
            } else {
                mqStatus.textContent = 'Menunggu Data';
                mqStatus.className = 'iot-status stable';
            }
        }

        // Status Otomasi AC — Ruang Keuangan (card baru)
        const acVisual = $('#acVisual');
        const acStatusText = $('#acStatusText');
        const kehadiranText = $('#kehadiranText');
        const keuanganTemp = $('#keuanganTemp');
        const keuanganHum = $('#keuanganHum');
        const acDuration = $('#acDuration');
        
        if (acVisual) {
            acVisual.className = `ac-visual ${iotData.acStatus ? 'on' : 'off'}`;
        }
        if (acStatusText) {
            acStatusText.textContent = iotData.acStatus ? 'ON' : 'OFF';
            acStatusText.className = `value ${iotData.acStatus ? 'on' : 'off'}`;
        }
        if (kehadiranText) {
            kehadiranText.textContent = `${iotData.kehadiran} people`;
        }
        if (keuanganTemp) {
            keuanganTemp.textContent = `${iotData.keuanganTemp.toFixed(1)}°C`;
        }
        if (keuanganHum) {
            keuanganHum.textContent = `${iotData.keuanganHum}%`;
        }
        if (acDuration) {
            const onM = Math.round(iotData.acOnMinutes || 0);
            const offM = Math.round(iotData.acOffMinutes || 0);
            acDuration.textContent = `Nyala: ${Math.floor(onM/60)}j ${onM%60}m | Mati: ${Math.floor(offM/60)}j ${offM%60}m`;
        }

        // Alert Suhu Lab
        updateAlertSuhu(currentSuhu);

        // Daily Recap
        updateDailyRecap();

        // Update timer
        const timer = $('#iotTimestamp');
        if (timer) {
            const now = new Date();
            timer.textContent = `Terakhir update: ${now.toLocaleTimeString('id-ID')}`;
        }

        // Redraw charts
        drawLineChart('chartSuhu', iotData.suhu, '#C62828', 'rgba(198, 40, 40, 0.08)', 20, 30);
        drawLineChart('chartKelembapan', iotData.kelembapan, '#1565C0', 'rgba(21, 101, 192, 0.08)', 30, 80);
        drawLineChart('chartMQ', iotData.mq, '#a855f7', 'rgba(168, 85, 247, 0.08)', 300, 2500);
        
        updateGauges();
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

    function updateDailyRecap() {
        // Throttle: update setiap 10 detik, bukan setiap 3 detik
        const now = Date.now();
        if (now - lastRecapUpdate < 10000) return;
        lastRecapUpdate = now;

        // Hitung dari data lokal (iotData) sebagai fallback
        const suhuArr = iotData.suhu.filter(v => v !== null);
        const humArr = iotData.kelembapan.filter(v => v !== null);
        const mqArr = iotData.mq.filter(v => v > 0);

        // Fetch todayAccumulator dari server
        fetch(`${API_BASE}/data?key=todayAccumulator`)
            .then(r => r.json())
            .then(acc => {
                if (acc && acc.count > 0) {
                    renderRecap({
                        avgSuhu: (acc.sumSuhu / acc.count).toFixed(1),
                        minSuhu: acc.minSuhu !== 99 ? acc.minSuhu.toFixed(1) : '—',
                        maxSuhu: acc.maxSuhu !== -99 ? acc.maxSuhu.toFixed(1) : '—',
                        avgHum: (acc.sumKelembapan / acc.count).toFixed(1),
                        minHum: acc.minKelembapan !== 99 ? acc.minKelembapan.toFixed(1) : '—',
                        maxHum: acc.maxKelembapan !== -99 ? acc.maxKelembapan.toFixed(1) : '—',
                        avgMQ: Math.round(acc.sumMQ / acc.count),
                        minMQ: acc.minMQ !== 9999 ? acc.minMQ : '—',
                        maxMQ: acc.maxMQ !== -9999 ? acc.maxMQ : '—',
                        samples: acc.count,
                        alerts: acc.alertCount || 0,
                        date: acc.date
                    });
                } else {
                    // Fallback: gunakan data lokal
                    const avg = arr => arr.length ? arr.reduce((a, b) => a + b, 0) / arr.length : 0;
                    let localAlerts = 0;
                    suhuArr.forEach(s => {
                        if (s < labSettings.suhuMin || s > labSettings.suhuMax) localAlerts++;
                    });

                    renderRecap({
                        avgSuhu: avg(suhuArr).toFixed(1),
                        minSuhu: suhuArr.length ? Math.min(...suhuArr).toFixed(1) : '—',
                        maxSuhu: suhuArr.length ? Math.max(...suhuArr).toFixed(1) : '—',
                        avgHum: avg(humArr).toFixed(1),
                        minHum: humArr.length ? Math.min(...humArr).toFixed(1) : '—',
                        maxHum: humArr.length ? Math.max(...humArr).toFixed(1) : '—',
                        avgMQ: mqArr.length ? Math.round(avg(mqArr)) : '—',
                        minMQ: mqArr.length ? Math.min(...mqArr) : '—',
                        maxMQ: mqArr.length ? Math.max(...mqArr) : '—',
                        samples: suhuArr.length,
                        alerts: localAlerts,
                        date: new Date().toISOString().split('T')[0]
                    });
                }
            })
            .catch(() => {
                // Fallback offline
                const avg = arr => arr.length ? arr.reduce((a, b) => a + b, 0) / arr.length : 0;
                let localAlerts = 0;
                suhuArr.forEach(s => {
                    if (s < labSettings.suhuMin || s > labSettings.suhuMax) localAlerts++;
                });

                renderRecap({
                    avgSuhu: avg(suhuArr).toFixed(1),
                    minSuhu: suhuArr.length ? Math.min(...suhuArr).toFixed(1) : '—',
                    maxSuhu: suhuArr.length ? Math.max(...suhuArr).toFixed(1) : '—',
                    avgHum: avg(humArr).toFixed(1),
                    minHum: humArr.length ? Math.min(...humArr).toFixed(1) : '—',
                    maxHum: humArr.length ? Math.max(...humArr).toFixed(1) : '—',
                    avgMQ: mqArr.length ? Math.round(avg(mqArr)) : '—',
                    minMQ: mqArr.length ? Math.min(...mqArr) : '—',
                    maxMQ: mqArr.length ? Math.max(...mqArr) : '—',
                    samples: suhuArr.length,
                    alerts: localAlerts,
                    date: new Date().toISOString().split('T')[0]
                });
            });
    }

    function renderRecap(data) {
        const dateEl = $('#recapDate');
        if (dateEl && data.date) {
            const d = new Date(data.date + 'T00:00:00');
            const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            dateEl.textContent = `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
        }

        const set = (id, val) => { const el = $(id); if (el) el.textContent = val; };
        set('#recapAvgSuhu', `${data.avgSuhu}°C`);
        set('#recapMinSuhu', `${data.minSuhu}°C`);
        set('#recapMaxSuhu', `${data.maxSuhu}°C`);
        set('#recapAvgHum', `${data.avgHum}%`);
        set('#recapMinHum', `${data.minHum}%`);
        set('#recapMaxHum', `${data.maxHum}%`);
        set('#recapAvgMQ', data.avgMQ);
        set('#recapMinMQ', data.minMQ);
        set('#recapMaxMQ', data.maxMQ);
        set('#recapSamples', data.samples);
        set('#recapAlerts', data.alerts);

        // AC duration
        const onM = Math.round(iotData.acOnMinutes || 0);
        const offM = Math.round(iotData.acOffMinutes || 0);
        set('#recapAcOn', `${Math.floor(onM/60)}j ${onM%60}m`);
        set('#recapAcOff', `${Math.floor(offM/60)}j ${offM%60}m`);

        // Simpan data untuk PDF
        iotData._lastRecap = data;
    }

    // ============================================================
    // PDF GENERATOR (Client-side, tanpa library)
    // ============================================================

    function initPDFButton() {
        const btn = $('#btnGeneratePDF');
        if (!btn) return;
        btn.addEventListener('click', generateRecapPDF);
    }

    function generateRecapPDF() {
        const recap = iotData._lastRecap;
        if (!recap) {
            alert('Data rekap belum tersedia. Tunggu beberapa detik.');
            return;
        }

        const now = new Date();
        const dateStr = formatDateID(now);
        const onM = Math.round(iotData.acOnMinutes || 0);
        const offM = Math.round(iotData.acOffMinutes || 0);

        // Buat konten HTML untuk dicetak
        const printContent = `
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Monitoring IoT — BBPOM Bandung</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #1a2332; padding: 40px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 32px; border-bottom: 3px solid #1565C0; padding-bottom: 20px; }
        .header h1 { font-size: 18px; color: #1565C0; margin-bottom: 4px; }
        .header h2 { font-size: 14px; font-weight: 400; color: #475569; }
        .header .date { font-size: 12px; color: #94a3b8; margin-top: 8px; }
        .section { margin-bottom: 28px; }
        .section h3 { font-size: 14px; color: #1565C0; margin-bottom: 12px; padding: 6px 12px; background: #f0f7ff; border-left: 4px solid #1565C0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 12px; }
        th { background: #f0f7ff; color: #1565C0; text-align: left; padding: 8px 12px; border: 1px solid #e2e8f0; font-weight: 600; }
        td { padding: 8px 12px; border: 1px solid #e2e8f0; }
        .status-row { display: flex; gap: 40px; margin-top: 8px; }
        .status-item { font-size: 12px; }
        .status-item .label { color: #64748b; }
        .status-item .val { font-weight: 700; color: #1a2332; }
        .alert-info { background: #fef2f2; border: 1px solid #fecaca; border-radius: 4px; padding: 8px 12px; font-size: 12px; color: #991b1b; margin-top: 8px; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 16px; }
        @media print { body { padding: 20px; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN MONITORING IoT HARIAN</h1>
        <h2>Command Center SPBE — BBPOM di Bandung</h2>
        <div class="date">${dateStr} | Digenerate: ${now.toLocaleTimeString('id-ID')}</div>
    </div>

    <div class="section">
        <h3>📍 Alat 1 — Ruang Keuangan (Otomasi AC)</h3>
        <table>
            <tr><th>Parameter</th><th>Nilai</th></tr>
            <tr><td>Status AC Saat Ini</td><td>${iotData.acStatus ? 'NYALA' : 'MATI'}</td></tr>
            <tr><td>Kehadiran</td><td>${iotData.kehadiran ? 'Ada Orang' : 'Kosong'}</td></tr>
            <tr><td>Durasi AC Nyala</td><td>${Math.floor(onM/60)}j ${onM%60}m</td></tr>
            <tr><td>Durasi AC Mati</td><td>${Math.floor(offM/60)}j ${offM%60}m</td></tr>
            <tr><td>Sensor</td><td>PIR / Radar Motion</td></tr>
            <tr><td>Aktuator</td><td>IR Blaster</td></tr>
        </table>
    </div>

    <div class="section">
        <h3>📍 Alat 2 — Ruang LAB (Monitoring Lingkungan)</h3>
        <table>
            <tr><th>Parameter</th><th>Rata-rata</th><th>Min</th><th>Max</th><th>Sampel</th></tr>
            <tr>
                <td>🌡️ Suhu</td>
                <td>${recap.avgSuhu}°C</td>
                <td>${recap.minSuhu}°C</td>
                <td>${recap.maxSuhu}°C</td>
                <td rowspan="3" style="text-align:center; font-weight:700;">${recap.samples}</td>
            </tr>
            <tr>
                <td>💧 Kelembapan</td>
                <td>${recap.avgHum}%</td>
                <td>${recap.minHum}%</td>
                <td>${recap.maxHum}%</td>
            </tr>
            <tr>
                <td>🌫️ Kualitas Udara</td>
                <td>${recap.avgMQ} PPM</td>
                <td>${recap.minMQ} PPM</td>
                <td>${recap.maxMQ} PPM</td>
            </tr>
        </table>
        <table>
            <tr><th>Ketentuan Suhu Lab</th><th>Nilai</th></tr>
            <tr><td>Batas Minimum</td><td>${labSettings.suhuMin}°C</td></tr>
            <tr><td>Batas Maksimum</td><td>${labSettings.suhuMax}°C</td></tr>
            <tr><td>Alert Suhu Hari Ini</td><td style="color: ${recap.alerts > 0 ? '#991b1b' : '#166534'}; font-weight: 700;">${recap.alerts} kali</td></tr>
        </table>
        ${recap.alerts > 0 ? '<div class="alert-info">⚠️ Terjadi ' + recap.alerts + ' kali pelanggaran batas suhu lab pada hari ini. Harap periksa pengaturan AC dan kondisi ruangan.</div>' : ''}
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh Command Center SPBE BBPOM Bandung</p>
        <p>© ${now.getFullYear()} BBPOM di Bandung — Seluruh data bersifat rahasia</p>
    </div>
</body>
</html>`;

        // Buka window baru dan print
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
        }, 500);
    }

    // ============================================================
    // DOWNLOAD HANDLERS
    // ============================================================

    function initDownloadButtons() {
        const btnPDF = $('#btnDownloadPDF');
        const btnCSV = $('#btnDownloadCSV');

        if (btnPDF) {
            btnPDF.addEventListener('click', () => {
                // Placeholder untuk integrasi PDF library (jsPDF, dll.)
                alert('📄 Fitur Download PDF\n\nLaporan harian pemakaian listrik akan diunduh dalam format PDF.\n\nFitur ini akan segera diintegrasikan dengan library jsPDF.');
            });
        }

        if (btnCSV) {
            btnCSV.addEventListener('click', () => {
                generateCSV();
            });
        }
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

    // ============================================================
    // NEW PAGES RENDER LOGIC
    // ============================================================

    function renderTopografi() {
        const img = $('#topografiImage');
        if (img) {
            img.src = db.topografi || 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&q=80&w=1200';
        }
    }

    function renderPemeliharaan() {
        const tbody = $('#tablePemeliharaanBody');
        if (!tbody) return;
        const data = db.pemeliharaan || [];
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="14" style="text-align:center;">Belum ada data pemeliharaan</td></tr>';
            return;
        }
        data.forEach((row, idx) => {
            const tr = document.createElement('tr');
            let html = `<td>${idx + 1}</td><td style="text-align: left; font-weight: 500;">${row.aktivitas || row.Aktivitas || '-'}</td>`;
            const months = ['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des'];
            months.forEach(m => {
                const val = (row[m] || row[m.toUpperCase()] || '').toString().toLowerCase();
                let statusClass = 'access-dash';
                let symbol = '—';
                if (val === '1' || val === 'v' || val === 'true' || val === 'ya' || val === 'selesai' || val === 'ok') {
                    statusClass = 'access-check';
                    symbol = '✓';
                } else if (val === 'proses' || val === 'berjalan') {
                    statusClass = 'access-dash';
                    symbol = 'O';
                }
                html += `<td class="${statusClass}">${symbol}</td>`;
            });
            tr.innerHTML = html;
            tbody.appendChild(tr);
        });
    }

    function renderPerencanaan() {
        const tbody = $('#tablePerencanaanBody');
        const info = $('#infoPerencanaanTotal');
        if (!tbody) return;
        const data = db.perencanaan || [];
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Belum ada data perencanaan</td></tr>';
            if (info) info.textContent = `Total Anggaran: Rp 0`;
            return;
        }
        
        let totalAll = 0;
        data.forEach((row, idx) => {
            const tr = document.createElement('tr');
            let volStr = (row.volume || row.Volume || '0').toString().replace(/[^\d]/g, '');
            let hargaStr = (row.harga || row.hargaSatuan || row.Harga || '0').toString().replace(/[^\d]/g, '');
            const vol = parseFloat(volStr) || 0;
            const harga = parseFloat(hargaStr) || 0;
            const total = vol * harga;
            totalAll += total;
            
            tr.innerHTML = `
                <td>${idx + 1}</td>
                <td style="text-align: left; font-weight: 500;">${row.uraian || row.Uraian || row.kebutuhan || '-'}</td>
                <td>${vol}</td>
                <td>${row.satuan || row.Satuan || '-'}</td>
                <td>Rp ${harga.toLocaleString('id-ID')}</td>
                <td style="font-weight: 600; color: #1565C0;">Rp ${total.toLocaleString('id-ID')}</td>
                <td>${row.keterangan || row.Keterangan || '-'}</td>
            `;
            tbody.appendChild(tr);
        });
        
        if (info) {
            info.textContent = `Total Anggaran: Rp ${totalAll.toLocaleString('id-ID')}`;
        }
    }

    function renderPengembangan() {
        const container = $('#gridPengembangan');
        if (!container) return;
        const data = db.pengembangan || [];
        container.innerHTML = '';
        if (data.length === 0) {
            container.innerHTML = '<p style="text-align:center; width:100%; color:#64748b;">Belum ada data progres pengembangan.</p>';
            return;
        }
        
        data.forEach(app => {
            const progress = parseFloat((app.progres || app.Progres || '0').toString().replace(/[^\d.]/g, '')) || 0;
            let color = '#3b82f6';
            if (progress >= 100) color = '#10b981';
            else if (progress > 50) color = '#6366f1';
            else if (progress < 20) color = '#f59e0b';
            
            const card = document.createElement('article');
            card.className = 'glass-card';
            card.style.padding = '20px';
            
            card.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 1.1rem; color: #1a2332;">${app.nama || app.Nama || 'Aplikasi'}</h3>
                        <p style="margin: 0; font-size: 0.85rem; color: #64748b;">${app.deskripsi || app.Deskripsi || app.vendor || 'Pengembangan Internal'}</p>
                    </div>
                    <span style="font-weight: 700; font-size: 1.2rem; color: ${color};">${progress}%</span>
                </div>
                <div class="mini-progress" style="height: 8px; border-radius: 4px; background: rgba(0,0,0,0.05); overflow: hidden;">
                    <div class="mini-progress-bar" style="width: ${progress}%; height: 100%; background: ${color}; border-radius: 4px;"></div>
                </div>
                <div style="margin-top: 15px; font-size: 0.8rem; color: #475569; display: flex; justify-content: space-between;">
                    <span>Mulai: <strong>${app.mulai || app.Mulai || '-'}</strong></span>
                    <span>Target: <strong>${app.target || app.Target || '-'}</strong></span>
                </div>
            `;
            container.appendChild(card);
        });
    }



    function updateTimestamp() {
        const t = document.querySelector('#iotTimestamp');
        if (t) {
            const date = new Date();
            t.textContent = 'Terakhir update: ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
    }

})();




