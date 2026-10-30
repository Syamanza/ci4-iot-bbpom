<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | DigitLab BDG — BBPOM di Bandung</title>
    <link rel="icon" type="image/png" href="<?= base_url('favicon-digitlab-bdg.png') ?>"><link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>"><link rel="apple-touch-icon" href="<?= base_url('favicon-digitlab-bdg.png') ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: radial-gradient(circle at 12% 15%, #dceaf9 0%, #e9f2fb 38%, #f4f8fc 72%, #e0f2ec 100%);
            position: relative;
            overflow-x: hidden;
        }
        /* Subtle decorative corner shapes matching reference background */
        body::before {
            content: '';
            position: fixed;
            bottom: -120px;
            left: -120px;
            width: 460px;
            height: 460px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(0, 140, 85, 0.22), rgba(3, 66, 132, 0.18));
            z-index: 0;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            top: -140px;
            right: -120px;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(3, 66, 132, 0.14), rgba(0, 140, 85, 0.12));
            z-index: 0;
            pointer-events: none;
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1040px;
            min-height: 610px;
            background: #ffffff;
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(13, 38, 76, 0.14), 0 4px 16px rgba(13, 38, 76, 0.06);
            display: grid;
            grid-template-columns: 1.24fr 1fr;
            overflow: hidden;
        }

        /* Left Hero Visual Panel */
        .login-hero {
            position: relative;
            background: #e8f1fa;
            overflow: hidden;
            display: flex;
            align-items: stretch;
        }
        .login-hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Right Form Panel */
        .login-form-panel {
            background: #ffffff;
            padding: 44px 48px 34px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-box {
            text-align: center;
            margin-bottom: 26px;
        }
        .brand-logo {
            height: 118px;
            width: auto;
            object-fit: contain;
            margin-bottom: 6px;
        }
        .brand-subtitle {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: #7c8da6;
            font-size: 0.76rem;
            font-weight: 600;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .brand-subtitle::before,
        .brand-subtitle::after {
            content: '';
            height: 1px;
            width: 44px;
            background: #dce4ee;
        }

        .alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 16px;
        }
        .input-group-custom .input-icon-left {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.2rem;
            pointer-events: none;
        }
        .input-group-custom input {
            width: 100%;
            height: 50px;
            padding: 0 44px 0 48px;
            border: 1.5px solid #dce4ee;
            border-radius: 11px;
            font-size: 0.94rem;
            font-family: inherit;
            color: #1e293b;
            background: #ffffff;
            transition: all 0.2s ease;
            outline: none;
        }
        .input-group-custom input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        .input-group-custom input:focus {
            border-color: #03498e;
            box-shadow: 0 0 0 3.5px rgba(3, 73, 142, 0.11);
        }
        .input-group-custom .btn-toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.15rem;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .input-group-custom .btn-toggle-pass:hover {
            color: #03498e;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 6px 0 24px;
            font-size: 0.85rem;
        }
        .remember-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }
        .remember-label input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: #03498e;
            border-radius: 4px;
            cursor: pointer;
        }
        .forgot-link {
            color: #03498e;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s;
        }
        .forgot-link:hover {
            color: #023366;
            text-decoration: underline;
        }

        .btn-login-submit {
            width: 100%;
            height: 52px;
            background: linear-gradient(90deg, #034284 0%, #0554a5 100%);
            color: #ffffff;
            border: none;
            border-radius: 11px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(3, 66, 132, 0.24);
            transition: all 0.2s ease;
        }
        .btn-login-submit:hover {
            background: linear-gradient(90deg, #023366 0%, #034284 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(3, 66, 132, 0.3);
        }
        .btn-login-submit:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 32px;
        }
        .login-footer p {
            color: #7c8da6;
            font-size: 0.78rem;
            line-height: 1.5;
            font-weight: 500;
        }
        .footer-accent-bar {
            display: flex;
            justify-content: center;
            gap: 4px;
            margin-top: 12px;
        }
        .footer-accent-bar .bar-green {
            width: 44px;
            height: 3.5px;
            background: #008c55;
            border-radius: 4px;
        }
        .footer-accent-bar .bar-blue {
            width: 44px;
            height: 3.5px;
            background: #034284;
            border-radius: 4px;
        }

        @media (max-width: 900px) {
            .login-card {
                grid-template-columns: 1fr;
                max-width: 480px;
            }
            .login-hero {
                display: none;
            }
            .login-form-panel {
                padding: 36px 28px 28px;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Left Hero Panel (Exact Match to Design) -->
        <div class="login-hero">
            <img src="<?= base_url('login-hero-left.png') ?>?v=2" alt="Selamat Datang di DigitLab BDG - BBPOM di Bandung">
        </div>

        <!-- Right Login Form Panel (Without SSO BPOM as requested) -->
        <div class="login-form-panel">
            <div></div>

            <div>
                <div class="brand-box">
                    <img src="<?= base_url('logo-digitlab-bdg.png') ?>" alt="Logo DigitLab BDG" class="brand-logo">
                    <div class="brand-subtitle">BBPOM DI BANDUNG</div>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert-error">
                        <i class="ti ti-alert-circle"></i>
                        <span><?= esc(session()->getFlashdata('error')) ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('login') ?>" method="POST">
                    <div class="input-group-custom">
                        <i class="ti ti-user input-icon-left"></i>
                        <input type="text" name="username" id="inputUsername" placeholder="NIP / Username" required autocomplete="username" value="">
                    </div>

                    <div class="input-group-custom">
                        <i class="ti ti-lock input-icon-left"></i>
                        <input type="password" name="password" id="inputPassword" placeholder="Password" required autocomplete="current-password" value="">
                        <button type="button" class="btn-toggle-pass" onclick="togglePasswordVisibility()" title="Tampilkan / Sembunyikan Password">
                            <i class="ti ti-eye" id="eyeIcon"></i>
                        </button>
                    </div>

                    <div class="form-options">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" checked>
                            <span>Ingat saya</span>
                        </label>
                        <a href="javascript:void(0)" class="forgot-link" onclick="alert('Silakan hubungi Administrator DigitLab BDG - BBPOM di Bandung untuk reset password.')">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn-login-submit">
                        <span>Masuk</span>
                        <i class="ti ti-arrow-right"></i>
                    </button>
                </form>
            </div>

            <div class="login-footer">
                <p>&copy; 2026 BBPOM di Bandung<br>Badan Pengawas Obat dan Makanan</p>
                <div class="footer-accent-bar">
                    <span class="bar-green"></span>
                    <span class="bar-blue"></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('inputPassword');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.className = 'ti ti-eye-off';
            } else {
                passInput.type = 'password';
                eyeIcon.className = 'ti ti-eye';
            }
        }
    </script>
</body>
</html>
