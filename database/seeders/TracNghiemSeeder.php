<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChiTietBaiHoc;
use App\Models\DeThiTracNghiem;
use App\Models\CauHoiKiemTra;
use App\Models\PhuongAnLuaChon;

class TracNghiemSeeder extends Seeder
{
    public function run()
    {
        // 1. Lấy bài học đầu tiên để gán đề thi trắc nghiệm
        $baiHoc = ChiTietBaiHoc::first();
        if (!$baiHoc) return;

        // 2. Tạo đề thi trắc nghiệm kiểm tra kiến thức bài học
        $deThi = DeThiTracNghiem::firstOrCreate(
            [
                'id_bai_hoc'     => $baiHoc->id_bai_hoc,
                'tieu_de_de_thi' => 'Bài kiểm tra nhanh kiến thức: Cấu trúc CSDL E-Learning',
            ],
            [
                'mo_ta_yeu_cau'           => 'Hoàn thành 2 câu hỏi để củng cố kiến thức về thiết kế CSDL.',
                'thoi_luong_lam_bai_phut' => 10,
                'diem_so_toi_da'          => 10,
                'diem_so_qua_mon'         => 5,
                'so_lan_lam_lai_toi_da'   => 3,
                'cho_phep_xem_loi_giai'   => 1,
                'trang_thai_mo_de'        => 1,
            ]
        );

        // 3. Câu hỏi số 1: Giảng viên tự soạn
        $cauHoi1 = CauHoiKiemTra::firstOrCreate(
            [
                'id_de_thi'        => $deThi->id_de_thi,
                'noi_dung_cau_hoi' => 'Trong Laravel Eloquent, nếu bảng không có cột created_at và updated_at thì cần cấu hình thuộc tính nào?',
            ],
            [
                'giai_thich_chi_tiet_dap_an' => 'Laravel mặc định bật timestamps, cần gán public $timestamps = false; để tắt.',
                'nguon_goc_cau_hoi'          => 'giang_vien_soan',
                'trang_thai_kiem_duyet'      => 1,
                'muc_do_kho'                 => 'De',
                'diem_so_cau_hoi'            => 5,
                'thu_tu_hien_thi'            => 1,
            ]
        );

        // Các phương án cho Câu 1
        $phuongAnC1 = [
            ['ky_tu' => 'A', 'noi_dung' => 'public $timestamps = false;', 'dung' => 1],
            ['ky_tu' => 'B', 'noi_dung' => 'protected $timestamps = null;', 'dung' => 0],
            ['ky_tu' => 'C', 'noi_dung' => 'public $no_timestamps = true;', 'dung' => 0],
            ['ky_tu' => 'D', 'noi_dung' => 'protected $disable_time = true;', 'dung' => 0],
        ];

        foreach ($phuongAnC1 as $pa) {
            PhuongAnLuaChon::firstOrCreate(
                [
                    'id_cau_hoi'      => $cauHoi1->id_cau_hoi,
                    'ky_tu_phuong_an' => $pa['ky_tu'],
                ],
                [
                    'noi_dung_phuong_an'  => $pa['noi_dung'],
                    'la_dap_an_chinh_xac' => $pa['dung'],
                ]
            );
        }

        // 4. Câu hỏi số 2: Mô phỏng AI sinh tự động dựa trên tóm tắt bài giảng
        $cauHoi2 = CauHoiKiemTra::firstOrCreate(
            [
                'id_de_thi'        => $deThi->id_de_thi,
                'noi_dung_cau_hoi' => '[AI Tạo] Khóa chính tùy biến trong Eloquent Model được định nghĩa bằng thuộc tính nào?',
            ],
            [
                'giai_thich_chi_tiet_dap_an' => 'Eloquent mặc định khóa chính là id, nếu dùng tên khác cần khai báo qua $primaryKey.',
                'nguon_goc_cau_hoi'          => 'ai_sinh',
                'trang_thai_kiem_duyet'      => 1,
                'muc_do_kho'                 => 'Trung_binh',
                'diem_so_cau_hoi'            => 5,
                'thu_tu_hien_thi'            => 2,
            ]
        );

        // Các phương án cho Câu 2
        $phuongAnC2 = [
            ['ky_tu' => 'A', 'noi_dung' => 'protected $id = "custom_id";', 'dung' => 0],
            ['ky_tu' => 'B', 'noi_dung' => 'protected $primaryKey = "id_danh_muc";', 'dung' => 1],
            ['ky_tu' => 'C', 'noi_dung' => 'public $mainKey = "id_khoa";', 'dung' => 0],
            ['ky_tu' => 'D', 'noi_dung' => 'protected $key = "id";', 'dung' => 0],
        ];

        foreach ($phuongAnC2 as $pa) {
            PhuongAnLuaChon::firstOrCreate(
                [
                    'id_cau_hoi'      => $cauHoi2->id_cau_hoi,
                    'ky_tu_phuong_an' => $pa['ky_tu'],
                ],
                [
                    'noi_dung_phuong_an'  => $pa['noi_dung'],
                    'la_dap_an_chinh_xac' => $pa['dung'],
                ]
            );
        }
    }
}