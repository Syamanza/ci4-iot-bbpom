<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="id" class="light-style layout-compact" dir="ltr" data-theme="theme-bpom" data-style="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>Digitalisasi Lab | BBPOM di Bandung</title>
    
    <link rel="icon" type="image/png" href="<?= base_url('logo-digitalisasi-lab.png') ?>">

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
                <img src="<?= base_url('logo-digitalisasi-lab.png') ?>" alt="Logo Digitalisasi Lab">
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
                    <span id="topbarActiveTitle">Digitalisasi Lab Terintegrasi</span>
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
                 SECTION 1: MENU DASHBOARD UTAMA (DATA DUMMY SESUAI REQUEST)
                 ========================================================== -->
            <div id="section-dashboard">

                <!-- Greeting Banner -->
                <div class="greeting-strip">
                    <div>
                        <span class="fs-5 me-1">👋</span>
                        <strong style="color: #0f172a; font-size: 1.02rem;">Sampurasun, Akang/Teteh!</strong>
                        <span class="text-muted mx-1">|</span>
                        <span class="text-secondary fw-medium">Laboratorium Regional UPT BBPOM di Bandung (LP-173-IDN)</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-label-success"><i class="ti ti-circle-filled me-1" style="font-size: 7px;"></i>Sistem Digitalisasi Lab Online</span>
                    </div>
                </div>

                <!-- 1, 2, 3: KPI CARDS ROW (Jumlah Sampel Update, Jumlah Selesai Uji, Indeks Maturitas Digitalisasi Lab) -->
                <div class="row g-3 mb-4">
                    <!-- 1. Jumlah Sampel Update -->
                    <div class="col-xl-3 col-md-6">
                        <div class="kpi-stat-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="kpi-icon-box" style="background: #0284c7; color: #ffffff;">
                                    <i class="ti ti-hierarchy-2"></i>
                                </div>
                                <div>
                                    <div class="kpi-label">1. JUMLAH SAMPEL UPDATE</div>
                                    <div class="kpi-number">1,240</div>
                                    <div class="kpi-sub text-success">
                                        <i class="ti ti-trending-up"></i> +12% dari bln lalu (48 hari ini)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Jumlah Selesai Uji -->
                    <div class="col-xl-3 col-md-6">
                        <div class="kpi-stat-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="kpi-icon-box" style="background: #10b981; color: #ffffff;">
                                    <i class="ti ti-circle-check"></i>
                                </div>
                                <div>
                                    <div class="kpi-label">2. JUMLAH SELESAI UJI</div>
                                    <div class="kpi-number">1,085</div>
                                    <div class="kpi-sub" style="color: #059669;">
                                        <i class="ti ti-checks"></i> 87.5% Selesai (Rata-rata SLA 3 hari)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Indeks Maturitas Digitalisasi Lab -->
                    <div class="col-xl-3 col-md-6">
                        <div class="kpi-stat-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="kpi-icon-box" style="background: #f59e0b; color: #ffffff;">
                                    <i class="ti ti-award"></i>
                                </div>
                                <div>
                                    <div class="kpi-label">3. INDEKS MATURITAS DIGITALISASI LAB</div>
                                    <div class="kpi-number">4.25 <small class="fs-6 fw-semibold text-muted">/ 5.0</small></div>
                                    <div class="kpi-sub" style="color: #d97706;">
                                        <i class="ti ti-chart-bar"></i> Skor 85% (Level: Terintegrasi)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tambahan Ringkasan Antrean / Proses Uji -->
                    <div class="col-xl-3 col-md-6">
                        <div class="kpi-stat-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="kpi-icon-box" style="background: #ef4444; color: #ffffff;">
                                    <i class="ti ti-clock-exclamation"></i>
                                </div>
                                <div>
                                    <div class="kpi-label">DALAM PROSES &amp; VERIFIKASI</div>
                                    <div class="kpi-number">155</div>
                                    <div class="kpi-sub text-danger">
                                        <i class="ti ti-Urgent"></i> 143 Sesuai SLA &bull; 12 Prioritas Segera
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CHARTS ROW: TREN SAMPEL UPDATE vs SELESAI UJI & DETAIL INDEKS MATURITAS DIGITALISASI LAB -->
                <div class="row g-3 mb-4">
                    <!-- Left: Grafik Tren Sampel Update & Selesai Uji per Komoditi -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #0f172a;">
                                            <i class="ti ti-chart-bar text-primary me-1"></i> TREN JUMLAH SAMPEL UPDATE &amp; SELESAI UJI (2026)
                                        </h6>
                                        <small class="text-muted">Perbandingan sampel masuk/update dan sampel selesai uji per komoditi</small>
                                    </div>
                                    <span class="badge bg-label-primary fw-bold">Target SLA: 95%</span>
                                </div>

                                <div class="d-flex flex-wrap gap-3 mb-3 small fw-semibold">
                                    <span><i class="ti ti-circle-filled me-1" style="color: #0284c7;"></i>Pangan</span>
                                    <span><i class="ti ti-circle-filled me-1" style="color: #0d9488;"></i>Obat/NAPZA</span>
                                    <span><i class="ti ti-circle-filled me-1" style="color: #eab308;"></i>Kosmetik</span>
                                    <span><i class="ti ti-circle-filled me-1" style="color: #22c55e;"></i>OT &amp; Suplemen</span>
                                    <span><i class="ti ti-circle-filled me-1" style="color: #8b5cf6;"></i>Mikrobiologi</span>
                                </div>

                                <div style="height: 255px;">
                                    <canvas id="chartDashboardKomoditi"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Detail Indeks Maturitas Digitalisasi Lab -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #0f172a;">
                                            <i class="ti ti-gauge text-warning me-1"></i> INDEKS MATURITAS DIGITALISASI LAB.
                                        </h6>
                                        <small class="text-muted">Capaian transformasi digital laboratorium BBPOM</small>
                                    </div>
                                    <span class="badge bg-label-success fw-bold fs-6">4.25 / 5.0 (85%)</span>
                                </div>

                                <!-- Pilar Maturitas Progress Bars -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>1. Akomodasi &amp; Lingkungan (Monitoring IoT)</span>
                                        <span class="text-success fw-bold">92% (Level 4.6)</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 6px;">
                                        <div class="progress-bar bg-success" style="width: 92%;"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>2. Pelaksanaan Pengujian &amp; LIMS Terintegrasi</span>
                                        <span class="text-primary fw-bold">88% (Level 4.4)</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 6px;">
                                        <div class="progress-bar bg-primary" style="width: 88%;"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>3. Keamanan &amp; Integritas Data Laboratorium</span>
                                        <span class="text-info fw-bold">85% (Level 4.2)</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 6px;">
                                        <div class="progress-bar bg-info" style="width: 85%;"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>4. Pengelolaan Instrumen, Inventori &amp; Kalibrasi</span>
                                        <span class="text-warning fw-bold">82% (Level 4.1)</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 6px;">
                                        <div class="progress-bar bg-warning" style="width: 82%;"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>5. Metode, Validasi, Mutu &amp; Kompetensi SDM</span>
                                        <span style="color: #8b5cf6;" class="fw-bold">78% (Level 3.9)</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 6px;">
                                        <div class="progress-bar" style="width: 78%; background-color: #8b5cf6;"></div>
                                    </div>
                                </div>

                                <div class="p-2 rounded border bg-light d-flex align-items-center justify-content-between mt-3">
                                    <span class="small text-muted"><i class="ti ti-info-circle text-primary me-1"></i>Status Maturitas: <strong>Digital Lab Terintegrasi (ISO/IEC 17025)</strong></span>
                                    <span class="badge bg-primary">Target 2026: 4.50</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. TIMELINE PENYELESAIAN UJI SAMPLE -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                            <div>
                                <h6 class="fw-bold mb-1" style="color: #0f172a;">
                                    <i class="ti ti-timeline-event text-primary me-1"></i> 4. TIMELINE PENYELESAIAN UJI SAMPLE (INTEGRASI BARCODE / QR LIMS)
                                </h6>
                                <small class="text-muted">Alur tahapan penyelesaian pengujian sampel secara real-time dari registrasi hingga penerbitan LHU</small>
                            </div>
                            <span class="badge bg-label-info">Update Otomatis (Dummy Data)</span>
                        </div>

                        <!-- Stepper Ringkasan Tahapan -->
                        <div class="timeline-stepper">
                            <div class="timeline-step-card completed">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-success">Tahap 1</span>
                                    <i class="ti ti-qrcode text-success fs-5"></i>
                                </div>
                                <div class="fw-bold small text-dark">Registrasi &amp; Kode Sampel</div>
                                <div class="text-muted" style="font-size: 0.75rem;">1,240 Sampel Terdaftar</div>
                            </div>

                            <div class="timeline-step-card completed">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-success">Tahap 2</span>
                                    <i class="ti ti-arrows-split text-success fs-5"></i>
                                </div>
                                <div class="fw-bold small text-dark">Distribusi &amp; Preparasi</div>
                                <div class="text-muted" style="font-size: 0.75rem;">42 Sampel Sedang Preparasi</div>
                            </div>

                            <div class="timeline-step-card active-step">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-primary">Tahap 3</span>
                                    <i class="ti ti-microscope text-primary fs-5"></i>
                                </div>
                                <div class="fw-bold small text-dark">Pengujian Instrumen Lab</div>
                                <div class="text-muted" style="font-size: 0.75rem;">78 Sampel Diuji Analis</div>
                            </div>

                            <div class="timeline-step-card active-step">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-warning text-dark">Tahap 4</span>
                                    <i class="ti ti-shield-check text-warning fs-5"></i>
                                </div>
                                <div class="fw-bold small text-dark">Verifikasi &amp; Kendali Mutu</div>
                                <div class="text-muted" style="font-size: 0.75rem;">35 Menunggu Verifikasi</div>
                            </div>

                            <div class="timeline-step-card completed">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-success">Tahap 5</span>
                                    <i class="ti ti-file-certificate text-success fs-5"></i>
                                </div>
                                <div class="fw-bold small text-dark">Selesai Uji (Terbit LHU)</div>
                                <div class="text-muted" style="font-size: 0.75rem;">1,085 Sampel Selesai Uji</div>
                            </div>
                        </div>

                        <!-- Tabel Timeline Penyelesaian Uji Sample -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Sampel</th>
                                        <th>Komoditi</th>
                                        <th>Parameter Uji</th>
                                        <th>Lokasi Lab</th>
                                        <th>Timeline Tahapan Penyelesaian</th>
                                        <th>Batas SLA</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-primary">BDG-98211</td>
                                        <td>Obat &amp; NAPZA</td>
                                        <td>Identifikasi Spektrofotometri</td>
                                        <td>Lab Kimia</td>
                                        <td style="min-width: 210px;">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="fw-semibold">Tahap 4/5: Verifikasi Penyelia</span>
                                                <span class="text-primary fw-bold">85%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-primary" style="width: 85%;"></div>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Masuk: 26 Okt &rarr; Est. Selesai: 28 Okt 16:00</small>
                                        </td>
                                        <td><span class="fw-semibold text-danger">Sisa 2 Jam</span></td>
                                        <td><span class="badge bg-label-danger"><i class="ti ti-circle-filled me-1" style="font-size: 7px;"></i>Urgent SLA</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-primary">BDG-98212</td>
                                        <td>Pangan Olahan</td>
                                        <td>Cemaran Mikrobiologi (ALT)</td>
                                        <td>Lab Mikro</td>
                                        <td>
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="fw-semibold">Tahap 3/5: Inkubasi &amp; Uji Lab</span>
                                                <span class="text-warning fw-bold">60%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-warning" style="width: 60%;"></div>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Masuk: 26 Okt &rarr; Est. Selesai: 29 Okt 14:00</small>
                                        </td>
                                        <td><span class="fw-semibold">Sisa 1 Hari</span></td>
                                        <td><span class="badge bg-label-warning"><i class="ti ti-circle-filled me-1" style="font-size: 7px;"></i>Proses Uji</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-primary">BDG-98213</td>
                                        <td>Kosmetik</td>
                                        <td>Logam Berat (Hg, Pb, Cd - AAS)</td>
                                        <td>Lab Kimia</td>
                                        <td>
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="fw-semibold">Tahap 5/5: LHU Terbit (TTE)</span>
                                                <span class="text-success fw-bold">100%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 100%;"></div>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Masuk: 25 Okt &rarr; Selesai: 28 Okt 10:15</small>
                                        </td>
                                        <td><span class="text-success fw-semibold">Tepat Waktu</span></td>
                                        <td><span class="badge bg-label-success"><i class="ti ti-circle-check me-1"></i>Selesai Uji</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-primary">BDG-98214</td>
                                        <td>Obat Tradisional</td>
                                        <td>Uji Bahan Kimia Obat (KLT/HPLC)</td>
                                        <td>Lab OT &amp; Kos</td>
                                        <td>
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="fw-semibold">Tahap 3/5: Analisis Instrumen HPLC</span>
                                                <span class="text-info fw-bold">70%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-info" style="width: 70%;"></div>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Masuk: 27 Okt &rarr; Est. Selesai: 28 Okt 19:00</small>
                                        </td>
                                        <td><span class="fw-semibold text-warning">Sisa 5 Jam</span></td>
                                        <td><span class="badge bg-label-info"><i class="ti ti-circle-filled me-1" style="font-size: 7px;"></i>On Track</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-primary">BDG-98215</td>
                                        <td>Pangan Jajanan</td>
                                        <td>Uji Formalin, Boraks &amp; Rhodamin B</td>
                                        <td>Lab Kimia</td>
                                        <td>
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="fw-semibold">Tahap 5/5: LHU Terbit (TTE)</span>
                                                <span class="text-success fw-bold">100%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 100%;"></div>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Masuk: 25 Okt &rarr; Selesai: 28 Okt 09:30</small>
                                        </td>
                                        <td><span class="text-success fw-semibold">Tepat Waktu</span></td>
                                        <td><span class="badge bg-label-success"><i class="ti ti-circle-check me-1"></i>Selesai Uji</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                        <h4 class="fw-bold mb-0" style="color: #034284;"><span class="text-muted fw-light">Digitalisasi Lab /</span> Akomodasi dan Lingkungan</h4>
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
                            Modul ini sedang dalam tahap pengembangan dan integrasi pada ekosistem <strong>Digitalisasi Lab BBPOM di Bandung</strong>.
                        </p>

                        <div class="mx-auto mb-4 p-3 rounded border bg-light" style="max-width: 480px;">
                            <div class="d-flex justify-content-between small fw-bold mb-1">
                                <span class="text-secondary">Status Integrasi Sistem Digitalisasi Lab</span>
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
            dashboard:   { title: 'Dashboard Digitalisasi Lab', icon: 'ti-home', desc: '' },
            perencanaan: { title: 'Perencanaan Laboratorium', icon: 'ti-calendar-stats', desc: 'Modul Perencanaan Sampling, Jadwal Pengujian Tahunan, dan Alokasi Anggaran Laboratorium sedang dalam tahap pengembangan.' },
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
            const secAko = document.getElementById('section-akomodasi');
            const secDev = document.getElementById('section-under-dev');
            const secHuls = document.getElementById('section-huls');
            const subNavBar = document.getElementById('pengujianSubNavBar');

            if (secDash) secDash.style.display = 'none';
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
            const secAko = document.getElementById('section-akomodasi');
            const secDev = document.getElementById('section-under-dev');
            const secHuls = document.getElementById('section-huls');
            const subNavBar = document.getElementById('pengujianSubNavBar');
            const topTitle = document.getElementById('topbarActiveTitle');
            const devBadge = document.getElementById('underDevBadge');
            const meta = MENU_META[menuKey] || { title: 'Digitalisasi Lab', icon: 'ti-settings-automation', desc: 'Modul sedang dalam pengembangan.' };

            if (subNavBar) subNavBar.style.display = 'none';
            if (secHuls) secHuls.style.display = 'none';
            if (devBadge) devBadge.innerHTML = '<i class="ti ti-Barrier-block me-1"></i> Modul Dalam Pengembangan';
            if (topTitle) {
                topTitle.textContent = meta.title;
                topTitle.title = meta.title;
            }

            if (menuKey === 'dashboard') {
                if (secDash) secDash.style.display = 'block';
                if (secAko) secAko.style.display = 'none';
                if (secDev) secDev.style.display = 'none';
            } else if (menuKey === 'akomodasi') {
                if (secDash) secDash.style.display = 'none';
                if (secAko) secAko.style.display = 'block';
                if (secDev) secDev.style.display = 'none';
                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));
                }, 60);
            } else {
                if (secDash) secDash.style.display = 'none';
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

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize default HULS form values immediately
            populateHulsForm(HULS_DEFAULT_DOC);
            // Initialize Dummy Chart for Dashboard Utama
            const ctxKom = document.getElementById('chartDashboardKomoditi');
            if (ctxKom && window.Chart) {
                new Chart(ctxKom, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'],
                        datasets: [
                            { label: 'Pangan', data: [55, 62, 60, 68, 61, 70, 74, 71, 69, 73], backgroundColor: '#0284c7', borderRadius: 3 },
                            { label: 'Obat/NAPZA', data: [38, 37, 41, 42, 40, 48, 50, 47, 49, 52], backgroundColor: '#0d9488', borderRadius: 3 },
                            { label: 'Kosmetik', data: [24, 28, 33, 31, 39, 36, 54, 45, 44, 48], backgroundColor: '#eab308', borderRadius: 3 },
                            { label: 'OT & Suplemen', data: [19, 21, 26, 28, 27, 29, 32, 30, 31, 34], backgroundColor: '#22c55e', borderRadius: 3 },
                            { label: 'Mikrobiologi', data: [15, 14, 30, 25, 29, 26, 20, 19, 24, 31], backgroundColor: '#8b5cf6', borderRadius: 3 }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9' }
                            },
                            x: {
                                grid: { display: false }
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
    <script charset="utf-8" src="app.js?v=37"></script>
</body>
</html>
