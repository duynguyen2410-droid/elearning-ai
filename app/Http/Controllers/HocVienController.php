<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhoaHoc;
use App\Models\ChiTietBaiHoc;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HocVienController extends Controller
{
    /**
     * Bảng điều khiển học viên
     */
    public function dashboard()
    {
        $user = Auth::user() ?? User::first();

        // Eager load toàn bộ quan hệ chương mục, bài học và đề thi trắc nghiệm
        $danhSachKhoaHoc = KhoaHoc::with([
            'giangVien',
            'danhMuc',
            'danhSachChuongMuc.danhSachBaiHoc.deThi',
            'danhSachChuongMuc.danhSachBaiHoc.deThiTracNghiem',
            'danhSachChuongMuc.danhSachBaiHoc.danhSachDeThi',
        ])->get();

        return view('hoc_vien.tong_quan', compact('user', 'danhSachKhoaHoc'));
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

        if ($khoaHoc) {
            $khoaHoc->load([
                'danhSachChuongMuc.danhSachBaiHoc.deThi',
                'giangVien',
                'danhMuc',
            ]);
        }

        $danhSachBaiHoc = $chuongMuc ? $chuongMuc->danhSachBaiHoc : collect();

        return view('hoc_vien.chi_tiet_bai_hoc', compact('baiHoc', 'chuongMuc', 'khoaHoc', 'danhSachBaiHoc'));
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
}

