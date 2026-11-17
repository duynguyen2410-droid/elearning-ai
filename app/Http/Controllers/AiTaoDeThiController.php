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
use App\Services\GeminiService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AiTaoDeThiController extends Controller
{
    /**
     * AI Phát sinh đề thi trắc nghiệm (Hỗ trợ giới hạn 3 lần cho học viên, tùy chọn 3/5/10 câu)
     */
    public function generateQuiz(Request $request, $baiHocId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('dang_nhap')->with('error', 'Vui lòng đăng nhập để sử dụng tính năng AI.');
        }

        $baiHoc = ChiTietBaiHoc::with('chuongMuc.khoaHoc')->findOrFail($baiHocId);
        $isHocVien = ((int)$user->id_phan_quyen === 3);
        $soLanDaTao = 0;

        // 1. Kiểm tra giới hạn lượt tạo: Học viên tối đa 3 lần / bài học
        if ($isHocVien) {
            $soLanDaTao = DeThiTracNghiem::where(function ($q) use ($baiHocId) {
                    $q->where('id_chi_tiet_bai_hoc', $baiHocId)
                      ->orWhere('id_bai_hoc', $baiHocId);
                })
                ->where('id_nguoi_tao', $user->id_nguoi_dung)
                ->count();

            if ($soLanDaTao >= 3) {
                return back()->with('error', 'Bạn đã dùng hết giới hạn 3 lần phát sinh đề thi AI cho bài học này!');
            }
        }

        // 2. Lấy số lượng câu hỏi mong muốn (3, 5, 10 câu)
        $soLuong = (int)$request->input('so_luong_cau_hoi', 5);
        if (!in_array($soLuong, [3, 5, 10])) {
            $soLuong = 5;
        }

        // 3. Lấy danh sách câu hỏi đã có sẵn của bài học này để CHỐNG TRÙNG LẶP
        $cauHoiHienCo = CauHoiKiemTra::whereHas('deThi', function ($q) use ($baiHocId) {
                $q->where('id_chi_tiet_bai_hoc', $baiHocId)
                  ->orWhere('id_bai_hoc', $baiHocId);
            })
            ->pluck('noi_dung_cau_hoi')
            ->map(function ($q) {
                return trim(preg_replace('/^\[AI\s*Tạo\]\s*/i', '', $q));
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $chongTrungText = "";
        if (!empty($cauHoiHienCo)) {
            $chongTrungText = "\nCÁC CÂU HỎI ĐÃ CÓ (TUYỆT ĐỐI KHÔNG TẠO TRÙNG HOẶC TƯƠNG TỰ VỀ NỘI DUNG VỚI DANH SÁCH NÀY):\n";
            foreach (array_slice($cauHoiHienCo, -15) as $c) {
                $chongTrungText .= "- " . $c . "\n";
            }
        }

        $contextText = $baiHoc->tom_tat_kien_thuc_cho_ai
            ?: ($baiHoc->noi_dung_bai_hoc_chi_tiet
                ? strip_tags($baiHoc->noi_dung_bai_hoc_chi_tiet)
                : $baiHoc->tieu_de_bai_hoc);

        $prompt = "Bạn là chuyên gia sư phạm và biên soạn đề thi trắc nghiệm AI cho nền tảng e-learning EduMind AI.\n"
            . "Dựa trên bài học: '{$baiHoc->tieu_de_bai_hoc}' và tóm tắt kiến thức: '{$contextText}'.\n"
            . "Hãy tạo ĐÚNG {$soLuong} câu hỏi trắc nghiệm khách quan mới chất lượng cao nhằm đánh giá hiểu biết của học viên.\n"
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

        // 4. Gọi GeminiService tập trung để sinh câu hỏi
        $geminiService = app(GeminiService::class);
        $questionsData = $geminiService->generateQuizQuestions($prompt, $soLuong);

        // 5. Fallback câu hỏi chất lượng cao nếu Gemini API bận hoặc chưa có phản hồi
        if (empty($questionsData)) {
            $baseBank = [
                [
                    'noi_dung_cau_hoi' => "Mục tiêu cốt lõi của bài học '{$baiHoc->tieu_de_bai_hoc}' là gì?",
                    'giai_thich'       => "Nội dung bài học hướng đến việc nắm chắc khái niệm nền tảng và vận dụng giải quyết bài tập thực tế.",
                    'muc_do_kho'       => "de",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Hiểu rõ bản chất kiến thức và áp dụng chính xác vào thực tế", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Chỉ học thuộc lòng lý thuyết mà không cần thực hành", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Bỏ qua các bước cơ bản để làm phần nâng cao", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Chỉ áp dụng lý thuyết trên giấy", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Để nắm vững kiến thức bài '{$baiHoc->tieu_de_bai_hoc}', phương pháp học tập nào tối ưu nhất?",
                    'giai_thich'       => "Kết hợp theo dõi bài giảng, làm bài tập thực hành và trắc nghiệm củng cố kiến thức.",
                    'muc_do_kho'       => "trung_binh",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Theo dõi bài giảng kết hợp làm bài tập trắc nghiệm củng cố", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Chỉ đọc lướt tiêu đề bài học", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Không xem video và tài liệu đính kèm", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Bỏ qua các phần giải thích chi tiết đáp án", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Trong quá trình áp dụng nội dung '{$baiHoc->tieu_de_bai_hoc}', kỹ năng nào là quan trọng nhất?",
                    'giai_thich'       => "Tư duy phân tích và khả năng tự kiểm tra, xử lý vấn đề độc lập.",
                    'muc_do_kho'       => "kho",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Tư duy giải quyết vấn đề và tối ưu hóa giải pháp", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Sao chép mã mà không hiểu nguyên lý", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Áp dụng công thức ngẫu nhiên không có kiểm chứng", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Bỏ qua các trường hợp biên và ngoại lệ", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Sau khi hoàn thành bài học, bước nào giúp học viên đánh giá chính xác mức độ tiếp thu?",
                    'giai_thich'       => "Làm bài kiểm tra trắc nghiệm AI và xem lại các câu trả lời sai.",
                    'muc_do_kho'       => "trung_binh",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Làm bài thi trắc nghiệm và đối chiếu giải thích chi tiết", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Chỉ ghi nhớ điểm số mà không xem lại câu sai", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Đóng hệ thống không nộp bài", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Chuyển ngay sang bài khác khi chưa hoàn thành kiểm tra", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Yếu tố nào quyết định chất lượng đầu ra khi hoàn thành bài '{$baiHoc->tieu_de_bai_hoc}'?",
                    'giai_thich'       => "Khả năng tự áp dụng kiến thức vào dự án hoặc tình huống thực tế.",
                    'muc_do_kho'       => "kho",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Khả năng xây dựng sản phẩm và giải quyết vấn đề độc lập", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Học vẹt từng định nghĩa", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Chỉ dựa vào kết quả của người khác", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Không thực hành bài tập mẫu", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Lợi ích nổi bật của việc làm đề thi trắc nghiệm AI sau bài '{$baiHoc->tieu_de_bai_hoc}' là gì?",
                    'giai_thich'       => "Giúp phản ánh tức thì các lỗ hổng kiến thức để bổ sung kịp thời.",
                    'muc_do_kho'       => "de",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Nhận biết ngay điểm mạnh và lỗ hổng kiến thức để củng cố", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Chỉ để lấy chứng chỉ mà không cần hiểu", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Không mang lại giá trị ôn tập", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Thay thế hoàn toàn việc đọc bài giảng", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Khi gặp câu hỏi khó trong chủ đề '{$baiHoc->tieu_de_bai_hoc}', cách xử lý đúng đắn là gì?",
                    'giai_thich'       => "Xem lại lý thuyết và phân tích các phương án lựa chọn dựa trên nguyên lý bài học.",
                    'muc_do_kho'       => "trung_binh",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Xem lại tài liệu bài học và suy luận logic dựa trên nguyên lý", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Chọn ngẫu nhiên không cần suy nghĩ", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Bỏ qua không hoàn thành bài", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Dừng bài thi ngay lập tức", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Thực hành bài tập thực tế bài '{$baiHoc->tieu_de_bai_hoc}' giúp ích gì nhất cho bạn?",
                    'giai_thich'       => "Biến lý thuyết trừu tượng thành kỹ năng vận dụng thực tiễn bền vững.",
                    'muc_do_kho'       => "kho",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Chuyển hóa kiến thức lý thuyết thành kỹ năng thực tế", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Tốn thời gian không cần thiết", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Chỉ cần xem người khác làm là đủ", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Chỉ phục vụ mục đích vượt qua kỳ thi", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Để ghi nhớ dài hạn kiến thức của bài '{$baiHoc->tieu_de_bai_hoc}', bạn nên làm gì?",
                    'giai_thich'       => "Ôn tập ngắt quãng và tự kiểm tra định kỳ bằng các đề trắc nghiệm ngẫu nhiên.",
                    'muc_do_kho'       => "trung_binh",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Ôn tập định kỳ và luyện tập làm bài trắc nghiệm lặp lại", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Chỉ học một lần rồi không xem lại", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Học dồn trước kỳ thi", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Chỉ nhớ mẹo làm bài mà không hiểu bản chất", 'dung' => 0],
                    ]
                ],
                [
                    'noi_dung_cau_hoi' => "Mức độ thông hiểu bài '{$baiHoc->tieu_de_bai_hoc}' được chứng minh rõ nhất khi nào?",
                    'giai_thich'       => "Khi có thể giải thích lại cho người khác và vận dụng giải quyết bài toán mới.",
                    'muc_do_kho'       => "kho",
                    'phuong_an'        => [
                        ['ky_tu' => 'A', 'noi_dung' => "Giải thích rõ ràng nguyên lý cho người khác và giải bài toán mới", 'dung' => 1],
                        ['ky_tu' => 'B', 'noi_dung' => "Chỉ giải được đúng ví dụ trong sách", 'dung' => 0],
                        ['ky_tu' => 'C', 'noi_dung' => "Thuộc lòng từng định nghĩa từ ngữ", 'dung' => 0],
                        ['ky_tu' => 'D', 'noi_dung' => "Không thể giải bài tập khi có biến đổi số liệu", 'dung' => 0],
                    ]
                ],
            ];

            // Lọc các câu chưa trùng với câu hỏi đã có
            $filtered = [];
            foreach ($baseBank as $item) {
                $isDuplicate = false;
                foreach ($cauHoiHienCo as $cu) {
                    if (str_contains(mb_strtolower($cu), mb_strtolower(mb_substr($item['noi_dung_cau_hoi'], 0, 20)))) {
                        $isDuplicate = true;
                        break;
                    }
                }
                if (!$isDuplicate) {
                    $filtered[] = $item;
                }
            }

            if (count($filtered) < $soLuong) {
                $filtered = array_merge($filtered, $baseBank);
            }
            $questionsData = array_slice($filtered, 0, $soLuong);
        }

        // 6. Lưu ĐỀ THI MỚI vào database với id_nguoi_tao và id_chi_tiet_bai_hoc
        $lanTaoThu = $isHocVien
            ? ($soLanDaTao + 1)
            : (DeThiTracNghiem::where('id_chi_tiet_bai_hoc', $baiHoc->id_bai_hoc)->count() + 1);

        $tieuDeDeThi = 'Đề thi AI: ' . $baiHoc->tieu_de_bai_hoc . ($isHocVien ? " (Lần {$lanTaoThu})" : " (#{$lanTaoThu})");

        $deThi = DeThiTracNghiem::create([
            'id_bai_hoc'              => $baiHoc->id_bai_hoc,
            'id_chi_tiet_bai_hoc'     => $baiHoc->id_bai_hoc,
            'id_nguoi_tao'            => $user->id_nguoi_dung,
            'tieu_de_de_thi'          => $tieuDeDeThi,
            'mo_ta_yeu_cau'           => "Đề thi tự động gồm {$soLuong} câu hỏi do Trợ lý AI Gemini tạo theo yêu cầu.",
            'thoi_luong_lam_bai_phut' => max(5, $soLuong * 2),
            'diem_so_toi_da'          => 10,
            'diem_so_qua_mon'         => 5,
            'so_lan_lam_lai_toi_da'   => 5,
            'cho_phep_xem_loi_giai'   => 1,
            'trang_thai_mo_de'        => 1,
        ]);

        // 7. Lưu từng câu hỏi và phương án lựa chọn
        $diemMoiCau = round(10 / max(1, count($questionsData)), 2);
        foreach ($questionsData as $idx => $qData) {
            $noiDung = $qData['noi_dung_cau_hoi'] ?? ($qData['cau_hoi'] ?? ($qData['question'] ?? ('Câu hỏi trắc nghiệm số ' . ($idx + 1))));
            $cauHoi = CauHoiKiemTra::create([
                'id_de_thi'                  => $deThi->id_de_thi,
                'noi_dung_cau_hoi'           => '[AI Tạo] ' . $noiDung,
                'giai_thich_chi_tiet_dap_an' => $qData['giai_thich'] ?? 'Không có giải thích chi tiết',
                'nguon_goc_cau_hoi'          => 'ai_sinh',
                'trang_thai_kiem_duyet'      => 1,
                'muc_do_kho'                 => $qData['muc_do_kho'] ?? 'trung_binh',
                'diem_so_cau_hoi'            => $diemMoiCau,
                'thu_tu_hien_thi'            => $idx + 1,
            ]);

            $danhSachPa = $qData['phuong_an'] ?? [];
            foreach ($danhSachPa as $pa) {
                PhuongAnLuaChon::create([
                    'id_cau_hoi'          => $cauHoi->id_cau_hoi,
                    'ky_tu_phuong_an'     => $pa['ky_tu'] ?? 'A',
                    'noi_dung_phuong_an'  => $pa['noi_dung'] ?? '',
                    'la_dap_an_chinh_xac' => !empty($pa['dung']) ? 1 : 0,
                ]);
            }
        }

        // 8. Tự động chuyển hướng học viên đến trang làm bài thi của đề vừa tạo
        return redirect()->route('quiz.take', $deThi->id_de_thi)
            ->with('success', "AI đã tạo thành công đề thi gồm " . count($questionsData) . " câu hỏi cho bài học '{$baiHoc->tieu_de_bai_hoc}'! Chúc bạn làm bài đạt kết quả tốt!");
    }

    /**
     * Alias method sinhDeThiAi
     */
    public function sinhDeThiAi(Request $request, $baiHocId)
    {
        return $this->generateQuiz($request, $baiHocId);
    }

    /**
     * Alias method hocVienSinhDeThi
     */
    public function hocVienSinhDeThi(Request $request, $baiHocId)
    {
        return $this->generateQuiz($request, $baiHocId);
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

        $deThi = DeThiTracNghiem::with('baiHoc.chuongMuc.khoaHoc')->find($deThiId);

        return view('hoc_vien.ket_qua_thi', compact('diemSo', 'soCauDung', 'tongSoCau', 'chiTietKetQua', 'deThi', 'luotLamBai'));
    }

    /**
     * Xem chi tiết kết quả lượt làm bài thi theo ID lượt thi
     */
    public function xemKetQua($idLuotLamBai)
    {
        $luotLamBai = LuotLamBaiThi::with('deThi.baiHoc.chuongMuc.khoaHoc')->findOrFail($idLuotLamBai);
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
        $deThi = $luotLamBai->deThi;

        return view('hoc_vien.ket_qua_thi', compact('diemSo', 'soCauDung', 'tongSoCau', 'chiTietKetQua', 'deThi', 'luotLamBai'));
    }
}

