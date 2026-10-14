@extends('layouts.khung')

@section('title', 'Bảng Điều Khiển Học Viên - EduMind AI')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     INLINE STYLES — Student Dashboard
══════════════════════════════════════════════════════════════ --}}
<style>
    /* ────────────────────────────────────────────
       Design Tokens
    ──────────────────────────────────────────── */
    :root {
        --brand:            #4f46e5;
        --brand-hover:      #4338ca;
        --brand-light:      #eef2ff;
        --brand-50:         rgba(79, 70, 229, .08);
        --brand-100:        rgba(79, 70, 229, .12);
        --accent-amber:     #f59e0b;
        --accent-amber-light: rgba(245, 158, 11, .1);
        --accent-violet:    #8b5cf6;
        --accent-emerald:   #10b981;
        --accent-rose:      #f43f5e;
        --accent-sky:       #0ea5e9;
        --surface:          #ffffff;
        --bg:               #f8fafc;
        --text-primary:     #1e293b;
        --text-secondary:   #64748b;
        --text-muted:       #94a3b8;
        --border:           #e2e8f0;
        --border-light:     #f1f5f9;
        --radius-xs:        .375rem;
        --radius-sm:        .5rem;
        --radius-md:        .75rem;
        --radius-lg:        1rem;
        --radius-xl:        1.25rem;
        --shadow-sm:        0 1px 2px rgba(0,0,0,.05);
        --shadow-md:        0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -2px rgba(0,0,0,.05);
        --shadow-lg:        0 10px 25px -3px rgba(0,0,0,.08), 0 4px 6px -4px rgba(0,0,0,.04);
        --transition:       all .25s cubic-bezier(.4,0,.2,1);
        --transition-fast:  all .15s cubic-bezier(.4,0,.2,1);
    }

    /* ────────────────────────────────────────────
       Dashboard Shell
    ──────────────────────────────────────────── */
    .dashboard-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-primary);
        background: var(--bg);
        min-height: 100vh;
        padding-top: 0.5rem;
        padding-bottom: 4rem;
        -webkit-font-smoothing: antialiased;
    }

    .dashboard-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* ════════════════════════════════════════════
       1. WELCOME HEADER
    ════════════════════════════════════════════ */
    .welcome-section {
        padding: 2.5rem 0 0;
    }

    .welcome-card {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 45%, #8b5cf6 100%);
        border-radius: var(--radius-xl);
        padding: 2.25rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -15%;
        width: 420px;
        height: 420px;
        background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -35%;
        left: -10%;
        width: 280px;
        height: 280px;
        background: radial-gradient(circle, rgba(245,158,11,.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .welcome-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .welcome-text h1 {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0 0 .4rem;
        letter-spacing: -.01em;
    }

    .welcome-text p {
        font-size: .9rem;
        color: rgba(255,255,255,.78);
        margin: 0;
        max-width: 480px;
        line-height: 1.6;
    }

    .welcome-badges {
        display: flex;
        gap: .65rem;
        flex-wrap: wrap;
    }

    .w-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .45rem .9rem;
        border-radius: 100px;
        font-size: .75rem;
        font-weight: 600;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.18);
        white-space: nowrap;
    }

    .w-badge.courses {
        background: rgba(255,255,255,.14);
        color: #fff;
    }

    .w-badge.ai-ready {
        background: linear-gradient(135deg, rgba(245,158,11,.25), rgba(139,92,246,.25));
        color: #fff;
        border-color: rgba(245,158,11,.3);
    }

    .w-badge i {
        font-size: .7rem;
    }

    .w-badge .pulse-dot {
        width: 6px;
        height: 6px;
        background: var(--accent-emerald);
        border-radius: 50%;
        animation: pulse-dot 2s ease-in-out infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: .5; transform: scale(1.5); }
    }

    /* ════════════════════════════════════════════
       2. COURSE LIST
    ════════════════════════════════════════════ */
    .courses-section {
        padding: 2.25rem 0 2rem;
        scroll-margin-top: 5.5rem;
    }

    .section-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        padding-top: 0.25rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-title-group {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .section-icon-box {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.12), rgba(139, 92, 246, 0.15));
        color: var(--brand);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        border: 1px solid rgba(79, 70, 229, 0.2);
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.08);
        flex-shrink: 0;
    }

    .section-heading-wrap {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .section-heading {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.015em;
        color: var(--text-primary);
        line-height: 1.3;
    }

    .section-subtext {
        font-size: 0.825rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .course-count-badge {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--brand);
        background: var(--brand-light);
        border: 1px solid rgba(79, 70, 229, 0.2);
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }

    .course-count-badge .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: var(--brand);
    }

    /* ── Course Card ─────────────────────────── */
    .course-block {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: var(--transition);
    }

    .course-block:hover {
        box-shadow: var(--shadow-md);
    }

    /* Card header */
    .course-header {
        display: flex;
        gap: 1.25rem;
        padding: 1.5rem;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .course-thumb {
        width: 160px;
        height: 100px;
        border-radius: var(--radius-md);
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--brand-light), rgba(139,92,246,.06));
        border: 1px solid var(--border-light);
    }

    .course-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .course-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: rgba(79,70,229,.2);
    }

    .course-info {
        flex: 1;
        min-width: 200px;
    }

    .course-info-top {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-wrap: wrap;
        margin-bottom: .5rem;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .2rem .55rem;
        border-radius: 100px;
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        line-height: 1.4;
    }

    .pill-level {
        border: 1px solid;
    }

    .pill-level.beginner    { background: rgba(16,185,129,.08); color: #065f46; border-color: rgba(16,185,129,.2); }
    .pill-level.intermediate { background: rgba(245,158,11,.08); color: #92400e; border-color: rgba(245,158,11,.2); }
    .pill-level.advanced    { background: rgba(239,68,68,.08);  color: #991b1b; border-color: rgba(239,68,68,.15); }

    .pill-category {
        background: var(--brand-light);
        color: var(--brand);
    }

    .course-info h3 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 .35rem;
        letter-spacing: -.01em;
        line-height: 1.4;
    }

    .course-info .course-desc {
        font-size: .82rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin: 0 0 .65rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-meta-row {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }

    .course-meta-item {
        display: flex;
        align-items: center;
        gap: .35rem;
        font-size: .78rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .course-meta-item i {
        font-size: .72rem;
        width: 14px;
        text-align: center;
    }

    .course-meta-item.instructor {
        color: var(--text-secondary);
    }

    /* ── Accordion / Curriculum ──────────────── */
    .curriculum-wrapper {
        border-top: 1px solid var(--border-light);
    }

    .curriculum-toggle-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .9rem 1.5rem;
        background: var(--bg);
        cursor: pointer;
        transition: var(--transition-fast);
        user-select: none;
    }

    .curriculum-toggle-bar:hover {
        background: var(--border-light);
    }

    .curriculum-toggle-bar h4 {
        font-size: .82rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .curriculum-toggle-bar h4 i {
        color: var(--brand);
        font-size: .75rem;
    }

    .toggle-chevron {
        font-size: .7rem;
        color: var(--text-muted);
        transition: transform .25s ease;
    }

    .curriculum-toggle-bar[aria-expanded="true"] .toggle-chevron {
        transform: rotate(180deg);
    }

    /* Chapter accordion items */
    .chapter-item {
        border-top: 1px solid var(--border-light);
    }

    .chapter-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .85rem 1.5rem .85rem 2rem;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: var(--transition-fast);
        text-align: left;
        gap: .75rem;
    }

    .chapter-btn:hover {
        background: rgba(79,70,229,.03);
    }

    .chapter-left {
        display: flex;
        align-items: center;
        gap: .65rem;
        flex: 1;
        min-width: 0;
    }

    .chapter-number {
        width: 28px;
        height: 28px;
        border-radius: var(--radius-xs);
        background: var(--brand-light);
        color: var(--brand);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .chapter-name {
        font-size: .85rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chapter-right {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-shrink: 0;
    }

    .chapter-lesson-count {
        font-size: .7rem;
        font-weight: 600;
        color: var(--text-muted);
        white-space: nowrap;
    }

    .chapter-chevron {
        font-size: .65rem;
        color: var(--text-muted);
        transition: transform .25s ease;
    }

    .chapter-btn[aria-expanded="true"] .chapter-chevron {
        transform: rotate(180deg);
    }

    /* Lesson rows */
    .lesson-list {
        list-style: none;
        padding: 0;
        margin: 0;
        background: var(--bg);
    }

    .lesson-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .85rem 1.75rem .85rem 2.75rem;
        border-top: 1px solid var(--border-light);
        gap: 1rem;
        transition: var(--transition-fast);
        flex-wrap: nowrap;
    }

    .lesson-item:hover {
        background: rgba(79,70,229,.025);
    }

    .lesson-left {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex: 1;
        min-width: 0;
    }

    .lesson-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--surface);
        border: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .65rem;
        color: var(--text-muted);
        flex-shrink: 0;
        transition: var(--transition-fast);
    }

    .lesson-item:hover .lesson-icon {
        border-color: var(--brand);
        color: var(--brand);
        background: var(--brand-light);
    }

    .lesson-info-wrap {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
        min-width: 0;
        flex: 1;
    }

    .lesson-title-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        text-decoration: none;
        color: var(--text-primary);
        font-weight: 600;
        font-size: .875rem;
        transition: color .2s ease;
        min-width: 0;
        max-width: 440px;
        flex-shrink: 1;
    }

    .lesson-title-link:hover {
        color: var(--brand);
    }

    .lesson-title {
        font-size: .875rem;
        font-weight: 600;
        color: inherit;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .lesson-link-icon {
        font-size: .65rem;
        color: var(--text-muted);
        opacity: 0;
        transform: translate(-3px, 2px);
        transition: all .2s ease;
        flex-shrink: 0;
    }

    .lesson-item:hover .lesson-link-icon {
        opacity: 0.8;
        transform: translate(0, 0);
    }

    .lesson-badges-row {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        flex-wrap: wrap;
        flex-shrink: 0;
    }

    /* Duration & Status Badges */
    .badge-lesson-meta {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        font-weight: 500;
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
        white-space: nowrap;
        line-height: 1.4;
    }

    .badge-duration {
        background-color: var(--border-light);
        color: var(--text-secondary);
        border: 1px solid var(--border);
    }

    .badge-lesson-status {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        white-space: nowrap;
        line-height: 1.4;
    }

    .badge-lesson-status.completed {
        background-color: rgba(16, 185, 129, 0.08);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-lesson-status.pending {
        background-color: rgba(100, 116, 139, 0.08);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.18);
    }

    .lesson-trial-badge {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        background: rgba(16,185,129,.08);
        color: #065f46;
        padding: .2rem .5rem;
        border-radius: 100px;
        border: 1px solid rgba(16,185,129,.15);
        flex-shrink: 0;
    }

    .lesson-right {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* Action buttons */
    .btn-quiz-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        padding: .42rem .85rem;
        font-size: .75rem;
        font-weight: 600;
        color: #ffffff;
        background: var(--brand);
        border: 1px solid var(--brand);
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition-fast);
        cursor: pointer;
        white-space: nowrap;
        line-height: 1.4;
        box-shadow: 0 1px 3px rgba(79, 70, 229, 0.2);
    }

    .btn-quiz-primary:hover {
        background: var(--brand-hover);
        border-color: var(--brand-hover);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(79, 70, 229, 0.3);
    }

    .btn-quiz-primary.disabled,
    .btn-quiz-primary:disabled {
        opacity: .5;
        cursor: not-allowed;
        pointer-events: none;
        background: #94a3b8;
        border-color: #94a3b8;
        box-shadow: none;
        transform: none;
    }

    .btn-quiz-ai {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        padding: .42rem .85rem;
        font-size: .75rem;
        font-weight: 600;
        font-family: inherit;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition-fast);
        cursor: pointer;
        white-space: nowrap;
        border: 1px solid #fde68a;
        background-color: #fffbeb;
        color: #b45309;
        line-height: 1.4;
    }

    .btn-quiz-ai:hover {
        background-color: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(245, 158, 11, 0.15);
    }

    .btn-quiz-ai.disabled,
    .btn-quiz-ai:disabled {
        opacity: .7;
        pointer-events: none;
        transform: none;
    }

    /* ════════════════════════════════════════════
       3. EMPTY STATE
    ════════════════════════════════════════════ */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--surface);
        border: 1.5px dashed var(--border);
        border-radius: var(--radius-xl);
    }

    .empty-state-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        border-radius: var(--radius-lg);
        background: var(--brand-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: var(--brand);
    }

    .empty-state h3 {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: .4rem;
    }

    .empty-state p {
        font-size: .88rem;
        color: var(--text-secondary);
        max-width: 400px;
        margin: 0 auto 1.5rem;
        line-height: 1.65;
    }

    .btn-explore {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .75rem 1.75rem;
        font-size: .88rem;
        font-weight: 600;
        font-family: inherit;
        color: #fff;
        background: linear-gradient(135deg, var(--brand), #6366f1);
        border: none;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-explore:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79,70,229,.35);
        color: #fff;
    }

    /* ════════════════════════════════════════════
       RESPONSIVE
    ════════════════════════════════════════════ */
    @media (max-width: 768px) {
        .welcome-card {
            padding: 1.75rem 1.5rem;
        }

        .welcome-text h1 {
            font-size: 1.3rem;
        }

        .courses-section {
            padding: 1.5rem 0;
        }

        .course-header {
            flex-direction: column;
            padding: 1.25rem;
        }

        .course-thumb {
            width: 100%;
            height: 140px;
        }

        .curriculum-toggle-bar {
            padding: 0.85rem 1.25rem;
        }

        .chapter-btn {
            padding: 0.85rem 1.25rem;
        }

        .lesson-item {
            flex-wrap: wrap;
            padding: 0.85rem 1.25rem 0.85rem 1.75rem;
            gap: 0.75rem;
        }

        .lesson-left {
            flex-basis: 100%;
            width: 100%;
        }

        .lesson-info-wrap {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
        }

        .lesson-title-link {
            max-width: 100%;
        }

        .lesson-right {
            padding-left: 2.5rem;
            width: 100%;
            justify-content: flex-start;
            flex-wrap: wrap;
        }
    }

    @media (max-width: 576px) {
        .dashboard-container {
            padding: 0 1rem;
        }

        .welcome-card {
            padding: 1.5rem 1.25rem;
        }

        .welcome-badges {
            flex-direction: column;
            align-items: flex-start;
        }

        .section-title-group {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .lesson-item {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .lesson-right {
            padding-left: 0;
            width: 100%;
            justify-content: stretch;
        }

        .lesson-right .btn-quiz-primary,
        .lesson-right form,
        .lesson-right .btn-quiz-ai {
            flex: 1;
            text-align: center;
            justify-content: center;
        }
    }
</style>

<div class="dashboard-page">

    {{-- ════════════════════════════════════════════════════════════
         SECTION 1 — WELCOME HEADER
    ════════════════════════════════════════════════════════════ --}}
    <section class="welcome-section">
        <div class="dashboard-container">
            <div class="welcome-card">
                <div class="welcome-inner">
                    <div class="welcome-text">
                        <h1>Xin chào, {{ Auth::user()->ho_va_ten ?? 'Học viên' }} 👋</h1>
                        <p>
                            Tiếp tục hành trình học tập của bạn — mỗi bài học hoàn thành là một bước tiến tới thành công.
                            Hãy để trợ lý AI giúp bạn ôn luyện hiệu quả hơn!
                        </p>
                    </div>
                    <div class="welcome-badges">
                        <span class="w-badge courses">
                            <i class="fas fa-book-open"></i>
                            {{ isset($danhSachKhoaHoc) ? $danhSachKhoaHoc->count() : 0 }} khóa học đang học
                        </span>
                        <span class="w-badge ai-ready">
                            <span class="pulse-dot"></span>
                            <i class="fas fa-wand-magic-sparkles"></i>
                            Trợ lý AI Gemini: Sẵn sàng 24/7
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         SECTION 2 — COURSE LIST & CURRICULUM
    ════════════════════════════════════════════════════════════ --}}
    <section class="courses-section" id="courses">
        <div class="dashboard-container">
            <div class="section-bar">
                <div class="section-title-group">
                    <div class="section-icon-box">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="section-heading-wrap">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h2 class="section-heading">Khóa học của bạn</h2>
                            @if (isset($danhSachKhoaHoc) && $danhSachKhoaHoc->count())
                                <span class="course-count-badge">
                                    <span class="badge-dot"></span>
                                    {{ $danhSachKhoaHoc->count() }} khóa học
                                </span>
                            @endif
                        </div>
                        <p class="section-subtext mb-0">Danh sách các khóa học bạn đã đăng ký và lộ trình bài học chi tiết</p>
                    </div>
                </div>
            </div>

            @forelse ($danhSachKhoaHoc as $indexKhoaHoc => $khoaHoc)
                <div class="course-block">

                    {{-- ── Course header ────────────────────── --}}
                    <div class="course-header">
                        <div class="course-thumb">
                            @if ($khoaHoc->hinh_anh_dai_dien)
                                <img
                                    src="{{ asset('storage/' . $khoaHoc->hinh_anh_dai_dien) }}"
                                    alt="{{ $khoaHoc->ten_khoa_hoc }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="course-thumb-placeholder">
                                    <i class="fas fa-book-open"></i>
                                </div>
                            @endif
                        </div>

                        <div class="course-info">
                            <div class="course-info-top">
                                {{-- Level pill --}}
                                @php
                                    $levelClass = match($khoaHoc->muc_do ?? 'beginner') {
                                        'intermediate' => 'intermediate',
                                        'advanced'     => 'advanced',
                                        default        => 'beginner',
                                    };
                                    $levelLabel = match($khoaHoc->muc_do ?? 'beginner') {
                                        'intermediate' => 'Trung cấp',
                                        'advanced'     => 'Nâng cao',
                                        default        => 'Cơ bản',
                                    };
                                @endphp
                                <span class="pill pill-level {{ $levelClass }}">{{ $levelLabel }}</span>

                                {{-- Category pill --}}
                                @if ($khoaHoc->danhMuc)
                                    <span class="pill pill-category">{{ $khoaHoc->danhMuc->ten_danh_muc }}</span>
                                @endif
                            </div>

                            <h3>{{ $khoaHoc->ten_khoa_hoc }}</h3>

                            @if ($khoaHoc->mo_ta_khoa_hoc)
                                <p class="course-desc">{{ $khoaHoc->mo_ta_khoa_hoc }}</p>
                            @endif

                            <div class="course-meta-row">
                                @if ($khoaHoc->giangVien)
                                    <span class="course-meta-item instructor">
                                        <i class="fas fa-chalkboard-user"></i>
                                        {{ $khoaHoc->giangVien->ho_va_ten }}
                                    </span>
                                @endif

                                @if ($khoaHoc->thoi_luong_phut)
                                    <span class="course-meta-item">
                                        <i class="fas fa-clock"></i>
                                        {{ floor($khoaHoc->thoi_luong_phut / 60) > 0 ? floor($khoaHoc->thoi_luong_phut / 60) . ' giờ ' : '' }}{{ $khoaHoc->thoi_luong_phut % 60 > 0 ? ($khoaHoc->thoi_luong_phut % 60) . ' phút' : '' }}
                                    </span>
                                @endif

                                @if ($khoaHoc->danhSachChuongMuc)
                                    <span class="course-meta-item">
                                        <i class="fas fa-layer-group"></i>
                                        {{ $khoaHoc->danhSachChuongMuc->count() }} chương
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── Curriculum accordion ─────────────── --}}
                    @if ($khoaHoc->danhSachChuongMuc && $khoaHoc->danhSachChuongMuc->count())
                        <div class="curriculum-wrapper">
                            {{-- Master toggle --}}
                            <div
                                class="curriculum-toggle-bar"
                                data-bs-toggle="collapse"
                                data-bs-target="#curriculum-{{ $khoaHoc->id_khoa_hoc }}"
                                aria-expanded="{{ $indexKhoaHoc === 0 ? 'true' : 'false' }}"
                                aria-controls="curriculum-{{ $khoaHoc->id_khoa_hoc }}"
                                role="button"
                            >
                                <h4>
                                    <i class="fas fa-list-check"></i>
                                    Nội dung khóa học
                                    <span style="font-weight:500; color: var(--text-muted); font-size:.78rem;">
                                        — {{ $khoaHoc->danhSachChuongMuc->count() }} chương
                                        @php
                                            $totalLessons = $khoaHoc->danhSachChuongMuc->sum(fn($ch) => $ch->danhSachBaiHoc ? $ch->danhSachBaiHoc->count() : 0);
                                        @endphp
                                        • {{ $totalLessons }} bài học
                                    </span>
                                </h4>
                                <i class="fas fa-chevron-down toggle-chevron"></i>
                            </div>

                            <div
                                class="collapse {{ $indexKhoaHoc === 0 ? 'show' : '' }}"
                                id="curriculum-{{ $khoaHoc->id_khoa_hoc }}"
                            >
                                <div class="accordion accordion-flush" id="chapters-{{ $khoaHoc->id_khoa_hoc }}">
                                    @foreach ($khoaHoc->danhSachChuongMuc as $indexChuong => $chuongMuc)
                                        <div class="chapter-item">
                                            {{-- Chapter button --}}
                                            <button
                                                class="chapter-btn {{ $indexKhoaHoc === 0 && $indexChuong === 0 ? '' : 'collapsed' }}"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#chapter-{{ $khoaHoc->id_khoa_hoc }}-{{ $indexChuong }}"
                                                aria-expanded="{{ $indexKhoaHoc === 0 && $indexChuong === 0 ? 'true' : 'false' }}"
                                            >
                                                <div class="chapter-left">
                                                    <span class="chapter-number">{{ $indexChuong + 1 }}</span>
                                                    <span class="chapter-name">{{ $chuongMuc->ten_chuong_muc }}</span>
                                                </div>
                                                <div class="chapter-right">
                                                    @if ($chuongMuc->danhSachBaiHoc)
                                                        <span class="chapter-lesson-count">
                                                            {{ $chuongMuc->danhSachBaiHoc->count() }} bài học
                                                        </span>
                                                    @endif
                                                    <i class="fas fa-chevron-down chapter-chevron"></i>
                                                </div>
                                            </button>

                                            {{-- Lesson list --}}
                                            <div
                                                class="collapse {{ $indexKhoaHoc === 0 && $indexChuong === 0 ? 'show' : '' }}"
                                                id="chapter-{{ $khoaHoc->id_khoa_hoc }}-{{ $indexChuong }}"
                                                data-bs-parent="#chapters-{{ $khoaHoc->id_khoa_hoc }}"
                                            >
                                                @if ($chuongMuc->danhSachBaiHoc && $chuongMuc->danhSachBaiHoc->count())
                                                    <ul class="lesson-list">
                                                        @foreach ($chuongMuc->danhSachBaiHoc as $baiHoc)
                                                            <li class="lesson-item">
                                                                <div class="lesson-left">
                                                                    <span class="lesson-icon">
                                                                        <i class="fas fa-play"></i>
                                                                    </span>
                                                                    <div class="lesson-info-wrap">
                                                                        <a href="{{ route('student.lesson.show', $baiHoc->id_chi_tiet_bai_hoc ?? $baiHoc->id_bai_hoc) }}" class="lesson-title-link" title="{{ $baiHoc->tieu_de_bai_hoc }}">
                                                                            <span class="lesson-title">{{ $baiHoc->tieu_de_bai_hoc }}</span>
                                                                            <i class="fa-solid fa-arrow-up-right-from-square lesson-link-icon"></i>
                                                                        </a>
                                                                        <div class="lesson-badges-row">
                                                                            {{-- Duration badge --}}
                                                                            <span class="badge-lesson-meta badge-duration">
                                                                                <i class="fa-regular fa-clock"></i>
                                                                                {{ $baiHoc->thoi_luong_video ? (floor($baiHoc->thoi_luong_video / 60) > 0 ? floor($baiHoc->thoi_luong_video / 60) . ' phút' : gmdate('i:s', $baiHoc->thoi_luong_video)) : '15 phút' }}
                                                                            </span>

                                                                            {{-- Status badge --}}
                                                                            @php
                                                                                $quiz = $baiHoc->deThi ?? $baiHoc->deThiTracNghiem ?? ($baiHoc->danhSachDeThi ? $baiHoc->danhSachDeThi->first() : null);
                                                                            @endphp

                                                                            @if ($quiz)
                                                                                <span class="badge-lesson-status completed">
                                                                                    <i class="fa-solid fa-circle-check"></i> Đã có đề thi
                                                                                </span>
                                                                            @else
                                                                                <span class="badge-lesson-status pending">
                                                                                    <i class="fa-regular fa-circle-dot"></i> Chưa có đề thi
                                                                                </span>
                                                                            @endif

                                                                            {{-- Trial badge --}}
                                                                            @if ($baiHoc->co_cho_phep_hoc_thu)
                                                                                <span class="lesson-trial-badge">Học thử</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="lesson-right">
                                                                    {{-- Primary Action Button: Làm bài thi / Vào thi --}}
                                                                    @if ($quiz)
                                                                        <a
                                                                            href="{{ route('quiz.take', $quiz->id_de_thi) }}"
                                                                            class="btn-quiz-primary"
                                                                        >
                                                                            <i class="fa-solid fa-file-pen"></i>
                                                                            Làm bài thi
                                                                        </a>
                                                                    @else
                                                                        <button
                                                                            type="button"
                                                                            class="btn-quiz-primary disabled"
                                                                            disabled
                                                                            title="Chưa có đề thi cho bài học này. Vui lòng bấm 'AI Tạo Đề Thi' để tạo đề tự động!"
                                                                        >
                                                                            <i class="fa-solid fa-file-pen"></i>
                                                                            Làm bài thi
                                                                        </button>
                                                                    @endif

                                                                    {{-- AI Generate Quiz Button --}}
                                                                    <form
                                                                        action="{{ route('baihoc.ai-tao-cau-hoi', $baiHoc->id_bai_hoc) }}"
                                                                        method="POST"
                                                                        class="m-0 d-inline"
                                                                    >
                                                                        @csrf
                                                                        <button
                                                                            type="submit"
                                                                            class="btn-quiz-ai"
                                                                            onclick="this.innerHTML='<i class=\'fa-solid fa-spinner fa-spin\'></i> AI đang tạo...'; this.classList.add('disabled');"
                                                                        >
                                                                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                                                                            AI Tạo Đề Thi
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div style="padding: 1rem 1.5rem 1rem 3.5rem;">
                                                        <p style="font-size:.82rem; color:var(--text-muted); margin:0;">
                                                            <i class="fas fa-info-circle" style="margin-right:.3rem;"></i>
                                                            Chưa có bài học nào trong chương này.
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                {{-- ── Empty state ──────────────────────── --}}
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-book-open-reader"></i>
                    </div>
                    <h3>Bạn chưa tham gia khóa học nào</h3>
                    <p>
                        Khám phá hàng trăm khóa học chất lượng cao với sự hỗ trợ của AI —
                        bắt đầu hành trình học tập ngay hôm nay!
                    </p>
                    <a href="{{ route('home') }}" class="btn-explore">
                        <i class="fas fa-compass"></i>
                        Khám phá khóa học
                    </a>
                </div>
            @endforelse

        </div>
    </section>

</div>

@endsection