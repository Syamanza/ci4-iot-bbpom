<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="id" class="light-style layout-compact" dir="ltr" data-theme="theme-bpom" data-style="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>DigitLab BDG | BBPOM di Bandung</title>
    
    <link rel="icon" type="image/png" href="<?= base_url('favicon-digitlab-bdg.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-digitlab-bdg.png') ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Icons: Using Public CDN to avoid CORS missing icon boxes -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="https://bpombandung.services/assets/template/assets/vendor/css/rtl/core.css" class="template-customizer-core-css">
    <link rel="stylesheet" href="https://bpombandung.services/assets/template/assets/vendor/css/rtl/theme-bpom.css" class="template-customizer-theme-css">
    <link rel="stylesheet" href="https://bpombandung.services/assets/template/assets/css/demo.css">

    <!-- Digitalisasi Lab & IoT Styles -->
    <style>
        .iot-card {
            border: none;
            box-shadow: 0 0.125rem 0.5rem rgba(161, 172, 184, 0.15);
            border-radius: 0.65rem;
            background: #fff;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.2s;
        }
        .iot-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .iot-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #435971;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .iot-status {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .iot-status.normal { background: #e8fadf; color: #28c76f; }
        .iot-status.warning { background: #fff2d6; color: #ff9f43; }
        .iot-status.danger { background: #ffe1e1; color: #ea5455; }
        
        .iot-value-display {
            display: flex;
            align-items: baseline;
            gap: 6px;
            margin-bottom: 0.5rem;
        }
        .iot-big-value { font-size: 2.5rem; font-weight: 700; color: #034284; line-height: 1; }
        .iot-unit { font-size: 1rem; color: #a1acb8; font-weight: 500; }
        
        .iot-meta { display: flex; justify-content: space-between; font-size: 0.75rem; color: #a1acb8; margin-top: 1rem; }
        
        .history-btn {
            padding: 6px 14px; border: 1px solid #d9dee3; border-radius: 6px; background: #fff; color: #697a8d;
            font-size: 0.8rem; font-weight: 500; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;
        }
        .history-btn:hover { background: #f8f9fa; }
        .history-btn.active { background: #034284; color: white; border-color: #034284; }
        
        .alert-banner { padding: 16px; border-radius: 6px; margin-bottom: 24px; display: flex; gap: 16px; align-items: flex-start; box-shadow: 0 0.125rem 0.25rem rgba(161, 172, 184, 0.1); }
        .alert-banner.safe { background: #e8fadf; border: 1px solid #28c76f; color: #28c76f; }
        .alert-banner.danger { background: #ffe1e1; border: 1px solid #ea5455; color: #ea5455; }
        
        .recap-table th, .recap-table td { padding: 12px; border-bottom: 1px solid #d9dee3; text-align: left; vertical-align: middle; }
        .recap-table th { color: #566a7f; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; }
        
        body {
            background: #f0f4f9 !important;
            margin: 0;
            overflow-x: hidden;
        }
        .ti-circle-filled { font-size: 0.7rem; }

        /* Custom Room & Device Management Styles */
        .sensor-check-card {
            border: 1px solid #d9dee3;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 10px;
            transition: all 0.2s;
            background: #fff;
        }
        .sensor-check-card:hover {
            border-color: #034284;
            background: #fafafe;
        }
        .btn-geulis-primary {
            background-color: #034284;
            border-color: #034284;
            color: #fff;
        }
        .btn-geulis-primary:hover {
            background-color: #023266;
            border-color: #023266;
            color: #fff;
        }
        .btn-geulis-outline {
            border: 1px solid #034284;
            color: #034284;
            background: #fff;
        }
        .btn-geulis-outline:hover {
            background: #034284;
            color: #fff;
        }

        /* ============================================================
           DIGITALISASI LAB SHELL: SIDEBAR & TOPBAR (SESUAI REFERENSI)
           ============================================================ */
        .digilab-app-shell {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .digilab-sidebar {
            width: 268px;
            min-width: 268px;
            background: linear-gradient(180deg, #12315c 0%, #0d2344 62%, #081830 100%);
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1040;
            box-shadow: 4px 0 24px rgba(8, 24, 48, 0.18);
            transition: transform 0.25s ease;
            overflow: hidden;
        }

        /* Decorative batik / cloud watermark at bottom of sidebar */
        .digilab-sidebar::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: -20px;
            right: -20px;
            height: 210px;
            background-image: radial-gradient(circle at 25% 70%, rgba(56, 189, 248, 0.08) 0%, transparent 55%),
                              radial-gradient(circle at 75% 85%, rgba(16, 185, 129, 0.08) 0%, transparent 55%);
            pointer-events: none;
            z-index: 0;
        }

        .sidebar-brand-box {
            padding: 18px 18px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.09);
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .sidebar-logo-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 10px 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
            margin-bottom: 8px;
            width: 100%;
        }
        .sidebar-logo-card img {
            height: 62px;
            width: auto;
            object-fit: contain;
        }
        .sidebar-brand-sub {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: #93c5fd;
            text-transform: uppercase;
        }

        .sidebar-menu-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 12px 12px 20px;
            position: relative;
            z-index: 1;
        }
        .sidebar-menu-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-menu-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.18);
            border-radius: 4px;
        }

        .sidebar-nav-item {
            width: 100%;
            border: none;
            background: transparent;
            color: #cbd5e1;
            padding: 10px 14px;
            margin-bottom: 4px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 0.86rem;
            font-weight: 500;
            text-align: left;
            cursor: pointer;
            transition: all 0.18s ease;
            position: relative;
        }
        .sidebar-nav-item i {
            font-size: 1.15rem;
            color: #94a3b8;
            transition: color 0.18s ease;
            flex-shrink: 0;
        }
        .sidebar-nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }
        .sidebar-nav-item:hover i {
            color: #38bdf8;
        }
        .sidebar-nav-item.active {
            background: linear-gradient(90deg, rgba(14, 165, 233, 0.24) 0%, rgba(14, 165, 233, 0.06) 100%);
            color: #ffffff;
            font-weight: 700;
            box-shadow: inset 3.5px 0 0 #38bdf8;
        }
        .sidebar-nav-item.active i {
            color: #38bdf8;
        }
        .sidebar-badge-live {
            margin-left: auto;
            font-size: 0.65rem;
            padding: 2px 7px;
            border-radius: 20px;
            background: rgba(16, 185, 129, 0.22);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.35);
            font-weight: 700;
        }
        .sidebar-chevron {
            margin-left: auto;
            font-size: 0.95rem !important;
            transition: transform 0.22s ease, color 0.18s ease;
        }
        .sidebar-chevron.rotated {
            transform: rotate(180deg);
            color: #38bdf8 !important;
        }
        .sidebar-submenu {
            display: none;
            margin: 2px 0 8px 22px;
            padding-left: 12px;
            border-left: 1.5px solid rgba(148, 163, 184, 0.28);
        }
        .sidebar-submenu.open {
            display: block;
        }
        .sidebar-sub-item {
            width: 100%;
            border: none;
            background: transparent;
            color: #94a3b8;
            padding: 7px 11px;
            margin-bottom: 3px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            text-align: left;
            cursor: pointer;
            transition: all 0.16s ease;
        }
        .sidebar-sub-item:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.07);
        }
        .sidebar-sub-item.active {
            color: #38bdf8;
            background: rgba(14, 165, 233, 0.15);
            font-weight: 700;
        }

        /* Sub-pages Navigation Pills & Cards */
        .subpage-nav-bar {
            background: #ffffff;
            border-radius: 14px;
            padding: 12px 16px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
            margin-bottom: 18px;
        }
        .subpage-pill-btn {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #475569;
            padding: 8px 14px;
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            transition: all 0.18s ease;
        }
        .subpage-pill-btn:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #034284;
        }
        .subpage-pill-btn.active {
            background: #034284;
            border-color: #034284;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(3, 66, 132, 0.2);
        }
        .subpage-card-item {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 18px;
            height: 100%;
            cursor: pointer;
            transition: all 0.18s ease;
            text-align: left;
        }
        .subpage-card-item:hover {
            transform: translateY(-2px);
            border-color: #38bdf8;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.07);
        }
        .subpage-card-item.active {
            border-color: #034284;
            background: #f8fbff;
            box-shadow: 0 6px 18px rgba(3, 66, 132, 0.11);
        }

        /* ==========================================================
           HULS DIGITAL FORM & WORD-EXACT PDF STYLES
           ========================================================== */
        .huls-form-shell {
            max-width: 1080px;
            margin: 0 auto;
        }
        .huls-main-card {
            background: #ffffff;
            border: 1px solid #dce4ee;
            border-radius: 14px;
            padding: 28px 32px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        }
        .huls-tab-btn {
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 0.83rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s ease;
        }
        .huls-tab-btn:hover {
            border-color: #034284;
            color: #034284;
            background: #f8fafc;
        }
        .huls-tab-btn.active {
            background: #0b4f94;
            border-color: #0b4f94;
            color: #ffffff;
            box-shadow: 0 3px 10px rgba(11, 79, 148, 0.2);
        }
        .huls-info-banner {
            background: #f0fdfa;
            border: 1px solid #99f6e4;
            color: #0f766e;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.8rem;
            margin-bottom: 22px;
        }
        .huls-section-box {
            border: 1px solid #e2e8f0;
            border-radius: 11px;
            background: #ffffff;
            margin-bottom: 22px;
            overflow: hidden;
        }
        .huls-section-box-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 11px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .huls-section-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .huls-section-title::before {
            content: "";
            display: inline-block;
            width: 4px;
            height: 16px;
            border-radius: 2px;
            background: #0b4f94;
        }
        .huls-section-box-body {
            padding: 18px 20px;
        }
        .huls-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
            display: block;
        }
        .huls-input, .huls-select, .huls-textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 8px 12px;
            font-size: 0.83rem;
            color: #1e293b;
            background: #ffffff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .huls-input::placeholder, .huls-textarea::placeholder {
            color: #94a3b8;
        }
        .huls-input:focus, .huls-select:focus, .huls-textarea:focus {
            border-color: #0b4f94;
            outline: none;
            box-shadow: 0 0 0 3px rgba(11, 79, 148, 0.09);
        }
        .huls-check-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dce4ee;
            font-size: 0.82rem;
        }
        .huls-check-table th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            padding: 10px 13px;
            border: 1px solid #dce4ee;
            text-align: left;
            font-size: 0.79rem;
        }
        .huls-check-table td {
            padding: 12px 13px;
            border: 1px solid #dce4ee;
            vertical-align: top;
            background: #ffffff;
        }
        .huls-subparam-grid {
            display: grid;
            grid-template-columns: 205px 1fr;
            gap: 7px 12px;
            align-items: start;
        }
        .huls-subparam-label {
            font-size: 0.77rem;
            font-weight: 600;
            color: #475569;
            padding-top: 7px;
            line-height: 1.3;
        }
        .huls-subblock {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .huls-workspace-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            background: #ffffff;
            border: 1px solid #dce4ef;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(15, 30, 54, 0.03);
        }
        .huls-workspace-tab-btn {
            border: 1px solid transparent;
            background: transparent;
            color: #475569;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 9px 18px;
            border-radius: 8px;
            transition: all 0.16s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .huls-workspace-tab-btn:hover {
            background: #f1f5f9;
            color: #034284;
        }
        .huls-workspace-tab-btn.active {
            background: #034284;
            color: #ffffff;
            border-color: #034284;
            box-shadow: 0 3px 8px rgba(3, 66, 132, 0.22);
        }
        @media (max-width: 767px) {
            .huls-subparam-grid {
                grid-template-columns: 1fr;
                gap: 4px;
            }
            .huls-main-card {
                padding: 18px 16px;
            }
        }

        /* Word Document Exact Replica Preview & PDF Sheet */
        .word-doc-sheet {
            background: #ffffff;
            color: #000000;
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            padding: 20mm 18mm;
            margin: 0 auto 24px;
            max-width: 210mm;
            border: 1px solid #cbd5e1;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }
        .word-doc-sheet h4.word-doc-heading {
            font-family: "Times New Roman", Times, serif;
            font-size: 11.5pt;
            font-weight: bold;
            color: #000000;
            margin: 10px 0 6px;
        }
        .word-doc-center-title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            line-height: 1.35;
            margin-bottom: 14px;
            color: #000000;
        }
        .word-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000000;
            margin-bottom: 12px;
            font-family: "Times New Roman", Times, serif;
            font-size: 10.5pt;
            color: #000000;
        }
        .word-table th, .word-table td {
            border: 1px solid #000000;
            padding: 4px 7px;
            vertical-align: top;
            color: #000000;
        }
        .word-table th {
            font-weight: bold;
            text-align: center;
            background: #ffffff;
        }
        .word-preline {
            white-space: pre-line;
        }
        .word-page-break {
            page-break-before: always;
            break-before: page;
        }

        .sidebar-footer {
            padding: 12px 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.09);
            position: relative;
            z-index: 1;
            background: rgba(8, 24, 48, 0.55);
        }
        .btn-sidebar-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.28);
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-sidebar-logout:hover {
            background: #ef4444;
            color: #ffffff;
        }

        /* Main Area to the Right of Sidebar */
        .digilab-main {
            flex: 1;
            margin-left: 268px;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .digilab-topbar {
            min-height: 68px;
            height: auto;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1 1 0%;
            min-width: 0;
        }
        .topbar-menu-btn {
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            padding: 0;
        }
        .topbar-title-wrap {
            min-width: 0;
            flex: 1 1 auto;
        }
        #topbarActiveTitle {
            color: #034284;
            font-weight: 700;
            font-size: 1.02rem;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-word;
        }
        .topbar-search {
            position: relative;
            flex: 0 1 280px;
            width: 100%;
            min-width: 150px;
            max-width: 320px;
        }
        .topbar-search input {
            width: 100%;
            height: 38px;
            border-radius: 8px;
            border: 1px solid #dce4ee;
            background: #f8fafc;
            padding: 0 34px 0 34px;
            font-size: 0.83rem;
            color: #334155;
            outline: none;
        }
        .topbar-search input:focus {
            border-color: #034284;
            background: #fff;
        }
        .topbar-search .search-ic {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .topbar-search .qr-ic {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .topbar-date-badge {
            white-space: nowrap;
            flex-shrink: 0;
        }
        .topbar-user-block {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .topbar-avatar {
            width: 36px;
            height: 36px;
            min-width: 36px;
            min-height: 36px;
            border-radius: 50%;
            background: #034284;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }
        .topbar-user-info {
            line-height: 1.25;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .sidebar-mobile-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.48);
            backdrop-filter: blur(2px);
            z-index: 1035;
        }
        .sidebar-mobile-backdrop.active {
            display: block;
        }

        .digilab-content {
            padding: 22px 26px 40px;
            flex: 1;
            min-width: 0;
        }

        /* Greeting Banner */
        .greeting-strip {
            background: #ffffff;
            border-radius: 12px;
            padding: 13px 20px;
            margin-bottom: 18px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-left: 4px solid #034284;
        }

        /* Dashboard KPI Cards */
        .kpi-stat-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.05);
            height: 100%;
            border: 1px solid #eef2f7;
            transition: transform 0.18s ease;
        }
        .kpi-stat-card:hover {
            transform: translateY(-2px);
        }
        .kpi-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            flex-shrink: 0;
        }
        .kpi-label {
            font-size: 0.73rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #475569;
            margin-bottom: 2px;
        }
        .kpi-number {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }
        .kpi-sub {
            font-size: 0.77rem;
            margin-top: 6px;
            font-weight: 600;
        }

        /* Timeline Stepper */
        .timeline-stepper {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .timeline-step-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 11px;
            padding: 12px 14px;
            position: relative;
        }
        .timeline-step-card.completed {
            border-left: 4px solid #10b981;
            background: #f0fdf4;
        }
        .timeline-step-card.active-step {
            border-left: 4px solid #0284c7;
            background: #f0f9ff;
        }

        @media (max-width: 1199px) {
            .topbar-search {
                flex: 0 1 200px;
                max-width: 215px;
            }
            #topbarActiveTitle {
                font-size: 0.95rem;
            }
            .timeline-stepper {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 991px) {
            .digilab-sidebar {
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }
            .digilab-sidebar.show-mobile {
                transform: translateX(0);
            }
            .digilab-main {
                margin-left: 0;
            }
            .digilab-topbar {
                padding: 8px 16px;
                gap: 10px;
            }
            .topbar-search {
                flex: 0 1 180px;
                min-width: 130px;
                max-width: 195px;
            }
            #topbarActiveTitle {
                font-size: 0.9rem;
                -webkit-line-clamp: 2;
            }
            .digilab-content {
                padding: 16px 16px 32px;
            }
            .timeline-stepper {
                grid-template-columns: 1fr 1fr;
            }
            .huls-subparam-grid {
                grid-template-columns: 1fr;
                gap: 4px;
            }
            .huls-check-table {
                min-width: 640px;
            }
        }

        @media (max-width: 767px) {
            .digilab-topbar {
                padding: 8px 12px;
                gap: 8px;
                min-height: 58px;
            }
            #topbarActiveTitle {
                font-size: 0.86rem;
                -webkit-line-clamp: 2;
            }
            .digilab-content {
                padding: 14px 12px 28px;
            }
            .timeline-stepper {
                grid-template-columns: 1fr;
            }
            .huls-workspace-tabs {
                width: 100%;
            }
            .huls-workspace-tab-btn {
                flex: 1 1 auto;
                justify-content: center;
                font-size: 0.79rem;
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>

<div class="digilab-app-shell">
    <div class="sidebar-mobile-backdrop" id="sidebarMobileBackdrop" onclick="toggleMobileSidebar(false)"></div>

    <!-- ============================================================
         LEFT SIDEBAR: 12 MENU DIGITALISASI LAB
         ============================================================ -->
    <aside class="digilab-sidebar" id="digilabSidebar">
        <div class="sidebar-brand-box">
            <div class="sidebar-logo-card">
                <img src="<?= base_url('logo-digitlab-bdg.png') ?>" alt="Logo DigitLab BDG">
            </div>
            <div class="sidebar-brand-sub">BBPOM DI BANDUNG</div>
        </div>

        <div class="sidebar-menu-scroll">
            <button type="button" class="sidebar-nav-item active" id="nav-dashboard" onclick="switchMainMenu('dashboard', this)">
                <i class="ti ti-home"></i>
                <span>1. Dashboard</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-perencanaan" onclick="switchMainMenu('perencanaan', this)">
                <i class="ti ti-calendar-stats"></i>
                <span>2. Perencanaan</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-pengujian" onclick="togglePengujianMenu(this)">
                <i class="ti ti-flask-2"></i>
                <span>3. Pelaksanaan Pengujian</span>
                <i class="ti ti-chevron-down sidebar-chevron" id="chevron-pengujian"></i>
            </button>
            <div class="sidebar-submenu" id="submenu-pengujian">
                <button type="button" class="sidebar-sub-item" id="nav-pengujian-sampel" onclick="switchPengujianSubPage('sampel')">
                    <span>1. Pengelolaan Sampel</span>
                </button>
                <button type="button" class="sidebar-sub-item" id="nav-pengujian-status" onclick="switchPengujianSubPage('status')">
                    <span>2. Status Pengujian</span>
                </button>
                <button type="button" class="sidebar-sub-item" id="nav-pengujian-lims" onclick="switchPengujianSubPage('lims')">
                    <span>3. Penerapan LIMS</span>
                </button>
                <button type="button" class="sidebar-sub-item" id="nav-pengujian-reagen" onclick="switchPengujianSubPage('reagen')">
                    <span>4. Pengelolaan Reagen</span>
                </button>
                <button type="button" class="sidebar-sub-item" id="nav-pengujian-pengesahan" onclick="switchPengujianSubPage('pengesahan')">
                    <span>5. Pengesahan Hasil Uji</span>
                </button>
                <button type="button" class="sidebar-sub-item" id="nav-pengujian-huls" onclick="switchPengujianSubPage('huls')">
                    <span>6. HULS</span>
                </button>
            </div>

            <button type="button" class="sidebar-nav-item" id="nav-instrumen" onclick="switchMainMenu('instrumen', this)">
                <i class="ti ti-microscope"></i>
                <span>4. Pengelolaan Instrumen</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-inventor" onclick="switchMainMenu('inventor', this)">
                <i class="ti ti-packages"></i>
                <span>5. Pengelolaan Inventor</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-metode" onclick="switchMainMenu('metode', this)">
                <i class="ti ti-certificate"></i>
                <span>6. Metode dan Validasi</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-keamanan" onclick="switchMainMenu('keamanan', this)">
                <i class="ti ti-shield-lock"></i>
                <span>7. Keamanan dan Integritas Data</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-mutu" onclick="switchMainMenu('mutu', this)">
                <i class="ti ti-award"></i>
                <span>8. Pengendalian Mutu</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-akomodasi" onclick="switchMainMenu('akomodasi', this)">
                <i class="ti ti-device-analytics"></i>
                <span>9. Akomodasi dan Lingkungan</span>
                <span class="sidebar-badge-live">IoT</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-sdm" onclick="switchMainMenu('sdm', this)">
                <i class="ti ti-users"></i>
                <span>10. Pengelolaan SDM</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-ai" onclick="switchMainMenu('ai', this)">
                <i class="ti ti-sparkles"></i>
                <span>11. AI</span>
            </button>

            <button type="button" class="sidebar-nav-item" id="nav-admin" onclick="switchMainMenu('admin', this)">
                <i class="ti ti-settings"></i>
                <span>12. Admin</span>
            </button>
        </div>

        <div class="sidebar-footer">
            <a href="<?= site_url('logout') ?>" class="btn-sidebar-logout">
                <i class="ti ti-logout"></i>
                <span>Keluar / Halaman Login</span>
            </a>
        </div>
    </aside>

    <!-- ============================================================
         MAIN PAGE CONTAINER (TOPBAR + DYNAMIC MENU SECTIONS)
         ============================================================ -->
    <div class="digilab-main">

        <!-- TOPBAR -->
        <header class="digilab-topbar">
            <div class="topbar-left">
                <button type="button" class="btn btn-light border topbar-menu-btn d-lg-none" onclick="toggleMobileSidebar()" title="Buka Menu Navigasi">
                    <i class="ti ti-menu-2 fs-5"></i>
                </button>
                <div class="topbar-title-wrap">
                    <span id="topbarActiveTitle">DigitLab BDG</span>
                </div>
            </div>

            <div class="topbar-search d-none d-md-block">
                <i class="ti ti-search search-ic"></i>
                <input type="text" placeholder="Cari Kode Sampel / ID LIMS / Alat...">
                <i class="ti ti-qrcode qr-ic"></i>
            </div>

            <div class="topbar-right">
                <span id="iotTimestamp" class="text-muted small text-nowrap d-none d-xxl-inline"></span>

                <div class="position-relative flex-shrink-0" title="Notifikasi Sistem">
                    <i class="ti ti-bell fs-4 text-secondary"></i>
                </div>

                <span class="topbar-date-badge text-muted small fw-semibold d-none d-xl-inline border-start ps-3"><?= date('d M Y') ?></span>

                <div class="topbar-user-block border-start ps-3">
                    <div class="topbar-avatar">
                        AD
                    </div>
                    <div class="topbar-user-info d-none d-md-block">
                        <div class="fw-bold small text-dark"><?= esc($userName ?? 'Akang Dadang') ?> <span class="text-muted fw-normal">(<?= esc($userRole ?? 'Verifikator') ?>)</span></div>
                        <a href="<?= site_url('logout') ?>" class="text-danger" style="font-size: 0.72rem; text-decoration: none;"><i class="ti ti-logout"></i> Ganti Akun / Login</a>
                    </div>
                    <a href="<?= site_url('logout') ?>" class="btn btn-sm btn-outline-danger d-md-none py-1 px-2" title="Ganti Akun / Login">
                        <i class="ti ti-logout"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="digilab-content">

            <!-- ==========================================================
                 SECTION 1: MENU DASHBOARD UTAMA (MINIMALIST ENTERPRISE UI)
                 ========================================================== -->
            <div id="section-dashboard">
                <style>
                    /* Minimalist & Modern Dashboard Styles */
                    .dash-page-header {
                        padding: 4px 0 16px 0;
                        margin-bottom: 8px;
                        border-bottom: 1px solid #eef2f6;
                    }
                    .dash-stat-strip {
                        background: #ffffff;
                        border: 1px solid #e2e8f0;
                        border-radius: 10px;
                        padding: 14px 18px;
                        margin-bottom: 16px;
                    }
                    .dash-stat-col {
                        position: relative;
                    }
                    @media (min-width: 768px) {
                        .dash-stat-col:not(:last-child)::after {
                            content: '';
                            position: absolute;
                            right: 0;
                            top: 15%;
                            height: 70%;
                            width: 1px;
                            background: #eef2f6;
                        }
                    }
                    .dash-stat-label {
                        font-size: 0.72rem;
                        font-weight: 600;
                        color: #64748b;
                        text-transform: uppercase;
                        letter-spacing: 0.4px;
                        margin-bottom: 2px;
                    }
                    .dash-stat-value {
                        font-size: 1.35rem;
                        font-weight: 700;
                        color: #0f172a;
                        line-height: 1.2;
                    }
                    .dash-card {
                        background: #ffffff;
                        border: 1px solid #e2e8f0;
                        border-radius: 10px;
                        overflow: hidden;
                        margin-bottom: 16px;
                    }
                    .dash-card-header {
                        padding: 12px 18px;
                        background: #f8fafc;
                        border-bottom: 1px solid #e2e8f0;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        flex-wrap: wrap;
                        gap: 8px;
                    }
                    /* Modernized Minimalist Data Table */
                    .dash-table-container {
                        overflow-x: auto;
                        position: relative;
                    }
                    .dash-data-table {
                        width: max-content;
                        min-width: 100%;
                        border-collapse: collapse;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                        font-size: 11.5px;
                        color: #1e293b;
                    }
                    .dash-data-table th,
                    .dash-data-table td {
                        border: 1px solid #e2e8f0;
                        padding: 7px 9px;
                        vertical-align: middle;
                    }
                    .dash-data-table thead th {
                        background: #f8fafc;
                        color: #475569;
                        font-weight: 600;
                        font-size: 11px;
                        text-align: center;
                        white-space: nowrap;
                    }
                    .dash-sticky-col {
                        position: sticky !important;
                        left: 0;
                        z-index: 8;
                        background: #ffffff !important;
                        width: 220px;
                        min-width: 220px;
                        max-width: 220px;
                        font-weight: 500;
                        border-right: 2px solid #cbd5e1 !important;
                    }
                    .dash-data-table thead .dash-sticky-col {
                        z-index: 12 !important;
                        background: #f1f5f9 !important;
                        font-weight: 600;
                    }
                    .dash-sec-divider td {
                        background: #f8fafc !important;
                        font-weight: 700;
                        color: #0f172a;
                        font-size: 11px;
                        letter-spacing: 0.3px;
                        padding: 6px 10px;
                        border-top: 1px solid #cbd5e1;
                        border-bottom: 1px solid #cbd5e1;
                    }
                    .dash-sec-divider td.dash-sticky-col {
                        background: #f8fafc !important;
                    }
                    .cell-target-subtle {
                        background: #fefce8;
                        color: #854d0e;
                        font-weight: 600;
                        text-align: right;
                    }
                    .cell-capaian-subtle {
                        background: #f0fdf4;
                        color: #15803d;
                        font-weight: 600;
                        text-align: center;
                    }
                    .dash-num-r {
                        text-align: right;
                        white-space: nowrap;
                    }
                    .dash-num-c {
                        text-align: center;
                        white-space: nowrap;
                    }
                    .dash-chip-link {
                        display: inline-flex;
                        align-items: center;
                        gap: 5px;
                        background: #f1f5f9;
                        color: #334155;
                        padding: 2px 8px;
                        border-radius: 4px;
                        font-size: 10.5px;
                        text-decoration: none;
                        max-width: 200px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        border: 1px solid #e2e8f0;
                    }
                    .dash-chip-link:hover {
                        background: #e2e8f0;
                        color: #034284;
                    }
                    .dash-sub-table {
                        width: max-content;
                        min-width: 100%;
                        border-collapse: collapse;
                        font-size: 11px;
                        color: #475569;
                    }
                    .dash-sub-table th,
                    .dash-sub-table td {
                        border: 1px solid #e2e8f0;
                        padding: 4px 7px;
                    }
                    .dash-sub-table th {
                        background: #f8fafc;
                        font-weight: 600;
                        text-align: center;
                    }
                </style>

                <!-- 1. TOP HEADER: MINIMALIST & PROFESSIONAL -->
                <div class="dash-page-header">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.3px; font-size: 1.25rem;">
                                    Dashboard Kinerja Digitalisasi &amp; Pengujian
                                </h4>
                                <span class="badge bg-light text-secondary border fw-medium px-2 py-1" style="font-size: 0.72rem;">BBPOM di Bandung &bull; 2026</span>
                            </div>
                            <p class="text-muted mb-0 small">
                                Pemantauan indikator maturitas digitalisasi, capaian IKU pengujian laboratorium, dan rekapitulasi sampel komoditi.
                            </p>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="badge bg-light text-secondary border fw-medium px-2 py-1 small">
                                <i class="ti ti-calendar-event me-1"></i>Periode: <strong>1/1/2026</strong> &ndash; <strong>10/30/2026</strong>
                            </span>
                            <span class="badge bg-light text-primary border fw-semibold px-2 py-1 small">
                                <i class="ti ti-clock me-1"></i>Sisa <strong>63</strong> Hari Kalender
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 2. STAT STRIP: 5 INDIKATOR UTAMA (SISA HARI, MATURITAS, IKU 1-3) -->
                <div class="dash-stat-strip">
                    <div class="row g-3 align-items-center">
                        <div class="col-6 col-md-4 col-xl-2 dash-stat-col">
                            <div class="dash-stat-label">Sisa Hari Kalender</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="dash-stat-value text-primary">63</span>
                                <small class="text-muted">Hari (Thn 2026)</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl-2 dash-stat-col">
                            <div class="dash-stat-label">Indeks Maturitas Lab</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="dash-stat-value">85.00</span>
                                <small class="text-muted">Target: 100</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl-3 dash-stat-col">
                            <div class="dash-stat-label">IKU 1 &bull; Sediaan Farmasi</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="dash-stat-value text-success">99.25%</span>
                                <small class="text-muted">Target: 98.54%</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-6 col-xl-3 dash-stat-col">
                            <div class="dash-stat-label">IKU 2 &bull; Pangan Olahan</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="dash-stat-value text-success">97.24%</span>
                                <small class="text-muted">Target: 95.98%</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-2">
                            <div class="dash-stat-label">IKU 3 &bull; Sampel KLB</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="dash-stat-value text-success">100.00%</span>
                                <small class="text-muted">Target: 74%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. CHART CARD: REKAPITULASI SAMPEL UJI -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="ti ti-chart-bar me-1 text-primary"></i> Rekapitulasi Sampel Uji per Komoditi
                            </h6>
                            <small class="text-muted">Grafik batang bulanan sampel uji laboratorium BBPOM di Bandung (Target &amp; Realisasi Jan &ndash; Des)</small>
                        </div>
                        <span class="badge bg-light text-secondary border fw-medium">Tahun 2026</span>
                    </div>
                    <div class="p-3">
                        <div style="height: 250px; position: relative;">
                            <canvas id="chartDashboardKomoditi"></canvas>
                        </div>
                    </div>
                </div>

                <!-- 4. MAIN DATA TABLE: INDEKS MATURITAS, IKU & JUMLAH SAMPEL UJI -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="ti ti-table me-1 text-primary"></i> Matriks Capaian Indikator Kinerja &amp; Sampel Laboratorium
                            </h6>
                            <small class="text-muted">Target, realisasi bulanan (Jan &ndash; Des), capaian akhir, serta sumber data verifikasi</small>
                        </div>
                        <span class="badge bg-light text-secondary border fw-medium">Lengkap Jan &ndash; Des</span>
                    </div>

                    <div class="dash-table-container">
                        <table class="dash-data-table">
                            <thead>
                                <tr>
                                    <th class="dash-sticky-col">URAIAN</th>
                                    <th style="width: 70px;">TARGET</th>
                                    <th style="width: 48px;">JAN</th>
                                    <th style="width: 48px;">FEB</th>
                                    <th style="width: 48px;">MAR</th>
                                    <th style="width: 48px;">APR</th>
                                    <th style="width: 48px;">MEI</th>
                                    <th style="width: 48px;">JUN</th>
                                    <th style="width: 48px;">JUL</th>
                                    <th style="width: 48px;">AGS</th>
                                    <th style="width: 48px;">SEPT</th>
                                    <th style="width: 48px;">OKT</th>
                                    <th style="width: 48px;">NOV</th>
                                    <th style="width: 48px;">DES</th>
                                    <th style="width: 140px;">UPDATE CAPAIAN</th>
                                    <th style="min-width: 170px;">SUMBER DATA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- SECTION A: INDIKATOR MATURITAS & IKU -->
                                <tr class="dash-sec-divider">
                                    <td class="dash-sticky-col">1. INDIKATOR MATURITAS &amp; IKU LABORATORIUM</td>
                                    <td colspan="15"></td>
                                </tr>

                                <!-- Row 1: Indeks Maturitas Digitalisasi Lab -->
                                <tr>
                                    <td class="dash-sticky-col">Indeks Maturitas Digitalisasi Lab</td>
                                    <td class="cell-target-subtle">100</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r fw-bold">85</td>
                                    <td class="dash-num-r fw-bold">85</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="cell-capaian-subtle">85.00</td>
                                    <td>
                                        <a href="javascript:void(0)" class="dash-chip-link" title="template_master_asesmen">
                                            <i class="ti ti-file-analytics text-success"></i> template_master_ases...
                                        </a>
                                    </td>
                                </tr>

                                <!-- Row 2: IKU 1 -->
                                <tr>
                                    <td class="dash-sticky-col">
                                        <div class="fw-semibold">IKU 1 - Persentase Sampel Sediaan Farmasi</div>
                                        <small class="text-muted">Sampel berisiko ditindaklanjuti sesuai ketentuan</small>
                                    </td>
                                    <td class="cell-target-subtle">98.54</td>
                                    <td class="dash-num-r">100.00</td>
                                    <td class="dash-num-r">94.71</td>
                                    <td class="dash-num-r">96.18</td>
                                    <td class="dash-num-r">97.70</td>
                                    <td class="dash-num-r">96.68</td>
                                    <td class="dash-num-r">97.15</td>
                                    <td class="dash-num-r">98.97</td>
                                    <td class="dash-num-r">99.19</td>
                                    <td class="dash-num-r fw-bold text-success">99.25</td>
                                    <td class="dash-num-r text-muted">0.00</td>
                                    <td class="dash-num-r text-muted">0.00</td>
                                    <td class="dash-num-r text-muted">0.00</td>
                                    <td class="cell-capaian-subtle">99.25</td>
                                    <td rowspan="4" style="vertical-align: top;">
                                        <a href="https://sites.google.com/view/manajemenkinerjabbpomdibandung/home/2026/monev-2026/input-capaian-iku?authuser=0" target="_blank" rel="noopener" class="dash-chip-link" style="max-width: 240px;" title="Input Capaian IKU Monev BBPOM di Bandung">
                                            <i class="ti ti-external-link text-primary"></i> Monev Capaian IKU BBPOM
                                        </a>
                                    </td>
                                </tr>

                                <!-- Row 3: IKU 2 -->
                                <tr>
                                    <td class="dash-sticky-col">
                                        <div class="fw-semibold">IKU 2 - Persentase Sampel Pangan Olahan</div>
                                        <small class="text-muted">Sampel berisiko ditindaklanjuti sesuai ketentuan</small>
                                    </td>
                                    <td class="cell-target-subtle">95.98</td>
                                    <td class="dash-num-r text-muted">0.00</td>
                                    <td class="dash-num-r">94.87</td>
                                    <td class="dash-num-r">93.34</td>
                                    <td class="dash-num-r">96.25</td>
                                    <td class="dash-num-r">95.12</td>
                                    <td class="dash-num-r">97.21</td>
                                    <td class="dash-num-r">97.66</td>
                                    <td class="dash-num-r">98.00</td>
                                    <td class="dash-num-r fw-bold text-success">97.24</td>
                                    <td class="dash-num-r text-muted">0.00</td>
                                    <td class="dash-num-r text-muted">0.00</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="cell-capaian-subtle">97.24</td>
                                </tr>

                                <!-- Row 4: IKU 3 -->
                                <tr>
                                    <td class="dash-sticky-col">
                                        <div class="fw-semibold">IKU 3 - Persentase Sampel KLB</div>
                                        <small class="text-muted">Keracunan pangan yang diuji sesuai standar</small>
                                    </td>
                                    <td class="cell-target-subtle">74</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r fw-bold text-success">100</td>
                                    <td class="dash-num-r fw-bold text-success">100</td>
                                    <td class="dash-num-r fw-bold text-success">100</td>
                                    <td class="dash-num-r fw-bold text-success">100</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="cell-capaian-subtle">100.00</td>
                                </tr>

                                <!-- Row 5: IKU PIRT -->
                                <tr>
                                    <td class="dash-sticky-col">
                                        <div class="fw-semibold">IKU - Persentase Sampel PIRT Berisiko</div>
                                        <small class="text-muted">Ditindaklanjuti sesuai ketentuan</small>
                                    </td>
                                    <td class="cell-target-subtle">95</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r">92.42</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r">75</td>
                                    <td class="dash-num-r">75</td>
                                    <td class="dash-num-r text-danger">#REF!</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-r text-muted">0</td>
                                    <td class="dash-num-c text-danger">#REF!</td>
                                </tr>

                                <!-- SECTION B: JUMLAH SAMPEL KOMODITI -->
                                <tr class="dash-sec-divider">
                                    <td class="dash-sticky-col">2. JUMLAH SAMPEL UJI PER KOMODITI</td>
                                    <td colspan="15"></td>
                                </tr>

                                <!-- Sampel Row 1: Obat -->
                                <tr>
                                    <td class="dash-sticky-col">Jumlah Sampel Sediaan Farmasi - Obat</td>
                                    <td class="dash-num-c text-muted">-</td>
                                    <td class="dash-num-r">25</td>
                                    <td class="dash-num-r">41</td>
                                    <td class="dash-num-r">37</td>
                                    <td class="dash-num-r">36</td>
                                    <td class="dash-num-r">46</td>
                                    <td class="dash-num-r">46</td>
                                    <td class="dash-num-r">43</td>
                                    <td class="dash-num-r">43</td>
                                    <td class="dash-num-r">42</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-c fw-semibold">42.00</td>
                                    <td class="dash-num-r fw-bold" style="background:#f8fafc;">359 Total</td>
                                </tr>

                                <!-- Sampel Row 2: OBA -->
                                <tr>
                                    <td class="dash-sticky-col">Jumlah Sampel Sediaan Farmasi - OBA</td>
                                    <td class="dash-num-c text-muted">-</td>
                                    <td class="dash-num-r">22</td>
                                    <td class="dash-num-r">26</td>
                                    <td class="dash-num-r">28</td>
                                    <td class="dash-num-r">18</td>
                                    <td class="dash-num-r">49</td>
                                    <td class="dash-num-r">37</td>
                                    <td class="dash-num-r">35</td>
                                    <td class="dash-num-r">18</td>
                                    <td class="dash-num-r">32</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-c fw-semibold">32.00</td>
                                    <td class="dash-num-r fw-bold" style="background:#f8fafc;">265 Total</td>
                                </tr>

                                <!-- Sampel Row 3: SK -->
                                <tr>
                                    <td class="dash-sticky-col">Jumlah Sampel Sediaan Farmasi - SK</td>
                                    <td class="dash-num-c text-muted">-</td>
                                    <td class="dash-num-r">10</td>
                                    <td class="dash-num-r">7</td>
                                    <td class="dash-num-r">8</td>
                                    <td class="dash-num-r">4</td>
                                    <td class="dash-num-r">10</td>
                                    <td class="dash-num-r">7</td>
                                    <td class="dash-num-r">5</td>
                                    <td class="dash-num-r">6</td>
                                    <td class="dash-num-r">4</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-c fw-semibold">4.00</td>
                                    <td class="dash-num-r fw-bold" style="background:#f8fafc;">61 Total</td>
                                </tr>

                                <!-- Sampel Row 4: Kosmetik -->
                                <tr>
                                    <td class="dash-sticky-col">Jumlah Sampel Sediaan Farmasi - Kosmetik</td>
                                    <td class="dash-num-c text-muted">-</td>
                                    <td class="dash-num-r">34</td>
                                    <td class="dash-num-r">60</td>
                                    <td class="dash-num-r">60</td>
                                    <td class="dash-num-r">52</td>
                                    <td class="dash-num-r">70</td>
                                    <td class="dash-num-r">64</td>
                                    <td class="dash-num-r">62</td>
                                    <td class="dash-num-r">62</td>
                                    <td class="dash-num-r">61</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-c fw-semibold">61.00</td>
                                    <td class="dash-num-r fw-bold" style="background:#f8fafc;">525 Total</td>
                                </tr>

                                <!-- Sampel Row 5: Pangan Olahan -->
                                <tr>
                                    <td class="dash-sticky-col">Jumlah Sampel Sediaan Pangan Olahan</td>
                                    <td class="dash-num-c text-muted">-</td>
                                    <td class="dash-num-r">81</td>
                                    <td class="dash-num-r">54</td>
                                    <td class="dash-num-r">51</td>
                                    <td class="dash-num-r">17</td>
                                    <td class="dash-num-r">41</td>
                                    <td class="dash-num-r">30</td>
                                    <td class="dash-num-r">30</td>
                                    <td class="dash-num-r">55</td>
                                    <td class="dash-num-r">21</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-c fw-semibold">21.00</td>
                                    <td class="dash-num-r fw-bold" style="background:#f8fafc;">380 Total</td>
                                </tr>

                                <!-- Sampel Row 6: KLB -->
                                <tr>
                                    <td class="dash-sticky-col">Jumlah Sampel KLB</td>
                                    <td class="dash-num-c text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-r text-muted">-</td>
                                    <td class="dash-num-c text-muted">-</td>
                                    <td class="dash-num-r text-muted" style="background:#f8fafc;">0 Total</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 5. AKUMULASI KUMULATIF & TREN BULANAN -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="ti ti-calculator me-1 text-primary"></i> Rincian Akumulasi Kumulatif &amp; Bulanan per Komoditi
                            </h6>
                            <small class="text-muted">Progres penambahan sampel kumulatif berjalan dan rincian per bulan</small>
                        </div>
                    </div>
                    <div class="dash-table-container">
                        <table class="dash-sub-table">
                            <thead>
                                <tr>
                                    <th style="width: 220px; text-align: left; padding-left: 10px;">KOMODITI</th>
                                    <th>JAN</th><th>FEB</th><th>MAR</th><th>APR</th><th>MEI</th><th>JUN</th><th>JUL</th><th>AGS</th><th>SEPT</th>
                                    <th>JAN</th><th>FEB</th><th>MAR</th><th>APR</th><th>MAY</th><th>JUN</th><th>JUL</th><th>AUG</th><th>SEP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: 500; padding-left: 10px;">Jumlah Sampel Sediaan Farmasi - Obat</td>
                                    <td class="dash-num-r">25.00</td><td class="dash-num-r">66.00</td><td class="dash-num-r">103.00</td><td class="dash-num-r">139.00</td><td class="dash-num-r">185.00</td><td class="dash-num-r">231.00</td><td class="dash-num-r">274.00</td><td class="dash-num-r">317.00</td><td class="dash-num-r fw-bold">359.00</td>
                                    <td class="dash-num-r">25.00</td><td class="dash-num-r">41.00</td><td class="dash-num-r">37.00</td><td class="dash-num-r">36.00</td><td class="dash-num-r">46.00</td><td class="dash-num-r">46.00</td><td class="dash-num-r">43.00</td><td class="dash-num-r">43.00</td><td class="dash-num-r">42.00</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; padding-left: 10px;">Jumlah Sampel Sediaan Farmasi - OBA</td>
                                    <td class="dash-num-r">22.00</td><td class="dash-num-r">48.00</td><td class="dash-num-r">76.00</td><td class="dash-num-r">94.00</td><td class="dash-num-r">143.00</td><td class="dash-num-r">180.00</td><td class="dash-num-r">215.00</td><td class="dash-num-r">233.00</td><td class="dash-num-r fw-bold">265.00</td>
                                    <td class="dash-num-r">22.00</td><td class="dash-num-r">26.00</td><td class="dash-num-r">28.00</td><td class="dash-num-r">18.00</td><td class="dash-num-r">49.00</td><td class="dash-num-r">37.00</td><td class="dash-num-r">35.00</td><td class="dash-num-r">18.00</td><td class="dash-num-r">32.00</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; padding-left: 10px;">Jumlah Sampel Sediaan Farmasi - SK</td>
                                    <td class="dash-num-r">10.00</td><td class="dash-num-r">17.00</td><td class="dash-num-r">25.00</td><td class="dash-num-r">29.00</td><td class="dash-num-r">39.00</td><td class="dash-num-r">46.00</td><td class="dash-num-r">51.00</td><td class="dash-num-r">57.00</td><td class="dash-num-r fw-bold">61.00</td>
                                    <td class="dash-num-r">10.00</td><td class="dash-num-r">7.00</td><td class="dash-num-r">8.00</td><td class="dash-num-r">4.00</td><td class="dash-num-r">10.00</td><td class="dash-num-r">7.00</td><td class="dash-num-r">5.00</td><td class="dash-num-r">6.00</td><td class="dash-num-r">4.00</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; padding-left: 10px;">Jumlah Sampel Sediaan Farmasi - Kosmetik</td>
                                    <td class="dash-num-r">34.00</td><td class="dash-num-r">94.00</td><td class="dash-num-r">154.00</td><td class="dash-num-r">206.00</td><td class="dash-num-r">276.00</td><td class="dash-num-r">340.00</td><td class="dash-num-r">402.00</td><td class="dash-num-r">464.00</td><td class="dash-num-r fw-bold">525.00</td>
                                    <td class="dash-num-r">34.00</td><td class="dash-num-r">60.00</td><td class="dash-num-r">60.00</td><td class="dash-num-r">52.00</td><td class="dash-num-r">70.00</td><td class="dash-num-r">64.00</td><td class="dash-num-r">62.00</td><td class="dash-num-r">62.00</td><td class="dash-num-r">61.00</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; padding-left: 10px;">Jumlah Sampel Sediaan Pangan Olahan</td>
                                    <td class="dash-num-r">81.00</td><td class="dash-num-r">135.00</td><td class="dash-num-r">186.00</td><td class="dash-num-r">203.00</td><td class="dash-num-r">244.00</td><td class="dash-num-r">274.00</td><td class="dash-num-r">304.00</td><td class="dash-num-r">359.00</td><td class="dash-num-r fw-bold">380.00</td>
                                    <td class="dash-num-r">81.00</td><td class="dash-num-r">54.00</td><td class="dash-num-r">51.00</td><td class="dash-num-r">17.00</td><td class="dash-num-r">41.00</td><td class="dash-num-r">30.00</td><td class="dash-num-r">30.00</td><td class="dash-num-r">55.00</td><td class="dash-num-r">21.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> <!-- / #section-dashboard -->

            <!-- ==========================================================
                 SECTION 2: MENU AKOMODASI DAN LINGKUNGAN (MONITORING IoT)
                 ========================================================== -->
            <div id="section-akomodasi" style="display: none;">

                <!-- HEADER & ACTION BUTTONS FOR DYNAMIC ROOMS/DEVICES -->
                <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-3 gap-2">
                    <div>
                        <h4 class="fw-bold mb-0" style="color: #034284;"><span class="text-muted fw-light">DigitLab BDG /</span> Akomodasi dan Lingkungan</h4>
                        <small class="text-muted">Monitoring Suhu, Kelembapan, Gas, Otomasi AC &amp; Intensitas Cahaya (Saklar Servo) Real-Time</small>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-geulis-outline d-flex align-items-center gap-1 shadow-sm" onclick="openModalTambahRuangan()">
                            <i class="ti ti-plus"></i> Tambah Ruangan
                        </button>
                        <button type="button" class="btn btn-sm btn-geulis-primary d-flex align-items-center gap-1 shadow-sm" onclick="openModalTambahAlat()">
                            <i class="ti ti-device-analytics"></i> Tambah Alat Sensor
                        </button>
                        <button type="button" class="btn btn-sm btn-light border d-flex align-items-center gap-1 shadow-sm" onclick="openModalKelola()" title="Daftar Ruangan & Konfigurasi Alat">
                            <i class="ti ti-adjustments-horizontal"></i> Kelola
                        </button>
                    </div>
                </div>

            <!-- MAIN CONTENT TABS (Bootstrap 5) -->
            <ul class="nav nav-pills mb-4 gap-2" id="mainRoomTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-lab" type="button" role="tab"><i class="ti ti-flask me-1"></i> Ruang Lab Reagen</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-keuangan" type="button" role="tab"><i class="ti ti-coins me-1"></i> Ruang Keuangan</button>
                </li>
            </ul>

            <div class="tab-content" id="mainRoomTabContent" style="padding: 0; background: transparent; box-shadow: none;">
                <!-- TAB LAB REAGEN -->
                <div class="tab-pane fade show active" id="tab-lab" role="tabpanel">
                    
                    <!-- HISTORY CONTROLS -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body d-flex flex-wrap align-items-center gap-3">
                            <span class="fw-bold text-muted"><i class="ti ti-filter"></i> Filter:</span>
                            <button class="history-btn active" data-range="realtime" onclick="setHistoryRange('realtime', this)">
                                <i class="ti ti-circle-filled text-danger" style="font-size: 8px;"></i> Real-time
                            </button>
                            <button class="history-btn" data-range="1h" onclick="setHistoryRange('1h', this)">1 Jam</button>
                            <button class="history-btn" data-range="6h" onclick="setHistoryRange('6h', this)">6 Jam</button>
                            <button class="history-btn" data-range="24h" onclick="setHistoryRange('24h', this)">Hari Ini</button>
                            
                            <span class="text-muted mx-2">|</span>
                            
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-muted small mb-0">Dari:</label>
                                <input type="date" id="historyFrom" class="form-control form-control-sm">
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-muted small mb-0">Sampai:</label>
                                <input type="date" id="historyTo" class="form-control form-control-sm">
                            </div>
                            <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" onclick="setHistoryDateRange()" style="background-color: #3b327b; border-color: #3b327b;">
                                <i class="ti ti-search" style="font-size: 14px;"></i> Terapkan
                            </button>
                            
                            <span id="historyInfo" class="ms-auto small text-muted"></span>
                        </div>
                    </div>

                    <!-- ==========================================
                         ALAT 1 — RUANG LAB REAGEN (AC)
                         ========================================== -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="background: #3b327b; padding: 12px; border-radius: 10px; color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(59,50,123,0.2);">
                                <i class="ti ti-device-analytics ti-md"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #3b327b;">
                                    Alat 1 &mdash; Ruang LAB Reagen (AC)
                                    <span class="badge bg-label-primary ms-2" style="font-size: 0.72rem;">Kode Alat: ALAT_1_AC (ID JSON: LAB_AC)</span>
                                </h5>
                                <small class="text-muted">Monitoring suhu, kelembapan &amp; kualitas udara | Node: Lab Reagen Area AC</small>
                            </div>
                        </div>
                    </div>

                    <!-- ALERT ALAT 1 -->
                    <div class="alert-banner safe" id="alertSuhuBanner">
                        <div style="margin-top: 2px;"><i class="ti ti-circle-check-filled ti-lg"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold" id="alertSuhuTitle">Suhu Lab Dalam Batas Aman</h6>
                            <small id="alertSuhuDesc">Rentang aman: 15&deg;C &mdash; 25&deg;C | Suhu saat ini: <strong id="alertSuhuCurrent">--&deg;C</strong></small>
                        </div>
                    </div>

                    <!-- SENSORS ALAT 1 -->
                    <div class="row">
                        <!-- SUHU -->
                        <div class="col-md-4">
                            <div class="iot-card" id="cardSuhu">
                                <div class="iot-card-header">
                                    <h6 class="iot-card-title"><i class="ti ti-temperature text-danger fs-4"></i> Suhu Ruang Lab</h6>
                                    <span class="iot-status normal" id="suhuStatus">Aman</span>
                                </div>
                                <div class="iot-value-display">
                                    <span class="iot-big-value" id="suhuValue">--</span>
                                    <span class="iot-unit">&deg;C</span>
                                </div>
                                <div><canvas id="chartSuhu" style="width: 100%; height: 150px; display: block;"></canvas></div>
                                <div class="iot-meta"><span>Sensor: DHT22</span><span>Kode: ALAT_1_AC</span></div>
                            </div>
                        </div>
                        <!-- HUM -->
                        <div class="col-md-4">
                            <div class="iot-card" id="cardKelembapan">
                                <div class="iot-card-header">
                                    <h6 class="iot-card-title"><i class="ti ti-droplet text-primary fs-4"></i> Kelembapan Lab</h6>
                                    <span class="iot-status normal" id="humStatus">Aman</span>
                                </div>
                                <div class="iot-value-display">
                                    <span class="iot-big-value" id="kelembapanValue">--</span>
                                    <span class="iot-unit">%RH</span>
                                </div>
                                <div><canvas id="chartKelembapan" style="width: 100%; height: 150px; display: block;"></canvas></div>
                                <div class="iot-meta"><span>Sensor: DHT22</span><span>Kode: ALAT_1_AC</span></div>
                            </div>
                        </div>
                        <!-- MQ -->
                        <div class="col-md-4">
                            <div class="iot-card" id="cardMQ">
                                <div class="iot-card-header">
                                    <h6 class="iot-card-title"><i class="ti ti-wind text-warning fs-4"></i> Kualitas Udara (MQ)</h6>
                                    <span class="iot-status normal" id="mqStatus">Aman</span>
                                </div>
                                <div class="iot-value-display">
                                    <span class="iot-big-value" id="mqValue">--</span>
                                    <span class="iot-unit">PPM</span>
                                </div>
                                <div><canvas id="chartMQ" style="width: 100%; height: 150px; display: block;"></canvas></div>
                                <div class="iot-meta"><span>Sensor: MQ-2/MQ-135</span><span>Kode: ALAT_1_AC</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- REKAP HARIAN & EXPORT PDF KHUSUS ALAT 1 -->
                    <div class="card mt-1 mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h6 class="card-title mb-0 fw-bold" style="color: #3b327b;">
                                <i class="ti ti-table text-primary me-1"></i> Rekap Harian Alat 1 (AC) &mdash; <span id="recapDate">Hari Ini</span>
                                <span class="badge bg-label-primary ms-1">ALAT_1_AC</span>
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-muted small mb-0">Pilih Tanggal:</label>
                                <input type="date" id="pdfDateLab" class="form-control form-control-sm" style="width: auto;" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                                <button class="btn btn-success btn-sm d-flex align-items-center gap-1" id="btnGeneratePDFAlat1">
                                    <i class="ti ti-file-download"></i> Export PDF Alat 1
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
                                    <tr>
                                        <td><i class="ti ti-temperature text-muted me-2"></i>Suhu</td>
                                        <td id="recapAvgSuhu">--</td><td id="recapMinSuhu">--</td><td id="recapMaxSuhu">--</td>
                                        <td id="recapSamples" rowspan="3" class="align-middle text-center fw-bold fs-4" style="color: #3b327b;">0</td>
                                    </tr>
                                    <tr><td><i class="ti ti-droplet text-muted me-2"></i>Kelembapan</td><td id="recapAvgHum">--</td><td id="recapMinHum">--</td><td id="recapMaxHum">--</td></tr>
                                    <tr><td><i class="ti ti-wind text-muted me-2"></i>Udara (PPM)</td><td id="recapAvgMQ">--</td><td id="recapMinMQ">--</td><td id="recapMaxMQ">--</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-2 d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <span class="badge bg-label-danger me-1"><i class="ti ti-alert-circle"></i></span>
                                Alert Suhu Alat 1: <strong id="recapAlerts">0</strong> kali melampaui batas aman
                            </div>
                        </div>
                    </div>

                    <!-- ==========================================
                         ALAT 2 — RUANG LAB REAGEN (NON-AC)
                         ========================================== -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 mt-4">
                        <div class="d-flex align-items-center gap-3">
                            <div style="background: #3b327b; padding: 12px; border-radius: 10px; color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(59,50,123,0.2);">
                                <i class="ti ti-device-analytics ti-md"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #3b327b;">
                                    Alat 2 &mdash; Ruang LAB Reagen (Non-AC)
                                    <span class="badge bg-label-primary ms-2" style="font-size: 0.72rem;">Kode Alat: ALAT_2_NON_AC (ID JSON: LAB_NON_AC)</span>
                                </h5>
                                <small class="text-muted">Monitoring area non-AC (Suhu Lingkungan Normal) | Node: Lab Reagen Area Non-AC</small>
                            </div>
                        </div>
                    </div>

                    <!-- ALERT ALAT 2 -->
                    <div class="alert-banner safe" id="alertSuhuBanner_2">
                        <div style="margin-top: 2px;"><i class="ti ti-circle-check-filled ti-lg"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold" id="alertSuhuTitle_2">Suhu Lingkungan Aman</h6>
                            <small id="alertSuhuDesc_2">Rentang aman: 15&deg;C &mdash; 25&deg;C | Suhu saat ini: <strong id="alertSuhuCurrent_2">--&deg;C</strong></small>
                        </div>
                    </div>

                    <!-- SENSORS ALAT 2 -->
                    <div class="row">
                        <!-- SUHU -->
                        <div class="col-md-4">
                            <div class="iot-card" id="cardSuhu_2">
                                <div class="iot-card-header">
                                    <h6 class="iot-card-title"><i class="ti ti-temperature text-danger fs-4"></i> Suhu Lingkungan</h6>
                                    <span class="iot-status normal" id="suhuStatus_2">Aman</span>
                                </div>
                                <div class="iot-value-display">
                                    <span class="iot-big-value" id="suhuValue_2">--</span>
                                    <span class="iot-unit">&deg;C</span>
                                </div>
                                <div><canvas id="chartSuhu_2" style="width: 100%; height: 150px; display: block;"></canvas></div>
                                <div class="iot-meta"><span>Sensor: DHT22</span><span>Kode: ALAT_2_NON_AC</span></div>
                            </div>
                        </div>
                        <!-- HUM -->
                        <div class="col-md-4">
                            <div class="iot-card" id="cardHum_2">
                                <div class="iot-card-header">
                                    <h6 class="iot-card-title"><i class="ti ti-droplet text-primary fs-4"></i> Kelembapan</h6>
                                    <span class="iot-status normal" id="humStatus_2">Aman</span>
                                </div>
                                <div class="iot-value-display">
                                    <span class="iot-big-value" id="humValue_2">--</span>
                                    <span class="iot-unit">%RH</span>
                                </div>
                                <div><canvas id="chartHum_2" style="width: 100%; height: 150px; display: block;"></canvas></div>
                                <div class="iot-meta"><span>Sensor: DHT22</span><span>Kode: ALAT_2_NON_AC</span></div>
                            </div>
                        </div>
                        <!-- MQ -->
                        <div class="col-md-4">
                            <div class="iot-card" id="cardMQ_2">
                                <div class="iot-card-header">
                                    <h6 class="iot-card-title"><i class="ti ti-wind text-warning fs-4"></i> Kualitas Udara</h6>
                                    <span class="iot-status normal" id="mqStatus_2">Aman</span>
                                </div>
                                <div class="iot-value-display">
                                    <span class="iot-big-value" id="mqValue_2">--</span>
                                    <span class="iot-unit">PPM</span>
                                </div>
                                <div><canvas id="chartMQ_2" style="width: 100%; height: 150px; display: block;"></canvas></div>
                                <div class="iot-meta"><span>Sensor: MQ-2/MQ-135</span><span>Kode: ALAT_2_NON_AC</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- REKAP HARIAN & EXPORT PDF KHUSUS ALAT 2 -->
                    <div class="card mt-1 mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h6 class="card-title mb-0 fw-bold" style="color: #3b327b;">
                                <i class="ti ti-table text-primary me-1"></i> Rekap Harian Alat 2 (Non-AC) &mdash; <span id="recapDate_2">Hari Ini</span>
                                <span class="badge bg-label-primary ms-1">ALAT_2_NON_AC</span>
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-muted small mb-0">Pilih Tanggal:</label>
                                <input type="date" id="pdfDateLab_2" class="form-control form-control-sm" style="width: auto;" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                                <button class="btn btn-success btn-sm d-flex align-items-center gap-1" id="btnGeneratePDFAlat2">
                                    <i class="ti ti-file-download"></i> Export PDF Alat 2
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
                                    <tr>
                                        <td><i class="ti ti-temperature text-muted me-2"></i>Suhu</td>
                                        <td id="recapAvgSuhu_2">--</td><td id="recapMinSuhu_2">--</td><td id="recapMaxSuhu_2">--</td>
                                        <td id="recapSamples_2" rowspan="3" class="align-middle text-center fw-bold fs-4" style="color: #3b327b;">0</td>
                                    </tr>
                                    <tr><td><i class="ti ti-droplet text-muted me-2"></i>Kelembapan</td><td id="recapAvgHum_2">--</td><td id="recapMinHum_2">--</td><td id="recapMaxHum_2">--</td></tr>
                                    <tr><td><i class="ti ti-wind text-muted me-2"></i>Udara (PPM)</td><td id="recapAvgMQ_2">--</td><td id="recapMinMQ_2">--</td><td id="recapMaxMQ_2">--</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted small">
                                <span class="badge bg-label-danger me-1"><i class="ti ti-alert-circle"></i></span>
                                Alert Suhu Alat 2: <strong id="recapAlerts_2">0</strong> kali melampaui batas aman
                            </div>
                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" id="btnGeneratePDF">
                                <i class="ti ti-files"></i> Export PDF Gabungan (Alat 1 &amp; 2)
                            </button>
                        </div>
                    </div>

                    <!-- DYNAMIC DEVICES ADDED TO LAB REAGEN -->
                    <div id="customDevices_lab"></div>
                    
                </div>

                <!-- TAB RUANG KEUANGAN -->
                <div class="tab-pane fade" id="tab-keuangan" role="tabpanel">
                    
                    <!-- FILTER CONTROLS RUANG KEUANGAN -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body d-flex flex-wrap align-items-center gap-3">
                            <span class="fw-bold text-muted"><i class="ti ti-filter"></i> Filter:</span>
                            <button class="history-btn active" data-range="realtime" onclick="setRoomHistoryRange('tab-keuangan', 'realtime', this)">
                                <i class="ti ti-circle-filled text-danger" style="font-size: 8px;"></i> Real-time
                            </button>
                            <button class="history-btn" data-range="1h" onclick="setRoomHistoryRange('tab-keuangan', '1h', this)">1 Jam</button>
                            <button class="history-btn" data-range="6h" onclick="setRoomHistoryRange('tab-keuangan', '6h', this)">6 Jam</button>
                            <button class="history-btn" data-range="24h" onclick="setRoomHistoryRange('tab-keuangan', '24h', this)">Hari Ini</button>
                            
                            <span class="text-muted mx-2">|</span>
                            
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-muted small mb-0">Dari:</label>
                                <input type="date" id="historyFrom_tab-keuangan" class="form-control form-control-sm">
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-muted small mb-0">Sampai:</label>
                                <input type="date" id="historyTo_tab-keuangan" class="form-control form-control-sm">
                            </div>
                            <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" onclick="setRoomHistoryDateRange('tab-keuangan')" style="background-color: #3b327b; border-color: #3b327b;">
                                <i class="ti ti-search" style="font-size: 14px;"></i> Terapkan
                            </button>
                            
                            <span id="historyInfo_tab-keuangan" class="ms-auto small text-muted"></span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="background: #3b327b; padding: 12px; border-radius: 10px; color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(59,50,123,0.2);">
                            <i class="ti ti-air-conditioning ti-md"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: #3b327b;">
                                Alat 1 &mdash; Ruang Keuangan (Otomasi AC)
                                <span class="badge bg-label-primary ms-2" style="font-size: 0.72rem;">Kode Alat: ALAT_1 (ID JSON: KEUANGAN)</span>
                            </h5>
                            <small class="text-muted">Otomasi AC berbasis sensor kehadiran | Node: Ruang Keuangan Lt. 1</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="iot-card" id="cardAC">
                                <div class="iot-card-header border-bottom pb-3 mb-3">
                                    <h6 class="iot-card-title"><i class="ti ti-device-remote text-primary fs-4"></i> Otomasi AC &mdash; Ruang Keuangan</h6>
                                    <span class="iot-status normal" id="acAutomasiStatus">Otomasi Aktif</span>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-center border-end">
                                        <div class="text-center p-3">
                                            <div id="acVisual" class="mb-3">
                                                <i class="ti ti-wind" style="font-size: 4.5rem; color: #3b327b;"></i>
                                            </div>
                                            <h4 id="acStatusValue" class="fw-bold mb-0 text-success" style="font-size: 2rem;">OFF</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-8 px-4 py-2">
                                        <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.8rem; letter-spacing: 0.5px;"><i class="ti ti-activity text-danger"></i> Kondisi Ruangan</h6>
                                        
                                        <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
                                            <span class="text-muted fs-6">Temperature</span>
                                            <span class="fw-bold fs-6" id="keuanganTemp">--&deg;C</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
                                            <span class="text-muted fs-6">Humidity</span>
                                            <span class="fw-bold fs-6" id="keuanganHum">--%</span>
                                        </div>
                                        <div class="d-flex justify-content-between pb-2 mb-2">
                                            <span class="text-muted fs-6">Occupancy</span>
                                            <span class="fw-bold text-primary fs-6" id="occupancyValue">0 people</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="iot-meta mt-4 border-top pt-3 text-muted">
                                    <span><i class="ti ti-map-pin me-1"></i>Node: Ruang Keuangan Lt. 1 (Kode: ALAT_1 / KEUANGAN)</span>
                                    <span id="acDuration" class="fw-medium"><i class="ti ti-clock me-1"></i>Nyala: 0j 0m | Mati: 0j 0m</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RECAP KEUANGAN (ALAT 1 KEUANGAN) -->
                    <div class="card mt-1 mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h6 class="card-title mb-0 fw-bold" style="color: #3b327b;">
                                <i class="ti ti-table text-primary me-1"></i> Rekap Harian Alat 1 Ruang Keuangan &mdash; <span id="recapDateKeu">Hari Ini</span>
                                <span class="badge bg-label-primary ms-1">ALAT_1 (KEUANGAN)</span>
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-muted small mb-0">Pilih Tanggal:</label>
                                <input type="date" id="pdfDateKeu" class="form-control form-control-sm" style="width: auto;" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                                <button class="btn btn-success btn-sm d-flex align-items-center gap-1 shadow-sm" id="btnGeneratePDFKeu">
                                    <i class="ti ti-file-download"></i> Export PDF Alat Keuangan
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover recap-table m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Status / Durasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><i class="ti ti-clock-play text-success me-2 fs-5"></i> Total AC Menyala</td>
                                        <td id="recapAcOnKeu" class="fw-bold text-success fs-6">0j 0m</td>
                                    </tr>
                                    <tr>
                                        <td><i class="ti ti-clock-pause text-secondary me-2 fs-5"></i> Total AC Mati</td>
                                        <td id="recapAcOffKeu" class="fw-bold text-secondary fs-6">0j 0m</td>
                                    </tr>
                                    <tr>
                                        <td><i class="ti ti-temperature text-danger me-2 fs-5"></i> Rata-rata Suhu Ruangan</td>
                                        <td id="recapAvgSuhuKeu" class="fw-bold fs-6">-- &deg;C</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ==========================================================
                         ALAT 2 — RUANG KEUANGAN (SENSOR INTENSITAS CAHAYA & SERVO)
                         ========================================================== -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 mt-4">
                        <div class="d-flex align-items-center gap-3">
                            <div style="background: #3b327b; padding: 12px; border-radius: 10px; color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(59,50,123,0.2);">
                                <i class="ti ti-bulb ti-md"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #3b327b;">
                                    Alat 2 &mdash; Ruang Keuangan (Sensor Intensitas Cahaya &amp; Saklar Servo)
                                    <span class="badge bg-label-primary ms-2" style="font-size: 0.72rem;">Kode Alat: ALAT_2_CAHAYA (ID JSON: KEUANGAN_CAHAYA)</span>
                                    <span class="badge bg-label-warning ms-1" style="font-size: 0.72rem;">Tahap Perakitan</span>
                                </h5>
                                <small class="text-muted">Mendeteksi intensitas cahaya (Lumens) &amp; menggerakkan motor Servo pada saklar lampu (Otomatis / Switch Manual) | Node: Saklar Lampu Ruang Keuangan</small>
                            </div>
                        </div>
                    </div>

                    <!-- BANNER STATUS PENCAHAYAAN & SERVO -->
                    <div class="alert-banner safe" id="alertCahayaBanner">
                        <div style="margin-top: 2px;"><i class="ti ti-sun-high ti-lg"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold" id="alertCahayaTitle">Intensitas Cahaya Ruangan Ideal &mdash; Lampu Menyala Normal</h6>
                            <small id="alertCahayaDesc">Batas Gelap: &lt; 250 lm (Servo Hidupkan Lampu) | Batas Terang: &gt; 500 lm (Servo Matikan Sebagian Lampu) | Saat ini: <strong id="alertCahayaCurrent">380 lm</strong></small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- KARTU KONTROL SERVO SAKLAR LAMPU & SWITCH MANUAL ON/OFF -->
                        <div class="col-md-7">
                            <div class="iot-card" id="cardServoLampu">
                                <div class="iot-card-header border-bottom pb-3 mb-3">
                                    <h6 class="iot-card-title"><i class="ti ti-toggle-right text-warning fs-4"></i> Saklar Lampu (Penggerak Servo) &mdash; Ruang Keuangan</h6>
                                    <span class="iot-status normal" id="servoModeBadge">Mode: Otomatis Sensor</span>
                                </div>

                                <div class="row align-items-center">
                                    <!-- Visualisasi Lampu & Posisi Servo -->
                                    <div class="col-md-5 d-flex align-items-center justify-content-center border-end">
                                        <div class="text-center p-3">
                                            <div id="lampVisualWrap" class="mb-2">
                                                <i class="ti ti-bulb-filled" id="lampVisualIcon" style="font-size: 4.5rem; color: #f59e0b; filter: drop-shadow(0 0 12px rgba(245, 158, 11, 0.45)); transition: all 0.3s ease;"></i>
                                            </div>
                                            <h4 id="lampStatusValue" class="fw-bold mb-1 text-success" style="font-size: 1.5rem;">LAMPU ON</h4>
                                            <div class="badge bg-label-primary mt-1" id="servoAngleBadge" style="font-size: 0.78rem;">
                                                <i class="ti ti-settings-automation me-1"></i>Servo: 90&deg; (Tekan ON)
                                            </div>
                                            <div class="small text-muted mt-2" id="lampActionReason">Cahaya ruangan ideal / dibutuhkan</div>
                                        </div>
                                    </div>

                                    <!-- Panel Switch Manual ON/OFF & Mode Kerja -->
                                    <div class="col-md-7 px-4 py-2">
                                        <h6 class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.78rem; letter-spacing: 0.5px;">
                                            <i class="ti ti-adjustments-horizontal text-primary"></i> Kontrol Mode &amp; Switch Manual
                                        </h6>

                                        <!-- Pilihan Mode: Otomatis Sensor vs Manual Web -->
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                            <span class="text-muted small fw-semibold">Mode Kerja Alat:</span>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-primary" id="btnModeAutoKeu" onclick="setLightServoMode('ALAT_2_CAHAYA', 'auto')" style="background-color: #3b327b; border-color: #3b327b;">
                                                    <i class="ti ti-Cpu me-1"></i>Otomatis Sensor
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary" id="btnModeManualKeu" onclick="setLightServoMode('ALAT_2_CAHAYA', 'manual')">
                                                    <i class="ti ti-hand-click me-1"></i>Manual Switch
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Switch Manual ON / OFF Penggerak Servo -->
                                        <div class="p-3 rounded border mb-3" style="background: #f8fafc;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <div class="fw-bold" style="color: #3b327b; font-size: 0.9rem;">Switch Saklar Lampu (Servo)</div>
                                                    <small class="text-muted" id="manualSwitchHint">Klik untuk menggerakkan Servo ON/OFF secara manual</small>
                                                </div>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="switchManualLampKeu" checked style="width: 3rem; height: 1.5rem; cursor: pointer;" onchange="toggleLightServoManual('ALAT_2_CAHAYA', this.checked)">
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 mt-2">
                                                <button type="button" class="btn btn-sm btn-success flex-fill d-flex align-items-center justify-content-center gap-1" id="btnForceLampOnKeu" onclick="toggleLightServoManual('ALAT_2_CAHAYA', true)">
                                                    <i class="ti ti-bulb"></i> Hidupkan (Servo ON)
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger flex-fill d-flex align-items-center justify-content-center gap-1" id="btnForceLampOffKeu" onclick="toggleLightServoManual('ALAT_2_CAHAYA', false)">
                                                    <i class="ti ti-bulb-off"></i> Matikan Sebagian (Servo OFF)
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Simulasi Uji Cahaya (Untuk Demo Tahap Perakitan) -->
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted fw-semibold"><i class="ti ti-flask me-1"></i>Uji Respons Sensor Cahaya (Demo Perakitan):</small>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                <button type="button" class="btn btn-xs btn-outline-dark py-1 px-2" style="font-size: 0.74rem;" onclick="simulateLumensCondition('ALAT_2_CAHAYA', 140)">
                                                    🌑 Gelap (140 lm &rarr; Lampu ON)
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2" style="font-size: 0.74rem;" onclick="simulateLumensCondition('ALAT_2_CAHAYA', 380)">
                                                    ⛅ Ideal (380 lm)
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-warning py-1 px-2" style="font-size: 0.74rem;" onclick="simulateLumensCondition('ALAT_2_CAHAYA', 640)">
                                                    ☀️ Terlalu Terang (640 lm &rarr; Lampu OFF)
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="iot-meta mt-3 border-top pt-3 text-muted">
                                    <span><i class="ti ti-map-pin me-1"></i>Node: Dekat Saklar Lampu R. Keuangan (Kode: ALAT_2_CAHAYA)</span>
                                    <span id="servoActionCounter" class="fw-medium"><i class="ti ti-rotate-clockwise me-1"></i>Aksi Servo Hari Ini: 4x</span>
                                </div>
                            </div>
                        </div>

                        <!-- KARTU GRAFIK SENSOR INTENSITAS CAHAYA (LUMENS) -->
                        <div class="col-md-5">
                            <div class="iot-card" id="cardLumensKeu">
                                <div class="iot-card-header">
                                    <h6 class="iot-card-title"><i class="ti ti-sun text-warning fs-4"></i> Intensitas Cahaya</h6>
                                    <span class="iot-status normal" id="cahayaStatusKeu">Ideal</span>
                                </div>
                                <div class="iot-value-display">
                                    <span class="iot-big-value" id="cahayaValueKeu">380</span>
                                    <span class="iot-unit">Lumens (lm)</span>
                                </div>
                                <div><canvas id="chartCahayaKeu" style="width: 100%; height: 175px; display: block;"></canvas></div>
                                <div class="iot-meta mt-2">
                                    <span>Ambang: &lt;250 lm (ON) | &gt;500 lm (OFF)</span>
                                    <span>Kode: ALAT_2_CAHAYA</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- REKAP HARIAN & EXPORT PDF KHUSUS ALAT 2 RUANG KEUANGAN (SENSOR CAHAYA & SERVO) -->
                    <div class="card mt-1 mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h6 class="card-title mb-0 fw-bold" style="color: #3b327b;">
                                <i class="ti ti-table text-primary me-1"></i> Rekap Harian Alat 2 Ruang Keuangan (Sensor Cahaya &amp; Servo) &mdash; <span id="recapDateKeuCahaya">Hari Ini</span>
                                <span class="badge bg-label-primary ms-1">ALAT_2_CAHAYA</span>
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-muted small mb-0">Pilih Tanggal:</label>
                                <input type="date" id="pdfDateKeuCahaya" class="form-control form-control-sm" style="width: auto;" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                                <button class="btn btn-success btn-sm d-flex align-items-center gap-1 shadow-sm" id="btnGeneratePDFKeuCahaya">
                                    <i class="ti ti-file-download"></i> Export PDF Alat 2 Cahaya
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover recap-table m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Rata-rata / Status</th>
                                        <th>Min</th>
                                        <th>Max</th>
                                        <th>Sampel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><i class="ti ti-sun text-warning me-2"></i>Intensitas Cahaya (Lumens)</td>
                                        <td id="recapAvgLumensKeu">380 lm</td>
                                        <td id="recapMinLumensKeu">140 lm</td>
                                        <td id="recapMaxLumensKeu">640 lm</td>
                                        <td id="recapSamplesLumensKeu" rowspan="2" class="align-middle text-center fw-bold fs-4" style="color: #3b327b;">20</td>
                                    </tr>
                                    <tr>
                                        <td><i class="ti ti-bulb text-success me-2"></i>Saklar Lampu (Motor Servo)</td>
                                        <td id="recapServoStatusKeu">Lampu ON (Servo 90&deg;)</td>
                                        <td id="recapServoModeKeu">Otomatis Sensor</td>
                                        <td id="recapServoActionsKeu">4x Penggerak</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted small">
                                <span class="badge bg-label-warning me-1"><i class="ti ti-bulb"></i></span>
                                Penghematan Lampu Otomatis (Saat Terlalu Terang &gt; 500 lm): <strong id="recapLightOffCountKeu">2</strong> kali dimatikan sebagian
                            </div>
                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" id="btnGeneratePDFKeuAll">
                                <i class="ti ti-files"></i> Export PDF Gabungan Keuangan (Alat 1 &amp; 2)
                            </button>
                        </div>
                    </div>

                    <!-- DYNAMIC DEVICES ADDED TO RUANG KEUANGAN -->
                    <div id="customDevices_keuangan"></div>

                </div> <!-- End of Tab Keuangan -->

            </div> <!-- End of Tab Content -->

            </div> <!-- / #section-akomodasi -->

            <!-- ===================================================================== -->
            <!-- SECTION 2: PERENCANAAN LABORATORIUM (RENLAK) — MINIMALIST ENTERPRISE UI -->
            <!-- ===================================================================== -->
            <div id="section-perencanaan" style="display: none;">
                <style>
                    /* Minimalist & Professional Clean Styles */
                    .renlak-header-wrap {
                        padding: 4px 0 16px 0;
                        margin-bottom: 8px;
                        border-bottom: 1px solid #eef2f6;
                    }
                    .renlak-stat-strip {
                        background: #ffffff;
                        border: 1px solid #e2e8f0;
                        border-radius: 10px;
                        padding: 14px 18px;
                        margin-bottom: 14px;
                    }
                    .renlak-stat-col {
                        position: relative;
                    }
                    @media (min-width: 768px) {
                        .renlak-stat-col:not(:last-child)::after {
                            content: '';
                            position: absolute;
                            right: 0;
                            top: 15%;
                            height: 70%;
                            width: 1px;
                            background: #eef2f6;
                        }
                    }
                    .renlak-stat-label {
                        font-size: 0.72rem;
                        font-weight: 600;
                        color: #64748b;
                        text-transform: uppercase;
                        letter-spacing: 0.4px;
                        margin-bottom: 2px;
                    }
                    .renlak-stat-value {
                        font-size: 1.35rem;
                        font-weight: 700;
                        color: #0f172a;
                        line-height: 1.2;
                    }
                    .renlak-filter-bar {
                        background: #f8fafc;
                        border: 1px solid #e2e8f0;
                        border-radius: 8px;
                        padding: 8px 12px;
                        margin-bottom: 14px;
                    }
                    /* Minimal Tabs */
                    .renlak-minimal-tabs {
                        display: flex;
                        gap: 4px;
                        border-bottom: 1px solid #e2e8f0;
                        margin-bottom: 12px;
                    }
                    .renlak-tab-link {
                        background: transparent;
                        border: none;
                        border-bottom: 2px solid transparent;
                        color: #64748b;
                        font-weight: 600;
                        font-size: 0.84rem;
                        padding: 8px 14px;
                        cursor: pointer;
                        transition: color 0.15s ease, border-color 0.15s ease;
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                    }
                    .renlak-tab-link:hover {
                        color: #034284;
                    }
                    .renlak-tab-link.active {
                        color: #034284;
                        border-bottom-color: #034284;
                    }
                    /* Matrix Table */
                    .renlak-matrix-card {
                        background: #ffffff;
                        border: 1px solid #e2e8f0;
                        border-radius: 8px;
                        overflow: hidden;
                    }
                    .renlak-matrix-scroll {
                        overflow-x: auto;
                        overflow-y: auto;
                        max-height: 680px;
                        position: relative;
                        scroll-behavior: smooth;
                    }
                    .renlak-table {
                        border-collapse: separate;
                        border-spacing: 0;
                        width: max-content;
                        min-width: 100%;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                        font-size: 12px;
                        color: #1e293b;
                    }
                    .renlak-table th,
                    .renlak-table td {
                        border-right: 1px solid #e2e8f0;
                        border-bottom: 1px solid #e2e8f0;
                        padding: 6px 8px;
                        vertical-align: middle;
                    }
                    .renlak-table thead th {
                        position: sticky;
                        z-index: 15;
                        background: #f8fafc;
                        color: #475569;
                        font-weight: 600;
                        text-align: center;
                        white-space: nowrap;
                    }
                    .renlak-table thead tr.renlak-hdr-row-1 th {
                        top: 0;
                        height: 28px;
                    }
                    .renlak-table thead tr.renlak-hdr-row-2 th {
                        top: 28px;
                        height: 28px;
                    }
                    .renlak-table thead tr.renlak-hdr-row-3 th {
                        top: 56px;
                        height: 24px;
                    }
                    /* Sticky Left Columns */
                    .renlak-sticky-1 {
                        position: sticky !important;
                        left: 0;
                        width: 44px;
                        min-width: 44px;
                        max-width: 44px;
                        z-index: 12;
                        background: #ffffff;
                    }
                    .renlak-sticky-2 {
                        position: sticky !important;
                        left: 44px;
                        width: 270px;
                        min-width: 270px;
                        max-width: 270px;
                        z-index: 12;
                        background: #ffffff;
                        border-right: 2px solid #cbd5e1 !important;
                    }
                    .renlak-table thead .renlak-sticky-1,
                    .renlak-table thead .renlak-sticky-2 {
                        z-index: 25 !important;
                        background: #f1f5f9 !important;
                    }
                    /* Clean Category Row (Refined corporate style) */
                    .renlak-category-row td {
                        background: #f8fafc !important;
                        color: #0f172a !important;
                        font-weight: 700;
                        font-size: 11.5px;
                        letter-spacing: 0.3px;
                        padding: 6px 10px !important;
                        border-top: 1px solid #cbd5e1;
                        border-bottom: 1px solid #cbd5e1;
                    }
                    .renlak-category-row td.renlak-sticky-1,
                    .renlak-category-row td.renlak-sticky-2 {
                        background: #f8fafc !important;
                    }
                    .renlak-cell-capaian {
                        background: #f0fdf4;
                        font-weight: 600;
                        text-align: center;
                        color: #15803d;
                    }
                    .renlak-wk-cell {
                        width: 24px;
                        min-width: 24px;
                        max-width: 24px;
                        height: 28px;
                        text-align: center;
                        font-weight: 600;
                        font-size: 11px;
                        padding: 0 !important;
                        cursor: pointer;
                        user-select: none;
                        transition: background-color 0.12s ease;
                    }
                    .renlak-wk-cell:hover {
                        outline: 1px solid #034284;
                        z-index: 5;
                    }
                    /* Minimalist Clean Colors for Weeks */
                    .wk-empty-plan {
                        background: #ffffff;
                        color: #cbd5e1;
                    }
                    .wk-empty-real {
                        background: #ffffff;
                        color: #cbd5e1;
                    }
                    .wk-empty-gantt {
                        background: #ffffff;
                        color: #e2e8f0;
                    }
                    .wk-active-plan {
                        background: #3b82f6 !important;
                        color: #ffffff !important;
                    }
                    .wk-active-plan-excel {
                        background: #3b82f6 !important;
                        color: #ffffff !important;
                    }
                    .wk-active-real {
                        background: #10b981 !important;
                        color: #ffffff !important;
                    }
                    .wk-active-real-excel {
                        background: #10b981 !important;
                        color: #ffffff !important;
                    }
                    .wk-active-both {
                        background: #0284c7 !important;
                        color: #ffffff !important;
                    }
                    .wk-active-late {
                        background: #ef4444 !important;
                        color: #ffffff !important;
                    }
                    .wk-active-ongoing {
                        background: #f59e0b !important;
                        color: #ffffff !important;
                    }
                    /* Status Badges */
                    .badge-status-renlak {
                        font-size: 11px;
                        font-weight: 500;
                        padding: 3px 8px;
                        border-radius: 6px;
                        display: inline-flex;
                        align-items: center;
                        gap: 4px;
                        white-space: nowrap;
                    }
                    .status-selesai {
                        background: #f0fdf4;
                        color: #166534;
                        border: 1px solid #bbf7d0;
                    }
                    .status-berjalan {
                        background: #fffbeb;
                        color: #92400e;
                        border: 1px solid #fde68a;
                    }
                    .status-belum {
                        background: #f8fafc;
                        color: #475569;
                        border: 1px solid #e2e8f0;
                    }
                    .status-terlambat {
                        background: #fef2f2;
                        color: #991b1b;
                        border: 1px solid #fecaca;
                    }
                    /* Schedule Picker in Modal */
                    .renlak-picker-month-box {
                        border: 1px solid #e2e8f0;
                        border-radius: 6px;
                        padding: 8px;
                        background: #ffffff;
                    }
                    .renlak-picker-wk-btn {
                        width: 28px;
                        height: 26px;
                        border: 1px solid #e2e8f0;
                        background: #f8fafc;
                        border-radius: 4px;
                        font-size: 10.5px;
                        font-weight: 600;
                        color: #334155;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        padding: 0;
                    }
                    .renlak-picker-wk-btn.selected-plan {
                        background: #2563eb;
                        color: #ffffff;
                        border-color: #1d4ed8;
                    }
                    .renlak-picker-wk-btn.selected-real {
                        background: #10b981;
                        color: #ffffff;
                        border-color: #059669;
                    }
                </style>

                <!-- 1. TOP HEADER: MINIMALIST & PROFESSIONAL -->
                <div class="renlak-header-wrap">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.3px; font-size: 1.25rem;">
                                    Perencanaan Laboratorium (RENLAK)
                                </h4>
                                <span class="badge bg-light text-secondary border fw-medium px-2 py-1" style="font-size: 0.72rem;">2026 – 2027</span>
                            </div>
                            <p class="text-muted mb-0 small">
                                Matriks rencana kerja tahunan, jadwal mingguan, dan pemantauan capaian laboratorium BBPOM di Bandung.
                            </p>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="text-muted small me-1 d-none d-sm-inline">
                                <i class="ti ti-calendar me-1"></i>Acuan: <strong class="text-dark" id="renlakRefDateLabel">29 Okt 2026</strong>
                            </span>
                            <button type="button" class="btn btn-primary btn-sm px-3 shadow-none fw-medium" onclick="openRenlakModal()">
                                <i class="ti ti-plus me-1"></i> Tambah Kegiatan
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" onclick="exportRenlakPdf()">
                                <i class="ti ti-file-text me-1"></i> Ekspor PDF
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" onclick="printRenlakReport()">
                                <i class="ti ti-printer me-1"></i> Cetak
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" onclick="resetRenlakToExcelDefault()" title="Muat Ulang Data Standar">
                                <i class="ti ti-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. STATISTIK KPI SUMMARY (CLEAN MINIMALIST STRIP) -->
                <div class="renlak-stat-strip">
                    <div class="row g-3 align-items-center">
                        <div class="col-6 col-md-3 col-xl-2 renlak-stat-col">
                            <div class="renlak-stat-label">Total Kegiatan</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="renlak-stat-value" id="renlakKpiTotalKegiatan">11</span>
                                <small class="text-muted" id="renlakKpiKategoriSub">3 Kategori</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl-2 renlak-stat-col">
                            <div class="renlak-stat-label">Target Output</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="renlak-stat-value" id="renlakKpiTotalTarget">32</span>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl-2 renlak-stat-col">
                            <div class="renlak-stat-label">Realisasi</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="renlak-stat-value text-success" id="renlakKpiTotalRealisasi">6</span>
                                <small class="text-muted">Tercapai</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-3 renlak-stat-col">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="renlak-stat-label mb-0">Rata-rata Capaian</span>
                                <span class="fw-bold text-dark" id="renlakKpiRataCapaian">34.85%</span>
                            </div>
                            <div class="progress" style="height: 5px; background: #e2e8f0; border-radius: 3px;">
                                <div id="renlakKpiProgressBar" class="progress-bar bg-primary" role="progressbar" style="width: 34.85%;"></div>
                            </div>
                            <small class="text-muted mt-1 d-block" id="renlakKpiBobotCapaian">Bobot Volume: 18.75%</small>
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="renlak-stat-label mb-1">Status Kegiatan</div>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-success border fw-medium px-2 py-1"><span id="renlakStatSelesai">3</span> Selesai</span>
                                <span class="badge bg-light text-warning border fw-medium px-2 py-1"><span id="renlakStatBerjalan">2</span> Berjalan</span>
                                <span class="badge bg-light text-secondary border fw-medium px-2 py-1"><span id="renlakStatBelum">6</span> Belum</span>
                                <span class="badge bg-light text-danger border fw-medium px-2 py-1"><span id="renlakStatTerlambat">0</span> Terlambat</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. FILTER & TOOLBAR (CLEAN, COMPACT) -->
                <div class="renlak-filter-bar">
                    <div class="row g-2 align-items-center">
                        <!-- Pencarian -->
                        <div class="col-12 col-md-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="ti ti-search"></i></span>
                                <input type="text" id="renlakFilterSearch" class="form-control border-start-0" placeholder="Cari kegiatan atau sasaran..." oninput="applyRenlakFilters()">
                            </div>
                        </div>
                        <!-- Filter Kategori -->
                        <div class="col-6 col-md-2">
                            <select id="renlakFilterKategori" class="form-select form-select-sm" onchange="applyRenlakFilters()">
                                <option value="ALL">Semua Kategori</option>
                                <option value="Perencanaan">1. Perencanaan</option>
                                <option value="Pengembangan Digitalisasi LAB">2. Pengembangan Digitalisasi</option>
                                <option value="Monitoring dan Evaluasi">3. Monitoring &amp; Evaluasi</option>
                            </select>
                        </div>
                        <!-- Filter Status -->
                        <div class="col-6 col-md-2">
                            <select id="renlakFilterStatus" class="form-select form-select-sm" onchange="applyRenlakFilters()">
                                <option value="ALL">Semua Status</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Berjalan">Berjalan</option>
                                <option value="Belum Dimulai">Belum Dimulai</option>
                                <option value="Terlambat">Terlambat</option>
                            </select>
                        </div>
                        <!-- Filter Tahun -->
                        <div class="col-6 col-md-1">
                            <select id="renlakFilterTahun" class="form-select form-select-sm" onchange="applyRenlakFilters()">
                                <option value="ALL" selected>2026-27</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                            </select>
                        </div>
                        <!-- Filter Periode Bulan -->
                        <div class="col-6 col-md-2">
                            <select id="renlakFilterPeriode" class="form-select form-select-sm" onchange="applyRenlakFilters()">
                                <option value="EXCEL_DEFAULT" selected>Periode Aktif (Sep '26 - Des '27)</option>
                                <option value="FULL_JAN_DEC">Penuh (Jan '26 - Des '27)</option>
                                <option value="Q1">Triwulan I</option>
                                <option value="Q2">Triwulan II</option>
                                <option value="Q3">Triwulan III</option>
                                <option value="Q4">Triwulan IV</option>
                                <option value="M_1">Januari</option>
                                <option value="M_2">Februari</option>
                                <option value="M_3">Maret</option>
                                <option value="M_4">April</option>
                                <option value="M_5">Mei</option>
                                <option value="M_6">Juni</option>
                                <option value="M_7">Juli</option>
                                <option value="M_8">Agustus</option>
                                <option value="M_9">September</option>
                                <option value="M_10">Oktober</option>
                                <option value="M_11">November</option>
                                <option value="M_12">Desember</option>
                            </select>
                        </div>
                        <!-- Mode Timeline & Jumlah Minggu -->
                        <div class="col-12 col-md-2">
                            <div class="d-flex gap-1">
                                <select id="renlakTimelineMode" class="form-select form-select-sm" onchange="renderRenlakAll()">
                                    <option value="EXCEL_SPLIT" selected>Matriks Split</option>
                                    <option value="GANTT_UNIFIED">Gantt Terpadu</option>
                                </select>
                                <select id="renlakWeeksPerMonth" class="form-select form-select-sm" style="max-width: 68px;" onchange="renderRenlakAll()">
                                    <option value="4" selected>4 Mg</option>
                                    <option value="5">5 Mg</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. SUB-NAVIGATION TABS (MINIMALIST TABS & QUIET LEGEND) -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                    <div class="renlak-minimal-tabs mb-0">
                        <button type="button" class="renlak-tab-link active" id="btnRenlakTabMatrix" onclick="switchRenlakWorkspaceTab('matrix')">
                            Tabel &amp; Timeline Gantt
                        </button>
                        <button type="button" class="renlak-tab-link" id="btnRenlakTabMonev" onclick="switchRenlakWorkspaceTab('monev')">
                            Monitoring &amp; Evaluasi
                        </button>
                        <button type="button" class="renlak-tab-link" id="btnRenlakTabHistory" onclick="switchRenlakWorkspaceTab('history')">
                            Riwayat Perubahan (<span id="renlakAuditCountBadge">0</span>)
                        </button>
                    </div>

                    <!-- Legend & Horizontal Scroll Controls -->
                    <div class="d-flex flex-wrap align-items-center gap-2 small text-muted" id="renlakMatrixLegendBar">
                        <span class="d-inline-flex align-items-center gap-1" style="font-size: 0.76rem;">
                            <span style="width: 9px; height: 9px; background: #3b82f6; border-radius: 2px; display: inline-block;"></span> Rencana
                        </span>
                        <span class="d-inline-flex align-items-center gap-1" style="font-size: 0.76rem;">
                            <span style="width: 9px; height: 9px; background: #10b981; border-radius: 2px; display: inline-block;"></span> Realisasi
                        </span>
                        <span class="d-inline-flex align-items-center gap-1" style="font-size: 0.76rem;">
                            <span style="width: 9px; height: 9px; background: #f59e0b; border-radius: 2px; display: inline-block;"></span> Berjalan
                        </span>
                        <span class="d-inline-flex align-items-center gap-1" style="font-size: 0.76rem;">
                            <span style="width: 9px; height: 9px; background: #ef4444; border-radius: 2px; display: inline-block;"></span> Terlambat
                        </span>
                        <div class="btn-group btn-group-sm ms-2">
                            <button type="button" class="btn btn-outline-secondary py-0 px-2" onclick="scrollRenlakTimeline(-380)" title="Geser Kiri">&lsaquo;</button>
                            <button type="button" class="btn btn-outline-secondary py-0 px-2" onclick="scrollRenlakTimeline(380)" title="Geser Kanan">&rsaquo;</button>
                        </div>
                    </div>
                </div>

                <!-- ================================================================= -->
                <!-- WORKSPACE TAB 1: TABEL & TIMELINE GANTT CHART (2026 - 2027)       -->
                <!-- ================================================================= -->
                <div id="renlakTabContentMatrix">
                    <div class="renlak-matrix-card">
                        <div class="px-3 py-2 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: #f8fafc;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold" style="color: #034284; font-size: 0.82rem;">
                                    <i class="ti ti-table me-1"></i> MATRIKS RENCANA PELAKSANAAN &amp; REALISASI KEGIATAN LABORATORIUM
                                </span>
                                <span class="badge bg-label-info" style="font-size: 0.7rem;">
                                    <i class="ti ti-hand-click me-1"></i> Klik sel minggu untuk mengisi / mengubah angka jadwal secara cepat
                                </span>
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;" id="renlakShowingInfoLabel">
                                Menampilkan 11 kegiatan
                            </div>
                        </div>

                        <div class="renlak-matrix-scroll" id="renlakTimelineScrollContainer">
                            <table class="renlak-table" id="renlakMainTable">
                                <thead id="renlakMainThead"></thead>
                                <tbody id="renlakMainTbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ================================================================= -->
                <!-- WORKSPACE TAB 2: MONITORING, EVALUASI & GRAFIK PROGRES            -->
                <!-- ================================================================= -->
                <div id="renlakTabContentMonev" style="display: none;">
                    <div class="row g-3 mb-3">
                        <!-- Grafik Perbandingan Target vs Realisasi -->
                        <div class="col-12 col-lg-8">
                            <div class="card border shadow-none h-100" style="border-radius: 12px;">
                                <div class="card-header d-flex align-items-center justify-content-between py-3 px-3 border-bottom">
                                    <div>
                                        <h6 class="fw-bold mb-0" style="color: #034284;">
                                            <i class="ti ti-chart-bar me-1"></i> Grafik Perbandingan Target vs Realisasi Kegiatan
                                        </h6>
                                        <small class="text-muted">Perbandingan langsung jumlah Target, Realisasi, dan Persentase Capaian (%) tiap kegiatan</small>
                                    </div>
                                    <span class="badge bg-label-primary">Real-Time Chart</span>
                                </div>
                                <div class="card-body p-3">
                                    <div style="height: 310px; position: relative;">
                                        <canvas id="chartRenlakTargetRealisasi"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik Distribusi Status & Capaian per Kategori -->
                        <div class="col-12 col-lg-4">
                            <div class="card border shadow-none h-100" style="border-radius: 12px;">
                                <div class="card-header py-3 px-3 border-bottom">
                                    <h6 class="fw-bold mb-0" style="color: #034284;">
                                        <i class="ti ti-chart-donut-3 me-1"></i> Distribusi Status Kegiatan
                                    </h6>
                                    <small class="text-muted">Proporsi kegiatan Belum Dimulai, Berjalan, Selesai &amp; Terlambat</small>
                                </div>
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div style="height: 205px; position: relative;">
                                        <canvas id="chartRenlakStatusPie"></canvas>
                                    </div>
                                    <div id="renlakCategorySummaryBox" class="mt-3 pt-2 border-top"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Monitoring & Evaluasi Cepat (Pengaturan Target, Realisasi & Status) -->
                    <div class="card border shadow-none" style="border-radius: 12px;">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between py-3 px-3 border-bottom">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #034284;">
                                    <i class="ti ti-adjustments-horizontal me-1"></i> Panel Monitoring Realisasi &amp; Evaluasi Capaian Kegiatan
                                </h6>
                                <small class="text-muted">Ubah nilai Realisasi, Target, atau Status secara langsung untuk memperbarui Persentase Capaian otomatis</small>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" onclick="openRenlakModal()">
                                <i class="ti ti-plus me-1"></i> Tambah Kegiatan Baru
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0" style="font-size: 0.82rem;">
                                <thead style="background: #f1f5f9;">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No.</th>
                                        <th style="min-width: 240px;">Nama Kegiatan / Sasaran</th>
                                        <th style="width: 170px;">Kategori</th>
                                        <th class="text-center" style="width: 110px;">Realisasi</th>
                                        <th class="text-center" style="width: 110px;">Target</th>
                                        <th class="text-center" style="width: 90px;">Satuan</th>
                                        <th style="width: 165px;">Persentase Capaian</th>
                                        <th style="min-width: 190px;">Jadwal Pelaksanaan</th>
                                        <th class="text-center" style="width: 145px;">Status Kegiatan</th>
                                        <th class="text-center" style="width: 115px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="renlakMonevTbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ================================================================= -->
                <!-- WORKSPACE TAB 3: RIWAYAT PERUBAHAN KEGIATAN (AUDIT TRAIL)         -->
                <!-- ================================================================= -->
                <div id="renlakTabContentHistory" style="display: none;">
                    <div class="card border shadow-none" style="border-radius: 12px;">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between py-3 px-3 border-bottom">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #034284;">
                                    <i class="ti ti-history me-1"></i> Riwayat Perubahan Kegiatan (Audit Trail RENLAK)
                                </h6>
                                <small class="text-muted">Rekam jejak seluruh penambahan, pembaruan target/realisasi, jadwal minggu, dan penghapusan kegiatan</small>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="loadRenlakData()">
                                <i class="ti ti-refresh me-1"></i> Segarkan Log
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle mb-0" style="font-size: 0.82rem;">
                                <thead style="background: #f1f5f9;">
                                    <tr>
                                        <th style="width: 160px;">Waktu Perubahan</th>
                                        <th style="width: 150px;">Pengguna</th>
                                        <th style="width: 140px;">Aksi</th>
                                        <th style="width: 240px;">Nama Kegiatan</th>
                                        <th>Rincian Perubahan</th>
                                    </tr>
                                </thead>
                                <tbody id="renlakAuditTbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ================================================================= -->
                <!-- MODAL TAMBAH / EDIT KEGIATAN RENLAK                               -->
                <!-- ================================================================= -->
                <div class="modal fade" id="modalRenlakForm" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content" style="border-radius: 14px;">
                            <div class="modal-header" style="background: #034284; color: #ffffff;">
                                <h5 class="modal-title fw-bold text-white" id="modalRenlakFormTitle">
                                    <i class="ti ti-calendar-plus me-1"></i> Tambah Kegiatan RENLAK
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form id="formRenlakActivity" onsubmit="event.preventDefault(); saveRenlakForm();">
                                    <input type="hidden" id="renlakInputId" value="0">

                                    <div class="row g-3 mb-3">
                                        <div class="col-12 col-md-2">
                                            <label class="form-label fw-bold small">Nomor Urut <span class="text-danger">*</span></label>
                                            <input type="number" id="renlakInputNomor" class="form-control" min="1" value="1" required>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-bold small">Kategori Kegiatan <span class="text-danger">*</span></label>
                                            <input type="text" id="renlakInputKategori" class="form-control" list="renlakKategoriOptions" placeholder="Pilih atau ketik kategori..." required>
                                            <datalist id="renlakKategoriOptions">
                                                <option value="Perencanaan"></option>
                                                <option value="Pengembangan Digitalisasi LAB"></option>
                                                <option value="Monitoring dan Evaluasi"></option>
                                            </datalist>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label fw-bold small">Nama Kegiatan / Sasaran <span class="text-danger">*</span></label>
                                            <input type="text" id="renlakInputNama" class="form-control" placeholder="Contoh: Pembentukan Tim Digitalisasi LAB" required>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-6 col-md-2">
                                            <label class="form-label fw-bold small">Realisasi <span class="text-danger">*</span></label>
                                            <input type="number" step="any" min="0" id="renlakInputRealisasi" class="form-control fw-bold" value="0" oninput="recalcRenlakModalPercentage()" required>
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label class="form-label fw-bold small">Target <span class="text-danger">*</span></label>
                                            <input type="number" step="any" min="0.01" id="renlakInputTarget" class="form-control fw-bold" value="1" oninput="recalcRenlakModalPercentage()" required>
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label class="form-label fw-bold small">Satuan <span class="text-danger">*</span></label>
                                            <input type="text" id="renlakInputSatuan" class="form-control" list="renlakSatuanOptions" placeholder="SK / Dokumen / Fitur" required>
                                            <datalist id="renlakSatuanOptions">
                                                <option value="SK"></option>
                                                <option value="Dokumen"></option>
                                                <option value="Database"></option>
                                                <option value="Aplikasi"></option>
                                                <option value="Fitur"></option>
                                                <option value="Persen"></option>
                                                <option value="Kegiatan"></option>
                                                <option value="Laporan"></option>
                                            </datalist>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label fw-bold small">% Capaian (Otomatis)</label>
                                            <div class="input-group">
                                                <input type="text" id="renlakInputPersenPreview" class="form-control fw-bold text-success" style="background: #dcfce7;" value="0.00%" readonly>
                                                <span class="input-group-text bg-success text-white fw-bold"><i class="ti ti-percentage"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label class="form-label fw-bold small">Status Kegiatan</label>
                                            <select id="renlakInputStatus" class="form-select fw-semibold">
                                                <option value="AUTO">Otomatis (Sesuai Realisasi &amp; Jadwal)</option>
                                                <option value="Belum Dimulai">Belum Dimulai</option>
                                                <option value="Berjalan">Berjalan</option>
                                                <option value="Selesai">Selesai</option>
                                                <option value="Terlambat">Terlambat</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label fw-bold small">Rencana Kegiatan / Deskripsi Uraian</label>
                                            <textarea id="renlakInputRencanaText" class="form-control" rows="2" placeholder="Uraian rencana pelaksanaan kegiatan..."></textarea>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label class="form-label fw-bold small">Penanggung Jawab (PIC)</label>
                                            <input type="text" id="renlakInputPic" class="form-control" placeholder="Contoh: Tim Digitalisasi Lab">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label class="form-label fw-bold small">Catatan Monitoring &amp; Evaluasi</label>
                                            <textarea id="renlakInputCatatan" class="form-control" rows="2" placeholder="Catatan evaluasi / kendala..."></textarea>
                                        </div>
                                    </div>

                                    <!-- Interactive Weekly Schedule Builder (2026 & 2027) -->
                                    <div class="border rounded-3 p-3" style="background: #f8fafc;">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom">
                                            <div>
                                                <h6 class="fw-bold mb-0" style="color: #034284;">
                                                    <i class="ti ti-calendar-week me-1"></i> Pengaturan Jadwal Pelaksanaan Mingguan (Tahun 2026 &amp; 2027)
                                                </h6>
                                                <small class="text-muted">Klik tombol minggu (M1&ndash;M5) pada bulan terkait untuk menandai/menghapus jadwal <strong>Rencana</strong> maupun <strong>Realisasi</strong>.</small>
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-primary active" id="btnPickerModePlan" onclick="switchModalScheduleMode('plan')">
                                                        <i class="ti ti-calendar-event me-1"></i> Edit Jadwal RENCANA
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" id="btnPickerModeReal" onclick="switchModalScheduleMode('real')">
                                                        <i class="ti ti-checks me-1"></i> Edit Jadwal REALISASI
                                                    </button>
                                                </div>
                                                <select id="modalScheduleYearSelect" class="form-select form-select-sm" style="width: 130px;" onchange="renderModalSchedulePickerGrid()">
                                                    <option value="2026">Tahun 2026</option>
                                                    <option value="2027">Tahun 2027</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="modalSchedulePickerContainer" class="row g-2"></div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-primary fw-bold px-4" onclick="saveRenlakForm()">
                                    <i class="ti ti-device-floppy me-1"></i> Simpan Kegiatan RENLAK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================================================================= -->
                <!-- MODAL DETAIL KEGIATAN RENLAK                                      -->
                <!-- ================================================================= -->
                <div class="modal fade" id="modalRenlakDetail" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 14px;">
                            <div class="modal-header" style="background: #034284; color: #ffffff;">
                                <h5 class="modal-title fw-bold text-white">
                                    <i class="ti ti-file-analytics me-1"></i> Detail Kegiatan &amp; Evaluasi RENLAK
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4" id="modalRenlakDetailBody"></div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-primary" id="btnDetailEditRenlak">
                                    <i class="ti ti-edit me-1"></i> Edit Kegiatan Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- / #section-perencanaan -->

            <!-- Sub-Pages Navigation Bar khusus Menu 3: Pelaksanaan Pengujian Sampel -->
            <div id="pengujianSubNavBar" class="subpage-nav-bar" style="display: none;">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom">
                    <div>
                        <h5 class="fw-bold mb-0" style="color: #034284;">
                            <i class="ti ti-flask-2 me-1"></i> Pelaksanaan Pengujian Sampel
                        </h5>
                        <small class="text-muted">Pilih sub-halaman modul pelaksanaan pengujian laboratorium di bawah ini:</small>
                    </div>
                    <span class="badge bg-label-primary">6 Sub-Halaman Modul</span>
                </div>
                <div class="d-flex flex-wrap gap-2 pt-1">
                    <button type="button" class="subpage-pill-btn" id="pill-pengujian-sampel" onclick="switchPengujianSubPage('sampel')">
                        <i class="ti ti-package"></i> 1. Pengelolaan Sampel
                    </button>
                    <button type="button" class="subpage-pill-btn" id="pill-pengujian-status" onclick="switchPengujianSubPage('status')">
                        <i class="ti ti-timeline-event"></i> 2. Status Pengujian
                    </button>
                    <button type="button" class="subpage-pill-btn" id="pill-pengujian-lims" onclick="switchPengujianSubPage('lims')">
                        <i class="ti ti-device-desktop-analytics"></i> 3. Penerapan LIMS
                    </button>
                    <button type="button" class="subpage-pill-btn" id="pill-pengujian-reagen" onclick="switchPengujianSubPage('reagen')">
                        <i class="ti ti-flask"></i> 4. Pengelolaan Reagen
                    </button>
                    <button type="button" class="subpage-pill-btn" id="pill-pengujian-pengesahan" onclick="switchPengujianSubPage('pengesahan')">
                        <i class="ti ti-file-certificate"></i> 5. Pengesahan Hasil Uji
                    </button>
                    <button type="button" class="subpage-pill-btn" id="pill-pengujian-huls" onclick="switchPengujianSubPage('huls')">
                        <i class="ti ti-alert-octagon"></i> 6. HULS
                    </button>
                </div>
            </div>

            <!-- ==========================================================
                 SECTION 3: MODUL DALAM PENGEMBANGAN (MENU 2..8, 10..12)
                 ========================================================== -->
            <div id="section-under-dev" style="display: none;">
                <div class="card border-0 shadow-sm my-3" style="border-radius: 16px;">
                    <div class="card-body text-center py-5 px-4">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 84px; height: 84px; border-radius: 22px; background: linear-gradient(135deg, #034284 0%, #008c55 100%); color: #ffffff; box-shadow: 0 10px 24px rgba(3, 66, 132, 0.22);">
                            <i class="ti ti-settings-automation" id="underDevIcon" style="font-size: 2.6rem;"></i>
                        </div>
                        <span class="badge bg-label-warning mb-2 px-3 py-2" style="font-size: 0.78rem;" id="underDevBadge">
                            <i class="ti ti-Barrier-block me-1"></i> Modul Dalam Pengembangan
                        </span>
                        <h3 class="fw-bold mt-2 mb-2" style="color: #034284;" id="underDevTitle">Nama Modul</h3>
                        <p class="text-muted mx-auto mb-4" style="max-width: 580px; font-size: 0.95rem;" id="underDevDesc">
                            Modul ini sedang dalam tahap pengembangan dan integrasi pada ekosistem <strong>DigitLab BDG &ndash; BBPOM di Bandung</strong>.
                        </p>

                        <div class="mx-auto mb-4 p-3 rounded border bg-light" style="max-width: 480px;">
                            <div class="d-flex justify-content-between small fw-bold mb-1">
                                <span class="text-secondary">Status Integrasi Sistem DigitLab BDG</span>
                                <span class="text-primary">Tahap Pengembangan (20%)</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" class="btn btn-geulis-primary d-flex align-items-center gap-1" onclick="switchMainMenu('dashboard', document.getElementById('nav-dashboard'))">
                                <i class="ti ti-home"></i> Kembali ke Dashboard Utama
                            </button>
                        </div>
                    </div>
                </div>
            </div> <!-- / #section-under-dev -->

            <!-- ==========================================================
                 SECTION 4: SUBMENU 3.6 — HULS (HASIL UJI DI LUAR SPESIFIKASI)
                 (Pre-Investigasi & Investigasi Tahap 1 + Ekspor PDF Persis Word)
                 ========================================================== -->
            <div id="section-huls" style="display: none;">

                <!-- Top Breadcrumb & Main HULS Workspace Tabs -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                    <div class="small fw-semibold text-secondary">
                        <span class="text-primary" style="cursor:pointer;" onclick="switchPengujianSubPage('sampel')">Pelaksanaan Pengujian</span>
                        <span class="mx-1">/</span>
                        <span class="text-primary" style="cursor:pointer;" onclick="switchPengujianSubPage('huls')">6. HULS</span>
                        <span class="mx-1">/</span>
                        <span class="text-dark fw-bold" id="hulsBreadcrumbActiveLabel">Formulir HULS Baru</span>
                    </div>

                    <!-- 3 Tab Utama Terpisah: 1. Formulir Baru | 2. Daftar Dokumen Tersimpan | 3. Riwayat / Audit Trail -->
                    <div class="huls-workspace-tabs">
                        <button type="button" class="huls-workspace-tab-btn active" id="btnMainHulsTabForm" onclick="switchHulsWorkspaceTab('form')">
                            <i class="ti ti-file-plus"></i> Formulir HULS Baru
                        </button>
                        <button type="button" class="huls-workspace-tab-btn" id="btnMainHulsTabList" onclick="switchHulsWorkspaceTab('list')">
                            <i class="ti ti-folders"></i> Daftar Dokumen HULS (<span id="hulsTotalDocCount">0</span>)
                        </button>
                        <button type="button" class="huls-workspace-tab-btn" id="btnMainHulsTabAudit" onclick="switchHulsWorkspaceTab('audit')">
                            <i class="ti ti-history"></i> Riwayat / Audit Trail
                        </button>
                    </div>
                </div>

                <!-- =====================================================
                     WORKSPACE TAB 2: DAFTAR DOKUMEN HULS TERSIMPAN
                     ===================================================== -->
                <div id="hulsMainViewList" style="display: none;">
                    <div class="huls-form-shell">
                        <div class="huls-main-card">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 pb-3 border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: #034284;"><i class="ti ti-database me-1"></i> Daftar Dokumen HULS Tersimpan</h5>
                                    <div class="text-muted small">Semua formulir HULS yang telah disimpan oleh petugas dapat dilihat, dicetak PDF (format Word), atau diedit dari daftar ini.</div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <input type="text" id="hulsSearchInput" class="form-control form-control-sm" style="width: 240px;" placeholder="Cari Kode / No. HULS / Sampel..." oninput="loadHulsDocuments()">
                                    <select id="hulsStatusFilter" class="form-select form-select-sm" style="width: 170px;" onchange="loadHulsDocuments()">
                                        <option value="">Semua Status</option>
                                        <option value="Draft">Draft</option>
                                        <option value="Dalam Verifikasi">Dalam Verifikasi</option>
                                        <option value="Disetujui">Disetujui</option>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-primary fw-bold" style="background:#034284;" onclick="switchHulsWorkspaceTab('form')">
                                        <i class="ti ti-plus me-1"></i> Buat Dokumen Baru
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size: 0.84rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No. HULS</th>
                                            <th>Kode Sampel</th>
                                            <th>Nama Sampel</th>
                                            <th>Parameter Uji / Metode</th>
                                            <th>Penguji</th>
                                            <th>Status</th>
                                            <th>Update Terakhir</th>
                                            <th class="text-end">Aksi Dokumen</th>
                                        </tr>
                                    </thead>
                                    <tbody id="hulsDocTableBody">
                                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada dokumen HULS tersimpan. Silakan isi pada tab <strong>Formulir HULS Baru</strong>.</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Area Pratinjau Dokumen Tersimpan (Muncul saat tombol Lihat / PDF Word diklik pada tabel di atas) -->
                            <div id="hulsSavedDocPreviewBox" class="mt-4 pt-4 border-top" style="display: none;">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 p-3 rounded border bg-light">
                                    <div>
                                        <h6 class="fw-bold mb-0" style="color:#034284;">
                                            <i class="ti ti-file-text me-1"></i> Detail &amp; Cetak Dokumen Tersimpan: <span id="hulsSavedPreviewTitle" class="text-dark"></span>
                                        </h6>
                                        <small class="text-muted">Tampilan dokumen yang telah disimpan dalam format resmi Word BBPOM Bandung:</small>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <select id="hulsSavedPreviewMode" class="form-select form-select-sm fw-bold" style="width: 240px;" onchange="renderSavedDocWordPreview()">
                                            <option value="both">Gabungan (Pre-Investigasi + Tahap 1)</option>
                                            <option value="pre">Hanya HULS Pre-Investigasi</option>
                                            <option value="tahap1">Hanya HULS Investigasi Tahap 1</option>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-primary fw-bold" style="background:#0b4f94;" onclick="downloadSavedHulsPdf()">
                                            <i class="ti ti-download me-1"></i> Unduh PDF (.pdf)
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success fw-bold" style="background:#008c55;" onclick="printSavedHulsWindow()">
                                            <i class="ti ti-printer me-1"></i> Cetak / Save as PDF
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary fw-bold" onclick="document.getElementById('hulsSavedDocPreviewBox').style.display='none'">
                                            <i class="ti ti-x me-1"></i> Tutup Pratinjau
                                        </button>
                                    </div>
                                </div>
                                <div id="hulsSavedWordPrintArea"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =====================================================
                     WORKSPACE TAB 3: RIWAYAT / AUDIT TRAIL HULS
                     ===================================================== -->
                <div id="hulsMainViewAudit" style="display: none;">
                    <div class="huls-form-shell">
                        <div class="huls-main-card">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-3 border-bottom">
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: #034284;"><i class="ti ti-shield-check me-1"></i> Riwayat Perubahan &amp; Audit Trail HULS</h5>
                                    <div class="text-muted small">Rekam jejak waktu pembuatan, pembaruan data, dan petugas laboratorium yang melakukan aksi.</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-semibold" onclick="loadHulsDocuments()">
                                    <i class="ti ti-refresh me-1"></i> Muat Ulang Riwayat
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0" style="font-size: 0.83rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Waktu</th>
                                            <th>No. HULS</th>
                                            <th>Aksi</th>
                                            <th>Petugas (Role)</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="hulsAuditTableBody">
                                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat perubahan.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =====================================================
                     WORKSPACE TAB 1: FORMULIR HULS BARU (DEFAULT AKTIF)
                     ===================================================== -->
                <div id="hulsMainViewForm">
                    <div class="huls-form-shell">
                        <div class="huls-main-card">

                            <!-- Notifikasi Sukses Simpan (Muncul setelah form disimpan & direset jadi form baru) -->
                            <div id="hulsSaveSuccessAlert" class="alert alert-success d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3" style="display: none !important; border-radius: 10px;">
                                <div>
                                    <i class="ti ti-circle-check-filled me-1 fs-5 align-middle"></i>
                                    <span id="hulsSaveSuccessText" class="fw-semibold">Dokumen HULS berhasil disimpan! Formulir ini kini telah disiapkan kembali untuk pengisian dokumen baru.</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-success fw-bold" onclick="switchHulsWorkspaceTab('list')">
                                        <i class="ti ti-folders me-1"></i> Buka Daftar Dokumen Tersimpan
                                    </button>
                                    <button type="button" class="btn-close btn-sm" onclick="hideHulsSaveAlert()"></button>
                                </div>
                            </div>

                            <!-- Banner Indikator jika sedang mengedit dokumen yang dibuka dari Daftar Tersimpan -->
                            <div id="hulsEditingDocBanner" class="alert alert-info d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3" style="display: none !important; border-radius: 10px;">
                                <div>
                                    <i class="ti ti-edit me-1 fs-5 align-middle"></i>
                                    <span>Sedang mengedit Dokumen Tersimpan: <strong id="hulsEditingDocLabel">-</strong>. Setelah disimpan, formulir akan otomatis kembali menjadi formulir baru.</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold bg-white" onclick="cancelEditAndStartNewHuls()">
                                    <i class="ti ti-file-plus me-1"></i> Batal Edit &amp; Kembali ke Form Baru
                                </button>
                            </div>

                            <!-- Header Judul & Status -->
                            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                                <div>
                                    <h4 class="fw-bold mb-1" style="color: #034284;" id="hulsFormMainHeading">Formulir Hasil Uji di Luar Spesifikasi (HULS) — Dokumen Baru</h4>
                                    <div class="text-muted small">Formulir digital Pre-Investigasi &amp; Investigasi Tahap 1 — Laboratorium BBPOM di Bandung</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="hidden" id="hulsCurrentDocId" value="0">
                                    <select id="hulsStatusDokumen" class="form-select form-select-sm fw-bold" style="width: 165px;" onchange="updateHulsStatusBadge()">
                                        <option value="Draft" selected>Status: DRAFT</option>
                                        <option value="Dalam Verifikasi">DALAM VERIFIKASI</option>
                                        <option value="Disetujui">DISETUJUI</option>
                                    </select>
                                    <span id="hulsHeaderBadge" class="badge bg-label-warning px-3 py-2">DRAFT</span>
                                </div>
                            </div>

                            <!-- Sub-Tab Navigation Form: 1. Pre-Investigasi | 2. Investigasi Tahap 1 | 3. Pratinjau & Ekspor PDF Word -->
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-3 border-bottom">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="huls-tab-btn active" id="btnTabHulsPre" onclick="switchHulsFormTab('pre')">
                                        1. Pre-Investigasi HULS
                                    </button>
                                    <button type="button" class="huls-tab-btn" id="btnTabHulsTahap1" onclick="switchHulsFormTab('tahap1')">
                                        2. Investigasi Tahap 1
                                    </button>
                                    <button type="button" class="huls-tab-btn" id="btnTabHulsWordPreview" onclick="switchHulsFormTab('preview')">
                                        <i class="ti ti-file-type-pdf me-1"></i> 3. Pratinjau &amp; Ekspor PDF (Format Word)
                                    </button>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fillHulsPengujiFromLogin()" title="Isi nama penguji otomatis dari akun login">
                                        <i class="ti ti-user-check me-1"></i> Isi Penguji dari Akun
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportHulsWordPdf('current')">
                                        <i class="ti ti-printer me-1"></i> Pratinjau / Cetak PDF
                                    </button>
                                </div>
                            </div>

                            <div class="huls-info-banner">
                                <strong>Petunjuk Pengisian:</strong> Formulir ini digunakan untuk mengisi <strong>Dokumen HULS Baru</strong>. Setelah Anda klik <strong>Simpan Dokumen HULS</strong>, data akan tersimpan ke tab <em>Daftar Dokumen HULS</em> dan formulir ini otomatis siap kembali untuk pengisian dokumen berikutnya.
                            </div>

                        <!-- =====================================================
                             TAB 1: FORM PRE-INVESTIGASI HULS
                             ===================================================== -->
                        <div id="hulsTabContentPre">

                            <!-- BAGIAN I: INFORMASI PENGUJIAN -->
                            <div class="huls-section-box">
                                <div class="huls-section-box-header">
                                    <div class="huls-section-title">I. Informasi Pengujian</div>
                                    <span class="text-muted small">Data identitas sampel &amp; hasil pengujian awal</span>
                                </div>
                                <div class="huls-section-box-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="huls-label">Nama Sampel <span class="text-danger">*</span></label>
                                            <input type="text" id="pre_nama_sampel" class="huls-input" placeholder="Masukkan nama sampel yang diuji">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Kode Sampel <span class="text-danger">*</span></label>
                                            <input type="text" id="pre_kode_sampel" class="huls-input" placeholder="Masukkan kode sampel">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">No. HULS <span class="text-danger">*</span></label>
                                            <input type="text" id="pre_nomor_huls" class="huls-input" placeholder="Masukkan nomor dokumen HULS">
                                        </div>
                                        <div class="col-12">
                                            <label class="huls-label">Parameter Uji / Metode</label>
                                            <input type="text" id="pre_parameter_uji" class="huls-input" placeholder="Masukkan parameter uji dan metode analisis">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Nama Penguji</label>
                                            <input type="text" id="pre_nama_penguji" class="huls-input" placeholder="Masukkan nama analis / penguji">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Tanggal Pengujian</label>
                                            <input type="text" id="pre_tanggal_pengujian" class="huls-input" placeholder="Masukkan tanggal pengujian (misal: 8 Oktober 2026)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Tanggal Dilaporkan</label>
                                            <input type="text" id="pre_tanggal_dilaporkan" class="huls-input" placeholder="Masukkan tanggal dilaporkan (misal: 12 Oktober 2026)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Hasil Uji</label>
                                            <input type="text" id="pre_hasil_uji" class="huls-input" list="listHasilUjiOptions" placeholder="Masukkan hasil uji">
                                            <datalist id="listHasilUjiOptions">
                                                <option value="Tidak Memenuhi Syarat">
                                                <option value="Memenuhi Syarat">
                                            </datalist>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Syarat</label>
                                            <input type="text" id="pre_syarat" class="huls-input" placeholder="Masukkan syarat keberterimaan">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- BAGIAN II: PRE-INVESTIGASI HULS -->
                            <div class="huls-section-box">
                                <div class="huls-section-box-header">
                                    <div class="huls-section-title">II. Pre-Investigasi HULS</div>
                                    <span class="text-muted small">Pemeriksaan kondisi sampel, pemindahan data, serta rumus &amp; perhitungan</span>
                                </div>
                                <div class="huls-section-box-body p-0">
                                    <div class="table-responsive">
                                        <table class="huls-check-table border-0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 52px; text-align:center;">No.</th>
                                                    <th style="width: 26%;">Parameter</th>
                                                    <th>Observasi</th>
                                                    <th style="width: 26%;">Paraf dan Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold text-center">1</td>
                                                    <td class="fw-bold">Kondisi sampel</td>
                                                    <td>
                                                        <select class="huls-select mb-2" onchange="if(this.value) document.getElementById('pre_obs_1').value = this.value;">
                                                            <option value="">-- Pilih cepat / isi di bawah --</option>
                                                            <option value="sesuai">Sesuai</option>
                                                            <option value="tidak sesuai">Tidak Sesuai</option>
                                                        </select>
                                                        <textarea id="pre_obs_1" class="huls-textarea" rows="2" placeholder="Catatan observasi kondisi sampel"></textarea>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="pre_paraf_1" class="huls-input mb-2" placeholder="Nama / Paraf">
                                                        <input type="text" id="pre_tgl_1" class="huls-input" placeholder="Tanggal pemeriksaan">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold text-center">2</td>
                                                    <td class="fw-bold">Pemindahan data</td>
                                                    <td>
                                                        <select class="huls-select mb-2" onchange="if(this.value) document.getElementById('pre_obs_2').value = this.value;">
                                                            <option value="">-- Pilih cepat / isi di bawah --</option>
                                                            <option value="sesuai">Sesuai</option>
                                                            <option value="tidak sesuai">Tidak Sesuai</option>
                                                        </select>
                                                        <textarea id="pre_obs_2" class="huls-textarea" rows="2" placeholder="Catatan observasi pemindahan data"></textarea>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="pre_paraf_2" class="huls-input mb-2" placeholder="Nama / Paraf">
                                                        <input type="text" id="pre_tgl_2" class="huls-input" placeholder="Tanggal pemeriksaan">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold text-center">3</td>
                                                    <td class="fw-bold">Rumus dan perhitungan</td>
                                                    <td>
                                                        <select class="huls-select mb-2" onchange="if(this.value) document.getElementById('pre_obs_3').value = this.value;">
                                                            <option value="">-- Pilih cepat / isi di bawah --</option>
                                                            <option value="sesuai">Sesuai</option>
                                                            <option value="tidak sesuai">Tidak Sesuai</option>
                                                        </select>
                                                        <textarea id="pre_obs_3" class="huls-textarea" rows="2" placeholder="Catatan observasi rumus dan perhitungan"></textarea>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="pre_paraf_3" class="huls-input mb-2" placeholder="Nama / Paraf">
                                                        <input type="text" id="pre_tgl_3" class="huls-input" placeholder="Tanggal pemeriksaan">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- BAGIAN III: KOREKSI, HASIL PEMERIKSAAN, RE-ANALISA, & KESIMPULAN -->
                            <div class="huls-section-box mb-0">
                                <div class="huls-section-box-header">
                                    <div class="huls-section-title">III. Koreksi, Hasil Pemeriksaan, dan Kesimpulan</div>
                                    <span class="text-muted small">Pencatatan koreksi, hasil pemeriksaan, re-analisa, serta kesimpulan pre-investigasi</span>
                                </div>
                                <div class="huls-section-box-body">
                                    <div class="row g-3">
                                        <!-- Sub-blok 1: Koreksi -->
                                        <div class="col-12">
                                            <div class="huls-subblock">
                                                <div class="row g-3 align-items-end">
                                                    <div class="col-md-4">
                                                        <label class="huls-label">Koreksi</label>
                                                        <input type="text" id="pre_koreksi_catatan" class="huls-input" placeholder="Tuliskan koreksi atau '-' jika tidak ada">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="huls-label">Tertulis (Tabel Koreksi No. 1)</label>
                                                        <input type="text" id="pre_koreksi_tertulis_1" class="huls-input" placeholder="Contoh: Tidak ada koreksi">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="huls-label">Seharusnya (Tabel Koreksi No. 1)</label>
                                                        <input type="text" id="pre_koreksi_seharusnya_1" class="huls-input" placeholder="Isi jika terdapat perbaikan">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sub-blok 2: Hasil Koreksi & Tabel Pemeriksaan -->
                                        <div class="col-12">
                                            <div class="huls-subblock">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="huls-label">Hasil Koreksi</label>
                                                        <input type="text" id="pre_hasil_koreksi_ket" class="huls-input" placeholder="Contoh: pengujian sesuai">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="huls-label">Sampel (No. 1)</label>
                                                        <input type="text" id="pre_hk_sampel_1" class="huls-input" placeholder="Kode sampel">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="huls-label">Syarat</label>
                                                        <input type="text" id="pre_hk_syarat_1" class="huls-input" placeholder="Syarat">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="huls-label">Hasil</label>
                                                        <input type="text" id="pre_hk_hasil_1" class="huls-input" placeholder="Hasil uji">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="huls-label">RSD</label>
                                                        <input type="text" id="pre_hk_rsd" class="huls-input" placeholder="Nilai RSD">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sub-blok 3: Re-analisa dengan sampel yang sama -->
                                        <div class="col-12">
                                            <div class="huls-subblock">
                                                <div class="fw-bold small text-secondary mb-2">Re-analisa dengan sampel yang sama (jika hasil koreksi telah memenuhi syarat)</div>
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="huls-label">Sampel (No. 1)</label>
                                                        <input type="text" id="pre_reanalisa_sampel_1" class="huls-input" placeholder="Kode sampel atau '-'">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="huls-label">Syarat</label>
                                                        <input type="text" id="pre_reanalisa_syarat_1" class="huls-input" placeholder="Syarat">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="huls-label">Hasil</label>
                                                        <input type="text" id="pre_reanalisa_hasil_1" class="huls-input" placeholder="Hasil re-analisa">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="huls-label">RSD</label>
                                                        <input type="text" id="pre_reanalisa_rsd" class="huls-input" placeholder="Nilai RSD">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sub-blok 4: Kesimpulan & Penandatangan -->
                                        <div class="col-md-6">
                                            <label class="huls-label">Kesimpulan dari Pre-Investigasi</label>
                                            <select id="pre_kesimpulan" class="huls-select">
                                                <option value="">-- Pilih Kesimpulan Pre-Investigasi --</option>
                                                <option value="TMS_LANJUT_TAHAP_1">Tidak Memenuhi Syarat — dilanjutkan ke Investigasi Tahap 1</option>
                                                <option value="MS_DILAPORKAN">Memenuhi Syarat dan hasil dilaporkan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="huls-label">Diuji Oleh</label>
                                            <input type="text" id="pre_diuji_oleh" class="huls-input" placeholder="Nama penguji">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="huls-label">Verifikator</label>
                                            <input type="text" id="pre_verifikator" class="huls-input" placeholder="Nama verifikator">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Tombol Tab 1 -->
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4 pt-3 border-top">
                                <span class="text-muted small"><i class="ti ti-info-circle me-1"></i> Langkah 1 dari 2: Setelah mengisi Pre-Investigasi, lanjutkan ke <strong>Investigasi Tahap 1</strong> untuk menyimpan 1 paket Dokumen HULS.</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-success fw-bold px-4" style="background:#008c55; border-color:#008c55;" onclick="syncPreToTahap1AndSwitch()">
                                        Lanjut ke Investigasi Tahap 1 <i class="ti ti-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                        </div> <!-- / #hulsTabContentPre -->

                        <!-- =====================================================
                             TAB 2: FORM INVESTIGASI TAHAP 1
                             ===================================================== -->
                        <div id="hulsTabContentTahap1" style="display: none;">

                            <!-- BAGIAN I: INFORMASI PENGUJIAN TAHAP 1 -->
                            <div class="huls-section-box">
                                <div class="huls-section-box-header">
                                    <div class="huls-section-title">I. Informasi Pengujian</div>
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-sm py-1 px-2" onclick="copyHeaderFromPreToTahap1()">
                                        <i class="ti ti-copy me-1"></i> Salin dari Pre-Investigasi
                                    </button>
                                </div>
                                <div class="huls-section-box-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="huls-label">Nama Sampel <span class="text-danger">*</span></label>
                                            <input type="text" id="t1_nama_sampel" class="huls-input" placeholder="Masukkan nama sampel">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Kode Sampel <span class="text-danger">*</span></label>
                                            <input type="text" id="t1_kode_sampel" class="huls-input" placeholder="Masukkan kode sampel">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">No. I/OBA (Nomor HULS) <span class="text-danger">*</span></label>
                                            <input type="text" id="t1_nomor_huls" class="huls-input" placeholder="Masukkan nomor HULS (misal: I/OBA/013/X/26)">
                                        </div>
                                        <div class="col-12">
                                            <label class="huls-label">Parameter Uji / Metode</label>
                                            <input type="text" id="t1_parameter_uji" class="huls-input" placeholder="Masukkan parameter uji dan metode analisis">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Nama Penguji</label>
                                            <input type="text" id="t1_nama_penguji" class="huls-input" placeholder="Masukkan nama penguji">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Tanggal Pengujian</label>
                                            <input type="text" id="t1_tanggal_pengujian" class="huls-input" placeholder="Masukkan tanggal pengujian">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Tanggal Dilaporkan</label>
                                            <input type="text" id="t1_tanggal_dilaporkan" class="huls-input" placeholder="Masukkan tanggal dilaporkan">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Hasil Uji</label>
                                            <input type="text" id="t1_hasil_uji" class="huls-input" list="listHasilUjiOptions" placeholder="Masukkan hasil uji">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Syarat</label>
                                            <input type="text" id="t1_syarat" class="huls-input" placeholder="Masukkan syarat keberterimaan">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- BAGIAN II: DAFTAR PEMERIKSAAN TAHAP 1 (NO. 1 S/D 12) -->
                            <div class="huls-section-box">
                                <div class="huls-section-box-header">
                                    <div class="huls-section-title">II. Investigasi HULS Tahap 1 — Daftar Pemeriksaan</div>
                                    <span class="text-muted small">12 Parameter pemeriksaan investigasi laboratorium fase 1</span>
                                </div>
                                <div class="huls-section-box-body p-0">
                                    <div class="table-responsive">
                                        <table class="huls-check-table border-0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 46px; text-align:center;">No.</th>
                                                    <th style="width: 23%;">Parameter Pemeriksaan</th>
                                                    <th>Observasi / Rincian Pemeriksaan</th>
                                                    <th style="width: 21%;">Paraf / Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- 1. Kondisi Sampel -->
                                                <tr>
                                                    <td class="fw-bold text-center">1</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Kondisi Sampel</div>
                                                        <div class="text-muted small">Kondisi fisik, penyimpanan, wadah, labeling, segel</div>
                                                    </td>
                                                    <td>
                                                        <div class="huls-subparam-grid">
                                                            <span class="huls-subparam-label">Pengamatan kondisi fisik</span>
                                                            <input type="text" id="t1_ks_fisik" class="huls-input" placeholder="Catat kondisi fisik sampel">
                                                            <span class="huls-subparam-label">Kesesuaian penyimpanan</span>
                                                            <input type="text" id="t1_ks_penyimpanan" class="huls-input" placeholder="Contoh: Sesuai">
                                                            <span class="huls-subparam-label">Wadah penyimpanan</span>
                                                            <input type="text" id="t1_ks_wadah" class="huls-input" placeholder="Catat jenis & jumlah wadah">
                                                            <span class="huls-subparam-label">Labeling</span>
                                                            <input type="text" id="t1_ks_labeling" class="huls-input" placeholder="Catat keterangan labeling">
                                                            <span class="huls-subparam-label">Segel</span>
                                                            <input type="text" id="t1_ks_segel" class="huls-input" placeholder="Contoh: Segel utuh">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_ks_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 2. Pengecekan Alat (timbangan) -->
                                                <tr>
                                                    <td class="fw-bold text-center">2</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Pengecekan Alat (Timbangan)</div>
                                                        <div class="text-muted small">Identitas, kalibrasi, logbook, spesifikasi, riwayat</div>
                                                    </td>
                                                    <td>
                                                        <div class="huls-subblock mb-2">
                                                            <div class="fw-bold small text-primary mb-2">Timbangan 1</div>
                                                            <div class="huls-subparam-grid">
                                                                <span class="huls-subparam-label">Nama alat</span>
                                                                <input type="text" id="t1_timb1_nama" class="huls-input" placeholder="Contoh: Timbangan semimikro">
                                                                <span class="huls-subparam-label">Nomor identitas</span>
                                                                <input type="text" id="t1_timb1_id" class="huls-input" placeholder="Nomor seri / identitas alat">
                                                                <span class="huls-subparam-label">Status kalibrasi &amp; jadwal</span>
                                                                <input type="text" id="t1_timb1_kalibrasi" class="huls-input" placeholder="Tanggal / status kalibrasi">
                                                                <span class="huls-subparam-label">Review log book alat</span>
                                                                <input type="text" id="t1_timb1_logbook" class="huls-input" placeholder="Ada / Tidak">
                                                                <span class="huls-subparam-label">Pemenuhan spesifikasi</span>
                                                                <input type="text" id="t1_timb1_spesifikasi" class="huls-input" placeholder="Memenuhi Syarat / Tidak">
                                                                <span class="huls-subparam-label">Catatan riwayat / history</span>
                                                                <input type="text" id="t1_timb1_history" class="huls-input" placeholder="Ada / Tidak">
                                                            </div>
                                                        </div>
                                                        <div class="huls-subblock">
                                                            <div class="fw-bold small text-primary mb-2">Timbangan 2 (Opsional)</div>
                                                            <div class="huls-subparam-grid">
                                                                <span class="huls-subparam-label">Nama alat</span>
                                                                <input type="text" id="t1_timb2_nama" class="huls-input" placeholder="Contoh: Timbangan analitik">
                                                                <span class="huls-subparam-label">Nomor identitas</span>
                                                                <input type="text" id="t1_timb2_id" class="huls-input" placeholder="Nomor seri / identitas alat">
                                                                <span class="huls-subparam-label">Status kalibrasi &amp; jadwal</span>
                                                                <input type="text" id="t1_timb2_kalibrasi" class="huls-input" placeholder="Tanggal / status kalibrasi">
                                                                <span class="huls-subparam-label">Review log book alat</span>
                                                                <input type="text" id="t1_timb2_logbook" class="huls-input" placeholder="Ada / Tidak">
                                                                <span class="huls-subparam-label">Pemenuhan spesifikasi</span>
                                                                <input type="text" id="t1_timb2_spesifikasi" class="huls-input" placeholder="Memenuhi Syarat / Tidak">
                                                                <span class="huls-subparam-label">Catatan riwayat / history</span>
                                                                <input type="text" id="t1_timb2_history" class="huls-input" placeholder="Ada / Tidak">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_timb_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 3. Pengecekan Kalibrasi Instrumen -->
                                                <tr>
                                                    <td class="fw-bold text-center">3</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Pengecekan Kalibrasi Instrumen</div>
                                                        <div class="text-muted small">Nama instrumen, identitas, kalibrasi, logbook</div>
                                                    </td>
                                                    <td>
                                                        <div class="huls-subparam-grid">
                                                            <span class="huls-subparam-label">Nama Instrumen</span>
                                                            <textarea id="t1_inst_nama" class="huls-textarea" rows="2" placeholder="Nama instrumen yang digunakan"></textarea>
                                                            <span class="huls-subparam-label">Nomor Identitas</span>
                                                            <textarea id="t1_inst_id" class="huls-textarea" rows="2" placeholder="Merk, tipe, dan nomor identitas"></textarea>
                                                            <span class="huls-subparam-label">Status kalibrasi &amp; jadwal</span>
                                                            <input type="text" id="t1_inst_kalibrasi" class="huls-input" placeholder="Tanggal kalibrasi instrumen">
                                                            <span class="huls-subparam-label">Review Log book Alat</span>
                                                            <input type="text" id="t1_inst_logbook" class="huls-input" placeholder="Ada / Tidak">
                                                            <span class="huls-subparam-label">Pemenuhan spesifikasi</span>
                                                            <input type="text" id="t1_inst_spesifikasi" class="huls-input" placeholder="Memenuhi Syarat / Tidak">
                                                            <span class="huls-subparam-label">Catatan riwayat / history</span>
                                                            <input type="text" id="t1_inst_history" class="huls-input" placeholder="Ada / Tidak">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_inst_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 4. Pengecekan alat mikropipet -->
                                                <tr>
                                                    <td class="fw-bold text-center">4</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Pengecekan Alat Mikropipet</div>
                                                        <div class="text-muted small">Nomor identitas &amp; kalibrasi terakhir</div>
                                                    </td>
                                                    <td>
                                                        <textarea id="t1_pipet_identitas_kalibrasi" class="huls-textarea" rows="2" placeholder="Tuliskan ukuran mikropipet, nomor identitas, dan tanggal kalibrasi terakhir"></textarea>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_pipet_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 5. Reagen yang digunakan -->
                                                <tr>
                                                    <td class="fw-bold text-center">5</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Reagen yang Digunakan</div>
                                                        <div class="text-muted small">Identitas, penampakan fisik, kedaluwarsa</div>
                                                    </td>
                                                    <td>
                                                        <div class="huls-subparam-grid">
                                                            <span class="huls-subparam-label">Identitas reagen</span>
                                                            <textarea id="t1_reagen_identitas" class="huls-textarea" rows="2" placeholder="Daftar nama & kode batch reagen"></textarea>
                                                            <span class="huls-subparam-label">Penampakan fisik</span>
                                                            <input type="text" id="t1_reagen_fisik" class="huls-input" placeholder="Contoh: Sesuai">
                                                            <span class="huls-subparam-label">Kedaluwarsa reagen (ED)</span>
                                                            <textarea id="t1_reagen_ed" class="huls-textarea" rows="2" placeholder="Daftar tanggal kedaluwarsa (ED) reagen"></textarea>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_reagen_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 6. Pemindahan/pengolahan data -->
                                                <tr>
                                                    <td class="fw-bold text-center">6</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Pemindahan / Pengolahan Data</div>
                                                        <div class="text-muted small">Pengenceran dan penimbangan</div>
                                                    </td>
                                                    <td>
                                                        <div class="huls-subparam-grid">
                                                            <span class="huls-subparam-label">Pengenceran</span>
                                                            <input type="text" id="t1_data_pengenceran" class="huls-input" placeholder="Kesesuaian pengenceran">
                                                            <span class="huls-subparam-label">Penimbangan</span>
                                                            <input type="text" id="t1_data_penimbangan" class="huls-input" placeholder="Kesesuaian penimbangan">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_data_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 7. Baku Pembanding -->
                                                <tr>
                                                    <td class="fw-bold text-center">7</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Baku Pembanding</div>
                                                        <div class="text-muted small">Identitas, kadar, kesesuaian penggunaan</div>
                                                    </td>
                                                    <td>
                                                        <div class="huls-subparam-grid">
                                                            <span class="huls-subparam-label">Identitas baku pembanding</span>
                                                            <textarea id="t1_baku_identitas" class="huls-textarea" rows="2" placeholder="Nama baku, No. Kontrol, Kadar, KA"></textarea>
                                                            <span class="huls-subparam-label">Kesesuaian penggunaan</span>
                                                            <input type="text" id="t1_baku_kesesuaian" class="huls-input" placeholder="Contoh: Sesuai dengan metode analisis">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_baku_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 8. Pengecekan kromatogram/spektrum/ plat KLT -->
                                                <tr>
                                                    <td class="fw-bold text-center">8</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Pengecekan Kromatogram / Spektrum / Plat KLT</div>
                                                    </td>
                                                    <td>
                                                        <textarea id="t1_kromatogram_obs" class="huls-textarea" rows="2" placeholder="Catatan hasil pengecekan kromatogram / spektrum / plat KLT"></textarea>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_kromatogram_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 9. Pengecekan alat gelas -->
                                                <tr>
                                                    <td class="fw-bold text-center">9</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Pengecekan Alat Gelas</div>
                                                        <div class="text-muted small">Kebersihan dan kesesuaian alat gelas</div>
                                                    </td>
                                                    <td>
                                                        <div class="huls-subparam-grid">
                                                            <span class="huls-subparam-label">Kebersihan</span>
                                                            <input type="text" id="t1_gelas_kebersihan" class="huls-input" placeholder="Contoh: Bersih">
                                                            <span class="huls-subparam-label">Kesesuaian alat gelas</span>
                                                            <textarea id="t1_gelas_kesesuaian" class="huls-textarea" rows="2" placeholder="Volume pipet, labu tentukur, tabung sentrifuga, dll."></textarea>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_gelas_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 10. Kesesuaian Sistem -->
                                                <tr>
                                                    <td class="fw-bold text-center">10</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Kesesuaian Sistem (Hasil UKS)</div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_uks_hasil" class="huls-input" placeholder="Catatan hasil Uji Kesesuaian Sistem (UKS)">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_uks_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 11. Validitas metode -->
                                                <tr>
                                                    <td class="fw-bold text-center">11</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Validitas Metode</div>
                                                        <div class="text-muted small">Identitas/edisi dan verifikasi/validasi</div>
                                                    </td>
                                                    <td>
                                                        <div class="huls-subparam-grid">
                                                            <span class="huls-subparam-label">Identitas / edisi metode</span>
                                                            <input type="text" id="t1_metode_identitas" class="huls-input" placeholder="Nomor / edisi metode analisis">
                                                            <span class="huls-subparam-label">Verifikasi / validasi metode</span>
                                                            <input type="text" id="t1_metode_verifikasi" class="huls-input" placeholder="Contoh: Memenuhi persyaratan">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_metode_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>

                                                <!-- 12. Kondisi suhu dan kelembaban laboratorium -->
                                                <tr>
                                                    <td class="fw-bold text-center">12</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">Kondisi Suhu &amp; Kelembapan Lab / Ruang Instrumen</div>
                                                        <button type="button" class="btn btn-xs btn-outline-success btn-sm py-1 px-2 mt-1" onclick="pullIoTEnvToHuls()">
                                                            <i class="ti ti-device-analytics me-1"></i> Ambil dari Sensor IoT
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <textarea id="t1_lingkungan_obs" class="huls-textarea" rows="2" placeholder="Catat suhu (°C) dan kelembapan (%) ruangan laboratorium"></textarea>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="t1_lingkungan_paraf_tgl" class="huls-input" placeholder="Nama / Paraf & Tanggal">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- BAGIAN III: DISKUSI, KESIMPULAN, & TINDAK LANJUT -->
                            <div class="huls-section-box mb-0">
                                <div class="huls-section-box-header">
                                    <div class="huls-section-title">III. Diskusi, Kesimpulan, dan Tindak Lanjut</div>
                                    <span class="text-muted small">Diskusi dengan analis, re-analisis, kesimpulan fase 1, dan pengesahan</span>
                                </div>
                                <div class="huls-section-box-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="huls-label">Diskusi — 1. Penanganan sampel</label>
                                            <textarea id="t1_diskusi_1_pengamatan" class="huls-textarea" rows="2" placeholder="Ringkasan diskusi penanganan sampel dengan analis"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Diskusi — 2. Pengujian</label>
                                            <textarea id="t1_diskusi_2_pengamatan" class="huls-textarea" rows="2" placeholder="Kesesuaian prosedur pengujian dan catatan diskusi"></textarea>
                                        </div>

                                        <div class="col-12">
                                            <label class="huls-label">Kesimpulan dari Investigator</label>
                                            <textarea id="t1_kesimpulan_investigator" class="huls-textarea" rows="2" placeholder="Tuliskan kesimpulan dari investigator"></textarea>
                                        </div>

                                        <!-- Sub-blok Re-analisis -->
                                        <div class="col-12">
                                            <div class="huls-subblock">
                                                <div class="fw-bold small text-secondary mb-2">Re-analisis dengan sampel yang sama (jika ditemukan kesalahan dalam analisis)</div>
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="huls-label">Protokol pengujian baru diperlukan?</label>
                                                        <select id="t1_protokol_baru" class="huls-select">
                                                            <option value="Tidak">Tidak</option>
                                                            <option value="Ya">Ya (lampirkan)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="huls-label">Sampel (No. 1)</label>
                                                        <input type="text" id="t1_reanalisis_sampel" class="huls-input" placeholder="Kode sampel">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="huls-label">Syarat</label>
                                                        <input type="text" id="t1_reanalisis_syarat" class="huls-input" placeholder="Syarat">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="huls-label">Hasil</label>
                                                        <input type="text" id="t1_reanalisis_hasil" class="huls-input" placeholder="Hasil">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="huls-label">Kesimpulan Investigasi Fase 1</label>
                                            <textarea id="t1_kesimpulan_fase1" class="huls-textarea" rows="2" placeholder="Tuliskan kesimpulan investigasi fase 1"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="huls-label">Tindakan perbaikan yang diambil</label>
                                            <textarea id="t1_tindakan_perbaikan" class="huls-textarea" rows="2" placeholder="Tuliskan tindakan perbaikan atau tanda '-' jika tidak ada"></textarea>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="huls-label">Investigator / Penguji</label>
                                            <input type="text" id="t1_reanalisis_penguji" class="huls-input mb-2" placeholder="Nama Investigator / Penguji">
                                            <input type="text" id="t1_reanalisis_paraf_tgl" class="huls-input" placeholder="Paraf / Tanggal">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="huls-label">Verifikator</label>
                                            <input type="text" id="t1_verifikator_tahap1" class="huls-input" placeholder="Nama Verifikator">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="huls-label">Ketua Tim (Jabatan &amp; Nama)</label>
                                            <input type="text" id="t1_ketua_tim_jabatan" class="huls-input mb-2" placeholder="Jabatan (Contoh: Ketua Tim Kimia Obat)">
                                            <input type="text" id="t1_ketua_tim_nama" class="huls-input" placeholder="Nama Lengkap &amp; Gelar Ketua Tim">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Tombol Tab 2 -->
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-light border fw-bold" onclick="switchHulsFormTab('pre')">
                                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Pre-Investigasi
                                </button>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-primary fw-bold" onclick="exportHulsWordPdf('both')">
                                        <i class="ti ti-file-type-pdf me-1"></i> Pratinjau / Ekspor PDF
                                    </button>
                                    <button type="button" class="btn btn-primary fw-bold px-4" style="background:#0b4f94; border-color:#0b4f94;" onclick="saveHulsDocumentToServer('SIMPAN_DOKUMEN_HULS')">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Dokumen HULS (Pre-Investigasi &amp; Tahap 1)
                                    </button>
                                </div>
                            </div>

                        </div> <!-- / #hulsTabContentTahap1 -->

                        <!-- =====================================================
                             TAB 3: PRATINJAU & EKSPOR PDF PERSIS DOKUMEN WORD
                             ===================================================== -->
                        <div id="hulsTabContentPreview" style="display: none;">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 p-3 rounded border bg-light">
                                <div>
                                    <h6 class="fw-bold mb-0" style="color:#034284;"><i class="ti ti-file-word me-1"></i> Pratinjau Dokumen Resmi (Tata Letak Persis File Word BBPOM)</h6>
                                    <small class="text-muted">Pilih dokumen yang ingin ditampilkan atau diunduh ke dalam format PDF:</small>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <select id="hulsPreviewDocMode" class="form-select form-select-sm fw-bold" style="width: 240px;" onchange="renderHulsWordExactPreview()">
                                        <option value="both">Gabungan (Pre-Investigasi + Tahap 1)</option>
                                        <option value="pre">Hanya HULS Pre-Investigasi</option>
                                        <option value="tahap1">Hanya HULS Investigasi Tahap 1</option>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-primary fw-bold" style="background:#0b4f94;" onclick="downloadHulsDirectPdfFile()">
                                        <i class="ti ti-download me-1"></i> Unduh File PDF (.pdf)
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success fw-bold" style="background:#008c55;" onclick="printHulsWordExactWindow()">
                                        <i class="ti ti-printer me-1"></i> Cetak / Save as PDF (Kualitas Vektor)
                                    </button>
                                </div>
                            </div>

                            <!-- Container Render Persis Word -->
                            <div id="hulsWordPrintArea"></div>
                        </div> <!-- / #hulsTabContentPreview -->

                        </div>
                    </div>
                </div> <!-- / #hulsMainViewForm -->

            </div> <!-- / #section-huls -->

        </div> <!-- / .digilab-content -->
    </div> <!-- / .digilab-main -->
</div> <!-- / .digilab-app-shell -->

<!-- ============================================================
     MODAL 1: TAMBAH RUANGAN BARU
     ============================================================ -->
<div class="modal fade" id="modalTambahRuangan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" style="color: #3b327b;"><i class="ti ti-building-community me-1"></i> Tambah Ruangan Monitoring Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formTambahRuangan">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Ruangan <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="inputRoomName" placeholder="Contoh: Ruang Lab Mikrobiologi / Ruang Server" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Pilih Ikon Ruangan</label>
            <select class="form-select" id="inputRoomIcon">
              <option value="ti-flask">🧪 Laboratorium / Pengujian (Flask)</option>
              <option value="ti-server">🖥️ Ruang Server / IT (Server)</option>
              <option value="ti-package">📦 Gudang Penyimpanan / Reagen (Box)</option>
              <option value="ti-snowflake">❄️ Ruang Pendingin / Chiller (Snowflake)</option>
              <option value="ti-building">🏢 Ruangan Kantor / Rapat (Building)</option>
              <option value="ti-shield-check">🛡️ Ruang Steril / Biosafety (Shield)</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Deskripsi Singkat</label>
            <input type="text" class="form-control" id="inputRoomDesc" placeholder="Contoh: Monitoring suhu dingin & kelembapan ruang sampel">
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-geulis-primary"><i class="ti ti-check me-1"></i> Simpan Ruangan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ============================================================
     MODAL 2: TAMBAH ALAT SENSOR BARU (MODULAR CHECKLIST)
     ============================================================ -->
<div class="modal fade" id="modalTambahAlat" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" style="color: #3b327b;"><i class="ti ti-device-analytics me-1"></i> Tambah Alat Sensor IoT Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formTambahAlat">
        <div class="modal-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Pilih Ruangan <span class="text-danger">*</span></label>
              <select class="form-select" id="inputDeviceRoom" required>
                <option value="tab-lab">Ruang Lab Reagen</option>
                <option value="tab-keuangan">Ruang Keuangan</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Nama Alat / Titik Pantau <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="inputDeviceName" placeholder="Contoh: Alat 3 — Pojok Utara (Chiller)" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Kode ID Alat (Device ID) <span class="text-danger">*</span></label>
              <input type="text" class="form-control text-uppercase" id="inputDeviceId" placeholder="Contoh: ALAT_3_MIKRO" required>
              <small class="text-muted">Kode unik yang dikirim oleh ESP32 pada JSON <code>"id"</code>.</small>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Lokasi Node / Keterangan</label>
              <input type="text" class="form-control" id="inputDeviceNode" placeholder="Contoh: Rak Sampel B2 / Lt. 2">
            </div>
          </div>

          <hr class="my-3">
          <h6 class="fw-bold mb-2" style="color: #3b327b;"><i class="ti ti-list-check me-1"></i> Pilih Sensor yang Terpasang pada Alat Ini</h6>
          <p class="text-muted small mb-3">Centang hanya sensor yang dimiliki oleh alat ini. Tampilan kartu, grafik, dan laporan PDF akan menyesuaikan otomatis.</p>

          <!-- Sensor 1: Suhu -->
          <div class="sensor-check-card">
            <div class="form-check d-flex justify-content-between align-items-center">
              <div>
                <input class="form-check-input" type="checkbox" id="chkSensorSuhu" checked>
                <label class="form-check-label fw-bold" for="chkSensorSuhu">
                  <i class="ti ti-temperature text-danger"></i> Sensor Suhu Ruangan (&deg;C)
                </label>
                <div class="text-muted small">Contoh hardware: DHT22, DS18B20, SHT31, BME280</div>
              </div>
              <div class="d-flex align-items-center gap-2" style="max-width: 240px;">
                <div class="input-group input-group-sm">
                  <span class="input-group-text">Min</span>
                  <input type="number" step="0.5" class="form-control" id="inputSuhuMin" value="15">
                </div>
                <div class="input-group input-group-sm">
                  <span class="input-group-text">Max</span>
                  <input type="number" step="0.5" class="form-control" id="inputSuhuMax" value="25">
                </div>
              </div>
            </div>
          </div>

          <!-- Sensor 2: Kelembapan -->
          <div class="sensor-check-card">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="chkSensorHum" checked>
              <label class="form-check-label fw-bold" for="chkSensorHum">
                <i class="ti ti-droplet text-primary"></i> Sensor Kelembapan Udara (%RH)
              </label>
              <div class="text-muted small">Contoh hardware: DHT22, SHT31, BME280</div>
            </div>
          </div>

          <!-- Sensor 3: Kualitas Udara (MQ) -->
          <div class="sensor-check-card">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="chkSensorMQ">
              <label class="form-check-label fw-bold" for="chkSensorMQ">
                <i class="ti ti-wind text-warning"></i> Sensor Kualitas Udara / Gas (PPM)
              </label>
              <div class="text-muted small">Contoh hardware: MQ-135, MQ-2, CCS811 (Skala kelipatan 500 PPM)</div>
            </div>
          </div>

          <!-- Sensor 4: Otomasi AC & Kehadiran -->
          <div class="sensor-check-card">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="chkSensorAC">
              <label class="form-check-label fw-bold" for="chkSensorAC">
                <i class="ti ti-air-conditioning text-success"></i> Sensor Kehadiran (PIR/Radar) &amp; Status Otomasi AC
              </label>
              <div class="text-muted small">Menampilkan panel status AC (ON/OFF) dan deteksi jumlah orang di ruangan</div>
            </div>
          </div>

          <!-- Sensor 5: Intensitas Cahaya (Lumens) & Saklar Lampu (Servo) -->
          <div class="sensor-check-card">
            <div class="form-check d-flex flex-wrap justify-content-between align-items-center gap-2">
              <div>
                <input class="form-check-input" type="checkbox" id="chkSensorLight">
                <label class="form-check-label fw-bold" for="chkSensorLight">
                  <i class="ti ti-bulb text-warning"></i> Sensor Intensitas Cahaya (Lumens) &amp; Saklar Lampu (Penggerak Servo)
                </label>
                <div class="text-muted small">Mendeteksi Lumens cahaya &amp; menggerakkan Servo saklar lampu otomatis (Gelap &rarr; ON, Terlalu Terang &rarr; OFF) + Switch Manual Web</div>
              </div>
              <div class="d-flex align-items-center gap-2" style="max-width: 280px;">
                <div class="input-group input-group-sm">
                  <span class="input-group-text">Gelap &lt;</span>
                  <input type="number" step="10" class="form-control" id="inputLumensMin" value="250">
                </div>
                <div class="input-group input-group-sm">
                  <span class="input-group-text">Terang &gt;</span>
                  <input type="number" step="10" class="form-control" id="inputLumensMax" value="500">
                </div>
              </div>
            </div>
          </div>

          <!-- Sensor 6: Custom / Model Baru -->
          <div class="mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fw-bold small text-uppercase text-muted">Parameter Sensor Tambahan / Model Baru (Opsional)</span>
              <button type="button" class="btn btn-xs btn-outline-primary btn-sm py-1 px-2" onclick="addCustomSensorRow()">
                <i class="ti ti-plus"></i> Tambah Parameter Kustom
              </button>
            </div>
            <div id="customSensorRowsContainer"></div>
          </div>

          <hr class="my-3">
          <div class="form-check form-switch bg-light p-3 rounded border">
            <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSimulateMode" checked>
            <label class="form-check-label fw-semibold" for="chkSimulateMode">
              Aktifkan Simulasi Data Real-time Otomatis (Jika alat fisik belum mengirim data)
            </label>
            <div class="text-muted small mt-1">
              Sangat berguna untuk demo/presentasi: grafik dan angka langsung bergerak aktif meskipun mikrokontroler belum dinyalakan. Otomatis beralih ke data asli begitu ESP32 mengirim data ke server.
            </div>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-geulis-primary"><i class="ti ti-check me-1"></i> Tambahkan Alat ke Dasbor</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ============================================================
     MODAL 3: KELOLA RUANGAN & ALAT (DAFTAR, HAPUS & PANDUAN ESP32)
     ============================================================ -->
<div class="modal fade" id="modalKelolaIoT" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" style="color: #3b327b;"><i class="ti ti-adjustments-horizontal me-1"></i> Kelola Ruangan &amp; Alat Sensor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="fw-bold mb-0">Daftar Seluruh Ruangan</h6>
          <button class="btn btn-sm btn-geulis-outline" onclick="bootstrap.Modal.getInstance(document.getElementById('modalKelolaIoT')).hide(); openModalTambahRuangan();">
            <i class="ti ti-plus"></i> Ruangan Baru
          </button>
        </div>
        <div id="manageRoomsList" class="mb-4"></div>

        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="fw-bold mb-0">Daftar Seluruh Alat Sensor &amp; Kode ID</h6>
          <button class="btn btn-sm btn-geulis-primary" onclick="bootstrap.Modal.getInstance(document.getElementById('modalKelolaIoT')).hide(); openModalTambahAlat();">
            <i class="ti ti-plus"></i> Alat Baru
          </button>
        </div>
        <div id="manageDevicesList" class="mb-3"></div>

        <div class="alert alert-info small mb-0">
          <strong><i class="ti ti-code me-1"></i> Panduan Integrasi Hardware (ESP32 / NodeMCU):</strong><br>
          Untuk mengirim data dari alat ke website ini, arahkan HTTP POST ke <code>/api/sensor</code> dengan header <code>Authorization: spbe-bbpom-secret-key-2026</code> dan body JSON sesuai Kode ID Alat:<br>
          <code class="d-block mt-1 p-2 bg-white rounded border text-dark">{"id": "KODE_ID_ALAT", "suhu": 23.4, "hum": 56, "mq": 620, "ac": 1, "occ": 2, "lumens": 380, "lampu": 1, "servo": 90}</code>
        </div>
      </div>
      <div class="modal-footer border-top">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================
     MODAL 4: LAPORAN EKSPOR PDF PEMANTAUAN SUHU & KELEMBAPAN RUANGAN
     Format Resmi BBPOM Bandung: 6.3/PTJM-01/BBPOM BDG/18 F (01)
     (Halaman 1: Tabel 08.00 & 15.00 | Halaman 2: Grafik 08.00 | Halaman 3: Grafik 15.00)
     ============================================================ -->
<div class="modal fade" id="modalLaporanSuhuKelembapan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 1140px;">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom bg-light">
        <div>
          <h5 class="modal-title fw-bold mb-0" style="color: #034284;">
            <i class="ti ti-file-type-pdf text-danger me-1"></i>
            Laporan Pemantauan Suhu dan Kelembapan Ruangan (08.00 &amp; 15.00)
          </h5>
          <small class="text-muted">Formulir Resmi BBPOM di Bandung — Kode: <strong>6.3/PTJM-01/BBPOM BDG/18 F (01)</strong> (3 Halaman: Tabel + Grafik 08.00 + Grafik 15.00)</small>
        </div>
        <div class="d-flex align-items-center gap-2">
          <button type="button" class="btn btn-sm btn-primary fw-bold px-3" style="background:#034284;" onclick="generateOfficialTempHumPdfFile('open')">
            <i class="ti ti-printer me-1"></i> Buka / Cetak PDF (3 Halaman)
          </button>
          <button type="button" class="btn btn-sm btn-success fw-bold px-3" style="background:#008c55;" onclick="generateOfficialTempHumPdfFile('download')">
            <i class="ti ti-download me-1"></i> Download File PDF
          </button>
          <button type="button" class="btn-close ms-1" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>

      <div class="modal-body p-3" style="background: #eef2f6;">
        <!-- Panel Konfigurasi Header, Spesifikasi, & Sumber Data -->
        <div class="card border shadow-sm mb-3" style="border-radius: 10px;">
          <div class="card-body p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2 pb-2 border-bottom">
              <div class="fw-bold small text-uppercase" style="color:#034284;">
                <i class="ti ti-adjustments me-1"></i> Identitas Ruangan, Spesifikasi &amp; Sumber Data Pemantauan (08.00 &amp; 15.00)
              </div>
              <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-xs btn-outline-primary fw-semibold py-1 px-2" onclick="loadTempHumDataFromDatabase()">
                  <i class="ti ti-database-import me-1"></i> Ambil Data Sensor Database (08.00 &amp; 15.00)
                </button>
                <button type="button" class="btn btn-xs btn-outline-dark fw-semibold py-1 px-2" onclick="toggleTempHumTableEditor()">
                  <i class="ti ti-edit me-1"></i> Edit Nilai Tabel (Tgl 1–31)
                </button>
              </div>
            </div>

            <div class="row g-2">
              <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Kode Dokumen</label>
                <input type="text" id="th_doc_code" class="form-control form-control-sm" value="6.3/PTJM-01/BBPOM BDG/18 F (01)" oninput="refreshOfficialTempHumPreview()">
              </div>
              <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Laboratorium</label>
                <input type="text" id="th_lab_name" class="form-control form-control-sm" value="MIKROBIOLOGI" oninput="refreshOfficialTempHumPreview()">
              </div>
              <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Ruangan</label>
                <input type="text" id="th_room_name" class="form-control form-control-sm" value="R. CEMARAN JAMUR" oninput="refreshOfficialTempHumPreview()">
              </div>
              <div class="col-md-3">
                <div class="row g-1">
                  <div class="col-7">
                    <label class="form-label small fw-bold mb-1">Bulan</label>
                    <select id="th_month_select" class="form-select form-select-sm" onchange="onTempHumMonthYearChange()">
                      <option value="1">JANUARI</option>
                      <option value="2">FEBRUARI</option>
                      <option value="3">MARET</option>
                      <option value="4">APRIL</option>
                      <option value="5">MEI</option>
                      <option value="6">JUNI</option>
                      <option value="7">JULI</option>
                      <option value="8" selected>AGUSTUS</option>
                      <option value="9">SEPTEMBER</option>
                      <option value="10">OKTOBER</option>
                      <option value="11">NOVEMBER</option>
                      <option value="12">DESEMBER</option>
                    </select>
                  </div>
                  <div class="col-5">
                    <label class="form-label small fw-bold mb-1">Tahun</label>
                    <input type="number" id="th_year_input" class="form-control form-control-sm" value="2026" oninput="refreshOfficialTempHumPreview()">
                  </div>
                </div>
              </div>

              <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Spesifikasi Suhu</label>
                <input type="text" id="th_spec_suhu" class="form-control form-control-sm" value="20-27°C" oninput="refreshOfficialTempHumPreview()">
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Spesifikasi Kelembapan</label>
                <input type="text" id="th_spec_hum" class="form-control form-control-sm" value="30-70%" oninput="refreshOfficialTempHumPreview()">
              </div>
              <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Pelaksana (Footer TTD)</label>
                <input type="text" id="th_sign_pelaksana" class="form-control form-control-sm" value="" placeholder="Isi manual / kosongkan untuk ttd basah" oninput="refreshOfficialTempHumPreview()">
              </div>
              <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Verifikator (Footer TTD)</label>
                <input type="text" id="th_sign_verifikator" class="form-control form-control-sm" value="" placeholder="Isi manual / kosongkan untuk ttd basah" oninput="refreshOfficialTempHumPreview()">
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Checklist Evaluasi</label>
                <select id="th_eval_check" class="form-select form-select-sm" onchange="refreshOfficialTempHumPreview()">
                  <option value="V" selected>☑ Ya (Memenuhi)</option>
                  <option value="X">☒ Tidak (X)</option>
                </select>
              </div>
            </div>

            <!-- Editor Tabel Harian 1-31 (Collapsible) -->
            <div id="thTableEditorWrap" class="mt-3 pt-3 border-top" style="display: none;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-bold text-secondary">Edit Data Harian Tanggal 1 s/d 31 (Hanya Pukul 08.00* dan 15.00*):</span>
                <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2" onclick="clearAllTempHumDays()">Kosongkan Semua Hari</button>
              </div>
              <div class="table-responsive" style="max-height: 240px; overflow-y: auto;">
                <table class="table table-sm table-bordered align-middle mb-0 bg-white" style="font-size: 0.78rem;">
                  <thead class="table-light text-center">
                    <tr>
                      <th style="width:50px;">Tgl</th>
                      <th>Suhu 08.00 (°C)</th>
                      <th>Suhu 15.00 (°C)</th>
                      <th>Kelembapan 08.00 (%)</th>
                      <th>Kelembapan 15.00 (%)</th>
                      <th>Nama Pelaksana</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody id="thEditorTbody"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- AREA PRATINJAU 3 HALAMAN PERSIS DOKUMEN PDF BBPOM -->
        <div id="thOfficialPreviewContainer" class="d-flex flex-column align-items-center gap-4"></div>
      </div>

      <div class="modal-footer border-top bg-white">
        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary fw-bold" style="background:#034284;" onclick="generateOfficialTempHumPdfFile('open')">
          <i class="ti ti-printer me-1"></i> Buka &amp; Cetak PDF (3 Halaman)
        </button>
        <button type="button" class="btn btn-success fw-bold" style="background:#008c55;" onclick="generateOfficialTempHumPdfFile('download')">
          <i class="ti ti-download me-1"></i> Download PDF (.pdf)
        </button>
      </div>
    </div>
  </div>
</div>

    <script>
        const API_BASE = '/api';
        const LOGGED_IN_USER_NAME = <?= json_encode($userName ?? 'Admin Lab') ?>;
        const LOGGED_IN_USER_ROLE = <?= json_encode($userRole ?? 'Administrator') ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- Include Bootstrap JS (used in GEULIS) -->
    <script src="https://bpombandung.services/assets/template/assets/vendor/js/bootstrap.js"></script>
    
    <script>
        const MENU_META = {
            dashboard:   { title: 'Dashboard DigitLab BDG', icon: 'ti-home', desc: '' },
            perencanaan: { title: 'Perencanaan Laboratorium (RENLAK)', icon: 'ti-calendar-stats', desc: 'Sistem Rencana Pelaksanaan & Kegiatan Digitalisasi Laboratorium BBPOM di Bandung (2026–2027).' },
            pengujian:   { title: 'Pelaksanaan Pengujian Sampel', icon: 'ti-flask-2', desc: 'Modul Pelaksanaan Pengujian Sampel terintegrasi dari penerimaan sampel hingga pengesahan hasil uji.' },
            instrumen:   { title: 'Pengelolaan Instrumen', icon: 'ti-microscope', desc: 'Modul Inventarisasi Instrumen Lab, Jadwal Kalibrasi, Log Penggunaan Alat, dan Pemeliharaan Berkala sedang dalam tahap pengembangan.' },
            inventor:    { title: 'Pengelolaan Inventor', icon: 'ti-packages', desc: 'Modul Pengelolaan Stok Reagen, Baku Pembanding, Media Mikrobiologi, dan Peringatan Kedaluwarsa sedang dalam tahap pengembangan.' },
            metode:      { title: 'Metode dan Validasi', icon: 'ti-certificate', desc: 'Modul Dokumen Metode Analisis Standar, Verifikasi/Validasi Metode, dan Estimasi Ketidakpastian Pengukuran sedang dalam tahap pengembangan.' },
            keamanan:    { title: 'Keamanan dan Integritas Data', icon: 'ti-shield-lock', desc: 'Modul Audit Trail, Backup Terenkripsi, Otorisasi Berjenjang (ISO/IEC 17025), dan Integritas Rekaman Elektronik sedang dalam tahap pengembangan.' },
            mutu:        { title: 'Pengendalian Mutu', icon: 'ti-award', desc: 'Modul Uji Profisiensi, Control Chart Internal Quality Control (IQC), dan Evaluasi Jaminan Mutu Hasil Uji sedang dalam tahap pengembangan.' },
            akomodasi:   { title: 'Akomodasi dan Lingkungan (Monitoring IoT)', icon: 'ti-device-analytics', desc: '' },
            sdm:         { title: 'Pengelolaan SDM', icon: 'ti-users', desc: 'Modul Matriks Kompetensi Analis, Riwayat Pelatihan Teknis, dan Penugasan Personel Laboratorium sedang dalam tahap pengembangan.' },
            ai:          { title: 'AI (Kecerdasan Buatan Laboratorium)', icon: 'ti-sparkles', desc: 'Modul Asisten AI Analisis Tren Ketidaksesuaian Sampel, Prediksi Beban Kerja Lab, dan Anomali Sensor sedang dalam tahap pengembangan.' },
            admin:       { title: 'Admin & Pengaturan Sistem', icon: 'ti-settings', desc: 'Modul Manajemen Pengguna, Hak Akses Role, Master Parameter Komoditi, dan Konfigurasi Integrasi API sedang dalam tahap pengembangan.' }
        };

        const PENGUJIAN_SUB_META = {
            sampel: {
                title: '1. Pengelolaan Sampel',
                icon: 'ti-package',
                desc: 'Sub-halaman Penerimaan Sampel, Registrasi Kode Sampel, Pelabelan Barcode/QR, Distribusi ke Laboratorium Uji, serta Penyimpanan Arsip Sampel (Retained Sample) sedang dalam tahap pengembangan.'
            },
            status: {
                title: '2. Status Pengujian',
                icon: 'ti-timeline-event',
                desc: 'Sub-halaman Pelacakan Status Tahapan Pengujian Sampel secara Real-Time, Monitoring Timeline Batas Waktu Uji (SLA), dan Antrean Prioritas sedang dalam tahap pengembangan.'
            },
            lims: {
                title: '3. Penerapan LIMS',
                icon: 'ti-device-desktop-analytics',
                desc: 'Sub-halaman Integrasi Laboratory Information Management System (LIMS Nasional), Lembar Kerja Analis Digital (e-Worksheet), dan Sinkronisasi Data Pengujian sedang dalam tahap pengembangan.'
            },
            reagen: {
                title: '4. Pengelolaan Reagen',
                icon: 'ti-flask',
                desc: 'Sub-halaman Pencatatan Pemakaian Reagen Pengujian, Pembuatan Larutan Kerja/Standar, Ketertelusuran Batch Reagen & Media, serta Pemantauan Masa Pakai sedang dalam tahap pengembangan.'
            },
            pengesahan: {
                title: '5. Pengesahan Hasil Uji',
                icon: 'ti-file-certificate',
                desc: 'Sub-halaman Verifikasi Berjenjang oleh Penyelia dan Manajer Teknis, Validasi Hasil Analisis, serta Pengesahan Laporan Hasil Uji (LHU) dengan Tanda Tangan Elektronik (TTE) sedang dalam tahap pengembangan.'
            },
            huls: {
                title: '6. HULS (Hasil Uji di Luar Spesifikasi)',
                icon: 'ti-alert-octagon',
                desc: 'Formulir Digital Pre-Investigasi HULS & Investigasi Tahap 1 HULS BBPOM di Bandung.'
            }
        };

        let activePengujianSubKey = 'sampel';

        window.toggleMobileSidebar = function(forceState) {
            const sb = document.getElementById('digilabSidebar');
            const bd = document.getElementById('sidebarMobileBackdrop');
            if (!sb) return;
            const shouldShow = (typeof forceState === 'boolean')
                ? forceState
                : !sb.classList.contains('show-mobile');
            sb.classList.toggle('show-mobile', shouldShow);
            if (bd) bd.classList.toggle('active', shouldShow);
        };

        window.togglePengujianMenu = function(btnEl) {
            const submenu = document.getElementById('submenu-pengujian');
            const chevron = document.getElementById('chevron-pengujian');
            const navPengujian = document.getElementById('nav-pengujian');
            const isCurrentlyActive = navPengujian && navPengujian.classList.contains('active');

            if (isCurrentlyActive && submenu) {
                submenu.classList.toggle('open');
                if (chevron) chevron.classList.toggle('rotated');
            } else {
                if (submenu) submenu.classList.add('open');
                if (chevron) chevron.classList.add('rotated');
                switchPengujianSubPage(activePengujianSubKey || 'sampel');
            }
        };

        window.switchPengujianSubPage = function(subKey) {
            activePengujianSubKey = subKey;
            const subMeta = PENGUJIAN_SUB_META[subKey] || PENGUJIAN_SUB_META.sampel;

            // Highlight main menu 3 & open submenu
            document.querySelectorAll('.sidebar-nav-item').forEach(el => el.classList.remove('active'));
            const navPengujian = document.getElementById('nav-pengujian');
            if (navPengujian) navPengujian.classList.add('active');

            const submenu = document.getElementById('submenu-pengujian');
            const chevron = document.getElementById('chevron-pengujian');
            if (submenu) submenu.classList.add('open');
            if (chevron) chevron.classList.add('rotated');

            // Highlight active sidebar sub-item and top pill
            document.querySelectorAll('.sidebar-sub-item').forEach(el => el.classList.remove('active'));
            const activeSubNav = document.getElementById('nav-pengujian-' + subKey);
            if (activeSubNav) activeSubNav.classList.add('active');

            document.querySelectorAll('.subpage-pill-btn').forEach(el => el.classList.remove('active'));
            const activePill = document.getElementById('pill-pengujian-' + subKey);
            if (activePill) activePill.classList.add('active');

            // Toggle visible sections
            const secDash = document.getElementById('section-dashboard');
            const secRenlak = document.getElementById('section-perencanaan');
            const secAko = document.getElementById('section-akomodasi');
            const secDev = document.getElementById('section-under-dev');
            const secHuls = document.getElementById('section-huls');
            const subNavBar = document.getElementById('pengujianSubNavBar');

            if (secDash) secDash.style.display = 'none';
            if (secRenlak) secRenlak.style.display = 'none';
            if (secAko) secAko.style.display = 'none';
            if (subNavBar) subNavBar.style.display = 'block';

            const topTitle = document.getElementById('topbarActiveTitle');
            if (topTitle) {
                topTitle.textContent = 'Pelaksanaan Pengujian / ' + subMeta.title;
                topTitle.title = 'Pelaksanaan Pengujian / ' + subMeta.title;
            }

            if (subKey === 'huls') {
                if (secDev) secDev.style.display = 'none';
                if (secHuls) secHuls.style.display = 'block';
                loadHulsDocuments();
            } else {
                if (secHuls) secHuls.style.display = 'none';
                if (secDev) secDev.style.display = 'block';

                const devTitle = document.getElementById('underDevTitle');
                const devDesc = document.getElementById('underDevDesc');
                const devIcon = document.getElementById('underDevIcon');
                const devBadge = document.getElementById('underDevBadge');

                if (devTitle) devTitle.textContent = subMeta.title;
                if (devDesc) devDesc.innerHTML = subMeta.desc;
                if (devIcon) devIcon.className = 'ti ' + subMeta.icon;
                if (devBadge) devBadge.innerHTML = '<i class="ti ti-flask-2 me-1"></i> Sub-Halaman Pelaksanaan Pengujian &bull; Dalam Pengembangan';
            }

            // Close mobile sidebar drawer if open
            toggleMobileSidebar(false);
        };

        window.switchMainMenu = function(menuKey, btnEl) {
            if (menuKey === 'pengujian') {
                switchPengujianSubPage(activePengujianSubKey || 'sampel');
                return;
            }

            document.querySelectorAll('.sidebar-nav-item').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.sidebar-sub-item').forEach(el => el.classList.remove('active'));

            if (btnEl) {
                btnEl.classList.add('active');
            } else {
                const targetNav = document.getElementById('nav-' + menuKey);
                if (targetNav) targetNav.classList.add('active');
            }

            const secDash = document.getElementById('section-dashboard');
            const secRenlak = document.getElementById('section-perencanaan');
            const secAko = document.getElementById('section-akomodasi');
            const secDev = document.getElementById('section-under-dev');
            const secHuls = document.getElementById('section-huls');
            const subNavBar = document.getElementById('pengujianSubNavBar');
            const topTitle = document.getElementById('topbarActiveTitle');
            const devBadge = document.getElementById('underDevBadge');
            const meta = MENU_META[menuKey] || { title: 'DigitLab BDG', icon: 'ti-settings-automation', desc: 'Modul sedang dalam pengembangan.' };

            if (subNavBar) subNavBar.style.display = 'none';
            if (secHuls) secHuls.style.display = 'none';
            if (devBadge) devBadge.innerHTML = '<i class="ti ti-Barrier-block me-1"></i> Modul Dalam Pengembangan';
            if (topTitle) {
                topTitle.textContent = meta.title;
                topTitle.title = meta.title;
            }

            if (menuKey === 'dashboard') {
                if (secDash) secDash.style.display = 'block';
                if (secRenlak) secRenlak.style.display = 'none';
                if (secAko) secAko.style.display = 'none';
                if (secDev) secDev.style.display = 'none';
            } else if (menuKey === 'perencanaan') {
                if (secDash) secDash.style.display = 'none';
                if (secRenlak) secRenlak.style.display = 'block';
                if (secAko) secAko.style.display = 'none';
                if (secDev) secDev.style.display = 'none';
                if (typeof loadRenlakData === 'function') {
                    loadRenlakData();
                }
            } else if (menuKey === 'akomodasi') {
                if (secDash) secDash.style.display = 'none';
                if (secRenlak) secRenlak.style.display = 'none';
                if (secAko) secAko.style.display = 'block';
                if (secDev) secDev.style.display = 'none';
                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));
                }, 60);
            } else {
                if (secDash) secDash.style.display = 'none';
                if (secRenlak) secRenlak.style.display = 'none';
                if (secAko) secAko.style.display = 'none';
                if (secDev) secDev.style.display = 'block';

                const devTitle = document.getElementById('underDevTitle');
                const devDesc = document.getElementById('underDevDesc');
                const devIcon = document.getElementById('underDevIcon');
                if (devTitle) devTitle.textContent = meta.title;
                if (devDesc) devDesc.innerHTML = meta.desc;
                if (devIcon) devIcon.className = 'ti ' + meta.icon;
            }

            // Close mobile sidebar drawer if open
            toggleMobileSidebar(false);
        };

        // =========================================================================
        // HULS (HASIL UJI DI LUAR SPESIFIKASI) — FULL FRONTEND & PDF WORD ENGINE
        // =========================================================================
        const HULS_DEFAULT_DOC = {
            id: 0,
            nomor_huls: '',
            kode_sampel: '',
            nama_sampel: '',
            parameter_uji: '',
            nama_penguji: '',
            tanggal_pengujian: '',
            tanggal_dilaporkan: '',
            hasil_uji: '',
            syarat: '',
            status_dokumen: 'Draft',
            pre_investigasi: {
                nama_sampel: '',
                kode_sampel: '',
                nomor_huls: '',
                parameter_uji: '',
                nama_penguji: '',
                tanggal_pengujian: '',
                tanggal_dilaporkan: '',
                hasil_uji: '',
                syarat: '',
                obs_1_kondisi_sampel: '',
                paraf_1_kondisi_sampel: '',
                tgl_1_kondisi_sampel: '',
                obs_2_pemindahan_data: '',
                paraf_2_pemindahan_data: '',
                tgl_2_pemindahan_data: '',
                obs_3_rumus_perhitungan: '',
                paraf_3_rumus_perhitungan: '',
                tgl_3_rumus_perhitungan: '',
                koreksi_catatan: '',
                koreksi_tertulis_1: '',
                koreksi_seharusnya_1: '',
                hasil_koreksi_ket: '',
                hk_sampel_1: '',
                hk_syarat_1: '',
                hk_hasil_1: '',
                hk_rsd: '',
                reanalisa_sampel_1: '',
                reanalisa_syarat_1: '',
                reanalisa_hasil_1: '',
                reanalisa_rsd: '',
                kesimpulan_pre: '',
                diuji_oleh: '',
                verifikator: ''
            },
            tahap1: {
                nomor_huls: '',
                nama_sampel: '',
                kode_sampel: '',
                parameter_uji: '',
                nama_penguji: '',
                tanggal_pengujian: '',
                tanggal_dilaporkan: '',
                hasil_uji: '',
                syarat: '',
                ks_fisik: '',
                ks_penyimpanan: '',
                ks_wadah: '',
                ks_labeling: '',
                ks_segel: '',
                ks_paraf_tgl: '',
                timb1_nama: '',
                timb1_id: '',
                timb1_kalibrasi: '',
                timb1_logbook: '',
                timb1_spesifikasi: '',
                timb1_history: '',
                timb2_nama: '',
                timb2_id: '',
                timb2_kalibrasi: '',
                timb2_logbook: '',
                timb2_spesifikasi: '',
                timb2_history: '',
                timb_paraf_tgl: '',
                inst_nama: '',
                inst_id: '',
                inst_kalibrasi: '',
                inst_logbook: '',
                inst_spesifikasi: '',
                inst_history: '',
                inst_paraf_tgl: '',
                pipet_identitas_kalibrasi: '',
                pipet_paraf_tgl: '',
                reagen_identitas: '',
                reagen_fisik: '',
                reagen_ed: '',
                reagen_paraf_tgl: '',
                data_pengenceran: '',
                data_penimbangan: '',
                data_paraf_tgl: '',
                baku_identitas: '',
                baku_kesesuaian: '',
                baku_paraf_tgl: '',
                kromatogram_obs: '',
                kromatogram_paraf_tgl: '',
                gelas_kebersihan: '',
                gelas_kesesuaian: '',
                gelas_paraf_tgl: '',
                uks_hasil: '',
                uks_paraf_tgl: '',
                metode_identitas: '',
                metode_verifikasi: '',
                metode_paraf_tgl: '',
                lingkungan_obs: '',
                lingkungan_paraf_tgl: '',
                diskusi_1_pengamatan: '',
                diskusi_2_pengamatan: '',
                kesimpulan_investigator: '',
                protokol_baru: '',
                reanalisis_sampel: '',
                reanalisis_syarat: '',
                reanalisis_hasil: '',
                reanalisis_penguji: '',
                reanalisis_paraf_tgl: '',
                kesimpulan_fase1: '',
                tindakan_perbaikan: '',
                verifikator_tahap1: '',
                ketua_tim_jabatan: '',
                ketua_tim_nama: ''
            }
        };

        let hulsDocumentsCache = [];
        let activeHulsFormTab = 'pre';
        let hulsInitializedOnce = false;

        function setVal(id, val) {
            const el = document.getElementById(id);
            if (el) el.value = (val !== undefined && val !== null) ? val : '';
        }
        function getVal(id) {
            const el = document.getElementById(id);
            return el ? el.value.trim() : '';
        }
        function escHtml(str) {
            return String(str ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        window.updateHulsStatusBadge = function() {
            const status = getVal('hulsStatusDokumen') || 'Draft';
            const badge = document.getElementById('hulsHeaderBadge');
            if (!badge) return;
            if (status === 'Disetujui') {
                badge.className = 'badge bg-label-success px-3 py-2';
                badge.textContent = 'DISETUJUI';
            } else if (status === 'Dalam Verifikasi') {
                badge.className = 'badge bg-label-info px-3 py-2';
                badge.textContent = 'DALAM VERIFIKASI';
            } else {
                badge.className = 'badge bg-label-warning px-3 py-2';
                badge.textContent = 'DRAFT';
            }
        };

        window.switchHulsFormTab = function(tabKey) {
            activeHulsFormTab = tabKey;
            const btnPre = document.getElementById('btnTabHulsPre');
            const btnT1 = document.getElementById('btnTabHulsTahap1');
            const btnPrev = document.getElementById('btnTabHulsWordPreview');

            const contentPre = document.getElementById('hulsTabContentPre');
            const contentT1 = document.getElementById('hulsTabContentTahap1');
            const contentPrev = document.getElementById('hulsTabContentPreview');

            if (btnPre) btnPre.classList.toggle('active', tabKey === 'pre');
            if (btnT1) btnT1.classList.toggle('active', tabKey === 'tahap1');
            if (btnPrev) btnPrev.classList.toggle('active', tabKey === 'preview');

            if (contentPre) contentPre.style.display = (tabKey === 'pre') ? 'block' : 'none';
            if (contentT1) contentT1.style.display = (tabKey === 'tahap1') ? 'block' : 'none';
            if (contentPrev) {
                contentPrev.style.display = (tabKey === 'preview') ? 'block' : 'none';
                if (tabKey === 'preview') {
                    renderHulsWordExactPreview();
                }
            }
        };

        window.populateHulsForm = function(doc) {
            const d = doc || HULS_DEFAULT_DOC;
            const pre = d.pre_investigasi || {};
            const t1 = d.tahap1 || {};

            setVal('hulsCurrentDocId', d.id || 0);
            setVal('hulsStatusDokumen', d.status_dokumen || 'Draft');
            updateHulsStatusBadge();

            // Populate Tab 1: Pre-Investigasi
            setVal('pre_nama_sampel', pre.nama_sampel ?? d.nama_sampel ?? '');
            setVal('pre_kode_sampel', pre.kode_sampel ?? d.kode_sampel ?? '');
            setVal('pre_nomor_huls', pre.nomor_huls ?? d.nomor_huls ?? '');
            setVal('pre_parameter_uji', pre.parameter_uji ?? d.parameter_uji ?? '');
            setVal('pre_nama_penguji', pre.nama_penguji ?? d.nama_penguji ?? '');
            setVal('pre_tanggal_pengujian', pre.tanggal_pengujian ?? d.tanggal_pengujian ?? '');
            setVal('pre_tanggal_dilaporkan', pre.tanggal_dilaporkan ?? d.tanggal_dilaporkan ?? '');
            setVal('pre_hasil_uji', pre.hasil_uji ?? d.hasil_uji ?? '');
            setVal('pre_syarat', pre.syarat ?? d.syarat ?? '');

            setVal('pre_obs_1', pre.obs_1_kondisi_sampel ?? '');
            setVal('pre_paraf_1', pre.paraf_1_kondisi_sampel ?? '');
            setVal('pre_tgl_1', pre.tgl_1_kondisi_sampel ?? '');

            setVal('pre_obs_2', pre.obs_2_pemindahan_data ?? '');
            setVal('pre_paraf_2', pre.paraf_2_pemindahan_data ?? '');
            setVal('pre_tgl_2', pre.tgl_2_pemindahan_data ?? '');

            setVal('pre_obs_3', pre.obs_3_rumus_perhitungan ?? '');
            setVal('pre_paraf_3', pre.paraf_3_rumus_perhitungan ?? '');
            setVal('pre_tgl_3', pre.tgl_3_rumus_perhitungan ?? '');

            setVal('pre_koreksi_catatan', pre.koreksi_catatan ?? '');
            setVal('pre_koreksi_tertulis_1', pre.koreksi_tertulis_1 ?? '');
            setVal('pre_koreksi_seharusnya_1', pre.koreksi_seharusnya_1 ?? '');
            setVal('pre_hasil_koreksi_ket', pre.hasil_koreksi_ket ?? '');
            setVal('pre_hk_sampel_1', pre.hk_sampel_1 ?? '');
            setVal('pre_hk_syarat_1', pre.hk_syarat_1 ?? '');
            setVal('pre_hk_hasil_1', pre.hk_hasil_1 ?? '');
            setVal('pre_hk_rsd', pre.hk_rsd ?? '');

            setVal('pre_reanalisa_sampel_1', pre.reanalisa_sampel_1 ?? '');
            setVal('pre_reanalisa_syarat_1', pre.reanalisa_syarat_1 ?? '');
            setVal('pre_reanalisa_hasil_1', pre.reanalisa_hasil_1 ?? '');
            setVal('pre_reanalisa_rsd', pre.reanalisa_rsd ?? '');

            setVal('pre_kesimpulan', pre.kesimpulan_pre ?? '');
            setVal('pre_diuji_oleh', pre.diuji_oleh ?? '');
            setVal('pre_verifikator', pre.verifikator ?? '');

            // Populate Tab 2: Investigasi Tahap 1
            setVal('t1_nama_sampel', t1.nama_sampel ?? d.nama_sampel ?? '');
            setVal('t1_kode_sampel', t1.kode_sampel ?? d.kode_sampel ?? '');
            setVal('t1_nomor_huls', t1.nomor_huls ?? d.nomor_huls ?? '');
            setVal('t1_parameter_uji', t1.parameter_uji ?? d.parameter_uji ?? '');
            setVal('t1_nama_penguji', t1.nama_penguji ?? d.nama_penguji ?? '');
            setVal('t1_tanggal_pengujian', t1.tanggal_pengujian ?? d.tanggal_pengujian ?? '');
            setVal('t1_tanggal_dilaporkan', t1.tanggal_dilaporkan ?? d.tanggal_dilaporkan ?? '');
            setVal('t1_hasil_uji', t1.hasil_uji ?? d.hasil_uji ?? '');
            setVal('t1_syarat', t1.syarat ?? d.syarat ?? '');

            setVal('t1_ks_fisik', t1.ks_fisik ?? '');
            setVal('t1_ks_penyimpanan', t1.ks_penyimpanan ?? '');
            setVal('t1_ks_wadah', t1.ks_wadah ?? '');
            setVal('t1_ks_labeling', t1.ks_labeling ?? '');
            setVal('t1_ks_segel', t1.ks_segel ?? '');
            setVal('t1_ks_paraf_tgl', t1.ks_paraf_tgl ?? '');

            setVal('t1_timb1_nama', t1.timb1_nama ?? '');
            setVal('t1_timb1_id', t1.timb1_id ?? '');
            setVal('t1_timb1_kalibrasi', t1.timb1_kalibrasi ?? '');
            setVal('t1_timb1_logbook', t1.timb1_logbook ?? '');
            setVal('t1_timb1_spesifikasi', t1.timb1_spesifikasi ?? '');
            setVal('t1_timb1_history', t1.timb1_history ?? '');

            setVal('t1_timb2_nama', t1.timb2_nama ?? '');
            setVal('t1_timb2_id', t1.timb2_id ?? '');
            setVal('t1_timb2_kalibrasi', t1.timb2_kalibrasi ?? '');
            setVal('t1_timb2_logbook', t1.timb2_logbook ?? '');
            setVal('t1_timb2_spesifikasi', t1.timb2_spesifikasi ?? '');
            setVal('t1_timb2_history', t1.timb2_history ?? '');
            setVal('t1_timb_paraf_tgl', t1.timb_paraf_tgl ?? '');

            setVal('t1_inst_nama', t1.inst_nama ?? '');
            setVal('t1_inst_id', t1.inst_id ?? '');
            setVal('t1_inst_kalibrasi', t1.inst_kalibrasi ?? '');
            setVal('t1_inst_logbook', t1.inst_logbook ?? '');
            setVal('t1_inst_spesifikasi', t1.inst_spesifikasi ?? '');
            setVal('t1_inst_history', t1.inst_history ?? '');
            setVal('t1_inst_paraf_tgl', t1.inst_paraf_tgl ?? '');

            setVal('t1_pipet_identitas_kalibrasi', t1.pipet_identitas_kalibrasi ?? '');
            setVal('t1_pipet_paraf_tgl', t1.pipet_paraf_tgl ?? '');

            setVal('t1_reagen_identitas', t1.reagen_identitas ?? '');
            setVal('t1_reagen_fisik', t1.reagen_fisik ?? '');
            setVal('t1_reagen_ed', t1.reagen_ed ?? '');
            setVal('t1_reagen_paraf_tgl', t1.reagen_paraf_tgl ?? '');

            setVal('t1_data_pengenceran', t1.data_pengenceran ?? '');
            setVal('t1_data_penimbangan', t1.data_penimbangan ?? '');
            setVal('t1_data_paraf_tgl', t1.data_paraf_tgl ?? '');

            setVal('t1_baku_identitas', t1.baku_identitas ?? '');
            setVal('t1_baku_kesesuaian', t1.baku_kesesuaian ?? '');
            setVal('t1_baku_paraf_tgl', t1.baku_paraf_tgl ?? '');

            setVal('t1_kromatogram_obs', t1.kromatogram_obs ?? '');
            setVal('t1_kromatogram_paraf_tgl', t1.kromatogram_paraf_tgl ?? '');

            setVal('t1_gelas_kebersihan', t1.gelas_kebersihan ?? '');
            setVal('t1_gelas_kesesuaian', t1.gelas_kesesuaian ?? '');
            setVal('t1_gelas_paraf_tgl', t1.gelas_paraf_tgl ?? '');

            setVal('t1_uks_hasil', t1.uks_hasil ?? '');
            setVal('t1_uks_paraf_tgl', t1.uks_paraf_tgl ?? '');

            setVal('t1_metode_identitas', t1.metode_identitas ?? '');
            setVal('t1_metode_verifikasi', t1.metode_verifikasi ?? '');
            setVal('t1_metode_paraf_tgl', t1.metode_paraf_tgl ?? '');

            setVal('t1_lingkungan_obs', t1.lingkungan_obs ?? '');
            setVal('t1_lingkungan_paraf_tgl', t1.lingkungan_paraf_tgl ?? '');

            setVal('t1_diskusi_1_pengamatan', t1.diskusi_1_pengamatan ?? '');
            setVal('t1_diskusi_2_pengamatan', t1.diskusi_2_pengamatan ?? '');
            setVal('t1_kesimpulan_investigator', t1.kesimpulan_investigator ?? '');
            setVal('t1_protokol_baru', t1.protokol_baru ?? '');
            setVal('t1_reanalisis_sampel', t1.reanalisis_sampel ?? '');
            setVal('t1_reanalisis_syarat', t1.reanalisis_syarat ?? '');
            setVal('t1_reanalisis_hasil', t1.reanalisis_hasil ?? '');
            setVal('t1_reanalisis_penguji', t1.reanalisis_penguji ?? '');
            setVal('t1_reanalisis_paraf_tgl', t1.reanalisis_paraf_tgl ?? '');
            setVal('t1_kesimpulan_fase1', t1.kesimpulan_fase1 ?? '');
            setVal('t1_tindakan_perbaikan', t1.tindakan_perbaikan ?? '');
            setVal('t1_verifikator_tahap1', t1.verifikator_tahap1 ?? '');
            setVal('t1_ketua_tim_jabatan', t1.ketua_tim_jabatan ?? '');
            setVal('t1_ketua_tim_nama', t1.ketua_tim_nama ?? '');

            if (activeHulsFormTab === 'preview') {
                renderHulsWordExactPreview();
            }
        };

        window.collectHulsFormPayload = function(auditAction) {
            const pre = {
                nama_sampel: getVal('pre_nama_sampel'),
                kode_sampel: getVal('pre_kode_sampel'),
                nomor_huls: getVal('pre_nomor_huls'),
                parameter_uji: getVal('pre_parameter_uji'),
                nama_penguji: getVal('pre_nama_penguji'),
                tanggal_pengujian: getVal('pre_tanggal_pengujian'),
                tanggal_dilaporkan: getVal('pre_tanggal_dilaporkan'),
                hasil_uji: getVal('pre_hasil_uji'),
                syarat: getVal('pre_syarat'),
                obs_1_kondisi_sampel: getVal('pre_obs_1'),
                paraf_1_kondisi_sampel: getVal('pre_paraf_1'),
                tgl_1_kondisi_sampel: getVal('pre_tgl_1'),
                obs_2_pemindahan_data: getVal('pre_obs_2'),
                paraf_2_pemindahan_data: getVal('pre_paraf_2'),
                tgl_2_pemindahan_data: getVal('pre_tgl_2'),
                obs_3_rumus_perhitungan: getVal('pre_obs_3'),
                paraf_3_rumus_perhitungan: getVal('pre_paraf_3'),
                tgl_3_rumus_perhitungan: getVal('pre_tgl_3'),
                koreksi_catatan: getVal('pre_koreksi_catatan'),
                koreksi_tertulis_1: getVal('pre_koreksi_tertulis_1'),
                koreksi_seharusnya_1: getVal('pre_koreksi_seharusnya_1'),
                hasil_koreksi_ket: getVal('pre_hasil_koreksi_ket'),
                hk_sampel_1: getVal('pre_hk_sampel_1'),
                hk_syarat_1: getVal('pre_hk_syarat_1'),
                hk_hasil_1: getVal('pre_hk_hasil_1'),
                hk_rsd: getVal('pre_hk_rsd'),
                reanalisa_sampel_1: getVal('pre_reanalisa_sampel_1'),
                reanalisa_syarat_1: getVal('pre_reanalisa_syarat_1'),
                reanalisa_hasil_1: getVal('pre_reanalisa_hasil_1'),
                reanalisa_rsd: getVal('pre_reanalisa_rsd'),
                kesimpulan_pre: getVal('pre_kesimpulan'),
                diuji_oleh: getVal('pre_diuji_oleh'),
                verifikator: getVal('pre_verifikator')
            };

            const tahap1 = {
                nama_sampel: getVal('t1_nama_sampel') || pre.nama_sampel,
                kode_sampel: getVal('t1_kode_sampel') || pre.kode_sampel,
                nomor_huls: getVal('t1_nomor_huls') || pre.nomor_huls,
                parameter_uji: getVal('t1_parameter_uji') || pre.parameter_uji,
                nama_penguji: getVal('t1_nama_penguji') || pre.nama_penguji,
                tanggal_pengujian: getVal('t1_tanggal_pengujian') || pre.tanggal_pengujian,
                tanggal_dilaporkan: getVal('t1_tanggal_dilaporkan') || pre.tanggal_dilaporkan,
                hasil_uji: getVal('t1_hasil_uji') || pre.hasil_uji,
                syarat: getVal('t1_syarat') || pre.syarat,
                ks_fisik: getVal('t1_ks_fisik'),
                ks_penyimpanan: getVal('t1_ks_penyimpanan'),
                ks_wadah: getVal('t1_ks_wadah'),
                ks_labeling: getVal('t1_ks_labeling'),
                ks_segel: getVal('t1_ks_segel'),
                ks_paraf_tgl: getVal('t1_ks_paraf_tgl'),
                timb1_nama: getVal('t1_timb1_nama'),
                timb1_id: getVal('t1_timb1_id'),
                timb1_kalibrasi: getVal('t1_timb1_kalibrasi'),
                timb1_logbook: getVal('t1_timb1_logbook'),
                timb1_spesifikasi: getVal('t1_timb1_spesifikasi'),
                timb1_history: getVal('t1_timb1_history'),
                timb2_nama: getVal('t1_timb2_nama'),
                timb2_id: getVal('t1_timb2_id'),
                timb2_kalibrasi: getVal('t1_timb2_kalibrasi'),
                timb2_logbook: getVal('t1_timb2_logbook'),
                timb2_spesifikasi: getVal('t1_timb2_spesifikasi'),
                timb2_history: getVal('t1_timb2_history'),
                timb_paraf_tgl: getVal('t1_timb_paraf_tgl'),
                inst_nama: getVal('t1_inst_nama'),
                inst_id: getVal('t1_inst_id'),
                inst_kalibrasi: getVal('t1_inst_kalibrasi'),
                inst_logbook: getVal('t1_inst_logbook'),
                inst_spesifikasi: getVal('t1_inst_spesifikasi'),
                inst_history: getVal('t1_inst_history'),
                inst_paraf_tgl: getVal('t1_inst_paraf_tgl'),
                pipet_identitas_kalibrasi: getVal('t1_pipet_identitas_kalibrasi'),
                pipet_paraf_tgl: getVal('t1_pipet_paraf_tgl'),
                reagen_identitas: getVal('t1_reagen_identitas'),
                reagen_fisik: getVal('t1_reagen_fisik'),
                reagen_ed: getVal('t1_reagen_ed'),
                reagen_paraf_tgl: getVal('t1_reagen_paraf_tgl'),
                data_pengenceran: getVal('t1_data_pengenceran'),
                data_penimbangan: getVal('t1_data_penimbangan'),
                data_paraf_tgl: getVal('t1_data_paraf_tgl'),
                baku_identitas: getVal('t1_baku_identitas'),
                baku_kesesuaian: getVal('t1_baku_kesesuaian'),
                baku_paraf_tgl: getVal('t1_baku_paraf_tgl'),
                kromatogram_obs: getVal('t1_kromatogram_obs'),
                kromatogram_paraf_tgl: getVal('t1_kromatogram_paraf_tgl'),
                gelas_kebersihan: getVal('t1_gelas_kebersihan'),
                gelas_kesesuaian: getVal('t1_gelas_kesesuaian'),
                gelas_paraf_tgl: getVal('t1_gelas_paraf_tgl'),
                uks_hasil: getVal('t1_uks_hasil'),
                uks_paraf_tgl: getVal('t1_uks_paraf_tgl'),
                metode_identitas: getVal('t1_metode_identitas'),
                metode_verifikasi: getVal('t1_metode_verifikasi'),
                metode_paraf_tgl: getVal('t1_metode_paraf_tgl'),
                lingkungan_obs: getVal('t1_lingkungan_obs'),
                lingkungan_paraf_tgl: getVal('t1_lingkungan_paraf_tgl'),
                diskusi_1_pengamatan: getVal('t1_diskusi_1_pengamatan'),
                diskusi_2_pengamatan: getVal('t1_diskusi_2_pengamatan'),
                kesimpulan_investigator: getVal('t1_kesimpulan_investigator'),
                protokol_baru: getVal('t1_protokol_baru'),
                reanalisis_sampel: getVal('t1_reanalisis_sampel'),
                reanalisis_syarat: getVal('t1_reanalisis_syarat'),
                reanalisis_hasil: getVal('t1_reanalisis_hasil'),
                reanalisis_penguji: getVal('t1_reanalisis_penguji'),
                reanalisis_paraf_tgl: getVal('t1_reanalisis_paraf_tgl'),
                kesimpulan_fase1: getVal('t1_kesimpulan_fase1'),
                tindakan_perbaikan: getVal('t1_tindakan_perbaikan'),
                verifikator_tahap1: getVal('t1_verifikator_tahap1'),
                ketua_tim_jabatan: getVal('t1_ketua_tim_jabatan'),
                ketua_tim_nama: getVal('t1_ketua_tim_nama')
            };

            const savedFromTab = (auditAction === 'SIMPAN_TAHAP_1' || activeHulsFormTab === 'tahap1') ? 'tahap1' : 'pre';
            pre._saved_tab = savedFromTab;
            tahap1._saved_tab = savedFromTab;

            return {
                id: parseInt(getVal('hulsCurrentDocId') || '0', 10),
                nama_sampel: pre.nama_sampel || tahap1.nama_sampel,
                kode_sampel: pre.kode_sampel || tahap1.kode_sampel,
                nomor_huls: pre.nomor_huls || tahap1.nomor_huls,
                parameter_uji: pre.parameter_uji || tahap1.parameter_uji,
                nama_penguji: pre.nama_penguji || tahap1.nama_penguji,
                tanggal_pengujian: pre.tanggal_pengujian || tahap1.tanggal_pengujian,
                tanggal_dilaporkan: pre.tanggal_dilaporkan || tahap1.tanggal_dilaporkan,
                hasil_uji: pre.hasil_uji || tahap1.hasil_uji,
                syarat: pre.syarat || tahap1.syarat,
                status_dokumen: getVal('hulsStatusDokumen') || 'Draft',
                pre_investigasi: pre,
                tahap1: tahap1,
                audit_action: auditAction || 'UPDATE_DOKUMEN_HULS'
            };
        };

        window.loadHulsDocuments = function() {
            const q = getVal('hulsSearchInput');
            const st = getVal('hulsStatusFilter');
            const url = `api/data?key=hulsDocuments&q=${encodeURIComponent(q)}&status=${encodeURIComponent(st)}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data && Array.isArray(data.documents)) {
                        hulsDocumentsCache = data.documents;
                        renderHulsDocumentTable(data.documents);
                        renderHulsAuditTable(data.audit_logs || []);
                        hulsInitializedOnce = true;
                    }
                })
                .catch(() => {
                    renderHulsDocumentTable(hulsDocumentsCache);
                    hulsInitializedOnce = true;
                });
        };

        let currentSavedPreviewDoc = null;

        window.switchHulsWorkspaceTab = function(tabKey) {
            const viewForm = document.getElementById('hulsMainViewForm');
            const viewList = document.getElementById('hulsMainViewList');
            const viewAudit = document.getElementById('hulsMainViewAudit');

            const btnForm = document.getElementById('btnMainHulsTabForm');
            const btnList = document.getElementById('btnMainHulsTabList');
            const btnAudit = document.getElementById('btnMainHulsTabAudit');
            const bcLabel = document.getElementById('hulsBreadcrumbActiveLabel');

            if (viewForm) viewForm.style.display = (tabKey === 'form') ? 'block' : 'none';
            if (viewList) viewList.style.display = (tabKey === 'list') ? 'block' : 'none';
            if (viewAudit) viewAudit.style.display = (tabKey === 'audit') ? 'block' : 'none';

            if (btnForm) btnForm.classList.toggle('active', tabKey === 'form');
            if (btnList) btnList.classList.toggle('active', tabKey === 'list');
            if (btnAudit) btnAudit.classList.toggle('active', tabKey === 'audit');

            if (bcLabel) {
                if (tabKey === 'list') bcLabel.textContent = 'Daftar Dokumen HULS';
                else if (tabKey === 'audit') bcLabel.textContent = 'Riwayat / Audit Trail';
                else bcLabel.textContent = 'Formulir HULS Baru';
            }

            if (tabKey === 'list' || tabKey === 'audit') {
                loadHulsDocuments();
            }
        };

        window.hideHulsSaveAlert = function() {
            const el = document.getElementById('hulsSaveSuccessAlert');
            if (el) el.style.setProperty('display', 'none', 'important');
        };

        window.showHulsSaveAlert = function(msg) {
            const el = document.getElementById('hulsSaveSuccessAlert');
            const txt = document.getElementById('hulsSaveSuccessText');
            if (txt && msg) txt.textContent = msg;
            if (el) el.style.setProperty('display', 'flex', 'important');
        };

        window.setHulsEditBanner = function(isEditing, docLabel) {
            const banner = document.getElementById('hulsEditingDocBanner');
            const labelEl = document.getElementById('hulsEditingDocLabel');
            const headingEl = document.getElementById('hulsFormMainHeading');
            if (isEditing) {
                if (labelEl) labelEl.textContent = docLabel || '';
                if (banner) banner.style.setProperty('display', 'flex', 'important');
                if (headingEl) headingEl.textContent = 'Edit Dokumen HULS Tersimpan (' + (docLabel || '') + ')';
            } else {
                if (banner) banner.style.setProperty('display', 'none', 'important');
                if (headingEl) headingEl.textContent = 'Formulir Hasil Uji di Luar Spesifikasi (HULS) — Dokumen Baru';
            }
        };

        window.cancelEditAndStartNewHuls = function() {
            setHulsEditBanner(false);
            populateHulsForm(HULS_DEFAULT_DOC);
            switchHulsFormTab('pre');
        };

        function detectHulsDocTypeInfo(doc) {
            const pre = (doc && doc.pre_investigasi) || {};
            const t1 = (doc && doc.tahap1) || {};

            const hasPreFields = Boolean(
                pre.obs_1_kondisi_sampel || pre.obs_2_pemindahan_data || pre.obs_3_rumus_perhitungan ||
                pre.kesimpulan_pre || pre.diuji_oleh || pre.verifikator || pre.hk_hasil_1
            );
            const hasTahap1Fields = Boolean(
                t1.ks_fisik || t1.timb1_nama || t1.inst_nama || t1.reagen_identitas ||
                t1.baku_identitas || t1.kesimpulan_investigator || t1.kesimpulan_fase1 ||
                t1.verifikator_tahap1 || t1.ketua_tim_nama
            );

            const savedTab = t1._saved_tab || pre._saved_tab || '';

            if (hasPreFields && hasTahap1Fields) {
                return {
                    key: 'both',
                    defaultMode: 'both',
                    badgeHtml: '<div class="d-flex flex-column gap-1 align-items-start"><span class="badge bg-label-primary">1. Pre-Investigasi HULS</span><span class="badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">2. Investigasi Tahap 1</span></div>'
                };
            }
            if (savedTab === 'tahap1' || hasTahap1Fields) {
                return {
                    key: 'tahap1',
                    defaultMode: 'tahap1',
                    badgeHtml: '<span class="badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">Investigasi Tahap 1</span>'
                };
            }
            return {
                key: 'pre',
                defaultMode: 'pre',
                badgeHtml: '<span class="badge bg-label-primary">Pre-Investigasi HULS</span>'
            };
        }

        function renderHulsDocumentTable(docs) {
            const tbody = document.getElementById('hulsDocTableBody');
            const countEl = document.getElementById('hulsTotalDocCount');
            const allDocs = Array.isArray(docs) ? docs : [];
            if (countEl) countEl.textContent = allDocs.length;
            if (!tbody) return;

            if (!allDocs.length) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">Belum ada dokumen HULS tersimpan. Silakan isi pada tab <strong>Formulir HULS Baru</strong>.</td></tr>';
                return;
            }

            tbody.innerHTML = allDocs.map(doc => {
                const st = doc.status_dokumen || 'Draft';
                const badgeCls = st === 'Disetujui' ? 'bg-label-success' : (st === 'Dalam Verifikasi' ? 'bg-label-info' : 'bg-label-warning');
                return `
                    <tr>
                        <td class="fw-bold text-primary">${escHtml(doc.nomor_huls)}</td>
                        <td><code>${escHtml(doc.kode_sampel)}</code></td>
                        <td class="fw-semibold">${escHtml(doc.nama_sampel)}</td>
                        <td class="text-muted small">${escHtml(doc.parameter_uji)}</td>
                        <td>${escHtml(doc.nama_penguji)}</td>
                        <td><span class="badge ${badgeCls}">${escHtml(st)}</span></td>
                        <td class="small text-muted">${escHtml(doc.updated_at || '')}</td>
                        <td class="text-end text-nowrap">
                            <button type="button" class="btn btn-xs btn-primary py-1 px-2" style="background:#034284;" onclick="viewSavedHulsDoc(${doc.id}, 'both')" title="Lihat Detail & Cetak PDF Dokumen Ini">
                                <i class="ti ti-eye me-1"></i> Lihat / PDF
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2" onclick="selectHulsDocById(${doc.id})" title="Edit Isian Dokumen Ini">
                                <i class="ti ti-edit"></i> Edit
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-danger py-1 px-2" onclick="deleteHulsDocById(${doc.id})" title="Hapus Dokumen">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderHulsAuditTable(logs) {
            const tbody = document.getElementById('hulsAuditTableBody');
            if (!tbody) return;
            if (!logs.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat perubahan.</td></tr>';
                return;
            }
            tbody.innerHTML = logs.map(l => `
                <tr>
                    <td class="text-nowrap">${escHtml(l.created_at)}</td>
                    <td class="fw-bold">${escHtml(l.nomor_huls)}</td>
                    <td><span class="badge bg-label-primary">${escHtml(l.action)}</span></td>
                    <td>${escHtml(l.actor_name)} <span class="text-muted">(${escHtml(l.actor_role)})</span></td>
                    <td>${escHtml(l.notes)}</td>
                </tr>
            `).join('');
        }

        window.viewSavedHulsDoc = function(id, mode) {
            const found = hulsDocumentsCache.find(d => Number(d.id) === Number(id));
            if (!found) return;
            currentSavedPreviewDoc = found;
            const box = document.getElementById('hulsSavedDocPreviewBox');
            const titleEl = document.getElementById('hulsSavedPreviewTitle');
            if (titleEl) titleEl.textContent = `${found.nomor_huls || '-'} — ${found.nama_sampel || '-'} (${found.kode_sampel || '-'})`;
            if (mode) setVal('hulsSavedPreviewMode', mode);
            if (box) {
                box.style.display = 'block';
                renderSavedDocWordPreview();
                box.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        window.renderSavedDocWordPreview = function() {
            if (!currentSavedPreviewDoc) return;
            const mode = getVal('hulsSavedPreviewMode') || 'both';
            const container = document.getElementById('hulsSavedWordPrintArea');
            if (!container) return;
            const pre = currentSavedPreviewDoc.pre_investigasi || {};
            const t1 = currentSavedPreviewDoc.tahap1 || {};
            let html = '';
            if (mode === 'pre') {
                html = buildWordExactPreHtml(pre);
            } else if (mode === 'tahap1') {
                html = buildWordExactTahap1Html(t1, false);
            } else {
                html = buildWordExactPreHtml(pre) + buildWordExactTahap1Html(t1, true);
            }
            container.innerHTML = html;
        };

        window.downloadSavedHulsPdf = function() {
            if (!currentSavedPreviewDoc) return;
            renderSavedDocWordPreview();
            const printArea = document.getElementById('hulsSavedWordPrintArea');
            if (!printArea) return;
            const mode = getVal('hulsSavedPreviewMode') || 'both';
            const safeCode = (currentSavedPreviewDoc.kode_sampel || 'Sampel').replace(/[^a-zA-Z0-9._-]/g, '_');
            const prefix = mode === 'pre' ? 'HULS_Pre_Investigasi_' : (mode === 'tahap1' ? 'HULS_Investigasi_Tahap_1_' : 'HULS_Lengkap_');
            if (window.html2pdf) {
                window.html2pdf().set({
                    margin: [10, 10, 10, 10],
                    filename: prefix + safeCode + '.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                    pagebreak: { mode: ['css', 'legacy'] }
                }).from(printArea).save();
            } else {
                printSavedHulsWindow();
            }
        };

        window.printSavedHulsWindow = function() {
            if (!currentSavedPreviewDoc) return;
            renderSavedDocWordPreview();
            const printArea = document.getElementById('hulsSavedWordPrintArea');
            if (!printArea) return;
            openWordPrintPopupWindow(printArea.innerHTML, currentSavedPreviewDoc.kode_sampel || 'BBPOM');
        };

        window.selectHulsDocById = function(id) {
            const found = hulsDocumentsCache.find(d => Number(d.id) === Number(id));
            if (found) {
                hideHulsSaveAlert();
                populateHulsForm(found);
                const typeInfo = detectHulsDocTypeInfo(found);
                setHulsEditBanner(true, `${found.nomor_huls} — ${found.nama_sampel}`);
                switchHulsWorkspaceTab('form');
                switchHulsFormTab(typeInfo.key === 'tahap1' ? 'tahap1' : 'pre');
            }
        };

        window.selectAndExportHulsDoc = function(id, mode) {
            viewSavedHulsDoc(id, mode || 'both');
        };

        window.createNewHulsForm = function() {
            setHulsEditBanner(false);
            populateHulsForm(HULS_DEFAULT_DOC);
            switchHulsFormTab('pre');
        };

        window.fillHulsPengujiFromLogin = function() {
            setVal('pre_nama_penguji', LOGGED_IN_USER_NAME);
            setVal('pre_diuji_oleh', LOGGED_IN_USER_NAME);
            setVal('t1_nama_penguji', LOGGED_IN_USER_NAME);
            setVal('t1_reanalisis_penguji', LOGGED_IN_USER_NAME);
        };

        window.copyHeaderFromPreToTahap1 = function() {
            setVal('t1_nama_sampel', getVal('pre_nama_sampel'));
            setVal('t1_kode_sampel', getVal('pre_kode_sampel'));
            setVal('t1_nomor_huls', getVal('pre_nomor_huls'));
            if (!getVal('t1_parameter_uji')) setVal('t1_parameter_uji', getVal('pre_parameter_uji'));
            setVal('t1_nama_penguji', getVal('pre_nama_penguji'));
            if (!getVal('t1_tanggal_pengujian')) setVal('t1_tanggal_pengujian', getVal('pre_tanggal_pengujian'));
            setVal('t1_tanggal_dilaporkan', getVal('pre_tanggal_dilaporkan'));
            setVal('t1_hasil_uji', getVal('pre_hasil_uji'));
            setVal('t1_syarat', getVal('pre_syarat'));
            if (!getVal('t1_reanalisis_sampel')) setVal('t1_reanalisis_sampel', getVal('pre_kode_sampel'));
        };

        window.syncPreToTahap1AndSwitch = function() {
            copyHeaderFromPreToTahap1();
            switchHulsFormTab('tahap1');
        };

        window.pullIoTEnvToHuls = function() {
            const suhuEl = document.getElementById('valSuhu');
            const humEl = document.getElementById('valKelembapan');
            const suhu = (suhuEl && suhuEl.textContent && !suhuEl.textContent.includes('--')) ? suhuEl.textContent.trim() : '23.4';
            const hum = (humEl && humEl.textContent && !humEl.textContent.includes('--')) ? humEl.textContent.trim() : '54';
            setVal('t1_lingkungan_obs', `Suhu ruangan : ${suhu}ºC\nKelembaban ruangan : ${hum}%`);
        };

        window.saveHulsDocumentToServer = function(auditAction) {
            const payload = collectHulsFormPayload(auditAction);
            if (!payload.nama_sampel || !payload.kode_sampel || !payload.nomor_huls) {
                alert('Mohon lengkapi Nama Sampel, Kode Sampel, dan Nomor HULS terlebih dahulu.');
                return;
            }

            fetch('api/data?key=saveHulsDocument', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
                .then(res => res.json())
                .then(data => {
                    if (data && data.status === 'success') {
                        if (Array.isArray(data.documents)) {
                            hulsDocumentsCache = data.documents;
                            renderHulsDocumentTable(data.documents);
                        }
                        if (Array.isArray(data.audit_logs)) {
                            renderHulsAuditTable(data.audit_logs);
                        }
                        // Otomatis reset form menjadi Formulir HULS Baru yang kosong setelah tersimpan
                        setHulsEditBanner(false);
                        populateHulsForm(HULS_DEFAULT_DOC);
                        switchHulsFormTab('pre');
                        showHulsSaveAlert(`Dokumen HULS (${payload.nomor_huls} — ${payload.nama_sampel}) berhasil disimpan! Formulir kini telah disiapkan kembali untuk pengisian dokumen baru.`);
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        alert((data && data.messages && data.messages.error) || 'Gagal menyimpan dokumen HULS.');
                    }
                })
                .catch(err => {
                    alert('Gagal menghubungi server: ' + err.message);
                });
        };

        window.deleteHulsDocById = function(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus dokumen HULS ini dari database?')) return;
            fetch('api/data?key=deleteHulsDocument', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
                .then(res => res.json())
                .then(data => {
                    if (data && Array.isArray(data.documents)) {
                        hulsDocumentsCache = data.documents;
                        renderHulsDocumentTable(data.documents);
                        renderHulsAuditTable(data.audit_logs || []);
                        if (currentSavedPreviewDoc && Number(currentSavedPreviewDoc.id) === Number(id)) {
                            currentSavedPreviewDoc = null;
                            const box = document.getElementById('hulsSavedDocPreviewBox');
                            if (box) box.style.display = 'none';
                        }
                    }
                });
        };

        // =========================================================================
        // WORD-EXACT HTML & PDF GENERATOR (PERSIS DOKUMEN WORD HULS BBPOM)
        // =========================================================================
        function buildWordExactPreHtml(pre) {
            const isMs = pre.kesimpulan_pre === 'MS_DILAPORKAN';
            const isTms = pre.kesimpulan_pre === 'TMS_LANJUT_TAHAP_1';
            const checkMs = isMs ? '&#10003;' : '&nbsp;&nbsp;&nbsp;';
            const checkTms = isTms ? '&#10003;' : '&nbsp;&nbsp;&nbsp;';

            const parafTgl1 = [pre.paraf_1_kondisi_sampel, pre.tgl_1_kondisi_sampel].filter(Boolean).join(' — ');
            const parafTgl2 = [pre.paraf_2_pemindahan_data, pre.tgl_2_pemindahan_data].filter(Boolean).join(' — ');
            const parafTgl3 = [pre.paraf_3_rumus_perhitungan, pre.tgl_3_rumus_perhitungan].filter(Boolean).join(' — ');

            return `
                <div class="word-doc-sheet">
                    <h4 class="word-doc-heading">Informasi Pengujian</h4>
                    <table class="word-table">
                        <tbody>
                            <tr>
                                <td style="width: 30%;">Nama Sampel</td>
                                <td style="width: 3%; text-align:center;">:</td>
                                <td>${escHtml(pre.nama_sampel)}</td>
                            </tr>
                            <tr>
                                <td>Kode Sampel</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(pre.kode_sampel)}</td>
                            </tr>
                            <tr>
                                <td>Parameter Uji/Metode</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(pre.parameter_uji)}</td>
                            </tr>
                            <tr>
                                <td>Nama Penguji</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(pre.nama_penguji)}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengujian</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(pre.tanggal_pengujian)}</td>
                            </tr>
                            <tr>
                                <td>Tanggal dilaporkan</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(pre.tanggal_dilaporkan)}</td>
                            </tr>
                            <tr>
                                <td>Hasil Uji</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(pre.hasil_uji)}</td>
                            </tr>
                            <tr>
                                <td>Syarat</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(pre.syarat)}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 class="word-doc-heading">Pre-Investigasi HULS</h4>
                    <table class="word-table">
                        <tbody>
                            <tr>
                                <th style="width: 6%;">No.</th>
                                <th colspan="2" style="width: 28%;">Parameter</th>
                                <th colspan="4" style="width: 40%;">Observasi</th>
                                <th style="width: 26%;">Paraf dan Tanggal</th>
                            </tr>
                            <tr>
                                <td style="text-align:center;">1</td>
                                <td colspan="2">Kondisi sampel</td>
                                <td colspan="4" class="word-preline">${escHtml(pre.obs_1_kondisi_sampel)}</td>
                                <td>${escHtml(parafTgl1)}</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">2</td>
                                <td colspan="2">Pemindahan data</td>
                                <td colspan="4" class="word-preline">${escHtml(pre.obs_2_pemindahan_data)}</td>
                                <td>${escHtml(parafTgl2)}</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">3</td>
                                <td colspan="2">Rumus dan perhitungan</td>
                                <td colspan="4" class="word-preline">${escHtml(pre.obs_3_rumus_perhitungan)}</td>
                                <td>${escHtml(parafTgl3)}</td>
                            </tr>
                            <tr>
                                <td colspan="8"><strong>Koreksi:</strong> ${escHtml(pre.koreksi_catatan)}</td>
                            </tr>
                            <tr>
                                <th style="width: 6%;">No.</th>
                                <th colspan="4">Tertulis</th>
                                <th colspan="3">Seharusnya</th>
                            </tr>
                            <tr>
                                <td style="text-align:center;">1</td>
                                <td colspan="4">${escHtml(pre.koreksi_tertulis_1)}</td>
                                <td colspan="3">${escHtml(pre.koreksi_seharusnya_1)}</td>
                            </tr>
                            <tr>
                                <td colspan="8"><strong>Hasil Koreksi:</strong> ${escHtml(pre.hasil_koreksi_ket)}</td>
                            </tr>
                            <tr>
                                <th style="width: 6%;">No.</th>
                                <th>Sampel</th>
                                <th colspan="4">Syarat</th>
                                <th colspan="2">Hasil</th>
                            </tr>
                            <tr>
                                <td style="text-align:center;">1</td>
                                <td>${escHtml(pre.hk_sampel_1)}</td>
                                <td colspan="4">${escHtml(pre.hk_syarat_1)}</td>
                                <td colspan="2">${escHtml(pre.hk_hasil_1)}</td>
                            </tr>
                            <tr>
                                <td colspan="6"><strong>RSD</strong></td>
                                <td colspan="2">${escHtml(pre.hk_rsd)}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="height: 46px;"><strong>Diuji Oleh:</strong> ${escHtml(pre.diuji_oleh)}</td>
                                <td colspan="4" style="height: 46px;"><strong>Verifikator:</strong> ${escHtml(pre.verifikator)}</td>
                            </tr>
                            <tr>
                                <td colspan="8"><strong>Re-analisa dengan sampel yang sama jika hasil koreksi telah memenuhi syarat</strong></td>
                            </tr>
                            <tr>
                                <th style="width: 6%;">No.</th>
                                <th>Sampel</th>
                                <th colspan="4">Syarat</th>
                                <th colspan="2">Hasil</th>
                            </tr>
                            <tr>
                                <td style="text-align:center;">1</td>
                                <td>${escHtml(pre.reanalisa_sampel_1)}</td>
                                <td colspan="4">${escHtml(pre.reanalisa_syarat_1)}</td>
                                <td colspan="2">${escHtml(pre.reanalisa_hasil_1)}</td>
                            </tr>
                            <tr>
                                <td colspan="6"><strong>RSD</strong></td>
                                <td colspan="2">${escHtml(pre.reanalisa_rsd)}</td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding: 8px 7px;">
                                    <div><strong>Kesimpulan dari Pre-Investigasi:</strong></div>
                                    <div style="margin-top: 4px;">
                                        [ ${checkMs} ] Memenuhi Syarat dan hasil dilaporkan
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        [ ${checkTms} ] Tidak Memenuhi Syarat dan dilanjutkan ke Investigasi Tahap 1
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="height: 52px;"><strong>Diuji Oleh:</strong> ${escHtml(pre.diuji_oleh)}</td>
                                <td colspan="4" style="height: 52px;"><strong>Verifikator:</strong> ${escHtml(pre.verifikator)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }

        function buildWordExactTahap1Html(t1, includePageBreak) {
            const isProtokolYa = (t1.protokol_baru === 'Ya');
            const isProtokolTidak = (t1.protokol_baru === 'Tidak');
            const chkYa = isProtokolYa ? '&#10003;' : '&nbsp;&nbsp;&nbsp;';
            const chkTidak = isProtokolTidak ? '&#10003;' : '&nbsp;&nbsp;&nbsp;';

            return `
                <div class="word-doc-sheet ${includePageBreak ? 'word-page-break' : ''}">
                    <div class="word-doc-center-title">
                        <div>INVESTIGASI TAHAP 1</div>
                        <div>HASIL UJI DI LUAR SPESIFIKASI (HULS)</div>
                        <div>NO. ${escHtml(t1.nomor_huls)}</div>
                    </div>

                    <h4 class="word-doc-heading">Informasi Pengujian</h4>
                    <table class="word-table">
                        <tbody>
                            <tr>
                                <td style="width: 30%;">Nama Sampel</td>
                                <td style="width: 3%; text-align:center;">:</td>
                                <td>${escHtml(t1.nama_sampel)}</td>
                            </tr>
                            <tr>
                                <td>Kode Sampel</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(t1.kode_sampel)}</td>
                            </tr>
                            <tr>
                                <td>Parameter Uji/Metode</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(t1.parameter_uji)}</td>
                            </tr>
                            <tr>
                                <td>Nama Penguji</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(t1.nama_penguji)}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengujian</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(t1.tanggal_pengujian)}</td>
                            </tr>
                            <tr>
                                <td>Tanggal dilaporkan</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(t1.tanggal_dilaporkan)}</td>
                            </tr>
                            <tr>
                                <td>Hasil Uji</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(t1.hasil_uji)}</td>
                            </tr>
                            <tr>
                                <td>Syarat</td>
                                <td style="text-align:center;">:</td>
                                <td>${escHtml(t1.syarat)}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 class="word-doc-heading">Investigasi HULS Tahap 1</h4>
                    <table class="word-table">
                        <tbody>
                            <tr>
                                <th style="width: 6%;">No.</th>
                                <th style="width: 34%;">Parameter</th>
                                <th style="width: 42%;">Observasi</th>
                                <th style="width: 18%;">Paraf dan Tanggal</th>
                            </tr>

                            <!-- 1. Kondisi Sampel -->
                            <tr>
                                <td style="text-align:center;">1</td>
                                <td colspan="2"><strong>Kondisi Sampel:</strong></td>
                                <td rowspan="6">${escHtml(t1.ks_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Pengamatan kondisi fisik</td>
                                <td class="word-preline">${escHtml(t1.ks_fisik)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Kesesuaian kondisi penyimpanan</td>
                                <td>${escHtml(t1.ks_penyimpanan)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Wadah penyimpanan</td>
                                <td>${escHtml(t1.ks_wadah)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Labeling</td>
                                <td>${escHtml(t1.ks_labeling)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Segel</td>
                                <td>${escHtml(t1.ks_segel)}</td>
                            </tr>

                            <!-- 2. Pengecekan Alat (timbangan) -->
                            <tr>
                                <td style="text-align:center;">2</td>
                                <td colspan="2"><strong>Pengecekan Alat (timbangan):</strong></td>
                                <td rowspan="13">${escHtml(t1.timb_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Nama alat :</td>
                                <td>${escHtml(t1.timb1_nama)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Nomor identitas :</td>
                                <td>${escHtml(t1.timb1_id)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Status kalibrasi dan jadwal :</td>
                                <td>${escHtml(t1.timb1_kalibrasi)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Review log book alat :</td>
                                <td>${escHtml(t1.timb1_logbook)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Pemenuhan spesifikasi/persyaratan :</td>
                                <td>${escHtml(t1.timb1_spesifikasi)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Catatan riwayat alat/history :</td>
                                <td>${escHtml(t1.timb1_history)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Nama alat :</td>
                                <td>${escHtml(t1.timb2_nama)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Nomor identitas :</td>
                                <td>${escHtml(t1.timb2_id)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Status kalibrasi dan jadwal :</td>
                                <td>${escHtml(t1.timb2_kalibrasi)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Review log book alat :</td>
                                <td>${escHtml(t1.timb2_logbook)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Pemenuhan spesifikasi/persyaratan :</td>
                                <td>${escHtml(t1.timb2_spesifikasi)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Catatan riwayat alat/history :</td>
                                <td>${escHtml(t1.timb2_history)}</td>
                            </tr>

                            <!-- 3. Pengecekan Kalibrasi Instrumen -->
                            <tr>
                                <td style="text-align:center;">3</td>
                                <td colspan="2"><strong>Pengecekan Kalibrasi Instrumen :</strong></td>
                                <td rowspan="7">${escHtml(t1.inst_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Nama Instrumen :</td>
                                <td class="word-preline">${escHtml(t1.inst_nama)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Nomor Identitas :</td>
                                <td class="word-preline">${escHtml(t1.inst_id)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Status kalibrasi dan jadwal :</td>
                                <td class="word-preline">${escHtml(t1.inst_kalibrasi)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Review Log book Alat :</td>
                                <td>${escHtml(t1.inst_logbook)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Pemenuhan spesifikasi/persyaratan:</td>
                                <td>${escHtml(t1.inst_spesifikasi)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Catatan riwayat alat/history :</td>
                                <td>${escHtml(t1.inst_history)}</td>
                            </tr>

                            <!-- 4. Pengecekan alat mikropipet -->
                            <tr>
                                <td style="text-align:center;">4</td>
                                <td colspan="2"><strong>Pengecekan alat mikropipet :</strong></td>
                                <td rowspan="2">${escHtml(t1.pipet_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Nomor Identitas :<br>- Kalibrasi terakhir :</td>
                                <td class="word-preline">${escHtml(t1.pipet_identitas_kalibrasi)}</td>
                            </tr>

                            <!-- 5. Reagen yang digunakan -->
                            <tr>
                                <td style="text-align:center;">5</td>
                                <td colspan="2"><strong>Reagen yang digunakan:</strong></td>
                                <td rowspan="4">${escHtml(t1.reagen_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Identitas :</td>
                                <td class="word-preline">${escHtml(t1.reagen_identitas)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Penampakan fisik :</td>
                                <td>${escHtml(t1.reagen_fisik)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Kedaluarsa reagen:</td>
                                <td class="word-preline">${escHtml(t1.reagen_ed)}</td>
                            </tr>

                            <!-- 6. Pemindahan/pengolahan data -->
                            <tr>
                                <td style="text-align:center;">6</td>
                                <td colspan="2"><strong>Pemindahan/pengolahan data:</strong></td>
                                <td rowspan="3">${escHtml(t1.data_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Pengenceran :</td>
                                <td>${escHtml(t1.data_pengenceran)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Penimbangan :</td>
                                <td>${escHtml(t1.data_penimbangan)}</td>
                            </tr>

                            <!-- 7. Baku Pembanding -->
                            <tr>
                                <td style="text-align:center;">7</td>
                                <td colspan="2"><strong>Baku Pembanding :</strong></td>
                                <td rowspan="3">${escHtml(t1.baku_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Identitas:</td>
                                <td class="word-preline">${escHtml(t1.baku_identitas)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Kesesuaian penggunaan baku pembanding yang digunakan :</td>
                                <td>${escHtml(t1.baku_kesesuaian)}</td>
                            </tr>

                            <!-- 8. Pengecekan kromatogram/spektrum/ plat KLT -->
                            <tr>
                                <td style="text-align:center;">8</td>
                                <td><strong>Pengecekan kromatogram/spektrum/ plat KLT:</strong></td>
                                <td>${escHtml(t1.kromatogram_obs)}</td>
                                <td>${escHtml(t1.kromatogram_paraf_tgl)}</td>
                            </tr>

                            <!-- 9. Pengecekan alat gelas -->
                            <tr>
                                <td style="text-align:center;">9</td>
                                <td colspan="2"><strong>Pengecekan alat gelas:</strong></td>
                                <td rowspan="3">${escHtml(t1.gelas_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Kebersihan :</td>
                                <td>${escHtml(t1.gelas_kebersihan)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Kesesuaian alat gelas (volume pipet, labu tentukur, tabung sentrifuga)</td>
                                <td class="word-preline">${escHtml(t1.gelas_kesesuaian)}</td>
                            </tr>

                            <!-- 10. Kesesuaian Sistem -->
                            <tr>
                                <td style="text-align:center;">10</td>
                                <td colspan="2"><strong>Kesesuaian Sistem:</strong></td>
                                <td rowspan="2">${escHtml(t1.uks_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Hasil UKS:</td>
                                <td>${escHtml(t1.uks_hasil)}</td>
                            </tr>

                            <!-- 11. Validitas metode -->
                            <tr>
                                <td style="text-align:center;">11</td>
                                <td colspan="2"><strong>Validitas metode:</strong></td>
                                <td rowspan="3">${escHtml(t1.metode_paraf_tgl)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>- Identitas metode/edisi metode:</td>
                                <td class="word-preline">${escHtml(t1.metode_identitas)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Verifikasi/validasi dari metode yang digunakan:</td>
                                <td>${escHtml(t1.metode_verifikasi)}</td>
                            </tr>

                            <!-- 12. Kondisi suhu dan kelembaban laboratorium -->
                            <tr>
                                <td style="text-align:center;">12</td>
                                <td><strong>Kondisi suhu dan kelembaban laboratorium dan ruang instrumen:</strong></td>
                                <td class="word-preline">${escHtml(t1.lingkungan_obs)}</td>
                                <td>${escHtml(t1.lingkungan_paraf_tgl)}</td>
                            </tr>

                            <!-- Diskusi dengan analis -->
                            <tr>
                                <td colspan="4"><strong>Diskusi dengan analis:</strong></td>
                            </tr>
                            <tr>
                                <th style="width: 6%;">No.</th>
                                <th>Poin diskusi</th>
                                <th colspan="2">Pengamatan</th>
                            </tr>
                            <tr>
                                <td style="text-align:center;">1.</td>
                                <td>Penanganan sampel</td>
                                <td colspan="2">${escHtml(t1.diskusi_1_pengamatan)}</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">2.</td>
                                <td>Pengujian</td>
                                <td colspan="2">${escHtml(t1.diskusi_2_pengamatan)}</td>
                            </tr>

                            <!-- Kesimpulan dari investigator -->
                            <tr>
                                <td colspan="4" class="word-preline"><strong>Kesimpulan dari investigator:</strong>\n${escHtml(t1.kesimpulan_investigator)}</td>
                            </tr>

                            <!-- Re-analisis dengan sampel yang sama -->
                            <tr>
                                <td colspan="4"><strong>Re-analisis dengan sampel yang sama (jika ditemukan kesalahan dalam analisis)</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <strong>Diperlukan membuat protokol pengujian baru:</strong>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    [ ${chkYa} ] Ya (lampirkan)
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    [ ${chkTidak} ] Tidak
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 6%;">No.</th>
                                <th>Sampel</th>
                                <th>Syarat</th>
                                <th>Hasil</th>
                            </tr>
                            <tr>
                                <td style="text-align:center;">1.</td>
                                <td>${escHtml(t1.reanalisis_sampel)}</td>
                                <td>${escHtml(t1.reanalisis_syarat)}</td>
                                <td>${escHtml(t1.reanalisis_hasil)}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="height: 44px;"><strong>${escHtml(t1.reanalisis_penguji)}</strong></td>
                                <td colspan="2" style="height: 44px;"><strong>Paraf/Tanggal:</strong> ${escHtml(t1.reanalisis_paraf_tgl)}</td>
                            </tr>

                            <!-- Kesimpulan investigasi fase 1 & Tindakan perbaikan -->
                            <tr>
                                <td colspan="4"><strong>Kesimpulan investigasi fase 1:</strong> ${escHtml(t1.kesimpulan_fase1)}</td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>Tindakan perbaikan yang diambil :</strong> ${escHtml(t1.tindakan_perbaikan)}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="height: 56px;">
                                    <strong>Verifikator:</strong><br><br>
                                    ${escHtml(t1.verifikator_tahap1)}
                                </td>
                                <td colspan="2" style="height: 56px;">
                                    <strong>${escHtml(t1.ketua_tim_jabatan || 'Ketua Tim Kimia Obat')}:</strong><br><br>
                                    ${escHtml(t1.ketua_tim_nama)}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }

        window.renderHulsWordExactPreview = function() {
            const payload = collectHulsFormPayload();
            const mode = getVal('hulsPreviewDocMode') || 'both';
            const container = document.getElementById('hulsWordPrintArea');
            if (!container) return;

            let html = '';
            if (mode === 'pre') {
                html = buildWordExactPreHtml(payload.pre_investigasi);
            } else if (mode === 'tahap1') {
                html = buildWordExactTahap1Html(payload.tahap1, false);
            } else {
                html = buildWordExactPreHtml(payload.pre_investigasi) + buildWordExactTahap1Html(payload.tahap1, true);
            }
            container.innerHTML = html;
        };

        window.exportHulsWordPdf = function(mode) {
            let targetMode = mode;
            if (targetMode === 'current') {
                targetMode = (activeHulsFormTab === 'tahap1') ? 'tahap1' : (activeHulsFormTab === 'pre' ? 'pre' : (getVal('hulsPreviewDocMode') || 'both'));
            }
            setVal('hulsPreviewDocMode', targetMode);
            switchHulsFormTab('preview');
            renderHulsWordExactPreview();
        };

        window.downloadHulsDirectPdfFile = function() {
            renderHulsWordExactPreview();
            const printArea = document.getElementById('hulsWordPrintArea');
            if (!printArea) return;

            const payload = collectHulsFormPayload();
            const mode = getVal('hulsPreviewDocMode') || 'both';
            const safeCode = (payload.kode_sampel || 'Sampel').replace(/[^a-zA-Z0-9._-]/g, '_');
            const prefix = mode === 'pre' ? 'HULS_Pre_Investigasi_' : (mode === 'tahap1' ? 'HULS_Investigasi_Tahap_1_' : 'HULS_Lengkap_');

            if (window.html2pdf) {
                const opt = {
                    margin: [10, 10, 10, 10],
                    filename: prefix + safeCode + '.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                    pagebreak: { mode: ['css', 'legacy'] }
                };
                window.html2pdf().set(opt).from(printArea).save();
            } else {
                printHulsWordExactWindow();
            }
        };

        function openWordPrintPopupWindow(htmlContent, titleCode) {
            const win = window.open('', '_blank', 'width=960,height=800');
            if (!win) {
                alert('Izinkan pop-up pada browser untuk membuka jendela cetak PDF.');
                return;
            }
            win.document.write(`
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <title>HULS_${escHtml(titleCode || 'BBPOM')}</title>
                    <style>
                        @page { size: A4 portrait; margin: 14mm 14mm; }
                        body {
                            margin: 0;
                            padding: 0;
                            background: #ffffff;
                            color: #000000;
                            font-family: "Times New Roman", Times, serif;
                            font-size: 10.5pt;
                        }
                        .word-doc-sheet {
                            width: 100%;
                            margin: 0;
                            padding: 0;
                            border: none;
                            box-shadow: none;
                        }
                        h4.word-doc-heading {
                            font-family: "Times New Roman", Times, serif;
                            font-size: 11pt;
                            font-weight: bold;
                            margin: 8px 0 5px;
                        }
                        .word-doc-center-title {
                            text-align: center;
                            font-weight: bold;
                            font-size: 11.5pt;
                            line-height: 1.35;
                            margin-bottom: 12px;
                        }
                        .word-table {
                            width: 100%;
                            border-collapse: collapse;
                            border: 1px solid #000000;
                            margin-bottom: 10px;
                            font-size: 10pt;
                        }
                        .word-table th, .word-table td {
                            border: 1px solid #000000;
                            padding: 3.5px 6px;
                            vertical-align: top;
                        }
                        .word-table th {
                            font-weight: bold;
                            text-align: center;
                        }
                        .word-preline {
                            white-space: pre-line;
                        }
                        .word-page-break {
                            page-break-before: always;
                            break-before: page;
                        }
                    </style>
                </head>
                <body>
                    ${htmlContent}
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                            }, 250);
                        };
                    <\/script>
                </body>
                </html>
            `);
            win.document.close();
        }

        window.printHulsWordExactWindow = function() {
            renderHulsWordExactPreview();
            const printArea = document.getElementById('hulsWordPrintArea');
            if (!printArea) return;
            const payload = collectHulsFormPayload();
            openWordPrintPopupWindow(printArea.innerHTML, payload.kode_sampel || 'BBPOM');
        };

        // =========================================================================
        // LAPORAN PEMANTAUAN SUHU & KELEMBAPAN RUANGAN (08.00 & 15.00)
        // Format Resmi BBPOM Bandung: 6.3/PTJM-01/BBPOM BDG/18 F (01)
        // Halaman 1: Tabel 1-31 | Halaman 2: Grafik Pukul 08.00 | Halaman 3: Grafik Pukul 15.00
        // =========================================================================
        const TH_MONTH_NAMES = [
            '', 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI',
            'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'
        ];

        const TH_REFERENCE_SAMPLE_MAP = {
            3:  { s08: 21.7, s15: 23.0, h08: 50, h15: 44, nama: 'Wahyu',   ket: 'Memenuhi Syarat' },
            4:  { s08: 24.1, s15: 24.3, h08: 42, h15: 41, nama: 'Vani',    ket: 'Memenuhi Syarat' },
            5:  { s08: 25.2, s15: 25.1, h08: 42, h15: 42, nama: 'Aditya',  ket: 'Memenuhi Syarat' },
            6:  { s08: 24.8, s15: 23.1, h08: 43, h15: 41, nama: 'Vani',    ket: 'Memenuhi Syarat' },
            10: { s08: 23.9, s15: 23.6, h08: 44, h15: 41, nama: 'Wahyu',   ket: 'Memenuhi Syarat' },
            11: { s08: 24.4, s15: 23.1, h08: 42, h15: 38, nama: 'Pradita', ket: 'Memenuhi Syarat' },
            12: { s08: 23.8, s15: 23.6, h08: 40, h15: 41, nama: 'Vani',    ket: 'Memenuhi Syarat' },
            13: { s08: 24.0, s15: 22.2, h08: 45, h15: 39, nama: 'Vani',    ket: 'Memenuhi Syarat' },
            18: { s08: 21.3, s15: 23.4, h08: 55, h15: 43, nama: 'Fanya',   ket: 'Memenuhi Syarat' },
            19: { s08: 22.3, s15: 23.5, h08: 38, h15: 39, nama: 'Pradita', ket: 'Memenuhi Syarat' },
            20: { s08: 21.8, s15: 22.3, h08: 45, h15: 40, nama: 'Vani',    ket: 'Memenuhi Syarat' },
            24: { s08: 21.5, s15: 21.9, h08: 45, h15: 39, nama: 'Pradita', ket: 'Memenuhi Syarat' },
            26: { s08: 21.1, s15: 21.5, h08: 48, h15: 40, nama: 'Fanya',   ket: 'Memenuhi Syarat' },
            27: { s08: 23.4, s15: 23.5, h08: 51, h15: 47, nama: 'Wahyu',   ket: 'Memenuhi Syarat' },
            31: { s08: 25.5, s15: 24.3, h08: 39, h15: 37, nama: 'Fanya',   ket: 'Memenuhi Syarat' }
        };

        const thReportState = {
            deviceId: 'ALAT_1_AC',
            days: {}
        };

        function initTempHumReferenceDays() {
            for (let d = 1; d <= 31; d++) {
                const ref = TH_REFERENCE_SAMPLE_MAP[d];
                thReportState.days[d] = ref
                    ? { s08: ref.s08, s15: ref.s15, h08: ref.h08, h15: ref.h15, nama: ref.nama, ket: ref.ket }
                    : { s08: '', s15: '', h08: '', h15: '', nama: '', ket: '' };
            }
        }
        initTempHumReferenceDays();

        function fmtComma1(val) {
            if (val === '' || val === null || val === undefined || isNaN(Number(val))) return '';
            return Number(val).toFixed(1).replace('.', ',');
        }
        function fmtInt(val) {
            if (val === '' || val === null || val === undefined || isNaN(Number(val))) return '';
            return String(Math.round(Number(val)));
        }

        window.openTempHumOfficialPdfModal = function(opts) {
            const o = opts || {};
            thReportState.deviceId = o.deviceId || 'ALAT_1_AC';
            if (o.roomName) setVal('th_room_name', o.roomName);
            if (o.labName) setVal('th_lab_name', o.labName);
            setVal('th_sign_pelaksana', '');
            setVal('th_sign_verifikator', '');

            buildTempHumEditorRows();
            refreshOfficialTempHumPreview();

            const modalEl = document.getElementById('modalLaporanSuhuKelembapan');
            if (modalEl && window.bootstrap) {
                const m = window.bootstrap.Modal.getOrCreateInstance(modalEl);
                m.show();
                setTimeout(() => {
                    refreshOfficialTempHumPreview();
                }, 180);
            }
        };

        window.toggleTempHumTableEditor = function() {
            const w = document.getElementById('thTableEditorWrap');
            if (!w) return;
            w.style.display = (w.style.display === 'none') ? 'block' : 'none';
            if (w.style.display === 'block') buildTempHumEditorRows();
        };

        function buildTempHumEditorRows() {
            const tbody = document.getElementById('thEditorTbody');
            if (!tbody) return;
            let html = '';
            for (let d = 1; d <= 31; d++) {
                const row = thReportState.days[d] || { s08: '', s15: '', h08: '', h15: '', nama: '', ket: '' };
                html += `
                    <tr>
                        <td class="text-center fw-bold">${d}</td>
                        <td><input type="number" step="0.1" class="form-control form-control-sm text-center" value="${row.s08 !== '' ? row.s08 : ''}" oninput="updateTempHumDayCell(${d}, 's08', this.value)"></td>
                        <td><input type="number" step="0.1" class="form-control form-control-sm text-center" value="${row.s15 !== '' ? row.s15 : ''}" oninput="updateTempHumDayCell(${d}, 's15', this.value)"></td>
                        <td><input type="number" step="1" class="form-control form-control-sm text-center" value="${row.h08 !== '' ? row.h08 : ''}" oninput="updateTempHumDayCell(${d}, 'h08', this.value)"></td>
                        <td><input type="number" step="1" class="form-control form-control-sm text-center" value="${row.h15 !== '' ? row.h15 : ''}" oninput="updateTempHumDayCell(${d}, 'h15', this.value)"></td>
                        <td><input type="text" class="form-control form-control-sm text-center" value="${escHtml(row.nama)}" oninput="updateTempHumDayCell(${d}, 'nama', this.value)"></td>
                        <td><input type="text" class="form-control form-control-sm text-center" value="${escHtml(row.ket)}" oninput="updateTempHumDayCell(${d}, 'ket', this.value)"></td>
                    </tr>
                `;
            }
            tbody.innerHTML = html;
        }

        window.updateTempHumDayCell = function(day, key, val) {
            if (!thReportState.days[day]) {
                thReportState.days[day] = { s08: '', s15: '', h08: '', h15: '', nama: '', ket: '' };
            }
            if (key === 's08' || key === 's15' || key === 'h08' || key === 'h15') {
                thReportState.days[day][key] = (val === '' || val === null) ? '' : parseFloat(val);
            } else {
                thReportState.days[day][key] = val;
            }
            refreshOfficialTempHumPreview();
        };

        window.loadTempHumReferenceSampleData = function() {
            setVal('th_lab_name', 'MIKROBIOLOGI');
            setVal('th_room_name', 'R. CEMARAN JAMUR');
            setVal('th_month_select', '8');
            setVal('th_year_input', '2026');
            setVal('th_spec_suhu', '20-27°C');
            setVal('th_spec_hum', '30-70%');
            setVal('th_sign_pelaksana', '');
            setVal('th_sign_verifikator', '');
            setVal('th_eval_check', 'V');
            initTempHumReferenceDays();
            buildTempHumEditorRows();
            refreshOfficialTempHumPreview();
        };

        window.clearAllTempHumDays = function() {
            for (let d = 1; d <= 31; d++) {
                thReportState.days[d] = { s08: '', s15: '', h08: '', h15: '', nama: '', ket: '' };
            }
            buildTempHumEditorRows();
            refreshOfficialTempHumPreview();
        };

        window.onTempHumMonthYearChange = function() {
            refreshOfficialTempHumPreview();
        };

        window.loadTempHumDataFromDatabase = function() {
            const devId = thReportState.deviceId || 'ALAT_1_AC';
            const month = parseInt(getVal('th_month_select') || '8', 10);
            const year = parseInt(getVal('th_year_input') || '2026', 10);

            fetch(`api/data?key=monthlyTempHumReport&device_id=${encodeURIComponent(devId)}&year=${year}&month=${month}`)
                .then(res => res.json())
                .then(data => {
                    if (data && Array.isArray(data.days)) {
                        let foundAny = false;
                        data.days.forEach(item => {
                            const d = Number(item.day);
                            if (d >= 1 && d <= 31) {
                                const has08 = item.suhu_08 !== null && item.suhu_08 !== undefined;
                                const has15 = item.suhu_15 !== null && item.suhu_15 !== undefined;
                                if (has08 || has15) {
                                    foundAny = true;
                                    thReportState.days[d] = {
                                        s08: has08 ? item.suhu_08 : '',
                                        s15: has15 ? item.suhu_15 : '',
                                        h08: (item.hum_08 !== null && item.hum_08 !== undefined) ? item.hum_08 : '',
                                        h15: (item.hum_15 !== null && item.hum_15 !== undefined) ? item.hum_15 : '',
                                        nama: LOGGED_IN_USER_NAME || 'Petugas',
                                        ket: 'Memenuhi Syarat'
                                    };
                                } else {
                                    thReportState.days[d] = { s08: '', s15: '', h08: '', h15: '', nama: '', ket: '' };
                                }
                            }
                        });
                        buildTempHumEditorRows();
                        refreshOfficialTempHumPreview();
                        if (!foundAny) {
                            alert('Belum ditemukan log sensor pukul 08.00 / 15.00 pada bulan dan tahun tersebut di database. Anda dapat mengisi manual pada tabel atau klik "Muat Data Contoh Dokumen BBPOM".');
                        }
                    }
                })
                .catch(() => {
                    alert('Gagal mengambil data bulanan dari database.');
                });
        };

        /**
         * Menggambar Grafik Kontrol (Suhu / Kelembapan Pukul 08.00 / 15.00)
         * Persis 100% menyerupai grafik di dokumen PDF resmi BBPOM Bandung
         */
        function drawOfficialControlChartOnCanvas(canvas, cfg) {
            if (!canvas) return;
            const scale = cfg.scale || 2.5;
            const logicalW = cfg.width || 680;
            const logicalH = cfg.height || 265;

            canvas.width = Math.round(logicalW * scale);
            canvas.height = Math.round(logicalH * scale);
            canvas.style.width = logicalW + 'px';
            canvas.style.height = logicalH + 'px';

            const ctx = canvas.getContext('2d');
            ctx.save();
            ctx.scale(scale, scale);

            // White background
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, logicalW, logicalH);

            // Outer black border box
            ctx.strokeStyle = '#000000';
            ctx.lineWidth = 1.1;
            ctx.strokeRect(0.5, 0.5, logicalW - 1, logicalH - 1);

            // Title at top center
            ctx.fillStyle = '#000000';
            ctx.font = 'bold 13px Tahoma, Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'top';
            ctx.fillText(cfg.title || '', logicalW / 2, 12);

            // Plot area coordinates
            const plotLeft = 78;
            const plotRight = logicalW - 22;
            const plotTop = 38;
            const plotBottom = 184;
            const plotW = plotRight - plotLeft;
            const plotH = plotBottom - plotTop;

            const yMin = cfg.yMin;
            const yMax = cfg.yMax;
            const yToPx = (val) => plotBottom - ((val - yMin) / (yMax - yMin)) * plotH;
            const dayToPx = (day) => plotLeft + ((day - 1) / 30) * plotW;

            // Vertical grid lines (day 1 to 31)
            ctx.strokeStyle = '#e5e7eb';
            ctx.lineWidth = 0.8;
            for (let d = 1; d <= 31; d++) {
                const x = dayToPx(d);
                ctx.beginPath();
                ctx.moveTo(x, plotTop);
                ctx.lineTo(x, plotBottom);
                ctx.stroke();
            }

            // Horizontal grid lines (major + minor)
            if (Array.isArray(cfg.gridYTicks)) {
                cfg.gridYTicks.forEach(gv => {
                    const y = yToPx(gv);
                    if (y >= plotTop - 1 && y <= plotBottom + 1) {
                        ctx.beginPath();
                        ctx.moveTo(plotLeft, y);
                        ctx.lineTo(plotRight, y);
                        ctx.stroke();
                    }
                });
            }

            // Y-axis line & ticks
            ctx.strokeStyle = '#333333';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(plotLeft, plotTop);
            ctx.lineTo(plotLeft, plotBottom);
            ctx.stroke();

            ctx.fillStyle = '#000000';
            ctx.font = '10.5px Tahoma, Arial, sans-serif';
            ctx.textAlign = 'right';
            ctx.textBaseline = 'middle';
            (cfg.majorYTicks || []).forEach(tv => {
                const y = yToPx(tv);
                ctx.beginPath();
                ctx.moveTo(plotLeft - 6, y);
                ctx.lineTo(plotLeft, y);
                ctx.stroke();
                const lbl = cfg.useCommaDecimal ? tv.toFixed(1).replace('.', ',') : String(Math.round(tv));
                ctx.fillText(lbl, plotLeft - 9, y);
            });

            // Rotated Y-axis label
            ctx.save();
            ctx.translate(22, (plotTop + plotBottom) / 2);
            ctx.rotate(-Math.PI / 2);
            ctx.font = 'bold 11px Tahoma, Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(cfg.yAxisLabel || '', 0, 0);
            ctx.restore();

            // X-axis line & ticks 1..31
            ctx.strokeStyle = '#333333';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(plotLeft, plotBottom);
            ctx.lineTo(plotRight, plotBottom);
            ctx.stroke();

            ctx.font = '10px Tahoma, Arial, sans-serif';
            ctx.fillStyle = '#000000';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'top';
            for (let d = 1; d <= 31; d++) {
                const x = dayToPx(d);
                ctx.beginPath();
                ctx.moveTo(x, plotBottom);
                ctx.lineTo(x, plotBottom + 6);
                ctx.stroke();
                ctx.fillText(String(d), x, plotBottom + 9);
            }

            // "Tanggal" X-axis title
            ctx.font = 'bold 11px Tahoma, Arial, sans-serif';
            ctx.fillText('Tanggal', (plotLeft + plotRight) / 2, plotBottom + 27);

            // Helper to draw a constant limit series (UWL, UCL, LCL, LWL) across days 1..31
            function drawLimitLine(val, color) {
                const y = yToPx(val);
                ctx.strokeStyle = color;
                ctx.lineWidth = 1.4;
                ctx.beginPath();
                ctx.moveTo(dayToPx(1), y);
                ctx.lineTo(dayToPx(31), y);
                ctx.stroke();

                ctx.fillStyle = color;
                for (let d = 1; d <= 31; d++) {
                    const x = dayToPx(d);
                    ctx.beginPath();
                    ctx.arc(x, y, 2.7, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            // Draw the 4 control limit series
            drawLimitLine(cfg.ucl, '#eab308'); // UCL (Yellow)
            drawLimitLine(cfg.uwl, '#d9383a'); // UWL (Red)
            drawLimitLine(cfg.lwl, '#f97316'); // LWL (Orange)
            drawLimitLine(cfg.lcl, '#28a745'); // LCL (Green)

            // Draw Data Aktual series (Blue #4285cc) connecting valid points across 1..31
            const actualPts = [];
            for (let d = 1; d <= 31; d++) {
                const v = cfg.valuesByDay[d];
                if (v !== '' && v !== null && v !== undefined && !isNaN(Number(v))) {
                    actualPts.push({ d: d, x: dayToPx(d), y: yToPx(Number(v)) });
                }
            }
            if (actualPts.length > 0) {
                ctx.strokeStyle = '#4285cc';
                ctx.lineWidth = 1.6;
                ctx.beginPath();
                actualPts.forEach((pt, idx) => {
                    if (idx === 0) ctx.moveTo(pt.x, pt.y);
                    else ctx.lineTo(pt.x, pt.y);
                });
                ctx.stroke();

                ctx.fillStyle = '#4285cc';
                actualPts.forEach(pt => {
                    ctx.beginPath();
                    ctx.arc(pt.x, pt.y, 2.9, 0, Math.PI * 2);
                    ctx.fill();
                });
            }

            // Legend at the bottom of the box: Data Aktual | UWL | UCL | LCL | LWL
            const legendItems = [
                { label: 'Data Aktual', color: '#4285cc' },
                { label: 'UWL',         color: '#d9383a' },
                { label: 'UCL',         color: '#eab308' },
                { label: 'LCL',         color: '#28a745' },
                { label: 'LWL',         color: '#f97316' }
            ];
            const legendY = logicalH - 16;
            ctx.font = '10.5px Tahoma, Arial, sans-serif';
            ctx.textBaseline = 'middle';

            // Calculate total legend width to center it
            let totalLegW = 0;
            const itemWidths = legendItems.map(it => {
                const w = 14 + ctx.measureText(it.label).width + 20;
                totalLegW += w;
                return w;
            });
            totalLegW -= 20;

            let curX = (logicalW - totalLegW) / 2;
            legendItems.forEach((it, idx) => {
                ctx.fillStyle = it.color;
                ctx.beginPath();
                ctx.arc(curX + 4.5, legendY, 4.5, 0, Math.PI * 2);
                ctx.fill();

                ctx.fillStyle = '#000000';
                ctx.textAlign = 'left';
                ctx.fillText(it.label, curX + 13, legendY);
                curX += itemWidths[idx];
            });

            ctx.restore();
        }

        function collectTempHumHeaderMeta() {
            const monthIdx = parseInt(getVal('th_month_select') || '8', 10);
            return {
                docCode: getVal('th_doc_code') || '6.3/PTJM-01/BBPOM BDG/18 F (01)',
                labName: getVal('th_lab_name') || 'MIKROBIOLOGI',
                roomName: getVal('th_room_name') || 'R. CEMARAN JAMUR',
                monthName: TH_MONTH_NAMES[monthIdx] || 'AGUSTUS',
                year: getVal('th_year_input') || '2026',
                specSuhu: getVal('th_spec_suhu') || '20-27°C',
                specHum: getVal('th_spec_hum') || '30-70%',
                pelaksana: (getVal('th_sign_pelaksana') || '').trim(),
                verifikator: (getVal('th_sign_verifikator') || '').trim(),
                evalCheck: getVal('th_eval_check') || 'V'
            };
        }

        window.refreshOfficialTempHumPreview = function() {
            const container = document.getElementById('thOfficialPreviewContainer');
            if (!container) return;
            const m = collectTempHumHeaderMeta();

            let rowsHtml = '';
            for (let d = 1; d <= 31; d++) {
                const r = thReportState.days[d] || {};
                rowsHtml += `
                    <tr>
                        <td style="text-align:center;">${d}</td>
                        <td style="text-align:center;">${fmtComma1(r.s08)}</td>
                        <td style="text-align:center;">${fmtComma1(r.s15)}</td>
                        <td style="text-align:center;">${fmtInt(r.h08)}</td>
                        <td style="text-align:center;">${fmtInt(r.h15)}</td>
                        <td style="text-align:center;">${escHtml(r.nama || '')}</td>
                        <td style="text-align:center;">${escHtml(r.ket || '')}</td>
                    </tr>
                `;
            }

            const commonMetaBlock = `
                <div style="display:flex; justify-content:space-between; font-size:9.5pt; font-weight:bold; margin: 10px 0 8px; line-height:1.45;">
                    <table style="border:none; border-collapse:collapse; width:54%;">
                        <tr><td style="border:none; padding:1px 0; width:130px;">LABORATORIUM</td><td style="border:none; padding:1px 0;">: ${escHtml(m.labName)}</td></tr>
                        <tr><td style="border:none; padding:1px 0;">RUANGAN</td><td style="border:none; padding:1px 0;">: ${escHtml(m.roomName)}</td></tr>
                        <tr><td style="border:none; padding:1px 0;">BULAN</td><td style="border:none; padding:1px 0;">: ${escHtml(m.monthName)}</td></tr>
                    </table>
                    <table style="border:none; border-collapse:collapse; width:42%;">
                        <tr><td colspan="2" style="border:none; padding:1px 0;">SPESIFIKASI</td></tr>
                        <tr><td style="border:none; padding:1px 0; width:120px;">SUHU</td><td style="border:none; padding:1px 0;">: ${escHtml(m.specSuhu)}</td></tr>
                        <tr><td style="border:none; padding:1px 0;">KELEMBAPAN</td><td style="border:none; padding:1px 0;">: ${escHtml(m.specHum)}</td></tr>
                    </table>
                </div>
            `;

            const evalBoxHtml = `
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px; margin-top:10px; font-size:8.8pt;">
                    <div style="width:45%; line-height:1.55;">
                        <div style="font-weight:bold;">Keterangan</div>
                        <div>UCL = Upper Control Limit (27,1°C &amp; 70%)</div>
                        <div>UWL = Upper Warning Limit (25,8°C &amp; 63,3%)</div>
                        <div>LWL = Lower Warning Limit (21,1°C &amp; 36,7%)</div>
                        <div>LCL = Lower Control Limit (19,9°C &amp; 30%)</div>
                    </div>
                    <div style="width:53%;">
                        <table style="width:100%; border-collapse:collapse; border:1px solid #000; font-size:8.4pt;">
                            <thead>
                                <tr>
                                    <th style="border:1px solid #000; padding:3px 6px; text-align:center; width:66%;">Evaluasi</th>
                                    <th style="border:1px solid #000; padding:3px 6px; text-align:center; width:34%;">Checklist*</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border:1px solid #000; padding:5px 8px; text-align:center; line-height:1.3;">
                                        Tidak ada hasil pemantauan suhu dan kelembapan yang lebih besar sama dengan nilai upper control limit (UCL) dan lower control limit (LCL) sebanyak 2 kali berturut-turut.
                                    </td>
                                    <td style="border:1px solid #000; padding:5px; text-align:center; vertical-align:middle;">
                                        ${m.evalCheck === 'V'
                                            ? '<span style="display:inline-flex; align-items:center; justify-content:center; width:18px; height:18px; background:#808080; color:#fff; font-weight:bold; border-radius:2px; font-size:12px;">&#10003;</span>'
                                            : '<span style="display:inline-flex; align-items:center; justify-content:center; width:18px; height:18px; border:1px solid #000; font-weight:bold; font-size:12px;">X</span>'}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border:1px solid #000; padding:3px 6px; text-align:left;">
                                        * Isi dengan (V) jika "Ya" dan (X) jika "Tidak"
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            container.innerHTML = `
                <!-- HALAMAN 1: TABEL PEMANTAUAN SUHU DAN KELEMBAPAN RUANGAN -->
                <div class="word-doc-sheet mb-2" style="font-family: Tahoma, Arial, sans-serif; padding: 14mm 16mm; width: 210mm; max-width: 100%;">
                    <div style="display:flex; justify-content:flex-end; margin-bottom:6px;">
                        <div style="border:1px solid #000; padding:2px 26px; font-size:9.5pt;">${escHtml(m.docCode)}</div>
                    </div>
                    <div style="text-align:center; font-weight:bold; font-size:11pt; line-height:1.4;">
                        <div>BALAI BESAR POM DI BANDUNG</div>
                        <div>PEMANTAUAN SUHU DAN KELEMBAPAN RUANGAN</div>
                        <div>TAHUN ${escHtml(m.year)}</div>
                    </div>
                    ${commonMetaBlock}
                    <table style="width:100%; border-collapse:collapse; border:1px solid #000; font-size:8.6pt;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="border:1px solid #000; padding:3px; text-align:center; vertical-align:middle; width:11%;">TANGGAL</th>
                                <th colspan="2" style="border:1px solid #000; padding:3px; text-align:center; width:24%;">SUHU (°C)</th>
                                <th colspan="2" style="border:1px solid #000; padding:3px; text-align:center; width:24%;">KELEMBAPAN (%)</th>
                                <th style="border:1px solid #000; padding:3px; text-align:center; width:21%;">PELAKSANA</th>
                                <th rowspan="2" style="border:1px solid #000; padding:3px; text-align:center; vertical-align:middle; width:20%;">KETERANGAN</th>
                            </tr>
                            <tr>
                                <th style="border:1px solid #000; padding:3px; text-align:center;">08.00*</th>
                                <th style="border:1px solid #000; padding:3px; text-align:center;">15.00*</th>
                                <th style="border:1px solid #000; padding:3px; text-align:center;">08.00*</th>
                                <th style="border:1px solid #000; padding:3px; text-align:center;">15.00*</th>
                                <th style="border:1px solid #000; padding:3px; text-align:center;">NAMA</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                    <style>
                        #thOfficialPreviewContainer tbody td { border: 1px solid #000; padding: 2px 4px; height: 18px; }
                    </style>
                    <div style="display:flex; justify-content:space-between; margin-top:10px; font-size:8.8pt;">
                        <div>
                            <div style="font-weight:bold;">Catatan:</div>
                            <div>*Toleransi &plusmn;15 menit</div>
                            <div style="margin-top:46px; font-weight:bold;">Direvisi tanggal 3 Januari 2022</div>
                        </div>
                        <div style="display:flex; gap:54px; text-align:center; padding-right:10px;">
                            <div>
                                <div style="font-weight:bold;">PELAKSANA</div>
                                <div style="margin-top:52px;">${escHtml(m.pelaksana) || '&nbsp;'}</div>
                            </div>
                            <div>
                                <div style="font-weight:bold;">VERIFIKATOR</div>
                                <div style="margin-top:52px; white-space:pre;">${escHtml(m.verifikator) || '&nbsp;'}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HALAMAN 2: GRAFIK PUKUL 08.00 -->
                <div class="word-doc-sheet mb-2" style="font-family: Tahoma, Arial, sans-serif; padding: 14mm 16mm; width: 210mm; max-width: 100%;">
                    <div style="display:flex; justify-content:flex-end; margin-bottom:6px;">
                        <div style="border:1px solid #000; padding:2px 26px; font-size:9.5pt;">${escHtml(m.docCode)}</div>
                    </div>
                    <div style="text-align:center; font-weight:bold; font-size:11pt; line-height:1.4;">
                        <div>BALAI BESAR POM DI BANDUNG</div>
                        <div>PEMANTAUAN SUHU DAN KELEMBAPAN RUANGAN (PUKUL 08.00)</div>
                        <div>TAHUN ${escHtml(m.year)}</div>
                    </div>
                    ${commonMetaBlock}
                    <div class="d-flex flex-column align-items-center">
                        <canvas id="canvasPreviewSuhu08" style="max-width:100%; margin-bottom:3px;"></canvas>
                        <canvas id="canvasPreviewHum08" style="max-width:100%;"></canvas>
                    </div>
                    ${evalBoxHtml}
                    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-top:16px; font-size:8.8pt;">
                        <div style="font-weight:bold;">Direvisi tanggal 3 Januari 2022</div>
                        <div style="display:flex; gap:54px; text-align:center; padding-right:10px;">
                            <div>
                                <div style="font-weight:bold;">PELAKSANA</div>
                                <div style="margin-top:46px;">${escHtml(m.pelaksana) || '&nbsp;'}</div>
                            </div>
                            <div>
                                <div style="font-weight:bold;">VERIFIKATOR</div>
                                <div style="margin-top:46px; white-space:pre;">${escHtml(m.verifikator) || '&nbsp;'}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HALAMAN 3: GRAFIK PUKUL 15.00 -->
                <div class="word-doc-sheet mb-2" style="font-family: Tahoma, Arial, sans-serif; padding: 14mm 16mm; width: 210mm; max-width: 100%;">
                    <div style="display:flex; justify-content:flex-end; margin-bottom:6px;">
                        <div style="border:1px solid #000; padding:2px 26px; font-size:9.5pt;">${escHtml(m.docCode)}</div>
                    </div>
                    <div style="text-align:center; font-weight:bold; font-size:11pt; line-height:1.4;">
                        <div>BALAI BESAR POM DI BANDUNG</div>
                        <div>PEMANTAUAN SUHU DAN KELEMBAPAN RUANGAN (PUKUL 15.00)</div>
                        <div>TAHUN ${escHtml(m.year)}</div>
                    </div>
                    ${commonMetaBlock}
                    <div class="d-flex flex-column align-items-center">
                        <canvas id="canvasPreviewSuhu15" style="max-width:100%; margin-bottom:3px;"></canvas>
                        <canvas id="canvasPreviewHum15" style="max-width:100%;"></canvas>
                    </div>
                    ${evalBoxHtml}
                    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-top:16px; font-size:8.8pt;">
                        <div style="font-weight:bold;">Direvisi tanggal 3 Januari 2022</div>
                        <div style="display:flex; gap:54px; text-align:center; padding-right:10px;">
                            <div>
                                <div style="font-weight:bold;">PELAKSANA</div>
                                <div style="margin-top:46px;">${escHtml(m.pelaksana) || '&nbsp;'}</div>
                            </div>
                            <div>
                                <div style="font-weight:bold;">VERIFIKATOR</div>
                                <div style="margin-top:46px; white-space:pre;">${escHtml(m.verifikator) || '&nbsp;'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Build maps for 08.00 and 15.00
            const s08Map = {}, s15Map = {}, h08Map = {}, h15Map = {};
            for (let d = 1; d <= 31; d++) {
                const r = thReportState.days[d] || {};
                s08Map[d] = r.s08;
                s15Map[d] = r.s15;
                h08Map[d] = r.h08;
                h15Map[d] = r.h15;
            }

            drawOfficialControlChartOnCanvas(document.getElementById('canvasPreviewSuhu08'), {
                title: 'Suhu Pukul 08.00',
                yAxisLabel: 'Suhu (°C)',
                yMin: 19.65,
                yMax: 28.0,
                majorYTicks: [20, 22, 24, 26, 28],
                gridYTicks: [20, 21, 22, 23, 24, 25, 26, 27, 28],
                useCommaDecimal: true,
                ucl: 27.1, uwl: 25.8, lwl: 21.1, lcl: 19.9,
                valuesByDay: s08Map
            });
            drawOfficialControlChartOnCanvas(document.getElementById('canvasPreviewHum08'), {
                title: 'Kelembapan Pukul 08.00',
                yAxisLabel: 'Kelembapan (%)',
                yMin: 29.0,
                yMax: 70.2,
                majorYTicks: [30, 40, 50, 60, 70],
                gridYTicks: [30, 35, 40, 45, 50, 55, 60, 65, 70],
                useCommaDecimal: false,
                ucl: 70.0, uwl: 63.3, lwl: 36.7, lcl: 30.0,
                valuesByDay: h08Map
            });
            drawOfficialControlChartOnCanvas(document.getElementById('canvasPreviewSuhu15'), {
                title: 'Suhu Pukul 15.00',
                yAxisLabel: 'Suhu (°C)',
                yMin: 19.65,
                yMax: 28.0,
                majorYTicks: [20, 22, 24, 26, 28],
                gridYTicks: [20, 21, 22, 23, 24, 25, 26, 27, 28],
                useCommaDecimal: true,
                ucl: 27.1, uwl: 25.8, lwl: 21.1, lcl: 19.9,
                valuesByDay: s15Map
            });
            drawOfficialControlChartOnCanvas(document.getElementById('canvasPreviewHum15'), {
                title: 'Kelembapan Pukul 15.00',
                yAxisLabel: 'Kelembapan (%)',
                yMin: 29.0,
                yMax: 70.2,
                majorYTicks: [30, 40, 50, 60, 70],
                gridYTicks: [30, 35, 40, 45, 50, 55, 60, 65, 70],
                useCommaDecimal: false,
                ucl: 70.0, uwl: 63.3, lwl: 36.7, lcl: 30.0,
                valuesByDay: h15Map
            });
        };

        /**
         * Generate 3-Page Official A4 Portrait PDF using jsPDF + autoTable + High-DPI Canvas Charts
         */
        window.generateOfficialTempHumPdfFile = function(actionMode) {
            if (!window.jspdf) {
                alert('Library PDF belum siap. Silakan tunggu sebentar.');
                return;
            }
            refreshOfficialTempHumPreview();

            const m = collectTempHumHeaderMeta();
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ unit: 'mm', format: 'a4', orientation: 'portrait' });

            function drawPageHeader(doc, subtitleLine) {
                // Top-right boxed document code
                doc.setDrawColor(0, 0, 0);
                doc.setLineWidth(0.25);
                doc.rect(114, 12, 78, 5.8);
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(9.5);
                doc.setTextColor(0, 0, 0);
                doc.text(m.docCode, 153, 16.1, { align: 'center' });

                // Main centered title
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(10.5);
                doc.text('BALAI BESAR POM DI BANDUNG', 105, 22.8, { align: 'center' });
                doc.text(subtitleLine, 105, 27.6, { align: 'center' });
                doc.text('TAHUN ' + m.year, 105, 32.4, { align: 'center' });

                // Metadata Left & Right
                doc.setFontSize(8.8);
                doc.text('LABORATORIUM', 18, 39.5);
                doc.text(': ' + m.labName, 53, 39.5);
                doc.text('RUANGAN', 18, 44.0);
                doc.text(': ' + m.roomName, 53, 44.0);
                doc.text('BULAN', 18, 48.5);
                doc.text(': ' + m.monthName, 53, 48.5);

                doc.text('SPESIFIKASI', 117, 39.5);
                doc.text('SUHU', 117, 44.0);
                doc.text(': ' + m.specSuhu, 152, 44.0);
                doc.text('KELEMBAPAN', 117, 48.5);
                doc.text(': ' + m.specHum, 152, 48.5);
            }

            // =====================================================================
            // PAGE 1: TABEL PEMANTAUAN SUHU DAN KELEMBAPAN RUANGAN (1 s/d 31)
            // =====================================================================
            drawPageHeader(doc, 'PEMANTAUAN SUHU DAN KELEMBAPAN RUANGAN');

            const bodyRows = [];
            for (let d = 1; d <= 31; d++) {
                const r = thReportState.days[d] || {};
                bodyRows.push([
                    String(d),
                    fmtComma1(r.s08),
                    fmtComma1(r.s15),
                    fmtInt(r.h08),
                    fmtInt(r.h15),
                    r.nama || '',
                    r.ket || ''
                ]);
            }

            doc.autoTable({
                startY: 52.5,
                margin: { left: 18, right: 18 },
                head: [
                    [
                        { content: 'TANGGAL', rowSpan: 2, styles: { valign: 'middle', halign: 'center' } },
                        { content: 'SUHU (°C)', colSpan: 2, styles: { halign: 'center' } },
                        { content: 'KELEMBAPAN (%)', colSpan: 2, styles: { halign: 'center' } },
                        { content: 'PELAKSANA', styles: { halign: 'center' } },
                        { content: 'KETERANGAN', rowSpan: 2, styles: { valign: 'middle', halign: 'center' } }
                    ],
                    [
                        { content: '08.00*', styles: { halign: 'center' } },
                        { content: '15.00*', styles: { halign: 'center' } },
                        { content: '08.00*', styles: { halign: 'center' } },
                        { content: '15.00*', styles: { halign: 'center' } },
                        { content: 'NAMA', styles: { halign: 'center' } }
                    ]
                ],
                body: bodyRows,
                theme: 'grid',
                styles: {
                    font: 'helvetica',
                    fontSize: 8.2,
                    textColor: [0, 0, 0],
                    lineColor: [0, 0, 0],
                    lineWidth: 0.22,
                    cellPadding: { top: 1.05, bottom: 1.05, left: 1.5, right: 1.5 },
                    halign: 'center',
                    valign: 'middle'
                },
                headStyles: {
                    fillColor: [255, 255, 255],
                    textColor: [0, 0, 0],
                    fontStyle: 'bold',
                    lineColor: [0, 0, 0],
                    lineWidth: 0.25
                },
                columnStyles: {
                    0: { cellWidth: 19 },
                    1: { cellWidth: 21 },
                    2: { cellWidth: 21 },
                    3: { cellWidth: 21 },
                    4: { cellWidth: 21 },
                    5: { cellWidth: 36 },
                    6: { cellWidth: 35 }
                }
            });

            const p1TableEndY = doc.lastAutoTable.finalY;
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(8.5);
            doc.text('Catatan:', 18, p1TableEndY + 6.5);
            doc.setFont('helvetica', 'normal');
            doc.text('*Toleransi ±15 menit', 18, p1TableEndY + 11.2);

            doc.setFont('helvetica', 'bold');
            doc.text('PELAKSANA', 132, p1TableEndY + 6.5, { align: 'center' });
            doc.text('VERIFIKATOR', 173, p1TableEndY + 6.5, { align: 'center' });

            doc.setFont('helvetica', 'normal');
            if (m.pelaksana) doc.text(m.pelaksana, 132, p1TableEndY + 29, { align: 'center' });
            if (m.verifikator) doc.text(m.verifikator, 173, p1TableEndY + 29, { align: 'center' });

            doc.setFont('helvetica', 'bold');
            doc.text('Direvisi tanggal 3 Januari 2022', 18, p1TableEndY + 33);

            // Helper to draw Page 2 (08.00) and Page 3 (15.00)
            function drawChartPage(timeLabel, canvasSuhuId, canvasHumId) {
                doc.addPage();
                drawPageHeader(doc, `PEMANTAUAN SUHU DAN KELEMBAPAN RUANGAN (PUKUL ${timeLabel})`);

                const cSuhu = document.getElementById(canvasSuhuId);
                const cHum = document.getElementById(canvasHumId);
                if (cSuhu) {
                    const imgSuhu = cSuhu.toDataURL('image/png', 1.0);
                    doc.addImage(imgSuhu, 'PNG', 20, 52, 170, 68);
                }
                if (cHum) {
                    const imgHum = cHum.toDataURL('image/png', 1.0);
                    doc.addImage(imgHum, 'PNG', 20, 121.5, 170, 68);
                }

                // Bottom Left: Keterangan
                const ketY = 195.5;
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(8.5);
                doc.text('Keterangan', 18, ketY);
                doc.setFont('helvetica', 'normal');
                doc.text('UCL = Upper Control Limit (27,1°C & 70%)', 18, ketY + 5.2);
                doc.text('UWL = Upper Warning Limit (25,8°C & 63,3%)', 18, ketY + 10.4);
                doc.text('LWL = Lower Warning Limit (21,1°C & 36,7%)', 18, ketY + 15.6);
                doc.text('LCL = Lower Control Limit (19,9°C & 30%)', 18, ketY + 20.8);

                // Bottom Right: Evaluasi & Checklist Table
                const tblX = 94;
                const tblY = 192.5;
                const col1W = 63;
                const col2W = 35;
                const hHead = 5.5;
                const hBody = 18.5;
                const hFoot = 5.5;

                doc.setDrawColor(0, 0, 0);
                doc.setLineWidth(0.25);
                // Header boxes
                doc.rect(tblX, tblY, col1W, hHead);
                doc.rect(tblX + col1W, tblY, col2W, hHead);
                // Body boxes
                doc.rect(tblX, tblY + hHead, col1W, hBody);
                doc.rect(tblX + col1W, tblY + hHead, col2W, hBody);
                // Footer box
                doc.rect(tblX, tblY + hHead + hBody, col1W + col2W, hFoot);

                doc.setFont('helvetica', 'bold');
                doc.setFontSize(8.3);
                doc.text('Evaluasi', tblX + col1W / 2, tblY + 3.9, { align: 'center' });
                doc.text('Checklist*', tblX + col1W + col2W / 2, tblY + 3.9, { align: 'center' });

                doc.setFont('helvetica', 'normal');
                doc.setFontSize(7.9);
                const evalLines = [
                    'Tidak ada hasil pemantauan suhu dan',
                    'kelembapan yang lebih besar sama dengan',
                    'nilai upper control limit (UCL) dan lower',
                    'control limit (LCL) sebanyak 2 kali berturut-',
                    'turut.'
                ];
                evalLines.forEach((ln, idx) => {
                    doc.text(ln, tblX + col1W / 2, tblY + hHead + 3.6 + idx * 3.35, { align: 'center' });
                });

                // Draw Checkmark Box inside Checklist* cell
                const chkCenterX = tblX + col1W + col2W / 2;
                const chkCenterY = tblY + hHead + hBody / 2;
                if (m.evalCheck === 'V') {
                    doc.setFillColor(128, 128, 128);
                    doc.rect(chkCenterX - 2.3, chkCenterY - 2.3, 4.6, 4.6, 'F');
                    doc.setDrawColor(255, 255, 255);
                    doc.setLineWidth(0.55);
                    doc.line(chkCenterX - 1.3, chkCenterY + 0.1, chkCenterX - 0.3, chkCenterY + 1.2);
                    doc.line(chkCenterX - 0.3, chkCenterY + 1.2, chkCenterX + 1.4, chkCenterY - 1.1);
                    doc.setDrawColor(0, 0, 0);
                } else {
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(10);
                    doc.text('X', chkCenterX, chkCenterY + 1.2, { align: 'center' });
                }

                doc.setFont('helvetica', 'normal');
                doc.setFontSize(8.0);
                doc.text('* Isi dengan (V) jika "Ya" dan (X) jika "Tidak"', tblX + 2, tblY + hHead + hBody + 3.9);

                // Signatures & Revision Date
                const sigY = 231;
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(8.5);
                doc.text('PELAKSANA', 132, sigY, { align: 'center' });
                doc.text('VERIFIKATOR', 173, sigY, { align: 'center' });

                doc.setFont('helvetica', 'normal');
                if (m.pelaksana) doc.text(m.pelaksana, 132, sigY + 23.5, { align: 'center' });
                if (m.verifikator) doc.text(m.verifikator, 173, sigY + 23.5, { align: 'center' });

                doc.setFont('helvetica', 'bold');
                doc.text('Direvisi tanggal 3 Januari 2022', 18, sigY + 23.5);
            }

            // PAGE 2: PUKUL 08.00
            drawChartPage('08.00', 'canvasPreviewSuhu08', 'canvasPreviewHum08');

            // PAGE 3: PUKUL 15.00
            drawChartPage('15.00', 'canvasPreviewSuhu15', 'canvasPreviewHum15');

            const safeRoom = (m.roomName || 'Ruangan').replace(/[^a-zA-Z0-9._-]/g, '_');
            const fileName = `Pemantauan_Suhu_Kelembapan_${safeRoom}_${m.monthName}_${m.year}.pdf`;

            if (actionMode === 'download') {
                doc.save(fileName);
            } else {
                const blob = doc.output('blob');
                window.open(URL.createObjectURL(blob), '_blank');
            }
        };

        // =========================================================================
        // MODUL PERENCANAAN LABORATORIUM (RENLAK 2026 - 2027) — FULL ENGINE
        // =========================================================================
        const RENLAK_MONTH_NAMES = {
            1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April',
            5: 'Mei', 6: 'Juni', 7: 'Juli', 8: 'Agustus',
            9: 'September', 10: 'Okt', 11: 'November', 12: 'Desember'
        };

        const RENLAK_DEFAULT_SEED = [
            {
                id: 1,
                nomor_urut: 1,
                kategori: 'Perencanaan',
                nama_kegiatan: 'Pembentukan Tim Digitalisasi LAB',
                rencana_kegiatan: 'Penyusunan dan penetapan SK Tim Digitalisasi Laboratorium BBPOM di Bandung (September 2026 Minggu ke-1)',
                realisasi: 1,
                target: 1,
                satuan: 'SK',
                persentase_capaian: 100.00,
                status_kegiatan: 'Selesai',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: { '2026': { '9': { '1': 1 } }, '2027': {} },
                jadwal_realisasi: { '2026': { '10': { '4': 1 } }, '2027': {} },
                catatan_evaluasi: 'SK Tim Digitalisasi LAB telah ditetapkan dan terealisasi 100%.',
                pic_penanggung_jawab: 'Manajer Puncak / Ketua Tim Digitalisasi'
            },
            {
                id: 2,
                nomor_urut: 2,
                kategori: 'Perencanaan',
                nama_kegiatan: 'Penyusunan Program Kerja Tim Digitalisasi LAB',
                rencana_kegiatan: 'Penyusunan dokumen Program Kerja & Roadmap Tim Digitalisasi LAB (Oktober 2026 Minggu ke-3)',
                realisasi: 1,
                target: 1,
                satuan: 'Dokumen',
                persentase_capaian: 100.00,
                status_kegiatan: 'Selesai',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: { '2026': { '10': { '3': 1 } }, '2027': {} },
                jadwal_realisasi: { '2026': { '10': { '4': 1 } }, '2027': {} },
                catatan_evaluasi: 'Dokumen Program Kerja Tim Digitalisasi LAB telah disahkan.',
                pic_penanggung_jawab: 'Koordinator Perencanaan Digitalisasi'
            },
            {
                id: 3,
                nomor_urut: 4,
                kategori: 'Pengembangan Digitalisasi LAB',
                nama_kegiatan: 'Pengembangan DATA BASE Fitur Digitalisasi LAB',
                rencana_kegiatan: 'Perancangan dan integrasi 6 modul database utama fitur Digitalisasi LAB secara bertahap (2026–2027)',
                realisasi: 2,
                target: 6,
                satuan: 'Database',
                persentase_capaian: 33.33,
                status_kegiatan: 'Berjalan',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: {
                    '2026': { '10': { '4': 2 }, '11': { '2': 1 } },
                    '2027': { '3': { '1': 1 }, '6': { '1': 1 }, '9': { '1': 1 } }
                },
                jadwal_realisasi: { '2026': { '10': { '4': 2 } }, '2027': {} },
                catatan_evaluasi: '2 dari 6 skema database modul utama telah aktif dan berjalan.',
                pic_penanggung_jawab: 'Tim Database & Backend'
            },
            {
                id: 4,
                nomor_urut: 5,
                kategori: 'Pengembangan Digitalisasi LAB',
                nama_kegiatan: 'Pengembangan Aplikasi Digitalisasi LAB',
                rencana_kegiatan: 'Pengembangan portal aplikasi terintegrasi DigitLab BDG tahap 1 (Okt 2026) dan tahap 2 (Jun 2027)',
                realisasi: 1,
                target: 2,
                satuan: 'Aplikasi',
                persentase_capaian: 50.00,
                status_kegiatan: 'Berjalan',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: {
                    '2026': { '10': { '1': 1 } },
                    '2027': { '6': { '1': 1 } }
                },
                jadwal_realisasi: { '2026': { '10': { '4': 1 } }, '2027': {} },
                catatan_evaluasi: 'Tahap 1 aplikasi utama DigitLab BDG telah beroperasi (50%).',
                pic_penanggung_jawab: 'Tim Pengembang Sistem'
            },
            {
                id: 5,
                nomor_urut: 6,
                kategori: 'Pengembangan Digitalisasi LAB',
                nama_kegiatan: 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Akomodasi Lingkungan (IoT)',
                rencana_kegiatan: 'Implementasi pemantauan suhu & kelembapan berbasis sensor IoT real-time dan laporan PDF BBPOM',
                realisasi: 1,
                target: 1,
                satuan: 'Fitur',
                persentase_capaian: 100.00,
                status_kegiatan: 'Selesai',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: { '2026': { '10': { '4': 1 } }, '2027': {} },
                jadwal_realisasi: { '2026': { '10': { '4': 1 } }, '2027': {} },
                catatan_evaluasi: 'Fitur Akomodasi & Lingkungan IoT selesai 100% dan telah digunakan.',
                pic_penanggung_jawab: 'Tim IoT & Akomodasi Lingkungan'
            },
            {
                id: 6,
                nomor_urut: 7,
                kategori: 'Pengembangan Digitalisasi LAB',
                nama_kegiatan: 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Pelaksanaan Pengujian dan LIMS',
                rencana_kegiatan: 'Pengembangan modul pengelolaan sampel, status pengujian, integrasi LIMS, reagen, dan HULS',
                realisasi: 0,
                target: 2,
                satuan: 'Fitur',
                persentase_capaian: 0.00,
                status_kegiatan: 'Belum Dimulai',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: { '2026': { '11': { '2': 1 }, '12': { '4': 1 } }, '2027': {} },
                jadwal_realisasi: { '2026': {}, '2027': {} },
                catatan_evaluasi: 'Dijadwalkan pada November M2 dan Desember M4 2026.',
                pic_penanggung_jawab: 'Tim Pengujian & LIMS'
            },
            {
                id: 7,
                nomor_urut: 8,
                kategori: 'Pengembangan Digitalisasi LAB',
                nama_kegiatan: 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Keamanan dan Integritas Data LAB',
                rencana_kegiatan: 'Implementasi audit trail, otorisasi berjenjang ISO/IEC 17025, dan keamanan rekaman elektronik',
                realisasi: 0,
                target: 1,
                satuan: 'Fitur',
                persentase_capaian: 0.00,
                status_kegiatan: 'Belum Dimulai',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: { '2026': { '11': { '4': 1 } }, '2027': {} },
                jadwal_realisasi: { '2026': {}, '2027': {} },
                catatan_evaluasi: 'Dijadwalkan pada November Minggu ke-4 Tahun 2026.',
                pic_penanggung_jawab: 'Tim Keamanan Data & Mutu'
            },
            {
                id: 8,
                nomor_urut: 9,
                kategori: 'Pengembangan Digitalisasi LAB',
                nama_kegiatan: 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Pengelolaan Instrumen, Inventori & Kalibrasi',
                rencana_kegiatan: 'Pengembangan modul inventaris instrumen lab, kartu stok inventori, dan jadwal kalibrasi berkala',
                realisasi: 0,
                target: 3,
                satuan: 'Fitur',
                persentase_capaian: 0.00,
                status_kegiatan: 'Belum Dimulai',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: {
                    '2026': { '11': { '4': 1 } },
                    '2027': { '1': { '1': 1 }, '2': { '1': 1 } }
                },
                jadwal_realisasi: { '2026': {}, '2027': {} },
                catatan_evaluasi: 'Dijadwalkan bertahap mulai Nov 2026 hingga Feb 2027.',
                pic_penanggung_jawab: 'Tim Instrumen & Kalibrasi'
            },
            {
                id: 9,
                nomor_urut: 10,
                kategori: 'Pengembangan Digitalisasi LAB',
                nama_kegiatan: 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Metode, Validasi, Mutu & Kompetensi SDM',
                rencana_kegiatan: 'Pengembangan modul dokumen metode analisis, validasi/verifikasi, IQC mutu, dan matriks SDM',
                realisasi: 0,
                target: 4,
                satuan: 'Fitur',
                persentase_capaian: 0.00,
                status_kegiatan: 'Belum Dimulai',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: {
                    '2026': { '12': { '1': 1 } },
                    '2027': { '1': { '3': 1 }, '2': { '3': 1 }, '3': { '4': 1 } }
                },
                jadwal_realisasi: { '2026': {}, '2027': {} },
                catatan_evaluasi: 'Dijadwalkan mulai Desember 2026 hingga Maret 2027.',
                pic_penanggung_jawab: 'Tim Jaminan Mutu & SDM'
            },
            {
                id: 10,
                nomor_urut: 1,
                kategori: 'Monitoring dan Evaluasi',
                nama_kegiatan: 'Rekon Data, Monitoring dan Evaluasi DIGITLAB',
                rencana_kegiatan: 'Rekonsiliasi data bulanan serta monitoring dan evaluasi rutin implementasi DIGITLAB (Nov 2026–Sep 2027)',
                realisasi: 0,
                target: 11,
                satuan: 'Persen',
                persentase_capaian: 0.00,
                status_kegiatan: 'Belum Dimulai',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: {
                    '2026': { '11': { '3': 1 }, '12': { '3': 1 } },
                    '2027': {
                        '1': { '3': 1 }, '2': { '4': 1 }, '3': { '4': 1 },
                        '4': { '4': 1 }, '5': { '4': 1 }, '6': { '4': 1 },
                        '7': { '3': 1 }, '8': { '3': 1 }, '9': { '3': 1 }
                    }
                },
                jadwal_realisasi: { '2026': {}, '2027': {} },
                catatan_evaluasi: 'Monitoring berkala setiap bulan dari Nov 2026 s.d. Sep 2027.',
                pic_penanggung_jawab: 'Tim Monev Digitalisasi Lab'
            },
            {
                id: 11,
                nomor_urut: 2,
                kategori: 'Monitoring dan Evaluasi',
                nama_kegiatan: 'Tinjau Ulang Manajemen DIGITLAB',
                rencana_kegiatan: 'Rapat Tinjauan Manajemen (RTM) evaluasi menyeluruh sistem Digitalisasi Laboratorium',
                realisasi: 0,
                target: 1,
                satuan: 'Persen',
                persentase_capaian: 0.00,
                status_kegiatan: 'Belum Dimulai',
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: { '2026': {}, '2027': { '1': { '2': 1 } } },
                jadwal_realisasi: { '2026': {}, '2027': {} },
                catatan_evaluasi: 'Dijadwalkan pada Januari 2027 Minggu ke-2.',
                pic_penanggung_jawab: 'Manajer Mutu & Kepala BBPOM'
            }
        ];

        const RENLAK_STORAGE_KEY = 'digitlab_bdg_renlak_v1';
        const RENLAK_AUDIT_STORAGE_KEY = 'digitlab_bdg_renlak_audit_v1';

        let renlakActivities = JSON.parse(JSON.stringify(RENLAK_DEFAULT_SEED));
        let renlakAuditLogs = [];
        let renlakActiveWorkspaceTab = 'matrix';
        let chartRenlakBarInstance = null;
        let chartRenlakPieInstance = null;

        // Modal schedule picker state
        let modalTempJadwalRencana = { '2026': {}, '2027': {} };
        let modalTempJadwalRealisasi = { '2026': {}, '2027': {} };
        let modalScheduleEditMode = 'plan'; // 'plan' | 'real'

        function escapeRenlakHtml(str) {
            return String(str ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function calcRenlakPercent(realisasi, target) {
            const r = parseFloat(realisasi) || 0;
            const t = parseFloat(target) || 0;
            if (t <= 0) return 0;
            return Math.round((r / t) * 10000) / 100;
        }

        function determineRenlakAutoStatus(act) {
            const pct = calcRenlakPercent(act.realisasi, act.target);
            if (pct >= 100) return 'Selesai';
            if (pct > 0) return 'Berjalan';

            // Check if any planned week is before October 2026 W4 (reference date 29-Oct-26) with 0 realization
            const plan2026 = (act.jadwal_rencana && act.jadwal_rencana['2026']) ? act.jadwal_rencana['2026'] : {};
            let hasPastPlan = false;
            Object.keys(plan2026).forEach(mStr => {
                const m = parseInt(mStr, 10);
                if (m < 10) {
                    const wks = plan2026[mStr] || {};
                    Object.values(wks).forEach(v => { if (parseFloat(v) > 0) hasPastPlan = true; });
                } else if (m === 10) {
                    const wks = plan2026[mStr] || {};
                    ['1', '2', '3'].forEach(w => { if (parseFloat(wks[w]) > 0) hasPastPlan = true; });
                }
            });
            if (hasPastPlan && (parseFloat(act.realisasi) || 0) <= 0) {
                return 'Terlambat';
            }
            return 'Belum Dimulai';
        }

        function summarizeRenlakScheduleText(jadwalObj) {
            if (!jadwalObj || typeof jadwalObj !== 'object') return '-';
            const parts = [];
            ['2026', '2027'].forEach(yr => {
                const yrMap = jadwalObj[yr];
                if (!yrMap || typeof yrMap !== 'object') return;
                const mKeys = Object.keys(yrMap).map(Number).sort((a, b) => a - b);
                mKeys.forEach(m => {
                    const wMap = yrMap[String(m)];
                    if (!wMap || typeof wMap !== 'object') return;
                    const activeWeeks = Object.keys(wMap)
                        .filter(w => parseFloat(wMap[w]) > 0)
                        .sort((a, b) => Number(a) - Number(b))
                        .map(w => 'M' + w + (parseFloat(wMap[w]) > 1 ? '(' + wMap[w] + ')' : ''));
                    if (activeWeeks.length > 0) {
                        parts.push(`${RENLAK_MONTH_NAMES[m] || ('Bln ' + m)} ${yr}: ${activeWeeks.join(', ')}`);
                    }
                });
            });
            return parts.length > 0 ? parts.join(' | ') : 'Belum dijadwalkan';
        }

        function normalizeRenlakActivityList(rawList) {
            if (!Array.isArray(rawList) || rawList.length === 0) {
                return JSON.parse(JSON.stringify(RENLAK_DEFAULT_SEED));
            }
            return rawList.map((item, idx) => {
                const real = parseFloat(item.realisasi) || 0;
                const targ = parseFloat(item.target) || 0;
                const pct = calcRenlakPercent(real, targ);
                const jr = (item.jadwal_rencana && typeof item.jadwal_rencana === 'object')
                    ? item.jadwal_rencana
                    : { '2026': {}, '2027': {} };
                const jreal = (item.jadwal_realisasi && typeof item.jadwal_realisasi === 'object')
                    ? item.jadwal_realisasi
                    : { '2026': {}, '2027': {} };

                const normalized = {
                    id: parseInt(item.id, 10) || (idx + 1),
                    nomor_urut: parseInt(item.nomor_urut, 10) || (idx + 1),
                    kategori: item.kategori || 'Pengembangan Digitalisasi LAB',
                    nama_kegiatan: item.nama_kegiatan || '',
                    rencana_kegiatan: item.rencana_kegiatan || '',
                    realisasi: real,
                    target: targ,
                    satuan: item.satuan || 'Fitur',
                    persentase_capaian: pct,
                    status_kegiatan: item.status_kegiatan || 'Belum Dimulai',
                    tahun_awal: parseInt(item.tahun_awal, 10) || 2026,
                    tahun_akhir: parseInt(item.tahun_akhir, 10) || 2027,
                    jadwal_rencana: jr,
                    jadwal_realisasi: jreal,
                    catatan_evaluasi: item.catatan_evaluasi || '',
                    pic_penanggung_jawab: item.pic_penanggung_jawab || 'Tim Digitalisasi Lab'
                };
                if (!['Belum Dimulai', 'Berjalan', 'Selesai', 'Terlambat'].includes(normalized.status_kegiatan)) {
                    normalized.status_kegiatan = determineRenlakAutoStatus(normalized);
                }
                return normalized;
            });
        }

        function appendLocalAuditLog(actionType, actName, descText) {
            const entry = {
                id: Date.now(),
                action_type: actionType,
                nama_kegiatan: actName,
                change_description: descText,
                changed_by: (typeof LOGGED_IN_USER_NAME !== 'undefined' ? LOGGED_IN_USER_NAME : 'Admin Lab'),
                created_at: new Date().toISOString().replace('T', ' ').substring(0, 19)
            };
            renlakAuditLogs.unshift(entry);
            try {
                localStorage.setItem(RENLAK_AUDIT_STORAGE_KEY, JSON.stringify(renlakAuditLogs.slice(0, 100)));
            } catch (e) {}
        }

        window.loadRenlakData = async function() {
            // First load any cached local state for instant responsiveness
            try {
                const cached = localStorage.getItem(RENLAK_STORAGE_KEY);
                if (cached) {
                    const parsed = JSON.parse(cached);
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        renlakActivities = normalizeRenlakActivityList(parsed);
                    }
                }
                const cachedAudit = localStorage.getItem(RENLAK_AUDIT_STORAGE_KEY);
                if (cachedAudit) {
                    const parsedAudit = JSON.parse(cachedAudit);
                    if (Array.isArray(parsedAudit)) renlakAuditLogs = parsedAudit;
                }
            } catch (e) {}

            renderRenlakAll();

            // Fetch authoritative data from CodeIgniter 4 Backend API
            try {
                const res = await fetch(`${API_BASE}/data?key=renlakData&_t=${Date.now()}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const json = await res.json();
                    if (json && json.status === 'success' && Array.isArray(json.activities) && json.activities.length > 0) {
                        renlakActivities = normalizeRenlakActivityList(json.activities);
                        localStorage.setItem(RENLAK_STORAGE_KEY, JSON.stringify(renlakActivities));
                    }
                    if (json && Array.isArray(json.audit_logs) && json.audit_logs.length > 0) {
                        renlakAuditLogs = json.audit_logs;
                        localStorage.setItem(RENLAK_AUDIT_STORAGE_KEY, JSON.stringify(renlakAuditLogs));
                    }
                    renderRenlakAll();
                }
            } catch (err) {
                console.warn('RENLAK menggunakan penyimpanan sinkronisasi lokal sementara:', err);
            }
        };

        function getVisibleTimelineMonths() {
            const yrFilter = (document.getElementById('renlakFilterTahun')?.value || 'ALL');
            const perFilter = (document.getElementById('renlakFilterPeriode')?.value || 'EXCEL_DEFAULT');

            let months2026 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            let months2027 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

            if (perFilter === 'EXCEL_DEFAULT') {
                months2026 = [9, 10, 11, 12];
                months2027 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            } else if (perFilter === 'Q1') {
                months2026 = [1, 2, 3];
                months2027 = [1, 2, 3];
            } else if (perFilter === 'Q2') {
                months2026 = [4, 5, 6];
                months2027 = [4, 5, 6];
            } else if (perFilter === 'Q3') {
                months2026 = [7, 8, 9];
                months2027 = [7, 8, 9];
            } else if (perFilter === 'Q4') {
                months2026 = [10, 11, 12];
                months2027 = [10, 11, 12];
            } else if (perFilter.startsWith('M_')) {
                const mNum = parseInt(perFilter.replace('M_', ''), 10);
                if (mNum >= 1 && mNum <= 12) {
                    months2026 = [mNum];
                    months2027 = [mNum];
                }
            }

            const result = [];
            if (yrFilter === 'ALL' || yrFilter === '2026') {
                if (months2026.length > 0) result.push({ year: '2026', months: months2026 });
            }
            if (yrFilter === 'ALL' || yrFilter === '2027') {
                if (months2027.length > 0) result.push({ year: '2027', months: months2027 });
            }
            return result;
        }

        function getFilteredRenlakActivities() {
            const q = (document.getElementById('renlakFilterSearch')?.value || '').trim().toLowerCase();
            const kat = (document.getElementById('renlakFilterKategori')?.value || 'ALL');
            const st = (document.getElementById('renlakFilterStatus')?.value || 'ALL');

            return renlakActivities.filter(act => {
                if (kat !== 'ALL' && act.kategori !== kat) return false;
                if (st !== 'ALL' && act.status_kegiatan !== st) return false;
                if (q !== '') {
                    const hay = `${act.nama_kegiatan} ${act.kategori} ${act.satuan} ${act.rencana_kegiatan} ${act.pic_penanggung_jawab}`.toLowerCase();
                    if (!hay.includes(q)) return false;
                }
                return true;
            });
        }

        window.applyRenlakFilters = function() {
            renderRenlakAll();
        };

        window.renderRenlakAll = function() {
            const filtered = getFilteredRenlakActivities();
            renderRenlakKpis(filtered);
            renderRenlakMatrixTable(filtered);
            renderRenlakMonevTab(filtered);
            renderRenlakAuditTab();
            if (renlakActiveWorkspaceTab === 'monev') {
                renderRenlakCharts(filtered);
            }
        };

        function renderRenlakKpis(list) {
            const totalKeg = list.length;
            let totalTarget = 0;
            let totalReal = 0;
            let sumPct = 0;
            const counts = { 'Selesai': 0, 'Berjalan': 0, 'Belum Dimulai': 0, 'Terlambat': 0 };
            const categoriesSet = new Set();

            list.forEach(a => {
                const r = parseFloat(a.realisasi) || 0;
                const t = parseFloat(a.target) || 0;
                const p = calcRenlakPercent(r, t);
                a.persentase_capaian = p;
                totalTarget += t;
                totalReal += r;
                sumPct += p;
                categoriesSet.add(a.kategori);
                const st = a.status_kegiatan || 'Belum Dimulai';
                if (counts[st] !== undefined) counts[st]++;
                else counts['Belum Dimulai']++;
            });

            const avgPct = totalKeg > 0 ? (sumPct / totalKeg) : 0;
            const volPct = totalTarget > 0 ? ((totalReal / totalTarget) * 100) : 0;

            const elTotal = document.getElementById('renlakKpiTotalKegiatan');
            const elKatSub = document.getElementById('renlakKpiKategoriSub');
            const elTarget = document.getElementById('renlakKpiTotalTarget');
            const elReal = document.getElementById('renlakKpiTotalRealisasi');
            const elRata = document.getElementById('renlakKpiRataCapaian');
            const elBobot = document.getElementById('renlakKpiBobotCapaian');
            const elBar = document.getElementById('renlakKpiProgressBar');

            if (elTotal) elTotal.textContent = totalKeg;
            if (elKatSub) elKatSub.textContent = `${categoriesSet.size} Kategori Sasaran Aktif`;
            if (elTarget) elTarget.textContent = Number.isInteger(totalTarget) ? totalTarget : totalTarget.toFixed(2);
            if (elReal) elReal.textContent = Number.isInteger(totalReal) ? totalReal : totalReal.toFixed(2);
            if (elRata) elRata.textContent = `${avgPct.toFixed(2)}%`;
            if (elBobot) elBobot.textContent = `(Vol: ${volPct.toFixed(2)}%)`;
            if (elBar) elBar.style.width = `${Math.min(100, Math.max(0, avgPct)).toFixed(1)}%`;

            const elSelesai = document.getElementById('renlakStatSelesai');
            const elBerjalan = document.getElementById('renlakStatBerjalan');
            const elBelum = document.getElementById('renlakStatBelum');
            const elTerlambat = document.getElementById('renlakStatTerlambat');
            if (elSelesai) elSelesai.textContent = counts['Selesai'];
            if (elBerjalan) elBerjalan.textContent = counts['Berjalan'];
            if (elBelum) elBelum.textContent = counts['Belum Dimulai'];
            if (elTerlambat) elTerlambat.textContent = counts['Terlambat'];

            const elShow = document.getElementById('renlakShowingInfoLabel');
            if (elShow) elShow.textContent = `Menampilkan ${totalKeg} dari ${renlakActivities.length} kegiatan`;
        }

        function getRenlakStatusBadgeHtml(status) {
            if (status === 'Selesai') {
                return `<span class="badge-status-renlak status-selesai"><i class="ti ti-circle-check"></i> Selesai</span>`;
            }
            if (status === 'Berjalan') {
                return `<span class="badge-status-renlak status-berjalan"><i class="ti ti-loader-2"></i> Berjalan</span>`;
            }
            if (status === 'Terlambat') {
                return `<span class="badge-status-renlak status-terlambat"><i class="ti ti-alert-triangle"></i> Terlambat</span>`;
            }
            return `<span class="badge-status-renlak status-belum"><i class="ti ti-clock"></i> Belum Dimulai</span>`;
        }

        function renderRenlakMatrixTable(list) {
            const thead = document.getElementById('renlakMainThead');
            const tbody = document.getElementById('renlakMainTbody');
            if (!thead || !tbody) return;

            const mode = document.getElementById('renlakTimelineMode')?.value || 'EXCEL_SPLIT';
            const weeksPerMonth = parseInt(document.getElementById('renlakWeeksPerMonth')?.value || '4', 10);
            const yearBlocks = getVisibleTimelineMonths();

            let hRow1 = `<tr class="renlak-hdr-row-1">
                <th rowspan="3" class="renlak-sticky-1">No.</th>
                <th rowspan="3" class="renlak-sticky-2">Nama Kegiatan/ Sasaran</th>
                <th rowspan="3" style="min-width: 62px;">Realisasi</th>
                <th rowspan="3" style="min-width: 58px;">Target</th>
                <th rowspan="3" style="min-width: 74px;">Satuan</th>
                <th rowspan="3" style="min-width: 82px;">% CAPAIAN</th>
                <th rowspan="3" style="min-width: 210px;">Rencana Kegiatan</th>
                <th rowspan="3" style="min-width: 170px;">Jadwal Pelaksanaan</th>
                <th rowspan="3" style="min-width: 118px;">Status</th>
                <th rowspan="3" style="min-width: 102px;">Aksi</th>`;
            let hRow2 = `<tr class="renlak-hdr-row-2">`;
            let hRow3 = `<tr class="renlak-hdr-row-3">`;

            let totalTimelineCols = 0;

            if (mode === 'EXCEL_SPLIT') {
                // Block 1: RENCANA (2026 & 2027)
                yearBlocks.forEach(yb => {
                    const colSpanYear = yb.months.length * weeksPerMonth;
                    totalTimelineCols += colSpanYear;
                    hRow1 += `<th colspan="${colSpanYear}" style="background: #f1f5f9; color: #1e293b; font-weight: 600; border-bottom: 1px solid #cbd5e1;">RENCANA ${yb.year}</th>`;
                    yb.months.forEach(m => {
                        hRow2 += `<th colspan="${weeksPerMonth}" style="background: #f8fafc; font-size: 11px; font-weight: 600; color: #475569;">${RENLAK_MONTH_NAMES[m]}</th>`;
                        for (let w = 1; w <= weeksPerMonth; w++) {
                            hRow3 += `<th style="background: #f8fafc; font-size: 10px; font-weight: 500; color: #64748b; width: 24px;">${w}</th>`;
                        }
                    });
                });

                // Block 2: REALISASI (2026 & 2027)
                yearBlocks.forEach(yb => {
                    const colSpanYear = yb.months.length * weeksPerMonth;
                    totalTimelineCols += colSpanYear;
                    hRow1 += `<th colspan="${colSpanYear}" style="background: #e2e8f0; color: #1e293b; font-weight: 600; border-bottom: 1px solid #cbd5e1;">REALISASI ${yb.year}</th>`;
                    yb.months.forEach(m => {
                        hRow2 += `<th colspan="${weeksPerMonth}" style="background: #f1f5f9; font-size: 11px; font-weight: 600; color: #475569;">${RENLAK_MONTH_NAMES[m]}</th>`;
                        for (let w = 1; w <= weeksPerMonth; w++) {
                            hRow3 += `<th style="background: #f1f5f9; font-size: 10px; font-weight: 500; color: #64748b; width: 24px;">${w}</th>`;
                        }
                    });
                });
            } else {
                // GANTT_UNIFIED: Single timeline 2026 & 2027 combining Rencana & Realisasi
                yearBlocks.forEach(yb => {
                    const colSpanYear = yb.months.length * weeksPerMonth;
                    totalTimelineCols += colSpanYear;
                    hRow1 += `<th colspan="${colSpanYear}" style="background: #034284; color: #ffffff; font-weight: 600;">TIMELINE GANTT ${yb.year}</th>`;
                    yb.months.forEach(m => {
                        hRow2 += `<th colspan="${weeksPerMonth}" style="background: #f8fafc; font-size: 11px; font-weight: 600; color: #475569;">${RENLAK_MONTH_NAMES[m]} '${yb.year.slice(-2)}</th>`;
                        for (let w = 1; w <= weeksPerMonth; w++) {
                            hRow3 += `<th style="background: #f8fafc; font-size: 10px; font-weight: 500; color: #64748b; width: 24px;">M${w}</th>`;
                        }
                    });
                });
            }

            hRow1 += `</tr>`;
            hRow2 += `</tr>`;
            hRow3 += `</tr>`;
            thead.innerHTML = hRow1 + hRow2 + hRow3;

            if (list.length === 0) {
                tbody.innerHTML = `<tr>
                    <td colspan="${10 + totalTimelineCols}" class="text-center py-5 text-muted">
                        <i class="ti ti-calendar-off fs-2 d-block mb-2"></i>
                        Tidak ada kegiatan RENLAK yang sesuai dengan filter pencarian.
                    </td>
                </tr>`;
                return;
            }

            // Group activities by Kategori while preserving order (Perencanaan -> Pengembangan Digitalisasi LAB -> Monitoring dan Evaluasi -> others)
            const categoryOrder = ['Perencanaan', 'Pengembangan Digitalisasi LAB', 'Monitoring dan Evaluasi'];
            const grouped = {};
            list.forEach(item => {
                const k = item.kategori || 'Lainnya';
                if (!grouped[k]) grouped[k] = [];
                grouped[k].push(item);
            });

            const orderedCategories = [
                ...categoryOrder.filter(k => grouped[k] && grouped[k].length > 0),
                ...Object.keys(grouped).filter(k => !categoryOrder.includes(k))
            ];

            let bodyHtml = '';
            orderedCategories.forEach(katName => {
                // Yellow Category Row (Exact Excel styling)
                bodyHtml += `<tr class="renlak-category-row">
                    <td class="renlak-sticky-1"></td>
                    <td class="renlak-sticky-2">${escapeRenlakHtml(katName)}</td>
                    <td colspan="${8 + totalTimelineCols}"></td>
                </tr>`;

                grouped[katName].forEach(act => {
                    const pct = calcRenlakPercent(act.realisasi, act.target);
                    const pctFormatted = `${pct.toFixed(2)}%`;
                    const schedSummaryPlan = summarizeRenlakScheduleText(act.jadwal_rencana);
                    const schedSummaryReal = summarizeRenlakScheduleText(act.jadwal_realisasi);
                    const isCompletedRow = pct >= 100;
                    const rowRightBg = isCompletedRow ? '#f0fdf4' : '#ffffff';

                    bodyHtml += `<tr>
                        <td class="renlak-sticky-1 text-center fw-bold">${act.nomor_urut}</td>
                        <td class="renlak-sticky-2">
                            <div class="fw-bold" style="color: #0f172a; line-height: 1.3;">${escapeRenlakHtml(act.nama_kegiatan)}</div>
                            <div class="text-muted" style="font-size: 10.5px;">PIC: ${escapeRenlakHtml(act.pic_penanggung_jawab || '-')}</div>
                        </td>
                        <td class="text-center fw-bold" style="color: #034284;">${act.realisasi}</td>
                        <td class="text-center fw-bold">${act.target}</td>
                        <td class="text-center">${escapeRenlakHtml(act.satuan)}</td>
                        <td class="renlak-cell-capaian">${pctFormatted}</td>
                        <td style="font-size: 11px; max-width: 230px; white-space: normal; line-height: 1.3;">
                            ${escapeRenlakHtml(act.rencana_kegiatan || '-')}
                        </td>
                        <td style="font-size: 10.5px; max-width: 190px; white-space: normal; line-height: 1.3;">
                            <div><strong class="text-primary">Rencana:</strong> ${escapeRenlakHtml(schedSummaryPlan)}</div>
                            ${schedSummaryReal !== 'Belum dijadwalkan' ? `<div class="mt-1"><strong class="text-success">Realisasi:</strong> ${escapeRenlakHtml(schedSummaryReal)}</div>` : ''}
                        </td>
                        <td class="text-center">${getRenlakStatusBadgeHtml(act.status_kegiatan)}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-info py-1 px-2" onclick="openRenlakDetailModal(${act.id})" title="Detail Kegiatan">
                                    <i class="ti ti-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary py-1 px-2" onclick="openRenlakModal(${act.id})" title="Edit Kegiatan">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger py-1 px-2" onclick="confirmDeleteRenlak(${act.id})" title="Hapus Kegiatan">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </td>`;

                    if (mode === 'EXCEL_SPLIT') {
                        // RENCANA CELLS
                        yearBlocks.forEach(yb => {
                            const yr = yb.year;
                            yb.months.forEach(m => {
                                for (let w = 1; w <= weeksPerMonth; w++) {
                                    const val = act.jadwal_rencana?.[yr]?.[String(m)]?.[String(w)] ?? '';
                                    const hasVal = val !== '' && parseFloat(val) > 0;
                                    const isLateCell = hasVal && act.status_kegiatan === 'Terlambat' && (parseFloat(act.realisasi) || 0) <= 0;
                                    const cellClass = isLateCell
                                        ? 'wk-active-late'
                                        : (hasVal ? 'wk-active-plan-excel' : 'wk-empty-plan');
                                    const titleTip = `RENCANA ${act.nama_kegiatan} — ${RENLAK_MONTH_NAMES[m]} ${yr} Minggu ${w}${hasVal ? ' (Nilai: ' + val + ')' : ''} [Klik untuk ubah]`;
                                    bodyHtml += `<td class="renlak-wk-cell ${cellClass}" title="${escapeRenlakHtml(titleTip)}" onclick="promptRenlakWeekCell(${act.id}, 'rencana', '${yr}', '${m}', '${w}')">${hasVal ? val : ''}</td>`;
                                }
                            });
                        });

                        // REALISASI CELLS
                        yearBlocks.forEach(yb => {
                            const yr = yb.year;
                            yb.months.forEach(m => {
                                for (let w = 1; w <= weeksPerMonth; w++) {
                                    const val = act.jadwal_realisasi?.[yr]?.[String(m)]?.[String(w)] ?? '';
                                    const hasVal = val !== '' && parseFloat(val) > 0;
                                    const cellClass = hasVal ? 'wk-active-real-excel' : 'wk-empty-real';
                                    const bgStyle = (!hasVal && isCompletedRow) ? 'style="background:#f0fdf4;"' : '';
                                    const titleTip = `REALISASI ${act.nama_kegiatan} — ${RENLAK_MONTH_NAMES[m]} ${yr} Minggu ${w}${hasVal ? ' (Nilai: ' + val + ')' : ''} [Klik untuk ubah]`;
                                    bodyHtml += `<td class="renlak-wk-cell ${cellClass}" ${bgStyle} title="${escapeRenlakHtml(titleTip)}" onclick="promptRenlakWeekCell(${act.id}, 'realisasi', '${yr}', '${m}', '${w}')">${hasVal ? val : ''}</td>`;
                                }
                            });
                        });
                    } else {
                        // GANTT_UNIFIED CELLS (Combines Rencana & Realisasi with distinct color coding)
                        yearBlocks.forEach(yb => {
                            const yr = yb.year;
                            yb.months.forEach(m => {
                                for (let w = 1; w <= weeksPerMonth; w++) {
                                    const pVal = act.jadwal_rencana?.[yr]?.[String(m)]?.[String(w)] ?? '';
                                    const rVal = act.jadwal_realisasi?.[yr]?.[String(m)]?.[String(w)] ?? '';
                                    const hasP = pVal !== '' && parseFloat(pVal) > 0;
                                    const hasR = rVal !== '' && parseFloat(rVal) > 0;

                                    let cellClass = 'wk-empty-gantt';
                                    let disp = '';
                                    let nextType = 'rencana';

                                    if (hasP && hasR) {
                                        cellClass = 'wk-active-both';
                                        disp = `${pVal}/${rVal}`;
                                        nextType = 'realisasi';
                                    } else if (hasR) {
                                        cellClass = 'wk-active-real';
                                        disp = rVal;
                                        nextType = 'realisasi';
                                    } else if (hasP) {
                                        if (act.status_kegiatan === 'Terlambat') {
                                            cellClass = 'wk-active-late';
                                        } else if (act.status_kegiatan === 'Berjalan') {
                                            cellClass = 'wk-active-ongoing';
                                        } else {
                                            cellClass = 'wk-active-plan';
                                        }
                                        disp = pVal;
                                        nextType = 'realisasi';
                                    }

                                    const titleTip = `${act.nama_kegiatan} (${RENLAK_MONTH_NAMES[m]} ${yr} M${w}) — Rencana: ${hasP ? pVal : 0}, Realisasi: ${hasR ? rVal : 0}`;
                                    bodyHtml += `<td class="renlak-wk-cell ${cellClass}" title="${escapeRenlakHtml(titleTip)}" onclick="promptRenlakWeekCell(${act.id}, '${nextType}', '${yr}', '${m}', '${w}')">${disp}</td>`;
                                }
                            });
                        });
                    }

                    bodyHtml += `</tr>`;
                });
            });

            tbody.innerHTML = bodyHtml;
        }

        window.scrollRenlakTimeline = function(deltaX) {
            const sc = document.getElementById('renlakTimelineScrollContainer');
            if (sc) sc.scrollBy({ left: deltaX, behavior: 'smooth' });
        };

        window.switchRenlakWorkspaceTab = function(tabKey) {
            renlakActiveWorkspaceTab = tabKey;
            const tMatrix = document.getElementById('renlakTabContentMatrix');
            const tMonev = document.getElementById('renlakTabContentMonev');
            const tHist = document.getElementById('renlakTabContentHistory');

            const bMatrix = document.getElementById('btnRenlakTabMatrix');
            const bMonev = document.getElementById('btnRenlakTabMonev');
            const bHist = document.getElementById('btnRenlakTabHistory');
            const legendBar = document.getElementById('renlakMatrixLegendBar');

            if (tMatrix) tMatrix.style.display = (tabKey === 'matrix') ? 'block' : 'none';
            if (tMonev) tMonev.style.display = (tabKey === 'monev') ? 'block' : 'none';
            if (tHist) tHist.style.display = (tabKey === 'history') ? 'block' : 'none';
            if (legendBar) legendBar.style.display = (tabKey === 'matrix') ? 'flex' : 'none';

            if (bMatrix) bMatrix.classList.toggle('active', tabKey === 'matrix');
            if (bMonev) bMonev.classList.toggle('active', tabKey === 'monev');
            if (bHist) bHist.classList.toggle('active', tabKey === 'history');

            if (tabKey === 'monev') {
                renderRenlakCharts(getFilteredRenlakActivities());
            }
        };

        window.promptRenlakWeekCell = async function(actId, schedType, year, month, week) {
            const act = renlakActivities.find(a => Number(a.id) === Number(actId));
            if (!act) return;

            const field = (schedType === 'realisasi') ? 'jadwal_realisasi' : 'jadwal_rencana';
            const labelType = (schedType === 'realisasi') ? 'REALISASI' : 'RENCANA';
            const currentVal = act[field]?.[year]?.[String(month)]?.[String(week)] ?? '';

            const input = window.prompt(
                `Ubah Nilai Jadwal ${labelType}\nKegiatan: ${act.nama_kegiatan}\nPeriode: ${RENLAK_MONTH_NAMES[month]} ${year} - Minggu ke-${week}\n\nMasukkan angka volume/target minggu ini (kosongkan atau isi 0 untuk menghapus penanda minggu):`,
                currentVal !== '' ? String(currentVal) : '1'
            );
            if (input === null) return;

            const trimmed = input.trim();
            const numVal = (trimmed === '' || parseFloat(trimmed) <= 0) ? 0 : parseFloat(trimmed);

            if (!act[field]) act[field] = { '2026': {}, '2027': {} };
            if (!act[field][year]) act[field][year] = {};
            if (!act[field][year][String(month)]) act[field][year][String(month)] = {};

            if (numVal <= 0) {
                delete act[field][year][String(month)][String(week)];
            } else {
                act[field][year][String(month)][String(week)] = numVal;
            }

            localStorage.setItem(RENLAK_STORAGE_KEY, JSON.stringify(renlakActivities));
            appendLocalAuditLog(
                'TIMELINE_UPDATE',
                act.nama_kegiatan,
                `Jadwal ${labelType} ${RENLAK_MONTH_NAMES[month]} ${year} M${week} diubah menjadi ${numVal > 0 ? numVal : 'Kosong'}`
            );
            renderRenlakAll();

            try {
                await fetch(`${API_BASE}/data?key=toggleRenlakWeekCell`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id: act.id,
                        schedule_type: schedType,
                        year: year,
                        month: month,
                        week: week,
                        value: numVal > 0 ? numVal : '',
                        changed_by: LOGGED_IN_USER_NAME
                    })
                });
            } catch (e) {}
        };

        function renderRenlakMonevTab(list) {
            const tbody = document.getElementById('renlakMonevTbody');
            if (!tbody) return;

            if (list.length === 0) {
                tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-muted">Tidak ada kegiatan yang ditampilkan.</td></tr>`;
                return;
            }

            let html = '';
            list.forEach(act => {
                const pct = calcRenlakPercent(act.realisasi, act.target);
                const barColor = pct >= 100 ? 'bg-success' : (pct > 0 ? 'bg-primary' : 'bg-secondary');
                const schedPlan = summarizeRenlakScheduleText(act.jadwal_rencana);

                html += `<tr>
                    <td class="text-center fw-bold">${act.nomor_urut}</td>
                    <td>
                        <div class="fw-bold text-dark">${escapeRenlakHtml(act.nama_kegiatan)}</div>
                        <small class="text-muted">${escapeRenlakHtml(act.rencana_kegiatan || '-')}</small>
                    </td>
                    <td><span class="badge bg-label-primary" style="white-space: normal; text-align: left;">${escapeRenlakHtml(act.kategori)}</span></td>
                    <td class="text-center">
                        <input type="number" step="any" min="0" class="form-control form-control-sm text-center fw-bold" value="${act.realisasi}" onchange="quickUpdateRenlakRow(${act.id}, 'realisasi', this.value)">
                    </td>
                    <td class="text-center">
                        <input type="number" step="any" min="0.01" class="form-control form-control-sm text-center fw-bold" value="${act.target}" onchange="quickUpdateRenlakRow(${act.id}, 'target', this.value)">
                    </td>
                    <td class="text-center fw-semibold">${escapeRenlakHtml(act.satuan)}</td>
                    <td>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-bold" style="color: #034284;">${pct.toFixed(2)}%</span>
                            <small class="text-muted">${act.realisasi}/${act.target}</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar ${barColor}" style="width: ${Math.min(100, pct)}%;"></div>
                        </div>
                    </td>
                    <td style="font-size: 11px;">${escapeRenlakHtml(schedPlan)}</td>
                    <td class="text-center">
                        <select class="form-select form-select-sm fw-semibold" onchange="quickUpdateRenlakRow(${act.id}, 'status_kegiatan', this.value)">
                            <option value="Belum Dimulai" ${act.status_kegiatan === 'Belum Dimulai' ? 'selected' : ''}>Belum Dimulai</option>
                            <option value="Berjalan" ${act.status_kegiatan === 'Berjalan' ? 'selected' : ''}>Berjalan</option>
                            <option value="Selesai" ${act.status_kegiatan === 'Selesai' ? 'selected' : ''}>Selesai</option>
                            <option value="Terlambat" ${act.status_kegiatan === 'Terlambat' ? 'selected' : ''}>Terlambat</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-info" onclick="openRenlakDetailModal(${act.id})" title="Detail"><i class="ti ti-eye"></i></button>
                            <button type="button" class="btn btn-outline-primary" onclick="openRenlakModal(${act.id})" title="Edit"><i class="ti ti-edit"></i></button>
                            <button type="button" class="btn btn-outline-danger" onclick="confirmDeleteRenlak(${act.id})" title="Hapus"><i class="ti ti-trash"></i></button>
                        </div>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;

            // Render Category Achievement Summary Box
            const catBox = document.getElementById('renlakCategorySummaryBox');
            if (catBox) {
                const cats = {};
                list.forEach(a => {
                    const k = a.kategori || 'Lainnya';
                    if (!cats[k]) cats[k] = { count: 0, sumPct: 0 };
                    cats[k].count++;
                    cats[k].sumPct += calcRenlakPercent(a.realisasi, a.target);
                });
                let catHtml = '<div class="fw-bold small mb-2" style="color:#034284;">Capaian Rata-rata per Kategori:</div>';
                Object.keys(cats).forEach(k => {
                    const avg = cats[k].count > 0 ? (cats[k].sumPct / cats[k].count) : 0;
                    catHtml += `<div class="mb-2">
                        <div class="d-flex justify-content-between small">
                            <span class="fw-semibold text-truncate" style="max-width: 210px;">${escapeRenlakHtml(k)}</span>
                            <span class="fw-bold text-primary">${avg.toFixed(1)}%</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: ${Math.min(100, avg)}%;"></div>
                        </div>
                    </div>`;
                });
                catBox.innerHTML = catHtml;
            }
        }

        window.quickUpdateRenlakRow = async function(actId, fieldName, rawValue) {
            const act = renlakActivities.find(a => Number(a.id) === Number(actId));
            if (!act) return;

            if (fieldName === 'realisasi') {
                act.realisasi = Math.max(0, parseFloat(rawValue) || 0);
                act.persentase_capaian = calcRenlakPercent(act.realisasi, act.target);
                act.status_kegiatan = determineRenlakAutoStatus(act);
            } else if (fieldName === 'target') {
                act.target = Math.max(0.01, parseFloat(rawValue) || 1);
                act.persentase_capaian = calcRenlakPercent(act.realisasi, act.target);
                act.status_kegiatan = determineRenlakAutoStatus(act);
            } else if (fieldName === 'status_kegiatan') {
                act.status_kegiatan = rawValue;
            }

            localStorage.setItem(RENLAK_STORAGE_KEY, JSON.stringify(renlakActivities));
            appendLocalAuditLog(
                'UPDATE',
                act.nama_kegiatan,
                `Pembaruan cepat ${fieldName}: Realisasi=${act.realisasi}, Target=${act.target}, Capaian=${act.persentase_capaian}%, Status=${act.status_kegiatan}`
            );
            renderRenlakAll();

            try {
                await fetch(`${API_BASE}/data?key=saveRenlakActivity`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ...act, changed_by: LOGGED_IN_USER_NAME })
                });
            } catch (e) {}
        };

        function renderRenlakCharts(list) {
            if (!window.Chart) return;

            // 1. Bar Chart: Target vs Realisasi
            const ctxBar = document.getElementById('chartRenlakTargetRealisasi');
            if (ctxBar) {
                if (chartRenlakBarInstance) chartRenlakBarInstance.destroy();
                const labels = list.map(a => {
                    const nm = a.nama_kegiatan || '';
                    return nm.length > 28 ? nm.substring(0, 28) + '...' : nm;
                });
                const dataTarget = list.map(a => parseFloat(a.target) || 0);
                const dataReal = list.map(a => parseFloat(a.realisasi) || 0);

                chartRenlakBarInstance = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Target Output',
                                data: dataTarget,
                                backgroundColor: '#3b82f6',
                                borderRadius: 4
                            },
                            {
                                label: 'Realisasi Output',
                                data: dataReal,
                                backgroundColor: '#10b981',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { ticks: { font: { size: 10 } } }
                        }
                    }
                });
            }

            // 2. Doughnut Chart: Status Kegiatan
            const ctxPie = document.getElementById('chartRenlakStatusPie');
            if (ctxPie) {
                if (chartRenlakPieInstance) chartRenlakPieInstance.destroy();
                const sCounts = { 'Selesai': 0, 'Berjalan': 0, 'Belum Dimulai': 0, 'Terlambat': 0 };
                list.forEach(a => {
                    const st = a.status_kegiatan || 'Belum Dimulai';
                    if (sCounts[st] !== undefined) sCounts[st]++;
                });

                chartRenlakPieInstance = new Chart(ctxPie, {
                    type: 'doughnut',
                    data: {
                        labels: ['Selesai', 'Berjalan', 'Belum Dimulai', 'Terlambat'],
                        datasets: [{
                            data: [sCounts['Selesai'], sCounts['Berjalan'], sCounts['Belum Dimulai'], sCounts['Terlambat']],
                            backgroundColor: ['#10b981', '#f59e0b', '#94a3b8', '#ef4444']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } }
                        }
                    }
                });
            }
        }

        function renderRenlakAuditTab() {
            const badge = document.getElementById('renlakAuditCountBadge');
            if (badge) badge.textContent = renlakAuditLogs.length;

            const tbody = document.getElementById('renlakAuditTbody');
            if (!tbody) return;

            if (!Array.isArray(renlakAuditLogs) || renlakAuditLogs.length === 0) {
                tbody.innerHTML = `<tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        Belum ada riwayat perubahan kegiatan yang tercatat.
                    </td>
                </tr>`;
                return;
            }

            tbody.innerHTML = renlakAuditLogs.map(log => {
                let badgeCls = 'bg-label-primary';
                if (log.action_type === 'CREATE') badgeCls = 'bg-label-success';
                else if (log.action_type === 'DELETE') badgeCls = 'bg-label-danger';
                else if (log.action_type === 'TIMELINE_UPDATE') badgeCls = 'bg-label-info';

                return `<tr>
                    <td class="text-muted small">${escapeRenlakHtml(log.created_at || '-')}</td>
                    <td class="fw-semibold">${escapeRenlakHtml(log.changed_by || 'Admin Lab')}</td>
                    <td><span class="badge ${badgeCls}">${escapeRenlakHtml(log.action_type || 'UPDATE')}</span></td>
                    <td class="fw-bold">${escapeRenlakHtml(log.nama_kegiatan || '-')}</td>
                    <td>${escapeRenlakHtml(log.change_description || '-')}</td>
                </tr>`;
            }).join('');
        }

        // =========================================================================
        // MODAL TAMBAH / EDIT & JADWAL MINGGUAN INTERAKTIF
        // =========================================================================
        window.recalcRenlakModalPercentage = function() {
            const r = parseFloat(document.getElementById('renlakInputRealisasi')?.value) || 0;
            const t = parseFloat(document.getElementById('renlakInputTarget')?.value) || 0;
            const pct = calcRenlakPercent(r, t);
            const el = document.getElementById('renlakInputPersenPreview');
            if (el) el.value = `${pct.toFixed(2)}%`;
        };

        window.switchModalScheduleMode = function(mode) {
            modalScheduleEditMode = mode;
            const btnPlan = document.getElementById('btnPickerModePlan');
            const btnReal = document.getElementById('btnPickerModeReal');
            if (btnPlan) {
                btnPlan.className = (mode === 'plan') ? 'btn btn-primary active' : 'btn btn-outline-primary';
            }
            if (btnReal) {
                btnReal.className = (mode === 'real') ? 'btn btn-success active' : 'btn btn-outline-success';
            }
            renderModalSchedulePickerGrid();
        };

        window.renderModalSchedulePickerGrid = function() {
            const container = document.getElementById('modalSchedulePickerContainer');
            if (!container) return;

            const yr = document.getElementById('modalScheduleYearSelect')?.value || '2026';
            const activeMap = (modalScheduleEditMode === 'real') ? modalTempJadwalRealisasi : modalTempJadwalRencana;
            if (!activeMap[yr]) activeMap[yr] = {};

            let html = '';
            for (let m = 1; m <= 12; m++) {
                const mStr = String(m);
                const wMap = activeMap[yr][mStr] || {};

                html += `<div class="col-6 col-md-3">
                    <div class="renlak-picker-month-box">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold small" style="color: #0f172a;">${RENLAK_MONTH_NAMES[m]} ${yr}</span>
                            <span class="text-muted" style="font-size: 10px;">M1–M5</span>
                        </div>
                        <div class="d-flex gap-1 justify-content-between">`;

                for (let w = 1; w <= 5; w++) {
                    const wStr = String(w);
                    const val = wMap[wStr];
                    const isSelected = val !== undefined && val !== '' && parseFloat(val) > 0;
                    const selClass = isSelected
                        ? (modalScheduleEditMode === 'real' ? 'selected-real' : 'selected-plan')
                        : '';
                    html += `<button type="button" class="renlak-picker-wk-btn ${selClass}" onclick="toggleModalWeekPick('${yr}', '${mStr}', '${wStr}')" title="Minggu ${w}${isSelected ? ' (Nilai: ' + val + ')' : ''}">
                        ${isSelected ? 'M' + w + (parseFloat(val) > 1 ? ':' + val : '') : 'M' + w}
                    </button>`;
                }

                html += `</div></div></div>`;
            }

            container.innerHTML = html;
        };

        window.toggleModalWeekPick = function(yr, mStr, wStr) {
            const activeMap = (modalScheduleEditMode === 'real') ? modalTempJadwalRealisasi : modalTempJadwalRencana;
            if (!activeMap[yr]) activeMap[yr] = {};
            if (!activeMap[yr][mStr]) activeMap[yr][mStr] = {};

            const cur = activeMap[yr][mStr][wStr];
            if (cur !== undefined && parseFloat(cur) > 0) {
                delete activeMap[yr][mStr][wStr];
            } else {
                activeMap[yr][mStr][wStr] = 1;
            }
            renderModalSchedulePickerGrid();
        };

        window.openRenlakModal = function(actId = 0) {
            const isEdit = Number(actId) > 0;
            const act = isEdit ? renlakActivities.find(a => Number(a.id) === Number(actId)) : null;

            const titleEl = document.getElementById('modalRenlakFormTitle');
            if (titleEl) {
                titleEl.innerHTML = isEdit
                    ? `<i class="ti ti-edit me-1"></i> Edit Kegiatan RENLAK`
                    : `<i class="ti ti-calendar-plus me-1"></i> Tambah Kegiatan RENLAK Baru`;
            }

            document.getElementById('renlakInputId').value = act ? act.id : 0;
            document.getElementById('renlakInputNomor').value = act ? act.nomor_urut : (renlakActivities.length + 1);
            document.getElementById('renlakInputKategori').value = act ? act.kategori : 'Pengembangan Digitalisasi LAB';
            document.getElementById('renlakInputNama').value = act ? act.nama_kegiatan : '';
            document.getElementById('renlakInputRealisasi').value = act ? act.realisasi : 0;
            document.getElementById('renlakInputTarget').value = act ? act.target : 1;
            document.getElementById('renlakInputSatuan').value = act ? act.satuan : 'Fitur';
            document.getElementById('renlakInputStatus').value = act ? act.status_kegiatan : 'AUTO';
            document.getElementById('renlakInputRencanaText').value = act ? (act.rencana_kegiatan || '') : '';
            document.getElementById('renlakInputPic').value = act ? (act.pic_penanggung_jawab || 'Tim Digitalisasi Lab') : 'Tim Digitalisasi Lab';
            document.getElementById('renlakInputCatatan').value = act ? (act.catatan_evaluasi || '') : '';

            modalTempJadwalRencana = act && act.jadwal_rencana
                ? JSON.parse(JSON.stringify(act.jadwal_rencana))
                : { '2026': {}, '2027': {} };
            modalTempJadwalRealisasi = act && act.jadwal_realisasi
                ? JSON.parse(JSON.stringify(act.jadwal_realisasi))
                : { '2026': {}, '2027': {} };

            recalcRenlakModalPercentage();
            switchModalScheduleMode('plan');

            const modalEl = document.getElementById('modalRenlakForm');
            if (modalEl && window.bootstrap) {
                const m = bootstrap.Modal.getOrCreateInstance(modalEl);
                m.show();
            }
        };

        window.saveRenlakForm = async function() {
            const id = parseInt(document.getElementById('renlakInputId')?.value || '0', 10);
            const nomor_urut = parseInt(document.getElementById('renlakInputNomor')?.value || '1', 10);
            const kategori = (document.getElementById('renlakInputKategori')?.value || '').trim();
            const nama_kegiatan = (document.getElementById('renlakInputNama')?.value || '').trim();
            const realisasi = parseFloat(document.getElementById('renlakInputRealisasi')?.value || '0');
            const target = parseFloat(document.getElementById('renlakInputTarget')?.value || '0');
            const satuan = (document.getElementById('renlakInputSatuan')?.value || '').trim();
            const statusSelect = document.getElementById('renlakInputStatus')?.value || 'AUTO';
            const rencana_kegiatan = (document.getElementById('renlakInputRencanaText')?.value || '').trim();
            const pic_penanggung_jawab = (document.getElementById('renlakInputPic')?.value || '').trim();
            const catatan_evaluasi = (document.getElementById('renlakInputCatatan')?.value || '').trim();

            if (!nama_kegiatan) {
                alert('Nama Kegiatan / Sasaran wajib diisi!');
                return;
            }
            if (!kategori) {
                alert('Kategori Kegiatan wajib diisi!');
                return;
            }
            if (isNaN(target) || target <= 0) {
                alert('Target harus berupa angka lebih dari 0!');
                return;
            }
            if (isNaN(realisasi) || realisasi < 0) {
                alert('Realisasi tidak boleh bernilai negatif!');
                return;
            }

            const persentase_capaian = calcRenlakPercent(realisasi, target);
            const tempObj = {
                id: id > 0 ? id : Date.now(),
                nomor_urut,
                kategori,
                nama_kegiatan,
                rencana_kegiatan: rencana_kegiatan || summarizeRenlakScheduleText(modalTempJadwalRencana),
                realisasi,
                target,
                satuan: satuan || 'Kegiatan',
                persentase_capaian,
                status_kegiatan: statusSelect,
                tahun_awal: 2026,
                tahun_akhir: 2027,
                jadwal_rencana: JSON.parse(JSON.stringify(modalTempJadwalRencana)),
                jadwal_realisasi: JSON.parse(JSON.stringify(modalTempJadwalRealisasi)),
                catatan_evaluasi,
                pic_penanggung_jawab: pic_penanggung_jawab || 'Tim Digitalisasi Lab'
            };

            if (statusSelect === 'AUTO') {
                tempObj.status_kegiatan = determineRenlakAutoStatus(tempObj);
            }

            if (id > 0) {
                const idx = renlakActivities.findIndex(a => Number(a.id) === Number(id));
                if (idx >= 0) renlakActivities[idx] = tempObj;
                appendLocalAuditLog('UPDATE', nama_kegiatan, `Kegiatan diperbarui (Realisasi: ${realisasi}/${target} ${tempObj.satuan} = ${persentase_capaian}%, Status: ${tempObj.status_kegiatan})`);
            } else {
                renlakActivities.push(tempObj);
                appendLocalAuditLog('CREATE', nama_kegiatan, `Kegiatan baru ditambahkan pada kategori ${kategori} (Target: ${target} ${tempObj.satuan})`);
            }

            localStorage.setItem(RENLAK_STORAGE_KEY, JSON.stringify(renlakActivities));
            renderRenlakAll();

            const modalEl = document.getElementById('modalRenlakForm');
            if (modalEl && window.bootstrap) {
                const m = bootstrap.Modal.getInstance(modalEl);
                if (m) m.hide();
            }

            try {
                const res = await fetch(`${API_BASE}/data?key=saveRenlakActivity`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ...tempObj, id: id, changed_by: LOGGED_IN_USER_NAME })
                });
                if (res.ok) {
                    const json = await res.json();
                    if (json && json.status === 'success' && json.activity && id === 0) {
                        tempObj.id = json.activity.id;
                        localStorage.setItem(RENLAK_STORAGE_KEY, JSON.stringify(renlakActivities));
                        renderRenlakAll();
                    }
                }
            } catch (e) {}
        };

        window.confirmDeleteRenlak = async function(actId) {
            const act = renlakActivities.find(a => Number(a.id) === Number(actId));
            if (!act) return;

            const ok = window.confirm(`Apakah Anda yakin ingin menghapus kegiatan berikut dari RENLAK?\n\n"${act.nama_kegiatan}"\nKategori: ${act.kategori}`);
            if (!ok) return;

            renlakActivities = renlakActivities.filter(a => Number(a.id) !== Number(actId));
            localStorage.setItem(RENLAK_STORAGE_KEY, JSON.stringify(renlakActivities));
            appendLocalAuditLog('DELETE', act.nama_kegiatan, `Kegiatan dihapus dari daftar RENLAK.`);
            renderRenlakAll();

            try {
                await fetch(`${API_BASE}/data?key=deleteRenlakActivity`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: actId, changed_by: LOGGED_IN_USER_NAME })
                });
            } catch (e) {}
        };

        window.openRenlakDetailModal = function(actId) {
            const act = renlakActivities.find(a => Number(a.id) === Number(actId));
            if (!act) return;

            const pct = calcRenlakPercent(act.realisasi, act.target);
            const schedPlan = summarizeRenlakScheduleText(act.jadwal_rencana);
            const schedReal = summarizeRenlakScheduleText(act.jadwal_realisasi);

            const bodyEl = document.getElementById('modalRenlakDetailBody');
            if (bodyEl) {
                bodyEl.innerHTML = `
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom">
                        <div>
                            <span class="badge bg-label-primary mb-1">${escapeRenlakHtml(act.kategori)}</span>
                            <h5 class="fw-bold mb-0" style="color: #0f172a;">${act.nomor_urut}. ${escapeRenlakHtml(act.nama_kegiatan)}</h5>
                        </div>
                        <div>${getRenlakStatusBadgeHtml(act.status_kegiatan)}</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded border bg-light text-center">
                                <div class="text-muted small">Target</div>
                                <div class="fs-5 fw-bold text-dark">${act.target} ${escapeRenlakHtml(act.satuan)}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded border bg-light text-center">
                                <div class="text-muted small">Realisasi</div>
                                <div class="fs-5 fw-bold text-success">${act.realisasi} ${escapeRenlakHtml(act.satuan)}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded border text-center" style="background: #dcfce7;">
                                <div class="text-muted small">% Capaian</div>
                                <div class="fs-5 fw-bold text-success">${pct.toFixed(2)}%</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded border bg-light text-center">
                                <div class="text-muted small">Periode Tahun</div>
                                <div class="fs-6 fw-bold text-primary">${act.tahun_awal} &ndash; ${act.tahun_akhir}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold small text-muted d-block mb-1">Rencana Kegiatan / Uraian Sasaran:</label>
                        <div class="p-2 rounded border bg-white">${escapeRenlakHtml(act.rencana_kegiatan || '-')}</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="fw-bold small text-primary d-block mb-1"><i class="ti ti-calendar-event me-1"></i> Jadwal Pelaksanaan (Rencana):</label>
                            <div class="p-2 rounded border" style="background: #fffbeb;">${escapeRenlakHtml(schedPlan)}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="fw-bold small text-success d-block mb-1"><i class="ti ti-checks me-1"></i> Jadwal Pelaksanaan (Realisasi):</label>
                            <div class="p-2 rounded border" style="background: #f0fdf4;">${escapeRenlakHtml(schedReal)}</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="fw-bold small text-muted d-block mb-1">Penanggung Jawab (PIC):</label>
                            <div class="p-2 rounded border bg-light">${escapeRenlakHtml(act.pic_penanggung_jawab || '-')}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="fw-bold small text-muted d-block mb-1">Catatan Monitoring &amp; Evaluasi:</label>
                            <div class="p-2 rounded border bg-light">${escapeRenlakHtml(act.catatan_evaluasi || '-')}</div>
                        </div>
                    </div>
                `;
            }

            const btnEdit = document.getElementById('btnDetailEditRenlak');
            if (btnEdit) {
                btnEdit.onclick = function() {
                    const md = document.getElementById('modalRenlakDetail');
                    if (md && window.bootstrap) {
                        const inst = bootstrap.Modal.getInstance(md);
                        if (inst) inst.hide();
                    }
                    openRenlakModal(act.id);
                };
            }

            const modalEl = document.getElementById('modalRenlakDetail');
            if (modalEl && window.bootstrap) {
                const m = bootstrap.Modal.getOrCreateInstance(modalEl);
                m.show();
            }
        };

        window.resetRenlakToExcelDefault = async function() {
            const ok = window.confirm('Kembalikan data RENLAK ke 11 kegiatan standar sesuai referensi Excel BBPOM di Bandung?');
            if (!ok) return;

            renlakActivities = JSON.parse(JSON.stringify(RENLAK_DEFAULT_SEED));
            localStorage.setItem(RENLAK_STORAGE_KEY, JSON.stringify(renlakActivities));
            appendLocalAuditLog('RESET_DEFAULT', 'Seluruh Kegiatan RENLAK', 'Data RENLAK direset ke 11 kegiatan referensi Excel (29-Oct-26).');
            renderRenlakAll();

            try {
                await fetch(`${API_BASE}/data?key=resetRenlakDefault`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ changed_by: LOGGED_IN_USER_NAME })
                });
            } catch (e) {}
        };

        // =========================================================================
        // LAPORAN RENLAK: EKSPOR EXCEL (.XLS), EKSPOR PDF & CETAK LAPORAN
        // =========================================================================
        window.exportRenlakExcel = function() {
            const list = getFilteredRenlakActivities();
            const weeksPerMonth = parseInt(document.getElementById('renlakWeeksPerMonth')?.value || '4', 10);
            const yearBlocks = getVisibleTimelineMonths();

            let totalCols = 6;
            yearBlocks.forEach(yb => { totalCols += yb.months.length * weeksPerMonth * 2; });

            let html = `<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="UTF-8"><style>
                table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; }
                th, td { border: 1px solid #000000; padding: 4px 6px; vertical-align: middle; }
                .hdr { background: #d9d9d9; font-weight: bold; text-align: center; }
                .cat { background: #ffff00; font-weight: bold; }
                .cap { background: #d9ead3; font-weight: bold; text-align: center; }
                .plan { background: #fff2cc; text-align: center; }
                .real { background: #d9e1f2; text-align: center; }
            </style></head><body><table>`;

            html += `<tr><td style="background:#f4cccc;font-weight:bold;">TANGGAL</td><td style="font-weight:bold;">29-Oct-26</td><td colspan="${totalCols - 2}"><strong>PERENCANAAN LABORATORIUM (RENLAK) - DIGITLAB BBPOM DI BANDUNG</strong></td></tr>`;

            // Header rows
            let r1 = `<tr><th rowspan="3" class="hdr">No.</th><th rowspan="3" class="hdr">Nama Kegiatan/ Sasaran</th><th rowspan="3" class="hdr">Realisasi</th><th rowspan="3" class="hdr">Target</th><th rowspan="3" class="hdr">Satuan</th><th rowspan="3" class="hdr">% CAPAIAN</th>`;
            let r2 = `<tr>`;
            let r3 = `<tr>`;

            yearBlocks.forEach(yb => {
                r1 += `<th colspan="${yb.months.length * weeksPerMonth}" class="hdr">RENCANA ${yb.year}</th>`;
                yb.months.forEach(m => {
                    r2 += `<th colspan="${weeksPerMonth}" class="hdr">${RENLAK_MONTH_NAMES[m]}</th>`;
                    for (let w = 1; w <= weeksPerMonth; w++) r3 += `<th class="hdr">${w}</th>`;
                });
            });
            yearBlocks.forEach(yb => {
                r1 += `<th colspan="${yb.months.length * weeksPerMonth}" class="hdr">REALISASI ${yb.year}</th>`;
                yb.months.forEach(m => {
                    r2 += `<th colspan="${weeksPerMonth}" class="hdr">${RENLAK_MONTH_NAMES[m]}</th>`;
                    for (let w = 1; w <= weeksPerMonth; w++) r3 += `<th class="hdr">${w}</th>`;
                });
            });
            r1 += `</tr>`; r2 += `</tr>`; r3 += `</tr>`;
            html += r1 + r2 + r3;

            const categoryOrder = ['Perencanaan', 'Pengembangan Digitalisasi LAB', 'Monitoring dan Evaluasi'];
            const grouped = {};
            list.forEach(item => {
                const k = item.kategori || 'Lainnya';
                if (!grouped[k]) grouped[k] = [];
                grouped[k].push(item);
            });
            const orderedCategories = [
                ...categoryOrder.filter(k => grouped[k] && grouped[k].length > 0),
                ...Object.keys(grouped).filter(k => !categoryOrder.includes(k))
            ];

            orderedCategories.forEach(kat => {
                html += `<tr class="cat"><td></td><td>${escapeRenlakHtml(kat)}</td><td colspan="${totalCols - 2}"></td></tr>`;
                grouped[kat].forEach(act => {
                    const pct = calcRenlakPercent(act.realisasi, act.target);
                    html += `<tr>
                        <td style="text-align:center;font-weight:bold;">${act.nomor_urut}</td>
                        <td>${escapeRenlakHtml(act.nama_kegiatan)}</td>
                        <td style="text-align:center;">${act.realisasi}</td>
                        <td style="text-align:center;">${act.target}</td>
                        <td style="text-align:center;">${escapeRenlakHtml(act.satuan)}</td>
                        <td class="cap">${pct.toFixed(2)}%</td>`;

                    yearBlocks.forEach(yb => {
                        yb.months.forEach(m => {
                            for (let w = 1; w <= weeksPerMonth; w++) {
                                const v = act.jadwal_rencana?.[yb.year]?.[String(m)]?.[String(w)] ?? '';
                                html += `<td class="plan">${v !== '' && parseFloat(v) > 0 ? v : ''}</td>`;
                            }
                        });
                    });
                    yearBlocks.forEach(yb => {
                        yb.months.forEach(m => {
                            for (let w = 1; w <= weeksPerMonth; w++) {
                                const v = act.jadwal_realisasi?.[yb.year]?.[String(m)]?.[String(w)] ?? '';
                                html += `<td class="real">${v !== '' && parseFloat(v) > 0 ? v : ''}</td>`;
                            }
                        });
                    });
                    html += `</tr>`;
                });
            });

            html += `</table></body></html>`;
            const blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `RENLAK_DigitLab_BBPOM_Bandung_2026_2027.xls`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        };

        window.exportRenlakPdf = function() {
            if (!window.jspdf || !window.jspdf.jsPDF) {
                alert('Pustaka jsPDF belum siap. Silakan muat ulang halaman.');
                return;
            }
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');
            const list = getFilteredRenlakActivities();

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(13);
            doc.text('LAPORAN PERENCANAAN LABORATORIUM (RENLAK) - DIGITLAB BDG', 14, 14);

            doc.setFont('helvetica', 'normal');
            doc.setFontSize(9.5);
            doc.text('Balai Besar POM di Bandung | Periode Perencanaan: Tahun 2026 - 2027 | Tanggal Acuan: 29-Oct-26', 14, 20);

            const rows = [];
            list.forEach(act => {
                const pct = calcRenlakPercent(act.realisasi, act.target);
                rows.push([
                    act.nomor_urut,
                    act.kategori,
                    act.nama_kegiatan,
                    act.realisasi,
                    act.target,
                    act.satuan,
                    `${pct.toFixed(2)}%`,
                    act.rencana_kegiatan || '-',
                    summarizeRenlakScheduleText(act.jadwal_rencana),
                    act.status_kegiatan
                ]);
            });

            if (typeof doc.autoTable === 'function') {
                doc.autoTable({
                    startY: 25,
                    head: [['No.', 'Kategori', 'Nama Kegiatan / Sasaran', 'Realisasi', 'Target', 'Satuan', '% Capaian', 'Rencana Kegiatan', 'Jadwal Pelaksanaan', 'Status']],
                    body: rows,
                    styles: { fontSize: 8, cellPadding: 2.2 },
                    headStyles: { fillColor: [3, 66, 132], textColor: 255, fontStyle: 'bold' },
                    columnStyles: {
                        0: { halign: 'center', cellWidth: 10 },
                        3: { halign: 'center', cellWidth: 16 },
                        4: { halign: 'center', cellWidth: 15 },
                        5: { halign: 'center', cellWidth: 18 },
                        6: { halign: 'center', cellWidth: 20, fontStyle: 'bold' },
                        9: { halign: 'center', cellWidth: 22 }
                    }
                });
            }

            doc.save('Laporan_RENLAK_DigitLab_BBPOM_Bandung_2026_2027.pdf');
        };

        window.printRenlakReport = function() {
            const list = getFilteredRenlakActivities();
            let rowsHtml = '';
            list.forEach(act => {
                const pct = calcRenlakPercent(act.realisasi, act.target);
                rowsHtml += `<tr>
                    <td style="text-align:center;">${act.nomor_urut}</td>
                    <td>${escapeRenlakHtml(act.kategori)}</td>
                    <td><strong>${escapeRenlakHtml(act.nama_kegiatan)}</strong></td>
                    <td style="text-align:center;">${act.realisasi}</td>
                    <td style="text-align:center;">${act.target}</td>
                    <td style="text-align:center;">${escapeRenlakHtml(act.satuan)}</td>
                    <td style="text-align:center;background:#d9ead3;font-weight:bold;">${pct.toFixed(2)}%</td>
                    <td>${escapeRenlakHtml(act.rencana_kegiatan || '-')}</td>
                    <td>${escapeRenlakHtml(summarizeRenlakScheduleText(act.jadwal_rencana))}</td>
                    <td style="text-align:center;">${escapeRenlakHtml(act.status_kegiatan)}</td>
                </tr>`;
            });

            const w = window.open('', '_blank');
            if (!w) return;
            w.document.write(`<!DOCTYPE html><html><head><title>Cetak Laporan RENLAK - DigitLab BDG</title>
            <style>
                @page { size: A4 landscape; margin: 12mm; }
                body { font-family: Arial, sans-serif; font-size: 11px; color: #111; }
                h2 { margin: 0 0 4px 0; color: #034284; }
                p { margin: 0 0 12px 0; color: #444; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #333; padding: 6px 8px; vertical-align: middle; }
                th { background: #034284; color: #fff; text-align: center; }
            </style></head><body>
            <h2>PERENCANAAN LABORATORIUM (RENLAK) - DIGITLAB BBPOM DI BANDUNG</h2>
            <p>Periode Perencanaan: Tahun 2026 &ndash; 2027 | Tanggal Acuan: 29-Oct-26 | Dicetak oleh: ${escapeRenlakHtml(LOGGED_IN_USER_NAME)}</p>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kategori</th>
                        <th>Nama Kegiatan / Sasaran</th>
                        <th>Realisasi</th>
                        <th>Target</th>
                        <th>Satuan</th>
                        <th>% Capaian</th>
                        <th>Rencana Kegiatan</th>
                        <th>Jadwal Pelaksanaan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>${rowsHtml}</tbody>
            </table>
            <script>window.onload = function(){ window.print(); };<\/script>
            </body></html>`);
            w.document.close();
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize RENLAK module data immediately
            renderRenlakAll();
            // Initialize default HULS form values immediately
            populateHulsForm(HULS_DEFAULT_DOC);
            // Initialize Rekapitulasi Sampel Uji Chart (Exact BBPOM Spreadsheet Replica)
            const ctxKom = document.getElementById('chartDashboardKomoditi');
            if (ctxKom && window.Chart) {
                new Chart(ctxKom, {
                    type: 'bar',
                    data: {
                        labels: [
                            ['Jumlah Sampel', 'Sediaan Farmasi', '-Obat'],
                            ['Jumlah Sampel', 'Sediaan Farmasi', '-OBA'],
                            ['Jumlah Sampel', 'Sediaan Farmasi -SK'],
                            ['Jumlah Sampel', 'Sediaan Farmasi', '-Kosmetik'],
                            ['Jumlah Sampel', 'Sediaan Pangan', 'Olahan'],
                            ['Jumlah Sampel KLB']
                        ],
                        datasets: [
                            { label: 'TARGET', data: [null, null, null, null, null, null], backgroundColor: '#4a86e8', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'JAN',    data: [25, 22, 10, 34, 81, null],           backgroundColor: '#cc4125', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'FEB',    data: [41, 26, 7, 60, 54, null],            backgroundColor: '#f1c232', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'MAR',    data: [37, 28, 8, 60, 51, null],            backgroundColor: '#6aa84f', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'APR',    data: [36, 18, 4, 52, 17, null],            backgroundColor: '#e6693e', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'MEI',    data: [46, 49, 10, 70, 41, null],           backgroundColor: '#46bdc6', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'JUN',    data: [46, 37, 7, 64, 30, null],            backgroundColor: '#6d9eeb', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'JUL',    data: [43, 35, 5, 62, 30, null],            backgroundColor: '#c27ba0', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'AGS',    data: [43, 18, 6, 62, 55, null],            backgroundColor: '#e6c86e', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'SEPT',   data: [42, 32, 4, 61, 21, null],            backgroundColor: '#67b87c', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'OKT',    data: [null, null, null, null, null, null], backgroundColor: '#f69679', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'NOV',    data: [null, null, null, null, null, null], backgroundColor: '#8fd3d8', barPercentage: 0.9, categoryPercentage: 0.78 },
                            { label: 'DES',    data: [null, null, null, null, null, null], backgroundColor: '#a4c2f4', barPercentage: 0.9, categoryPercentage: 0.78 }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'right',
                                align: 'start',
                                labels: {
                                    boxWidth: 9,
                                    boxHeight: 9,
                                    padding: 6,
                                    color: '#333333',
                                    font: {
                                        family: 'Arial, sans-serif',
                                        size: 9
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                min: 0,
                                max: 100,
                                ticks: {
                                    stepSize: 25,
                                    color: '#333333',
                                    font: { family: 'Arial, sans-serif', size: 9 }
                                },
                                grid: {
                                    color: '#d9d9d9',
                                    drawBorder: false
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#222222',
                                    maxRotation: 0,
                                    minRotation: 0,
                                    font: { family: 'Arial, sans-serif', size: 8.5 }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Resize IoT charts when room tab changes
            var triggerTabList = [].slice.call(document.querySelectorAll('button[data-bs-toggle="pill"]'));
            triggerTabList.forEach(function (triggerEl) {
                triggerEl.addEventListener('shown.bs.tab', function () {
                    window.dispatchEvent(new Event('resize'));
                });
            });
        });
    </script>
    <script charset="utf-8" src="app.js?v=38"></script>
</body>
</html>
