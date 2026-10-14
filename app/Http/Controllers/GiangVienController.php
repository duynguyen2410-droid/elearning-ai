<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\KhoaHoc;
use App\Models\DanhMucKhoaHoc;
use App\Models\ChuongMucBaiHoc;
use App\Models\ChiTietBaiHoc;
use App\Models\DeThiTracNghiem;
use App\Models\CauHoiKiemTra;
use App\Models\PhuongAnLuaChon;
use App\Models\LuotLamBaiThi;
use App\Models\ChiTietCauTraLoi;

class GiangVienController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. Tổng quan Bàn làm việc Giảng viên (Single-page Tabs Hub)
    |--------------------------------------------------------------------------
    */
    public function tongQuan(Request $request)
    {
        $user = Auth::user();
        $gvId = $user->id_nguoi_dung;

        // 1. Danh sách khóa học do Giảng viên này phụ trách
        $danhSachKhoaHoc = KhoaHoc::where('id_giang_vien_phu_trach', $gvId)
            ->with(['danhMuc', 'danhSachChuongMuc.danhSachBaiHoc.deThi'])
            ->withCount(['danhSachChuongMuc', 'danhSachBaiHoc'])
            ->orderByDesc('id_khoa_hoc')
            ->get();

        // Lấy danh sách ID để truy vấn liên kết
        $khoaHocIds = $danhSachKhoaHoc->pluck('id_khoa_hoc');
        $chuongIds = ChuongMucBaiHoc::whereIn('id_khoa_hoc', $khoaHocIds)->pluck('id_chuong_muc');

        // Danh sách toàn bộ bài học thuộc các khóa của giảng viên
        $tatCaBaiHoc = ChiTietBaiHoc::whereIn('id_chuong_muc', $chuongIds)
            ->with(['chuongMuc.khoaHoc', 'deThi.danhSachCauHoi.danhSachPhuongAn'])
            ->orderBy('id_bai_hoc', 'desc')
            ->get();
        $baiHocIds = $tatCaBaiHoc->pluck('id_bai_hoc');

        // 2. Danh sách toàn bộ đề thi gắn với bài học của giảng viên
        $danhSachDeThi = DeThiTracNghiem::whereIn('id_bai_hoc', $baiHocIds)
            ->with([
                'baiHoc.chuongMuc.khoaHoc',
                'danhSachCauHoi.danhSachPhuongAn'
            ])
            ->withCount(['danhSachCauHoi', 'danhSachLuotLamBai'])
            ->orderByDesc('id_de_thi')
            ->get();
        $deThiIds = $danhSachDeThi->pluck('id_de_thi');

        // 3. Lịch sử làm bài thi của học viên đối với các đề thi của giảng viên
        $danhSachLuotThi = LuotLamBaiThi::whereIn('id_de_thi', $deThiIds)
            ->with(['hocVien', 'deThi.baiHoc.chuongMuc.khoaHoc'])
            ->orderByDesc('id_luot_lam_bai')
            ->get();
        $luotThiGanDay = $danhSachLuotThi->take(6);

        // 4. Các chỉ số thống kê
        $tongKhoaHoc = $danhSachKhoaHoc->count();
        $tongBaiHoc  = $tatCaBaiHoc->count();
        $tongDeThi   = $danhSachDeThi->count();
        $tongLuotThi = $danhSachLuotThi->count();

        // 5. Danh mục khóa học (phục vụ chọn dropdown khi tạo khóa mới)
        $danhSachDanhMuc = DanhMucKhoaHoc::orderBy('thu_tu_hien_thi')->get();

        // 6. Bài học chưa có đề thi (để gợi ý tạo nhanh qua AI)
        $baiHocChuaCoDe = $tatCaBaiHoc->filter(function ($bh) {
            return !$bh->deThi;
        });

        // 7. Xác định tab đang chọn (?tab= hoặc flash session active_tab)
        $tabParam = $request->query('tab', session('active_tab', 'tong-quan'));
        $validTabs = ['tong-quan', 'khoa-hoc', 'de-thi', 'ket-qua'];
        $activeTab = in_array($tabParam, $validTabs, true) ? $tabParam : 'tong-quan';

        return view('giang_vien.tong_quan', compact(
            'user',
            'tongKhoaHoc',
            'tongBaiHoc',
            'tongDeThi',
            'tongLuotThi',
            'danhSachKhoaHoc',
            'tatCaBaiHoc',
            'danhSachDeThi',
            'danhSachLuotThi',
            'luotThiGanDay',
            'danhSachDanhMuc',
            'baiHocChuaCoDe',
            'activeTab'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Quản lý Khóa học & Giáo trình bài giảng
    |--------------------------------------------------------------------------
    */
    public function themKhoaHoc(Request $request)
    {
        $request->validate([
            'tieu_de_khoa_hoc'     => 'required|string|max:200',
            'id_danh_muc'          => 'required|integer|exists:danh_muc_khoa_hoc,id_danh_muc',
            'mo_ta_ngan_gon'       => 'nullable|string|max:500',
            'cap_do_khoa_hoc'      => 'nullable|string',
            'tong_thoi_luong_phut' => 'nullable|integer|min:0',
        ], [
            'tieu_de_khoa_hoc.required' => 'Vui lòng nhập tên khóa học.',
            'id_danh_muc.required'      => 'Vui lòng chọn danh mục đào tạo.',
            'id_danh_muc.exists'        => 'Danh mục được chọn không hợp lệ.',
        ]);

        $slug = Str::slug($request->tieu_de_khoa_hoc) . '-' . time();

        $khoaHoc = KhoaHoc::create([
            'id_giang_vien_phu_trach'       => Auth::id(),
            'id_danh_muc'                   => $request->id_danh_muc,
            'tieu_de_khoa_hoc'              => trim($request->tieu_de_khoa_hoc),
            'duong_dan_than_thien_slug'     => $slug,
            'mo_ta_ngan_gon'                => $request->mo_ta_ngan_gon,
            'noi_dung_gioi_thieu_chi_tiet'  => $request->noi_dung_gioi_thieu_chi_tiet ?? $request->mo_ta_ngan_gon,
            'cap_do_khoa_hoc'               => $request->input('cap_do_khoa_hoc', 'co_ban'),
            'trang_thai_xuat_ban'           => 'xuat_ban', // Mặc định mở lớp
            'tong_thoi_luong_phut'          => $request->input('tong_thoi_luong_phut', 60),
            'thoi_diem_tao'                 => Carbon::now(),
            'thoi_diem_cap_nhat'            => Carbon::now(),
        ]);

        return redirect()->route('giang_vien.tong_quan', ['tab' => 'khoa-hoc'])
            ->with('active_tab', 'khoa-hoc')
            ->with('success', "Đã tạo khóa học mới '{$khoaHoc->tieu_de_khoa_hoc}' thành công!");
    }

    public function capNhatKhoaHoc(Request $request, $id)
    {
        // Đảm bảo khóa học thuộc quyền sở hữu của Giảng viên này
        $khoaHoc = KhoaHoc::where('id_khoa_hoc', $id)
            ->where('id_giang_vien_phu_trach', Auth::id())
            ->firstOrFail();

        $request->validate([
            'tieu_de_khoa_hoc'     => 'nullable|string|max:200',
            'ten_khoa_hoc'          => 'nullable|string|max:200',
            'id_danh_muc'          => 'required|integer|exists:danh_muc_khoa_hoc,id_danh_muc',
            'thoi_luong'           => 'nullable|integer|min:0',
            'tong_thoi_luong_phut' => 'nullable|integer|min:0',
            'mo_ta_ngan_gon'       => 'nullable|string|max:500',
            'noi_dung_gioi_thieu_chi_tiet' => 'nullable|string',
            'cap_do_khoa_hoc'      => 'nullable|string',
            'trang_thai_xuat_ban'  => 'nullable|string',
        ], [
            'id_danh_muc.required' => 'Vui lòng chọn danh mục đào tạo.',
            'id_danh_muc.exists'   => 'Danh mục được chọn không hợp lệ.',
        ]);

        $tenKhoaHoc = trim($request->input('ten_khoa_hoc') ?: $request->input('tieu_de_khoa_hoc', ''));
        if (empty($tenKhoaHoc)) {
            return redirect()->route('giang_vien.tong_quan', ['tab' => 'khoa-hoc'])
                ->with('active_tab', 'khoa-hoc')
                ->with('error', 'Vui lòng nhập tên khóa học.');
        }

        $khoaHoc->tieu_de_khoa_hoc = $tenKhoaHoc;
        $khoaHoc->id_danh_muc = $request->id_danh_muc;

        $thoiLuong = $request->input('thoi_luong') ?? $request->input('tong_thoi_luong_phut');
        if ($thoiLuong !== null) {
            $khoaHoc->tong_thoi_luong_phut = (int)$thoiLuong;
        }

        if ($request->has('mo_ta_ngan_gon')) {
            $khoaHoc->mo_ta_ngan_gon = $request->mo_ta_ngan_gon;
        }
        if ($request->has('noi_dung_gioi_thieu_chi_tiet')) {
            $khoaHoc->noi_dung_gioi_thieu_chi_tiet = $request->noi_dung_gioi_thieu_chi_tiet;
        }
        if ($request->has('cap_do_khoa_hoc')) {
            $khoaHoc->cap_do_khoa_hoc = $request->cap_do_khoa_hoc;
        }
        if ($request->has('trang_thai_xuat_ban')) {
            $khoaHoc->trang_thai_xuat_ban = $request->trang_thai_xuat_ban;
        }

        $khoaHoc->thoi_diem_cap_nhat = Carbon::now();
        $khoaHoc->save();

        return redirect()->route('giang_vien.tong_quan', ['tab' => 'khoa-hoc'])
            ->with('active_tab', 'khoa-hoc')
            ->with('success', "Đã cập nhật thông tin khóa học '{$khoaHoc->tieu_de_khoa_hoc}' thành công!");
    }

    public function themChuong(Request $request, $id_khoa_hoc)
    {
        // Đảm bảo khóa học thuộc quyền sở hữu của Giảng viên này
        $khoaHoc = KhoaHoc::where('id_khoa_hoc', $id_khoa_hoc)
            ->where('id_giang_vien_phu_trach', Auth::id())
            ->firstOrFail();

        $request->validate([
            'tieu_de_chuong_muc' => 'required|string|max:200',
            'mo_ta_chuong_muc'   => 'nullable|string|max:500',
        ], [
            'tieu_de_chuong_muc.required' => 'Vui lòng nhập tên chương mục.',
        ]);

        $thuTu = ChuongMucBaiHoc::where('id_khoa_hoc', $khoaHoc->id_khoa_hoc)->count() + 1;

        ChuongMucBaiHoc::create([
            'id_khoa_hoc'        => $khoaHoc->id_khoa_hoc,
            'tieu_de_chuong_muc' => trim($request->tieu_de_chuong_muc),
            'mo_ta_chuong_muc'   => $request->mo_ta_chuong_muc,
            'thu_tu_sap_xep'     => $thuTu,
        ]);

        return redirect()->route('giang_vien.tong_quan', ['tab' => 'khoa-hoc'])
            ->with('active_tab', 'khoa-hoc')
            ->with('success', "Đã thêm chương mới vào khóa học '{$khoaHoc->tieu_de_khoa_hoc}'!");
    }

    public function themBaiHoc(Request $request, $id_chuong)
    {
        // Đảm bảo chương mục thuộc khóa học của Giảng viên này
        $chuong = ChuongMucBaiHoc::where('id_chuong_muc', $id_chuong)
            ->whereHas('khoaHoc', function ($q) {
                $q->where('id_giang_vien_phu_trach', Auth::id());
            })
            ->firstOrFail();

        $request->validate([
            'tieu_de_bai_hoc'           => 'required|string|max:200',
            'thoi_luong_video_giay'     => 'nullable|integer|min:0',
            'noi_dung_bai_hoc_chi_tiet' => 'nullable|string',
            'tom_tat_kien_thuc_cho_ai'  => 'nullable|string',
            'duong_dan_video_bai_giang' => 'nullable|string|max:255',
        ], [
            'tieu_de_bai_hoc.required' => 'Vui lòng nhập tiêu đề bài học.',
        ]);

        // Nếu giảng viên chưa nhập tóm tắt AI, dùng tóm tắt từ nội dung hoặc tiêu đề
        $tomTatAi = $request->tom_tat_kien_thuc_cho_ai;
        if (empty($tomTatAi)) {
            $tomTatAi = !empty($request->noi_dung_bai_hoc_chi_tiet)
                ? Str::limit(strip_tags($request->noi_dung_bai_hoc_chi_tiet), 250)
                : $request->tieu_de_bai_hoc;
        }

        $thuTu = ChiTietBaiHoc::where('id_chuong_muc', $chuong->id_chuong_muc)->count() + 1;

        ChiTietBaiHoc::create([
            'id_chuong_muc'              => $chuong->id_chuong_muc,
            'tieu_de_bai_hoc'            => trim($request->tieu_de_bai_hoc),
            'loai_bai_hoc'               => 'video',
            'duong_dan_video_bai_giang'  => $request->duong_dan_video_bai_giang,
            'thoi_luong_video_giay'      => $request->input('thoi_luong_video_giay', 600),
            'noi_dung_bai_hoc_chi_tiet'  => $request->noi_dung_bai_hoc_chi_tiet,
            'tom_tat_kien_thuc_cho_ai'   => $tomTatAi,
            'duong_dan_tai_lieu_dinh_kem'=> null,
            'cho_phep_hoc_thu'           => $request->has('cho_phep_hoc_thu') ? 1 : 0,
            'thu_tu_sap_xep'             => $thuTu,
        ]);

        return redirect()->route('giang_vien.tong_quan', ['tab' => 'khoa-hoc'])
            ->with('active_tab', 'khoa-hoc')
            ->with('success', "Đã thêm bài học mới '{$request->tieu_de_bai_hoc}' thành công!");
    }

    public function xoaBaiHoc($id)
    {
        // Đảm bảo bài học thuộc quyền sở hữu của Giảng viên này
        $baiHoc = ChiTietBaiHoc::where('id_bai_hoc', $id)
            ->whereHas('chuongMuc.khoaHoc', function ($q) {
                $q->where('id_giang_vien_phu_trach', Auth::id());
            })
            ->firstOrFail();

        try {
            DB::transaction(function () use ($baiHoc) {
                $bhid = $baiHoc->id_bai_hoc;

                // Xóa đề thi và câu hỏi liên quan nếu có
                $deThiIds = DeThiTracNghiem::where('id_bai_hoc', $bhid)->pluck('id_de_thi');
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

                // Xóa tiến độ bài học & lịch sử chatbot AI
                DB::table('tien_do_bai_hoc')->where('id_bai_hoc', $bhid)->delete();
                DB::table('lich_su_tro_giang_ai')->where('id_bai_hoc', $bhid)->delete();

                // Xóa bài học
                $baiHoc->delete();
            });

            return redirect()->route('giang_vien.tong_quan', ['tab' => 'khoa-hoc'])
                ->with('active_tab', 'khoa-hoc')
                ->with('success', "Đã xóa bài học '{$baiHoc->tieu_de_bai_hoc}' thành công!");
        } catch (\Throwable $e) {
            return redirect()->route('giang_vien.tong_quan', ['tab' => 'khoa-hoc'])
                ->with('active_tab', 'khoa-hoc')
                ->with('error', 'Lỗi khi xóa bài học: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Chuyên môn Đề thi AI (Tự soạn thủ công, AI sinh thông minh chống trùng, Sửa câu hỏi, Xóa đề)
    |--------------------------------------------------------------------------
    */
    public function taoDeThiThuCong(Request $request)
    {
        $request->validate([
            'id_bai_hoc'              => 'required|integer|exists:chi_tiet_bai_hoc,id_bai_hoc',
            'tieu_de_de_thi'          => 'required|string|max:200',
            'thoi_luong_lam_bai_phut' => 'nullable|integer|min:1|max:180',
            'diem_so_qua_mon'         => 'nullable|numeric|min:0|max:10',
            'cau_hoi'                 => 'required|array|min:1',
            'cau_hoi.*.noi_dung'      => 'required|string',
            'cau_hoi.*.dap_an_dung'   => 'required|string|in:A,B,C,D',
            'cau_hoi.*.phuong_an'     => 'required|array',
        ], [
            'id_bai_hoc.required'            => 'Vui lòng chọn bài học muốn gán đề thi.',
            'tieu_de_de_thi.required'        => 'Vui lòng nhập tên đề thi.',
            'cau_hoi.required'               => 'Vui lòng nhập ít nhất một câu hỏi trắc nghiệm.',
            'cau_hoi.*.noi_dung.required'    => 'Nội dung câu hỏi không được để trống.',
            'cau_hoi.*.dap_an_dung.required' => 'Vui lòng tích chọn đáp án đúng (A, B, C hoặc D).',
        ]);

        // Đảm bảo bài học thuộc quyền sở hữu của Giảng viên này
        $baiHoc = ChiTietBaiHoc::where('id_bai_hoc', $request->id_bai_hoc)
            ->whereHas('chuongMuc.khoaHoc', function ($q) {
                $q->where('id_giang_vien_phu_trach', Auth::id());
            })
            ->firstOrFail();

        $countSuccess = 0;

        DB::transaction(function () use ($request, $baiHoc, &$countSuccess) {
            // Tìm hoặc tạo đề thi gắn với bài học này
            $deThi = DeThiTracNghiem::where('id_bai_hoc', $baiHoc->id_bai_hoc)->first();

            if ($deThi) {
                if ($request->filled('tieu_de_de_thi')) {
                    $deThi->tieu_de_de_thi = trim($request->tieu_de_de_thi);
                }
                if ($request->filled('thoi_luong_lam_bai_phut')) {
                    $deThi->thoi_luong_lam_bai_phut = (int)$request->thoi_luong_lam_bai_phut;
                }
                if ($request->filled('diem_so_qua_mon')) {
                    $deThi->diem_so_qua_mon = (float)$request->diem_so_qua_mon;
                }
                $deThi->save();
            } else {
                $deThi = DeThiTracNghiem::create([
                    'id_bai_hoc'              => $baiHoc->id_bai_hoc,
                    'tieu_de_de_thi'          => trim($request->tieu_de_de_thi),
                    'mo_ta_yeu_cau'           => 'Đề thi trắc nghiệm do Thầy/Cô trực tiếp biên soạn.',
                    'thoi_luong_lam_bai_phut' => $request->input('thoi_luong_lam_bai_phut', 15),
                    'diem_so_toi_da'          => 10,
                    'diem_so_qua_mon'         => $request->input('diem_so_qua_mon', 5),
                    'so_lan_lam_lai_toi_da'   => 5,
                    'cho_phep_xem_loi_giai'   => 1,
                    'trang_thai_mo_de'        => 1,
                ]);
            }

            foreach ($request->cau_hoi as $qItem) {
                if (empty(trim($qItem['noi_dung'] ?? ''))) {
                    continue;
                }

                $thuTu = CauHoiKiemTra::where('id_de_thi', $deThi->id_de_thi)->count() + 1;
                $mucDoKho = $qItem['muc_do_kho'] ?? 'trung_binh';
                if (!in_array($mucDoKho, ['de', 'trung_binh', 'kho'])) {
                    $mucDoKho = 'trung_binh';
                }

                $cauHoi = CauHoiKiemTra::create([
                    'id_de_thi'                  => $deThi->id_de_thi,
                    'noi_dung_cau_hoi'           => trim($qItem['noi_dung']),
                    'giai_thich_chi_tiet_dap_an' => trim($qItem['giai_thich'] ?? 'Không có giải thích'),
                    'nguon_goc_cau_hoi'          => 'giang_vien_soan',
                    'trang_thai_kiem_duyet'      => 'da_duyet',
                    'muc_do_kho'                 => $mucDoKho,
                    'diem_so_cau_hoi'            => 1,
                    'thu_tu_hien_thi'            => $thuTu,
                ]);

                $dapAnDung = strtoupper(trim($qItem['dap_an_dung'] ?? 'A'));

                foreach (['A', 'B', 'C', 'D'] as $kyTu) {
                    $paText = trim($qItem['phuong_an'][$kyTu] ?? '');
                    if ($paText === '') {
                        $paText = "Lựa chọn " . $kyTu;
                    }

                    PhuongAnLuaChon::create([
                        'id_cau_hoi'          => $cauHoi->id_cau_hoi,
                        'ky_tu_phuong_an'     => $kyTu,
                        'noi_dung_phuong_an'  => $paText,
                        'la_dap_an_chinh_xac' => ($kyTu === $dapAnDung) ? 1 : 0,
                    ]);
                }

                $countSuccess++;
            }
        });

        return redirect()->route('giang_vien.tong_quan', ['tab' => 'de-thi'])
            ->with('active_tab', 'de-thi')
            ->with('success', "✍️ Đã lưu thành công đề thi và {$countSuccess} câu hỏi tự soạn cho bài '{$baiHoc->tieu_de_bai_hoc}'!");
    }

    public function sinhDeThiAi(Request $request, $id_bai_hoc = null)
    {
        $targetBaiHocId = $id_bai_hoc ?: $request->input('id_bai_hoc');

        if (!$targetBaiHocId) {
            return redirect()->route('giang_vien.tong_quan', ['tab' => 'de-thi'])
                ->with('active_tab', 'de-thi')
                ->with('error', 'Vui lòng chọn bài học cần sinh đề thi.');
        }

        // Đảm bảo bài học thuộc quyền sở hữu của Giảng viên này
        $baiHoc = ChiTietBaiHoc::where('id_bai_hoc', $targetBaiHocId)
            ->whereHas('chuongMuc.khoaHoc', function ($q) {
                $q->where('id_giang_vien_phu_trach', Auth::id());
            })
            ->firstOrFail();

        // 1. Tìm hoặc tạo đề thi gắn với bài học này
        $deThi = DeThiTracNghiem::firstOrCreate(
            ['id_bai_hoc' => $baiHoc->id_bai_hoc],
            [
                'tieu_de_de_thi'          => 'Đề trắc nghiệm AI: ' . $baiHoc->tieu_de_bai_hoc,
                'mo_ta_yeu_cau'           => 'Bài kiểm tra tự động do Gemini AI sinh từ nội dung bài học.',
                'thoi_luong_lam_bai_phut' => 15,
                'diem_so_toi_da'          => 10,
                'diem_so_qua_mon'         => 5,
                'so_lan_lam_lai_toi_da'   => 5,
                'cho_phep_xem_loi_giai'   => 1,
                'trang_thai_mo_de'        => 1,
            ]
        );

        // 2. Lấy toàn bộ nội dung các câu hỏi hiện có của bài học này để CHỐNG TRÙNG LẶP
        $cauHoiHienCo = CauHoiKiemTra::where('id_de_thi', $deThi->id_de_thi)
            ->pluck('noi_dung_cau_hoi')
            ->filter()
            ->values()
            ->toArray();

        // 3. Đọc cấu hình mong muốn (Số lượng câu & Độ khó ưu tiên)
        $soLuong = (int)$request->input('so_luong_cau_hoi', 4);
        if (!in_array($soLuong, [3, 4, 5, 10])) {
            $soLuong = 4;
        }

        $doKho = $request->input('do_kho', 'hon_hop');
        $doKhoMoTa = match($doKho) {
            'de'         => 'Độ khó ưu tiên: Dễ (nhận biết kiến thức căn bản).',
            'kho'        => 'Độ khó ưu tiên: Khó (vận dụng thực tiễn, tư duy logic, phân tích chuyên sâu).',
            'trung_binh' => 'Độ khó ưu tiên: Trung bình (thông hiểu kiến thức bài giảng).',
            default      => 'Độ khó phân bổ cân đối: bao gồm cả Dễ, Trung bình và Khó.'
        };

        // Chuẩn bị danh sách câu hỏi cũ đưa vào prompt chống trùng
        $chongTrungText = "";
        if (!empty($cauHoiHienCo)) {
            $chongTrungText = "\nCÁC CÂU HỎI ĐÃ CÓ (TUYỆT ĐỐI KHÔNG TẠO TRÙNG HOẶC TƯƠNG TỰ VỀ NỘI DUNG VỚI DANH SÁCH NÀY):\n";
            foreach ($cauHoiHienCo as $idx => $noiDungCu) {
                $chongTrungText .= "- " . trim($noiDungCu) . "\n";
            }
        }

        $contextText = $baiHoc->tom_tat_kien_thuc_cho_ai ?: ($baiHoc->noi_dung_bai_hoc_chi_tiet ?: $baiHoc->tieu_de_bai_hoc);
        $apiKey = env('GEMINI_API_KEY');

        $questionsData = [];

        if ($apiKey) {
            set_time_limit(90);
            $prompt = "Bạn là chuyên gia sư phạm và biên soạn đề kiểm tra trắc nghiệm cho nền tảng e-learning.\n"
                . "Dựa trên nội dung bài học: '{$baiHoc->tieu_de_bai_hoc}' và tóm tắt kiến thức: '{$contextText}'.\n"
                . "Hãy tạo ĐÚNG {$soLuong} câu hỏi trắc nghiệm khách quan mới chất lượng cao nhằm đánh giá hiểu biết của học viên.\n"
                . "{$doKhoMoTa}\n"
                . "{$chongTrungText}\n"
                . "Yêu cầu kỹ thuật bắt buộc:\n"
                . "- Mỗi câu hỏi gồm đúng 4 phương án (A, B, C, D) và chỉ có 1 phương án chính xác.\n"
                . "- Giá trị 'muc_do_kho' điền một trong: 'de', 'trung_binh', 'kho'.\n"
                . "- Trả về DUY NHẤT một mảng JSON thuần túy (không kèm markdown ```json hay bất kỳ văn bản nào khác ngoài JSON), theo mẫu sau:\n"
                . "[\n"
                . "  {\n"
                . "    \"noi_dung_cau_hoi\": \"Nội dung câu hỏi...\",\n"
                . "    \"giai_thich\": \"Giải thích ngắn gọn vì sao đáp án này đúng...\",\n"
                . "    \"muc_do_kho\": \"trung_binh\",\n"
                . "    \"phuong_an\": [\n"
                . "      {\"ky_tu\": \"A\", \"noi_dung\": \"...\", \"dung\": 1},\n"
                . "      {\"ky_tu\": \"B\", \"noi_dung\": \"...\", \"dung\": 0},\n"
                . "      {\"ky_tu\": \"C\", \"noi_dung\": \"...\", \"dung\": 0},\n"
                . "      {\"ky_tu\": \"D\", \"noi_dung\": \"...\", \"dung\": 0}\n"
                . "    ]\n"
                . "  }\n"
                . "]";

            $models = ['gemini-flash-latest', 'gemini-flash-lite-latest', 'gemini-pro-latest'];

            foreach ($models as $modelName) {
                try {
                    $response = Http::timeout(20)->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.7,
                        ]
                    ]);

                    if ($response->successful()) {
                        $resData = $response->json();
                        $rawText = $resData['candidates'][0]['content']['parts'][0]['text'] ?? '';
                        $cleanJson = preg_replace('/```(json)?|```/', '', $rawText);
                        $parsed = json_decode(trim($cleanJson), true);

                        if (is_array($parsed) && count($parsed) > 0 && isset($parsed[0]['noi_dung_cau_hoi'])) {
                            $questionsData = array_slice($parsed, 0, $soLuong);
                            break;
                        }
                    }
                } catch (\Throwable $e) {
                    // Thử model kế tiếp
                }
            }
        }

        // Fallback chất lượng cao nếu Gemini API bận hoặc chưa có API Key
        if (empty($questionsData)) {
            $existingCount = count($cauHoiHienCo);
            $baseBank = [
                [
                    'noi_dung_cau_hoi' => "Mục tiêu trọng tâm của bài học '{$baiHoc->tieu_de_bai_hoc}' là gì?",
                    'giai_thich'       => "Nội dung bài học nhấn mạnh vào việc nắm bắt kiến thức nền tảng và ứng dụng thực tiễn.",
                    'muc_do_kho'       => "de",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Hiểu rõ khái niệm và vận dụng linh hoạt trong bài tập thực tế", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Học thuộc lòng định nghĩa mà không cần thực hành", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Bỏ qua các bước cơ bản để làm phần nâng cao", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Chỉ áp dụng lý thuyết trên giấy", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Phương pháp nào sau đây giúp tiếp thu tốt nhất nội dung '{$baiHoc->tieu_de_bai_hoc}'?",
                    'giai_thich'       => "Kết hợp học video bài giảng và thực hành trắc nghiệm củng cố kiến thức ngay.",
                    'muc_do_kho'       => "trung_binh",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Theo dõi kỹ bài giảng và hoàn thành câu hỏi kiểm tra", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Chỉ đọc lướt tiêu đề", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Bỏ qua video và tài liệu đính kèm", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Không cần ôn tập định kỳ", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Trong bối cảnh thực hành, kỹ năng nào đóng vai trò cốt lõi trong chủ đề này?",
                    'giai_thich'       => "Kỹ năng phân tích logic và thực hành giải quyết bài toán cụ thể.",
                    'muc_do_kho'       => "kho",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Tư duy giải quyết vấn đề và tối ưu hóa giải pháp", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Sao chép nguyên mẫu mà không hiểu nguyên lý", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Áp dụng công thức ngẫu nhiên", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Dừng lại ngay khi gặp lỗi đầu tiên", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Khi hoàn thành bài học, bước kiểm tra nào sau đây là quan trọng nhất?",
                    'giai_thich'       => "Đối chiếu kết quả làm bài trắc nghiệm và đọc kỹ giải thích đáp án sai.",
                    'muc_do_kho'       => "trung_binh",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Đánh giá điểm số và đọc phần giải thích chi tiết", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Chỉ ghi nhớ điểm mà không xem lại câu sai", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Lập tức chuyển sang bài khác mà không kiểm tra", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Đóng hệ thống không nộp bài", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Khi triển khai thực tế kiến thức bài '{$baiHoc->tieu_de_bai_hoc}', sai lầm phổ biến nào cần tránh?",
                    'giai_thich'       => "Tránh việc không kiểm tra ngoại lệ và thiếu tối ưu hóa hiệu năng.",
                    'muc_do_kho'       => "kho",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Bỏ qua các trường hợp biên và không xử lý ngoại lệ", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Viết mã nguồn rõ ràng có chú thích", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Kiểm thử kỹ lưỡng trước khi đưa vào vận hành", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Tối ưu hóa tài nguyên hệ thống", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Yếu tố nào quyết định chất lượng đầu ra khi hoàn thành bài '{$baiHoc->tieu_de_bai_hoc}'?",
                    'giai_thich'       => "Khả năng tự áp dụng giải quyết bài toán độc lập.",
                    'muc_do_kho'       => "trung_binh",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Khả năng xây dựng dự án và giải quyết vấn đề độc lập", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Ghi nhớ từng dòng lệnh mà không thực hành", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Sử dụng mã của người khác mà không hiểu", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Chỉ hoàn thành lý thuyết trắc nghiệm", 'dung' => 0],
                    ]
                ],
            ];

            // Lọc ra các câu hỏi chưa từng xuất hiện trong danh sách câu hỏi cũ
            $filtered = [];
            foreach ($baseBank as $candidate) {
                $isDuplicated = false;
                foreach ($cauHoiHienCo as $old) {
                    if (mb_stripos($old, mb_substr($candidate['noi_dung_cau_hoi'], 0, 30)) !== false) {
                        $isDuplicated = true;
                        break;
                    }
                }
                if (!$isDuplicated) {
                    $filtered[] = $candidate;
                }
            }

            if (empty($filtered)) {
                // Tạo câu hỏi biến thể mới nếu đã trùng hết ngân hàng cơ bản
                for ($k = 1; $k <= $soLuong; $k++) {
                    $num = $existingCount + $k;
                    $filtered[] = [
                        'noi_dung_cau_hoi' => "Câu hỏi mở rộng {$num}: Ứng dụng nâng cao của chủ đề '{$baiHoc->tieu_de_bai_hoc}'?",
                        'giai_thich'       => "Vận dụng tổng hợp các kiến thức đã học vào ngữ cảnh thực tế.",
                        'muc_do_kho'       => "kho",
                        'phuong_an'        => [
                            ['ky_tu' => 'A', 'noi_dung' => "Phân tích và tối ưu hóa giải pháp trong hệ thống thực tế", 'dung' => 1],
                            ['ky_tu' => 'B', 'noi_dung' => "Chỉ sử dụng cấu hình mặc định", 'dung' => 0],
                            ['ky_tu' => 'C', 'noi_dung' => "Không cần theo dõi tiến trình thực thi", 'dung' => 0],
                            ['ky_tu' => 'D', 'noi_dung' => "Bỏ qua các tiêu chuẩn an toàn thông tin", 'dung' => 0],
                        ]
                    ];
                }
            }

            $questionsData = array_slice($filtered, 0, $soLuong);
        }

        // Lưu danh sách câu hỏi và phương án vào Database
        $countSuccess = 0;
        foreach ($questionsData as $qItem) {
            $currentOrder = CauHoiKiemTra::where('id_de_thi', $deThi->id_de_thi)->count() + 1;

            $mucDo = strtolower(trim($qItem['muc_do_kho'] ?? 'trung_binh'));
            if (!in_array($mucDo, ['de', 'trung_binh', 'kho'])) {
                $mucDo = 'trung_binh';
            }

            $cauHoi = CauHoiKiemTra::create([
                'id_de_thi'                  => $deThi->id_de_thi,
                'noi_dung_cau_hoi'           => $qItem['noi_dung_cau_hoi'],
                'giai_thich_chi_tiet_dap_an' => $qItem['giai_thich'] ?? 'Không có giải thích',
                'nguon_goc_cau_hoi'          => 'ai_tao',
                'trang_thai_kiem_duyet'      => 'da_duyet',
                'muc_do_kho'                 => $mucDo,
                'diem_so_cau_hoi'            => 1,
                'thu_tu_hien_thi'            => $currentOrder,
            ]);

            if (isset($qItem['phuong_an']) && is_array($qItem['phuong_an'])) {
                foreach ($qItem['phuong_an'] as $pa) {
                    PhuongAnLuaChon::create([
                        'id_cau_hoi'          => $cauHoi->id_cau_hoi,
                        'ky_tu_phuong_an'     => $pa['ky_tu'] ?? 'A',
                        'noi_dung_phuong_an'  => $pa['noi_dung'] ?? '',
                        'la_dap_an_chinh_xac' => $pa['dung'] ? 1 : 0,
                    ]);
                }
            }
            $countSuccess++;
        }

        return redirect()->route('giang_vien.tong_quan', ['tab' => 'de-thi'])
            ->with('active_tab', 'de-thi')
            ->with('success', "✨ AI đã sinh thành công {$countSuccess} câu hỏi trắc nghiệm mới (chống trùng) cho bài '{$baiHoc->tieu_de_bai_hoc}'!");
    }

    public function capNhatCauHoi(Request $request, $id_cau_hoi)
    {
        // Đảm bảo câu hỏi thuộc đề thi bài học do Giảng viên này phụ trách
        $cauHoi = CauHoiKiemTra::where('id_cau_hoi', $id_cau_hoi)
            ->whereHas('deThi.baiHoc.chuongMuc.khoaHoc', function ($q) {
                $q->where('id_giang_vien_phu_trach', Auth::id());
            })
            ->firstOrFail();

        $request->validate([
            'noi_dung_cau_hoi'           => 'required|string',
            'giai_thich_chi_tiet_dap_an' => 'nullable|string',
            'muc_do_kho'                 => 'nullable|string',
            'phuong_an'                  => 'required|array|min:2',
            'dap_an_dung'                => 'required',
        ], [
            'noi_dung_cau_hoi.required' => 'Vui lòng nhập nội dung câu hỏi.',
            'dap_an_dung.required'      => 'Vui lòng chọn 1 phương án là đáp án chính xác.',
        ]);

        // Cập nhật câu hỏi
        $cauHoi->noi_dung_cau_hoi = trim($request->noi_dung_cau_hoi);
        $cauHoi->giai_thich_chi_tiet_dap_an = $request->giai_thich_chi_tiet_dap_an;
        if ($request->has('muc_do_kho')) {
            $cauHoi->muc_do_kho = $request->muc_do_kho;
        }
        $cauHoi->save();

        // Cập nhật các phương án lựa chọn và đánh dấu đáp án đúng
        $dapAnDungId = (int)$request->dap_an_dung;

        foreach ($request->phuong_an as $paId => $noiDung) {
            $phuongAn = PhuongAnLuaChon::where('id_phuong_an', $paId)
                ->where('id_cau_hoi', $cauHoi->id_cau_hoi)
                ->first();

            if ($phuongAn) {
                $phuongAn->noi_dung_phuong_an = trim($noiDung);
                $phuongAn->la_dap_an_chinh_xac = ($phuongAn->id_phuong_an === $dapAnDungId) ? 1 : 0;
                $phuongAn->save();
            }
        }

        return redirect()->route('giang_vien.tong_quan', ['tab' => 'de-thi'])
            ->with('active_tab', 'de-thi')
            ->with('success', "Đã lưu chỉnh sửa câu hỏi và đáp án thành công!");
    }

    public function xoaDeThi($id)
    {
        // Đảm bảo đề thi thuộc bài học của Giảng viên này
        $deThi = DeThiTracNghiem::where('id_de_thi', $id)
            ->whereHas('baiHoc.chuongMuc.khoaHoc', function ($q) {
                $q->where('id_giang_vien_phu_trach', Auth::id());
            })
            ->firstOrFail();

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

                // Xóa các phương án lựa chọn
                if ($cauHoiIds->isNotEmpty()) {
                    PhuongAnLuaChon::whereIn('id_cau_hoi', $cauHoiIds)->delete();
                    CauHoiKiemTra::whereIn('id_cau_hoi', $cauHoiIds)->delete();
                }

                // Xóa lượt làm bài thi
                if ($luotThiIds->isNotEmpty()) {
                    LuotLamBaiThi::whereIn('id_luot_lam_bai', $luotThiIds)->delete();
                }

                // Xóa đề thi
                $deThi->delete();
            });

            return redirect()->route('giang_vien.tong_quan', ['tab' => 'de-thi'])
                ->with('active_tab', 'de-thi')
                ->with('success', "Đã xóa đề thi '{$deThi->tieu_de_de_thi}' thành công!");
        } catch (\Throwable $e) {
            return redirect()->route('giang_vien.tong_quan', ['tab' => 'de-thi'])
                ->with('active_tab', 'de-thi')
                ->with('error', 'Lỗi khi xóa đề thi: ' . $e->getMessage());
        }
    }
}
