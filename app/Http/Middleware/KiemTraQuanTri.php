<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class KiemTraQuanTri
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
        // 1. Kiểm tra: Người dùng phải đăng nhập và có quyền Quản trị viên (id_phan_quyen = 1)
        if (Auth::check() && (int)Auth::user()->id_phan_quyen === 1) {
            return $next($request);
        }

        // 2. Nếu là request Ajax/API JSON thì phản hồi 403 Forbidden
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập vào trang quản trị!',
            ], 403);
        }

        // 3. Nếu không phải Admin: Chuyển hướng người dùng về trang tổng quan học viên kèm thông báo lỗi
        $targetRoute = Route::has('hoc_vien.tong_quan')
            ? route('hoc_vien.tong_quan')
            : (Route::has('student.dashboard') ? route('student.dashboard') : '/hoc-vien/tong-quan');

        return redirect($targetRoute)
            ->with('error', 'Bạn không có quyền truy cập vào trang quản trị!');
    }
}

