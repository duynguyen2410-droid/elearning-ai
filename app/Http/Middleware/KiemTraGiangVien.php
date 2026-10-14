<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class KiemTraGiangVien
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Kiểm tra: Người dùng phải đăng nhập và là Giảng viên (id_phan_quyen = 2)
        if (Auth::check() && (int)Auth::user()->id_phan_quyen === 2) {
            return $next($request);
        }

        // 2. Nếu là request Ajax/API JSON thì phản hồi 403 Forbidden
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập khu vực Giảng viên!',
            ], 403);
        }

        // 3. Nếu không phải Giảng viên: Chuyển hướng theo đúng vai trò thực tế
        if (Auth::check()) {
            if ((int)Auth::user()->id_phan_quyen === 1) {
                return redirect()->route('quan_tri.tong_quan')
                    ->with('error', 'Khu vực này chỉ dành riêng cho Giảng viên!');
            }
            return redirect()->route('hoc_vien.tong_quan')
                ->with('error', 'Bạn không có quyền truy cập khu vực Giảng viên!');
        }

        return redirect()->route('login')
            ->with('error', 'Vui lòng đăng nhập với tài khoản Giảng viên!');
    }
}
