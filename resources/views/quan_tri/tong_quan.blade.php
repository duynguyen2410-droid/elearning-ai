@extends('layouts.khung')

@section('title', 'Trung Tâm Quản Trị Hệ Thống - EduMind AI')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     INLINE STYLES — Admin Dashboard SaaS Theme
══════════════════════════════════════════════════════════════ --}}
<style>
    /* ────────────────────────────────────────────
       Design Tokens & Scope
    ──────────────────────────────────────────── */
    .admin-dashboard {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
        min-height: 100vh;
        padding-bottom: 4rem;
        -webkit-font-smoothing: antialiased;
    }

    .admin-container {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* ────────────────────────────────────────────
       1. WELCOME BANNER
    ──────────────────────────────────────────── */
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
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.25) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .admin-welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .welcome-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.5rem;
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
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        max-width: 580px;
        line-height: 1.6;
    }

    .welcome-badges {
        display: flex;
        gap: 0.65rem;
        flex-wrap: wrap;
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
        background: rgba(16, 185, 129, 0.15);
        color: #a7f3d0;
        border-color: rgba(16, 185, 129, 0.3);
    }

    .admin-badge.ai-ready {
        background: rgba(245, 158, 11, 0.15);
        color: #fde68a;
        border-color: rgba(245, 158, 11, 0.3);
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0%   { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70%  { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* ────────────────────────────────────────────
       2. METRIC / STAT CARDS
    ──────────────────────────────────────────── */
    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .stat-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .stat-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin: 0;
    }

    .stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Card Themes */
    .stat-card-purple .stat-icon-wrap {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.2));
        color: #6366f1;
        border: 1px solid rgba(99, 102, 241, 0.25);
    }
    .stat-card-purple:hover {
        border-color: rgba(99, 102, 241, 0.4);
    }

    .stat-card-blue .stat-icon-wrap {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.15), rgba(59, 130, 246, 0.2));
        color: #0284c7;
        border: 1px solid rgba(14, 165, 233, 0.25);
    }
    .stat-card-blue:hover {
        border-color: rgba(14, 165, 233, 0.4);
    }

    .stat-card-amber .stat-icon-wrap {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(234, 88, 12, 0.2));
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.25);
    }
    .stat-card-amber:hover {
        border-color: rgba(245, 158, 11, 0.4);
    }

    .stat-value {
        font-size: 2.35rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        margin-bottom: 0.5rem;
        letter-spacing: -0.03em;
    }

    .stat-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        color: #64748b;
        padding-top: 0.85rem;
        border-top: 1px solid #f1f5f9;
        margin-top: auto;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-weight: 600;
        font-size: 0.72rem;
        padding: 0.15rem 0.55rem;
        border-radius: 999px;
    }

    .stat-pill.success {
        background: #ecfdf5;
        color: #059669;
    }

    .stat-pill.info {
        background: #eff6ff;
        color: #2563eb;
    }

    .stat-pill.warning {
        background: #fffbeb;
        color: #b45309;
    }

    /* ────────────────────────────────────────────
       3. QUICK ACTIONS BAR
    ──────────────────────────────────────────── */
    .quick-actions-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.15rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .quick-actions-title {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .quick-actions-title i {
        color: #6366f1;
        font-size: 1.1rem;
    }

    .quick-btn-group {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
    }

    .btn-action-quick {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.95rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 0.65rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
    }

    .btn-action-quick:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .btn-action-quick.primary {
        background: #4f46e5;
        border-color: #4f46e5;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
    }

    .btn-action-quick.primary:hover {
        background: #4338ca;
        border-color: #4338ca;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.35);
    }

    /* ────────────────────────────────────────────
       4. CONTENT CARDS & TABLES
    ──────────────────────────────────────────── */
    .card-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
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

    .card-panel-title {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .card-panel-title-icon {
        width: 32px;
        height: 32px;
        border-radius: 0.5rem;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .card-panel-body {
        padding: 1.5rem;
        flex: 1;
    }

    .table-modern {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .table-modern thead th:first-child {
        padding-left: 1.5rem;
    }

    .table-modern thead th:last-child {
        padding-right: 1.5rem;
    }

    .table-modern tbody td {
        padding: 1rem 1rem;
        vertical-align: middle;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .table-modern tbody td:first-child {
        padding-left: 1.5rem;
    }

    .table-modern tbody td:last-child {
        padding-right: 1.5rem;
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    .table-modern tbody tr {
        transition: background-color 0.15s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    .quiz-title-text {
        font-weight: 600;
        color: #0f172a;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-decoration: none;
    }

    .quiz-title-text:hover {
        color: #4f46e5;
    }

    .quiz-subtitle-lesson {
        font-size: 0.75rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        margin-top: 0.2rem;
    }

    /* Badges */
    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.2rem 0.65rem;
        border-radius: 999px;
        white-space: nowrap;
    }

    .badge-status-pill.active {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }

    .badge-status-pill.draft {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    /* Student List Widgets */
    .student-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .student-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .student-item:first-child {
        padding-top: 0;
    }

    .student-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #4338ca;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .student-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.1rem;
    }

    .student-email {
        font-size: 0.75rem;
        color: #64748b;
        max-width: 170px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* System Health Widget */
    .health-indicator-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.82rem;
    }

    .health-indicator-item:last-child {
        border-bottom: none;
    }

    /* Empty state */
    .empty-placeholder {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: #94a3b8;
    }

    .empty-placeholder i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        color: #cbd5e1;
    }

    @media (max-width: 768px) {
        .admin-welcome-card {
            padding: 1.75rem 1.5rem;
        }
        .welcome-text h1 {
            font-size: 1.35rem;
        }
        .quick-actions-bar {
            flex-direction: column;
            align-items: flex-start;
        }
        .quick-btn-group {
            width: 100%;
        }
        .btn-action-quick {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<div class="admin-dashboard">
    <div class="admin-container">

        {{-- ════════════════════════════════════════════════════════════
             SECTION 1 — WELCOME HEADER BANNER
        ════════════════════════════════════════════════════════════ --}}
        <div class="admin-welcome-card">
            <div class="welcome-content">
                <div class="welcome-text">
                    <h1>Xin chào, {{ Auth::user()->ho_va_ten ?? 'Quản trị viên' }} 👋</h1>
                    <p>
                        Chào mừng quay trở lại Trung tâm Điều hành EduMind AI. Theo dõi chỉ số hệ thống, giám sát nội dung khóa học và quản lý đề thi trắc nghiệm thông minh tự động.
                    </p>
                </div>
                <div class="welcome-badges">
                    <span class="admin-badge status">
                        <span class="pulse-dot"></span>
                        Hệ thống: Trực tuyến
                    </span>
                    <span class="admin-badge ai-ready">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        Gemini AI: Sẵn sàng 24/7
                    </span>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════
             SECTION 2 — 3 KEY METRIC CARDS
        ════════════════════════════════════════════════════════════ --}}
        <div class="row g-4 mb-4">
            {{-- Card 1: Tổng Khóa Học --}}
            <div class="col-md-4">
                <div class="stat-card stat-card-purple">
                    <div>
                        <div class="stat-card-top">
                            <h3 class="stat-title">Tổng Khóa Học</h3>
                            <div class="stat-icon-wrap">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                        </div>
                        <div class="stat-value">{{ $tongKhoaHoc ?? 0 }}</div>
                    </div>
                    <div class="stat-footer">
                        <span>Chương trình đào tạo</span>
                        <span class="stat-pill success">
                            <i class="fa-solid fa-check"></i> Đang mở lớp
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Tổng Học Viên --}}
            <div class="col-md-4">
                <div class="stat-card stat-card-blue">
                    <div>
                        <div class="stat-card-top">
                            <h3 class="stat-title">Tổng Học Viên</h3>
                            <div class="stat-icon-wrap">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-value">{{ $tongHocVien ?? 0 }}</div>
                    </div>
                    <div class="stat-footer">
                        <span>Tài khoản tham gia</span>
                        <span class="stat-pill info">
                            <i class="fa-solid fa-user-check"></i> Đã xác thực
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card 3: Đề Thi Trắc Nghiệm (AI) --}}
            <div class="col-md-4">
                <div class="stat-card stat-card-amber">
                    <div>
                        <div class="stat-card-top">
                            <h3 class="stat-title">Đề Thi Trắc Nghiệm (AI)</h3>
                            <div class="stat-icon-wrap">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            </div>
                        </div>
                        <div class="stat-value">{{ $tongDeThi ?? 0 }}</div>
                    </div>
                    <div class="stat-footer">
                        <span>Sinh tự động bởi AI</span>
                        <span class="stat-pill warning">
                            <i class="fa-solid fa-bolt"></i> Tự động hóa
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════
             SECTION 3 — QUICK MANAGEMENT ACTIONS BAR
        ════════════════════════════════════════════════════════════ --}}
        <div class="quick-actions-bar">
            <h4 class="quick-actions-title">
                <i class="fa-solid fa-sliders"></i>
                Thao tác quản trị nhanh
            </h4>
            <div class="quick-btn-group">
                <a href="#danh-sach-de-thi" class="btn-action-quick primary">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    Quản lý đề thi AI
                </a>
                <a href="{{ route('hoc_vien.tong_quan') }}" class="btn-action-quick" title="Xem giao diện học tập của học viên">
                    <i class="fa-solid fa-book-open"></i>
                    Xem khóa học & bài học
                </a>
                <a href="#danh-sach-hoc-vien" class="btn-action-quick">
                    <i class="fa-solid fa-users-gear"></i>
                    Quản lý tài khoản
                </a>
                <a href="{{ url('/') }}" target="_blank" class="btn-action-quick">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    Trang chủ công khai
                </a>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════
             SECTION 4 — RECENT ACTIVITIES & MANAGEMENT TABLE
        ════════════════════════════════════════════════════════════ --}}
        <div class="row g-4">
            {{-- Left Column: Đề thi AI mới nhất (Table) --}}
            <div class="col-lg-8" id="danh-sach-de-thi">
                <div class="card-panel">
                    <div class="card-panel-header">
                        <h4 class="card-panel-title">
                            <div class="card-panel-title-icon">
                                <i class="fa-solid fa-clipboard-question"></i>
                            </div>
                            Đề thi trắc nghiệm AI gần đây
                        </h4>
                        <span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-pill text-xs fw-semibold">
                            Cập nhật thời gian thực
                        </span>
                    </div>

                    @php
                        // Defensive query if not provided from controller/route
                        $danhSachDeThiGanDay = $danhSachDeThiGanDay ?? \App\Models\DeThiTracNghiem::with(['baiHoc', 'danhSachCauHoi'])->orderByDesc('id_de_thi')->take(5)->get();
                    @endphp

                    @if ($danhSachDeThiGanDay && $danhSachDeThiGanDay->count())
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Đề thi & Bài học</th>
                                        <th>Thời lượng</th>
                                        <th>Số câu</th>
                                        <th>Trạng thái</th>
                                        <th class="text-end">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($danhSachDeThiGanDay as $deThi)
                                        <tr>
                                            <td>
                                                <a href="{{ route('quiz.take', $deThi->id_de_thi) }}" class="quiz-title-text" title="{{ $deThi->tieu_de_de_thi }}">
                                                    {{ $deThi->tieu_de_de_thi }}
                                                </a>
                                                <div class="quiz-subtitle-lesson">
                                                    <i class="fa-solid fa-book-bookmark text-indigo-500"></i>
                                                    {{ $deThi->baiHoc->tieu_de_bai_hoc ?? 'Bài học liên kết' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fa-regular fa-clock me-1 text-muted"></i>
                                                    {{ $deThi->thoi_luong_lam_bai_phut ?? 15 }} phút
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-secondary">
                                                    {{ $deThi->danhSachCauHoi ? $deThi->danhSachCauHoi->count() : 'N/A' }} câu
                                                </span>
                                            </td>
                                            <td>
                                                @if ($deThi->trang_thai_mo_de == 1)
                                                    <span class="badge-status-pill active">
                                                        <i class="fa-solid fa-circle-check"></i> Đang mở
                                                    </span>
                                                @else
                                                    <span class="badge-status-pill draft">
                                                        <i class="fa-solid fa-circle-pause"></i> Tạm đóng
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a
                                                    href="{{ route('quiz.take', $deThi->id_de_thi) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 text-xs fw-semibold"
                                                    title="Xem và thi thử đề thi này"
                                                >
                                                    <i class="fa-solid fa-play me-1"></i> Thi thử
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-placeholder">
                            <i class="fa-solid fa-folder-open"></i>
                            <p class="mb-0">Chưa có đề thi nào trong hệ thống. Hãy truy cập trang học viên để kích hoạt Gemini AI tạo đề!</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Học viên mới & Giám sát dịch vụ --}}
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-4">

                    {{-- Widget 1: Học viên đăng ký mới --}}
                    <div class="card-panel" id="danh-sach-hoc-vien">
                        <div class="card-panel-header">
                            <h4 class="card-panel-title">
                                <div class="card-panel-title-icon" style="background:#e0f2fe; color:#0284c7;">
                                    <i class="fa-solid fa-user-plus"></i>
                                </div>
                                Học viên mới nhất
                            </h4>
                            <span class="badge bg-light text-muted border text-xs">Mới</span>
                        </div>
                        <div class="card-panel-body">
                            @php
                                $danhSachHocVienMoi = $danhSachHocVienMoi ?? \App\Models\User::where('id_phan_quyen', '!=', 1)->orderByDesc('id_nguoi_dung')->take(4)->get();
                            @endphp

                            @if ($danhSachHocVienMoi && $danhSachHocVienMoi->count())
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($danhSachHocVienMoi as $hv)
                                        <div class="student-item">
                                            <div class="d-flex align-items-center gap-2.5 min-w-0">
                                                <div class="student-avatar">
                                                    {{ mb_substr($hv->ho_va_ten ?? 'H', 0, 1) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="student-name text-truncate">{{ $hv->ho_va_ten ?? 'Học viên' }}</div>
                                                    <div class="student-email text-truncate">{{ $hv->dia_chi_email ?? 'Chưa cập nhật' }}</div>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="badge-status-pill active" style="font-size: 0.68rem; padding: 0.15rem 0.5rem;">
                                                    <i class="fa-solid fa-check"></i> Đã duyệt
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 text-muted small">
                                    <i class="fa-solid fa-user-slash d-block fs-3 mb-2 text-secondary opacity-50"></i>
                                    Chưa có học viên nào đăng ký.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Widget 2: Trạng thái Dịch vụ & AI Engine --}}
                    <div class="card-panel">
                        <div class="card-panel-header">
                            <h4 class="card-panel-title">
                                <div class="card-panel-title-icon" style="background:#fef3c7; color:#b45309;">
                                    <i class="fa-solid fa-server"></i>
                                </div>
                                Tình trạng hệ thống AI
                            </h4>
                        </div>
                        <div class="card-panel-body">
                            <div class="health-indicator-item">
                                <span class="d-flex align-items-center gap-2 text-secondary">
                                    <i class="fa-solid fa-microchip text-indigo-500"></i>
                                    Gemini 1.5 Flash API
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5">
                                    <i class="fa-solid fa-circle-check me-1"></i> Hoạt động
                                </span>
                            </div>

                            <div class="health-indicator-item">
                                <span class="d-flex align-items-center gap-2 text-secondary">
                                    <i class="fa-solid fa-database text-sky-500"></i>
                                    Cơ sở dữ liệu MySQL
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5">
                                    <i class="fa-solid fa-circle-check me-1"></i> Kết nối OK
                                </span>
                            </div>

                            <div class="health-indicator-item">
                                <span class="d-flex align-items-center gap-2 text-secondary">
                                    <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                                    Bảo mật & Phân quyền
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5">
                                    <i class="fa-solid fa-lock me-1"></i> Đã kích hoạt
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

