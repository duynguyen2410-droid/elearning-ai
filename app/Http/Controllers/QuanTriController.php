<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhoaHoc;
use App\Models\DeThiTracNghiem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class QuanTriController extends Controller
{
    /**
     * Bảng điều khiển quản trị viên
     */
    public function dashboard()
    {
        $user = Auth::user();
        $tongKhoaHoc = KhoaHoc::count();
        $tongHocVien = User::where('id_phan_quyen', '!=', 1)->count();
        $tongDeThi = DeThiTracNghiem::count();

        $danhSachDeThiGanDay = DeThiTracNghiem::with(['baiHoc', 'danhSachCauHoi'])
            ->orderByDesc('id_de_thi')
            ->take(5)
            ->get();
        $danhSachHocVienMoi = User::where('id_phan_quyen', '!=', 1)
            ->orderByDesc('id_nguoi_dung')
            ->take(5)
            ->get();
        $danhSachKhoaHoc = KhoaHoc::with('danhMuc')
            ->orderByDesc('id_khoa_hoc')
            ->take(5)
            ->get();

        return view('quan_tri.tong_quan', compact(
            'user', 
            'tongKhoaHoc', 
            'tongHocVien', 
            'tongDeThi',
            'danhSachDeThiGanDay',
            'danhSachHocVienMoi',
            'danhSachKhoaHoc'
        ));
    }

    /**
     * Alias tiếng Việt cho dashboard
     */
    public function tongQuan()
    {
        return $this->dashboard();
    }
}

