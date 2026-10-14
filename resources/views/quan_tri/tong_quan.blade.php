@extends('layouts.khung')

@section('title', 'Trung Tâm Quản Trị Hệ Thống - EduMind AI')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     STYLES — Modern Sidebar & Single-Page Admin Dashboard
══════════════════════════════════════════════════════════════ --}}
<style>
    /* Bố cục trang quản trị */
    .admin-wrapper {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
        min-height: calc(100vh - 120px);
        padding-top: 1.5rem;
        padding-bottom: 4rem;
        -webkit-font-smoothing: antialiased;
    }

    /* Sidebar dọc */
    .admin-sidebar-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.5rem 1.25rem;
        box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 90px;
        z-index: 100;
    }

    .admin-profile-box {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding-bottom: 1.25rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .admin-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5, #9333ea);
        color: #ffffff;
        font-weight: 800;
        font-size: 1.15rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
        flex-shrink: 0;
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0%   { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70%  { transform: scale(1); box-shadow: 0 0 0 5px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Menu Tabs dọc */
    .admin-nav-pills .nav-link {
        display: flex;
        align-items: center;
        padding: 0.8rem 1rem;
        border-radius: 0.85rem;
        font-weight: 600;
        font-size: 0.92rem;
        color: #475569;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        background: transparent;
        text-align: left;
        width: 100%;
        margin-bottom: 0.35rem;
    }

    .admin-nav-pills .nav-link:hover {
        background-color: #f1f5f9;
        color: #1e293b;
        transform: translateX(3px);
    }

    .admin-nav-pills .nav-link.active {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        border-color: transparent;
        transform: translateX(3px);
    }

    .admin-nav-pills .nav-link.active .badge {
        background-color: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
        border-color: transparent !important;
    }

    .admin-nav-pills .nav-link.active i {
        color: #ffffff !important;
    }

    /* Link phụ dưới Sidebar */
    .sidebar-footer-link {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.75rem 1rem;
        border-radius: 0.85rem;
        font-size: 0.88rem;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .sidebar-footer-link:hover {
        background: #f1f5f9;
        color: #4f46e5;
        border-color: #cbd5e1;
    }

    /* Welcome Banner */
    .admin-welcome-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 45%, #4338ca 100%);
        border-radius: 1.25rem;
        padding: 2.25rem 2.5rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(30, 27, 75, 0.25);
        margin-bottom: 2rem;
    }

    .admin-welcome-card::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.28) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .welcome-text h1 {
        font-size: 1.65rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
        color: #ffffff;
    }

    .welcome-text p {
        font-size: 0.92rem;
        color: rgba(255, 255, 255, 0.82);
        margin: 0;
        max-width: 620px;
        line-height: 1.6;
    }

    .admin-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        white-space: nowrap;
    }

    .admin-badge.status {
        background: rgba(16, 185, 129, 0.18);
        color: #a7f3d0;
        border-color: rgba(16, 185, 129, 0.35);
    }

    .admin-badge.ai-ready {
        background: rgba(245, 158, 11, 0.18);
        color: #fde68a;
        border-color: rgba(245, 158, 11, 0.35);
    }

    /* Thẻ thống kê (Metric Cards) */
    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.5rem;
        transition: all 0.25s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .stat-icon-wrap.indigo { background: #eef2ff; color: #4f46e5; }
    .stat-icon-wrap.emerald { background: #ecfdf5; color: #059669; }
    .stat-icon-wrap.amber { background: #fffbeb; color: #d97706; }
    .stat-icon-wrap.purple { background: #faf5ff; color: #9333ea; }

    /* Thẻ danh mục (Được thiết kế lại tinh gọn) */
    .category-box-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.15rem;
        padding: 1.25rem 1.35rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .category-box-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.06);
    }

    .category-box-card h6,
    .category-name-full {
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
        text-overflow: clip !important;
        overflow: visible !important;
    }

    /* Content Panels */
    .card-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .card-panel-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        background: #ffffff;
    }

    .text-xs { font-size: 0.75rem !important; }

    /* Huy hiệu phần Tiêu đề Tab Giám sát Đề thi AI (SaaS High-Contrast Badges) */
    .badge-header-indigo {
        background-color: #eef2ff !important;
        border: 1px solid #c7d2fe !important;
        color: #4338ca !important;
        font-weight: 600;
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        letter-spacing: 0.01em;
    }

    .badge-header-emerald {
        background-color: #ecfdf5 !important;
        border: 1px solid #a7f3d0 !important;
        color: #047857 !important;
        font-weight: 600;
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        letter-spacing: 0.01em;
    }

    /* Huy hiệu cột Số câu, Lượt thi, Thời lượng trong bảng đề thi */
    .badge-quiz-stat {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        color: #334155 !important;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 0.35rem 0.65rem;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }

    .badge-quiz-stat i {
        font-size: 0.8rem;
    }
</style>

{{-- ══════════════════════════════════════════════════════════════
     KHUNG CHÍNH: BỐ CỤC 2 CỘT (SIDEBAR DỌC + TABS NỘI DUNG)
══════════════════════════════════════════════════════════════ --}}
<div class="admin-wrapper">
    <div class="container-xxl px-3 px-lg-4">
        <div class="row g-4">

            {{-- ──────────────────────────────────────────
                 CỘT TRÁI: MENU DỌC (SIDEBAR - 260px)
            ────────────────────────────────────────── --}}
            <div class="col-lg-3 col-xl-3">
                <div class="admin-sidebar-card">

                    {{-- Admin Profile Snippet --}}
                    <div class="admin-profile-box">
                        <div class="admin-avatar">
                            {{ mb_substr(Auth::user()->ho_va_ten ?? 'A', 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-dark mb-0.5 text-truncate" title="{{ Auth::user()->ho_va_ten }}">
                                {{ Auth::user()->ho_va_ten ?? 'Quản Trị Viên' }}
                            </h6>
                            <div class="d-flex align-items-center gap-1.5 mt-1">
                                <span class="pulse-dot"></span>
                                <span class="text-xs text-muted fw-semibold">Quản trị tối cao</span>
                            </div>
                        </div>
                    </div>

                    {{-- Nhãn Danh mục Menu --}}
                    <div class="text-uppercase text-xs text-muted fw-bold px-2 mb-2" style="letter-spacing: 0.06em;">
                        Bảng điều khiển
                    </div>

                    {{-- Menu Điều Hướng (Tabs Dọc) --}}
                    <div class="nav flex-column nav-pills admin-nav-pills" id="adminMenuTab" role="tablist" aria-orientation="vertical">
                        
                        {{-- Tab 1: Tổng quan --}}
                        <button class="nav-link {{ $activeTab === 'tong-quan' ? 'active' : '' }}" 
                                id="btn-tab-tong-quan" 
                                data-bs-toggle="pill" 
                                data-bs-target="#pane-tab-tong-quan" 
                                type="button" 
                                role="tab" 
                                aria-controls="pane-tab-tong-quan" 
                                aria-selected="{{ $activeTab === 'tong-quan' ? 'true' : 'false' }}"
                                onclick="updateTabUrl('tong-quan')">
                            <i class="fa-solid fa-chart-pie me-2.5 text-primary fs-6"></i>
                            <span>Tổng quan hệ thống</span>
                        </button>

                        {{-- Tab 2: Danh mục & Khóa học --}}
                        <button class="nav-link {{ $activeTab === 'khoa-hoc' ? 'active' : '' }}" 
                                id="btn-tab-khoa-hoc" 
                                data-bs-toggle="pill" 
                                data-bs-target="#pane-tab-khoa-hoc" 
                                type="button" 
                                role="tab" 
                                aria-controls="pane-tab-khoa-hoc" 
                                aria-selected="{{ $activeTab === 'khoa-hoc' ? 'true' : 'false' }}"
                                onclick="updateTabUrl('khoa-hoc')">
                            <i class="fa-solid fa-book-open me-2.5 text-success fs-6"></i>
                            <span>Danh mục & Khóa học</span>
                            <span class="badge rounded-pill ms-auto bg-light text-muted border text-xs">
                                {{ $danhSachKhoaHoc->count() }}
                            </span>
                        </button>

                        {{-- Tab 3: Giám sát Đề thi AI --}}
                        <button class="nav-link {{ $activeTab === 'de-thi' ? 'active' : '' }}" 
                                id="btn-tab-de-thi" 
                                data-bs-toggle="pill" 
                                data-bs-target="#pane-tab-de-thi" 
                                type="button" 
                                role="tab" 
                                aria-controls="pane-tab-de-thi" 
                                aria-selected="{{ $activeTab === 'de-thi' ? 'true' : 'false' }}"
                                onclick="updateTabUrl('de-thi')">
                            <i class="fa-solid fa-wand-magic-sparkles me-2.5 text-warning fs-6"></i>
                            <span>Giám sát Đề thi AI</span>
                            <span class="badge rounded-pill ms-auto bg-light text-muted border text-xs">
                                {{ $tatCaDeThi->count() }}
                            </span>
                        </button>

                        {{-- Tab 4: Quản lý Tài khoản --}}
                        <button class="nav-link {{ $activeTab === 'tai-khoan' ? 'active' : '' }}" 
                                id="btn-tab-tai-khoan" 
                                data-bs-toggle="pill" 
                                data-bs-target="#pane-tab-tai-khoan" 
                                type="button" 
                                role="tab" 
                                aria-controls="pane-tab-tai-khoan" 
                                aria-selected="{{ $activeTab === 'tai-khoan' ? 'true' : 'false' }}"
                                onclick="updateTabUrl('tai-khoan')">
                            <i class="fa-solid fa-users-gear me-2.5 text-info fs-6"></i>
                            <span>Quản lý Tài khoản</span>
                            <span class="badge rounded-pill ms-auto bg-light text-muted border text-xs">
                                {{ $danhSachTaiKhoan->count() }}
                            </span>
                        </button>
                    </div>

                    <hr class="my-3 text-muted opacity-25">

                    {{-- Link phụ: Ra trang chủ công khai --}}
                    <a href="{{ url('/') }}" class="sidebar-footer-link">
                        <i class="fa-solid fa-arrow-up-right-from-square text-primary"></i>
                        <span>Ra trang chủ công khai</span>
                    </a>

                    {{-- Badge Thông tin Hệ thống --}}
                    <div class="mt-3 p-2.5 rounded-3 bg-light text-center">
                        <span class="text-xs text-muted d-block fw-medium">EduMind AI Platform</span>
                        <span class="badge bg-white text-secondary border text-xs mt-1">Phiên bản 2.5 • Gemini 1.5</span>
                    </div>

                </div>
            </div>

            {{-- ──────────────────────────────────────────
                 CỘT PHẢI: NỘI DUNG HIỂN THỊ (TABS - col-lg-9)
            ────────────────────────────────────────── --}}
            <div class="col-lg-9 col-xl-9">
                <div class="tab-content" id="adminMenuTabContent">

                    {{-- ══════════════════════════════════════════════════════
                         TAB 1: TỔNG QUAN HỆ THỐNG
                    ══════════════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $activeTab === 'tong-quan' ? 'show active' : '' }}" 
                         id="pane-tab-tong-quan" 
                         role="tabpanel" 
                         aria-labelledby="btn-tab-tong-quan">

                        {{-- 1. Banner chào mừng Quản trị viên --}}
                        <div class="admin-welcome-card">
                            <div class="row align-items-center position-relative" style="z-index: 2;">
                                <div class="col-lg-8 welcome-text">
                                    <h1>Xin chào, {{ Auth::user()->ho_va_ten ?? 'Quản Trị Viên' }} 👋</h1>
                                    <p>
                                        Chào mừng bạn trở lại Trung tâm Quản trị EduMind AI. Theo dõi chỉ số toàn hệ thống, 
                                        kiểm duyệt danh mục khóa học và giám sát chất lượng đề thi do Gemini AI tạo tự động.
                                    </p>
                                </div>
                                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                    <div class="d-flex flex-column flex-sm-row flex-lg-column gap-2 justify-content-lg-end align-items-start align-items-lg-end">
                                        <div class="admin-badge status">
                                            <span class="pulse-dot"></span>
                                            <span>Máy chủ: Ổn định</span>
                                        </div>
                                        <div class="admin-badge ai-ready">
                                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                                            <span>Gemini 1.5 Flash: Sẵn sàng</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Bộ 3 thẻ thống kê KPI --}}
                        <div class="row g-3 mb-4">
                            {{-- KPI 1: Khóa học --}}
                            <div class="col-md-4">
                                <div class="stat-card" onclick="switchToTab('khoa-hoc')" title="Bấm để xem danh mục & khóa học">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-xs fw-bold text-muted text-uppercase">Khóa học trực tuyến</span>
                                        <div class="stat-icon-wrap indigo">
                                            <i class="fa-solid fa-graduation-cap"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="fs-2 fw-bold text-dark mb-1">{{ number_format($tongKhoaHoc) }}</h2>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-xs text-muted">Đang phát hành</span>
                                            <span class="text-xs text-primary fw-semibold">Quản lý <i class="fa-solid fa-arrow-right ms-1"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KPI 2: Học viên --}}
                            <div class="col-md-4">
                                <div class="stat-card" onclick="switchToTab('tai-khoan')" title="Bấm để xem quản lý tài khoản">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-xs fw-bold text-muted text-uppercase">Học viên đăng ký</span>
                                        <div class="stat-icon-wrap emerald">
                                            <i class="fa-solid fa-user-graduate"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="fs-2 fw-bold text-dark mb-1">{{ number_format($tongHocVien) }}</h2>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-xs text-muted">Học viên thật (Quyền: 3)</span>
                                            <span class="text-xs text-success fw-semibold">Xem DS <i class="fa-solid fa-arrow-right ms-1"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KPI 3: Đề thi AI --}}
                            <div class="col-md-4">
                                <div class="stat-card" onclick="switchToTab('de-thi')" title="Bấm để giám sát đề thi AI">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-xs fw-bold text-muted text-uppercase">Đề thi AI tạo</span>
                                        <div class="stat-icon-wrap amber">
                                            <i class="fa-solid fa-brain"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="fs-2 fw-bold text-dark mb-1">{{ number_format($tongDeThi) }}</h2>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-xs text-muted">Trắc nghiệm tự động</span>
                                            <span class="text-xs text-warning fw-semibold">Giám sát <i class="fa-solid fa-arrow-right ms-1"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Bảng Đề thi gần đây & Học viên mới nhất --}}
                        <div class="row g-4">
                            {{-- Cột trái: Đề thi gần đây --}}
                            <div class="col-xl-7">
                                <div class="card-panel h-100">
                                    <div class="card-panel-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-3 p-2 bg-indigo-50 text-indigo-600">
                                                <i class="fa-solid fa-clock-rotate-left"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-0">Đề Thi Gần Đây</h6>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-link text-primary text-decoration-none p-0 fw-semibold text-xs" onclick="switchToTab('de-thi')">
                                            Xem tất cả <i class="fa-solid fa-chevron-right ms-0.5"></i>
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light text-muted text-xs text-uppercase fw-semibold">
                                                <tr>
                                                    <th class="ps-3">Đề thi</th>
                                                    <th>Bài học</th>
                                                    <th>Số câu</th>
                                                    <th class="text-end pe-3">Lượt thi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($deThiGanDay as $dt)
                                                    <tr>
                                                        <td class="ps-3">
                                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 190px;" title="{{ $dt->tieu_de_de_thi }}">
                                                                {{ $dt->tieu_de_de_thi }}
                                                            </div>
                                                            <span class="text-xs text-muted">
                                                                <i class="fa-regular fa-clock me-1"></i>{{ $dt->thoi_luong_lam_bai_phut ?? 15 }}p
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="text-xs text-dark fw-medium text-truncate" style="max-width: 160px;">
                                                                {{ $dt->baiHoc->tieu_de_bai_hoc ?? 'Bài học chung' }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge-quiz-stat">
                                                                <i class="fa-solid fa-list-check text-primary"></i> {{ $dt->danh_sach_cau_hoi_count ?? $dt->cau_hoi_count ?? ($dt->danhSachCauHoi ? $dt->danhSachCauHoi->count() : 0) }} câu
                                                            </span>
                                                        </td>
                                                        <td class="text-end pe-3">
                                                            <span class="badge-quiz-stat">
                                                                <i class="fa-solid fa-user-check text-success"></i> {{ $dt->danh_sach_luot_lam_bai_count ?? $dt->luot_lam_bai_thi_count ?? $dt->luot_lam_bai_count ?? ($dt->danhSachLuotLamBai ? $dt->danhSachLuotLamBai->count() : 0) }} lượt
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4 text-muted small">
                                                            Chưa có đề thi nào trong hệ thống.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Cột phải: Học viên mới đăng ký --}}
                            <div class="col-xl-5">
                                <div class="card-panel h-100">
                                    <div class="card-panel-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-3 p-2 bg-emerald-50 text-emerald-600">
                                                <i class="fa-solid fa-user-plus"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-0">Học Viên Mới Nhất</h6>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-link text-primary text-decoration-none p-0 fw-semibold text-xs" onclick="switchToTab('tai-khoan')">
                                            Xem tất cả <i class="fa-solid fa-chevron-right ms-0.5"></i>
                                        </button>
                                    </div>
                                    <div class="p-3">
                                        @forelse ($hocVienMoiNhat as $hv)
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 hover-bg mb-1">
                                                <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 36px; height: 36px; background: linear-gradient(135deg, #10b981, #059669); font-size: 0.9rem; flex-shrink: 0;">
                                                        {{ mb_substr($hv->ho_va_ten ?? 'H', 0, 1) }}
                                                    </div>
                                                    <div class="overflow-hidden">
                                                        <div class="fw-semibold text-dark text-truncate small" title="{{ $hv->ho_va_ten }}">
                                                            {{ $hv->ho_va_ten ?? 'Học viên' }}
                                                        </div>
                                                        <div class="text-muted text-xs text-truncate font-monospace" title="{{ $hv->dia_chi_email }}">
                                                            {{ $hv->dia_chi_email }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle text-xs rounded-pill flex-shrink-0 ms-2">
                                                    Học viên
                                                </span>
                                            </div>
                                        @empty
                                            <div class="text-center py-4 text-muted small">
                                                Chưa có học viên nào đăng ký.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    {{-- ══════════════════════════════════════════════════════
                         TAB 2: DANH MỤC & KHÓA HỌC
                    ══════════════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $activeTab === 'khoa-hoc' ? 'show active' : '' }}" 
                         id="pane-tab-khoa-hoc" 
                         role="tabpanel" 
                         aria-labelledby="btn-tab-khoa-hoc">

                        {{-- Tiêu đề Tab & Nút thêm danh mục --}}
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-book-open text-primary"></i> Quản Lý Danh Mục & Khóa Học
                                </h4>
                                <p class="text-muted small mb-0">Quản lý các danh mục phân loại và kiểm duyệt xuất bản các khóa học trong hệ thống.</p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary rounded-pill px-3.5 py-2 btn-sm fw-semibold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalThemDanhMuc">
                                    <i class="fa-solid fa-folder-plus"></i>
                                    <span>Thêm danh mục mới</span>
                                </button>
                            </div>
                        </div>

                        {{-- 1. Lưới Thẻ Danh Mục (ĐÃ SỬA LỖI - Gọn đẹp, không còn thanh / và icon khóa) --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-layer-group text-primary"></i> Danh Mục Khóa Học Trong Hệ Thống
                                </h6>
                                <span class="badge bg-light text-muted border px-2.5 py-1 text-xs">{{ $danhSachDanhMuc->count() }} danh mục</span>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    @forelse ($danhSachDanhMuc as $dm)
                                        <div class="col-md-6 col-xl-4">
                                            <div class="category-box-card">
                                                <div>
                                                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2.5">
                                                        <h6 class="flex-grow-1 fw-bold text-dark text-break text-wrap category-name-full mb-0" style="font-size: 0.96rem; line-height: 1.45; word-break: break-word; overflow-wrap: break-word; white-space: normal;">
                                                            {{ $dm->ten_danh_muc }}
                                                        </h6>
                                                        <div class="d-flex align-items-center gap-1.5 flex-shrink-0 ms-auto">
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill text-xs px-2.5 py-1 fw-semibold">
                                                                {{ $dm->danh_sach_khoa_hoc_count ?? $dm->khoa_hoc_count ?? 0 }} khóa học
                                                            </span>

                                                            {{-- Chỉ hiển thị nút xóa khi danh mục KHÔNG có khóa học --}}
                                                            @if (($dm->danh_sach_khoa_hoc_count ?? $dm->khoa_hoc_count ?? 0) === 0)
                                                                <form action="{{ route('quan_tri.danh_muc.xoa', $dm->id_danh_muc) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục \'{{ $dm->ten_danh_muc }}\'?');" class="d-inline m-0">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1 rounded-circle" title="Xóa danh mục trống">
                                                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <p class="text-muted text-xs mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                                        {{ $dm->mo_ta_danh_muc ?: 'Chưa có mô tả cho danh mục này.' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-4 text-muted small">
                                            Chưa có danh mục nào được khởi tạo.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- 2. Bảng Danh Sách Khóa Học Trong Hệ Thống --}}
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-graduation-cap text-indigo-600"></i> Danh Sách Khóa Học
                                    </h6>
                                    <span class="text-muted text-xs">Kiểm duyệt công khai hoặc tạm ẩn khóa học (Giảng viên chịu trách nhiệm nội dung).</span>
                                </div>
                                <span class="badge bg-light text-muted border px-2.5 py-1 text-xs">{{ $danhSachKhoaHoc->count() }} khóa</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted text-xs text-uppercase fw-semibold">
                                        <tr>
                                            <th class="ps-4" style="width: 70px;">ID</th>
                                            <th>Khóa học & Danh mục</th>
                                            <th>Giảng viên</th>
                                            <th>Chương / Bài</th>
                                            <th>Trạng thái xuất bản</th>
                                            <th class="text-end pe-4" style="width: 180px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($danhSachKhoaHoc as $kh)
                                            <tr>
                                                <td class="ps-4 fw-semibold text-muted text-xs">#{{ $kh->id_khoa_hoc }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark mb-0.5 text-truncate" style="max-width: 250px;" title="{{ $kh->tieu_de_khoa_hoc }}">
                                                        {{ $kh->tieu_de_khoa_hoc }}
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 text-xs">
                                                        <span class="badge bg-light text-primary border">
                                                            {{ $kh->danhMuc->ten_danh_muc ?? 'Chung' }}
                                                        </span>
                                                        <span class="text-muted">
                                                            <i class="fa-regular fa-clock me-1"></i>{{ $kh->tong_thoi_luong_phut ?? 120 }}p
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 30px; height: 30px; background: #0284c7; font-size: 0.8rem; flex-shrink: 0;">
                                                            {{ mb_substr($kh->giangVien->ho_va_ten ?? 'G', 0, 1) }}
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <div class="fw-semibold text-dark text-xs text-truncate" style="max-width: 140px;">
                                                                {{ $kh->giangVien->ho_va_ten ?? 'Chưa gán' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-xs text-dark fw-medium">{{ $kh->danh_sach_chuong_muc_count ?? 0 }} chương</span>
                                                    <span class="text-muted text-xs"> • {{ $kh->danh_sach_bai_hoc_count ?? 0 }} bài</span>
                                                </td>
                                                <td>
                                                    @if (($kh->trang_thai_xuat_ban ?? '') === 'xuat_ban' || ($kh->trang_thai_xuat_ban ?? '') === 'cong_khai' || ($kh->trang_thai_xuat_ban ?? '') === '1')
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                                            <i class="fa-solid fa-circle-check me-1"></i> Đã xuất bản
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                                            <i class="fa-solid fa-clock-rotate-left me-1"></i> Chờ duyệt
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex align-items-center gap-1.5">
                                                        {{-- Nút bật / tắt duyệt xuất bản --}}
                                                        <form action="{{ route('quan_tri.khoa_hoc.duyet', $kh->id_khoa_hoc) }}" method="POST" class="m-0">
                                                            @csrf
                                                            @if (($kh->trang_thai_xuat_ban ?? '') === 'xuat_ban' || ($kh->trang_thai_xuat_ban ?? '') === 'cong_khai' || ($kh->trang_thai_xuat_ban ?? '') === '1')
                                                                <input type="hidden" name="trang_thai" value="cho_duyet">
                                                                <button type="submit" class="btn btn-sm btn-outline-warning rounded-3 px-2 py-1" title="Tạm ẩn khóa học khỏi công khai">
                                                                    <i class="fa-solid fa-eye-slash text-xs"></i> Ẩn
                                                                </button>
                                                            @else
                                                                <input type="hidden" name="trang_thai" value="xuat_ban">
                                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-3 px-2 py-1" title="Duyệt xuất bản công khai">
                                                                    <i class="fa-solid fa-check text-xs"></i> Duyệt
                                                                </button>
                                                            @endif
                                                        </form>

                                                        {{-- Nút Xóa khóa học --}}
                                                        <form action="{{ route('quan_tri.khoa_hoc.xoa', $kh->id_khoa_hoc) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa khóa học \'{{ $kh->tieu_de_khoa_hoc }}\' sẽ xóa toàn bộ chương mục, bài học và đề thi liên quan. Bạn có chắc chắn?');" class="m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1" title="Xóa khóa học">
                                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted small">
                                                    Chưa có khóa học nào được đăng ký trong hệ thống.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                    {{-- ══════════════════════════════════════════════════════
                         TAB 3: GIÁM SÁT ĐỀ THI AI
                    ══════════════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $activeTab === 'de-thi' ? 'show active' : '' }}" 
                         id="pane-tab-de-thi" 
                         role="tabpanel" 
                         aria-labelledby="btn-tab-de-thi">

                        {{-- Tiêu đề Tab --}}
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i> Giám Sát Đề Thi Trắc Nghiệm AI
                                </h4>
                                <p class="text-muted small mb-0">Quản lý chất lượng câu hỏi sinh tự động từ Gemini AI, thi thử nghiệm và xóa đề thi lỗi.</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-header-indigo shadow-sm">
                                    <i class="fa-solid fa-brain me-1.5 text-primary"></i> {{ number_format($tongSoCauHoi) }} câu hỏi
                                </span>
                                <span class="badge badge-header-emerald shadow-sm">
                                    <i class="fa-solid fa-chart-line me-1.5 text-success"></i> {{ number_format($tongSoLuotThi) }} lượt thi
                                </span>
                            </div>
                        </div>

                        {{-- Bảng Toàn Bộ Đề Thi AI --}}
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-list-ol text-primary"></i> Toàn Bộ Đề Thi Trong Hệ Thống
                                    </h6>
                                    <span class="text-muted text-xs">Soi chi tiết từng phương án câu hỏi hoặc dọn dẹp các đề thi trùng lặp.</span>
                                </div>
                                <span class="badge bg-light text-muted border px-2.5 py-1 text-xs">{{ $tatCaDeThi->count() }} đề thi</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted text-xs text-uppercase fw-semibold">
                                        <tr>
                                            <th class="ps-4" style="width: 70px;">ID</th>
                                            <th>Tên đề thi trắc nghiệm</th>
                                            <th>Bài học & Khóa học</th>
                                            <th>Thời lượng</th>
                                            <th>Số câu</th>
                                            <th>Lượt thi</th>
                                            <th class="text-end pe-4" style="width: 160px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tatCaDeThi as $dt)
                                            <tr>
                                                <td class="ps-4 fw-semibold text-muted text-xs">#{{ $dt->id_de_thi }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark mb-0.5 text-truncate" style="max-width: 240px;" title="{{ $dt->tieu_de_de_thi }}">
                                                        {{ $dt->tieu_de_de_thi }}
                                                    </div>
                                                    <div class="text-muted text-xs">
                                                        Điểm đạt: <strong class="text-dark">{{ $dt->diem_so_qua_mon ?? 5 }}/{{ $dt->diem_so_toi_da ?? 10 }}</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark text-xs text-truncate" style="max-width: 180px;">
                                                        <i class="fa-solid fa-book-bookmark text-indigo-500 me-1"></i>
                                                        {{ $dt->baiHoc->tieu_de_bai_hoc ?? 'Bài học liên kết' }}
                                                    </div>
                                                    <div class="text-muted text-xs text-truncate" style="max-width: 180px;">
                                                        {{ $dt->baiHoc->chuongMuc->khoaHoc->tieu_de_khoa_hoc ?? '' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge-quiz-stat">
                                                        <i class="fa-regular fa-clock text-muted"></i> {{ $dt->thoi_luong_lam_bai_phut ?? 15 }} phút
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge-quiz-stat">
                                                        <i class="fa-solid fa-list-check text-primary"></i> {{ $dt->danh_sach_cau_hoi_count ?? $dt->cau_hoi_count ?? ($dt->danhSachCauHoi ? $dt->danhSachCauHoi->count() : 0) }} câu
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge-quiz-stat">
                                                        <i class="fa-solid fa-user-check text-success"></i> {{ $dt->danh_sach_luot_lam_bai_count ?? $dt->luot_lam_bai_thi_count ?? $dt->luot_lam_bai_count ?? ($dt->danhSachLuotLamBai ? $dt->danhSachLuotLamBai->count() : 0) }} lượt
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex align-items-center gap-1.5">
                                                        {{-- Nút Soi câu hỏi trong modal --}}
                                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalDetailQuiz{{ $dt->id_de_thi }}" title="Xem chi tiết câu hỏi">
                                                            <i class="fa-solid fa-eye text-xs"></i>
                                                        </button>

                                                        {{-- Nút Thi thử --}}
                                                        <a href="{{ route('quiz.take', $dt->id_de_thi) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-3 px-2 py-1" title="Vào thi thử nghiệm">
                                                            <i class="fa-solid fa-play text-xs"></i>
                                                        </a>

                                                        {{-- Nút Xóa đề thi --}}
                                                        <form action="{{ route('quan_tri.de_thi.xoa', $dt->id_de_thi) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa đề thi \'{{ $dt->tieu_de_de_thi }}\' sẽ xóa toàn bộ câu hỏi và kết quả thi của học viên. Bạn có chắc chắn?');" class="m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1" title="Xóa đề thi này">
                                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted small">
                                                    Chưa có đề thi nào trong hệ thống.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                    {{-- ══════════════════════════════════════════════════════
                         TAB 4: QUẢN LÝ TÀI KHOẢN
                    ══════════════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $activeTab === 'tai-khoan' ? 'show active' : '' }}" 
                         id="pane-tab-tai-khoan" 
                         role="tabpanel" 
                         aria-labelledby="btn-tab-tai-khoan">

                        {{-- Tiêu đề Tab & Nút thêm người dùng --}}
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-users-gear text-info"></i> Quản Lý Tài Khoản & Phân Quyền
                                </h4>
                                <p class="text-muted small mb-0">Quản trị người dùng, phân quyền vai trò (Admin / Giảng viên / Học viên) và giám sát trạng thái tài khoản.</p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary rounded-pill px-3.5 py-2 btn-sm fw-semibold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalThemTaiKhoan">
                                    <i class="fa-solid fa-user-plus"></i>
                                    <span>Thêm tài khoản mới</span>
                                </button>
                            </div>
                        </div>

                        {{-- 4 Thẻ Phân Quyền Nhanh --}}
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6 col-md-3">
                                <div class="p-3 bg-white border rounded-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted text-xs fw-semibold">Tổng người dùng</div>
                                        <div class="fs-4 fw-bold text-dark mt-0.5">{{ $danhSachTaiKhoan->count() }}</div>
                                    </div>
                                    <div class="rounded-3 p-2.5 bg-primary-subtle text-primary fs-5">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="p-3 bg-white border rounded-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted text-xs fw-semibold">Học viên</div>
                                        <div class="fs-4 fw-bold text-success mt-0.5">{{ $soLuongHocVien }}</div>
                                    </div>
                                    <div class="rounded-3 p-2.5 bg-success-subtle text-success fs-5">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="p-3 bg-white border rounded-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted text-xs fw-semibold">Giảng viên</div>
                                        <div class="fs-4 fw-bold text-info mt-0.5">{{ $soLuongGiangVien }}</div>
                                    </div>
                                    <div class="rounded-3 p-2.5 bg-info-subtle text-info fs-5">
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="p-3 bg-white border rounded-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted text-xs fw-semibold">Quản trị viên</div>
                                        <div class="fs-4 fw-bold text-danger mt-0.5">{{ $soLuongAdmin }}</div>
                                    </div>
                                    <div class="rounded-3 p-2.5 bg-danger-subtle text-danger fs-5">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bộ lọc & Tìm kiếm tức thì qua JS (Client-side instant filter) --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 rounded-start-pill text-muted ps-3">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </span>
                                            <input type="text" id="userFilterInput" onkeyup="filterUserList()" class="form-control bg-light border-start-0 rounded-end-pill py-2 text-sm" placeholder="Tìm nhanh học viên, giảng viên theo họ tên hoặc email...">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <select id="userRoleFilterSelect" onchange="filterUserList()" class="form-select rounded-pill py-2 text-sm">
                                            <option value="">-- Lọc theo tất cả vai trò --</option>
                                            <option value="1">Quản trị viên (Admin)</option>
                                            <option value="2">Giảng viên</option>
                                            <option value="3">Học viên</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bảng Danh Sách Toàn Bộ Người Dùng --}}
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-address-book text-primary"></i> Danh Sách Toàn Bộ Người Dùng
                                </h6>
                                <span class="badge bg-light text-muted border px-2.5 py-1 text-xs" id="visibleUsersCount">
                                    {{ $danhSachTaiKhoan->count() }} tài khoản
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted text-xs text-uppercase fw-semibold">
                                        <tr>
                                            <th class="ps-4" style="width: 70px;">ID</th>
                                            <th>Người dùng</th>
                                            <th>Email</th>
                                            <th>Vai trò phân quyền</th>
                                            <th>Trạng thái</th>
                                            <th class="text-end pe-4" style="width: 140px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="userTableBody">
                                        @forelse ($danhSachTaiKhoan as $u)
                                            <tr class="user-item-row" 
                                                data-name="{{ Str::lower($u->ho_va_ten) }}" 
                                                data-email="{{ Str::lower($u->dia_chi_email) }}" 
                                                data-role="{{ $u->id_phan_quyen }}">
                                                <td class="ps-4 fw-semibold text-muted text-xs">#{{ $u->id_nguoi_dung }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2.5">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 36px; height: 36px; background: linear-gradient(135deg, #6366f1, #a855f7); font-size: 0.9rem; flex-shrink: 0;">
                                                            {{ mb_substr($u->ho_va_ten ?? 'U', 0, 1) }}
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <div class="fw-bold text-dark text-sm text-truncate" style="max-width: 180px;">
                                                                {{ $u->ho_va_ten ?? 'Chưa cập nhật' }}
                                                            </div>
                                                            @if ($u->id_nguoi_dung === Auth::id())
                                                                <span class="badge bg-primary-subtle text-primary text-xs">Bạn (Đang đăng nhập)</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-monospace text-xs">{{ $u->dia_chi_email }}</span>
                                                </td>
                                                <td>
                                                    @if ($u->id_phan_quyen == 1)
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                                            <i class="fa-solid fa-shield-halved me-1"></i> Quản trị viên
                                                        </span>
                                                    @elseif ($u->id_phan_quyen == 2)
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                                            <i class="fa-solid fa-chalkboard-user me-1"></i> Giảng viên
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                                            <i class="fa-solid fa-graduation-cap me-1"></i> Học viên
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (($u->trang_thai_tai_khoan ?? 'kich_hoat') === 'kich_hoat')
                                                        <span class="badge bg-success text-white rounded-pill px-2 py-0.5 text-xs">Kích hoạt</span>
                                                    @else
                                                        <span class="badge bg-secondary text-white rounded-pill px-2 py-0.5 text-xs">Tạm khóa</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex align-items-center gap-1.5">
                                                        {{-- Nút Đổi vai trò (Modal) --}}
                                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalEditRole{{ $u->id_nguoi_dung }}" title="Phân quyền vai trò">
                                                            <i class="fa-solid fa-user-pen text-xs"></i>
                                                        </button>

                                                        {{-- Nút Cấp lại mật khẩu (Dành cho Giảng viên và Học viên) --}}
                                                        @if ($u->id_phan_quyen != 1)
                                                            <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-3 px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalResetPassword{{ $u->id_nguoi_dung }}" title="Cấp lại mật khẩu">
                                                                <i class="fa-solid fa-key text-xs"></i>
                                                            </button>
                                                        @endif

                                                        {{-- Nút Xóa tài khoản --}}
                                                        @if ($u->id_nguoi_dung !== Auth::id())
                                                            <form action="{{ route('quan_tri.tai_khoan.xoa', $u->id_nguoi_dung) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa tài khoản \'{{ $u->ho_va_ten }}\' sẽ xóa toàn bộ tiến độ và kết quả làm bài thi liên quan. Bạn có chắc chắn?');" class="m-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1" title="Xóa tài khoản này">
                                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted small">
                                                    Chưa có người dùng nào.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODALS HỆ THỐNG (Được render sẵn để thao tác ngay trên trang)
══════════════════════════════════════════════════════════════ --}}

{{-- 1. Modal Thêm danh mục mới --}}
<div class="modal fade" id="modalThemDanhMuc" tabindex="-1" aria-labelledby="modalThemDanhMucLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark" id="modalThemDanhMucLabel">
                    <i class="fa-solid fa-folder-plus text-primary me-2"></i> Thêm Danh Mục Khóa Học Mới
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('quan_tri.danh_muc.them') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="ten_danh_muc" class="form-control rounded-3" placeholder="Ví dụ: Trí tuệ nhân tạo, Lập trình Web..." required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark text-sm">Mô tả danh mục</label>
                        <textarea name="mo_ta_danh_muc" rows="3" class="form-control rounded-3" placeholder="Nhập tóm tắt mô tả về nhóm khóa học này..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 text-sm fw-semibold">
                        <i class="fa-solid fa-check me-1"></i> Lưu danh mục
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. Modal Thêm tài khoản mới --}}
<div class="modal fade" id="modalThemTaiKhoan" tabindex="-1" aria-labelledby="modalThemTaiKhoanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark" id="modalThemTaiKhoanLabel">
                    <i class="fa-solid fa-user-plus text-primary me-2"></i> Thêm Tài Khoản Người Dùng Mới
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('quan_tri.tai_khoan.them') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_va_ten" class="form-control rounded-3" placeholder="Ví dụ: Nguyễn Văn A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Địa chỉ Email <span class="text-danger">*</span></label>
                        <input type="email" name="dia_chi_email" class="form-control rounded-3" placeholder="user@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Mật khẩu khởi tạo <span class="text-danger">*</span></label>
                        <input type="password" name="mat_khau" class="form-control rounded-3" placeholder="Tối thiểu 6 ký tự" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Vai trò phân quyền <span class="text-danger">*</span></label>
                        <select name="id_phan_quyen" class="form-select rounded-3" required>
                            <option value="3" selected>Học viên (Học tập & Làm bài kiểm tra)</option>
                            <option value="2">Giảng viên (Biên soạn bài học & khóa học)</option>
                            <option value="1">Quản trị viên (Toàn quyền hệ thống)</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold text-dark text-sm">Trạng thái tài khoản</label>
                        <select name="trang_thai_tai_khoan" class="form-select rounded-3">
                            <option value="kich_hoat" selected>Kích hoạt ngay</option>
                            <option value="khoa">Tạm khóa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 text-sm fw-semibold">
                        <i class="fa-solid fa-user-check me-1"></i> Tạo tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3. Modals Sửa vai trò phân quyền cho từng tài khoản --}}
@foreach ($danhSachTaiKhoan as $u)
    <div class="modal fade" id="modalEditRole{{ $u->id_nguoi_dung }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom py-3">
                    <h6 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-user-pen text-primary me-2"></i> Phân Quyền: {{ $u->ho_va_ten }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('quan_tri.tai_khoan.cap_nhat_quyen', $u->id_nguoi_dung) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="p-3 rounded-3 bg-light mb-3">
                            <div class="small fw-semibold text-dark">{{ $u->ho_va_ten }}</div>
                            <div class="text-xs text-muted font-monospace">{{ $u->dia_chi_email }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark text-sm">Vai trò phân quyền</label>
                            <select name="id_phan_quyen" class="form-select rounded-3" {{ $u->id_nguoi_dung === Auth::id() ? 'disabled' : '' }}>
                                <option value="1" {{ $u->id_phan_quyen == 1 ? 'selected' : '' }}>1 - Quản trị viên (Admin)</option>
                                <option value="2" {{ $u->id_phan_quyen == 2 ? 'selected' : '' }}>2 - Giảng viên (Giảng dạy / Soạn bài)</option>
                                <option value="3" {{ $u->id_phan_quyen == 3 ? 'selected' : '' }}>3 - Học viên (Tham gia học & Thi)</option>
                            </select>
                            @if ($u->id_nguoi_dung === Auth::id())
                                <small class="text-muted text-xs mt-1 d-block">
                                    <i class="fa-solid fa-circle-info text-info me-1"></i> Bạn không thể tự thay đổi vai trò của chính mình.
                                </small>
                            @endif
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold text-dark text-sm">Trạng thái tài khoản</label>
                            <select name="trang_thai_tai_khoan" class="form-select rounded-3">
                                <option value="kich_hoat" {{ ($u->trang_thai_tai_khoan ?? 'kich_hoat') === 'kich_hoat' ? 'selected' : '' }}>Kích hoạt</option>
                                <option value="khoa" {{ ($u->trang_thai_tai_khoan ?? '') === 'khoa' ? 'selected' : '' }}>Tạm khóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 text-sm fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- 3b. Modals Cấp lại mật khẩu cho Giảng viên / Học viên bởi Admin --}}
@foreach ($danhSachTaiKhoan as $u)
    @if ($u->id_phan_quyen != 1)
        <div class="modal fade" id="modalResetPassword{{ $u->id_nguoi_dung }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom py-3 px-4">
                        <h6 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-key text-warning"></i> Cấp Lại Mật Khẩu
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('quan_tri.tai_khoan.dat_lai_mat_khau', $u->id_nguoi_dung) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="p-3 rounded-3 bg-light mb-3">
                                <div class="small fw-semibold text-dark">{{ $u->ho_va_ten }}</div>
                                <div class="text-xs text-muted font-monospace">{{ $u->dia_chi_email }}</div>
                                <div class="mt-1">
                                    <span class="badge {{ $u->id_phan_quyen == 2 ? 'bg-info-subtle text-info' : 'bg-success-subtle text-success' }} text-xs">
                                        {{ $u->id_phan_quyen == 2 ? 'Giảng viên' : 'Học viên' }}
                                    </span>
                                </div>
                            </div>

                            <div class="alert alert-warning-subtle border border-warning-subtle text-dark p-2.5 rounded-3 mb-3 small d-flex align-items-start gap-2">
                                <i class="fa-solid fa-shield-halved text-warning mt-0.5"></i>
                                <div>Là Quản trị viên, bạn có quyền cấp lại mật khẩu mới trực tiếp mà không cần nhập mật khẩu cũ của người dùng.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark text-sm">Mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" name="mat_khau_moi" class="form-control rounded-3" placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                            </div>

                            <div class="mb-1">
                                <label class="form-label fw-semibold text-dark text-sm">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" name="mat_khau_moi_confirmation" class="form-control rounded-3" placeholder="Nhập lại mật khẩu mới" minlength="6" required>
                            </div>
                        </div>
                        <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 py-1.5 text-sm fw-semibold">
                                <i class="fa-solid fa-key me-1"></i> Cấp lại mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- 4. Modals Xem chi tiết câu hỏi cho từng đề thi AI --}}
@foreach ($tatCaDeThi as $dt)
    <div class="modal fade" id="modalDetailQuiz{{ $dt->id_de_thi }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom py-3">
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0.5">
                            <i class="fa-solid fa-list-check text-indigo-600 me-2"></i> {{ $dt->tieu_de_de_thi }}
                        </h6>
                        <span class="text-muted text-xs">
                            {{ $dt->baiHoc->tieu_de_bai_hoc ?? 'Bài học' }} • {{ $dt->danhSachCauHoi->count() }} câu hỏi
                        </span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @forelse ($dt->danhSachCauHoi as $index => $cauHoi)
                        <div class="p-3.5 rounded-3 border bg-light-subtle mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                    Câu {{ $index + 1 }}
                                </span>
                                <span class="badge bg-light text-muted border text-xs">
                                    {{ $cauHoi->muc_do_kho ?? 'Trung bình' }}
                                </span>
                            </div>
                            <div class="fw-semibold text-dark mb-3 text-sm">
                                {{ $cauHoi->noi_dung_cau_hoi }}
                            </div>

                            {{-- Danh sách phương án lựa chọn --}}
                            <div class="row g-2">
                                @forelse ($cauHoi->danhSachPhuongAn as $pa)
                                    <div class="col-sm-6">
                                        <div class="p-2.5 rounded-3 border {{ $pa->la_dap_an_dung ? 'bg-success-subtle border-success text-success fw-bold' : 'bg-white text-dark' }} text-xs d-flex align-items-center justify-content-between">
                                            <span>{{ $pa->noi_dung_phuong_an }}</span>
                                            @if ($pa->la_dap_an_dung)
                                                <i class="fa-solid fa-circle-check text-success"></i>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted text-xs">Chưa có phương án nào.</div>
                                @endforelse
                            </div>

                            @if (!empty($cauHoi->giai_thich_dap_an))
                                <div class="mt-2.5 pt-2 border-top text-xs text-muted">
                                    <i class="fa-regular fa-lightbulb text-warning me-1"></i>
                                    <strong>Giải thích:</strong> {{ $cauHoi->giai_thich_dap_an }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            Đề thi này chưa có câu hỏi nào.
                        </div>
                    @endforelse
                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                    <a href="{{ route('quiz.take', $dt->id_de_thi) }}" target="_blank" class="btn btn-outline-success rounded-pill px-3 py-1.5 text-sm fw-medium">
                        <i class="fa-solid fa-play me-1"></i> Thi thử đề này
                    </a>
                    <button type="button" class="btn btn-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- ══════════════════════════════════════════════════════════════
     JAVASCRIPT: TABS ĐIỀU HƯỚNG & LỌC TỨC THÌ
══════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
    /**
     * Cập nhật URL khi chuyển tab mà không reload trang
     */
    function updateTabUrl(tabKey) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tabKey);
        window.history.replaceState({}, '', url);
    }

    /**
     * Chuyển tab từ nút bấm nội dung (vd: bấm thẻ thống kê KPI)
     */
    function switchToTab(tabKey) {
        const targetBtn = document.getElementById('btn-tab-' + tabKey);
        if (targetBtn) {
            const tabInstance = new bootstrap.Tab(targetBtn);
            tabInstance.show();
            updateTabUrl(tabKey);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    /**
     * Lọc danh sách người dùng tức thì qua JavaScript (Tab 4)
     */
    function filterUserList() {
        const query = (document.getElementById('userFilterInput')?.value || '').trim().toLowerCase();
        const role = document.getElementById('userRoleFilterSelect')?.value || '';
        const rows = document.querySelectorAll('.user-item-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const userRole = row.getAttribute('data-role') || '';

            const matchQuery = !query || name.includes(query) || email.includes(query);
            const matchRole = !role || userRole === role;

            if (matchQuery && matchRole) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const countBadge = document.getElementById('visibleUsersCount');
        if (countBadge) {
            countBadge.textContent = visibleCount + ' tài khoản';
        }
    }

    // Tự động kiểm tra URL khi tải trang
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam && ['tong-quan', 'khoa-hoc', 'de-thi', 'tai-khoan'].includes(tabParam)) {
            const targetBtn = document.getElementById('btn-tab-' + tabParam);
            if (targetBtn && !targetBtn.classList.contains('active')) {
                const tabInstance = new bootstrap.Tab(targetBtn);
                tabInstance.show();
            }
        }
    });
</script>
@endpush

@endsection
