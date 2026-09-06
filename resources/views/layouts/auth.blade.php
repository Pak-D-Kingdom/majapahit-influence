<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk') — Majapahit Influence</title>

    <meta name="description" content="Portal Akses Majapahit Influence Management Platform">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --orange: #d57028;
            --red: #d5282d;
            --yellow: #fec200;
            --dark: #1b0c08;
            --dark-card: #24110b;
            --dark-border: #442217;
            --brown: #b86021;
            --white: #ffffff;
            --off-white: #fff9f4;
            --muted: #a68c85;
            --font-heading: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'DM Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background: linear-gradient(135deg, #120704 0%, #1f0d08 50%, #100604 100%);
            color: var(--off-white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient glowing background shapes */
        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
            opacity: 0.25;
            z-index: 0;
        }
        .glow-1 {
            width: 450px;
            height: 450px;
            background: var(--orange);
            top: -100px;
            left: -100px;
        }
        .glow-2 {
            width: 400px;
            height: 400px;
            background: var(--yellow);
            bottom: -80px;
            right: -80px;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .auth-brand a {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--white);
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--orange), var(--red));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--white);
            box-shadow: 0 4px 16px rgba(213, 112, 40, 0.4);
        }

        .brand-text h1 {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(90deg, #ffffff, #fec200);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: left;
        }

        .brand-text span {
            display: block;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--orange);
            font-weight: 600;
            text-align: left;
        }

        .auth-card {
            background: rgba(36, 17, 11, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .auth-header {
            margin-bottom: 24px;
        }

        .auth-header h2 {
            font-family: var(--font-heading);
            font-size: 24px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 6px;
        }

        .auth-header p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }

        /* Alerts */
        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            line-height: 1.4;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }

        .alert-danger {
            background: rgba(220, 38, 38, 0.12);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #fca5a5;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #ebdcd6;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--muted);
            font-size: 16px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-control {
            width: 100%;
            background: rgba(20, 9, 6, 0.7);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            padding: 13px 14px 13px 42px;
            font-family: inherit;
            font-size: 14.5px;
            color: var(--white);
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control.no-icon {
            padding-left: 14px;
        }

        .form-control:focus {
            border-color: var(--orange);
            background: rgba(25, 11, 7, 0.9);
            box-shadow: 0 0 0 3px rgba(213, 112, 40, 0.25);
        }

        .form-control:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--orange);
        }

        .form-control.is-invalid {
            border-color: var(--red);
        }

        .invalid-feedback {
            color: #f87171;
            font-size: 12.5px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .password-toggle-btn {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 17px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .password-toggle-btn:hover {
            color: var(--yellow);
        }

        /* Checkbox & Options */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--muted);
            user-select: none;
        }

        .custom-checkbox input[type="checkbox"] {
            accent-color: var(--orange);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .auth-link {
            color: var(--orange);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: var(--yellow);
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--orange) 0%, var(--brown) 100%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: var(--white);
            padding: 14px;
            font-family: var(--font-heading);
            font-size: 15px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(213, 112, 40, 0.35);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #e47d33 0%, #c46825 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(213, 112, 40, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--orange);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover {
            color: var(--yellow);
            text-decoration: underline;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="auth-wrapper">
        <div class="auth-brand">
            <a href="{{ url('/') }}">
                <div class="brand-icon">
                    <i class="bi bi-shield-shaded"></i>
                </div>
                <div class="brand-text">
                    <h1>MAJAPAHIT</h1>
                    <span>Influence Platform</span>
                </div>
            </a>
        </div>

        <div class="auth-card">
            @yield('content')
        </div>

        <div class="auth-footer">
            <p>&copy; {{ date('Y') }} Majapahit Influence. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
