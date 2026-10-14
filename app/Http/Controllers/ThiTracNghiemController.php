<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeThiTracNghiem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ThiTracNghiemController extends Controller
{
    /**
     * Hiển thị giao diện làm bài thi trắc nghiệm cho học viên
     */
    public function take($id)
    {
        $user = Auth::user() ?? User::first();

        // Eager load câu hỏi và các phương án lựa chọn
        $deThi = DeThiTracNghiem::with([
            'danhSachCauHoi.danhSachPhuongAn',
            'baiHoc.chuongMuc.khoaHoc',
        ])->findOrFail($id);

        return view('hoc_vien.lam_bai_thi', compact('user', 'deThi'));
    }
}

