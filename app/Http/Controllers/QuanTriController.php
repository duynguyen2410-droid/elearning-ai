<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhoaHoc;
use App\Models\DanhMucKhoaHoc;
use App\Models\ChuongMucBaiHoc;
use App\Models\ChiTietBaiHoc;
use App\Models\DeThiTracNghiem;
use App\Models\CauHoiKiemTra;
use App\Models\PhuongAnLuaChon;
use App\Models\LuotLamBaiThi;
use App\Models\ChiTietCauTraLoi;
use App\Models\User;
use App\Models\PhanQuyen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuanTriController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. Tổng quan bảng điều khiển Quản trị viên (Single-page Tabs Hub)
    |--------------------------------------------------------------------------
    */
    public function tongQuan(Request $request)
    {
        $user = Auth::user();

        // 1. Số liệu thống kê KPI
        $tongKhoaHoc = KhoaHoc::count();
        $tongHocVien = User::where('id_phan_quyen', 3)->count(); // Chỉ đếm Học viên
        $tongDeThi = DeThiTracNghiem::count();

        // 2. Dữ liệu tổng quan: Đề thi gần đây & Học viên mới nhất
        $hocVienMoiNhat = User::where('id_phan_quyen', 3)
            ->with('phanQuyen')
            ->orderByDesc('id_nguoi_dung')
            ->take(5)
            ->get();
        $danhSachHocVienMoi = $hocVienMoiNhat;

        $deThiGanDay = DeThiTracNghiem::with(['baiHoc.chuongMuc.khoaHoc', 'danhSachCauHoi'])
            ->withCount(['danhSachCauHoi', 'danhSachLuotLamBai'])
            ->orderByDesc('id_de_thi')
            ->take(5)
            ->get();
        $danhSachDeThiGanDay = $deThiGanDay;

        // 3. Dữ liệu Tab Danh mục & Khóa học
        $danhSachDanhMuc = DanhMucKhoaHoc::withCount('danhSachKhoaHoc')
            ->orderBy('id_danh_muc', 'desc')
            ->get();

        $danhSachKhoaHoc = KhoaHoc::with(['giangVien', 'danhMuc'])
            ->withCount(['danhSachChuongMuc', 'danhSachBaiHoc'])
            ->orderByDesc('id_khoa_hoc')
            ->get();

        // 4. Dữ liệu Tab Giám sát Đề thi AI
        $tatCaDeThi = DeThiTracNghiem::with([
                'baiHoc.chuongMuc.khoaHoc',
                'danhSachCauHoi.danhSachPhuongAn',
            ])
            ->withCount(['danhSachCauHoi', 'danhSachLuotLamBai'])
            ->orderByDesc('id_de_thi')
            ->get();
        $danhSachDeThi = $tatCaDeThi;

        $tongSoCauHoi = CauHoiKiemTra::count();
        $tongSoLuotThi = LuotLamBaiThi::count();

        // 5. Dữ liệu Tab Quản lý Tài khoản
        $danhSachTaiKhoan = User::with('phanQuyen')
            ->orderBy('id_nguoi_dung', 'asc')
            ->get();
        $danhSachNguoiDung = $danhSachTaiKhoan;
        $danhSachPhanQuyen = PhanQuyen::all();

        $soLuongAdmin = User::where('id_phan_quyen', 1)->count();
        $soLuongGiangVien = User::where('id_phan_quyen', 2)->count();
        $soLuongHocVien = $tongHocVien;

        // Xác định tab đang kích hoạt (từ query param ?tab= hoặc flash session active_tab)
        $tabParam = $request->query('tab', session('active_tab', 'tong-quan'));
        $validTabs = ['tong-quan', 'khoa-hoc', 'de-thi', 'tai-khoan'];
        $activeTab = in_array($tabParam, $validTabs, true) ? $tabParam : 'tong-quan';

        return view('quan_tri.tong_quan', compact(
            'user',
            'tongKhoaHoc',
            'tongHocVien',
            'tongDeThi',
            'hocVienMoiNhat',
            'danhSachHocVienMoi',
            'deThiGanDay',
            'danhSachDeThiGanDay',
            'danhSachDanhMuc',
            'danhSachKhoaHoc',
            'tatCaDeThi',
            'danhSachDeThi',
            'tongSoCauHoi',
            'tongSoLuotThi',
            'danhSachTaiKhoan',
            'danhSachNguoiDung',
            'danhSachPhanQuyen',
            'soLuongAdmin',
            'soLuongGiangVien',
            'soLuongHocVien',
            'activeTab'
        ));
    }

    /**
     * Alias hỗ trợ route dashboard
     */
    public function dashboard(Request $request)
    {
        return $this->tongQuan($request);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Quản lý Tài khoản người dùng
    |--------------------------------------------------------------------------
    */
    public function quanLyTaiKhoan(Request $request)
    {
        return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan']);
    }

    /**
     * Thêm tài khoản người dùng mới (Admin tạo trực tiếp)
     */
    public function themTaiKhoan(Request $request)
    {
        $request->validate([
            'ho_va_ten'            => 'required|string|max:150',
            'dia_chi_email'        => 'required|string|email|max:150|unique:tai_khoan_nguoi_dung,dia_chi_email',
            'mat_khau'             => 'required|string|min:6',
            'id_phan_quyen'        => 'required|integer|in:1,2,3',
            'trang_thai_tai_khoan' => 'nullable|string',
        ], [
            'ho_va_ten.required'     => 'Vui lòng nhập họ và tên.',
            'dia_chi_email.required' => 'Vui lòng nhập email.',
            'dia_chi_email.unique'   => 'Email này đã tồn tại trong hệ thống.',
            'mat_khau.required'      => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min'           => 'Mật khẩu phải tối thiểu 6 ký tự.',
            'id_phan_quyen.required' => 'Vui lòng chọn vai trò phân quyền.',
        ]);

        User::create([
            'ho_va_ten'            => $request->ho_va_ten,
            'dia_chi_email'        => $request->dia_chi_email,
            'mat_khau_ma_hoa'      => Hash::make($request->mat_khau),
            'id_phan_quyen'        => (int)$request->id_phan_quyen,
            'trang_thai_tai_khoan' => $request->input('trang_thai_tai_khoan', 'kich_hoat'),
        ]);

        return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan'])
            ->with('active_tab', 'tai-khoan')
            ->with('success', "Đã thêm tài khoản mới '{$request->ho_va_ten}' thành công!");
    }

    /**
     * Cập nhật vai trò / trạng thái của người dùng
     */
    public function capNhatQuyen(Request $request, $id)
    {
        $request->validate([
            'id_phan_quyen'        => 'required|integer|in:1,2,3',
            'trang_thai_tai_khoan' => 'nullable|string',
        ]);

        $targetUser = User::findOrFail($id);

        // Không cho phép Admin tự hạ quyền của chính mình để tránh mất quyền quản trị hệ thống
        if ($targetUser->id_nguoi_dung === Auth::id() && (int)$request->id_phan_quyen !== 1) {
            return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan'])
                ->with('active_tab', 'tai-khoan')
                ->with('error', 'Bạn không thể tự hạ quyền Quản trị viên của chính tài khoản đang đăng nhập!');
        }

        $targetUser->id_phan_quyen = (int)$request->id_phan_quyen;

        if ($request->has('trang_thai_tai_khoan')) {
            $targetUser->trang_thai_tai_khoan = $request->input('trang_thai_tai_khoan');
        }

        $targetUser->save();

        return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan'])
            ->with('active_tab', 'tai-khoan')
            ->with('success', "Đã cập nhật vai trò phân quyền cho tài khoản '{$targetUser->ho_va_ten}' thành công!");
    }

    /**
     * Admin đặt lại mật khẩu cho Giảng viên hoặc Học viên
     */
    public function datLaiMatKhau(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);

        // Đảm bảo chỉ cấp lại mật khẩu cho Giảng viên (2) hoặc Học viên (3)
        if ((int)$targetUser->id_phan_quyen === 1) {
            return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan'])
                ->with('active_tab', 'tai-khoan')
                ->with('error', 'Không thể cấp lại mật khẩu cho tài khoản Quản trị viên qua tính năng này!');
        }

        $request->validate([
            'mat_khau_moi' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'mat_khau_moi.required'  => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau_moi.min'       => 'Mật khẩu mới phải có tối thiểu 6 ký tự.',
            'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu mới không trùng khớp.',
        ]);

        $targetUser->mat_khau_ma_hoa = Hash::make($request->mat_khau_moi);
        $targetUser->save();

        return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan'])
            ->with('active_tab', 'tai-khoan')
            ->with('success', "Đã cấp lại mật khẩu mới cho tài khoản '{$targetUser->ho_va_ten}' thành công!");
    }

    /**
     * Xóa tài khoản người dùng
     */
    public function xoaTaiKhoan($id)
    {
        $targetUser = User::findOrFail($id);

        // Không cho phép tự xóa tài khoản của chính mình
        if ($targetUser->id_nguoi_dung === Auth::id()) {
            return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan'])
                ->with('active_tab', 'tai-khoan')
                ->with('error', 'Bạn không thể tự xóa tài khoản Quản trị viên của chính mình!');
        }

        // Kiểm tra nếu là Giảng viên đang phụ trách khóa học thì không thể xóa trực tiếp
        if (KhoaHoc::where('id_giang_vien_phu_trach', $targetUser->id_nguoi_dung)->exists()) {
            return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan'])
                ->with('active_tab', 'tai-khoan')
                ->with('error', "Không thể xóa giảng viên '{$targetUser->ho_va_ten}' vì đang phụ trách khóa học trong hệ thống!");
        }

        try {
            DB::transaction(function () use ($targetUser) {
                $uid = $targetUser->id_nguoi_dung;

                // Xóa chi tiết câu trả lời và lượt làm bài thi của học viên
                $luotThiIds = LuotLamBaiThi::where('id_hoc_vien', $uid)->pluck('id_luot_lam_bai');
                if ($luotThiIds->isNotEmpty()) {
                    ChiTietCauTraLoi::whereIn('id_luot_lam_bai', $luotThiIds)->delete();
                    LuotLamBaiThi::whereIn('id_luot_lam_bai', $luotThiIds)->delete();
                }

                // Xóa thông tin ghi danh và lịch sử trợ giảng AI
                DB::table('ghi_danh_khoa_hoc')->where('id_hoc_vien', $uid)->delete();
                DB::table('lich_su_tro_giang_ai')->where('id_hoc_vien', $uid)->delete();

                // Xóa tài khoản
                $targetUser->delete();
            });

            return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan'])
                ->with('active_tab', 'tai-khoan')
                ->with('success', "Đã xóa tài khoản '{$targetUser->ho_va_ten}' thành công!");
        } catch (\Throwable $e) {
            return redirect()->route('quan_tri.tong_quan', ['tab' => 'tai-khoan'])
                ->with('active_tab', 'tai-khoan')
                ->with('error', 'Lỗi khi xóa tài khoản: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Quản lý Danh mục & Giám sát / Kiểm duyệt Khóa học
    |--------------------------------------------------------------------------
    */
    public function quanLyKhoaHoc(Request $request)
    {
        $user = Auth::user();

        // Danh sách danh mục khóa học kèm số lượng khóa học
        $danhSachDanhMuc = DanhMucKhoaHoc::withCount('danhSachKhoaHoc')
            ->orderBy('id_danh_muc', 'desc')
            ->get();

        // Danh sách khóa học kèm giảng viên, danh mục và đếm số chương, bài học
        $danhSachKhoaHoc = KhoaHoc::with(['giangVien', 'danhMuc'])
            ->withCount(['danhSachChuongMuc', 'danhSachBaiHoc'])
            ->orderByDesc('id_khoa_hoc')
            ->get();

        return redirect()->route('quan_tri.tong_quan', ['tab' => 'khoa-hoc']);
    }

    /**
     * Thêm danh mục khóa học mới
     */
    public function themDanhMuc(Request $request)
    {
        $request->validate([
            'ten_danh_muc'  => 'required|string|max:150',
            'mo_ta_danh_muc'=> 'nullable|string|max:1000',
        ], [
            'ten_danh_muc.required' => 'Vui lòng nhập tên danh mục.',
            'ten_danh_muc.max'      => 'Tên danh mục không vượt quá 150 ký tự.',
        ]);

        $ten = trim($request->ten_danh_muc);
        $slug = Str::slug($ten) ?: 'danh-muc-' . time();

        DanhMucKhoaHoc::create([
            'ten_danh_muc'              => $ten,
            'duong_dan_than_thien_slug' => $slug,
            'mo_ta_danh_muc'            => $request->mo_ta_danh_muc,
            'thu_tu_hien_thi'           => DanhMucKhoaHoc::count() + 1,
        ]);

        return redirect()->route('quan_tri.tong_quan', ['tab' => 'khoa-hoc'])
            ->with('active_tab', 'khoa-hoc')
            ->with('success', "Đã thêm danh mục '{$ten}' thành công!");
    }

    /**
     * Xóa danh mục khóa học
     */
    public function xoaDanhMuc($id)
    {
        $danhMuc = DanhMucKhoaHoc::findOrFail($id);

        if (KhoaHoc::where('id_danh_muc', $id)->exists()) {
            return redirect()->route('quan_tri.tong_quan', ['tab' => 'khoa-hoc'])
                ->with('active_tab', 'khoa-hoc')
                ->with('error', "Không thể xóa danh mục '{$danhMuc->ten_danh_muc}' vì đang có khóa học thuộc danh mục này!");
        }

        $danhMuc->delete();

        return redirect()->route('quan_tri.tong_quan', ['tab' => 'khoa-hoc'])
            ->with('active_tab', 'khoa-hoc')
            ->with('success', "Đã xóa danh mục '{$danhMuc->ten_danh_muc}' thành công!");
    }

    /**
     * Duyệt / Cập nhật trạng thái khóa học (Admin kiểm duyệt xuất bản)
     */
    public function duyetKhoaHoc(Request $request, $id)
    {
        $khoaHoc = KhoaHoc::findOrFail($id);
        $trangThaiMoi = $request->input('trang_thai', 'xuat_ban');

        $khoaHoc->trang_thai_xuat_ban = $trangThaiMoi;
        $khoaHoc->save();

        $nhanTrangThai = $trangThaiMoi === 'xuat_ban' ? 'Đã xuất bản (công khai)' : 'Tạm ẩn / Chờ duyệt';

        return redirect()->route('quan_tri.tong_quan', ['tab' => 'khoa-hoc'])
            ->with('active_tab', 'khoa-hoc')
            ->with('success', "Đã cập nhật trạng thái khóa học '{$khoaHoc->tieu_de_khoa_hoc}' sang [{$nhanTrangThai}]!");
    }

    /**
     * Xóa khóa học vi phạm / không hợp lệ
     */
    public function xoaKhoaHoc($id)
    {
        $khoaHoc = KhoaHoc::findOrFail($id);

        try {
            DB::transaction(function () use ($khoaHoc) {
                $kid = $khoaHoc->id_khoa_hoc;

                // Xóa ghi danh khóa học
                DB::table('ghi_danh_khoa_hoc')->where('id_khoa_hoc', $kid)->delete();

                // Lấy tất cả chương mục và bài học
                $chuongMucIds = ChuongMucBaiHoc::where('id_khoa_hoc', $kid)->pluck('id_chuong_muc');
                $baiHocIds = ChiTietBaiHoc::whereIn('id_chuong_muc', $chuongMucIds)->pluck('id_bai_hoc');

                if ($baiHocIds->isNotEmpty()) {
                    // Xóa đề thi trắc nghiệm gắn với bài học
                    $deThiIds = DeThiTracNghiem::whereIn('id_bai_hoc', $baiHocIds)->pluck('id_de_thi');
                    if ($deThiIds->isNotEmpty()) {
                        $cauHoiIds = CauHoiKiemTra::whereIn('id_de_thi', $deThiIds)->pluck('id_cau_hoi');
                        $luotThiIds = LuotLamBaiThi::whereIn('id_de_thi', $deThiIds)->pluck('id_luot_lam_bai');

                        if ($luotThiIds->isNotEmpty() || $cauHoiIds->isNotEmpty()) {
                            ChiTietCauTraLoi::whereIn('id_luot_lam_bai', $luotThiIds)
                                ->orWhereIn('id_cau_hoi', $cauHoiIds)
                                ->delete();
                        }

                        if ($cauHoiIds->isNotEmpty()) {
                            PhuongAnLuaChon::whereIn('id_cau_hoi', $cauHoiIds)->delete();
                            CauHoiKiemTra::whereIn('id_cau_hoi', $cauHoiIds)->delete();
                        }

                        if ($luotThiIds->isNotEmpty()) {
                            LuotLamBaiThi::whereIn('id_luot_lam_bai', $luotThiIds)->delete();
                        }

                        DeThiTracNghiem::whereIn('id_de_thi', $deThiIds)->delete();
                    }

                    // Xóa tiến độ bài học và lịch sử trợ giảng AI
                    DB::table('tien_do_bai_hoc')->whereIn('id_bai_hoc', $baiHocIds)->delete();
                    DB::table('lich_su_tro_giang_ai')->whereIn('id_bai_hoc', $baiHocIds)->delete();

                    // Xóa bài học
                    ChiTietBaiHoc::whereIn('id_bai_hoc', $baiHocIds)->delete();
                }

                // Xóa chương mục
                ChuongMucBaiHoc::where('id_khoa_hoc', $kid)->delete();

                // Xóa khóa học
                $khoaHoc->delete();
            });

            return redirect()->route('quan_tri.tong_quan', ['tab' => 'khoa-hoc'])
                ->with('active_tab', 'khoa-hoc')
                ->with('success', "Đã xóa khóa học '{$khoaHoc->tieu_de_khoa_hoc}' thành công!");
        } catch (\Throwable $e) {
            return redirect()->route('quan_tri.tong_quan', ['tab' => 'khoa-hoc'])
                ->with('active_tab', 'khoa-hoc')
                ->with('error', 'Lỗi khi xóa khóa học: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Giám sát & Quản trị Đề thi AI
    |--------------------------------------------------------------------------
    */
    public function giamSatDeThi(Request $request)
    {
        return redirect()->route('quan_tri.tong_quan', ['tab' => 'de-thi']);
    }

    /**
     * Xóa đề thi trắc nghiệm (bao gồm đề AI sinh bị lỗi)
     */
    public function xoaDeThi($id)
    {
        $deThi = DeThiTracNghiem::findOrFail($id);

        try {
            DB::transaction(function () use ($deThi) {
                $dtid = $deThi->id_de_thi;

                $cauHoiIds = CauHoiKiemTra::where('id_de_thi', $dtid)->pluck('id_cau_hoi');
                $luotThiIds = LuotLamBaiThi::where('id_de_thi', $dtid)->pluck('id_luot_lam_bai');

                // Xóa chi tiết câu trả lời
                if ($luotThiIds->isNotEmpty() || $cauHoiIds->isNotEmpty()) {
                    ChiTietCauTraLoi::whereIn('id_luot_lam_bai', $luotThiIds)
                        ->orWhereIn('id_cau_hoi', $cauHoiIds)
                        ->delete();
                }

                // Xóa các phương án lựa chọn (A, B, C, D)
                if ($cauHoiIds->isNotEmpty()) {
                    PhuongAnLuaChon::whereIn('id_cau_hoi', $cauHoiIds)->delete();
                    CauHoiKiemTra::whereIn('id_cau_hoi', $cauHoiIds)->delete();
                }

                // Xóa các lượt làm bài thi của đề này
                if ($luotThiIds->isNotEmpty()) {
                    LuotLamBaiThi::whereIn('id_luot_lam_bai', $luotThiIds)->delete();
                }

                // Xóa đề thi
                $deThi->delete();
            });

            return redirect()->route('quan_tri.tong_quan', ['tab' => 'de-thi'])
                ->with('active_tab', 'de-thi')
                ->with('success', "Đã xóa đề thi '{$deThi->tieu_de_de_thi}' thành công!");
        } catch (\Throwable $e) {
            return redirect()->route('quan_tri.tong_quan', ['tab' => 'de-thi'])
                ->with('active_tab', 'de-thi')
                ->with('error', 'Lỗi khi xóa đề thi: ' . $e->getMessage());
        }
    }
}
