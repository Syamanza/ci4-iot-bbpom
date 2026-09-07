<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel &mdash; IoT BBPOM Bandung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        .admin-main { margin-left: 0; padding: 24px; width: 100%; }
        .admin-header { left: 0; width: 100%; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Main Content (No Sidebar) -->
        <main class="main-content admin-main" id="adminMain">
            <header class="admin-header">
                <div class="header-left">
                    <h1 id="pageTitle">Admin IoT</h1>
                    <p class="header-subtitle" id="pageSubtitle">Pengaturan Sistem Monitoring Lingkungan</p>
                </div>
                <div class="header-right">
                    <a href="/ci4-iot/public/" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </header>

            <div class="admin-content" style="padding-top: 80px;">
                <section class="admin-section active" id="admin-iot">
                    <h2>Pengaturan Cerdas IoT & WhatsApp</h2>
                    <div class="glass-card form-card">
                        <form id="formIoT">
                            <div class="form-row">
                                <div class="form-group">
                                    <label style="color:#ef4444; font-weight:bold;">Suhu Maksimal Bahaya Lab (°C)</label>
                                    <input type="number" step="1" name="suhuMax" id="iotMaxTemp" class="admin-input" placeholder="25">
                                    <p class="help-text">Jika sensor membaca suhu di atas ini, peringatan merah dan WA akan terpicu.</p>
                                </div>
                                <div class="form-group">
                                    <label style="color:#10b981; font-weight:bold;">Suhu Minimal Bahaya Lab (°C)</label>
                                    <input type="number" step="1" name="suhuMin" id="iotMinTemp" class="admin-input" placeholder="15">
                                </div>
                            </div>

                            <div class="form-group" style="margin-top:20px;">
                                <label style="color:#25D366; font-weight:bold;">Nomor WhatsApp Notifikasi</label>
                                <input type="text" name="waTarget" id="iotWaNumber" class="admin-input" placeholder="08123456789">
                                <p class="help-text">Gunakan format 08xxx atau 628xxx. Pesan bot peringatan akan dikirimkan ke nomor ini.</p>
                            </div>

                            <button type="submit" class="btn btn-primary" style="margin-top: 15px; background: linear-gradient(135deg, #10b981, #059669); border:none;">
                                Simpan Pengaturan IoT
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </main>
    </div>
    
    <script>
        const API_BASE = '/api';
    </script>
    <script charset="utf-8" src="admin.js"></script>
</body>
</html>