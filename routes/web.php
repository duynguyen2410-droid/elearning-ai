<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XacThucController;
use App\Http\Controllers\AiTaoDeThiController;
use App\Http\Controllers\HocVienController;
use App\Http\Controllers\ThiTracNghiemController;
use App\Http\Controllers\TroLyAiController;
use App\Http\Controllers\QuanTriController;
use App\Http\Controllers\GiangVienController;
use Illuminate\Support\Facades\Auth;
use App\Models\KhoaHoc;
use App\Models\DeThiTracNghiem;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| 1. Public Routes (Khách vãng lai, không cần đăng nhập)
|--------------------------------------------------------------------------
*/

// Trang chủ công khai
Route::get('/', function () {
    $danhSachKhoaHoc = KhoaHoc::with(['giangVien', 'danhMuc'])->take(6)->get();
    return view('trang_chu', compact('danhSachKhoaHoc'));
})->name('home');

// ==========================================
// Nhóm Xác thực (Authentication - URL tiếng Việt kebab-case)
// ==========================================
Route::get('/dang-nhap', [XacThucController::class, 'showLoginForm'])->name('login');
Route::post('/dang-nhap', [XacThucController::class, 'login'])->name('login.post');
Route::post('/dang-xuat', [XacThucController::class, 'logout'])->name('logout');

Route::get('/dang-ky', [XacThucController::class, 'showRegisterForm'])->name('register');
Route::post('/dang-ky', [XacThucController::class, 'register'])->name('register.post');

// Chuyển hướng tương thích cho URL xác thực cũ
Route::redirect('/login', '/dang-nhap');
Route::redirect('/register', '/dang-ky');
Route::redirect('/logout', '/dang-nhap');


/*
|--------------------------------------------------------------------------
| 2. Authenticated Routes (Bắt buộc phải đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Route điều hướng trung tâm sau khi đăng nhập
    Route::get('/tong-quan', function () {
        $user = Auth::user();
        if ($user) {
            if ((int)$user->id_phan_quyen === 1) {
                return redirect()->route('quan_tri.tong_quan');
            } elseif ((int)$user->id_phan_quyen === 2) {
                return redirect()->route('giang_vien.tong_quan');
            }
            return redirect()->route('hoc_vien.tong_quan');
        }
        return redirect()->route('login');
    })->name('dashboard');

    // Chuyển hướng tương thích các URL trung tâm cũ
    Route::redirect('/dashboard', '/tong-quan');
    Route::redirect('/home', '/tong-quan');

    // Đổi mật khẩu cá nhân (Dành cho cả 3 vai trò: Admin, Giảng viên, Học viên)
    Route::post('/doi-mat-khau', [XacThucController::class, 'doiMatKhauCaNhan'])->name('tai_khoan.doi_mat_khau');

    // ==========================================
    // 3. Nhóm Quản trị hệ thống (/quan-tri/...)
    // Bảo vệ chặt chẽ: Bắt buộc đăng nhập & id_phan_quyen = 1
    // ==========================================
    Route::middleware(['auth', 'quan_tri'])->prefix('quan-tri')->name('quan_tri.')->group(function () {
        // Tổng quan
        Route::get('/tong-quan', [QuanTriController::class, 'tongQuan'])->name('tong_quan');

        // Quản lý tài khoản
        Route::get('/tai-khoan', [QuanTriController::class, 'quanLyTaiKhoan'])->name('tai_khoan');
        Route::post('/tai-khoan/them', [QuanTriController::class, 'themTaiKhoan'])->name('tai_khoan.them');
        Route::post('/tai-khoan/cap-nhat-quyen/{id}', [QuanTriController::class, 'capNhatQuyen'])->name('tai_khoan.cap_nhat_quyen');
        Route::post('/tai-khoan/dat-lai-mat-khau/{id}', [QuanTriController::class, 'datLaiMatKhau'])->name('tai_khoan.dat_lai_mat_khau');
        Route::delete('/tai-khoan/xoa/{id}', [QuanTriController::class, 'xoaTaiKhoan'])->name('tai_khoan.xoa');

        // Quản lý & Kiểm duyệt Khóa học, Danh mục
        Route::get('/khoa-hoc', [QuanTriController::class, 'quanLyKhoaHoc'])->name('khoa_hoc');
        Route::post('/danh-muc/them', [QuanTriController::class, 'themDanhMuc'])->name('danh_muc.them');
        Route::delete('/danh-muc/xoa/{id}', [QuanTriController::class, 'xoaDanhMuc'])->name('danh_muc.xoa');
        Route::post('/khoa-hoc/duyet/{id}', [QuanTriController::class, 'duyetKhoaHoc'])->name('khoa_hoc.duyet');
        Route::delete('/khoa-hoc/xoa/{id}', [QuanTriController::class, 'xoaKhoaHoc'])->name('khoa_hoc.xoa');

        // Giám sát đề thi AI
        Route::get('/de-thi', [QuanTriController::class, 'giamSatDeThi'])->name('de_thi');
        Route::delete('/de-thi/xoa/{id}', [QuanTriController::class, 'xoaDeThi'])->name('de_thi.xoa');
    });

    // Chuyển hướng tương thích cho URL quản trị cũ
    Route::redirect('/admin/dashboard', '/quan-tri/tong-quan')->name('admin.dashboard');
    Route::redirect('/admin', '/quan-tri/tong-quan');

    // ==========================================
    // 4. Nhóm Giảng viên (/giang-vien/...)
    // Bảo vệ: Bắt buộc đăng nhập & id_phan_quyen = 2
    // ==========================================
    Route::middleware(['auth', 'giang_vien'])->prefix('giang-vien')->name('giang_vien.')->group(function () {
        // Tổng quan bàn làm việc
        Route::get('/tong-quan', [GiangVienController::class, 'tongQuan'])->name('tong_quan');

        // Quản lý Khóa học & Giáo trình bài giảng
        Route::post('/khoa-hoc/them', [GiangVienController::class, 'themKhoaHoc'])->name('khoa_hoc.them');
        Route::post('/khoa-hoc/cap-nhat/{id}', [GiangVienController::class, 'capNhatKhoaHoc'])->name('khoa_hoc.cap_nhat');
        Route::post('/khoa-hoc/{id}/them-chuong', [GiangVienController::class, 'themChuong'])->name('chuong.them');
        Route::post('/chuong/{id}/them-bai-hoc', [GiangVienController::class, 'themBaiHoc'])->name('bai_hoc.them');
        Route::delete('/bai-hoc/xoa/{id}', [GiangVienController::class, 'xoaBaiHoc'])->name('bai_hoc.xoa');

        // Chuyên môn Đề thi AI (Tự soạn thủ công & AI sinh thông minh chống trùng)
        Route::post('/de-thi/tao-thu-cong', [GiangVienController::class, 'taoDeThiThuCong'])->name('de_thi.tao_thu_cong');
        Route::post('/de-thi/sinh-ai', [GiangVienController::class, 'sinhDeThiAi'])->name('de_thi.sinh_ai_modal');
        Route::post('/bai-hoc/{id}/sinh-de-thi-ai', [GiangVienController::class, 'sinhDeThiAi'])->name('de_thi.sinh_ai');
        Route::post('/cau-hoi/cap-nhat/{id}', [GiangVienController::class, 'capNhatCauHoi'])->name('cau_hoi.cap_nhat');
        Route::delete('/de-thi/xoa/{id}', [GiangVienController::class, 'xoaDeThi'])->name('de_thi.xoa');

        // Xem trước bài học và video bài giảng
        Route::get('/bai-hoc/{id}', [HocVienController::class, 'showLesson'])->name('bai_hoc.xem');
    });

    // ==========================================
    // 5. Nhóm Học viên (/hoc-vien/...)
    // Bảo vệ chặt chẽ: Bắt buộc đăng nhập & id_phan_quyen = 3
    // ==========================================
    Route::middleware(['auth', 'hoc_vien'])->prefix('hoc-vien')->group(function () {
        // Tổng quan bảng điều khiển học viên
        Route::get('/tong-quan', [HocVienController::class, 'dashboard'])->name('hoc_vien.tong_quan');

        // Chi tiết bài học
        Route::get('/bai-hoc/{id}', [HocVienController::class, 'showLesson'])->name('student.lesson.show');

        // Làm bài thi trắc nghiệm
        Route::get('/thi/{id}', [ThiTracNghiemController::class, 'take'])->name('quiz.take');
        Route::get('/de-thi/{id}', [ThiTracNghiemController::class, 'take'])->name('dethi.show');

        // Nộp bài thi
        Route::post('/nop-bai/{id}', [AiTaoDeThiController::class, 'nopBai'])->name('dethi.nopbai');
        Route::post('/nop-bai', [AiTaoDeThiController::class, 'nopBai']);
        Route::post('/de-thi/{id}/nop-bai', [AiTaoDeThiController::class, 'nopBai']);

        // Xem kết quả làm bài thi
        Route::get('/ket-qua/{id}', [AiTaoDeThiController::class, 'xemKetQua'])->name('dethi.ketqua');

        // AI Tạo câu hỏi trắc nghiệm tự động
        Route::post('/bai-hoc/{id}/ai-tao-cau-hoi', [AiTaoDeThiController::class, 'generateQuiz'])->name('baihoc.ai-tao-cau-hoi');

        // Trợ lý học tập AI Gemini
        Route::post('/tro-ly-ai/hoi-dap', [TroLyAiController::class, 'ask']);
    });

    // Chuyển hướng tương thích cho URL học viên cũ
    Route::redirect('/student/dashboard', '/hoc-vien/tong-quan')->name('student.dashboard');
    Route::get('/student/bai-hoc/{id}', function ($id) {
        return redirect()->route('student.lesson.show', $id);
    });
    Route::get('/quiz/{id}', function ($id) {
        return redirect()->route('quiz.take', $id);
    });
    Route::get('/de-thi/{id}', function ($id) {
        return redirect()->route('quiz.take', $id);
    });
    Route::post('/de-thi/{id}/nop-bai', [AiTaoDeThiController::class, 'nopBai']);
    Route::post('/bai-hoc/{id}/ai-tao-cau-hoi', [AiTaoDeThiController::class, 'generateQuiz']);

    // ==========================================
    // 5. Trợ lý AI (Chatbot)
    // ==========================================
    Route::post('/tro-ly-ai/hoi-dap', [TroLyAiController::class, 'ask'])->name('chatbot.ask');
    Route::post('/chatbot/ask', [TroLyAiController::class, 'ask']);
});