<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Đăng nhập vào hệ thống E-learning thông minh với trợ lý AI">
    <title>Đăng nhập | EduMind AI</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        /* ───────────────────────────────────────────────
           CSS Custom Properties
        ─────────────────────────────────────────────── */
        :root {
            --brand:           #4f46e5;
            --brand-hover:     #4338ca;
            --brand-light:     #eef2ff;
            --brand-50:        rgba(79, 70, 229, .08);
            --accent-amber:    #f59e0b;
            --accent-violet:   #8b5cf6;
            --surface:         #ffffff;
            --bg:              #f8fafc;
            --text-primary:    #1e293b;
            --text-secondary:  #64748b;
            --text-muted:      #94a3b8;
            --border:          #e2e8f0;
            --danger:          #ef4444;
            --success:         #10b981;
            --radius-sm:       .5rem;
            --radius-md:       .75rem;
            --radius-lg:       1rem;
            --radius-xl:       1.25rem;
            --shadow-sm:       0 1px 2px rgba(0,0,0,.05);
            --shadow-md:       0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -2px rgba(0,0,0,.05);
            --shadow-lg:       0 10px 25px -3px rgba(0,0,0,.08), 0 4px 6px -4px rgba(0,0,0,.04);
            --shadow-xl:       0 20px 50px -12px rgba(0,0,0,.12);
            --transition:      all .2s cubic-bezier(.4,0,.2,1);
        }

        /* ───────────────────────────────────────────────
           Global Reset & Base
        ─────────────────────────────────────────────── */
        *,
        *::before,
        *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            margin: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ───────────────────────────────────────────────
           Subtle background pattern
        ─────────────────────────────────────────────── */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(79,70,229,.06) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 80%, rgba(139,92,246,.05) 0%, transparent 60%),
                var(--bg);
        }

        .login-page::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 1px 1px, rgba(79,70,229,.04) 1px, transparent 0);
            background-size: 32px 32px;
            pointer-events: none;
        }

        /* ───────────────────────────────────────────────
           Main Card Container
        ─────────────────────────────────────────────── */
        .login-container {
            width: 100%;
            max-width: 960px;
            background: var(--surface);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
            z-index: 1;
            border: 1px solid var(--border);
        }

        /* ───────────────────────────────────────────────
           LEFT PANEL – Branding & AI Highlights
        ─────────────────────────────────────────────── */
        .brand-panel {
            background: linear-gradient(160deg, #4f46e5 0%, #6366f1 40%, #8b5cf6 100%);
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            color: #fff;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -30%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(245,158,11,.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .brand-logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,.15);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            border: 1px solid rgba(255,255,255,.2);
        }

        .brand-logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .brand-logo-text span {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-tagline {
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
            letter-spacing: -.01em;
        }

        .brand-subtitle {
            font-size: .95rem;
            color: rgba(255,255,255,.75);
            line-height: 1.6;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }

        /* AI Badge */
        .ai-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: linear-gradient(135deg, var(--accent-amber), var(--accent-violet));
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            padding: .3rem .7rem;
            border-radius: 100px;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 1;
            box-shadow: 0 2px 8px rgba(245,158,11,.3);
        }

        .ai-badge i { font-size: .65rem; }

        /* Feature highlights */
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: .9rem;
            position: relative;
            z-index: 1;
        }

        .feature-list li {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            font-size: .875rem;
            color: rgba(255,255,255,.9);
            line-height: 1.5;
        }

        .feature-icon {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,.12);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            margin-top: .1rem;
            border: 1px solid rgba(255,255,255,.1);
        }

        .feature-text strong {
            display: block;
            font-weight: 600;
            margin-bottom: .1rem;
        }

        .feature-text small {
            color: rgba(255,255,255,.6);
            font-size: .78rem;
        }

        /* ───────────────────────────────────────────────
           RIGHT PANEL – Login Form
        ─────────────────────────────────────────────── */
        .form-panel {
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: .35rem;
            letter-spacing: -.01em;
        }

        .form-header p {
            color: var(--text-secondary);
            font-size: .875rem;
            margin: 0;
        }

        /* Session Alerts */
        .alert-styled {
            border: none;
            border-radius: var(--radius-sm);
            font-size: .85rem;
            padding: .75rem 1rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-bottom: 1.25rem;
            font-weight: 500;
        }

        .alert-styled.alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 3px solid var(--danger);
        }

        .alert-styled.alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 3px solid var(--success);
        }

        .alert-styled i { font-size: .9rem; flex-shrink: 0; }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: .4rem;
            letter-spacing: .01em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: .85rem;
            transition: var(--transition);
            pointer-events: none;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: .7rem .9rem .7rem 2.6rem;
            font-size: .875rem;
            font-family: inherit;
            color: var(--text-primary);
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            transition: var(--transition);
            outline: none;
        }

        .form-input::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        .form-input:hover {
            border-color: #cbd5e1;
        }

        .form-input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px var(--brand-50);
        }

        .form-input:focus ~ .input-icon {
            color: var(--brand);
        }

        .form-input.is-invalid {
            border-color: var(--danger);
        }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,.1);
        }

        .form-input.is-invalid ~ .input-icon {
            color: var(--danger);
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: .6rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: .3rem;
            border-radius: var(--radius-sm);
            font-size: .85rem;
            transition: var(--transition);
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: var(--text-secondary);
            background: var(--brand-50);
        }

        .has-toggle .form-input {
            padding-right: 2.8rem;
        }

        /* Validation message */
        .validation-msg {
            display: block;
            font-size: .78rem;
            color: var(--danger);
            margin-top: .35rem;
            font-weight: 500;
            padding-left: .1rem;
        }

        /* Remember & Forgot */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
        }

        .remember-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--brand);
            border-radius: 3px;
            cursor: pointer;
            margin: 0;
        }

        .remember-check span {
            font-size: .82rem;
            color: var(--text-secondary);
            user-select: none;
        }

        .forgot-link {
            font-size: .82rem;
            font-weight: 500;
            color: var(--brand);
            text-decoration: none;
            transition: var(--transition);
        }

        .forgot-link:hover {
            color: var(--brand-hover);
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: .78rem 1.5rem;
            font-size: .9rem;
            font-weight: 600;
            font-family: inherit;
            color: #fff;
            background: linear-gradient(135deg, var(--brand) 0%, #6366f1 100%);
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            letter-spacing: .01em;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,.1) 100%);
            opacity: 0;
            transition: var(--transition);
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79,70,229,.35);
        }

        .btn-submit:hover::before { opacity: 1; }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(79,70,229,.25);
        }

        .btn-submit i { font-size: .85rem; }

        /* Divider */
        .form-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            color: var(--text-muted);
            font-size: .78rem;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Register link */
        .register-prompt {
            text-align: center;
            font-size: .85rem;
            color: var(--text-secondary);
            margin-top: .25rem;
        }

        .register-prompt a {
            color: var(--brand);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .register-prompt a:hover {
            color: var(--brand-hover);
            text-decoration: underline;
        }

        /* Footer note */
        .form-footer-note {
            text-align: center;
            font-size: .72rem;
            color: var(--text-muted);
            margin-top: 1.5rem;
            line-height: 1.5;
        }

        .form-footer-note i { margin-right: .2rem; }

        /* ───────────────────────────────────────────────
           Responsive
        ─────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 440px;
            }

            .brand-panel {
                padding: 2rem 2rem 1.75rem;
            }

            .brand-tagline {
                font-size: 1.3rem;
            }

            .brand-subtitle {
                margin-bottom: 1.5rem;
                font-size: .85rem;
            }

            .feature-list { gap: .65rem; }

            .form-panel {
                padding: 2rem 2rem 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .login-page { padding: 1rem; }

            .login-container {
                border-radius: var(--radius-lg);
            }

            .brand-panel,
            .form-panel {
                padding: 1.5rem;
            }

            .brand-tagline { font-size: 1.15rem; }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* ───────────────────────────────────────────────
           Subtle entrance animation
        ─────────────────────────────────────────────── */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container {
            animation: slideUp .5s cubic-bezier(.4,0,.2,1) both;
        }
    </style>
</head>

<body>
    <div class="login-page">
        <div class="login-container">

            {{-- ════════════════════════════════════════
                 LEFT PANEL – Branding & AI Highlights
            ════════════════════════════════════════ --}}
            <div class="brand-panel">
                {{-- Logo --}}
                <div class="brand-logo">
                    <div class="brand-logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="brand-logo-text">
                        Edu<span>Mind</span> AI
                    </div>
                </div>

                {{-- AI Badge --}}
                <div class="ai-badge">
                    <i class="fas fa-sparkles"></i>
                    Tích hợp AI thông minh
                </div>

                {{-- Tagline --}}
                <h1 class="brand-tagline">
                    Học tập thông minh,<br>
                    Thành công vượt trội.
                </h1>

                <p class="brand-subtitle">
                    Nền tảng e-learning thế hệ mới với trợ lý ảo AI giúp bạn cá nhân hóa lộ trình học tập và đạt hiệu quả tối đa.
                </p>

                {{-- Feature Highlights --}}
                <ul class="feature-list">
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="feature-text">
                            <strong>Trợ lý AI 24/7</strong>
                            <small>Hỗ trợ giải đáp mọi lúc, mọi nơi</small>
                        </div>
                    </li>
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="feature-text">
                            <strong>Lộ trình cá nhân hóa</strong>
                            <small>Tự động điều chỉnh theo năng lực</small>
                        </div>
                    </li>
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="feature-text">
                            <strong>Phân tích tiến độ</strong>
                            <small>Báo cáo chi tiết & đề xuất cải thiện</small>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- ════════════════════════════════════════
                 RIGHT PANEL – Login Form
            ════════════════════════════════════════ --}}
            <div class="form-panel">
                <div class="form-header">
                    <h2>Chào mừng trở lại!</h2>
                    <p>Đăng nhập để tiếp tục hành trình học tập của bạn</p>
                </div>

                {{-- Session Alerts --}}
                @if (session('error'))
                    <div class="alert-styled alert-danger" role="alert">
                        <i class="fas fa-circle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert-styled alert-success" role="alert">
                        <i class="fas fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Địa chỉ email</label>
                        <div class="input-wrapper">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-input @error('email') is-invalid @enderror"
                                placeholder="you@example.com"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                autofocus
                                required
                            >
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <span class="validation-msg">
                                <i class="fas fa-triangle-exclamation"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <div class="input-wrapper has-toggle">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input @error('password') is-invalid @enderror"
                                placeholder="Nhập mật khẩu của bạn"
                                autocomplete="current-password"
                                required
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button
                                type="button"
                                class="password-toggle"
                                id="togglePassword"
                                aria-label="Hiển thị mật khẩu"
                                tabindex="-1"
                            >
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="validation-msg">
                                <i class="fas fa-triangle-exclamation"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="form-options">
                        <label class="remember-check">
                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <span>Ghi nhớ đăng nhập</span>
                        </label>
                        <a href="#" class="forgot-link">Quên mật khẩu?</a>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-right-to-bracket"></i>
                        Đăng nhập vào hệ thống
                    </button>
                </form>

                {{-- Register --}}
                <div class="form-divider">hoặc</div>
                <p class="register-prompt">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}">Đăng ký ngay</a>
                </p>

                {{-- Footer --}}
                <p class="form-footer-note">
                    <i class="fas fa-shield-halved"></i>
                    Dữ liệu của bạn được bảo mật với mã hóa tiêu chuẩn SSL
                </p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         Vanilla JS – Password Toggle
    ══════════════════════════════════════════════ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (toggle && passwordInput && icon) {
                toggle.addEventListener('click', function () {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    icon.classList.toggle('fa-eye', !isPassword);
                    icon.classList.toggle('fa-eye-slash', isPassword);
                    toggle.setAttribute(
                        'aria-label',
                        isPassword ? 'Ẩn mật khẩu' : 'Hiển thị mật khẩu'
                    );
                });
            }
        });
    </script>
</body>

</html>
