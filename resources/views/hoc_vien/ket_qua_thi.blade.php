@extends('layouts.khung')

@section('title', 'Kết Quả Bài Kiểm Tra - EduMind AI')

@push('styles')
<style>
    .result-hero-card {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.03);
        position: relative;
        overflow: hidden;
    }

    .result-hero-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: {{ $diemSo >= 5.0 ? 'linear-gradient(90deg, #10b981, #06b6d4)' : 'linear-gradient(90deg, #f59e0b, #ef4444)' }};
    }

    .score-badge-circle {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0.5rem 0 1.25rem;
    }

    .score-number {
        font-size: 4rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -0.03em;
    }

    .score-number.pass {
        color: #059669;
    }

    .score-number.fail {
        color: #dc2626;
    }

    .mini-stat-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.15rem 1rem;
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .mini-stat-box:hover {
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
    }

    .question-result-card {
        background: #ffffff;
        border-radius: 1rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .question-result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    }

    .ai-explanation-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 0.85rem;
        padding: 1rem 1.15rem;
        color: #92400e;
    }

    .choice-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container pt-3 pb-5" style="max-width: 880px;">

    {{-- Breadcrumb điều hướng --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb text-xs">
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('hoc_vien.tong_quan') }}" class="text-decoration-none text-muted">Học viên</a>
            </li>
            @if(isset($deThi) && $deThi->baiHoc)
                <li class="breadcrumb-item">
                    <a href="{{ route('student.lesson.show', $deThi->id_bai_hoc) }}" class="text-decoration-none text-muted text-truncate" style="max-width: 220px;">
                        {{ $deThi->baiHoc->tieu_de_bai_hoc }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Kết quả kiểm tra</li>
        </ol>
    </nav>

    {{-- ══════════════════════════════════════════════════════════════
         1. KHUNG ĐIỂM SỐ & ĐÁNH GIÁ TỔNG QUAN (HERO RESULT CARD)
    ══════════════════════════════════════════════════════════════ --}}
    <div class="result-hero-card p-4 p-md-5 mb-4 text-center">

        {{-- Huy hiệu trạng thái --}}
        @if ($diemSo >= 5.0)
            <div class="d-inline-flex align-items-center gap-2 px-3.5 py-1.5 rounded-pill bg-success-subtle text-success border border-success-subtle fw-semibold text-xs mb-3 shadow-xs">
                <i class="fa-solid fa-circle-check fs-6"></i>
                <span>Chúc mừng! Bạn đã vượt qua bài thi thành công</span>
            </div>
        @else
            <div class="d-inline-flex align-items-center gap-2 px-3.5 py-1.5 rounded-pill bg-warning-subtle text-warning border border-warning-subtle fw-semibold text-xs mb-3 shadow-xs">
                <i class="fa-solid fa-triangle-exclamation fs-6"></i>
                <span>Hãy cố gắng ôn tập và thi lại để nắm chắc kiến thức nhé!</span>
            </div>
        @endif

        {{-- Tiêu đề đề thi / bài học nếu có --}}
        @if(isset($deThi))
            <h4 class="fw-bold text-dark text-break text-wrap mb-1 px-md-3">
                {{ $deThi->tieu_de_de_thi }}
            </h4>
            @if($deThi->baiHoc)
                <p class="text-muted text-xs mb-3">
                    <i class="fa-solid fa-book-bookmark me-1 text-primary"></i>
                    {{ $deThi->baiHoc->chuongMuc->khoaHoc->tieu_de_khoa_hoc ?? '' }}
                    <span class="mx-1.5">•</span>
                    {{ $deThi->baiHoc->tieu_de_bai_hoc }}
                </p>
            @endif
        @else
            <h4 class="fw-bold text-dark mb-2">Kết Quả Bài Kiểm Tra Đánh Giá</h4>
        @endif

        {{-- Điểm số to bản nổi bật --}}
        <div class="score-badge-circle">
            <div class="score-number {{ $diemSo >= 5.0 ? 'pass' : 'fail' }}">
                {{ number_format($diemSo, 1) }}
                <span class="fs-4 text-muted fw-normal">/ 10</span>
            </div>
            <span class="text-muted text-xs mt-1">Thang điểm tiêu chuẩn EduMind AI</span>
        </div>

        {{-- 3 thẻ thống kê phụ nằm ngang (Grid 3 cột) --}}
        @php
            $tyLe = $tongSoCau > 0 ? round(($soCauDung / $tongSoCau) * 100) : 0;
            $isPass = $diemSo >= 5.0;
        @endphp
        <div class="row g-3 my-2 text-start">
            {{-- Thẻ 1: Tỷ lệ chính xác --}}
            <div class="col-4">
                <div class="mini-stat-box text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-2 mx-auto" style="width: 38px; height: 38px;">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <div class="text-xs text-muted fw-semibold mb-0.5">Tỷ lệ chính xác</div>
                    <div class="fs-4 fw-bold text-dark">{{ $tyLe }}%</div>
                </div>
            </div>

            {{-- Thẻ 2: Số câu đúng --}}
            <div class="col-4">
                <div class="mini-stat-box text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success mb-2 mx-auto" style="width: 38px; height: 38px;">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div class="text-xs text-muted fw-semibold mb-0.5">Số câu đúng</div>
                    <div class="fs-4 fw-bold text-dark">{{ $soCauDung }}/{{ $tongSoCau }}</div>
                </div>
            </div>

            {{-- Thẻ 3: Đánh giá chung --}}
            <div class="col-4">
                <div class="mini-stat-box text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle {{ $isPass ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} mb-2 mx-auto" style="width: 38px; height: 38px;">
                        <i class="fa-solid {{ $isPass ? 'fa-award' : 'fa-circle-xmark' }}"></i>
                    </div>
                    <div class="text-xs text-muted fw-semibold mb-0.5">Kết quả đánh giá</div>
                    <div class="fs-5 fw-bold {{ $isPass ? 'text-success' : 'text-danger' }} mt-0.5">
                        {{ $isPass ? 'ĐẠT YÊU CẦU' : 'CHƯA ĐẠT' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Nút thao tác nhanh --}}
        <div class="d-flex flex-wrap justify-content-center gap-3 mt-4 pt-2">
            {{-- Nút phụ: Thi lại bài này --}}
            @if(isset($deThi))
                <a href="{{ route('quiz.take', $deThi->id_de_thi) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold text-sm d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>Thi lại bài này</span>
                </a>
            @else
                <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold text-sm d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>Làm lại bài thi</span>
                </a>
            @endif

            {{-- Nút chính: Tiếp tục bài học --}}
            @if(isset($deThi) && $deThi->id_bai_hoc)
                <a href="{{ route('student.lesson.show', $deThi->id_bai_hoc) }}" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold text-sm shadow-sm d-inline-flex align-items-center gap-2">
                    <span>Tiếp tục bài học</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            @else
                <a href="{{ route('hoc_vien.tong_quan') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold text-sm shadow-sm d-inline-flex align-items-center gap-2">
                    <span>Quay về khóa học của tôi</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            @endif
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════
         2. DANH SÁCH CHI TIẾT TỪNG CÂU HỎI & AI GIẢI THÍCH
    ══════════════════════════════════════════════════════════════ --}}
    <div class="d-flex align-items-center justify-content-between mb-3 px-1">
        <div>
            <h5 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2">
                <i class="fa-solid fa-brain text-primary"></i>
                <span>Chi Tiết Câu Trả Lời & Giải Thích Từ AI Gemini</span>
            </h5>
            <span class="text-muted text-xs">Đối chiếu lựa chọn của bạn với đáp án chính xác và lời giải chi tiết.</span>
        </div>
        <span class="badge bg-light text-muted border px-2.5 py-1 text-xs">
            Tổng cộng: {{ count($chiTietKetQua) }} câu
        </span>
    </div>

    @forelse ($chiTietKetQua as $index => $item)
        @php
            $isCorrect = (bool)($item['ket_qua'] ?? false);
            $rawQuestion = $item['cau_hoi'] ?? '';
            $isAiGenerated = \Illuminate\Support\Str::startsWith($rawQuestion, '[AI Tạo]');
            $cleanQuestion = trim(str_replace('[AI Tạo]', '', $rawQuestion));

            $chonKyTu = $item['phuong_an_chon']->ky_tu_phuong_an ?? $item['phuong_an_chon']->ky_tu ?? '';
            $chonNoiDung = $item['phuong_an_chon']->noi_dung_phuong_an ?? $item['phuong_an_chon']->noi_dung ?? 'Chưa chọn';

            $dungKyTu = $item['phuong_an_dung']->ky_tu_phuong_an ?? $item['phuong_an_dung']->ky_tu ?? '';
            $dungNoiDung = $item['phuong_an_dung']->noi_dung_phuong_an ?? $item['phuong_an_dung']->noi_dung ?? '';
        @endphp

        <div class="question-result-card card mb-3 border shadow-xs overflow-hidden {{ $isCorrect ? 'border-success-subtle' : 'border-danger-subtle' }}">
            {{-- Header câu hỏi: Số thứ tự + Badge Đúng/Sai --}}
            <div class="card-header py-3 px-3.5 bg-white border-bottom d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <span class="badge {{ $isCorrect ? 'bg-success text-white' : 'bg-danger text-white' }} rounded-pill px-2.5 py-1 text-xs fw-semibold flex-shrink-0">
                        Câu {{ $index + 1 }}
                    </span>
                    @if($isAiGenerated)
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill text-2xs fw-semibold flex-shrink-0">
                            <i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI Gemini
                        </span>
                    @endif
                </div>

                {{-- Badge trạng thái Đúng / Sai góc phải --}}
                @if ($isCorrect)
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 text-xs fw-semibold d-inline-flex align-items-center gap-1.5 flex-shrink-0">
                        <i class="fa-solid fa-check"></i>
                        <span>Chính xác ✓</span>
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 text-xs fw-semibold d-inline-flex align-items-center gap-1.5 flex-shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Chưa đúng ✕</span>
                    </span>
                @endif
            </div>

            {{-- Thân câu hỏi: Nội dung câu hỏi + Phương án --}}
            <div class="card-body p-3.5">
                {{-- Nội dung câu hỏi --}}
                <div class="fw-bold text-dark text-break text-wrap mb-3 text-sm">
                    {{ $cleanQuestion }}
                </div>

                {{-- Phương án đã chọn & Đáp án chính xác --}}
                <div class="d-flex flex-column gap-2 mb-2">
                    {{-- Phương án bạn chọn --}}
                    <div class="p-2.5 rounded-3 border d-flex align-items-start gap-2.5 {{ $isCorrect ? 'bg-success-subtle border-success-subtle text-success' : 'bg-danger-subtle border-danger-subtle text-danger' }}">
                        <span class="choice-badge {{ $isCorrect ? 'bg-success text-white' : 'bg-danger text-white' }}">
                            {{ $chonKyTu ?: '?' }}
                        </span>
                        <div class="text-xs text-break text-wrap flex-grow-1">
                            <span class="fw-semibold text-muted d-block mb-0.5">Phương án bạn chọn:</span>
                            <span class="fw-bold {{ $isCorrect ? 'text-success' : 'text-danger' }}">
                                {{ $chonKyTu ? $chonKyTu . '. ' : '' }}{{ $chonNoiDung }}
                            </span>
                        </div>
                    </div>

                    {{-- Nếu học viên chọn sai: Hiển thị rõ ràng đáp án chính xác --}}
                    @if (!$isCorrect && $dungNoiDung)
                        <div class="p-2.5 rounded-3 border bg-success-subtle border-success-subtle text-success d-flex align-items-start gap-2.5">
                            <span class="choice-badge bg-success text-white">
                                {{ $dungKyTu ?: '✓' }}
                            </span>
                            <div class="text-xs text-break text-wrap flex-grow-1">
                                <span class="fw-semibold text-muted d-block mb-0.5">Đáp án chính xác:</span>
                                <span class="fw-bold text-success">
                                    {{ $dungKyTu ? $dungKyTu . '. ' : '' }}{{ $dungNoiDung }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Khối Giải thích kiến thức từ Trợ lý AI Gemini --}}
                @if (!empty($item['giai_thich']))
                    <div class="ai-explanation-box mt-3 text-xs">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fa-solid fa-lightbulb fs-5 text-warning flex-shrink-0 mt-0.5"></i>
                            <div class="text-break text-wrap flex-grow-1">
                                <strong class="text-dark d-block mb-1">
                                    💡 Giải thích cốt lõi từ Trợ lý AI:
                                </strong>
                                <span class="text-dark-emphasis">
                                    {{ $item['giai_thich'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
            <div class="rounded-circle p-3 bg-secondary-subtle text-secondary d-inline-flex mx-auto mb-3 fs-3">
                <i class="fa-solid fa-file-circle-question"></i>
            </div>
            <h6 class="fw-bold text-dark">Không có dữ liệu câu trả lời chi tiết</h6>
            <p class="text-muted text-xs mb-3">Vui lòng quay lại để thực hiện lại bài kiểm tra.</p>
            <div>
                <a href="{{ route('hoc_vien.tong_quan') }}" class="btn btn-primary rounded-pill px-4 py-2 text-xs fw-semibold">
                    Về trang tổng quan
                </a>
            </div>
        </div>
    @endforelse

    {{-- Footer điều hướng dưới cùng --}}
    <div class="text-center mt-4 pt-2">
        @if(isset($deThi) && $deThi->id_bai_hoc)
            <a href="{{ route('student.lesson.show', $deThi->id_bai_hoc) }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold text-sm d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Trở lại bài học: {{ $deThi->baiHoc->tieu_de_bai_hoc ?? '' }}</span>
            </a>
        @else
            <a href="{{ route('hoc_vien.tong_quan') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold text-sm d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Quay về trang tổng quan học viên</span>
            </a>
        @endif
    </div>

</div>
@endsection