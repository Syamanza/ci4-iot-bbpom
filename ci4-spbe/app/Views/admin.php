<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — SPBE BBPOM Bandung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Khusus Admin -->
        <aside class="sidebar admin-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                        <rect width="36" height="36" rx="10" fill="#1565C0"/>
                        <path d="M10 18L16 12L22 18L16 24L10 18Z" fill="white" opacity="0.9"/>
                        <path d="M16 12L22 18L28 12L22 6L16 12Z" fill="white" opacity="0.6"/>
                    </svg>
                    <div class="sidebar-title">
                        <span class="title-main">ADMIN SPBE</span>
                    </div>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-item active" data-target="admin-evaluasi">Evaluasi SPBE</li>
                <li class="menu-item" data-target="admin-kendala">Kendala & Rekomendasi</li>
                <li class="menu-item" data-target="admin-bmn">Aset & BMN</li>
                <li class="menu-item" data-target="admin-pegawai">Data Pegawai</li>
                <li class="menu-item" data-target="admin-topografi">Topografi Jaringan</li>
                <li class="menu-item" data-target="admin-pemeliharaan">Pemeliharaan TIK</li>
                <li class="menu-item" data-target="admin-perencanaan">Perencanaan TIK</li>
                <li class="menu-item" data-target="admin-pengembangan">Pengembangan TIK</li>
                <li class="menu-item" data-target="admin-iot" style="color:#64b5f6; font-weight:600;">Pengaturan IoT & Bot WA</li>
                <li><hr class="sidebar-divider"></li>
                <li><a href="/" class="menu-item" style="color:#ffcc80">← Kembali ke Dashboard</a></li>
            </ul>
        </aside>

        <!-- Main Content Admin -->
        <main class="main-content admin-main">
            <header class="top-header">
                <div>
                    <h1>Panel Administrasi</h1>
                    <p class="header-subtitle">Kelola data Command Center secara real-time</p>
                </div>
                <div class="admin-auth">
                    <input type="password" id="apiKeyInput" placeholder="Masukkan API Key..." class="admin-input" value="spbe-bbpom-secret-key-2026">
                </div>
            </header>

            <div id="statusMessage" class="status-msg hidden"></div>

            <!-- Tab 1: Evaluasi SPBE -->
            <section class="admin-section active" id="admin-evaluasi">
                <h2>Capaian SPBE</h2>
                <div class="glass-card form-card">
                    <form id="formEvaluasi">
                        <div class="form-group">
                            <label>Sisa Hari Kalender</label>
                            <input type="number" name="sisaHari" id="inputSisaHari" class="admin-input">
                        </div>
                        <div class="form-group">
                            <label>Rata-rata Capaian (%)</label>
                            <input type="number" step="0.1" name="rataCapaian" id="inputRataCapaian" class="admin-input">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Kebijakan (%)</label>
                                <input type="number" name="kebijakan" id="inputKebijakan" class="admin-input">
                            </div>
                            <div class="form-group">
                                <label>Tata Kelola (%)</label>
                                <input type="number" name="tataKelola" id="inputTataKelola" class="admin-input">
                            </div>
                            <div class="form-group">
                                <label>Layanan (%)</label>
                                <input type="number" name="layanan" id="inputLayanan" class="admin-input">
                            </div>
                            <div class="form-group">
                                <label>Manajemen (%)</label>
                                <input type="number" name="manajemen" id="inputManajemen" class="admin-input">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </section>

            <!-- Tab 2: Kendala & Rekomendasi -->
            <section class="admin-section" id="admin-kendala">
                <h2>Kendala & Rekomendasi</h2>
                <div class="two-column-grid">
                    <div class="glass-card form-card">
                        <h3>Daftar Kendala (Pisahkan dengan baris baru / Enter)</h3>
                        <textarea id="inputKendala" class="admin-textarea" rows="8"></textarea>
                        <button id="btnSimpanKendala" class="btn btn-primary mt-2">Simpan Kendala</button>
                    </div>
                    <div class="glass-card form-card">
                        <h3>Daftar Rekomendasi (Pisahkan dengan baris baru / Enter)</h3>
                        <textarea id="inputRekomendasi" class="admin-textarea" rows="8"></textarea>
                        <button id="btnSimpanRekomendasi" class="btn btn-primary mt-2">Simpan Rekomendasi</button>
                    </div>
                </div>
            </section>

            <!-- Tab 3: Aset & BMN -->
            <section class="admin-section" id="admin-bmn">
                <h2>Inventaris Barang Milik Negara (BMN)</h2>
                <div class="glass-card form-card">
                    <div class="crud-toolbar">
                        <button type="button" class="btn btn-accent" id="btnAddBmn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Aset
                        </button>
                        <button type="button" class="btn btn-paste" id="btnTogglePasteBmn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="9" y="2" width="6" height="4" rx="1"/><path d="M9 4H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-2"/></svg>
                            Quick Paste dari Spreadsheet
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSaveBmn">Simpan Semua Data</button>
                    </div>
                    
                    <div class="quick-paste-box hidden" id="pasteBoxBmn">
                        <p class="paste-instruction">💡 Blok data di Google Sheets (Sesuai urutan kolom tabel di bawah), lalu <strong>Ctrl+V</strong> di sini:</p>
                        <textarea class="admin-textarea paste-area" id="pasteAreaBmn" rows="5" placeholder="Paste daftar aset BMN di sini... (kolom dipisahkan Tab)"></textarea>
                        <button type="button" class="btn btn-accent" id="btnParseBmn">⚡ Parse & Masukkan ke Tabel</button>
                    </div>

                    <div class="table-container">
                        <table class="data-table admin-table mt-3" id="adminBmnTable">
                            <thead>
                                <tr>
                                    <th>Jenis BMN</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Merk / Tipe</th>
                                    <th>Ruangan / Lokasi</th>
                                    <th>Kondisi</th>
                                    <th>Status BMN</th>
                                    <th>Intra/Extra</th>
                                    <th>Tanggal Peroleh</th>
                                    <th>Nilai Perolehan</th>
                                    <th>Nilai Penyusutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminBmnBody">
                            </tbody>
                        </table>
                    </div>
                    <p class="help-text mt-2">Data dari tabel ini akan otomatis dirangkum ke dalam Grafik Donat di Dashboard utama.</p>
                </div>
            </section>

            <!-- Tab 4: Pegawai -->
            <section class="admin-section" id="admin-pegawai">
                <h2>Data Akses Pegawai</h2>
                <div class="glass-card form-card">
                    <div class="crud-toolbar">
                        <button type="button" class="btn btn-accent" id="btnAddPegawai">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Pegawai
                        </button>
                        <button type="button" class="btn btn-paste" id="btnTogglePastePegawai">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="9" y="2" width="6" height="4" rx="1"/><path d="M9 4H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-2"/></svg>
                            Quick Paste dari Spreadsheet
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSavePegawai">Simpan Semua Data</button>
                    </div>
                    <div class="quick-paste-box hidden" id="pasteBoxPegawai">
                        <p class="paste-instruction">📋 Blok data di Google Sheets (kolom: <strong>Nama, dan 30 Aplikasi</strong>), lalu <strong>Ctrl+C</strong>, kemudian <strong>Ctrl+V</strong> di kotak ini:</p>
                        <textarea class="admin-textarea paste-area" id="pasteAreaPegawai" rows="5" placeholder="Paste data dari spreadsheet di sini... (setiap baris = 1 pegawai, kolom dipisahkan Tab)"></textarea>
                        <button type="button" class="btn btn-accent" id="btnParsePegawai">⚡ Parse & Masukkan ke Tabel</button>
                    </div>
                    <div class="table-container" style="overflow-x: auto; overflow-y: auto; max-width: 100%; max-height: 65vh;">
                        <table class="data-table admin-table mt-3" id="adminPegawaiTable" style="min-width: 3800px;">
                            <thead id="adminPegawaiHead">
                                <!-- Di-render dinamis oleh admin.js -->
                            </thead>
                            <tbody id="adminPegawaiBody">
                            </tbody>
                        </table>
                    </div>
                    <p class="help-text mt-2">Klik "Quick Paste" untuk mengimpor data cepat dari Spreadsheet, atau "+ Tambah Pegawai" untuk menambah satu per satu.</p>
                </div>
            </section>

            <!-- Tab 5: Topografi -->
            <section class="admin-section" id="admin-topografi">
                <h2>Topografi Jaringan BBPOM</h2>
                <div class="glass-card form-card">
                    <form id="formTopografi">
                        <div class="form-group">
                            <label>URL Gambar Topografi</label>
                            <input type="text" id="inputTopografi" class="admin-input" placeholder="https://contoh.com/gambar-jaringan.png">
                            <p class="help-text" style="margin-top:5px">Masukkan tautan (URL) gambar jaringan LAN/WAN. Anda bisa mengunggah gambar ke Google Drive/Imgur lalu salin tautannya ke sini.</p>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Gambar Topografi</button>
                    </form>
                </div>
            </section>

            <!-- Tab 6: Pemeliharaan -->
            <section class="admin-section" id="admin-pemeliharaan">
                <h2>Jadwal Pemeliharaan TIK</h2>
                <div class="glass-card form-card">
                    <div class="crud-toolbar">
                        <button type="button" class="btn btn-accent" id="btnAddPemeliharaan">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Aktivitas
                        </button>
                        <button type="button" class="btn btn-paste" id="btnTogglePastePemeliharaan">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="9" y="2" width="6" height="4" rx="1"/><path d="M9 4H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-2"/></svg>
                            Quick Paste dari Spreadsheet
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSimpanPemeliharaan">Simpan Semua Data</button>
                    </div>
                    <div class="quick-paste-box hidden" id="pasteBoxPemeliharaan">
                        <p class="paste-instruction">📋 Blok data di Google Sheets (kolom: <strong>Aktivitas</strong> saja), lalu <strong>Ctrl+V</strong> di sini:</p>
                        <textarea class="admin-textarea paste-area" id="pasteAreaPemeliharaan" rows="5" placeholder="Paste daftar aktivitas pemeliharaan di sini... (satu aktivitas per baris)"></textarea>
                        <button type="button" class="btn btn-accent" id="btnParsePemeliharaan">⚡ Parse & Masukkan ke Tabel</button>
                    </div>
                    <div class="table-container">
                        <table class="data-table admin-table mt-3" id="adminPemeliharaanTable">
                            <thead>
                                <tr>
                                    <th>Aktivitas</th>
                                    <th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>Mei</th><th>Jun</th>
                                    <th>Jul</th><th>Ags</th><th>Sep</th><th>Okt</th><th>Nov</th><th>Des</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminPemeliharaanBody">
                            </tbody>
                        </table>
                    </div>
                    <p class="help-text mt-2">Centang bulan di mana aktivitas pemeliharaan sudah selesai dilakukan.</p>
                </div>
            </section>

            <!-- Tab 7: Perencanaan -->
            <section class="admin-section" id="admin-perencanaan">
                <h2>Perencanaan Anggaran TIK</h2>
                <div class="glass-card form-card">
                    <div class="crud-toolbar">
                        <button type="button" class="btn btn-accent" id="btnAddPerencanaan">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Item
                        </button>
                        <button type="button" class="btn btn-paste" id="btnTogglePastePerencanaan">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="9" y="2" width="6" height="4" rx="1"/><path d="M9 4H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-2"/></svg>
                            Quick Paste dari Spreadsheet
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSimpanPerencanaan">Simpan Semua Data</button>
                    </div>
                    <div class="quick-paste-box hidden" id="pasteBoxPerencanaan">
                        <p class="paste-instruction">📋 Blok data di Google Sheets (kolom: <strong>Uraian, Volume, Satuan, Harga, Keterangan</strong>), lalu <strong>Ctrl+V</strong> di sini:</p>
                        <textarea class="admin-textarea paste-area" id="pasteAreaPerencanaan" rows="5" placeholder="Paste data perencanaan di sini... (kolom dipisahkan Tab)"></textarea>
                        <button type="button" class="btn btn-accent" id="btnParsePerencanaan">⚡ Parse & Masukkan ke Tabel</button>
                    </div>
                    <div class="table-container">
                        <table class="data-table admin-table mt-3" id="adminPerencanaanTable">
                            <thead>
                                <tr>
                                    <th>Uraian Kebutuhan</th>
                                    <th>Volume</th>
                                    <th>Satuan</th>
                                    <th>Harga Satuan (Rp)</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminPerencanaanBody">
                            </tbody>
                        </table>
                    </div>
                    <p class="help-text mt-2">Total anggaran akan dihitung otomatis di Dashboard berdasarkan Volume × Harga Satuan.</p>
                </div>
            </section>

            <!-- Tab 8: Pengembangan -->
            <section class="admin-section" id="admin-pengembangan">
                <h2>Pengembangan Aplikasi TIK</h2>
                <div class="glass-card form-card">
                    <div class="crud-toolbar">
                        <button type="button" class="btn btn-accent" id="btnAddPengembangan">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Aplikasi
                        </button>
                        <button type="button" class="btn btn-paste" id="btnTogglePastePengembangan">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="9" y="2" width="6" height="4" rx="1"/><path d="M9 4H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-2"/></svg>
                            Quick Paste dari Spreadsheet
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSimpanPengembangan">Simpan Semua Data</button>
                    </div>
                    <div class="quick-paste-box hidden" id="pasteBoxPengembangan">
                        <p class="paste-instruction">📋 Blok data di Google Sheets (kolom: <strong>Nama, Deskripsi, Progres(%), Tgl Mulai, Target</strong>), lalu <strong>Ctrl+V</strong> di sini:</p>
                        <textarea class="admin-textarea paste-area" id="pasteAreaPengembangan" rows="5" placeholder="Paste data pengembangan di sini... (kolom dipisahkan Tab)"></textarea>
                        <button type="button" class="btn btn-accent" id="btnParsePengembangan">⚡ Parse & Masukkan ke Tabel</button>
                    </div>
                    <div class="table-container">
                        <table class="data-table admin-table mt-3" id="adminPengembanganTable">
                            <thead>
                                <tr>
                                    <th>Nama Aplikasi</th>
                                    <th>Deskripsi</th>
                                    <th>Progres (%)</th>
                                    <th>Tgl Mulai</th>
                                    <th>Target Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminPengembanganBody">
                            </tbody>
                        </table>
                    </div>
                    <p class="help-text mt-2">Isi progres dalam persen (0-100). Dashboard akan menampilkan progress bar secara otomatis.</p>
                </div>
            </section>

            <!-- Tab 9: Pengaturan IoT & WA -->
            <section class="admin-section" id="admin-iot">
                <h2>Pengaturan Cerdas IoT & WhatsApp</h2>
                <div class="glass-card form-card">
                    <form id="formIotSettings">
                        <div class="form-row">
                            <div class="form-group">
                                <label style="color:#ef4444; font-weight:bold;">Suhu Maksimal Bahaya Lab (°C)</label>
                                <input type="number" step="1" name="suhuMax" id="inputSuhuMax" class="admin-input" placeholder="25">
                                <p class="help-text">Jika sensor membaca suhu di atas ini, peringatan merah dan WA akan terpicu.</p>
                            </div>
                            <div class="form-group">
                                <label style="color:#10b981; font-weight:bold;">Suhu Minimal Bahaya Lab (°C)</label>
                                <input type="number" step="1" name="suhuMin" id="inputSuhuMin" class="admin-input" placeholder="15">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <label style="color:#25D366; font-weight:bold;">Nomor WhatsApp Notifikasi</label>
                            <input type="text" name="waTarget" id="inputWaTarget" class="admin-input" placeholder="08123456789">
                            <p class="help-text">Gunakan format 08xxx atau 628xxx. Pesan bot peringatan akan dikirimkan ke nomor ini.</p>
                        </div>
                        
                        <div class="form-group" style="margin-top:20px;">
                            <label style="font-weight:bold;">Otomasi AC Ruang Keuangan (Simulasi Override)</label>
                            <select name="acKeuanganForce" id="inputAcKeuanganForce" class="admin-input" style="width:200px;">
                                <option value="auto">AUTO (Berdasarkan PIR)</option>
                                <option value="on">PAKSA ON</option>
                                <option value="off">PAKSA OFF</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 15px; background: linear-gradient(135deg, #10b981, #059669); border:none;">
                            Simpan Pengaturan IoT
                        </button>
                    </form>
                </div>
            </section>

        </main>
    </div>

    <script src="admin.js"></script>
</body>
</html>
