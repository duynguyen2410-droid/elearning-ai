<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhoaHoc;
use App\Models\ChiTietBaiHoc;
use App\Models\GhiDanhKhoaHoc;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HocVienController extends Controller
{
    /**
     * Bảng điều khiển học viên
     */
    public function dashboard()
    {
        $user = Auth::user() ?? User::first();

        // Chỉ query và hiển thị những khóa học có trang_thai_mo_dang_ky == 1
        $danhSachKhoaHoc = KhoaHoc::where('trang_thai_mo_dang_ky', 1)
            ->with([
                'giangVien',
                'danhMuc',
                'danhSachChuongMuc.danhSachBaiHoc.deThi',
                'danhSachChuongMuc.danhSachBaiHoc.deThiTracNghiem',
                'danhSachChuongMuc.danhSachBaiHoc.danhSachDeThi',
            ])->get();

        // Lấy danh sách ghi danh của học viên
        $danhSachGhiDanh = collect();
        $idKhoaHocDaDangKy = [];
        if ($user) {
            $danhSachGhiDanh = GhiDanhKhoaHoc::where('id_hoc_vien', $user->id_nguoi_dung)->get()->keyBy('id_khoa_hoc');
            $idKhoaHocDaDangKy = $danhSachGhiDanh->where('trang_thai_duyet', 1)->pluck('id_khoa_hoc')->toArray();
        }

        // Lọc bài học và xác định bài học đầu tiên hợp lệ theo điều kiện phân quyền:
        foreach ($danhSachKhoaHoc as $khoaHoc) {
            $ghiDanh = $danhSachGhiDanh->get($khoaHoc->id_khoa_hoc);
            $trangThaiDuyet = $ghiDanh ? (int)$ghiDanh->trang_thai_duyet : null;

            $daDangKy = ($trangThaiDuyet === 1)
                || ($user && (int)$user->id_phan_quyen === 1)
                || ($user && (int)$user->id_phan_quyen === 2 && (
                    (int)$khoaHoc->id_giang_vien_phu_trach === (int)$user->id_nguoi_dung ||
                    (int)($khoaHoc->id_nguoi_tao ?? 0) === (int)$user->id_nguoi_dung
                ));

            $khoaHoc->da_dang_ky = $daDangKy;
            $khoaHoc->trang_thai_duyet = $trangThaiDuyet;

            // Bài học đầu tiên của khóa học (phục vụ nút Vào học ngay khi đã được phê duyệt)
            $khoaHoc->bai_hoc_dau_tien = $khoaHoc->danhSachChuongMuc
                ->pluck('danhSachBaiHoc')
                ->flatten()
                ->first();
        }

        // Thống kê số lần học viên đã tạo đề thi AI cho từng bài học
        $soLanTaoDeTheoBaiHoc = [];
        if ($user) {
            $soLanTaoDeTheoBaiHoc = \App\Models\DeThiTracNghiem::where('id_nguoi_tao', $user->id_nguoi_dung)
                ->selectRaw('COALESCE(id_chi_tiet_bai_hoc, id_bai_hoc) as id_bai_hoc, count(*) as total')
                ->groupBy(\Illuminate\Support\Facades\DB::raw('COALESCE(id_chi_tiet_bai_hoc, id_bai_hoc)'))
                ->pluck('total', 'id_bai_hoc')
                ->toArray();
        }

        return view('hoc_vien.tong_quan', compact('user', 'danhSachKhoaHoc', 'idKhoaHocDaDangKy', 'soLanTaoDeTheoBaiHoc'));
    }

    /**
     * Xem chi tiết bài học
     */
    public function showLesson($id)
    {
        $baiHoc = ChiTietBaiHoc::with([
            'chuongMuc.khoaHoc.giangVien',
            'chuongMuc.khoaHoc.danhMuc',
            'deThi',
            'deThiTracNghiem',
        ])->findOrFail($id);

        $chuongMuc = $baiHoc->chuongMuc;
        $khoaHoc = $chuongMuc ? $chuongMuc->khoaHoc : null;

        // KIỂM TRA QUYỀN TRUY CẬP:
        // 1. Nếu người xem là Giảng viên sở hữu khóa học hoặc Admin: Cho phép xem mọi lúc
        $user = Auth::user();
        $isOwnerOrAdmin = false;
        $daDangKy = false;

        if ($user) {
            $isOwnerOrAdmin = ((int)$user->id_phan_quyen === 1) ||
                              ($khoaHoc && (
                                  (int)$khoaHoc->id_giang_vien_phu_trach === (int)$user->id_nguoi_dung ||
                                  (int)($khoaHoc->id_nguoi_tao ?? 0) === (int)$user->id_nguoi_dung
                              ));

            if (!$isOwnerOrAdmin) {
                // 2. Nếu là Học viên:
                // Bắt buộc phải có bản ghi đăng ký khóa học tương ứng VÀ trang_thai_duyet == 1
                if ($khoaHoc) {
                    $daDangKy = GhiDanhKhoaHoc::where('id_hoc_vien', $user->id_nguoi_dung)
                        ->where('id_khoa_hoc', $khoaHoc->id_khoa_hoc)
                        ->where('trang_thai_duyet', 1)
                        ->exists();
                }

                if (!$daDangKy) {
                    return redirect()->route('hoc_vien.tong_quan')
                        ->with('error', 'Khóa học này đang chờ giảng viên phê duyệt, bạn chưa thể truy cập bài học!');
                }
            }
        } else {
            return redirect()->route('login');
        }

        if ($khoaHoc) {
            $khoaHoc->load([
                'danhSachChuongMuc.danhSachBaiHoc.deThi',
                'giangVien',
                'danhMuc',
            ]);
        }

        $danhSachBaiHoc = $chuongMuc ? $chuongMuc->danhSachBaiHoc : collect();

        return view('hoc_vien.chi_tiet_bai_hoc', compact('baiHoc', 'chuongMuc', 'khoaHoc', 'danhSachBaiHoc', 'daDangKy', 'isOwnerOrAdmin'));
    }

    /**
     * Alias tiếng Việt cho dashboard
     */
    public function tongQuan()
    {
        return $this->dashboard();
    }

    /**
     * Alias tiếng Việt cho showLesson
     */
    public function chiTietBaiHoc($id)
    {
        return $this->showLesson($id);
    }

    /**
     * Làm bài thi trắc nghiệm (cho cả Học viên và Giảng viên thi thử)
     */
    public function lamBaiThi($id)
    {
        return app(ThiTracNghiemController::class)->take($id);
    }

    /**
     * Nộp bài thi trắc nghiệm
     */
    public function nopBaiThi(Request $request, $id = null)
    {
        return app(AiTaoDeThiController::class)->nopBai($request, $id);
    }

    /**
     * Sinh đề thi trắc nghiệm bằng AI
     */
    public function sinhDeThiAi(Request $request, $id)
    {
        return app(AiTaoDeThiController::class)->generateQuiz($request, $id);
    }

    /**
     * Alias sinh đề thi cho học viên
     */
    /**
     * Gửi yêu cầu đăng ký khóa học
     */
    public function dangKyKhoaHoc(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $khoaHoc = KhoaHoc::findOrFail($id);

        if ((int)($khoaHoc->trang_thai_mo_dang_ky ?? 1) !== 1) {
            return redirect()->route('hoc_vien.tong_quan')
                ->with('error', 'Khóa học này hiện đang đóng đăng ký.');
        }

        $ghiDanh = GhiDanhKhoaHoc::where('id_hoc_vien', $user->id_nguoi_dung)
            ->where('id_khoa_hoc', $khoaHoc->id_khoa_hoc)
            ->first();

        if ($ghiDanh) {
            if ((int)$ghiDanh->trang_thai_duyet === 1) {
                return redirect()->route('hoc_vien.tong_quan')
                    ->with('info', 'Bạn đã được phê duyệt tham gia khóa học này từ trước.');
            }
            if ((int)$ghiDanh->trang_thai_duyet === 0) {
                return redirect()->route('hoc_vien.tong_quan')
                    ->with('warning', 'Yêu cầu đăng ký của bạn đang chờ giảng viên xét duyệt.');
            }
            // Bị từ chối (trang_thai_duyet == 2): Cho phép gửi lại yêu cầu
            $ghiDanh->trang_thai_duyet = 0;
            $ghiDanh->ngay_ghi_danh = Carbon::now();
            $ghiDanh->save();

            return redirect()->route('hoc_vien.tong_quan')
                ->with('success', 'Đã gửi lại yêu cầu đăng ký khóa học "' . ($khoaHoc->tieu_de_khoa_hoc ?? $khoaHoc->ten_khoa_hoc) . '"! Vui lòng chờ giảng viên xét duyệt.');
        }

        // Tạo mới yêu cầu đăng ký
        GhiDanhKhoaHoc::create([
            'id_hoc_vien' => $user->id_nguoi_dung,
            'id_khoa_hoc' => $khoaHoc->id_khoa_hoc,
            'trang_thai_duyet' => 0,
            'phan_tram_hoan_thanh' => 0,
            'trang_thai_khoa_hoc' => 'dang_hoc',
            'ngay_ghi_danh' => Carbon::now(),
        ]);

        return redirect()->route('hoc_vien.tong_quan')
            ->with('success', 'Đã gửi yêu cầu đăng ký khóa học "' . ($khoaHoc->tieu_de_khoa_hoc ?? $khoaHoc->ten_khoa_hoc) . '" thành công! Vui lòng chờ giảng viên phê duyệt.');
    }
}

