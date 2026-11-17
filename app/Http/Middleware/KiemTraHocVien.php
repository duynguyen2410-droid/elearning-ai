<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChiTietBaiHoc;

class KiemTraHocVien
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
        // 1. Kiểm tra: Người dùng phải đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục!');
        }

        $user = Auth::user();

        // 2. Nếu là Học viên (id_phan_quyen = 3): Luôn cho phép truy cập khu vực học tập
        if ((int)$user->id_phan_quyen === 3) {
            return $next($request);
        }

        // 3. Nếu là Giảng viên (id_phan_quyen = 2):
        if ((int)$user->id_phan_quyen === 2) {
            // 3a. Cho phép thi thử, nộp bài thi, xem kết quả làm bài thi để kiểm tra chất lượng đề thi
            $isQuizAction = $request->routeIs(
                'quiz.take',
                'dethi.show',
                'hoc_vien.lam_bai_thi',
                'dethi.nopbai',
                'hoc_vien.nop_bai',
                'dethi.ketqua',
                'hoc_vien.ket_qua_thi'
            )
            || $request->is('hoc-vien/thi/*')
            || $request->is('hoc-vien/de-thi/*')
            || $request->is('hoc-vien/lam-bai-thi/*')
            || $request->is('hoc-vien/nop-bai*')
            || $request->is('hoc-vien/ket-qua*')
            || $request->is('quiz/*');

            if ($isQuizAction) {
                return $next($request);
            }

            // 3b. Cho phép xem chi tiết bài học / xem trước video nếu thuộc khóa học mà giảng viên sở hữu
            $isViewingLesson = $request->routeIs('student.lesson.show')
                || $request->is('hoc-vien/bai-hoc/*')
                || $request->is('student/bai-hoc/*')
                || $request->is('giang-vien/bai-hoc/*');

            if ($isViewingLesson) {
                $lessonId = $request->route('id') ?? $request->segment(3);
                if ($lessonId) {
                    $baiHoc = ChiTietBaiHoc::with('chuongMuc.khoaHoc')->find($lessonId);
                    $khoaHoc = $baiHoc && $baiHoc->chuongMuc ? $baiHoc->chuongMuc->khoaHoc : null;

                    // Giảng viên là người phụ trách khóa học (hoặc người tạo)
                    if ($khoaHoc && (
                        (int)$khoaHoc->id_giang_vien_phu_trach === (int)$user->id_nguoi_dung ||
                        (int)($khoaHoc->id_nguoi_tao ?? 0) === (int)$user->id_nguoi_dung
                    )) {
                        return $next($request);
                    }

                    return redirect()->route('giang_vien.tong_quan')
                        ->with('error', 'Bạn chỉ có quyền xem trước các bài học thuộc khóa học do mình phụ trách!');
                }
            }

            // Chặn Giảng viên đối với các chức năng riêng như Dashboard cá nhân của học viên
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Khu vực bảng điều khiển này chỉ dành riêng cho Học viên!',
                ], 403);
            }

            return redirect()->route('giang_vien.tong_quan')
                ->with('error', 'Bảng điều khiển cá nhân chỉ dành riêng cho Học viên!');
        }

        // 4. Nếu là Quản trị viên (id_phan_quyen = 1):
        $isQuizAction = $request->routeIs(
            'quiz.take',
            'dethi.show',
            'hoc_vien.lam_bai_thi',
            'dethi.nopbai',
            'hoc_vien.nop_bai',
            'dethi.ketqua',
            'hoc_vien.ket_qua_thi'
        )
        || $request->is('hoc-vien/thi/*')
        || $request->is('hoc-vien/de-thi/*')
        || $request->is('hoc-vien/lam-bai-thi/*')
        || $request->is('hoc-vien/nop-bai*')
        || $request->is('hoc-vien/ket-qua*')
        || $request->is('quiz/*');

        $isViewingLesson = $request->routeIs('student.lesson.show')
            || $request->is('hoc-vien/bai-hoc/*')
            || $request->is('student/bai-hoc/*');

        if ((int)$user->id_phan_quyen === 1 && ($isViewingLesson || $isQuizAction)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập khu vực học tập của Học viên!',
            ], 403);
        }

        return redirect()->route('quan_tri.tong_quan')
            ->with('error', 'Khu vực học tập chỉ dành riêng cho Học viên!');
    }
}
