@extends('layouts.khung')

@section('title', 'Làm Bài Thi Trắc Nghiệm - EduMind AI')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     INLINE STYLES — Quiz Examination Interface
══════════════════════════════════════════════════════════════ --}}
<style>
    /* ────────────────────────────────────────────
       Design Tokens
    ──────────────────────────────────────────── */
    :root {
        --brand:              #4f46e5;
        --brand-hover:        #4338ca;
        --brand-light:        #eef2ff;
        --brand-50:           rgba(79, 70, 229, .08);
        --brand-100:          rgba(79, 70, 229, .12);
        --accent-amber:       #f59e0b;
        --accent-amber-light: rgba(245, 158, 11, .1);
        --accent-violet:      #8b5cf6;
        --accent-emerald:     #10b981;
        --accent-emerald-light: rgba(16, 185, 129, .08);
        --accent-rose:        #f43f5e;
        --accent-rose-light:  rgba(244, 63, 94, .08);
        --accent-sky:         #0ea5e9;
        --surface:            #ffffff;
        --bg:                 #f8fafc;
        --text-primary:       #1e293b;
        --text-secondary:     #64748b;
        --text-muted:         #94a3b8;
        --border:             #e2e8f0;
        --border-light:       #f1f5f9;
        --danger:             #ef4444;
        --radius-xs:          .375rem;
        --radius-sm:          .5rem;
        --radius-md:          .75rem;
        --radius-lg:          1rem;
        --radius-xl:          1.25rem;
        --shadow-sm:          0 1px 2px rgba(0,0,0,.05);
        --shadow-md:          0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -2px rgba(0,0,0,.05);
        --shadow-lg:          0 10px 25px -3px rgba(0,0,0,.08), 0 4px 6px -4px rgba(0,0,0,.04);
        --shadow-xl:          0 20px 50px -12px rgba(0,0,0,.12);
        --transition:         all .25s cubic-bezier(.4,0,.2,1);
        --transition-fast:    all .15s cubic-bezier(.4,0,.2,1);
    }

    /* ────────────────────────────────────────────
       Page Shell
    ──────────────────────────────────────────── */
    .exam-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-primary);
        background: var(--bg);
        min-height: 100vh;
        padding-bottom: 4rem;
        -webkit-font-smoothing: antialiased;
    }

    .exam-container {
        max-width: 1140px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* ════════════════════════════════════════════
       1. STICKY EXAM HEADER
    ════════════════════════════════════════════ */
    .exam-header {
        position: sticky;
        top: 0;
        z-index: 100;
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        transition: box-shadow .2s ease;
    }

    .exam-header.scrolled {
        box-shadow: var(--shadow-md);
    }

    .exam-header-inner {
        max-width: 1140px;
        margin: 0 auto;
        padding: .75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* Left — title area */
    .exam-header-left {
        display: flex;
        align-items: center;
        gap: .75rem;
        min-width: 0;
        flex: 1;
    }

    .exam-icon-box {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, var(--brand), var(--accent-violet));
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: .9rem;
        flex-shrink: 0;
    }

    .exam-title-group {
        min-width: 0;
    }

    .exam-title {
        font-size: .88rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: -.01em;
    }

    .exam-breadcrumb {
        font-size: .72rem;
        color: var(--text-muted);
        margin: 0;
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .exam-breadcrumb i {
        font-size: .6rem;
    }

    /* Center — progress */
    .exam-header-center {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-shrink: 0;
    }

    .progress-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .35rem .75rem;
        border-radius: 100px;
        font-size: .72rem;
        font-weight: 600;
        background: var(--brand-light);
        color: var(--brand);
        border: 1px solid rgba(79,70,229,.12);
        white-space: nowrap;
    }

    .progress-badge i {
        font-size: .65rem;
    }

    /* Right — timer */
    .exam-header-right {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-shrink: 0;
    }

    .timer-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem .85rem;
        border-radius: 100px;
        font-size: .82rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        border: 1.5px solid;
        transition: var(--transition);
    }

    .timer-badge.normal {
        background: var(--accent-emerald-light);
        color: #065f46;
        border-color: rgba(16,185,129,.25);
    }

    .timer-badge.warning {
        background: var(--accent-amber-light);
        color: #92400e;
        border-color: rgba(245,158,11,.3);
        animation: timer-pulse 1s ease-in-out infinite;
    }

    .timer-badge.danger {
        background: var(--accent-rose-light);
        color: #991b1b;
        border-color: rgba(239,68,68,.3);
        animation: timer-pulse .6s ease-in-out infinite;
    }

    @keyframes timer-pulse {
        0%, 100% { opacity: 1; }
        50%      { opacity: .7; }
    }

    .timer-badge i {
        font-size: .75rem;
    }

    /* ════════════════════════════════════════════
       2. TWO-COLUMN LAYOUT
    ════════════════════════════════════════════ */
    .exam-body {
        padding-top: 1.75rem;
    }

    .exam-grid {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 1.75rem;
        align-items: flex-start;
    }

    /* ── Left Column — Questions ─────────────── */
    .question-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .question-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
        scroll-margin-top: 5.5rem;
    }

    .question-card:hover {
        box-shadow: var(--shadow-md);
    }

    .question-card.answered {
        border-color: rgba(79,70,229,.2);
    }

    .question-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        background: var(--bg);
        border-bottom: 1px solid var(--border-light);
        gap: .5rem;
    }

    .question-number {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .85rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .question-number-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--brand-light);
        color: var(--brand);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        font-weight: 800;
        flex-shrink: 0;
        transition: var(--transition-fast);
    }

    .question-card.answered .question-number-circle {
        background: var(--brand);
        color: #fff;
    }

    .difficulty-pill {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .2rem .55rem;
        border-radius: 100px;
        font-size: .62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        border: 1px solid;
    }

    .difficulty-pill.easy {
        background: var(--accent-emerald-light);
        color: #065f46;
        border-color: rgba(16,185,129,.2);
    }

    .difficulty-pill.medium {
        background: var(--accent-amber-light);
        color: #92400e;
        border-color: rgba(245,158,11,.2);
    }

    .difficulty-pill.hard {
        background: var(--accent-rose-light);
        color: #991b1b;
        border-color: rgba(244,63,94,.15);
    }

    .question-body {
        padding: 1.25rem 1.5rem 1.5rem;
    }

    .question-text {
        font-size: .92rem;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.65;
        margin-bottom: 1.25rem;
    }

    /* ── Option radio boxes ──────────────────── */
    .options-list {
        display: flex;
        flex-direction: column;
        gap: .55rem;
    }

    .option-box {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .75rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: var(--transition-fast);
        position: relative;
        background: var(--surface);
    }

    .option-box:hover {
        border-color: rgba(79,70,229,.3);
        background: rgba(79,70,229,.02);
    }

    .option-box.selected {
        border-color: var(--brand);
        background: var(--brand-light);
        box-shadow: 0 0 0 3px var(--brand-50);
    }

    .option-box input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        pointer-events: none;
    }

    .option-marker {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        font-weight: 800;
        color: var(--text-muted);
        flex-shrink: 0;
        transition: var(--transition-fast);
        margin-top: .05rem;
    }

    .option-box:hover .option-marker {
        border-color: rgba(79,70,229,.35);
        color: var(--brand);
    }

    .option-box.selected .option-marker {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }

    .option-text {
        font-size: .85rem;
        color: var(--text-primary);
        line-height: 1.6;
        padding-top: .15rem;
        flex: 1;
    }

    .option-box.selected .option-text {
        color: var(--brand-hover);
        font-weight: 600;
    }

    /* ── Submit bar ──────────────────────────── */
    .submit-bar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .submit-info {
        font-size: .82rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .submit-info i {
        color: var(--accent-amber);
    }

    .btn-submit-exam {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .75rem 2rem;
        font-size: .9rem;
        font-weight: 700;
        font-family: inherit;
        color: #fff;
        background: linear-gradient(135deg, var(--brand) 0%, #6366f1 100%);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        letter-spacing: .01em;
    }

    .btn-submit-exam:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(79,70,229,.35);
    }

    .btn-submit-exam:active {
        transform: translateY(0);
    }

    .btn-submit-exam i {
        font-size: .8rem;
    }

    /* ── Right Column — Palette ──────────────── */
    .palette-panel {
        position: sticky;
        top: 5rem;
    }

    .palette-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .palette-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg);
    }

    .palette-header h3 {
        font-size: .82rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .palette-header h3 i {
        color: var(--brand);
        font-size: .75rem;
    }

    .palette-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: .45rem;
        padding: 1.1rem 1.25rem;
    }

    .palette-btn {
        width: 100%;
        aspect-ratio: 1;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: var(--surface);
        color: var(--text-secondary);
        font-size: .75rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        transition: var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .palette-btn:hover {
        border-color: var(--brand);
        color: var(--brand);
        background: var(--brand-light);
    }

    .palette-btn.answered {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
        box-shadow: 0 2px 6px rgba(79,70,229,.25);
    }

    .palette-btn.current {
        border-color: var(--accent-amber);
        box-shadow: 0 0 0 2px rgba(245,158,11,.2);
    }

    /* Palette legend */
    .palette-legend {
        padding: .75rem 1.25rem 1rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        flex-direction: column;
        gap: .4rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: .45rem;
        font-size: .7rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: var(--radius-xs);
        flex-shrink: 0;
    }

    .legend-dot.answered {
        background: var(--brand);
    }

    .legend-dot.unanswered {
        background: var(--surface);
        border: 1.5px solid var(--border);
    }

    .legend-dot.current {
        background: var(--surface);
        border: 1.5px solid var(--accent-amber);
        box-shadow: 0 0 0 2px rgba(245,158,11,.2);
    }

    /* Palette quick-submit */
    .palette-footer {
        padding: .75rem 1.25rem 1.1rem;
        border-top: 1px solid var(--border-light);
    }

    .btn-palette-submit {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        padding: .6rem;
        font-size: .78rem;
        font-weight: 700;
        font-family: inherit;
        color: #fff;
        background: linear-gradient(135deg, var(--brand), #6366f1);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-palette-submit:hover {
        box-shadow: 0 4px 15px rgba(79,70,229,.3);
        transform: translateY(-1px);
    }

    .btn-palette-submit i {
        font-size: .7rem;
    }

    /* ════════════════════════════════════════════
       3. RESULT / AI ANALYSIS
    ════════════════════════════════════════════ */
    .result-section {
        margin-top: 1.5rem;
    }

    .result-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        overflow: hidden;
    }

    .result-header {
        padding: 1.75rem 2rem;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 45%, #8b5cf6 100%);
        color: #fff;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .result-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -15%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,.07), transparent 70%);
        border-radius: 50%;
    }

    .result-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0 0 .5rem;
        position: relative;
        z-index: 1;
    }

    .result-score {
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: -.02em;
        position: relative;
        z-index: 1;
    }

    .result-score span {
        font-size: 1.2rem;
        font-weight: 500;
        opacity: .8;
    }

    .result-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-light);
        flex-wrap: wrap;
    }

    .result-stat {
        text-align: center;
    }

    .result-stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -.01em;
    }

    .result-stat-value.correct { color: var(--accent-emerald); }
    .result-stat-value.wrong   { color: var(--danger); }
    .result-stat-value.total   { color: var(--brand); }

    .result-stat-label {
        font-size: .72rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-top: .15rem;
    }

    /* AI analysis card */
    .ai-analysis-card {
        margin: 1.25rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(79,70,229,.03), rgba(139,92,246,.03));
        border: 1px solid rgba(79,70,229,.1);
        border-radius: var(--radius-lg);
    }

    .ai-analysis-header {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: .75rem;
    }

    .ai-analysis-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--accent-amber), var(--accent-violet));
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: .75rem;
    }

    .ai-analysis-title {
        font-size: .85rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .ai-analysis-title small {
        display: block;
        font-size: .7rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    .ai-analysis-body {
        font-size: .85rem;
        color: var(--text-secondary);
        line-height: 1.75;
    }

    .ai-analysis-body p {
        margin-bottom: .6rem;
    }

    .ai-analysis-body p:last-child {
        margin-bottom: 0;
    }

    .result-actions {
        padding: 1.25rem 2rem 1.5rem;
        display: flex;
        gap: .75rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-result {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .65rem 1.5rem;
        font-size: .82rem;
        font-weight: 600;
        font-family: inherit;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        border: 1.5px solid;
    }

    .btn-result-primary {
        color: #fff;
        background: var(--brand);
        border-color: var(--brand);
    }

    .btn-result-primary:hover {
        background: var(--brand-hover);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-result-secondary {
        color: var(--text-primary);
        background: var(--surface);
        border-color: var(--border);
    }

    .btn-result-secondary:hover {
        border-color: var(--brand);
        color: var(--brand);
        background: var(--brand-light);
        transform: translateY(-1px);
    }

    /* ════════════════════════════════════════════
       CONFIRM MODAL
    ════════════════════════════════════════════ */
    .confirm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 23, 42, .45);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }

    .confirm-overlay.active {
        display: flex;
    }

    .confirm-dialog {
        background: var(--surface);
        border-radius: var(--radius-xl);
        padding: 2rem;
        max-width: 420px;
        width: 100%;
        box-shadow: var(--shadow-xl);
        text-align: center;
        animation: confirm-enter .25s cubic-bezier(.4,0,.2,1) both;
    }

    @keyframes confirm-enter {
        from { opacity: 0; transform: scale(.95) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .confirm-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: var(--brand-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: var(--brand);
    }

    .confirm-dialog h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: .4rem;
    }

    .confirm-dialog p {
        font-size: .85rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .confirm-actions {
        display: flex;
        gap: .65rem;
        justify-content: center;
    }

    .btn-confirm-yes {
        padding: .6rem 1.5rem;
        font-size: .85rem;
        font-weight: 700;
        font-family: inherit;
        color: #fff;
        background: linear-gradient(135deg, var(--brand), #6366f1);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-confirm-yes:hover {
        box-shadow: 0 6px 20px rgba(79,70,229,.35);
        transform: translateY(-1px);
    }

    .btn-confirm-no {
        padding: .6rem 1.5rem;
        font-size: .85rem;
        font-weight: 600;
        font-family: inherit;
        color: var(--text-secondary);
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-confirm-no:hover {
        border-color: var(--text-muted);
        color: var(--text-primary);
    }

    /* ════════════════════════════════════════════
       RESPONSIVE
    ════════════════════════════════════════════ */
    @media (max-width: 900px) {
        .exam-grid {
            grid-template-columns: 1fr;
        }

        .palette-panel {
            position: relative;
            top: 0;
            order: -1;
        }

        .palette-grid {
            grid-template-columns: repeat(10, 1fr);
        }
    }

    @media (max-width: 640px) {
        .exam-header-inner {
            padding: .6rem 1rem;
        }

        .exam-header-center {
            display: none;
        }

        .exam-container {
            padding: 0 1rem;
        }

        .question-header,
        .question-body {
            padding-left: 1.15rem;
            padding-right: 1.15rem;
        }

        .palette-grid {
            grid-template-columns: repeat(7, 1fr);
        }

        .submit-bar {
            flex-direction: column;
            text-align: center;
        }

        .result-stats {
            gap: 1.25rem;
        }
    }

    @media (max-width: 420px) {
        .palette-grid {
            grid-template-columns: repeat(5, 1fr);
        }
    }
</style>

<div class="exam-page">

    {{-- ════════════════════════════════════════════════════════════
         STICKY EXAM HEADER
    ════════════════════════════════════════════════════════════ --}}
    <header class="exam-header" id="examHeader">
        <div class="exam-header-inner">
            {{-- Left — Title --}}
            <div class="exam-header-left">
                <div class="exam-icon-box">
                    <i class="fas fa-file-circle-check"></i>
                </div>
                <div class="exam-title-group">
                    <h1 class="exam-title">{{ $deThi->tieu_de_de_thi }}</h1>
                    <p class="exam-breadcrumb">
                        <i class="fas fa-book-open"></i>
                        Thuộc bài: {{ $deThi->baiHoc->tieu_de_bai_hoc ?? 'Tổng quát' }}
                    </p>
                </div>
            </div>

            {{-- Center — Progress --}}
            <div class="exam-header-center">
                <span class="progress-badge" id="progressBadge">
                    <i class="fas fa-circle-check"></i>
                    Đã làm: <strong id="answeredCount">0</strong> / {{ $deThi->danhSachCauHoi->count() }}
                </span>
            </div>

            {{-- Right — Timer --}}
            <div class="exam-header-right">
                <div class="timer-badge normal" id="timerBadge">
                    <i class="fas fa-clock"></i>
                    <span id="timerDisplay">{{ sprintf('%02d', $deThi->thoi_gian_lam_bai ?? 15) }}:00</span>
                </div>
            </div>
        </div>
    </header>

    {{-- ════════════════════════════════════════════════════════════
         RESULT SECTION (shown if session has result data)
    ════════════════════════════════════════════════════════════ --}}
    @if (session('ketQuaThi'))
        @php
            $ketQua = session('ketQuaThi');
        @endphp
        <div class="exam-container">
            <div class="result-section">
                <div class="result-card">
                    <div class="result-header">
                        <h2><i class="fas fa-trophy" style="margin-right:.4rem;"></i> Kết quả bài thi</h2>
                        <div class="result-score">
                            {{ $ketQua['diem'] ?? '0' }}<span> / 10 điểm</span>
                        </div>
                    </div>

                    <div class="result-stats">
                        <div class="result-stat">
                            <div class="result-stat-value correct">{{ $ketQua['so_cau_dung'] ?? 0 }}</div>
                            <div class="result-stat-label">Câu đúng</div>
                        </div>
                        <div class="result-stat">
                            <div class="result-stat-value wrong">{{ $ketQua['so_cau_sai'] ?? 0 }}</div>
                            <div class="result-stat-label">Câu sai</div>
                        </div>
                        <div class="result-stat">
                            <div class="result-stat-value total">{{ $ketQua['tong_so_cau'] ?? 0 }}</div>
                            <div class="result-stat-label">Tổng câu</div>
                        </div>
                    </div>

                    @if (!empty($ketQua['phan_tich_ai']))
                        <div class="ai-analysis-card">
                            <div class="ai-analysis-header">
                                <div class="ai-analysis-icon">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="ai-analysis-title">
                                    Phân tích & Giải thích chi tiết từ Gemini AI
                                    <small>Tự động phân tích kết quả bài thi</small>
                                </div>
                            </div>
                            <div class="ai-analysis-body">
                                {!! nl2br(e($ketQua['phan_tich_ai'])) !!}
                            </div>
                        </div>
                    @endif

                    <div class="result-actions">
                        <a href="{{ route('hoc_vien.tong_quan') }}" class="btn-result btn-result-primary">
                            <i class="fas fa-home"></i> Về khóa học của tôi
                        </a>
                        <a href="{{ route('quiz.take', $deThi->id_de_thi) }}" class="btn-result btn-result-secondary">
                            <i class="fas fa-rotate-right"></i> Làm lại bài thi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else

    {{-- ════════════════════════════════════════════════════════════
         EXAM BODY — Two-Column Layout
    ════════════════════════════════════════════════════════════ --}}
    <div class="exam-container">
        <div class="exam-body">
            <form id="examForm" action="{{ route('dethi.nopbai', $deThi->id_de_thi) }}" method="POST">
                @csrf

                <div class="exam-grid">

                    {{-- ── Left Column — Questions ───────── --}}
                    <div class="question-list">

                        @foreach ($deThi->danhSachCauHoi as $index => $cauHoi)
                            <div
                                class="question-card"
                                id="question-{{ $index }}"
                                data-question-index="{{ $index }}"
                            >
                                <div class="question-header">
                                    <div class="question-number">
                                        <span class="question-number-circle">{{ $index + 1 }}</span>
                                        Câu {{ $index + 1 }}
                                    </div>

                                    @php
                                        $diffClass = match($cauHoi->muc_do ?? 'Dễ') {
                                            'Trung bình' => 'medium',
                                            'Khó'        => 'hard',
                                            default      => 'easy',
                                        };
                                    @endphp
                                    <span class="difficulty-pill {{ $diffClass }}">
                                        <i class="fas fa-signal"></i>
                                        {{ $cauHoi->muc_do ?? 'Dễ' }}
                                    </span>
                                </div>

                                <div class="question-body">
                                    <div class="question-text">
                                        {{ $cauHoi->noi_dung_cau_hoi }}
                                    </div>

                                    <div class="options-list">
                                        @foreach ($cauHoi->danhSachPhuongAn as $phuongAn)
                                            <label
                                                class="option-box"
                                                data-question="{{ $index }}"
                                                data-option-id="{{ $phuongAn->id_phuong_an }}"
                                            >
                                                <input
                                                    type="radio"
                                                    name="cau_hoi[{{ $cauHoi->id_cau_hoi }}]"
                                                    value="{{ $phuongAn->id_phuong_an }}"
                                                >
                                                <span class="option-marker">{{ $phuongAn->ky_tu_phuong_an }}</span>
                                                <span class="option-text">{{ $phuongAn->noi_dung_phuong_an }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Submit bar --}}
                        <div class="submit-bar">
                            <div class="submit-info">
                                <i class="fas fa-circle-info"></i>
                                Hãy kiểm tra kỹ trước khi nộp bài. Bạn không thể chỉnh sửa sau khi nộp.
                            </div>
                            <button type="button" class="btn-submit-exam" id="btnSubmitExam">
                                <i class="fas fa-paper-plane"></i>
                                Nộp bài thi
                            </button>
                        </div>
                    </div>

                    {{-- ── Right Column — Question Palette ── --}}
                    <aside class="palette-panel">
                        <div class="palette-card">
                            <div class="palette-header">
                                <h3>
                                    <i class="fas fa-th"></i>
                                    Bảng câu hỏi
                                </h3>
                            </div>

                            <div class="palette-grid" id="paletteGrid">
                                @foreach ($deThi->danhSachCauHoi as $index => $cauHoi)
                                    <button
                                        type="button"
                                        class="palette-btn"
                                        data-target="question-{{ $index }}"
                                        data-index="{{ $index }}"
                                        title="Câu {{ $index + 1 }}"
                                    >
                                        {{ $index + 1 }}
                                    </button>
                                @endforeach
                            </div>

                            <div class="palette-legend">
                                <div class="legend-item">
                                    <span class="legend-dot answered"></span>
                                    Đã trả lời
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot unanswered"></span>
                                    Chưa trả lời
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot current"></span>
                                    Đang xem
                                </div>
                            </div>

                            <div class="palette-footer">
                                <button type="button" class="btn-palette-submit" id="btnPaletteSubmit">
                                    <i class="fas fa-paper-plane"></i>
                                    Nộp bài thi
                                </button>
                            </div>
                        </div>
                    </aside>

                </div>
            </form>
        </div>
    </div>

    {{-- ── Confirmation Modal ─────────────────── --}}
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-dialog">
            <div class="confirm-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3>Xác nhận nộp bài</h3>
            <p id="confirmMessage">
                Bạn đã hoàn thành <strong id="confirmAnswered">0</strong> / <strong>{{ $deThi->danhSachCauHoi->count() }}</strong> câu hỏi. Bạn có chắc muốn nộp bài?
            </p>
            <div class="confirm-actions">
                <button type="button" class="btn-confirm-yes" id="btnConfirmYes">
                    <i class="fas fa-check" style="margin-right:.3rem;"></i> Nộp bài
                </button>
                <button type="button" class="btn-confirm-no" id="btnConfirmNo">
                    Quay lại
                </button>
            </div>
        </div>
    </div>

    @endif

</div>

{{-- ══════════════════════════════════════════════════════════════
     VANILLA JS — Timer, Palette Sync, Interactions
══════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Guard: skip JS if result is displayed ───
    var examForm = document.getElementById('examForm');
    if (!examForm) return;

    // ── References ──────────────────────────────
    var timerDisplay   = document.getElementById('timerDisplay');
    var timerBadge     = document.getElementById('timerBadge');
    var answeredCount  = document.getElementById('answeredCount');
    var paletteGrid    = document.getElementById('paletteGrid');
    var confirmOverlay = document.getElementById('confirmOverlay');
    var confirmAnswered = document.getElementById('confirmAnswered');
    var examHeader     = document.getElementById('examHeader');

    var totalQuestions = parseInt('{{ $deThi->danhSachCauHoi ? $deThi->danhSachCauHoi->count() : 0 }}', 10);
    var totalSeconds   = parseInt('{{ ($deThi->thoi_gian_lam_bai ?? 15) * 60 }}', 10);
    var remaining       = totalSeconds;

    // ── 1. COUNTDOWN TIMER ──────────────────────
    function padZero(n) {
        return n < 10 ? '0' + n : '' + n;
    }

    function updateTimerDisplay() {
        var mins = Math.floor(remaining / 60);
        var secs = remaining % 60;
        timerDisplay.textContent = padZero(mins) + ':' + padZero(secs);

        // Visual states
        var pct = remaining / totalSeconds;
        if (pct <= 0.15) {
            timerBadge.className = 'timer-badge danger';
        } else if (pct <= 0.35) {
            timerBadge.className = 'timer-badge warning';
        } else {
            timerBadge.className = 'timer-badge normal';
        }
    }

    var timerInterval = setInterval(function () {
        remaining--;
        if (remaining <= 0) {
            remaining = 0;
            clearInterval(timerInterval);
            updateTimerDisplay();
            // Auto-submit
            examForm.submit();
            return;
        }
        updateTimerDisplay();
    }, 1000);

    // ── 2. OPTION SELECTION ─────────────────────
    var optionBoxes  = document.querySelectorAll('.option-box');
    var paletteBtns  = paletteGrid ? paletteGrid.querySelectorAll('.palette-btn') : [];
    var answeredSet  = {};

    function syncAnsweredCount() {
        var count = Object.keys(answeredSet).length;
        if (answeredCount) answeredCount.textContent = count;
    }

    optionBoxes.forEach(function (box) {
        box.addEventListener('click', function () {
            var qIndex = this.getAttribute('data-question');

            // Deselect siblings
            var siblings = document.querySelectorAll('.option-box[data-question="' + qIndex + '"]');
            siblings.forEach(function (s) { s.classList.remove('selected'); });

            // Select this one
            this.classList.add('selected');
            var radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            // Mark question card
            var qCard = document.getElementById('question-' + qIndex);
            if (qCard) qCard.classList.add('answered');

            // Update palette
            answeredSet[qIndex] = true;
            syncAnsweredCount();

            paletteBtns.forEach(function (btn) {
                if (btn.getAttribute('data-index') === qIndex) {
                    btn.classList.add('answered');
                }
            });
        });
    });

    // ── 3. PALETTE NAVIGATION ───────────────────
    paletteBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var target   = document.getElementById(targetId);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            // Highlight current
            paletteBtns.forEach(function (b) { b.classList.remove('current'); });
            this.classList.add('current');
        });
    });

    // ── 4. CURRENT QUESTION TRACKING ────────────
    if ('IntersectionObserver' in window) {
        var questionCards = document.querySelectorAll('.question-card');
        var qObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var idx = entry.target.getAttribute('data-question-index');
                    paletteBtns.forEach(function (btn) {
                        btn.classList.toggle('current', btn.getAttribute('data-index') === idx);
                    });
                }
            });
        }, {
            threshold: 0.4,
            rootMargin: '-80px 0px -40% 0px'
        });

        questionCards.forEach(function (card) {
            qObserver.observe(card);
        });
    }

    // ── 5. STICKY HEADER SHADOW ─────────────────
    if (examHeader) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 10) {
                examHeader.classList.add('scrolled');
            } else {
                examHeader.classList.remove('scrolled');
            }
        }, { passive: true });
    }

    // ── 6. SUBMIT CONFIRMATION ──────────────────
    function showConfirm() {
        if (confirmAnswered) confirmAnswered.textContent = Object.keys(answeredSet).length;
        confirmOverlay.classList.add('active');
    }

    function hideConfirm() {
        confirmOverlay.classList.remove('active');
    }

    var btnSubmitExam    = document.getElementById('btnSubmitExam');
    var btnPaletteSubmit = document.getElementById('btnPaletteSubmit');
    var btnConfirmYes    = document.getElementById('btnConfirmYes');
    var btnConfirmNo     = document.getElementById('btnConfirmNo');

    if (btnSubmitExam)    btnSubmitExam.addEventListener('click', showConfirm);
    if (btnPaletteSubmit) btnPaletteSubmit.addEventListener('click', showConfirm);

    if (btnConfirmYes) {
        btnConfirmYes.addEventListener('click', function () {
            clearInterval(timerInterval);
            examForm.submit();
        });
    }

    if (btnConfirmNo) btnConfirmNo.addEventListener('click', hideConfirm);

    // Close on overlay click
    if (confirmOverlay) {
        confirmOverlay.addEventListener('click', function (e) {
            if (e.target === confirmOverlay) hideConfirm();
        });
    }

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && confirmOverlay.classList.contains('active')) {
            hideConfirm();
        }
    });
    /*
    // ── 7. PREVENT ACCIDENTAL NAVIGATION ────────
    window.addEventListener('beforeunload', function (e) {
        if (Object.keys(answeredSet).length > 0) {
            e.preventDefault();
            e.returnValue = '';
        }
    });*/
});
</script>

@endsection
