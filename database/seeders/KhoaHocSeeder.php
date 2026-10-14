<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DanhMucKhoaHoc;
use App\Models\KhoaHoc;
use App\Models\User;

class KhoaHocSeeder extends Seeder
{
    public function run()
    {
        // 1. Tạo danh mục mẫu
        $danhMuc = DanhMucKhoaHoc::firstOrCreate(
            ['ten_danh_muc' => 'Lập trình Web & Trí tuệ nhân tạo'],
            [
                'mo_ta_danh_muc' => 'Các khóa học về lập trình ứng dụng kết hợp công nghệ AI.',
            ]
        );

        // 2. Lấy tài khoản giảng viên
        $giangVien = User::where('dia_chi_email', 'giangvien@gmail.com')->first();

        // 3. Tạo khóa học mẫu khớp đúng tên cột
        KhoaHoc::updateOrCreate(
            ['tieu_de_khoa_hoc' => 'Khóa học Lập trình Laravel kết hợp Trợ lý ảo AI'],
            [
                'id_giang_vien_phu_trach'     => $giangVien ? $giangVien->id_nguoi_dung : 1,
                'id_danh_muc'                 => $danhMuc->id_danh_muc,
                'duong_dan_than_thien_slug'   => 'lap-trinh-laravel-ket-hop-tro-ly-ao-ai',
                'mo_ta_ngan_gon'              => 'Làm chủ Laravel và tích hợp các mô hình AI thông minh.',
                'noi_dung_gioi_thieu_chi_tiet'=> 'Nội dung chi tiết từ quản lý bài giảng đến tích hợp API Chatbot AI và thi trắc nghiệm.',
                'cap_do_khoa_hoc'             => 'Co_ban',
                'trang_thai_xuat_ban'         => 1,
                'tong_thoi_luong_phut'        => 120,
                'thoi_diem_tao'               => now(),
                'thoi_diem_cap_nhat'          => now(),
            ]
        );
    }
}