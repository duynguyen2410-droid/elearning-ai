@extends('layouts.khung')

@section('title', 'Quản Lý Danh Mục & Kiểm Duyệt Khóa Học - EduMind AI')

@section('content')
<div class="container py-4">

    {{-- Breadcrumb & Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('quan_tri.tong_quan') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-house me-1"></i> Quản trị</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Khóa học & Danh mục</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-book-open text-primary"></i> Quản Lý Danh Mục & Giám Sát Khóa Học
            </h2>
            <p class="text-muted small mb-0 mt-1">Quản lý cơ cấu phân loại danh mục và kiểm duyệt các khóa học trực tuyến do giảng viên phát hành.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('quan_tri.tong_quan') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-medium shadow-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Về Tổng quan
            </a>
            <button type="button" class="btn btn-primary rounded-pill px-3 py-2 btn-sm fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#modalThemDanhMuc">
                <i class="fa-solid fa-folder-plus me-1"></i> Thêm danh mục mới
            </button>
        </div>
    </div>

    {{-- SECTION 1: DANH MỤC KHÓA HỌC --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-layer-group text-primary"></i> Danh Mục Khóa Học
            </h5>
            <span class="badge bg-light text-muted border px-2.5 py-1.5">{{ $danhSachDanhMuc->count() }} danh mục</span>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @forelse ($danhSachDanhMuc as $dm)
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3.5 rounded-4 border bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 text-xs fw-semibold">
                                        ID #{{ $dm->id_danh_muc }}
                                    </span>
                                    <span class="badge bg-white text-muted border rounded-pill px-2.5 py-1 text-xs">
                                        {{ $dm->danh_sach_khoa_hoc_count ?? 0 }} khóa học
                                    </span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ $dm->ten_danh_muc }}</h6>
                                <p class="text-muted small mb-2 line-clamp-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $dm->mo_ta_danh_muc ?: 'Chưa có mô tả cho danh mục này.' }}
                                </p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                                <span class="text-xs text-muted font-monospace">/{{ $dm->duong_dan_than_thien_slug }}</span>
                                @if (($dm->danh_sach_khoa_hoc_count ?? 0) === 0)
                                    <form action="{{ route('quan_tri.danh_muc.xoa', $dm->id_danh_muc) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Xóa danh mục">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-muted" title="Không thể xóa khi có khóa học">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 text-muted">
                        Chưa có danh mục nào được khởi tạo.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SECTION 2: KIỂM DUYỆT & GIÁM SÁT KHÓA HỌC --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-indigo-600"></i> Danh Sách Khóa Học Trong Hệ Thống
                </h5>
                <span class="text-muted small">Kiểm duyệt tính hợp lệ, công khai hoặc tạm ẩn khóa học (Giảng viên chịu trách nhiệm biên soạn nội dung).</span>
            </div>
            <span class="badge bg-light text-muted border px-2.5 py-1.5">{{ $danhSachKhoaHoc->count() }} khóa học</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase fw-semibold">
                    <tr>
                        <th class="ps-4" style="width: 70px;">ID</th>
                        <th>Tên khóa học & Danh mục</th>
                        <th>Giảng viên phụ trách</th>
                        <th>Cơ cấu giáo trình</th>
                        <th>Trạng thái xuất bản</th>
                        <th class="text-end pe-4" style="width: 170px;">Kiểm duyệt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($danhSachKhoaHoc as $kh)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">#{{ $kh->id_khoa_hoc }}</td>
                            <td>
                                <div class="fw-bold text-dark mb-0.5">{{ $kh->tieu_de_khoa_hoc }}</div>
                                <div class="d-flex align-items-center gap-2 small">
                                    <span class="badge bg-light text-primary border text-xs">
                                        <i class="fa-solid fa-tag me-1"></i> {{ $kh->danhMuc->ten_danh_muc ?? 'Chung' }}
                                    </span>
                                    <span class="text-muted text-xs">
                                        <i class="fa-regular fa-clock me-1"></i> {{ $kh->tong_thoi_luong_phut ?? 120 }} phút
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background: #0284c7; font-size: 0.85rem;">
                                        {{ mb_substr($kh->giangVien->ho_va_ten ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark small">{{ $kh->giangVien->ho_va_ten ?? 'Chưa gán' }}</div>
                                        <div class="text-muted text-xs">{{ $kh->giangVien->dia_chi_email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small text-dark">
                                    <i class="fa-solid fa-list-check text-muted me-1"></i> <strong>{{ $kh->danh_sach_chuong_muc_count ?? 0 }}</strong> chương
                                </div>
                                <div class="small text-muted">
                                    <i class="fa-regular fa-file-video me-1"></i> {{ $kh->danh_sach_bai_hoc_count ?? 0 }} bài học
                                </div>
                            </td>
                            <td>
                                @if (($kh->trang_thai_xuat_ban ?? '') === 'xuat_ban' || ($kh->trang_thai_xuat_ban ?? '') === 'cong_khai' || ($kh->trang_thai_xuat_ban ?? '') === '1')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-semibold">
                                        <i class="fa-solid fa-circle-check me-1"></i> Đã xuất bản
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-semibold">
                                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Chờ duyệt / Tạm ẩn
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center gap-1.5">
                                    {{-- Nút Chuyển đổi duyệt/ẩn --}}
                                    <form action="{{ route('quan_tri.khoa_hoc.duyet', $kh->id_khoa_hoc) }}" method="POST" class="m-0">
                                        @csrf
                                        @if (($kh->trang_thai_xuat_ban ?? '') === 'xuat_ban')
                                            <input type="hidden" name="trang_thai" value="cho_duyet">
                                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-3 px-2.5 py-1" title="Tạm ẩn khóa học khỏi học viên">
                                                <i class="fa-solid fa-eye-slash"></i>
                                            </button>
                                        @else
                                            <input type="hidden" name="trang_thai" value="xuat_ban">
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-3 px-2.5 py-1" title="Phê duyệt xuất bản khóa học">
                                                <i class="fa-solid fa-check-double"></i>
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Xem giao diện học viên --}}
                                    <a href="{{ route('hoc_vien.tong_quan') }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-3 px-2.5 py-1" title="Xem trên giao diện học tập">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>

                                    {{-- Nút Xóa khóa học vi phạm --}}
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#modalDeleteCourse{{ $kh->id_khoa_hoc }}" title="Xóa khóa học">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>

                                {{-- Modal Xác nhận Xóa khóa học --}}
                                <div class="modal fade text-start" id="modalDeleteCourse{{ $kh->id_khoa_hoc }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('quan_tri.khoa_hoc.xoa', $kh->id_khoa_hoc) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header border-bottom py-3">
                                                    <h5 class="modal-title fw-bold text-danger">
                                                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Xác Nhận Xóa Khóa Học
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4 text-center">
                                                    <div class="text-danger fs-1 mb-2"><i class="fa-solid fa-circle-exclamation"></i></div>
                                                    <h6 class="fw-bold text-dark">Bạn có chắc chắn muốn xóa khóa học này?</h6>
                                                    <div class="p-3 bg-light rounded-3 my-2 text-start">
                                                        <div class="fw-bold text-dark">{{ $kh->tieu_de_khoa_hoc }}</div>
                                                        <div class="small text-muted">Giảng viên: {{ $kh->giangVien->ho_va_ten ?? 'N/A' }}</div>
                                                        <div class="small text-muted">Bao gồm: {{ $kh->danh_sach_chuong_muc_count ?? 0 }} chương, {{ $kh->danh_sach_bai_hoc_count ?? 0 }} bài học</div>
                                                    </div>
                                                    <p class="text-danger small mb-0">Hành động này sẽ xóa toàn bộ nội dung chương, bài học và đề thi đính kèm của khóa học này.</p>
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fs-2 text-secondary opacity-50 mb-2 d-block"></i>
                                Chưa có khóa học nào trong hệ thống.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Thêm Danh Mục Mới --}}
<div class="modal fade" id="modalThemDanhMuc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('quan_tri.danh_muc.them') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-folder-plus text-primary me-1"></i> Thêm Danh Mục Khóa Học Mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="ten_danh_muc" class="form-control rounded-3 py-2" placeholder="Ví dụ: Trí Tuệ Nhân Tạo & Machine Learning" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark">Mô tả ngắn danh mục</label>
                        <textarea name="mo_ta_danh_muc" rows="3" class="form-control rounded-3" placeholder="Mô tả tóm tắt nội dung các khóa học thuộc nhóm danh mục này..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 btn-sm fw-semibold">Lưu danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
