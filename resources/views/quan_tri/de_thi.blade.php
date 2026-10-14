@extends('layouts.khung')

@section('title', 'Giám Sát Đề Thi Trắc Nghiệm AI - EduMind AI')

@section('content')
<div class="container py-4">

    {{-- Breadcrumb & Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('quan_tri.tong_quan') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-house me-1"></i> Quản trị</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Giám sát đề thi AI</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i> Giám Sát Đề Thi Trắc Nghiệm AI
            </h2>
            <p class="text-muted small mb-0 mt-1">Quản lý, kiểm duyệt chất lượng câu hỏi do Gemini AI tạo tự động và thống kê số lượt thi của học viên.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('quan_tri.tong_quan') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-medium shadow-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Về Tổng quan
            </a>
            <a href="{{ route('hoc_vien.tong_quan') }}" class="btn btn-primary rounded-pill px-3 py-2 btn-sm fw-medium shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> Tạo đề mới qua AI
            </a>
        </div>
    </div>

    {{-- Stats Cards Row --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Tổng Đề Thi AI</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $danhSachDeThi->count() }}</div>
                        <div class="text-xs text-muted mt-0.5">Gắn với từng bài học</div>
                    </div>
                    <div class="rounded-3 p-3 bg-amber-50 text-amber-600 fs-4">
                        <i class="fa-solid fa-clipboard-question"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Tổng Câu Hỏi Đã Tạo</div>
                        <div class="fs-3 fw-bold text-indigo-600 mt-1">{{ $tongSoCauHoi }}</div>
                        <div class="text-xs text-muted mt-0.5">Sinh từ Gemini AI & Giảng viên</div>
                    </div>
                    <div class="rounded-3 p-3 bg-indigo-50 text-indigo-600 fs-4">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Lượt Học Viên Đã Thi</div>
                        <div class="fs-3 fw-bold text-success mt-1">{{ $tongSoLuotThi }}</div>
                        <div class="text-xs text-muted mt-0.5">Được chấm điểm tự động</div>
                    </div>
                    <div class="rounded-3 p-3 bg-success-subtle text-success fs-4">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quizzes Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-list-ol text-primary"></i> Danh Sách Toàn Bộ Đề Thi
                </h5>
                <span class="text-muted small">Kiểm tra chi tiết từng câu hỏi trong đề hoặc xóa đề thi AI bị lỗi/trùng lặp.</span>
            </div>
            <span class="badge bg-light text-muted border px-2.5 py-1.5">{{ $danhSachDeThi->count() }} đề thi</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase fw-semibold">
                    <tr>
                        <th class="ps-4" style="width: 70px;">ID</th>
                        <th>Đề thi trắc nghiệm</th>
                        <th>Bài học & Khóa học</th>
                        <th>Thời lượng</th>
                        <th>Số câu hỏi</th>
                        <th>Lượt đã thi</th>
                        <th class="text-end pe-4" style="width: 170px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($danhSachDeThi as $dt)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">#{{ $dt->id_de_thi }}</td>
                            <td>
                                <div class="fw-bold text-dark mb-0.5">{{ $dt->tieu_de_de_thi }}</div>
                                <div class="text-muted text-xs">
                                    Điểm qua môn: <strong class="text-dark">{{ $dt->diem_so_qua_mon ?? 5 }}/{{ $dt->diem_so_toi_da ?? 10 }}</strong>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark small mb-0.5">
                                    <i class="fa-solid fa-book-bookmark text-indigo-500 me-1"></i>
                                    {{ $dt->baiHoc->tieu_de_bai_hoc ?? 'Bài học liên kết' }}
                                </div>
                                <div class="text-muted text-xs">
                                    {{ $dt->baiHoc->chuongMuc->khoaHoc->tieu_de_khoa_hoc ?? 'Khóa học' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="fa-regular fa-clock me-1 text-muted"></i>
                                    {{ $dt->thoi_luong_lam_bai_phut ?? 15 }} phút
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-pill px-2.5 py-1 fw-semibold">
                                    <i class="fa-solid fa-list-check me-1"></i> {{ $dt->danh_sach_cau_hoi_count ?? 0 }} câu
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-pill px-2.5 py-1 fw-semibold">
                                    <i class="fa-solid fa-user-check me-1"></i> {{ $dt->danh_sach_luot_lam_bai_count ?? 0 }} lượt
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center gap-1.5">
                                    {{-- Nút Xem danh sách câu hỏi trong đề (Modal) --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#modalDetailQuiz{{ $dt->id_de_thi }}" title="Xem chi tiết các câu hỏi">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    {{-- Nút Thi thử --}}
                                    <a href="{{ route('quiz.take', $dt->id_de_thi) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-3 px-2.5 py-1" title="Vào thi thử">
                                        <i class="fa-solid fa-play"></i>
                                    </a>

                                    {{-- Nút Xóa đề thi --}}
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#modalDeleteQuiz{{ $dt->id_de_thi }}" title="Xóa đề thi">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>

                                {{-- Modal Chi tiết câu hỏi trong đề --}}
                                <div class="modal fade text-start" id="modalDetailQuiz{{ $dt->id_de_thi }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-bottom py-3">
                                                <div>
                                                    <h5 class="modal-title fw-bold text-dark">
                                                        <i class="fa-solid fa-clipboard-question text-primary me-1"></i> Chi Tiết Câu Hỏi: {{ $dt->tieu_de_de_thi }}
                                                    </h5>
                                                    <span class="text-muted small">Bài học: {{ $dt->baiHoc->tieu_de_bai_hoc ?? '' }}</span>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4 bg-light-subtle">
                                                @forelse ($dt->danhSachCauHoi as $idx => $cauHoi)
                                                    <div class="card border-0 shadow-sm rounded-3 mb-3 p-3 bg-white">
                                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                            <div class="fw-bold text-dark">
                                                                Câu {{ $idx + 1 }}: {{ $cauHoi->noi_dung_cau_hoi }}
                                                            </div>
                                                            <span class="badge bg-light text-muted border text-xs">
                                                                {{ $cauHoi->nguon_goc_cau_hoi === 'ai_sinh' ? 'AI Sinh' : 'Giảng viên' }}
                                                            </span>
                                                        </div>

                                                        {{-- Các phương án A, B, C, D --}}
                                                        <div class="row g-2 mb-2">
                                                            @foreach ($cauHoi->danhSachPhuongAn as $pa)
                                                                <div class="col-md-6">
                                                                    <div class="p-2 rounded-2 border text-sm {{ $pa->la_dap_an_chinh_xac ? 'bg-success-subtle border-success text-success fw-bold' : 'bg-light text-dark' }}">
                                                                        <span class="me-1 fw-bold">{{ $pa->ky_tu_phuong_an }}.</span> {{ $pa->noi_dung_phuong_an }}
                                                                        @if ($pa->la_dap_an_chinh_xac)
                                                                            <i class="fa-solid fa-circle-check ms-1"></i> (Đáp án đúng)
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        @if ($cauHoi->giai_thich_chi_tiet_dap_an)
                                                            <div class="alert alert-secondary py-1.5 px-2.5 mb-0 small text-muted">
                                                                <i class="fa-solid fa-lightbulb text-warning me-1"></i>
                                                                <strong>Giải thích:</strong> {{ $cauHoi->giai_thich_chi_tiet_dap_an }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <div class="text-center py-4 text-muted">
                                                        Đề thi này chưa có câu hỏi nào.
                                                    </div>
                                                @endforelse
                                            </div>
                                            <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                                                <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 btn-sm" data-bs-dismiss="modal">Đóng</button>
                                                <a href="{{ route('quiz.take', $dt->id_de_thi) }}" target="_blank" class="btn btn-primary rounded-pill px-3 py-1.5 btn-sm fw-semibold">
                                                    <i class="fa-solid fa-play me-1"></i> Thi thử đề này
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Xác nhận Xóa đề thi --}}
                                <div class="modal fade text-start" id="modalDeleteQuiz{{ $dt->id_de_thi }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('quan_tri.de_thi.xoa', $dt->id_de_thi) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header border-bottom py-3">
                                                    <h5 class="modal-title fw-bold text-danger">
                                                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Xác Nhận Xóa Đề Thi
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4 text-center">
                                                    <div class="text-danger fs-1 mb-2"><i class="fa-solid fa-circle-exclamation"></i></div>
                                                    <h6 class="fw-bold text-dark">Bạn có chắc chắn muốn xóa đề thi này?</h6>
                                                    <div class="p-3 bg-light rounded-3 my-2 text-start">
                                                        <div class="fw-bold text-dark">{{ $dt->tieu_de_de_thi }}</div>
                                                        <div class="small text-muted">Bài học: {{ $dt->baiHoc->tieu_de_bai_hoc ?? 'N/A' }}</div>
                                                        <div class="small text-muted">Bao gồm: {{ $dt->danh_sach_cau_hoi_count ?? 0 }} câu hỏi, {{ $dt->danh_sach_luot_lam_bai_count ?? 0 }} lượt học viên đã thi</div>
                                                    </div>
                                                    <p class="text-danger small mb-0">Toàn bộ câu hỏi, phương án và lịch sử thi của đề này sẽ bị xóa vĩnh viễn.</p>
                                                </div>
                                                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4 justify-content-center">
                                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4 py-1.5 btn-sm fw-semibold">Xóa vĩnh viễn</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fs-2 text-secondary opacity-50 mb-2 d-block"></i>
                                Chưa có đề thi nào trong hệ thống.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
