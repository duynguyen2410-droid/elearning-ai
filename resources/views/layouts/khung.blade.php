<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduMind AI - Nền tảng Học tập & Đánh giá Thông minh')</title>

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --ai-accent: #f59e0b;
            --bg-slate: #f8fafc;
            --card-border: #f1f5f9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-slate);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Glassmorphism Header */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 0.85rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            transition: all 0.3s ease;
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.5px;
        }

        .badge-ai {
            background: linear-gradient(135deg, #f59e0b, #ec4899);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .nav-link-custom {
            font-weight: 500;
            color: #475569;
            text-decoration: none;
            padding: 0.55rem 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--primary-color);
            background-color: #eef2ff;
        }

        /* User Avatar Pill */
        .user-profile-btn {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 0.3rem 0.85rem 0.3rem 0.35rem;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .user-profile-btn:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        .user-avatar-circle {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Modern Dropdown */
        .dropdown-menu-modern {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
            min-width: 230px;
        }

        .border-slate-200 {
            border-color: #e2e8f0 !important;
        }

        .dropdown-menu-modern .dropdown-item {
            border-radius: 8px;
            padding: 0.55rem 0.85rem;
            font-weight: 500;
            font-size: 0.9rem;
            color: #334155;
            transition: all 0.15s ease;
        }

        .dropdown-menu-modern .dropdown-item:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }

        .dropdown-menu-modern .dropdown-item.text-danger:hover {
            background-color: #fef2f2;
            color: #ef4444 !important;
        }

        .main-wrapper {
            flex: 1 0 auto;
        }

        /* Rich SaaS Footer */
        .footer-saas {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding-top: 4rem;
            padding-bottom: 2rem;
            color: #64748b;
        }

        .footer-heading {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            margin-bottom: 1.25rem;
        }

        .footer-links {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.92rem;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .footer-links a:hover {
            color: var(--primary-color);
            transform: translateX(3px);
        }

        .social-link-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f1f5f9;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .social-link-btn:hover {
            background-color: var(--primary-color);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .newsletter-input-group {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 0.25rem 0.25rem 0.25rem 1rem;
            display: flex;
            align-items: center;
        }

        .newsletter-input-group input {
            border: none;
            background: transparent;
            font-size: 0.88rem;
            width: 100%;
            outline: none;
            color: #1e293b;
        }

        .newsletter-input-group .btn-subscribe {
            background-color: var(--primary-color);
            color: white;
            border-radius: 999px;
            border: none;
            padding: 0.45rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .newsletter-input-group .btn-subscribe:hover {
            background-color: var(--primary-hover);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sticky Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="brand-logo" href="{{ url('/') }}">
                <i class="fa-solid fa-graduation-cap"></i> EduMind 
                <span class="badge-ai"><i class="fa-solid fa-wand-magic-sparkles"></i> AI</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars text-secondary fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fa-solid fa-house"></i> Trang chủ
                        </a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->is('hoc-vien*') || request()->is('quan-tri*') || request()->is('student*') || request()->is('dashboard*') || request()->is('tong-quan*') ? 'active' : '' }}" href="{{ Auth::user()->id_phan_quyen == 1 ? route('quan_tri.tong_quan') : route('hoc_vien.tong_quan') }}">
                            <i class="fa-solid fa-book-open"></i> {{ Auth::user()->id_phan_quyen == 1 ? 'Quản trị hệ thống' : 'Khóa học của tôi' }}
                        </a>
                    </li>
                    @endauth
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        <div class="dropdown">
                            <button class="btn user-profile-btn dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar-circle">
                                    {{ mb_substr(Auth::user()->ho_va_ten ?? 'U', 0, 1) }}
                                </div>
                                <span class="fw-medium text-dark small">{{ Auth::user()->ho_va_ten ?? 'Học viên' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-modern shadow-lg mt-2">
                                <li class="px-3 py-2 border-bottom mb-1 bg-light-subtle rounded-top">
                                    <p class="small text-muted mb-0"><i class="fa-solid fa-circle-user me-1"></i> Tài khoản</p>
                                    <p class="fw-semibold text-dark mb-0 text-truncate" style="max-width: 190px;">{{ Auth::user()->dia_chi_email }}</p>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ Auth::user()->id_phan_quyen == 1 ? route('quan_tri.tong_quan') : route('hoc_vien.tong_quan') }}">
                                        <i class="fa-solid fa-table-columns text-primary"></i> Bảng điều khiển
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary fw-medium px-3 rounded-pill">
                            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary fw-medium px-3 text-white rounded-pill shadow-sm" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                            Đăng ký miễn phí
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Alert Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 rounded-3" role="alert">
                <i class="fa-solid fa-circle-check fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 rounded-3" role="alert">
                <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content Injection Area -->
    <main class="main-wrapper py-4">
        @yield('content')
    </main>

    <!-- Rich SaaS Footer (Chân trang 4 cột chuyên nghiệp) -->
    <footer class="footer-saas">
        <div class="container">
            <div class="row g-4 mb-5">
                <!-- Cột 1: Thương hiệu -->
                <div class="col-lg-4 col-md-6">
                    <a class="brand-logo mb-3" href="{{ url('/') }}">
                        <i class="fa-solid fa-graduation-cap"></i> EduMind 
                        <span class="badge-ai"><i class="fa-solid fa-wand-magic-sparkles"></i> AI</span>
                    </a>
                    <p class="text-secondary small pe-lg-4">
                        Nền tảng E-Learning thế hệ mới tích hợp Trí tuệ nhân tạo Gemini. Cá nhân hóa lộ trình học tập, tự động sinh đề thi trắc nghiệm và phản hồi thông minh tức thì.
                    </p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="#" class="social-link-btn" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-link-btn" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="social-link-btn" title="GitHub"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="social-link-btn" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Cột 2: Khám phá -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-heading">Khám phá</h6>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fa-solid fa-angle-right me-1 small"></i> Khóa học nổi bật</a></li>
                        <li><a href="#"><i class="fa-solid fa-angle-right me-1 small"></i> Trợ lý ảo AI</a></li>
                        <li><a href="#"><i class="fa-solid fa-angle-right me-1 small"></i> Tạo đề trắc nghiệm</a></li>
                        <li><a href="#"><i class="fa-solid fa-angle-right me-1 small"></i> Bảng xếp hạng</a></li>
                    </ul>
                </div>

                <!-- Cột 3: Hỗ trợ -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-heading">Hỗ trợ</h6>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fa-solid fa-angle-right me-1 small"></i> Trung tâm trợ giúp</a></li>
                        <li><a href="#"><i class="fa-solid fa-angle-right me-1 small"></i> Điều khoản dịch vụ</a></li>
                        <li><a href="#"><i class="fa-solid fa-angle-right me-1 small"></i> Chính sách bảo mật</a></li>
                        <li><a href="#"><i class="fa-solid fa-angle-right me-1 small"></i> Liên hệ phản hồi</a></li>
                    </ul>
                </div>

                <!-- Cột 4: Đăng ký nhận tin -->
                <div class="col-lg-4 col-md-6">
                    <h6 class="footer-heading">Cập nhật tin tức</h6>
                    <p class="text-secondary small mb-3">Đăng ký nhận thông báo về các tính năng AI mới nhất và khóa học miễn phí.</p>
                    <div class="newsletter-input-group shadow-sm">
                        <input type="email" placeholder="Nhập địa chỉ email của bạn..." aria-label="Email">
                        <button class="btn-subscribe" type="button">Đăng ký</button>
                    </div>
                </div>
            </div>

            <!-- Dòng bản quyền cuối trang -->
            <div class="pt-4 border-top border-slate-200 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="small text-muted mb-0">EduMind AI &copy; {{ date('Y') }} - Hệ thống Quản lý Học tập & Khảo thí Trực quan.</p>
                <p class="small text-muted mb-0">Thiết kế với phong cách EdTech hiện đại.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    {{-- Trợ lý học tập AI Gemini (Floating Chatbot Widget) --}}
    @auth
        @include('components.chatbot-widget')
    @endauth
</body>
</html>