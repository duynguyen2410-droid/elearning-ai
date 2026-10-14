@extends('layouts.khung')

@section('title', 'EduMind AI – Nền tảng E-Learning Tích hợp AI Thông minh')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     INLINE STYLES – Landing Page
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
        --accent-violet:    #8b5cf6;
        --accent-emerald:   #10b981;
        --accent-rose:      #f43f5e;
        --surface:          #ffffff;
        --bg:               #f8fafc;
        --text-primary:     #1e293b;
        --text-secondary:   #64748b;
        --text-muted:       #94a3b8;
        --border:           #e2e8f0;
        --border-light:     #f1f5f9;
        --radius-sm:        .5rem;
        --radius-md:        .75rem;
        --radius-lg:        1rem;
        --radius-xl:        1.25rem;
        --radius-2xl:       1.5rem;
        --shadow-sm:        0 1px 2px rgba(0,0,0,.05);
        --shadow-md:        0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -2px rgba(0,0,0,.05);
        --shadow-lg:        0 10px 25px -3px rgba(0,0,0,.08), 0 4px 6px -4px rgba(0,0,0,.04);
        --shadow-xl:        0 20px 50px -12px rgba(0,0,0,.12);
        --transition:       all .25s cubic-bezier(.4,0,.2,1);
        --transition-fast:  all .15s cubic-bezier(.4,0,.2,1);
    }

    /* ────────────────────────────────────────────
       Global Landing Overrides
    ──────────────────────────────────────────── */
    .landing-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-primary);
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .landing-page *,
    .landing-page *::before,
    .landing-page *::after {
        box-sizing: border-box;
    }

    .section-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* ────────────────────────────────────────────
       Section Headers (reusable)
    ──────────────────────────────────────────── */
    .section-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--brand);
        background: var(--brand-light);
        padding: .35rem .85rem;
        border-radius: 100px;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -.025em;
        color: var(--text-primary);
        margin-bottom: .75rem;
    }

    .section-subtitle {
        font-size: 1.05rem;
        color: var(--text-secondary);
        line-height: 1.7;
        max-width: 600px;
    }

    /* ════════════════════════════════════════════
       1. HERO SECTION
    ════════════════════════════════════════════ */
    .hero-section {
        position: relative;
        padding: 5rem 0 6rem;
        background:
            radial-gradient(ellipse 70% 50% at 20% 30%, rgba(79,70,229,.06) 0%, transparent 60%),
            radial-gradient(ellipse 50% 40% at 80% 70%, rgba(139,92,246,.05) 0%, transparent 60%),
            var(--bg);
    }

    .hero-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle at 1px 1px, rgba(79,70,229,.035) 1px, transparent 0);
        background-size: 36px 36px;
        pointer-events: none;
    }

    .hero-inner {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3.5rem;
        align-items: center;
    }

    /* Hero – Left copy */
    .hero-copy { max-width: 540px; }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        background: linear-gradient(135deg, rgba(245,158,11,.12), rgba(139,92,246,.12));
        border: 1px solid rgba(245,158,11,.2);
        color: var(--text-primary);
        font-size: .78rem;
        font-weight: 600;
        padding: .4rem 1rem;
        border-radius: 100px;
        margin-bottom: 1.75rem;
        letter-spacing: .01em;
    }

    .hero-badge .badge-dot {
        width: 6px; height: 6px;
        background: var(--accent-amber);
        border-radius: 50%;
        animation: pulse-dot 2s ease-in-out infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: .5; transform: scale(1.4); }
    }

    .hero-headline {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -.03em;
        color: var(--text-primary);
        margin: 0 0 1.25rem;
    }

    .hero-headline .text-gradient {
        background: linear-gradient(135deg, var(--brand) 0%, var(--accent-violet) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: var(--text-secondary);
        line-height: 1.75;
        margin-bottom: 2.25rem;
        max-width: 480px;
    }

    /* CTA Buttons */
    .hero-cta {
        display: flex;
        flex-wrap: wrap;
        gap: .85rem;
        margin-bottom: 2.5rem;
    }

    .btn-primary-cta {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .85rem 1.75rem;
        font-size: .925rem;
        font-weight: 600;
        font-family: inherit;
        color: #fff;
        background: linear-gradient(135deg, var(--brand) 0%, #6366f1 100%);
        border: none;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-primary-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(79,70,229,.35);
        color: #fff;
    }

    .btn-primary-cta:active { transform: translateY(0); }

    .btn-secondary-cta {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .85rem 1.75rem;
        font-size: .925rem;
        font-weight: 600;
        font-family: inherit;
        color: var(--text-primary);
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-secondary-cta:hover {
        border-color: var(--brand);
        color: var(--brand);
        background: var(--brand-light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Social proof stats */
    .hero-stats {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .hero-stat {
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .hero-stat-icon {
        width: 36px; height: 36px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        flex-shrink: 0;
    }

    .hero-stat-icon.purple  { background: rgba(139,92,246,.1); color: var(--accent-violet); }
    .hero-stat-icon.emerald { background: rgba(16,185,129,.1);  color: var(--accent-emerald); }
    .hero-stat-icon.amber   { background: rgba(245,158,11,.1);  color: var(--accent-amber); }

    .hero-stat-value {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .hero-stat-label {
        font-size: .72rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* ────────────────────────────────────────────
       Hero – Bento Grid UI Widget
    ──────────────────────────────────────────── */
    .hero-bento-container {
        position: relative;
        width: 100%;
        max-width: 32rem;
        margin: 0 auto;
    }

    /* Ambient Radial Gradient Glow */
    .hero-ambient-glow {
        position: absolute;
        inset: -1.5rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.22) 0%, rgba(168, 85, 247, 0.2) 50%, rgba(236, 72, 153, 0.12) 100%);
        filter: blur(48px);
        -webkit-filter: blur(48px);
        border-radius: 2rem;
        z-index: 0;
        pointer-events: none;
    }

    .bento-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        position: relative;
        z-index: 1;
    }

    /* Glassmorphic Bento Cards */
    .bento-card {
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(241, 245, 249, 0.95);
        border-radius: 1rem;
        padding: 1.25rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.07), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.25s ease;
        position: relative;
    }

    .bento-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 25px 30px -5px rgba(79, 70, 229, 0.12), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: rgba(199, 210, 254, 0.8);
    }

    .bento-card-main {
        grid-column: span 2 / span 2;
    }

    /* Pulse Dot */
    .bento-pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
        position: relative;
        flex-shrink: 0;
    }

    .bento-pulse-dot::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 50%;
        background: #10b981;
        animation: bento-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        opacity: 0.75;
    }

    @keyframes bento-pulse {
        0%, 100% { transform: scale(0.9); opacity: 0.8; }
        50%      { transform: scale(1.9); opacity: 0; }
    }

    /* Spinning spark icon animation */
    .animate-spin-slow {
        display: inline-block;
        animation: bento-spin 6s linear infinite;
    }

    @keyframes bento-spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* AI Response Shimmer Effect */
    .ai-response-indicator {
        position: relative;
        overflow: hidden;
    }

    .ai-response-indicator::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.65) 50%, transparent 100%);
        transform: translateX(-100%);
        animation: bento-shimmer 2.8s infinite;
        pointer-events: none;
    }

    @keyframes bento-shimmer {
        100% { transform: translateX(100%); }
    }

    /* Typing Dots */
    .ai-typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    .typing-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #6366f1;
        display: inline-block;
        animation: bento-typing 1.4s infinite ease-in-out both;
    }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    .typing-dot:nth-child(3) { animation-delay: 0s; }

    @keyframes bento-typing {
        0%, 80%, 100% { transform: scale(0); opacity: 0.3; }
        40%           { transform: scale(1); opacity: 1; }
    }

    /* Progress bar */
    .bento-progress-bar {
        background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 50%, #8b5cf6 100%);
        position: relative;
        overflow: hidden;
        transition: width 1s ease-in-out;
    }

    .bento-progress-bar::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: bento-shimmer 2s infinite;
    }

    /* Bento Grid CSS Utilities */
    .relative { position: relative; }
    .absolute { position: absolute; }
    .flex { display: flex; }
    .inline-flex { display: inline-flex; }
    .flex-col { flex-direction: column; }
    .items-center { align-items: center; }
    .items-start { align-items: flex-start; }
    .items-baseline { align-items: baseline; }
    .justify-center { justify-content: center; }
    .justify-between { justify-content: space-between; }
    .flex-1 { flex: 1 1 0%; }
    .flex-shrink-0 { flex-shrink: 0; }
    .min-w-0 { min-width: 0; }
    .gap-1 { gap: 0.25rem; }
    .gap-1\.5 { gap: 0.375rem; }
    .gap-2\.5 { gap: 0.625rem; }
    .gap-3 { gap: 0.75rem; }
    .gap-4 { gap: 1rem; }
    .w-full { width: 100%; }
    .max-w-lg { max-width: 32rem; }
    .mx-auto { margin-left: auto; margin-right: auto; }
    .rounded-xl { border-radius: 0.75rem; }
    .rounded-2xl { border-radius: 1rem; }
    .rounded-full { border-radius: 9999px; }
    .p-0\.5 { padding: 0.125rem; }
    .p-3 { padding: 0.75rem; }
    .p-5 { padding: 1.25rem; }
    .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
    .px-2\.5 { padding-left: 0.625rem; padding-right: 0.625rem; }
    .py-0\.5 { padding-top: 0.125rem; padding-bottom: 0.125rem; }
    .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
    .pt-2 { padding-top: 0.5rem; }
    .pb-3 { padding-bottom: 0.75rem; }
    .mb-0\.5 { margin-bottom: 0.125rem; }
    .mb-1 { margin-bottom: 0.25rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-3 { margin-bottom: 0.75rem; }
    .mb-4 { margin-bottom: 1rem; }
    .m-0 { margin: 0; }
    .text-\[10px\] { font-size: 10px; }
    .text-\[11px\] { font-size: 11px; }
    .text-\[11\.5px\] { font-size: 11.5px; }
    .text-xs { font-size: 0.75rem; }
    .text-sm { font-size: 0.875rem; }
    .text-lg { font-size: 1.125rem; }
    .text-xl { font-size: 1.25rem; }
    .font-normal { font-weight: 400; }
    .font-medium { font-weight: 500; }
    .font-semibold { font-weight: 600; }
    .font-bold { font-weight: 700; }
    .font-extrabold { font-weight: 800; }
    .uppercase { text-transform: uppercase; }
    .tracking-wider { letter-spacing: 0.05em; }
    .tracking-tight { letter-spacing: -0.025em; }
    .leading-tight { line-height: 1.25; }
    .leading-relaxed { line-height: 1.625; }
    .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .overflow-hidden { overflow: hidden; }
    .border-b { border-bottom-width: 1px; border-bottom-style: solid; }
    .border-t { border-top-width: 1px; border-top-style: solid; }
    .border-slate-100 { border-color: #f1f5f9; }
    .text-slate-400 { color: #94a3b8; }
    .text-slate-500 { color: #64748b; }
    .text-slate-700 { color: #334155; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-900 { color: #0f172a; }
    .text-amber-500 { color: #f59e0b; }
    .text-amber-600 { color: #d97706; }
    .text-amber-700 { color: #b45309; }
    .bg-amber-100 { background-color: #fef3c7; }
    .bg-amber-500\/10 { background-color: rgba(245, 158, 11, 0.1); }
    .border-amber-500\/20 { border-color: rgba(245, 158, 11, 0.2); }
    .text-indigo-600 { color: #4f46e5; }
    .text-indigo-700 { color: #4338ca; }
    .text-indigo-950 { color: #1e1b4b; }
    .bg-indigo-50\/60 { background-color: rgba(238, 242, 255, 0.6); }
    .bg-indigo-50\/90 { background-color: rgba(238, 242, 255, 0.9); }
    .bg-indigo-100 { background-color: #e0e7ff; }
    .bg-indigo-500\/10 { background-color: rgba(79, 70, 229, 0.1); }
    .bg-indigo-600 { background-color: #4f46e5; }
    .border-indigo-100 { border-color: #e0e7ff; }
    .border-indigo-100\/80 { border-color: rgba(224, 231, 255, 0.8); }
    .border-indigo-500\/20 { border-color: rgba(79, 70, 229, 0.2); }
    .bg-emerald-50 { background-color: #ecfdf5; }
    .text-emerald-600 { color: #059669; }
    .border-emerald-100 { border-color: #d1fae5; }
    .bg-slate-100 { background-color: #f1f5f9; }
    .border-slate-200\/50 { border-color: rgba(226, 232, 240, 0.5); }
    .w-6 { width: 1.5rem; }
    .h-6 { height: 1.5rem; }
    .w-9 { width: 2.25rem; }
    .h-9 { height: 2.25rem; }
    .w-10 { width: 2.5rem; }
    .h-10 { height: 2.5rem; }
    .h-2\.5 { height: 0.625rem; }
    .-inset-6 { inset: -1.5rem; }
    .-z-10 { z-index: -10; }
    .z-10 { z-index: 10; }
    .pointer-events-none { pointer-events: none; }
    .shadow-xs { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1); }
    .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.07), 0 8px 10px -6px rgba(0, 0, 0, 0.03); }
    .backdrop-blur-md { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    .bg-white\/80 { background-color: rgba(255, 255, 255, 0.82); }
    .bg-gradient-to-tr { background-image: linear-gradient(to top right, var(--tw-gradient-stops, #6366f1, #a855f7, #ec4899)); }
    .from-indigo-600 { --tw-gradient-from: #4f46e5; }
    .to-purple-600 { --tw-gradient-to: #9333ea; }
    .from-indigo-500 { --tw-gradient-from: #6366f1; }
    .to-purple-600 { --tw-gradient-to: #9333ea; }
    .from-indigo-500\/20 { --tw-gradient-from: rgba(99, 102, 241, 0.2); }
    .via-purple-500\/20 { --tw-gradient-stops: rgba(99, 102, 241, 0.2), rgba(168, 85, 247, 0.2), rgba(236, 72, 153, 0.1); }
    .to-pink-500\/10 { --tw-gradient-to: rgba(236, 72, 153, 0.1); }
    .blur-3xl { filter: blur(48px); -webkit-filter: blur(48px); }

    /* ════════════════════════════════════════════
       2. FEATURES SECTION
    ════════════════════════════════════════════ */
    .features-section {
        padding: 5rem 0;
        background: var(--surface);
        border-top: 1px solid var(--border-light);
        border-bottom: 1px solid var(--border-light);
    }

    .features-header {
        text-align: center;
        margin-bottom: 3.5rem;
    }

    .features-header .section-subtitle {
        margin: 0 auto;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .feature-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.75rem 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        opacity: 0;
        transition: var(--transition);
    }

    .feature-card:hover {
        border-color: transparent;
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        background: var(--surface);
    }

    .feature-card:hover::before { opacity: 1; }

    .feature-card:nth-child(1)::before { background: linear-gradient(90deg, var(--brand), var(--accent-violet)); }
    .feature-card:nth-child(2)::before { background: linear-gradient(90deg, var(--accent-emerald), #34d399); }
    .feature-card:nth-child(3)::before { background: linear-gradient(90deg, var(--accent-amber), #fbbf24); }
    .feature-card:nth-child(4)::before { background: linear-gradient(90deg, var(--accent-rose), #fb7185); }

    .feature-icon-box {
        width: 48px; height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 1.25rem;
    }

    .feature-icon-box.indigo  { background: rgba(79,70,229,.1);  color: var(--brand); }
    .feature-icon-box.emerald { background: rgba(16,185,129,.1);  color: var(--accent-emerald); }
    .feature-icon-box.amber   { background: rgba(245,158,11,.1);  color: var(--accent-amber); }
    .feature-icon-box.rose    { background: rgba(244,63,94,.1);   color: var(--accent-rose); }

    .feature-card h3 {
        font-size: .95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: .5rem;
        letter-spacing: -.01em;
    }

    .feature-card p {
        font-size: .82rem;
        color: var(--text-secondary);
        line-height: 1.65;
        margin: 0;
    }

    /* ════════════════════════════════════════════
       3. COURSES SECTION
    ════════════════════════════════════════════ */
    .courses-section {
        padding: 5rem 0;
        background: var(--bg);
    }

    .courses-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .course-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }

    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }

    .course-thumb {
        position: relative;
        height: 180px;
        background: linear-gradient(135deg, rgba(79,70,229,.08), rgba(139,92,246,.08));
        overflow: hidden;
    }

    .course-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .course-card:hover .course-thumb img {
        transform: scale(1.05);
    }

    .course-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: rgba(79,70,229,.2);
        background: linear-gradient(135deg, var(--brand-light), rgba(139,92,246,.06));
    }

    .course-level-badge {
        position: absolute;
        top: .75rem;
        left: .75rem;
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding: .25rem .6rem;
        border-radius: 100px;
        backdrop-filter: blur(8px);
    }

    .course-level-badge.beginner   { background: rgba(16,185,129,.15); color: #065f46; border: 1px solid rgba(16,185,129,.2); }
    .course-level-badge.intermediate { background: rgba(245,158,11,.15); color: #92400e; border: 1px solid rgba(245,158,11,.2); }
    .course-level-badge.advanced   { background: rgba(239,68,68,.12);  color: #991b1b; border: 1px solid rgba(239,68,68,.15); }

    .course-duration-badge {
        position: absolute;
        top: .75rem;
        right: .75rem;
        display: flex;
        align-items: center;
        gap: .3rem;
        font-size: .65rem;
        font-weight: 600;
        padding: .25rem .6rem;
        border-radius: 100px;
        background: rgba(0,0,0,.55);
        color: #fff;
        backdrop-filter: blur(6px);
    }

    .course-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .course-category {
        font-size: .7rem;
        font-weight: 600;
        color: var(--brand);
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: .4rem;
    }

    .course-title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.45;
        margin-bottom: .5rem;
        letter-spacing: -.01em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-desc {
        font-size: .8rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
        margin-top: auto;
    }

    .course-instructor {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .78rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .course-instructor-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand-light), rgba(139,92,246,.1));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .65rem;
        color: var(--brand);
        font-weight: 700;
        flex-shrink: 0;
    }

    .course-detail-btn {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .78rem;
        font-weight: 600;
        color: var(--brand);
        text-decoration: none;
        transition: var(--transition-fast);
        padding: .3rem .6rem;
        border-radius: var(--radius-sm);
    }

    .course-detail-btn:hover {
        background: var(--brand-light);
        color: var(--brand-hover);
    }

    .course-detail-btn i {
        font-size: .65rem;
        transition: transform .2s ease;
    }

    .course-detail-btn:hover i {
        transform: translateX(3px);
    }

    /* Placeholder skeleton cards */
    .course-card.placeholder-card .course-thumb {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    }

    .placeholder-line {
        height: 10px;
        border-radius: 100px;
        background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.8s ease-in-out infinite;
    }

    .placeholder-line.w-75  { width: 75%; }
    .placeholder-line.w-100 { width: 100%; }
    .placeholder-line.w-50  { width: 50%; }
    .placeholder-line.thick { height: 14px; }

    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* ════════════════════════════════════════════
       4. CTA BANNER
    ════════════════════════════════════════════ */
    .cta-section {
        padding: 5rem 0;
        background: var(--surface);
    }

    .cta-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 40%, #8b5cf6 100%);
        border-radius: var(--radius-2xl);
        padding: 3.5rem 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        color: #fff;
    }

    .cta-banner::before {
        content: '';
        position: absolute;
        top: -60%;
        right: -20%;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .cta-banner::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -10%;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(245,158,11,.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .cta-content {
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: 0 auto;
    }

    .cta-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        background: rgba(255,255,255,.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.2);
        color: #fff;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: .35rem .85rem;
        border-radius: 100px;
        margin-bottom: 1.5rem;
    }

    .cta-headline {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -.025em;
        margin-bottom: .75rem;
    }

    .cta-subtitle {
        font-size: 1rem;
        color: rgba(255,255,255,.8);
        line-height: 1.65;
        margin-bottom: 2rem;
    }

    .cta-actions {
        display: flex;
        gap: .75rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-cta-white {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .8rem 2rem;
        font-size: .9rem;
        font-weight: 700;
        font-family: inherit;
        color: var(--brand);
        background: #fff;
        border: none;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-cta-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,.2);
        color: var(--brand-hover);
    }

    .btn-cta-outline {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .8rem 2rem;
        font-size: .9rem;
        font-weight: 600;
        font-family: inherit;
        color: #fff;
        background: transparent;
        border: 1.5px solid rgba(255,255,255,.4);
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-cta-outline:hover {
        background: rgba(255,255,255,.12);
        border-color: rgba(255,255,255,.7);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ════════════════════════════════════════════
       ANIMATIONS
    ════════════════════════════════════════════ */
    .fade-in-up {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity .6s cubic-bezier(.4,0,.2,1), transform .6s cubic-bezier(.4,0,.2,1);
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* stagger children */
    .stagger-children .fade-in-up:nth-child(1) { transition-delay: .05s; }
    .stagger-children .fade-in-up:nth-child(2) { transition-delay: .12s; }
    .stagger-children .fade-in-up:nth-child(3) { transition-delay: .19s; }
    .stagger-children .fade-in-up:nth-child(4) { transition-delay: .26s; }

    /* ════════════════════════════════════════════
       RESPONSIVE
    ════════════════════════════════════════════ */
    @media (max-width: 1024px) {
        .hero-headline { font-size: 2.4rem; }
        .features-grid { grid-template-columns: repeat(2, 1fr); }
        .courses-grid  { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .hero-inner {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }

        .hero-copy { max-width: 100%; }
        .hero-headline { font-size: 2rem; }
        .hero-subtitle { max-width: 100%; }

        .hero-bento-container {
            max-width: 100%;
        }

        .section-title { font-size: 1.6rem; }

        .courses-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .cta-banner { padding: 2.5rem 1.5rem; }
        .cta-headline { font-size: 1.6rem; }
    }

    @media (max-width: 576px) {
        .hero-section { padding: 3rem 0 4rem; }
        .features-grid,
        .courses-grid { grid-template-columns: 1fr; }

        .hero-headline { font-size: 1.7rem; }
        .hero-stats { gap: 1.25rem; }

        .cta-actions { flex-direction: column; }
        .bento-card { padding: 1rem; }
        .bento-grid { gap: .75rem; }
    }

    @media (max-width: 420px) {
        .bento-grid { grid-template-columns: 1fr; }
        .bento-card-main { grid-column: span 1; }
    }
</style>

<div class="landing-page">

    {{-- ════════════════════════════════════════════════════════════
         SECTION 1 — HERO
    ════════════════════════════════════════════════════════════ --}}
    <section class="hero-section">
        <div class="section-container">
            <div class="hero-inner">

                {{-- Left — Copy --}}
                <div class="hero-copy">
                    <div class="hero-badge">
                        <span class="badge-dot"></span>
                        ✨ Nền tảng E-Learning Tích hợp AI Thông minh
                    </div>

                    <h1 class="hero-headline">
                        Học tập thông minh,<br>
                        <span class="text-gradient">Bứt phá giới hạn</span><br>
                        cùng Trợ lý AI
                    </h1>

                    <p class="hero-subtitle">
                        Khám phá lộ trình học tập tối ưu, tự động tạo đề thi trắc nghiệm bằng AI
                        và nhận giải thích chi tiết tức thì.
                    </p>

                    {{-- Dynamic CTA --}}
                    <div class="hero-cta">
                        @if (Auth::check())
                            <a href="{{ route('dashboard') }}" class="btn-primary-cta">
                                <i class="fas fa-rocket"></i>
                                Vào học ngay
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn-secondary-cta">
                                <i class="fas fa-th-large"></i>
                                Bảng điều khiển
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary-cta">
                                <i class="fas fa-rocket"></i>
                                Bắt đầu học ngay
                            </a>
                            <a href="{{ route('register') }}" class="btn-secondary-cta">
                                <i class="fas fa-user-plus"></i>
                                Đăng ký miễn phí
                            </a>
                        @endif
                    </div>

                    {{-- Social proof --}}
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-icon purple">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div class="hero-stat-value">10,000+</div>
                                <div class="hero-stat-label">Học viên</div>
                            </div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-icon emerald">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <div class="hero-stat-value">500+</div>
                                <div class="hero-stat-label">Khóa học</div>
                            </div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-icon amber">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <div class="hero-stat-value">4.9/5</div>
                                <div class="hero-stat-label">Đánh giá</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right — Bento Grid UI Widget --}}
                <div class="hero-bento-container relative w-full max-w-lg mx-auto">
                    {{-- Ambient Radial Gradient Glow --}}
                    <div class="hero-ambient-glow absolute -inset-6 bg-gradient-to-tr from-indigo-500/20 via-purple-500/20 to-pink-500/10 blur-3xl rounded-3xl -z-10 pointer-events-none"></div>

                    {{-- Bento Grid Cards --}}
                    <div class="bento-grid grid grid-cols-2 gap-4 relative z-10">

                        {{-- Card 1: Main AI Assistant Simulation (Top Full Width) --}}
                        <div class="bento-card bento-card-main col-span-2 backdrop-blur-md bg-white/80 border border-slate-100 shadow-xl rounded-2xl p-5">
                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20">
                                        <i class="fas fa-robot text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 leading-tight">Trợ lý AI Gemini 24/7</div>
                                        <div class="text-[11px] text-slate-400">Tự động hóa học tập</div>
                                    </div>
                                </div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="bento-pulse-dot"></span>
                                    <span>Trực tuyến</span>
                                </div>
                            </div>

                            {{-- Student Prompt Bubble --}}
                            <div class="student-prompt-bubble flex items-start gap-2.5 bg-indigo-50/60 border border-indigo-100/80 rounded-xl p-3 mb-3 text-xs text-slate-700">
                                <div class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center flex-shrink-0 text-[10px] font-bold">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-slate-900 text-[11px] mb-0.5">Bạn</div>
                                    <p class="m-0 text-slate-700 leading-relaxed font-normal">"Tạo cho mình đề trắc nghiệm lập trình Web và AI..."</p>
                                </div>
                            </div>

                            {{-- AI Response Indicator --}}
                            <div class="ai-response-indicator relative overflow-hidden bg-gradient-to-r from-indigo-50/90 via-purple-50/70 to-pink-50/80 border border-indigo-100 rounded-xl p-3 text-xs text-indigo-950 flex items-center gap-2.5 shadow-xs">
                                <div class="ai-sparkle-icon w-6 h-6 rounded-lg bg-gradient-to-tr from-indigo-500 to-purple-600 text-white flex items-center justify-center flex-shrink-0 text-xs">
                                    <i class="fas fa-wand-magic-sparkles animate-spin-slow"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-slate-800 text-[11.5px] truncate">
                                        Đang phân tích cấu trúc khóa học &amp; tạo 10 câu hỏi...
                                    </div>
                                </div>
                                <div class="ai-typing-indicator flex items-center gap-1 flex-shrink-0">
                                    <span class="typing-dot"></span>
                                    <span class="typing-dot"></span>
                                    <span class="typing-dot"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Study Streak & Motivation (Bottom Left) --}}
                        <div class="bento-card col-span-1 backdrop-blur-md bg-white/80 border border-slate-100 shadow-xl rounded-2xl p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-500 flex items-center justify-center text-lg">
                                        🔥
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 uppercase tracking-wider">
                                        Streak
                                    </span>
                                </div>
                                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Chuỗi học tập</div>
                                <div class="text-xl font-extrabold text-slate-800 tracking-tight mb-2">7 ngày liên tiếp</div>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center gap-1.5 text-[11px] text-amber-600 font-semibold">
                                <i class="fas fa-trophy text-[10px]"></i>
                                <span>Top 5% học viên chăm chỉ</span>
                            </div>
                        </div>

                        {{-- Card 3: Target & Performance (Bottom Right) --}}
                        <div class="bento-card col-span-1 backdrop-blur-md bg-white/80 border border-slate-100 shadow-xl rounded-2xl p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 flex items-center justify-center text-sm">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 uppercase tracking-wider">
                                        Mục tiêu
                                    </span>
                                </div>
                                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Tiến độ mục tiêu</div>
                                <div class="flex items-baseline justify-between mb-2">
                                    <div class="text-xl font-extrabold text-slate-800 tracking-tight">85%</div>
                                    <span class="text-[11px] font-semibold text-indigo-600">hoàn thành</span>
                                </div>
                            </div>
                            <div class="pt-2">
                                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200/50">
                                    <div class="bento-progress-bar h-full rounded-full" style="width: 85%;"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         SECTION 2 — CORE FEATURES
    ════════════════════════════════════════════════════════════ --}}
    <section class="features-section">
        <div class="section-container">
            <div class="features-header">
                <span class="section-eyebrow">
                    <i class="fas fa-bolt"></i>
                    Tính năng nổi bật
                </span>
                <h2 class="section-title">Công nghệ AI hỗ trợ mọi bước học tập</h2>
                <p class="section-subtitle">
                    Trải nghiệm nền tảng học tập thế hệ mới với trí tuệ nhân tạo tích hợp sâu vào từng tính năng.
                </p>
            </div>

            <div class="features-grid stagger-children">
                {{-- Feature 1 --}}
                <div class="feature-card fade-in-up">
                    <div class="feature-icon-box indigo">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>Tạo đề thi tự động bằng AI</h3>
                    <p>
                        Gemini AI phân tích nội dung bài giảng và tạo ngân hàng câu hỏi trắc nghiệm
                        chất lượng cao tức thì, tiết kiệm thời gian cho giảng viên.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="feature-card fade-in-up">
                    <div class="feature-icon-box emerald">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Chấm điểm & Giải thích đáp án</h3>
                    <p>
                        Phân tích nguyên nhân đúng/sai chi tiết cho từng câu trả lời, giúp
                        học viên hiểu sâu kiến thức thay vì chỉ biết kết quả.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="feature-card fade-in-up">
                    <div class="feature-icon-box amber">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Theo dõi tiến độ & Thống kê</h3>
                    <p>
                        Báo cáo kết quả học tập trực quan với biểu đồ chi tiết, giúp
                        tối ưu hóa điểm số và lộ trình cá nhân hóa.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="feature-card fade-in-up">
                    <div class="feature-icon-box rose">
                        <i class="fas fa-mobile-screen-button"></i>
                    </div>
                    <h3>Học tập mọi lúc, mọi nơi</h3>
                    <p>
                        Tương thích hoàn toàn trên mọi thiết bị — điện thoại, máy tính bảng
                        và máy tính — để bạn không bỏ lỡ bất kỳ bài học nào.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         SECTION 3 — FEATURED COURSES
    ════════════════════════════════════════════════════════════ --}}
    <section class="courses-section">
        <div class="section-container">
            <div class="courses-header">
                <div>
                    <span class="section-eyebrow">
                        <i class="fas fa-graduation-cap"></i>
                        Khóa học tiêu biểu
                    </span>
                    <h2 class="section-title">Khám phá khóa học phù hợp với bạn</h2>
                    <p class="section-subtitle">
                        Hàng trăm khóa học chất lượng cao từ các giảng viên hàng đầu, được hỗ trợ bởi AI.
                    </p>
                </div>
            </div>

            <div class="courses-grid stagger-children">
                @if (!empty($danhSachKhoaHoc) && count($danhSachKhoaHoc) > 0)
                    @foreach ($danhSachKhoaHoc->take(6) as $khoaHoc)
                        <div class="course-card fade-in-up">
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

                                {{-- Level badge --}}
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
                                <span class="course-level-badge {{ $levelClass }}">{{ $levelLabel }}</span>

                                {{-- Duration --}}
                                @if ($khoaHoc->thoi_luong_phut)
                                    <span class="course-duration-badge">
                                        <i class="fas fa-clock"></i>
                                        {{ floor($khoaHoc->thoi_luong_phut / 60) > 0 ? floor($khoaHoc->thoi_luong_phut / 60) . 'h ' : '' }}{{ $khoaHoc->thoi_luong_phut % 60 > 0 ? ($khoaHoc->thoi_luong_phut % 60) . 'p' : '' }}
                                    </span>
                                @endif
                            </div>

                            <div class="course-body">
                                @if ($khoaHoc->danhMuc)
                                    <div class="course-category">{{ $khoaHoc->danhMuc->ten_danh_muc }}</div>
                                @endif

                                <h3 class="course-title">{{ $khoaHoc->ten_khoa_hoc }}</h3>

                                @if ($khoaHoc->mo_ta_khoa_hoc)
                                    <p class="course-desc">{{ $khoaHoc->mo_ta_khoa_hoc }}</p>
                                @endif

                                <div class="course-meta">
                                    @if ($khoaHoc->giangVien)
                                        <div class="course-instructor">
                                            <span class="course-instructor-avatar">
                                                {{ mb_substr($khoaHoc->giangVien->ho_va_ten, 0, 1) }}
                                            </span>
                                            {{ $khoaHoc->giangVien->ho_va_ten }}
                                        </div>
                                    @else
                                        <div class="course-instructor">
                                            <span class="course-instructor-avatar">
                                                <i class="fas fa-user" style="font-size:.55rem"></i>
                                            </span>
                                            Giảng viên
                                        </div>
                                    @endif

                                    <a href="#" class="course-detail-btn">
                                        Chi tiết
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback placeholder cards when no data --}}
                    @for ($i = 0; $i < 3; $i++)
                        <div class="course-card placeholder-card fade-in-up">
                            <div class="course-thumb">
                                <div class="course-thumb-placeholder">
                                    <i class="fas fa-photo-film"></i>
                                </div>
                            </div>
                            <div class="course-body">
                                <div class="placeholder-line w-50" style="margin-bottom: .6rem; height: 8px;"></div>
                                <div class="placeholder-line w-100 thick" style="margin-bottom: .4rem;"></div>
                                <div class="placeholder-line w-75 thick" style="margin-bottom: 1rem;"></div>
                                <div class="placeholder-line w-100" style="margin-bottom: .35rem;"></div>
                                <div class="placeholder-line w-75" style="margin-bottom: 1.25rem;"></div>
                                <div class="course-meta" style="border-top: 1px solid var(--border-light); padding-top: 1rem;">
                                    <div style="display:flex; align-items:center; gap:.5rem;">
                                        <div style="width:28px;height:28px;border-radius:50%;background:#e2e8f0;"></div>
                                        <div class="placeholder-line" style="width:80px; height:8px;"></div>
                                    </div>
                                    <div class="placeholder-line" style="width:60px; height:8px;"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         SECTION 4 — CTA BANNER
    ════════════════════════════════════════════════════════════ --}}
    <section class="cta-section">
        <div class="section-container">
            <div class="cta-banner">
                <div class="cta-content">
                    <div class="cta-badge">
                        <i class="fas fa-graduation-cap"></i>
                        Bắt đầu hành trình ngay hôm nay
                    </div>
                    <h2 class="cta-headline">Sẵn sàng nâng cấp hành trình học tập của bạn?</h2>
                    <p class="cta-subtitle">
                        Tham gia cộng đồng hơn 10,000 học viên đang sử dụng EduMind AI
                        để đạt kết quả vượt trội trong học tập.
                    </p>
                    <div class="cta-actions">
                        @if (Auth::check())
                            <a href="{{ route('dashboard') }}" class="btn-cta-white">
                                <i class="fas fa-rocket"></i>
                                Tiếp tục học ngay
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-cta-white">
                                <i class="fas fa-rocket"></i>
                                Đăng ký miễn phí
                            </a>
                            <a href="{{ route('login') }}" class="btn-cta-outline">
                                <i class="fas fa-right-to-bracket"></i>
                                Đã có tài khoản? Đăng nhập
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

{{-- ══════════════════════════════════════════════════════════════
     Vanilla JS — Scroll-triggered fade-in animations
══════════════════════════════════════════════════════════════ --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const targets = document.querySelectorAll('.fade-in-up');

        if (!targets.length) return;

        // Use IntersectionObserver for performant scroll detection
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15,
                rootMargin: '0px 0px -40px 0px'
            });

            targets.forEach(function (el) {
                observer.observe(el);
            });
        } else {
            // Fallback: just show everything
            targets.forEach(function (el) {
                el.classList.add('visible');
            });
        }
    });
</script>

@endsection
