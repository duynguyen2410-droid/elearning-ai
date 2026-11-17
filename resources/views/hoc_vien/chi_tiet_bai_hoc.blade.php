@extends('layouts.khung')

@section('title', ($baiHoc->tieu_de_bai_hoc ?? 'Chi tiết bài học') . ' - EduMind AI')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     INLINE STYLES — Lesson Detail SaaS Layout
══════════════════════════════════════════════════════════════ --}}
<style>
    /* ────────────────────────────────────────────
       Scope & Variables
    ──────────────────────────────────────────── */
    .lesson-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
        min-height: 100vh;
        padding-top: 0.5rem;
        padding-bottom: 4rem;
        -webkit-font-smoothing: antialiased;
    }

    .lesson-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* ────────────────────────────────────────────
       1. BREADCRUMB
    ──────────────────────────────────────────── */
    .lesson-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .lesson-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .lesson-breadcrumb a:hover {
        color: #4f46e5;
    }

    .lesson-breadcrumb .separator {
        color: #cbd5e1;
        font-size: 0.75rem;
    }

    .lesson-breadcrumb .current {
        color: #0f172a;
        font-weight: 600;
        max-width: 320px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ────────────────────────────────────────────
       2. VIDEO PLAYER / MEDIA DISPLAY
    ──────────────────────────────────────────── */
    .lesson-media-wrapper {
        background: #0f172a;
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.3);
        margin-bottom: 1.75rem;
        position: relative;
        aspect-ratio: 16 / 9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lesson-video-player {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .lesson-video-iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .video-placeholder-box {
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, #1e1b4b 0%, #0f172a 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        padding: 2rem;
        text-align: center;
        position: relative;
    }

    .video-placeholder-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #ffffff;
        box-shadow: 0 0 30px rgba(99, 102, 241, 0.5);
        margin-bottom: 1rem;
        transition: transform 0.25s ease;
    }

    .video-placeholder-box:hover .video-placeholder-icon {
        transform: scale(1.08);
    }

    /* ────────────────────────────────────────────
       3. LESSON HEADER & METADATA
    ──────────────────────────────────────────── */
    .lesson-header-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.75rem 2rem;
        margin-bottom: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .lesson-title-main {
        font-size: 1.55rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.35;
        margin-bottom: 0.85rem;
    }

    .lesson-meta-bar {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
        font-size: 0.85rem;
        color: #64748b;
    }

    .meta-item {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 500;
    }

    .meta-item i {
        color: #4f46e5;
        font-size: 0.85rem;
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .meta-badge.duration {
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }

    .meta-badge.free {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }

    /* ────────────────────────────────────────────
       4. AI SUMMARY WIDGET (Gemini Highlight)
    ──────────────────────────────────────────── */
    .ai-summary-card {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.04) 0%, rgba(99, 102, 241, 0.04) 100%);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 1.25rem;
        padding: 1.5rem;
        margin-bottom: 1.75rem;
        position: relative;
    }

    .ai-summary-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.65rem;
        color: #b45309;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .ai-summary-header i {
        color: #f59e0b;
        font-size: 1rem;
    }

    .ai-summary-content {
        font-size: 0.88rem;
        color: #334155;
        line-height: 1.65;
        margin: 0;
    }

    /* ────────────────────────────────────────────
       5. LESSON MAIN CONTENT
    ──────────────────────────────────────────── */
    .lesson-content-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 2rem;
        margin-bottom: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .content-heading {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .content-heading i {
        color: #4f46e5;
    }

    .lesson-body {
        font-size: 0.95rem;
        line-height: 1.8;
        color: #334155;
    }

    .lesson-body p {
        margin-bottom: 1rem;
    }

    .lesson-body p:last-child {
        margin-bottom: 0;
    }

    /* ────────────────────────────────────────────
       6. ACTION FOOTER
    ──────────────────────────────────────────── */
    .lesson-action-footer {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.25rem 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .btn-nav-course {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-nav-course:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .btn-quiz-take-main {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.45rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #ffffff;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border: 1px solid #4f46e5;
        border-radius: 0.75rem;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        transition: all 0.2s ease;
    }

    .btn-quiz-take-main:hover {
        background: linear-gradient(135deg, #4338ca, #4f46e5);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
    }

    .btn-ai-generate-main {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.45rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #b45309;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-ai-generate-main:hover {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);
    }

    /* ────────────────────────────────────────────
       7. PLAYLIST SIDEBAR (Right Area)
    ──────────────────────────────────────────── */
    .playlist-sidebar-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 5.5rem;
        overflow: hidden;
    }

    .playlist-header {
        padding: 1.25rem 1.5rem;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .playlist-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .playlist-title i {
        color: #4f46e5;
    }

    .playlist-count {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
    }

    .playlist-body {
        max-height: calc(100vh - 12rem);
        overflow-y: auto;
    }

    .chapter-heading-row {
        padding: 0.75rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .playlist-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        color: #334155;
        transition: all 0.2s ease;
        gap: 0.75rem;
    }

    .playlist-item:last-child {
        border-bottom: none;
    }

    .playlist-item:hover {
        background-color: rgba(79, 70, 229, 0.03);
        color: #4f46e5;
    }

    .playlist-item.active {
        background-color: #eef2ff;
        color: #4338ca;
        border-left: 3px solid #4f46e5;
        font-weight: 600;
    }

    .playlist-item-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 0;
        flex: 1;
    }

    .playlist-item-icon {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .playlist-item:hover .playlist-item-icon {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .playlist-item.active .playlist-item-icon {
        background: #4f46e5;
        color: #ffffff;
    }

    .playlist-item-title {
        font-size: 0.825rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .playlist-item-meta {
        font-size: 0.72rem;
        color: #94a3b8;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .playlist-item.active .playlist-item-meta {
        color: #6366f1;
    }

    @media (max-width: 991px) {
        .playlist-sidebar-card {
            position: static;
            margin-top: 2rem;
        }
        .playlist-body {
            max-height: 400px;
        }
    }
</style>

{{-- Current Lesson ID Meta for AI Chatbot Context --}}
<meta name="lesson-id" content="{{ $baiHoc->id_bai_hoc }}">

<div class="lesson-page">
    <div class="lesson-container">

        {{-- ════════════════════════════════════════════════════════════
             BREADCRUMB
        ════════════════════════════════════════════════════════════ --}}
        <nav class="lesson-breadcrumb d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ url('/') }}">
                    <i class="fa-solid fa-house"></i> Trang chủ
                </a>
                <span class="separator"><i class="fa-solid fa-chevron-right"></i></span>
                @if(Auth::check() && Auth::user()->id_phan_quyen == 2)
                    <a href="{{ route('giang_vien.tong_quan') }}">
                        <i class="fa-solid fa-chalkboard-user"></i> Bàn làm việc Giảng viên
                    </a>
                @else
                    <a href="{{ route('hoc_vien.tong_quan') }}">
                        <i class="fa-solid fa-graduation-cap"></i>
                        {{ $khoaHoc->tieu_de_khoa_hoc ?? $khoaHoc->ten_khoa_hoc ?? 'Khóa học của bạn' }}
                    </a>
                @endif
                @if ($chuongMuc)
                    <span class="separator"><i class="fa-solid fa-chevron-right"></i></span>
                    <span class="text-secondary">{{ $chuongMuc->tieu_de_chuong_muc ?? $chuongMuc->ten_chuong_muc }}</span>
                @endif
                <span class="separator"><i class="fa-solid fa-chevron-right"></i></span>
                <span class="current" title="{{ $baiHoc->tieu_de_bai_hoc }}">{{ $baiHoc->tieu_de_bai_hoc }}</span>
            </div>
            @if(Auth::check() && Auth::user()->id_phan_quyen == 2)
                <span class="badge py-1.5 px-3 rounded-pill text-xs d-none d-md-inline-flex align-items-center gap-1.5 shadow-xs" style="background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe;">
                    <i class="fa-solid fa-eye text-primary"></i> Chế độ xem trước của Giảng viên
                </span>
            @endif
        </nav>

        <div class="row g-4">
            {{-- ════════════════════════════════════════════════════════
                 LEFT / CENTER COLUMN: Video Player & Main Content
            ════════════════════════════════════════════════════════ --}}
            <div class="col-lg-8">

                {{-- Video Player Area --}}
                <div class="lesson-media-wrapper">
                    @php
                        $videoUrl = $baiHoc->duong_dan_video_bai_giang ?? '';
                        $isYoutube = str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be');
                        $isDirectVideo = str_ends_with(strtolower($videoUrl), '.mp4') || str_ends_with(strtolower($videoUrl), '.webm');
                    @endphp

                    @if ($isYoutube)
                        @php
                            // Extract YouTube Video ID (hỗ trợ cả watch?v=, youtu.be, embed, shorts)
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match);
                            $youtubeId = $match[1] ?? '';
                        @endphp
                        @if ($youtubeId)
                            <iframe
                                class="lesson-video-iframe"
                                src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=0&rel=0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        @else
                            <div class="video-placeholder-box">
                                <div class="video-placeholder-icon"><i class="fa-solid fa-play"></i></div>
                                <h5 class="fw-bold mb-1">{{ $baiHoc->tieu_de_bai_hoc }}</h5>
                                <p class="text-white-50 small mb-0">Video bài giảng YouTube</p>
                            </div>
                        @endif
                    @elseif ($isDirectVideo)
                        <video class="lesson-video-player" controls preload="metadata">
                            <source src="{{ str_starts_with($videoUrl, 'http') ? $videoUrl : asset($videoUrl) }}" type="video/mp4">
                            Trình duyệt của bạn không hỗ trợ thẻ video HTML5.
                        </video>
                    @else
                        {{-- Aesthetic video / visual banner placeholder --}}
                        <div class="video-placeholder-box">
                            <div class="video-placeholder-icon">
                                <i class="fa-solid fa-play"></i>
                            </div>
                            <h4 class="fw-bold mb-2">{{ $baiHoc->tieu_de_bai_hoc }}</h4>
                            <p class="text-white-50 small mb-0">
                                @if ($videoUrl)
                                    <i class="fa-solid fa-link me-1"></i> Đường dẫn bài giảng: <a href="{{ $videoUrl }}" target="_blank" class="text-white text-decoration-underline">{{ $videoUrl }}</a>
                                @else
                                    Khóa học trực tuyến EduMind AI — Hệ thống bài giảng kèm trợ lý ôn thi thông minh
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Lesson Header & Metadata Card --}}
                <div class="lesson-header-card">
                    <h1 class="lesson-title-main">{{ $baiHoc->tieu_de_bai_hoc }}</h1>
                    <div class="lesson-meta-bar">
                        {{-- Instructor --}}
                        <div class="meta-item">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            <span>Giảng viên: <strong>{{ $khoaHoc->giangVien->ho_va_ten ?? 'Giảng viên chuyên gia' }}</strong></span>
                        </div>

                        {{-- Duration --}}
                        <div class="meta-item">
                            <span class="meta-badge duration">
                                <i class="fa-regular fa-clock"></i>
                                @php
                                    $tongGiay = (int) ($baiHoc->thoi_luong_video_giay ?? $baiHoc->thoi_luong ?? 0);
                                    $phut = floor($tongGiay / 60);
                                    $giay = $tongGiay % 60;
                                @endphp
                                @if ($tongGiay > 0)
                                    @if ($phut > 0 && $giay > 0)
                                        {{ $phut }} phút {{ $giay }} giây
                                    @elseif ($phut > 0)
                                        {{ $phut }} phút
                                    @else
                                        {{ $giay }} giây
                                    @endif
                                @else
                                    {{ $baiHoc->thoi_luong_hien_thi ?? '0 phút' }}
                                @endif
                            </span>
                        </div>



                        {{-- Lesson Type --}}
                        <div class="meta-item ms-auto text-secondary small">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>{{ ucfirst($baiHoc->loai_bai_hoc ?? 'Video bài học') }}</span>
                        </div>
                    </div>
                </div>

                {{-- AI Summary Card (Gemini 1.5 Highlight) --}}
                @if (!empty($baiHoc->tom_tat_kien_thuc_cho_ai))
                    <div class="ai-summary-card">
                        <div class="ai-summary-header">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>Tóm tắt cốt lõi từ Trợ lý AI Gemini</span>
                        </div>
                        <p class="ai-summary-content">
                            {{ $baiHoc->tom_tat_kien_thuc_cho_ai }}
                        </p>
                    </div>
                @endif

                {{-- Main Lesson Content Card --}}
                <div class="lesson-content-card">
                    <h2 class="content-heading">
                        <i class="fa-solid fa-book-open-reader"></i>
                        Nội dung chi tiết bài học
                    </h2>
                    <div class="lesson-body">
                        @if (!empty($baiHoc->noi_dung_bai_hoc_chi_tiet))
                            {!! nl2br(e($baiHoc->noi_dung_bai_hoc_chi_tiet)) !!}
                        @elseif (!empty($baiHoc->mo_ta_bai_hoc))
                            {!! nl2br(e($baiHoc->mo_ta_bai_hoc)) !!}
                        @else
                            <p class="text-secondary fst-italic">
                                Bài học này tập trung vào thực hành và theo dõi video hướng dẫn ở trên. Sau khi xem xong, bạn có thể thực hiện bài kiểm tra trắc nghiệm do AI tạo để đánh giá mức độ tiếp thu!
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Action Footer --}}
                @php
                    $quiz = $baiHoc->deThi ?? $baiHoc->deThiTracNghiem ?? ($baiHoc->danhSachDeThi ? $baiHoc->danhSachDeThi->first() : null);
                @endphp

                <div class="lesson-action-footer">
                    {{-- Quay lại khóa học / Bàn làm việc --}}
                    @if(Auth::check() && Auth::user()->id_phan_quyen == 2)
                        <a href="{{ route('giang_vien.tong_quan') }}" class="btn-nav-course">
                            <i class="fa-solid fa-arrow-left"></i>
                            Quay lại Bàn làm việc Giảng viên
                        </a>
                    @else
                        <a href="{{ route('hoc_vien.tong_quan') }}" class="btn-nav-course">
                            <i class="fa-solid fa-arrow-left"></i>
                            Quay lại khóa học
                        </a>
                    @endif

                    {{-- Quiz Action Button --}}
                    <div class="d-flex align-items-center gap-2">
                        @if (Auth::check() && Auth::user()->id_phan_quyen == 2)
                            <a href="{{ route('giang_vien.tong_quan', ['tab' => 'de-thi']) }}" class="btn-quiz-take-main" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Quản lý đề thi bài này
                            </a>
                        @elseif ($quiz)
                            <a href="{{ route('quiz.take', $quiz->id_de_thi) }}" class="btn-quiz-take-main">
                                <i class="fa-solid fa-file-pen"></i>
                                Làm bài kiểm tra ngay
                            </a>
                        @else
                            <button
                                type="button"
                                class="btn-ai-generate-main"
                                data-bs-toggle="modal"
                                data-bs-target="#modalHocVienTaoDeAi_{{ $baiHoc->id_chi_tiet_bai_hoc ?? $baiHoc->id_bai_hoc }}"
                            >
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                AI Tạo Đề Thi
                            </button>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ════════════════════════════════════════════════════════
                 RIGHT COLUMN: Course Curriculum / Playlist Sidebar
            ════════════════════════════════════════════════════════ --}}
            <div class="col-lg-4">
                <div class="playlist-sidebar-card">
                    <div class="playlist-header">
                        <h3 class="playlist-title">
                            <i class="fa-solid fa-list-check"></i>
                            Nội dung khóa học
                        </h3>
                        @php
                            $currentUser = Auth::user();
                            $canViewAll = ($isOwnerOrAdmin ?? false) || ($daDangKy ?? false);
                            if (!isset($isOwnerOrAdmin) && $currentUser) {
                                $isOwnerOrAdmin = ((int)$currentUser->id_phan_quyen === 1) ||
                                    ($khoaHoc && ((int)$khoaHoc->id_giang_vien_phu_trach === (int)$currentUser->id_nguoi_dung || (int)($khoaHoc->id_nguoi_tao ?? 0) === (int)$currentUser->id_nguoi_dung));
                                $daDangKy = $khoaHoc ? \App\Models\GhiDanhKhoaHoc::where('id_hoc_vien', $currentUser->id_nguoi_dung)->where('id_khoa_hoc', $khoaHoc->id_khoa_hoc)->exists() : false;
                                $canViewAll = $isOwnerOrAdmin || $daDangKy;
                            }

                            $totalLessonsCount = 0;
                            if (isset($khoaHoc->danhSachChuongMuc)) {
                                foreach ($khoaHoc->danhSachChuongMuc as $ch) {
                                    if ($ch->danhSachBaiHoc) {
                                        $totalLessonsCount += $ch->danhSachBaiHoc->count();
                                    }
                                }
                            }
                            if ($totalLessonsCount === 0 && $danhSachBaiHoc) {
                                $totalLessonsCount = $danhSachBaiHoc->count();
                            }
                        @endphp
                        <span class="playlist-count">
                            {{ $totalLessonsCount }} bài học
                        </span>
                    </div>

                    <div class="playlist-body">
                        @if (isset($khoaHoc->danhSachChuongMuc) && $khoaHoc->danhSachChuongMuc->count())
                            @foreach ($khoaHoc->danhSachChuongMuc as $idxChuong => $chapter)
                                @php
                                    $chapterLessons = $chapter->danhSachBaiHoc ? $chapter->danhSachBaiHoc->values() : collect();
                                @endphp

                                @if ($chapterLessons->count())
                                    <div class="chapter-heading-row">
                                        {{ $chapter->tieu_de_chuong_muc ?? ('Chương ' . ($idxChuong + 1)) }}
                                    </div>

                                    @foreach ($chapterLessons as $idxLesson => $lessonItem)
                                        @php
                                            $isActive = ($lessonItem->id_bai_hoc == $baiHoc->id_bai_hoc);
                                            $hasQuiz = $lessonItem->deThi || $lessonItem->deThiTracNghiem;
                                        @endphp
                                        <a
                                            href="{{ route('student.lesson.show', $lessonItem->id_bai_hoc) }}"
                                            class="playlist-item {{ $isActive ? 'active' : '' }}"
                                        >
                                            <div class="playlist-item-left">
                                                <div class="playlist-item-icon">
                                                    @if ($isActive)
                                                        <i class="fa-solid fa-play"></i>
                                                    @elseif ($hasQuiz)
                                                        <i class="fa-solid fa-circle-check text-success"></i>
                                                    @else
                                                        {{ $idxLesson + 1 }}
                                                    @endif
                                                </div>
                                                <span class="playlist-item-title" title="{{ $lessonItem->tieu_de_bai_hoc }}">
                                                    {{ $lessonItem->tieu_de_bai_hoc }}
                                                </span>
                                            </div>

                                            <div class="playlist-item-meta">
                                                @php
                                                    $itemSec = (int) ($lessonItem->thoi_luong_video_giay ?? $lessonItem->thoi_luong ?? 0);
                                                    $itemM = floor($itemSec / 60);
                                                    $itemS = $itemSec % 60;
                                                @endphp
                                                @if ($itemSec > 0)
                                                    {{ $itemM > 0 ? $itemM . 'm' : '' }}{{ $itemS > 0 ? ' ' . $itemS . 's' : ($itemM == 0 ? '0s' : '') }}
                                                @else
                                                    {{ $lessonItem->thoi_luong_hien_thi ?? '--' }}
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            @endforeach
                        @elseif ($danhSachBaiHoc && $danhSachBaiHoc->count())
                            @php
                                $fallbackLessons = $danhSachBaiHoc->values();
                            @endphp
                            @foreach ($fallbackLessons as $idxLesson => $lessonItem)
                                @php
                                    $isActive = ($lessonItem->id_bai_hoc == $baiHoc->id_bai_hoc);
                                @endphp
                                <a
                                    href="{{ route('student.lesson.show', $lessonItem->id_bai_hoc) }}"
                                    class="playlist-item {{ $isActive ? 'active' : '' }}"
                                >
                                    <div class="playlist-item-left">
                                        <div class="playlist-item-icon">
                                            @if ($isActive)
                                                <i class="fa-solid fa-play"></i>
                                            @else
                                                {{ $idxLesson + 1 }}
                                            @endif
                                        </div>
                                        <span class="playlist-item-title">{{ $lessonItem->tieu_de_bai_hoc }}</span>
                                    </div>
                                    <div class="playlist-item-meta">
                                        @php
                                            $itemSec = (int) ($lessonItem->thoi_luong_video_giay ?? $lessonItem->thoi_luong ?? 0);
                                            $itemM = floor($itemSec / 60);
                                            $itemS = $itemSec % 60;
                                        @endphp
                                        @if ($itemSec > 0)
                                            {{ $itemM > 0 ? $itemM . 'm' : '' }}{{ $itemS > 0 ? ' ' . $itemS . 's' : ($itemM == 0 ? '0s' : '') }}
                                        @else
                                            {{ $lessonItem->thoi_luong_hien_thi ?? '--' }}
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
    {{-- ════════════════════════════════════════════════════════
         MODAL: AI TẠO ĐỀ THI CHO BÀI HỌC NÀY
    ════════════════════════════════════════════════════════ --}}
    @php
        $bhId = $baiHoc->id_chi_tiet_bai_hoc ?? $baiHoc->id_bai_hoc;
        $soLanDaTao = 0;
        if (Auth::check()) {
            $soLanDaTao = \App\Models\DeThiTracNghiem::where(function($q) use ($bhId) {
                $q->where('id_chi_tiet_bai_hoc', $bhId)->orWhere('id_bai_hoc', $bhId);
            })->where('id_nguoi_tao', Auth::id())->count();
        }
        $isHocVien = (Auth::check() && (int)Auth::user()->id_phan_quyen === 3);
        $isHetLuot = ($isHocVien && $soLanDaTao >= 3);
    @endphp
    <div class="modal fade" id="modalHocVienTaoDeAi_{{ $bhId }}" tabindex="-1" aria-labelledby="modalLabel_{{ $bhId }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2.5">
                        <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, rgba(79, 70, 229, 0.12), rgba(139, 92, 246, 0.18)); color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border: 1px solid rgba(79, 70, 229, 0.2); flex-shrink: 0;">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0 fs-6" id="modalLabel_{{ $bhId }}">
                                Khởi tạo Đề thi AI - Gemini 1.5
                            </h5>
                            <span class="text-xs text-muted">Cố vấn đề thi trắc nghiệm thông minh</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('baihoc.ai-tao-cau-hoi', $baiHoc->id_bai_hoc) }}" method="POST" id="formTaoDeAi_{{ $bhId }}">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        {{-- Tên bài học muốn tạo đề --}}
                        <div class="p-3 mb-3 rounded-3 bg-light border border-light-subtle">
                            <div class="text-xs text-muted fw-semibold text-uppercase mb-1 d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-book-open text-primary"></i>
                                <span>Bài học áp dụng</span>
                            </div>
                            <div class="fw-bold text-dark fs-6">
                                {{ $baiHoc->tieu_de_bai_hoc }}
                            </div>
                        </div>

                        {{-- Thông tin lượt dùng --}}
                        @if ($isHocVien)
                            @if ($isHetLuot)
                                <div class="alert alert-danger d-flex align-items-center gap-2.5 py-2.5 px-3 mb-3 rounded-3 border-danger-subtle shadow-xs" role="alert">
                                    <i class="fa-solid fa-circle-exclamation fs-5 text-danger flex-shrink-0"></i>
                                    <div>
                                        <div class="fw-bold text-danger">Bạn đã hết lượt tạo đề thi AI!</div>
                                        <div class="text-xs text-danger-emphasis">
                                            Bạn đã sử dụng <strong>{{ $soLanDaTao }} / 3</strong> lượt tạo đề thi AI cho bài học này.
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info d-flex align-items-center justify-content-between py-2.5 px-3 mb-3 rounded-3 border-info-subtle shadow-xs" role="alert">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-circle-info fs-5 text-primary flex-shrink-0"></i>
                                        <div>
                                            <div class="text-xs text-secondary mb-0.5">Giới hạn phát sinh đề thi:</div>
                                            <div class="text-xs text-dark">
                                                Bạn đã sử dụng <strong>{{ $soLanDaTao }} / 3</strong> lượt tạo đề thi AI cho bài học này.
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                        Còn {{ 3 - $soLanDaTao }} lượt
                                    </span>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 mb-3 rounded-3 border-success-subtle text-xs" role="alert">
                                <i class="fa-solid fa-infinity fs-6 text-success flex-shrink-0"></i>
                                <div>
                                    <span class="fw-semibold">Tài khoản Quản trị / Giảng viên:</span> Không giới hạn số lần phát sinh đề thi AI.
                                </div>
                            </div>
                        @endif

                        {{-- Dropdown chọn số lượng câu hỏi mong muốn --}}
                        <div class="mb-2">
                            <label class="form-label fw-semibold text-dark small mb-1.5 d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-list-ol text-primary"></i>
                                <span>Chọn số lượng câu hỏi mong muốn</span>
                            </label>
                            <select name="so_luong_cau_hoi" class="form-select form-select-sm py-2 rounded-3" {{ $isHetLuot ? 'disabled' : '' }}>
                                <option value="3">3 câu hỏi (Kiểm tra nhanh)</option>
                                <option value="5" selected>5 câu hỏi (Đầy đủ tiêu chuẩn)</option>
                                <option value="10">10 câu hỏi (Nâng cao & Toàn diện)</option>
                            </select>
                            <div class="form-text text-muted text-xs mt-1.5 d-flex align-items-start gap-1">
                                <i class="fa-solid fa-shield-halved text-success mt-0.5"></i>
                                <span>Gemini tự động kiểm tra chống trùng lặp với các câu hỏi đã có của bài học.</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3 border-top-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3.5 py-1.5 text-xs" data-bs-dismiss="modal">
                            Đóng
                        </button>

                        @if ($isHetLuot)
                            <button type="button" class="btn btn-sm btn-secondary rounded-pill px-4 py-1.5 text-xs fw-semibold" disabled>
                                <i class="fa-solid fa-ban me-1"></i> Đã hết lượt tạo đề thi AI
                            </button>
                        @else
                            <button
                                type="submit"
                                class="btn btn-sm btn-primary rounded-pill px-4 py-1.5 text-xs fw-semibold shadow-sm d-inline-flex align-items-center gap-1.5"
                                onclick="this.innerHTML='<i class=\'fa-solid fa-spinner fa-spin\'></i> AI đang khởi tạo đề thi...'; this.classList.add('disabled'); this.form.submit();"
                            >
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                Bắt đầu tạo đề thi AI
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

