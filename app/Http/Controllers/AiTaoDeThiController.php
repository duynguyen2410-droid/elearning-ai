<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChiTietBaiHoc;
use App\Models\DeThiTracNghiem;
use App\Models\CauHoiKiemTra;
use App\Models\PhuongAnLuaChon;
use App\Models\LuotLamBaiThi;
use App\Models\ChiTietCauTraLoi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AiTaoDeThiController extends Controller
{
    public function generateQuiz($baiHocId)
    {
        $baiHoc = ChiTietBaiHoc::findOrFail($baiHocId);
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return back()->with('error', 'Chưa cấu hình GEMINI_API_KEY trong file .env');
        }

        // 1. Tìm hoặc tạo đề thi gắn với bài học này
        $deThi = DeThiTracNghiem::firstOrCreate(
            ['id_bai_hoc' => $baiHoc->id_bai_hoc],
            [
                'tieu_de_de_thi'          => 'Bài trắc nghiệm: ' . $baiHoc->tieu_de_bai_hoc,
                'mo_ta_yeu_cau'           => 'Bài kiểm tra tự động bởi Trợ lý AI.',
                'thoi_luong_lam_bai_phut' => 15,
                'diem_so_toi_da'          => 10,
                'diem_so_qua_mon'         => 5,
                'so_lan_lam_lai_toi_da'   => 5,
                'cho_phep_xem_loi_giai'   => 1,
                'trang_thai_mo_de'        => 1,
            ]
        );

        // 2. Lấy danh sách các câu hỏi đã có sẵn để chống sinh trùng lặp
        $cacCauHoiCu = CauHoiKiemTra::where('id_de_thi', $deThi->id_de_thi)
            ->pluck('noi_dung_cau_hoi')
            ->toArray();

        $danhSachDaCo = empty($cacCauHoiCu) ? 'Chưa có' : implode("\n- ", $cacCauHoiCu);

        // 3. Xây dựng prompt chi tiết
        $prompt = "Dựa trên nội dung tóm tắt sau: '{$baiHoc->tom_tat_kien_thuc_cho_ai}'.\n"
            . "Các câu hỏi ĐÃ CÓ trong đề thi (TUYỆT ĐỐI KHÔNG TẠO TRÙNG LẶP HAY TƯƠNG TỰ CÁC CÂU NÀY):\n"
            . "- {$danhSachDaCo}\n\n"
            . "Yêu cầu: Hãy khai thác một khía cạnh kiến thức KHÁC hoàn toàn so với các câu trên, tạo 1 câu hỏi trắc nghiệm gồm 4 đáp án (A, B, C, D) với đúng 1 đáp án chính xác.\n"
            . "Trả về DUY NHẤT định dạng JSON như sau, không kèm bất kỳ giải thích hay ký tự markdown nào:\n"
            . "{\n"
            . "  \"noi_dung_cau_hoi\": \"Chuỗi câu hỏi mới...\",\n"
            . "  \"giai_thich\": \"Giải thích ngắn gọn lý do vì sao đáp án đúng...\",\n"
            . "  \"phuong_an\": [\n"
            . "    {\"ky_tu\": \"A\", \"noi_dung\": \"...\", \"dung\": 1},\n"
            . "    {\"ky_tu\": \"B\", \"noi_dung\": \"...\", \"dung\": 0},\n"
            . "    {\"ky_tu\": \"C\", \"noi_dung\": \"...\", \"dung\": 0},\n"
            . "    {\"ky_tu\": \"D\", \"noi_dung\": \"...\", \"dung\": 0}\n"
            . "  ]\n"
            . "}";

        set_time_limit(120);

        // Danh sách model ưu tiên
        $models = ['gemini-flash-latest', 'gemini-flash-lite-latest', 'gemini-pro-latest'];
        $quizData = null;
        $lastError = '';

        foreach ($models as $modelName) {
            try {
                $response = Http::timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7, // Tăng sáng tạo để tránh câu hỏi trùng lặp
                    ]
                ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    $rawText = $resData['candidates'][0]['content']['parts'][0]['text'] ?? '';

                    // Lọc bỏ markdown block nếu Gemini bọc json
                    $cleanJson = preg_replace('/```(json)?|```/', '', $rawText);
                    $parsed = json_decode(trim($cleanJson), true);

                    if ($parsed && isset($parsed['noi_dung_cau_hoi']) && isset($parsed['phuong_an'])) {
                        $quizData = $parsed;
                        break;
                    }
                } else {
                    $lastError = $response->body();
                }
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
            }
        }

        if (!$quizData) {
            return back()->with('error', 'Hệ thống AI đang bận hoặc không thể tạo câu hỏi: ' . $lastError);
        }

        // 4. Lưu câu hỏi vào DB
        $thuTu = CauHoiKiemTra::where('id_de_thi', $deThi->id_de_thi)->count() + 1;
        $cauHoi = CauHoiKiemTra::create([
            'id_de_thi'                  => $deThi->id_de_thi,
            'noi_dung_cau_hoi'           => '[AI Tạo] ' . $quizData['noi_dung_cau_hoi'],
            'giai_thich_chi_tiet_dap_an' => $quizData['giai_thich'] ?? 'Không có giải thích',
            'nguon_goc_cau_hoi'          => 'ai_sinh',
            'trang_thai_kiem_duyet'      => 1,
            'muc_do_kho'                 => 'Trung_binh',
            'diem_so_cau_hoi'            => 1,
            'thu_tu_hien_thi'            => $thuTu,
        ]);

        // 5. Lưu 4 phương án lựa chọn
        foreach ($quizData['phuong_an'] as $pa) {
            PhuongAnLuaChon::create([
                'id_cau_hoi'          => $cauHoi->id_cau_hoi,
                'ky_tu_phuong_an'     => $pa['ky_tu'],
                'noi_dung_phuong_an'  => $pa['noi_dung'],
                'la_dap_an_chinh_xac' => $pa['dung'],
            ]);
        }

        return redirect()->route('quiz.take', $deThi->id_de_thi)->with('success', 'AI đã sinh câu hỏi mới thành công!');
    }

    public function nopBai(Request $request, $deThiId = null)
    {
        $deThiId = $deThiId ?? $request->input('id_de_thi') ?? $request->input('de_thi_id');
        $dapAnNguoiDung = $request->input('dap_an') ?? $request->input('cau_hoi', []);
        $tongSoCau = count($dapAnNguoiDung);

        if ($tongSoCau === 0) {
            return back()->with('error', 'Bạn chưa chọn đáp án nào.');
        }

        $soCauDung = 0;
        $chiTietKetQua = [];

        foreach ($dapAnNguoiDung as $idCauHoi => $idPhuongAnChon) {
            $cauHoi = CauHoiKiemTra::with('danhSachPhuongAn')->find($idCauHoi);
            
            if (!$cauHoi) continue;

            $danhSach = $cauHoi->danhSachPhuongAn;

            // Tìm phương án đúng theo cột la_dap_an_chinh_xac
            $phuongAnDung = $danhSach->firstWhere('la_dap_an_chinh_xac', 1);
            $phuongAnChon = $danhSach->firstWhere('id_phuong_an', $idPhuongAnChon);

            // So khớp ID
            $laDung = false;
            if ($phuongAnDung && $phuongAnChon) {
                $laDung = ((int)$phuongAnDung->id_phuong_an === (int)$idPhuongAnChon);
            }

            if ($laDung) {
                $soCauDung++;
            }

            $chiTietKetQua[] = [
                'id_cau_hoi'     => $idCauHoi,
                'id_phuong_an'   => $idPhuongAnChon,
                'cau_hoi'        => $cauHoi->noi_dung_cau_hoi,
                'giai_thich'     => $cauHoi->giai_thich_chi_tiet_dap_an,
                'nguon_goc'      => $cauHoi->nguon_goc_cau_hoi ?? $cauHoi->nguon_tao,
                'phuong_an_chon' => $phuongAnChon,
                'phuong_an_dung' => $phuongAnDung,
                'ket_qua'        => $laDung,
            ];
        }

        $diemSo = $tongSoCau > 0 ? round(($soCauDung / $tongSoCau) * 10, 1) : 0;
        $xepLoai = $diemSo >= 5.0 ? 'Dat' : 'Khong_dat';

        // Lấy ID học viên đang đăng nhập (hoặc dùng tài khoản mẫu nếu chưa auth)
        $idHocVien = Auth::id() ?? 1;

        // Tính lần thi thứ mấy của học viên đối với đề này
        $lanThi = LuotLamBaiThi::where('id_de_thi', $deThiId)
            ->where('id_hoc_vien', $idHocVien)
            ->count() + 1;

        // 1. Lưu thông tin lượt làm bài
        $luotLamBai = LuotLamBaiThi::create([
            'id_de_thi'             => $deThiId,
            'id_hoc_vien'           => $idHocVien,
            'lan_thi_thu'           => $lanThi,
            'thoi_diem_bat_dau_lam' => Carbon::now()->subMinutes(5), // Ước lượng thời điểm bắt đầu
            'thoi_diem_nop_bai'     => Carbon::now(),
            'tong_so_cau_hoi'       => $tongSoCau,
            'so_cau_tra_loi_dung'   => $soCauDung,
            'tong_diem_dat_duoc'    => $diemSo,
            'ket_qua_xep_loai'      => $xepLoai,
        ]);

        // 2. Lưu chi tiết từng câu trả lời vào bảng chi_tiet_cau_tra_loi
        foreach ($chiTietKetQua as $item) {
            ChiTietCauTraLoi::create([
                'id_luot_lam_bai'      => $luotLamBai->id_luot_lam_bai,
                'id_cau_hoi'           => $item['id_cau_hoi'],
                'id_phuong_an_da_chon' => $item['id_phuong_an'],
                'danh_gia_dung_sai'    => $item['ket_qua'] ? 1 : 0,
            ]);
        }

        return view('hoc_vien.ket_qua_thi', compact('diemSo', 'soCauDung', 'tongSoCau', 'chiTietKetQua'));
    }

    /**
     * Xem chi tiết kết quả lượt làm bài thi theo ID lượt thi
     */
    public function xemKetQua($idLuotLamBai)
    {
        $luotLamBai = LuotLamBaiThi::findOrFail($idLuotLamBai);
        $danhSachTraLoi = ChiTietCauTraLoi::where('id_luot_lam_bai', $luotLamBai->id_luot_lam_bai)->get();

        $chiTietKetQua = [];
        foreach ($danhSachTraLoi as $tl) {
            $cauHoi = CauHoiKiemTra::with('danhSachPhuongAn')->find($tl->id_cau_hoi);
            if (!$cauHoi) continue;

            $danhSach = $cauHoi->danhSachPhuongAn;
            $phuongAnDung = $danhSach->firstWhere('la_dap_an_chinh_xac', 1);
            $phuongAnChon = $danhSach->firstWhere('id_phuong_an', $tl->id_phuong_an_da_chon);

            $chiTietKetQua[] = [
                'id_cau_hoi'     => $cauHoi->id_cau_hoi,
                'id_phuong_an'   => $tl->id_phuong_an_da_chon,
                'cau_hoi'        => $cauHoi->noi_dung_cau_hoi,
                'giai_thich'     => $cauHoi->giai_thich_chi_tiet_dap_an,
                'nguon_goc'      => $cauHoi->nguon_goc_cau_hoi ?? $cauHoi->nguon_tao,
                'phuong_an_chon' => $phuongAnChon,
                'phuong_an_dung' => $phuongAnDung,
                'ket_qua'        => (bool)$tl->danh_gia_dung_sai,
            ];
        }

        $diemSo = $luotLamBai->tong_diem_dat_duoc;
        $soCauDung = $luotLamBai->so_cau_tra_loi_dung;
        $tongSoCau = $luotLamBai->tong_so_cau_hoi;

        return view('hoc_vien.ket_qua_thi', compact('diemSo', 'soCauDung', 'tongSoCau', 'chiTietKetQua'));
    }
}

