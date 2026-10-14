<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KhoaHoc;
use App\Models\ChuongMucBaiHoc;
use App\Models\ChiTietBaiHoc;

class BaiHocSeeder extends Seeder
{
    public function run()
    {
        $khoaHoc = KhoaHoc::first();
        if (!$khoaHoc) return;

        // 1. Tạo Chương 1
        $chuong1 = ChuongMucBaiHoc::firstOrCreate(
            [
                'id_khoa_hoc'        => $khoaHoc->id_khoa_hoc,
                'tieu_de_chuong_muc' => 'Chương 1: Khởi động dự án và kiến trúc CSDL',
            ],
            [
                'mo_ta_chuong_muc'   => 'Tổng quan hệ thống E-Learning và chuẩn hóa Eloquent ORM.',
                'thu_tu_sap_xep'     => 1,
            ]
        );

        // 2. Tạo 2 bài học thuộc Chương 1
        ChiTietBaiHoc::firstOrCreate(
            [
                'id_chuong_muc'   => $chuong1->id_chuong_muc,
                'tieu_de_bai_hoc' => 'Bài 1: Giới thiệu cấu trúc cơ sở dữ liệu Elearning AI',
            ],
            [
                'loai_bai_hoc'              => 'video',
                'duong_dan_video_bai_giang' => 'https://example.com/videos/bai1.mp4',
                'thoi_luong_video_giay'     => 600,
                'noi_dung_bai_hoc_chi_tiet' => 'Bài học phân tích chi tiết 14 bảng trong hệ thống Elearning AI.',
                'tom_tat_kien_thuc_cho_ai'  => 'Kiến thức cốt lõi: Thiết kế 14 bảng quan hệ, tối ưu khóa ngoại và định dạng trường cho mô hình AI.',
                'cho_phep_hoc_thu'          => 1,
                'thu_tu_sap_xep'            => 1,
            ]
        );

        ChiTietBaiHoc::firstOrCreate(
            [
                'id_chuong_muc'   => $chuong1->id_chuong_muc,
                'tieu_de_bai_hoc' => 'Bài 2: Hướng dẫn kết nối Laravel với Model tự định nghĩa',
            ],
            [
                'loai_bai_hoc'              => 'ly_thuyet',
                'duong_dan_video_bai_giang' => null,
                'thoi_luong_video_giay'     => 0,
                'noi_dung_bai_hoc_chi_tiet' => 'Cách tùy biến Eloquent Model khi bảng không theo chuẩn mặc định của Laravel.',
                'tom_tat_kien_thuc_cho_ai'  => 'Tùy biến $table, $primaryKey, $timestamps = false và cấu hình quan hệ belongsTo, hasMany.',
                'cho_phep_hoc_thu'          => 0,
                'thu_tu_sap_xep'            => 2,
            ]
        );
    }
}