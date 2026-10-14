@extends('layouts.khung')

@section('title', 'Quản Lý Tài Khoản Người Dùng - EduMind AI')

@section('content')
<div class="container py-4">

    {{-- Breadcrumb & Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('quan_tri.tong_quan') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-house me-1"></i> Quản trị</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Quản lý tài khoản</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-users-gear text-primary"></i> Quản Lý Tài Khoản & Phân Quyền
            </h2>
            <p class="text-muted small mb-0 mt-1">Giám sát, phân quyền quản trị và quản lý trạng thái tài khoản người dùng trên toàn hệ thống.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('quan_tri.tong_quan') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-medium shadow-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Về Tổng quan
            </a>
            <button type="button" class="btn btn-primary rounded-pill px-3 py-2 btn-sm fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#modalThemTaiKhoan">
                <i class="fa-solid fa-user-plus me-1"></i> Thêm người dùng
            </button>
        </div>
    </div>

    {{-- Stats Cards Row --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Tổng người dùng</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $danhSachNguoiDung->total() }}</div>
                    </div>
                    <div class="rounded-3 p-3 bg-primary-subtle text-primary fs-4">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Học viên</div>
                        <div class="fs-3 fw-bold text-success mt-1">{{ $soLuongHocVien }}</div>
                    </div>
                    <div class="rounded-3 p-3 bg-success-subtle text-success fs-4">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Giảng viên</div>
                        <div class="fs-3 fw-bold text-info mt-1">{{ $soLuongGiangVien }}</div>
                    </div>
                    <div class="rounded-3 p-3 bg-info-subtle text-info fs-4">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Quản trị viên</div>
                        <div class="fs-3 fw-bold text-danger mt-1">{{ $soLuongAdmin }}</div>
                    </div>
                    <div class="rounded-3 p-3 bg-danger-subtle text-danger fs-4">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('quan_tri.tai_khoan') }}" class="row g-2 align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill text-muted ps-3">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="q" value="{{ $tuKhoa ?? '' }}" class="form-control bg-light border-start-0 rounded-end-pill py-2" placeholder="Tìm theo tên hoặc email...">
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <select name="quyen" class="form-select rounded-pill py-2" onchange="this.form.submit()">
                        <option value="">-- Tất cả vai trò --</option>
                        @foreach ($danhSachPhanQuyen as $pq)
                            <option value="{{ $pq->id_phan_quyen }}" {{ (string)($locQuyen ?? '') === (string)$pq->id_phan_quyen ? 'selected' : '' }}>
                                {{ $pq->ten_hien_thi_quyen }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-lg-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                        Lọc
                    </button>
                    @if (!empty($tuKhoa) || !empty($locQuyen))
                        <a href="{{ route('quan_tri.tai_khoan') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2" title="Xóa bộ lọc">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Users Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">Danh Sách Người Dùng</h5>
            <span class="badge bg-light text-muted border px-2.5 py-1.5">{{ $danhSachNguoiDung->total() }} tài khoản</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase fw-semibold">
                    <tr>
                        <th class="ps-4" style="width: 70px;">ID</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Vai trò phân quyền</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4" style="width: 160px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($danhSachNguoiDung as $u)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">#{{ $u->id_nguoi_dung }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #6366f1, #a855f7); font-size: 0.95rem;">
                                        {{ mb_substr($u->ho_va_ten ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->ho_va_ten ?? 'Chưa cập nhật' }}</div>
                                        @if ($u->id_nguoi_dung === Auth::id())
                                            <span class="badge bg-primary-subtle text-primary text-xs">Bạn (Đang đăng nhập)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted font-monospace small">{{ $u->dia_chi_email }}</span>
                            </td>
                            <td>
                                @if ($u->id_phan_quyen == 1)
                                    <span class="badge rounded-pill px-3 py-1.5 bg-danger-subtle text-danger border border-danger-subtle fw-semibold">
                                        <i class="fa-solid fa-shield-halved me-1"></i> Quản trị viên
                                    </span>
                                @elseif ($u->id_phan_quyen == 2)
                                    <span class="badge rounded-pill px-3 py-1.5 bg-info-subtle text-info border border-info-subtle fw-semibold">
                                        <i class="fa-solid fa-chalkboard-user me-1"></i> Giảng viên
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-1.5 bg-success-subtle text-success border border-success-subtle fw-semibold">
                                        <i class="fa-solid fa-graduation-cap me-1"></i> Học viên
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if (in_array($u->trang_thai_tai_khoan, ['khoa', 'tam_khoa', '0', 0]))
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1">
                                        <i class="fa-solid fa-lock me-1"></i> Bị khóa
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">
                                        <i class="fa-solid fa-circle-check me-1"></i> Hoạt động
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center gap-1.5">
                                    {{-- Nút Phân quyền --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#modalEditRole{{ $u->id_nguoi_dung }}" title="Chỉnh sửa vai trò">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </button>

                                    {{-- Nút Xóa tài khoản --}}
                                    @if ($u->id_nguoi_dung !== Auth::id())
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#modalDeleteUser{{ $u->id_nguoi_dung }}" title="Xóa tài khoản">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-light text-muted rounded-3 px-2.5 py-1" disabled title="Không thể xóa tài khoản của chính mình">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    @endif
                                </div>

                                {{-- Modal Chỉnh sửa quyền --}}
                                <div class="modal fade text-start" id="modalEditRole{{ $u->id_nguoi_dung }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('quan_tri.tai_khoan.cap_nhat_quyen', $u->id_nguoi_dung) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom py-3">
                                                    <h5 class="modal-title fw-bold text-dark">
                                                        <i class="fa-solid fa-user-gear text-primary me-1"></i> Phân Quyền Tài Khoản
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-semibold">Người dùng</label>
                                                        <div class="fw-bold fs-6 text-dark">{{ $u->ho_va_ten }} ({{ $u->dia_chi_email }})</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold text-dark">Vai trò trong hệ thống <span class="text-danger">*</span></label>
                                                        <select name="id_phan_quyen" class="form-select rounded-3 py-2" required>
                                                            @foreach ($danhSachPhanQuyen as $pq)
                                                                <option value="{{ $pq->id_phan_quyen }}" {{ $u->id_phan_quyen == $pq->id_phan_quyen ? 'selected' : '' }}>
                                                                    {{ $pq->ten_hien_thi_quyen }} — {{ $pq->mo_ta_quyen_han ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-text small text-muted">
                                                            • <strong>Quản trị viên (1):</strong> Toàn quyền kiểm duyệt, phân quyền, cấu hình hệ thống.<br>
                                                            • <strong>Giảng viên (2):</strong> Soạn bài giảng, khóa học và tạo đề thi.<br>
                                                            • <strong>Học viên (3):</strong> Tham gia học tập, làm bài thi và hỏi đáp AI.
                                                        </div>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label fw-semibold text-dark">Trạng thái tài khoản</label>
                                                        <select name="trang_thai_tai_khoan" class="form-select rounded-3 py-2">
                                                            <option value="kich_hoat" {{ !in_array($u->trang_thai_tai_khoan, ['khoa', 'tam_khoa', '0', 0]) ? 'selected' : '' }}>Hoạt động bình thường</option>
                                                            <option value="khoa" {{ in_array($u->trang_thai_tai_khoan, ['khoa', 'tam_khoa', '0', 0]) ? 'selected' : '' }}>Tạm khóa tài khoản</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 btn-sm" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 btn-sm fw-semibold">Lưu thay đổi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Xác nhận Xóa tài khoản --}}
                                @if ($u->id_nguoi_dung !== Auth::id())
                                    <div class="modal fade text-start" id="modalDeleteUser{{ $u->id_nguoi_dung }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <form action="{{ route('quan_tri.tai_khoan.xoa', $u->id_nguoi_dung) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header border-bottom py-3">
                                                        <h5 class="modal-title fw-bold text-danger">
                                                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Xác Nhận Xóa Tài Khoản
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4 text-center">
                                                        <div class="text-danger fs-1 mb-2"><i class="fa-solid fa-circle-exclamation"></i></div>
                                                        <h6 class="fw-bold text-dark">Bạn có chắc chắn muốn xóa người dùng này?</h6>
                                                        <p class="text-muted small mb-0">Tài khoản: <strong>{{ $u->ho_va_ten }}</strong> (<code>{{ $u->dia_chi_email }}</code>)</p>
                                                        <p class="text-muted small mb-0 mt-1">Toàn bộ dữ liệu kết quả thi, lịch sử hỏi đáp liên quan của người dùng này sẽ bị xóa khỏi hệ thống.</p>
                                                    </div>
                                                    <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4 justify-content-center">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-1.5 btn-sm fw-semibold">Xóa vĩnh viễn</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fs-2 text-secondary opacity-50 mb-2 d-block"></i>
                                Không tìm thấy người dùng nào phù hợp với điều kiện tìm kiếm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($danhSachNguoiDung->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-center">
                {{ $danhSachNguoiDung->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Modal Thêm Người Dùng Mới --}}
<div class="modal fade" id="modalThemTaiKhoan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('quan_tri.tai_khoan.them') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-user-plus text-primary me-1"></i> Thêm Người Dùng Mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_va_ten" class="form-control rounded-3 py-2" placeholder="Ví dụ: Nguyễn Văn A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
                        <input type="email" name="dia_chi_email" class="form-control rounded-3 py-2" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Mật khẩu ban đầu <span class="text-danger">*</span></label>
                        <input type="password" name="mat_khau" class="form-control rounded-3 py-2" placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Vai trò phân quyền <span class="text-danger">*</span></label>
                        <select name="id_phan_quyen" class="form-select rounded-3 py-2" required>
                            @foreach ($danhSachPhanQuyen as $pq)
                                <option value="{{ $pq->id_phan_quyen }}" {{ $pq->id_phan_quyen == 3 ? 'selected' : '' }}>
                                    {{ $pq->ten_hien_thi_quyen }} ({{ $pq->ma_dinh_danh_quyen }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-1.5 btn-sm fw-semibold">Tạo tài khoản</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
