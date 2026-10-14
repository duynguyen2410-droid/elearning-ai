<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PhanQuyen;
use Illuminate\Support\Facades\Hash;

class NguoiDungSeeder extends Seeder
{
    public function run()
    {
        $quyenAdmin = PhanQuyen::where('ma_dinh_danh_quyen', 'admin')->first();
        $quyenGiangVien = PhanQuyen::where('ma_dinh_danh_quyen', 'giang_vien')->first();

        // 1. Tạo tài khoản Admin
        User::updateOrCreate(
            ['dia_chi_email' => 'admin@gmail.com'],
            [
                'id_phan_quyen'        => $quyenAdmin ? $quyenAdmin->id_phan_quyen : 1,
                'ho_va_ten'            => 'Quản Trị Viên',
                'mat_khau_ma_hoa'      => Hash::make('12345678'),
                'trang_thai_tai_khoan' => 1,
            ]
        );

        // 2. Tạo tài khoản Giảng viên mẫu
        User::updateOrCreate(
            ['dia_chi_email' => 'giangvien@gmail.com'],
            [
                'id_phan_quyen'        => $quyenGiangVien ? $quyenGiangVien->id_phan_quyen : 2,
                'ho_va_ten'            => 'Thầy Nguyễn Văn A',
                'mat_khau_ma_hoa'      => Hash::make('12345678'),
                'trang_thai_tai_khoan' => 1,
            ]
        );
    }
}