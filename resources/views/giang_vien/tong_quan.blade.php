@extends('layouts.khung')

@section('title', 'Bàn Làm Việc Giảng Viên - EduMind AI')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     STYLES — Modern Sidebar & Single-Page Instructor Dashboard
══════════════════════════════════════════════════════════════ --}}
<style>
    /* Bố cục trang Giảng viên */
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

    .instructor-profile-box {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding-bottom: 1.25rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .instructor-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0284c7, #3b82f6);
        color: #ffffff;
        font-weight: 800;
        font-size: 1.15rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.25);
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
        background: linear-gradient(135deg, #0284c7, #2563eb);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
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
        color: #2563eb;
        border-color: #cbd5e1;
    }

    /* Welcome Banner */
    .instructor-welcome-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0369a1 100%);
        border-radius: 1.25rem;
        padding: 2.25rem 2.5rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
        margin-bottom: 2rem;
    }

    .instructor-welcome-card::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.25) 0%, transparent 70%);
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
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
        max-width: 620px;
        line-height: 1.6;
    }

    .instructor-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        white-space: nowrap;
    }

    .instructor-badge.status {
        background: rgba(16, 185, 129, 0.2);
        color: #a7f3d0;
        border-color: rgba(16, 185, 129, 0.4);
    }

    .instructor-badge.ai-ready {
        background: rgba(245, 158, 11, 0.2);
        color: #fde68a;
        border-color: rgba(245, 158, 11, 0.4);
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

    .stat-icon-wrap.blue { background: #eff6ff; color: #2563eb; }
    .stat-icon-wrap.indigo { background: #eef2ff; color: #4f46e5; }
    .stat-icon-wrap.amber { background: #fffbeb; color: #d97706; }
    .stat-icon-wrap.emerald { background: #ecfdf5; color: #059669; }

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

    /* Badge phong cách SaaS */
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
    }

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

    .text-xs { font-size: 0.75rem !important; }
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

                    {{-- Profile Snippet --}}
                    <div class="instructor-profile-box">
                        <div class="instructor-avatar">
                            {{ mb_substr(Auth::user()->ho_va_ten ?? 'G', 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-dark mb-0.5 text-truncate" title="{{ Auth::user()->ho_va_ten }}">
                                {{ Auth::user()->ho_va_ten ?? 'Giảng Viên' }}
                            </h6>
                            <div class="d-flex align-items-center gap-1.5 mt-1">
                                <span class="pulse-dot"></span>
                                <span class="text-xs text-muted fw-semibold">Giảng viên chuyên môn</span>
                            </div>
                        </div>
                    </div>

                    {{-- Nhãn Danh mục Menu --}}
                    <div class="text-uppercase text-xs text-muted fw-bold px-2 mb-2" style="letter-spacing: 0.06em;">
                        Bàn Làm Việc
                    </div>

                    {{-- Menu Điều Hướng (Tabs Dọc) --}}
                    <div class="nav flex-column nav-pills admin-nav-pills" id="instructorMenuTab" role="tablist" aria-orientation="vertical">
                        
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
                            <span>Tổng quan giảng dạy</span>
                        </button>

                        {{-- Tab 2: Khóa học & Bài giảng --}}
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
                            <span>Khóa học & Bài giảng</span>
                            <span class="badge rounded-pill ms-auto bg-light text-muted border text-xs">
                                {{ $tongKhoaHoc }}
                            </span>
                        </button>

                        {{-- Tab 3: Soạn & Duyệt đề thi AI --}}
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
                            <span>Soạn & Duyệt đề thi AI</span>
                            <span class="badge rounded-pill ms-auto bg-light text-muted border text-xs">
                                {{ $tongDeThi }}
                            </span>
                        </button>

                        {{-- Tab 4: Kết quả thi của học viên --}}
                        <button class="nav-link {{ $activeTab === 'ket-qua' ? 'active' : '' }}" 
                                id="btn-tab-ket-qua" 
                                data-bs-toggle="pill" 
                                data-bs-target="#pane-tab-ket-qua" 
                                type="button" 
                                role="tab" 
                                aria-controls="pane-tab-ket-qua" 
                                aria-selected="{{ $activeTab === 'ket-qua' ? 'true' : 'false' }}"
                                onclick="updateTabUrl('ket-qua')">
                            <i class="fa-solid fa-square-poll-vertical me-2.5 text-info fs-6"></i>
                            <span>Kết quả thi của học viên</span>
                            <span class="badge rounded-pill ms-auto bg-light text-muted border text-xs">
                                {{ $tongLuotThi }}
                            </span>
                        </button>
                    </div>

                    <hr class="my-3 text-muted opacity-25">

                    {{-- Link phụ: Ra trang chủ công khai --}}
                    <a href="{{ url('/') }}" class="sidebar-footer-link">
                        <i class="fa-solid fa-arrow-up-right-from-square text-primary"></i>
                        <span>Ra trang chủ công khai</span>
                    </a>

                    {{-- Badge Hệ thống --}}
                    <div class="mt-3 p-2.5 rounded-3 bg-light text-center">
                        <span class="text-xs text-muted d-block fw-medium">EduMind Instructor Hub</span>
                        <span class="badge bg-white text-secondary border text-xs mt-1">Trợ lý soạn đề Gemini 1.5</span>
                    </div>

                </div>
            </div>

            {{-- ──────────────────────────────────────────
                 CỘT PHẢI: NỘI DUNG HIỂN THỊ (TABS - col-lg-9)
            ────────────────────────────────────────── --}}
            <div class="col-lg-9 col-xl-9">
                <div class="tab-content" id="instructorMenuTabContent">

                    {{-- ══════════════════════════════════════════════════════
                         TAB 1: TỔNG QUAN GIẢNG DẠY
                    ══════════════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $activeTab === 'tong-quan' ? 'show active' : '' }}" 
                         id="pane-tab-tong-quan" 
                         role="tabpanel" 
                         aria-labelledby="btn-tab-tong-quan">

                        {{-- 1. Banner chào mừng Giảng viên --}}
                        <div class="instructor-welcome-card">
                            <div class="row align-items-center position-relative" style="z-index: 2;">
                                <div class="col-lg-8 welcome-text">
                                    <h1>Xin chào, {{ Auth::user()->ho_va_ten ?? 'Giảng Viên' }} 🎓</h1>
                                    <p>
                                        Chào mừng Thầy/Cô đến với Bàn làm việc Giảng dạy. Soạn thảo giáo trình, 
                                        ứng dụng trí tuệ nhân tạo Gemini 1.5 Flash để tạo đề trắc nghiệm nhanh và theo dõi kết quả thi của học viên.
                                    </p>
                                </div>
                                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                    <div class="d-flex flex-column flex-sm-row flex-lg-column gap-2 justify-content-lg-end align-items-start align-items-lg-end">
                                        <div class="instructor-badge status">
                                            <span class="pulse-dot"></span>
                                            <span>Trạng thái: Trực tuyến</span>
                                        </div>
                                        <div class="instructor-badge ai-ready">
                                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                                            <span>Trợ lý AI Gemini: Sẵn sàng</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Bộ 4 thẻ thống kê KPI --}}
                        <div class="row g-3 mb-4">
                            {{-- KPI 1: Khóa học --}}
                            <div class="col-sm-6 col-xl-3">
                                <div class="stat-card" onclick="switchToTab('khoa-hoc')" title="Bấm để xem danh sách khóa học">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-xs fw-bold text-muted text-uppercase">Khóa học của tôi</span>
                                        <div class="stat-icon-wrap blue">
                                            <i class="fa-solid fa-graduation-cap"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="fs-2 fw-bold text-dark mb-1">{{ number_format($tongKhoaHoc) }}</h2>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-xs text-muted">Đang phụ trách</span>
                                            <span class="text-xs text-primary fw-semibold">Quản lý <i class="fa-solid fa-arrow-right ms-0.5"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KPI 2: Bài giảng --}}
                            <div class="col-sm-6 col-xl-3">
                                <div class="stat-card" onclick="switchToTab('khoa-hoc')" title="Bấm để xem các bài giảng">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-xs fw-bold text-muted text-uppercase">Tổng bài giảng</span>
                                        <div class="stat-icon-wrap indigo">
                                            <i class="fa-solid fa-video"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="fs-2 fw-bold text-dark mb-1">{{ number_format($tongBaiHoc) }}</h2>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-xs text-muted">Video & Tài liệu</span>
                                            <span class="text-xs text-indigo-600 fw-semibold">Chi tiết <i class="fa-solid fa-arrow-right ms-0.5"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KPI 3: Đề thi AI --}}
                            <div class="col-sm-6 col-xl-3">
                                <div class="stat-card" onclick="switchToTab('de-thi')" title="Bấm để soạn & duyệt đề thi AI">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-xs fw-bold text-muted text-uppercase">Đề thi trắc nghiệm</span>
                                        <div class="stat-icon-wrap amber">
                                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="fs-2 fw-bold text-dark mb-1">{{ number_format($tongDeThi) }}</h2>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-xs text-muted">Đề kiểm tra AI</span>
                                            <span class="text-xs text-warning fw-semibold">Soạn đề <i class="fa-solid fa-arrow-right ms-0.5"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KPI 4: Lượt thi của học viên --}}
                            <div class="col-sm-6 col-xl-3">
                                <div class="stat-card" onclick="switchToTab('ket-qua')" title="Bấm để xem kết quả học viên">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-xs fw-bold text-muted text-uppercase">Lượt học viên thi</span>
                                        <div class="stat-icon-wrap emerald">
                                            <i class="fa-solid fa-user-check"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="fs-2 fw-bold text-dark mb-1">{{ number_format($tongLuotThi) }}</h2>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-xs text-muted">Được chấm điểm</span>
                                            <span class="text-xs text-success fw-semibold">Xem điểm <i class="fa-solid fa-arrow-right ms-0.5"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Bảng Kết quả thi gần nhất của học viên --}}
                        <div class="card-panel">
                            <div class="card-panel-header">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-3 p-2 bg-primary-subtle text-primary">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0">Học Viên Nộp Bài Thi Gần Đây</h6>
                                </div>
                                <button type="button" class="btn btn-sm btn-link text-primary text-decoration-none p-0 fw-semibold text-xs" onclick="switchToTab('ket-qua')">
                                    Xem tất cả kết quả <i class="fa-solid fa-chevron-right ms-0.5"></i>
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted text-xs text-uppercase fw-semibold">
                                        <tr>
                                            <th class="ps-4">Học viên</th>
                                            <th>Bài thi & Khóa học</th>
                                            <th>Điểm số</th>
                                            <th>Số câu đúng</th>
                                            <th>Xếp loại</th>
                                            <th class="text-end pe-4">Thời gian nộp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($luotThiGanDay as $lt)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 32px; height: 32px; background: linear-gradient(135deg, #10b981, #059669); font-size: 0.82rem; flex-shrink: 0;">
                                                            {{ mb_substr($lt->hocVien->ho_va_ten ?? 'H', 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold text-dark text-xs">{{ $lt->hocVien->ho_va_ten ?? 'Học viên' }}</div>
                                                            <div class="text-muted text-xs font-monospace">{{ $lt->hocVien->dia_chi_email ?? '' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark text-xs text-break text-wrap">
                                                        {{ $lt->deThi->tieu_de_de_thi ?? 'Đề kiểm tra' }}
                                                    </div>
                                                    <div class="text-muted text-xs">
                                                        {{ $lt->deThi->baiHoc->chuongMuc->khoaHoc->tieu_de_khoa_hoc ?? '' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fs-6 fw-bold {{ $lt->tong_diem_dat_duoc >= 5 ? 'text-success' : 'text-danger' }}">
                                                        {{ $lt->tong_diem_dat_duoc }}/10
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge-quiz-stat">
                                                        <i class="fa-solid fa-list-check text-primary"></i> {{ $lt->so_cau_tra_loi_dung }}/{{ $lt->tong_so_cau_hoi }} câu
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($lt->tong_diem_dat_duoc >= 8.5)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">Xuất sắc</span>
                                                    @elseif ($lt->tong_diem_dat_duoc >= 7.0)
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">Khá giỏi</span>
                                                    @elseif ($lt->tong_diem_dat_duoc >= 5.0)
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">Đạt môn</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">Chưa đạt</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4 text-xs text-muted">
                                                    {{ $lt->thoi_diem_nop_bai ? \Carbon\Carbon::parse($lt->thoi_diem_nop_bai)->format('H:i - d/m/Y') : 'Vừa xong' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted small">
                                                    Chưa có lượt học viên nào nộp bài thi cho các khóa học của Thầy/Cô.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                    {{-- ══════════════════════════════════════════════════════
                         TAB 2: KHÓA HỌC & BÀI GIẢNG
                    ══════════════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $activeTab === 'khoa-hoc' ? 'show active' : '' }}" 
                         id="pane-tab-khoa-hoc" 
                         role="tabpanel" 
                         aria-labelledby="btn-tab-khoa-hoc">

                        {{-- Tiêu đề Tab & Nút thêm khóa học --}}
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-book-open text-primary"></i> Quản Lý Khóa Học & Giáo Trình Bài Giảng
                                </h4>
                                <p class="text-muted small mb-0">Biên soạn nội dung bài giảng, thêm chương mục và cập nhật video học tập cho học viên.</p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary rounded-pill px-3.5 py-2 btn-sm fw-semibold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalThemKhoaHoc">
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Tạo khóa học mới</span>
                                </button>
                            </div>
                        </div>

                        {{-- Danh sách Khóa học & Giáo trình dạng Accordion --}}
                        @forelse ($danhSachKhoaHoc as $kh)
                            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                                <div class="card-header bg-white p-3.5 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-3 p-3 bg-primary-subtle text-primary fs-4 flex-shrink-0">
                                            <i class="fa-solid fa-graduation-cap"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold text-dark text-break text-wrap mb-1">
                                                {{ $kh->tieu_de_khoa_hoc }}
                                            </h5>
                                            <div class="d-flex align-items-center flex-wrap gap-2 text-xs">
                                                <span class="badge bg-light text-primary border">
                                                    {{ $kh->danhMuc->ten_danh_muc ?? 'Chung' }}
                                                </span>
                                                <span class="text-muted">
                                                    <i class="fa-regular fa-clock me-1"></i>{{ $kh->tong_thoi_luong_phut ?? 60 }} phút
                                                </span>
                                                <span class="text-muted">
                                                    <i class="fa-solid fa-folder me-1"></i>{{ $kh->danh_sach_chuong_muc_count ?? 0 }} chương
                                                </span>
                                                <span class="text-muted">
                                                    <i class="fa-regular fa-file-video me-1"></i>{{ $kh->danh_sach_bai_hoc_count ?? 0 }} bài giảng
                                                </span>
                                                <span class="badge {{ ($kh->trang_thai_xuat_ban === 'tam_an' || $kh->trang_thai_xuat_ban === 'ban_nhap') ? 'bg-secondary-subtle text-secondary border border-secondary-subtle' : 'bg-success-subtle text-success border border-success-subtle' }} rounded-pill">
                                                    {{ ($kh->trang_thai_xuat_ban === 'tam_an' || $kh->trang_thai_xuat_ban === 'ban_nhap') ? 'Tạm ẩn' : 'Đang mở lớp' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        {{-- Nút Sửa khóa học --}}
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalSuaKhoaHoc{{ $kh->id_khoa_hoc }}">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Sửa khóa học
                                        </button>
                                        {{-- Nút Thêm chương mục mới --}}
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalThemChuong{{ $kh->id_khoa_hoc }}">
                                            <i class="fa-solid fa-folder-plus me-1"></i> + Thêm chương
                                        </button>
                                    </div>
                                </div>

                                {{-- Danh sách các chương mục trong khóa --}}
                                <div class="card-body p-3.5 bg-light-subtle">
                                    @forelse ($kh->danhSachChuongMuc as $chIndex => $ch)
                                        <div class="card border rounded-3 mb-3 bg-white shadow-xs">
                                            <div class="card-header bg-white py-2.5 px-3 d-flex justify-content-between align-items-center border-bottom">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-dark text-white rounded-pill text-xs px-2 py-0.5">
                                                        Chương {{ $chIndex + 1 }}
                                                    </span>
                                                    <span class="fw-bold text-dark text-sm text-break text-wrap">
                                                        {{ $ch->tieu_de_chuong_muc }}
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-light text-muted border text-xs">
                                                        {{ $ch->danhSachBaiHoc->count() }} bài
                                                    </span>
                                                    {{-- Nút thêm bài học vào chương này --}}
                                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-1 text-xs fw-semibold" data-bs-toggle="modal" data-bs-target="#modalThemBaiHoc{{ $ch->id_chuong_muc }}">
                                                        <i class="fa-solid fa-plus me-0.5"></i> Thêm bài học
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0 text-xs">
                                                    <thead class="table-light text-muted text-uppercase fw-semibold">
                                                        <tr>
                                                            <th class="ps-3" style="width: 50px;">STT</th>
                                                            <th>Tiêu đề bài học</th>
                                                            <th>Thời lượng</th>
                                                            <th>Đề trắc nghiệm</th>
                                                            <th class="text-end pe-3" style="width: 130px;">Thao tác</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($ch->danhSachBaiHoc as $bIndex => $bh)
                                                            <tr>
                                                                <td class="ps-3 text-muted fw-semibold">{{ $bIndex + 1 }}</td>
                                                                <td>
                                                                    <div class="fw-bold text-dark text-break text-wrap mb-0.5">
                                                                        {{ $bh->tieu_de_bai_hoc }}
                                                                    </div>
                                                                    @if (!empty($bh->tom_tat_kien_thuc_cho_ai))
                                                                        <div class="text-muted text-xs text-truncate" style="max-width: 320px;" title="{{ $bh->tom_tat_kien_thuc_cho_ai }}">
                                                                            <i class="fa-solid fa-wand-magic-sparkles text-amber-500 me-1"></i> {{ $bh->tom_tat_kien_thuc_cho_ai }}
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="badge-quiz-stat">
                                                                        <i class="fa-regular fa-clock text-muted"></i> {{ round(($bh->thoi_luong_video_giay ?? 600) / 60) }} phút
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if ($bh->deThi)
                                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fw-semibold">
                                                                            <i class="fa-solid fa-circle-check me-1"></i> Có đề thi ({{ $bh->deThi->danhSachCauHoi->count() }} câu)
                                                                        </span>
                                                                    @else
                                                                        <div class="d-inline-flex align-items-center gap-1">
                                                                            {{-- Nút tự soạn đề --}}
                                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0.5 text-xs fw-semibold" onclick="moModalSoanThuCong({{ $bh->id_bai_hoc }}, '{{ addslashes($bh->tieu_de_bai_hoc) }}')" title="Tự soạn đề thi thủ công cho bài học này">
                                                                                <i class="fa-solid fa-pen me-0.5"></i> Soạn đề
                                                                            </button>
                                                                            {{-- Nút AI sinh đề thông minh --}}
                                                                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2 py-0.5 text-xs fw-semibold" onclick="moModalSinhDeAi({{ $bh->id_bai_hoc }}, '{{ addslashes($bh->tieu_de_bai_hoc) }}')" title="AI sinh đề thông minh chống trùng">
                                                                                <i class="fa-solid fa-wand-magic-sparkles me-0.5"></i> AI sinh đề
                                                                            </button>
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    <div class="d-inline-flex align-items-center gap-1.5">
                                                                        {{-- Nút xem video nhanh qua Modal --}}
                                                                        @if ($bh->duong_dan_video_bai_giang)
                                                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalPreviewVideo{{ $bh->id_bai_hoc }}" title="Xem video bài giảng ngay tại đây">
                                                                                <i class="fa-solid fa-circle-play text-xs"></i>
                                                                            </button>
                                                                        @endif

                                                                        {{-- Nút mở toàn trang bài học (giao diện học viên) --}}
                                                                        <a href="{{ route('student.lesson.show', $bh->id_bai_hoc) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1" title="Xem trước giao diện bài học (Trang học viên)">
                                                                            <i class="fa-solid fa-eye text-xs"></i>
                                                                        </a>

                                                                        {{-- Nút xóa bài học --}}
                                                                        <form action="{{ route('giang_vien.bai_hoc.xoa', $bh->id_bai_hoc) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài học \'{{ $bh->tieu_de_bai_hoc }}\'?');" class="m-0">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1" title="Xóa bài học">
                                                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center py-3 text-muted">
                                                                    Chương này chưa có bài học nào. Hãy bấm "+ Thêm bài học" ở trên.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-muted small bg-white rounded-3 border">
                                            Khóa học này chưa có chương mục nào. Hãy bấm nút <strong>"+ Thêm chương"</strong> ở trên để bắt đầu xây dựng giáo trình.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                                <div class="rounded-circle p-4 bg-primary-subtle text-primary d-inline-flex mx-auto mb-3 fs-1">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Thầy/Cô Chưa Có Khóa Học Nào</h5>
                                <p class="text-muted small mx-auto mb-3" style="max-width: 450px;">
                                    Bắt đầu tạo khóa học trực tuyến đầu tiên để chia sẻ kiến thức đến hàng nghìn học viên trên hệ thống EduMind AI.
                                </p>
                                <div>
                                    <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalThemKhoaHoc">
                                        <i class="fa-solid fa-plus me-1"></i> Tạo khóa học mới ngay
                                    </button>
                                </div>
                            </div>
                        @endforelse

                    </div>


                    {{-- ══════════════════════════════════════════════════════
                         TAB 3: SOẠN & DUYỆT ĐỀ THI AI
                    ══════════════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $activeTab === 'de-thi' ? 'show active' : '' }}" 
                         id="pane-tab-de-thi" 
                         role="tabpanel" 
                         aria-labelledby="btn-tab-de-thi">

                        {{-- Tiêu đề Tab & Hai nút hành động lớn --}}
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i> Soạn & Duyệt Đề Thi Trắc Nghiệm AI
                                </h4>
                                <p class="text-muted small mb-0">Hỗ trợ tự soạn câu hỏi thủ công chi tiết hoặc ứng dụng Gemini AI sinh đề thông minh chống trùng lặp.</p>
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                {{-- NÚT 1: Tự soạn đề thi thủ công --}}
                                <button type="button" class="btn btn-white border rounded-pill px-3 py-2 btn-sm fw-semibold shadow-xs text-dark d-inline-flex align-items-center gap-2 hover-shadow" data-bs-toggle="modal" data-bs-target="#modalTaoDeThuCong">
                                    <i class="fa-solid fa-pen-nib text-primary"></i>
                                    <span>✍️ Tự soạn đề thủ công</span>
                                </button>
                                {{-- NÚT 2: AI tạo đề thi thông minh --}}
                                <button type="button" class="btn btn-primary rounded-pill px-3.5 py-2 btn-sm fw-semibold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalSinhDeAiThongMinh">
                                    <i class="fa-solid fa-wand-magic-sparkles text-warning"></i>
                                    <span>✨ AI Tạo đề thông minh</span>
                                </button>
                            </div>
                        </div>

                        {{-- 1. Mục Gợi ý: Bài học chưa có đề thi --}}
                        @if ($baiHocChuaCoDe->isNotEmpty())
                            <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-warning">
                                <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-bolt text-warning"></i> Bài Học Cần Tạo Đề Thi Trắc Nghiệm
                                        </h6>
                                        <span class="text-muted text-xs">Các bài học này chưa có đề trắc nghiệm. Thầy/Cô có thể tự soạn thủ công hoặc để Gemini AI tự động sinh đề.</span>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill text-xs px-2.5 py-1">
                                        {{ $baiHocChuaCoDe->count() }} bài chưa có đề
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        @foreach ($baiHocChuaCoDe as $bhChua)
                                            <div class="col-md-6">
                                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between gap-2">
                                                    <div class="overflow-hidden">
                                                        <div class="fw-bold text-dark text-xs text-break text-wrap mb-0.5">
                                                            {{ $bhChua->tieu_de_bai_hoc }}
                                                        </div>
                                                        <div class="text-muted text-xs text-truncate">
                                                            {{ $bhChua->chuongMuc->khoaHoc->tieu_de_khoa_hoc ?? '' }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                                                        {{-- Cách 1: Tự soạn thủ công --}}
                                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 text-xs fw-semibold" onclick="moModalSoanThuCong({{ $bhChua->id_bai_hoc }}, '{{ addslashes($bhChua->tieu_de_bai_hoc) }}')" title="Tự soạn đề cho bài này">
                                                            <i class="fa-solid fa-pen me-1"></i> Tự soạn
                                                        </button>
                                                        {{-- Cách 2: AI sinh đề thông minh --}}
                                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-2.5 py-1 text-xs fw-semibold shadow-xs" onclick="moModalSinhDeAi({{ $bhChua->id_bai_hoc }}, '{{ addslashes($bhChua->tieu_de_bai_hoc) }}')" title="AI sinh đề thông minh chống trùng">
                                                            <i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI sinh đề
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 2. Bảng Danh sách Đề thi trắc nghiệm đã tạo --}}
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-list-ol text-primary"></i> Đề Thi Của Các Khóa Học Thuộc Quyền Quản Lý
                                    </h6>
                                    <span class="text-muted text-xs">Thầy/Cô có quyền chỉnh sửa chi tiết từng câu hỏi, điều chỉnh phương án A/B/C/D hoặc đổi đáp án đúng.</span>
                                </div>
                                <span class="badge bg-light text-muted border px-2.5 py-1 text-xs">{{ $danhSachDeThi->count() }} đề</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted text-xs text-uppercase fw-semibold">
                                        <tr>
                                            <th class="ps-4" style="width: 70px;">ID</th>
                                            <th>Đề thi trắc nghiệm</th>
                                            <th>Bài học liên kết</th>
                                            <th>Thời lượng</th>
                                            <th>Số câu</th>
                                            <th>Lượt thi</th>
                                            <th class="text-end pe-4" style="width: 170px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($danhSachDeThi as $dt)
                                            <tr>
                                                <td class="ps-4 fw-semibold text-muted text-xs">#{{ $dt->id_de_thi }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark text-break text-wrap mb-0.5">
                                                        {{ $dt->tieu_de_de_thi }}
                                                    </div>
                                                    <div class="text-muted text-xs">
                                                        Điểm đạt: <strong class="text-dark">{{ $dt->diem_so_qua_mon ?? 5 }}/{{ $dt->diem_so_toi_da ?? 10 }}</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark text-xs text-break text-wrap">
                                                        {{ $dt->baiHoc->tieu_de_bai_hoc ?? 'Bài học' }}
                                                    </div>
                                                    <div class="text-muted text-xs">
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
                                                        <i class="fa-solid fa-list-check text-primary"></i> {{ $dt->danh_sach_cau_hoi_count ?? $dt->danhSachCauHoi->count() }} câu
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge-quiz-stat">
                                                        <i class="fa-solid fa-user-check text-success"></i> {{ $dt->danh_sach_luot_lam_bai_count ?? $dt->danhSachLuotLamBai->count() }} lượt
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-inline-flex align-items-center gap-1.5">
                                                        {{-- Nút Xem & Sửa câu hỏi (Modal) --}}
                                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalSuaDeThi{{ $dt->id_de_thi }}" title="Xem và sửa câu hỏi / đáp án">
                                                            <i class="fa-solid fa-pen-to-square text-xs"></i> Sửa
                                                        </button>

                                                        {{-- Nút Thi thử --}}
                                                        <a href="{{ route('quiz.take', $dt->id_de_thi) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-3 px-2 py-1" title="Vào thi thử nghiệm">
                                                            <i class="fa-solid fa-play text-xs"></i>
                                                        </a>

                                                        {{-- Nút Xóa đề thi --}}
                                                        <form action="{{ route('giang_vien.de_thi.xoa', $dt->id_de_thi) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa đề thi này sẽ xóa toàn bộ câu hỏi và kết quả thi của học viên. Bạn có chắc chắn?');" class="m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1" title="Xóa đề thi">
                                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted small">
                                                    Chưa có đề thi nào được tạo. Hãy bấm "AI Sinh Đề" ở bảng trên.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                    {{-- ══════════════════════════════════════════════════════
                         TAB 4: KẾT QUẢ THI CỦA HỌC VIÊN
                    ══════════════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $activeTab === 'ket-qua' ? 'show active' : '' }}" 
                         id="pane-tab-ket-qua" 
                         role="tabpanel" 
                         aria-labelledby="btn-tab-ket-qua">

                        {{-- Tiêu đề Tab --}}
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-square-poll-vertical text-info"></i> Thống Kê Điểm Số & Kết Quả Học Viên
                                </h4>
                                <p class="text-muted small mb-0">Theo dõi tiến độ, phổ điểm và đánh giá mức độ tiếp thu bài học của học viên qua các đề kiểm tra.</p>
                            </div>
                            <div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-semibold">
                                    Tổng số: {{ $danhSachLuotThi->count() }} lượt nộp bài
                                </span>
                            </div>
                        </div>

                        {{-- Bảng chi tiết toàn bộ lượt làm bài thi --}}
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold text-dark mb-0">Danh Sách Học Viên Đã Thi</h6>
                                <span class="badge bg-light text-muted border text-xs">{{ $danhSachLuotThi->count() }} kết quả</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted text-xs text-uppercase fw-semibold">
                                        <tr>
                                            <th class="ps-4">Học viên</th>
                                            <th>Bài thi trắc nghiệm</th>
                                            <th>Khóa học</th>
                                            <th>Điểm số</th>
                                            <th>Số câu đúng</th>
                                            <th>Xếp loại</th>
                                            <th class="text-end pe-4">Thời gian nộp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($danhSachLuotThi as $lt)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 34px; height: 34px; background: linear-gradient(135deg, #6366f1, #8b5cf6); font-size: 0.85rem; flex-shrink: 0;">
                                                            {{ mb_substr($lt->hocVien->ho_va_ten ?? 'H', 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark text-xs">{{ $lt->hocVien->ho_va_ten ?? 'Học viên' }}</div>
                                                            <div class="text-muted text-xs font-monospace">{{ $lt->hocVien->dia_chi_email ?? '' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark text-xs text-break text-wrap">
                                                        {{ $lt->deThi->tieu_de_de_thi ?? 'Đề thi' }}
                                                    </div>
                                                    <div class="text-muted text-xs">
                                                        Lần thi thứ: {{ $lt->lan_thi_thu ?? 1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-xs text-dark fw-medium">
                                                        {{ $lt->deThi->baiHoc->chuongMuc->khoaHoc->tieu_de_khoa_hoc ?? 'Chung' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fs-6 fw-bold {{ $lt->tong_diem_dat_duoc >= 5 ? 'text-success' : 'text-danger' }}">
                                                        {{ $lt->tong_diem_dat_duoc }}/10
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge-quiz-stat">
                                                        <i class="fa-solid fa-list-check text-primary"></i> {{ $lt->so_cau_tra_loi_dung }}/{{ $lt->tong_so_cau_hoi }} câu
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($lt->tong_diem_dat_duoc >= 8.5)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">Xuất sắc</span>
                                                    @elseif ($lt->tong_diem_dat_duoc >= 7.0)
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">Khá giỏi</span>
                                                    @elseif ($lt->tong_diem_dat_duoc >= 5.0)
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">Đạt môn</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 text-xs fw-semibold">Chưa đạt</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4 text-xs text-muted">
                                                    {{ $lt->thoi_diem_nop_bai ? \Carbon\Carbon::parse($lt->thoi_diem_nop_bai)->format('H:i - d/m/Y') : 'Vừa xong' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted small">
                                                    Chưa có kết quả làm bài thi nào của học viên.
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
     MODALS HỆ THỐNG GIẢNG VIÊN (Tạo khóa học, thêm chương, thêm bài, sửa câu hỏi)
══════════════════════════════════════════════════════════════ --}}

{{-- 1. Modal Tạo Khóa Học Mới --}}
<div class="modal fade" id="modalThemKhoaHoc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-graduation-cap text-primary me-2"></i> Tạo Khóa Học Mới
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('giang_vien.khoa_hoc.them') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Tên khóa học <span class="text-danger">*</span></label>
                        <input type="text" name="tieu_de_khoa_hoc" class="form-control rounded-3" placeholder="Ví dụ: Khóa học Lập trình Flutter & AI Assistant" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark text-sm">Danh mục đào tạo <span class="text-danger">*</span></label>
                            <select name="id_danh_muc" class="form-select rounded-3" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($danhSachDanhMuc as $dm)
                                    <option value="{{ $dm->id_danh_muc }}">{{ $dm->ten_danh_muc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark text-sm">Tổng thời lượng dự kiến (phút)</label>
                            <input type="number" name="tong_thoi_luong_phut" class="form-control rounded-3" value="120" min="10">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Mô tả ngắn gọn</label>
                        <textarea name="mo_ta_ngan_gon" rows="2" class="form-control rounded-3" placeholder="Tóm tắt nội dung chính học viên sẽ đạt được sau khóa học..."></textarea>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold text-dark text-sm">Cấp độ đào tạo</label>
                        <select name="cap_do_khoa_hoc" class="form-select rounded-3">
                            <option value="co_ban" selected>Cơ bản (Người mới bắt đầu)</option>
                            <option value="trung_binh">Trung bình (Đã có kiến thức nền)</option>
                            <option value="chuyen_sau">Nâng cao / Chuyên sâu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 text-sm fw-semibold">
                        <i class="fa-solid fa-check me-1"></i> Lưu và Mở lớp
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 1.1 Modals Chỉnh Sửa Khóa Học Cho Giảng Viên --}}
@foreach ($danhSachKhoaHoc as $kh)
    <div class="modal fade" id="modalSuaKhoaHoc{{ $kh->id_khoa_hoc }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom py-3">
                    <h6 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-pen-to-square text-primary me-2"></i> Chỉnh Sửa Khóa Học: {{ $kh->tieu_de_khoa_hoc }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('giang_vien.khoa_hoc.cap_nhat', $kh->id_khoa_hoc) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark text-sm">Tên khóa học <span class="text-danger">*</span></label>
                            <input type="text" name="ten_khoa_hoc" class="form-control rounded-3" value="{{ $kh->tieu_de_khoa_hoc }}" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark text-sm">Danh mục đào tạo <span class="text-danger">*</span></label>
                                <select name="id_danh_muc" class="form-select rounded-3" required>
                                    @foreach ($danhSachDanhMuc as $dm)
                                        <option value="{{ $dm->id_danh_muc }}" {{ $kh->id_danh_muc == $dm->id_danh_muc ? 'selected' : '' }}>
                                            {{ $dm->ten_danh_muc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark text-sm">Tổng thời lượng (phút)</label>
                                <input type="number" name="thoi_luong" class="form-control rounded-3" value="{{ $kh->tong_thoi_luong_phut ?? 60 }}" min="10">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark text-sm">Trạng thái khóa học</label>
                                <select name="trang_thai_xuat_ban" class="form-select rounded-3">
                                    <option value="xuat_ban" {{ ($kh->trang_thai_xuat_ban !== 'tam_an' && $kh->trang_thai_xuat_ban !== 'ban_nhap') ? 'selected' : '' }}>🟢 Đang mở lớp (Học viên thấy và đăng ký học)</option>
                                    <option value="tam_an" {{ ($kh->trang_thai_xuat_ban === 'tam_an' || $kh->trang_thai_xuat_ban === 'ban_nhap') ? 'selected' : '' }}>⏸️ Tạm ẩn (Bản nháp / Chưa mở đăng ký)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark text-sm">Cấp độ đào tạo</label>
                                <select name="cap_do_khoa_hoc" class="form-select rounded-3">
                                    <option value="co_ban" {{ $kh->cap_do_khoa_hoc == 'co_ban' ? 'selected' : '' }}>Cơ bản (Người mới bắt đầu)</option>
                                    <option value="trung_binh" {{ $kh->cap_do_khoa_hoc == 'trung_binh' ? 'selected' : '' }}>Trung bình (Đã có kiến thức nền)</option>
                                    <option value="chuyen_sau" {{ $kh->cap_do_khoa_hoc == 'chuyen_sau' ? 'selected' : '' }}>Nâng cao / Chuyên sâu</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark text-sm">Mô tả ngắn gọn</label>
                            <textarea name="mo_ta_ngan_gon" rows="2" class="form-control rounded-3">{{ $kh->mo_ta_ngan_gon }}</textarea>
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold text-dark text-sm">Nội dung giới thiệu chi tiết</label>
                            <textarea name="noi_dung_gioi_thieu_chi_tiet" rows="3" class="form-control rounded-3">{{ $kh->noi_dung_gioi_thieu_chi_tiet }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 text-sm fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật khóa học
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- 2. Modals Thêm Chương Mục Cho Từng Khóa Học --}}
@foreach ($danhSachKhoaHoc as $kh)
    <div class="modal fade" id="modalThemChuong{{ $kh->id_khoa_hoc }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom py-3">
                    <h6 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-folder-plus text-primary me-2"></i> Thêm Chương Mới
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('giang_vien.chuong.them', $kh->id_khoa_hoc) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="p-2.5 rounded-3 bg-light mb-3 text-xs">
                            Khóa học: <strong>{{ $kh->tieu_de_khoa_hoc }}</strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark text-sm">Tiêu đề chương mục <span class="text-danger">*</span></label>
                            <input type="text" name="tieu_de_chuong_muc" class="form-control rounded-3" placeholder="Ví dụ: Chương 1: Kiến trúc hệ thống và Cài đặt" required>
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold text-dark text-sm">Mô tả ngắn của chương</label>
                            <textarea name="mo_ta_chuong_muc" rows="2" class="form-control rounded-3" placeholder="Tóm tắt kiến thức trong chương này..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 text-sm fw-semibold">
                            <i class="fa-solid fa-check me-1"></i> Lưu chương mục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- 3. Modals Thêm Bài Học Mới Cho Từng Chương Mục --}}
@foreach ($danhSachKhoaHoc as $kh)
    @foreach ($kh->danhSachChuongMuc as $ch)
        <div class="modal fade" id="modalThemBaiHoc{{ $ch->id_chuong_muc }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom py-3">
                        <h6 class="modal-title fw-bold text-dark">
                            <i class="fa-solid fa-plus-circle text-success me-2"></i> Thêm Bài Học Vào: {{ $ch->tieu_de_chuong_muc }}
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('giang_vien.bai_hoc.them', $ch->id_chuong_muc) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark text-sm">Tiêu đề bài học <span class="text-danger">*</span></label>
                                <input type="text" name="tieu_de_bai_hoc" class="form-control rounded-3" placeholder="Ví dụ: Bài 1: Cấu trúc cơ bản và Thiết lập môi trường" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-7">
                                    <label class="form-label fw-semibold text-dark text-sm">Đường dẫn Video bài giảng (URL / YouTube Embed)</label>
                                    <input type="text" name="duong_dan_video_bai_giang" class="form-control rounded-3" placeholder="https://www.youtube.com/embed/...">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold text-dark text-sm">Thời lượng video (giây)</label>
                                    <input type="number" name="thoi_luong_video_giay" class="form-control rounded-3" value="600" min="60">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark text-sm">Tóm tắt kiến thức (cho Trợ lý AI tạo đề trắc nghiệm)</label>
                                <textarea name="tom_tat_kien_thuc_cho_ai" rows="3" class="form-control rounded-3" placeholder="Nhập các ý chính trọng tâm để AI dựa vào đây tạo các câu hỏi trắc nghiệm kiểm tra..."></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-semibold text-dark text-sm">Nội dung chi tiết bài giảng</label>
                                <textarea name="noi_dung_bai_hoc_chi_tiet" rows="4" class="form-control rounded-3" placeholder="Nội dung giáo trình, bài tập, hướng dẫn học tập..."></textarea>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="cho_phep_hoc_thu" value="1" id="hocThu{{ $ch->id_chuong_muc }}" checked>
                                <label class="form-check-label text-sm text-dark" for="hocThu{{ $ch->id_chuong_muc }}">
                                    Cho phép học viên học thử bài này
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-success rounded-pill px-4 py-1.5 text-sm fw-semibold">
                                <i class="fa-solid fa-check me-1"></i> Lưu bài học
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

{{-- 3b. Modals Xem trước Video Bài Giảng Trực Tiếp Trên Bàn Làm Việc --}}
@foreach ($danhSachKhoaHoc as $kh)
    @foreach ($kh->danhSachChuongMuc as $ch)
        @foreach ($ch->danhSachBaiHoc as $bh)
            <div class="modal fade modal-preview-video" id="modalPreviewVideo{{ $bh->id_bai_hoc }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                        <div class="modal-header border-bottom py-3 px-4 bg-light">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 text-xs">
                                    <i class="fa-solid fa-video me-1"></i> Video bài giảng
                                </span>
                                <h6 class="modal-title fw-bold text-dark mb-0 text-truncate" style="max-width: 450px;" title="{{ $bh->tieu_de_bai_hoc }}">
                                    {{ $bh->tieu_de_bai_hoc }}
                                </h6>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            {{-- Video Player --}}
                            @php
                                $vUrl = $bh->duong_dan_video_bai_giang ?? '';
                                $isYt = str_contains($vUrl, 'youtube.com') || str_contains($vUrl, 'youtu.be');
                                $isDirect = str_ends_with(strtolower($vUrl), '.mp4') || str_ends_with(strtolower($vUrl), '.webm');
                                $ytId = '';
                                if ($isYt) {
                                    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $vUrl, $m);
                                    $ytId = $m[1] ?? '';
                                }
                            @endphp

                            <div class="bg-dark text-center" style="aspect-ratio: 16/9; max-height: 420px; display: flex; align-items: center; justify-content: center; position: relative;">
                                @if ($isYt && $ytId)
                                    <iframe
                                        style="width: 100%; height: 100%; border: 0;"
                                        src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=0&rel=0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                    ></iframe>
                                @elseif ($isDirect)
                                    <video style="width: 100%; height: 100%; object-fit: contain;" controls preload="metadata">
                                        <source src="{{ str_starts_with($vUrl, 'http') ? $vUrl : asset($vUrl) }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ thẻ video HTML5.
                                    </video>
                                @elseif ($vUrl)
                                    <div class="p-4 text-white">
                                        <i class="fa-solid fa-play-circle fs-1 text-primary mb-2"></i>
                                        <h6 class="fw-bold text-white mb-2">{{ $bh->tieu_de_bai_hoc }}</h6>
                                        <p class="text-white-50 small mb-3">Đường dẫn video ngoài:</p>
                                        <a href="{{ $vUrl }}" target="_blank" class="btn btn-sm btn-light rounded-pill px-3">
                                            <i class="fa-solid fa-up-right-from-square me-1"></i> Mở video trong tab mới
                                        </a>
                                    </div>
                                @else
                                    <div class="p-4 text-white text-center">
                                        <i class="fa-solid fa-video-slash fs-1 text-secondary mb-2"></i>
                                        <p class="text-white-50 small mb-0">Bài học này chưa được cấu hình đường dẫn video bài giảng.</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Lesson Content & AI Notes Preview --}}
                            <div class="p-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-xs text-muted">
                                        <i class="fa-regular fa-clock me-1"></i> Thời lượng: {{ $bh->thoi_luong_video_giay ? floor($bh->thoi_luong_video_giay / 60) . ' phút' : 'Chưa đặt' }}
                                    </span>
                                    @if ($bh->deThiTracNghiem || $bh->deThi)
                                        <span class="badge bg-success-subtle text-success text-xs">
                                            <i class="fa-solid fa-check me-1"></i> Đã có đề thi trắc nghiệm
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning text-xs">
                                            <i class="fa-solid fa-clock me-1"></i> Chưa có đề thi
                                        </span>
                                    @endif
                                </div>

                                @if (!empty($bh->tom_tat_kien_thuc_cho_ai))
                                    <div class="p-3 bg-light rounded-3 mb-3 border">
                                        <div class="fw-semibold text-dark text-xs mb-1 d-flex align-items-center gap-1.5">
                                            <i class="fa-solid fa-wand-magic-sparkles text-warning"></i> Tóm tắt kiến thức (cho AI Trợ giảng):
                                        </div>
                                        <div class="text-xs text-secondary mb-0" style="white-space: pre-line;">{{ $bh->tom_tat_kien_thuc_cho_ai }}</div>
                                    </div>
                                @endif

                                @if (!empty($bh->noi_dung_bai_hoc_chi_tiet))
                                    <div class="mb-0">
                                        <div class="fw-semibold text-dark text-xs mb-1">Nội dung chi tiết bài giảng:</div>
                                        <div class="text-xs text-muted" style="max-height: 120px; overflow-y: auto; white-space: pre-line;">{{ Str::limit(strip_tags($bh->noi_dung_bai_hoc_chi_tiet), 350) }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer border-top py-2.5 px-4 bg-light">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-xs" data-bs-dismiss="modal">Đóng</button>
                            <a href="{{ route('student.lesson.show', $bh->id_bai_hoc) }}" target="_blank" class="btn btn-primary rounded-pill px-3.5 py-1.5 text-xs fw-semibold">
                                <i class="fa-solid fa-up-right-from-square me-1"></i> Mở toàn màn hình bài học
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@endforeach

{{-- 4. Modals Xem & Sửa Câu Hỏi Đề Thi Cho Giảng Viên --}}
@foreach ($danhSachDeThi as $dt)
    <div class="modal fade" id="modalSuaDeThi{{ $dt->id_de_thi }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom py-3">
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0.5">
                            <i class="fa-solid fa-pen-to-square text-primary me-2"></i> Soạn & Chỉnh Sửa Câu Hỏi: {{ $dt->tieu_de_de_thi }}
                        </h6>
                        <span class="text-muted text-xs">
                            {{ $dt->baiHoc->tieu_de_bai_hoc ?? 'Bài học' }} • {{ $dt->danhSachCauHoi->count() }} câu hỏi
                        </span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @forelse ($dt->danhSachCauHoi as $cIndex => $ch)
                        <div class="card border rounded-3 p-3.5 mb-3 bg-light-subtle">
                            <form action="{{ route('giang_vien.cau_hoi.cap_nhat', $ch->id_cau_hoi) }}" method="POST">
                                @csrf
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                        Câu {{ $cIndex + 1 }}
                                    </span>
                                    <span class="badge bg-light text-muted border text-xs">
                                        {{ $ch->muc_do_kho ?? 'Trung bình' }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-xs fw-bold text-dark mb-1">Nội dung câu hỏi</label>
                                    <textarea name="noi_dung_cau_hoi" rows="2" class="form-control form-control-sm rounded-3" required>{{ $ch->noi_dung_cau_hoi }}</textarea>
                                </div>

                                {{-- Danh sách 4 phương án lựa chọn và nút chọn đáp án đúng --}}
                                <label class="form-label text-xs fw-bold text-dark mb-1">Phương án lựa chọn (chọn nút tròn để đánh dấu đáp án đúng)</label>
                                <div class="row g-2 mb-2">
                                    @foreach ($ch->danhSachPhuongAn as $pa)
                                        <div class="col-md-6">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-text bg-white">
                                                    <input class="form-check-input mt-0" type="radio" name="dap_an_dung" value="{{ $pa->id_phuong_an }}" {{ $pa->la_dap_an_chinh_xac ? 'checked' : '' }} title="Chọn làm đáp án đúng" required>
                                                    <span class="ms-1.5 fw-bold text-dark text-xs">{{ $pa->ky_tu_phuong_an }}.</span>
                                                </div>
                                                <input type="text" name="phuong_an[{{ $pa->id_phuong_an }}]" class="form-control {{ $pa->la_dap_an_chinh_xac ? 'border-success text-success fw-semibold' : '' }}" value="{{ $pa->noi_dung_phuong_an }}" required>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mb-2">
                                    <label class="form-label text-xs fw-bold text-dark mb-1">Giải thích đáp án</label>
                                    <input type="text" name="giai_thich_chi_tiet_dap_an" class="form-control form-control-sm rounded-3 text-xs" value="{{ $ch->giai_thich_chi_tiet_dap_an }}" placeholder="Giải thích vì sao đáp án này đúng...">
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 text-xs fw-semibold">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu câu này
                                    </button>
                                </div>
                            </form>
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
     5. MODAL TỰ SOẠN ĐỀ THI THỦ CÔNG
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalTaoDeThuCong" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 p-2 bg-primary-subtle text-primary">
                        <i class="fa-solid fa-pen-nib fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0">Tự Soạn Đề Thi Trắc Nghiệm Thủ Công</h6>
                        <span class="text-muted text-xs">Biên soạn nội dung câu hỏi, định nghĩa 4 phương án trả lời và đánh dấu đáp án chính xác.</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('giang_vien.de_thi.tao_thu_cong') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    {{-- 1. Cấu hình chung của Đề thi --}}
                    <div class="card border rounded-3 p-3.5 mb-4 bg-light">
                        <h6 class="fw-bold text-dark text-sm mb-3 d-flex align-items-center gap-1.5">
                            <i class="fa-solid fa-sliders text-primary"></i> 1. Cấu hình chung cho Đề thi
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark text-xs">Gán cho bài học <span class="text-danger">*</span></label>
                                <select name="id_bai_hoc" id="selectBaiHocThuCong" class="form-select form-select-sm rounded-3" required>
                                    <option value="">-- Chọn bài học gán đề thi --</option>
                                    @foreach ($tatCaBaiHoc as $bh)
                                        <option value="{{ $bh->id_bai_hoc }}">
                                            [{{ $bh->chuongMuc->khoaHoc->tieu_de_khoa_hoc ?? 'Khóa học' }}] {{ $bh->tieu_de_bai_hoc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark text-xs">Tên đề thi trắc nghiệm <span class="text-danger">*</span></label>
                                <input type="text" name="tieu_de_de_thi" id="inputTieuDeThuCong" class="form-control form-control-sm rounded-3" placeholder="Ví dụ: Đề kiểm tra đánh giá kiến thức bài học" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark text-xs">Thời gian làm bài (phút)</label>
                                <input type="number" name="thoi_luong_lam_bai_phut" class="form-control form-control-sm rounded-3" value="15" min="1" max="180">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark text-xs">Điểm số đạt yêu cầu (/10)</label>
                                <input type="number" step="0.5" name="diem_so_qua_mon" class="form-control form-control-sm rounded-3" value="5.0" min="0" max="10">
                            </div>
                        </div>
                    </div>

                    {{-- 2. Danh sách câu hỏi trắc nghiệm (Hỗ trợ thêm nhiều câu) --}}
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-dark text-sm mb-0 d-flex align-items-center gap-1.5">
                            <i class="fa-solid fa-list-ol text-primary"></i> 2. Danh sách câu hỏi & 4 phương án lựa chọn
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold text-xs" onclick="themCauHoiThuCong()">
                            <i class="fa-solid fa-plus me-1"></i> + Thêm câu hỏi khác
                        </button>
                    </div>

                    <div id="containerCauHoiThuCong">
                        {{-- Mẫu câu hỏi đầu tiên (Index 0) --}}
                        <div class="card border rounded-3 p-3.5 mb-3 bg-light-subtle cau-hoi-block" id="cauHoiBlock_0">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                    Câu hỏi 1
                                </span>
                                <span class="text-muted text-xs">Bắt buộc nhập đủ 4 phương án</span>
                            </div>
                            <div class="mb-2.5">
                                <label class="form-label text-xs fw-bold text-dark mb-1">Nội dung câu hỏi <span class="text-danger">*</span></label>
                                <textarea name="cau_hoi[0][noi_dung]" rows="2" class="form-control form-control-sm rounded-3" placeholder="Ví dụ: Framework Laravel sử dụng mô hình kiến trúc nào sau đây?" required></textarea>
                            </div>
                            <label class="form-label text-xs fw-bold text-dark mb-1">4 Phương án (Tích chọn nút tròn để đánh dấu đáp án đúng) <span class="text-danger">*</span></label>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-text bg-white">
                                            <input class="form-check-input mt-0" type="radio" name="cau_hoi[0][dap_an_dung]" value="A" checked required title="Chọn làm đáp án đúng">
                                            <span class="ms-1.5 fw-bold text-dark text-xs">A.</span>
                                        </div>
                                        <input type="text" name="cau_hoi[0][phuong_an][A]" class="form-control" placeholder="Phương án A..." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-text bg-white">
                                            <input class="form-check-input mt-0" type="radio" name="cau_hoi[0][dap_an_dung]" value="B" required title="Chọn làm đáp án đúng">
                                            <span class="ms-1.5 fw-bold text-dark text-xs">B.</span>
                                        </div>
                                        <input type="text" name="cau_hoi[0][phuong_an][B]" class="form-control" placeholder="Phương án B..." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-text bg-white">
                                            <input class="form-check-input mt-0" type="radio" name="cau_hoi[0][dap_an_dung]" value="C" required title="Chọn làm đáp án đúng">
                                            <span class="ms-1.5 fw-bold text-dark text-xs">C.</span>
                                        </div>
                                        <input type="text" name="cau_hoi[0][phuong_an][C]" class="form-control" placeholder="Phương án C..." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-text bg-white">
                                            <input class="form-check-input mt-0" type="radio" name="cau_hoi[0][dap_an_dung]" value="D" required title="Chọn làm đáp án đúng">
                                            <span class="ms-1.5 fw-bold text-dark text-xs">D.</span>
                                        </div>
                                        <input type="text" name="cau_hoi[0][phuong_an][D]" class="form-control" placeholder="Phương án D..." required>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label text-xs fw-bold text-dark mb-1">Mức độ khó</label>
                                    <select name="cau_hoi[0][muc_do_kho]" class="form-select form-select-sm rounded-3">
                                        <option value="de">Dễ (Nhận biết)</option>
                                        <option value="trung_binh" selected>Trung bình (Thông hiểu)</option>
                                        <option value="kho">Khó (Vận dụng)</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label text-xs fw-bold text-dark mb-1">Giải thích đáp án</label>
                                    <input type="text" name="cau_hoi[0][giai_thich]" class="form-control form-control-sm rounded-3" placeholder="Giải thích vì sao đáp án này đúng...">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Nút Thêm câu hỏi phía dưới --}}
                    <div class="text-center my-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-1.5 text-xs fw-semibold" onclick="themCauHoiThuCong()">
                            <i class="fa-solid fa-plus me-1 text-primary"></i> + Thêm câu hỏi khác
                        </button>
                    </div>

                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 text-sm fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Đề Thi & Danh Sách Câu Hỏi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     6. MODAL AI TẠO ĐỀ THI THÔNG MINH (CHỐNG TRÙNG LẶP)
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalSinhDeAiThongMinh" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 p-2 bg-warning-subtle text-warning">
                        <i class="fa-solid fa-wand-magic-sparkles fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0">AI Tạo Đề Thi Thông Minh</h6>
                        <span class="text-muted text-xs">Sử dụng Google Gemini AI để sinh câu hỏi trắc nghiệm tự động</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('giang_vien.de_thi.sinh_ai_modal') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    {{-- 1. Chọn bài học --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Chọn bài học cần sinh đề thi <span class="text-danger">*</span></label>
                        <select name="id_bai_hoc" id="selectBaiHocAi" class="form-select rounded-3" required>
                            <option value="">-- Chọn bài học --</option>
                            @foreach ($tatCaBaiHoc as $bh)
                                <option value="{{ $bh->id_bai_hoc }}">
                                    {{ !$bh->deThi ? '⚡ [Chưa có đề] ' : '✓ [Đã có đề] ' }} [{{ $bh->chuongMuc->khoaHoc->tieu_de_khoa_hoc ?? 'Khóa' }}] {{ $bh->tieu_de_bai_hoc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Chọn số lượng câu hỏi --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Số lượng câu hỏi mong muốn</label>
                        <select name="so_luong_cau_hoi" class="form-select rounded-3">
                            <option value="3">3 câu hỏi trắc nghiệm</option>
                            <option value="4">4 câu hỏi trắc nghiệm (Tiêu chuẩn)</option>
                            <option value="5" selected>5 câu hỏi trắc nghiệm</option>
                            <option value="10">10 câu hỏi trắc nghiệm (Chuyên sâu)</option>
                        </select>
                    </div>

                    {{-- 3. Chọn độ khó ưu tiên --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark text-sm">Độ khó ưu tiên</label>
                        <select name="do_kho" class="form-select rounded-3">
                            <option value="hon_hop" selected>🎯 Hỗn hợp (Cân đối Dễ, Trung bình, Khó)</option>
                            <option value="de">🟢 Dễ (Nhận biết kiến thức căn bản)</option>
                            <option value="trung_binh">🟡 Trung bình (Thông hiểu và áp dụng)</option>
                            <option value="kho">🔴 Khó (Vận dụng thực tiễn & Phân tích chuyên sâu)</option>
                        </select>
                    </div>

                    {{-- 4. Thông báo tính năng Chống trùng lặp --}}
                    <div class="alert alert-primary bg-primary-subtle border-primary-subtle rounded-3 p-3 mb-0 text-xs">
                        <div class="d-flex gap-2.5">
                            <i class="fa-solid fa-shield-halved text-primary fs-5 mt-0.5"></i>
                            <div>
                                <strong class="text-primary d-block mb-1">Cơ chế Tự động Chống Trùng Lặp 100%</strong>
                                <span>Hệ thống tự động trích xuất toàn bộ câu hỏi đã có của bài học này và chuyển vào Prompt yêu cầu Gemini AI không được tạo trùng hoặc tương tự về nội dung. Các câu hỏi mới sẽ được nối thêm vào đề thi hiện tại.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 text-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 py-1.5 text-sm fw-semibold shadow-xs">
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Bắt đầu sinh đề AI ngay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     JAVASCRIPT: TABS ĐIỀU HƯỚNG & HỖ TRỢ MODALS
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
     * Mở Modal Soạn Đề Thủ Công và gán bài học được chọn
     */
    function moModalSoanThuCong(idBaiHoc, tenBaiHoc) {
        const select = document.getElementById('selectBaiHocThuCong');
        if (select) {
            select.value = idBaiHoc;
        }
        const inputTieuDe = document.getElementById('inputTieuDeThuCong');
        if (inputTieuDe && (!inputTieuDe.value || inputTieuDe.value.includes('Đề kiểm tra trắc nghiệm:'))) {
            inputTieuDe.value = 'Đề kiểm tra trắc nghiệm: ' + tenBaiHoc;
        }
        const modalEl = document.getElementById('modalTaoDeThuCong');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    /**
     * Mở Modal Sinh Đề AI và gán bài học được chọn
     */
    function moModalSinhDeAi(idBaiHoc, tenBaiHoc) {
        const select = document.getElementById('selectBaiHocAi');
        if (select) {
            select.value = idBaiHoc;
        }
        const modalEl = document.getElementById('modalSinhDeAiThongMinh');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    /**
     * Động hóa thêm câu hỏi thủ công
     */
    let cauHoiIndex = 1;

    function themCauHoiThuCong() {
        const container = document.getElementById('containerCauHoiThuCong');
        if (!container) return;

        const idx = cauHoiIndex++;
        const questionHtml = `
            <div class="card border rounded-3 p-3.5 mb-3 bg-light-subtle cau-hoi-block" id="cauHoiBlock_${idx}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 text-xs fw-semibold">
                        Câu hỏi ${idx + 1}
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-pill px-2 py-0.5 text-xs" onclick="xoaCauHoiThuCong(${idx})" title="Xóa câu hỏi này">
                        <i class="fa-solid fa-trash-can me-1"></i> Xóa
                    </button>
                </div>
                <div class="mb-2.5">
                    <label class="form-label text-xs fw-bold text-dark mb-1">Nội dung câu hỏi <span class="text-danger">*</span></label>
                    <textarea name="cau_hoi[${idx}][noi_dung]" rows="2" class="form-control form-control-sm rounded-3" placeholder="Nhập nội dung câu hỏi..." required></textarea>
                </div>
                <label class="form-label text-xs fw-bold text-dark mb-1">4 Phương án (Tích chọn nút tròn để đánh dấu đáp án đúng) <span class="text-danger">*</span></label>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <div class="input-group-text bg-white">
                                <input class="form-check-input mt-0" type="radio" name="cau_hoi[${idx}][dap_an_dung]" value="A" checked required title="Chọn làm đáp án đúng">
                                <span class="ms-1.5 fw-bold text-dark text-xs">A.</span>
                            </div>
                            <input type="text" name="cau_hoi[${idx}][phuong_an][A]" class="form-control" placeholder="Phương án A..." required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <div class="input-group-text bg-white">
                                <input class="form-check-input mt-0" type="radio" name="cau_hoi[${idx}][dap_an_dung]" value="B" required title="Chọn làm đáp án đúng">
                                <span class="ms-1.5 fw-bold text-dark text-xs">B.</span>
                            </div>
                            <input type="text" name="cau_hoi[${idx}][phuong_an][B]" class="form-control" placeholder="Phương án B..." required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <div class="input-group-text bg-white">
                                <input class="form-check-input mt-0" type="radio" name="cau_hoi[${idx}][dap_an_dung]" value="C" required title="Chọn làm đáp án đúng">
                                <span class="ms-1.5 fw-bold text-dark text-xs">C.</span>
                            </div>
                            <input type="text" name="cau_hoi[${idx}][phuong_an][C]" class="form-control" placeholder="Phương án C..." required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <div class="input-group-text bg-white">
                                <input class="form-check-input mt-0" type="radio" name="cau_hoi[${idx}][dap_an_dung]" value="D" required title="Chọn làm đáp án đúng">
                                <span class="ms-1.5 fw-bold text-dark text-xs">D.</span>
                            </div>
                            <input type="text" name="cau_hoi[${idx}][phuong_an][D]" class="form-control" placeholder="Phương án D..." required>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label text-xs fw-bold text-dark mb-1">Mức độ khó</label>
                        <select name="cau_hoi[${idx}][muc_do_kho]" class="form-select form-select-sm rounded-3">
                            <option value="de">Dễ (Nhận biết)</option>
                            <option value="trung_binh" selected>Trung bình (Thông hiểu)</option>
                            <option value="kho">Khó (Vận dụng)</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label text-xs fw-bold text-dark mb-1">Giải thích đáp án</label>
                        <input type="text" name="cau_hoi[${idx}][giai_thich]" class="form-control form-control-sm rounded-3" placeholder="Giải thích vì sao đáp án này đúng...">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', questionHtml);
    }

    function xoaCauHoiThuCong(idx) {
        const el = document.getElementById('cauHoiBlock_' + idx);
        if (el) {
            el.remove();
        }
    }

    // Tự động kiểm tra URL khi tải trang
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam && ['tong-quan', 'khoa-hoc', 'de-thi', 'ket-qua'].includes(tabParam)) {
            const targetBtn = document.getElementById('btn-tab-' + tabParam);
            if (targetBtn && !targetBtn.classList.contains('active')) {
                const tabInstance = new bootstrap.Tab(targetBtn);
                tabInstance.show();
            }
        }

        // Dừng video khi đóng modal xem trước video
        document.querySelectorAll('.modal-preview-video').forEach(function (modal) {
            modal.addEventListener('hidden.bs.modal', function () {
                const iframe = this.querySelector('iframe');
                if (iframe) {
                    const temp = iframe.src;
                    iframe.src = '';
                    iframe.src = temp;
                }
                const video = this.querySelector('video');
                if (video) {
                    video.pause();
                }
            });
        });
    });
</script>
@endpush

@endsection
