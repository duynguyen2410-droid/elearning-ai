<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XacThucController;
use App\Http\Controllers\AiTaoDeThiController;
use App\Http\Controllers\HocVienController;
use App\Http\Controllers\ThiTracNghiemController;
use App\Http\Controllers\TroLyAiController;
use App\Http\Controllers\QuanTriController;
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
        if ($user && ((int)$user->id_phan_quyen === 1 || in_array($user->phan_quyen ?? '', ['admin', 'quan_tri']))) {
            return redirect()->route('quan_tri.tong_quan');
        }
        return redirect()->route('hoc_vien.tong_quan');
    })->name('dashboard');

    // Chuyển hướng tương thích các URL trung tâm cũ
    Route::redirect('/dashboard', '/tong-quan');
    Route::redirect('/home', '/tong-quan');

    // ==========================================
    // 3. Nhóm Quản trị hệ thống (/quan-tri/...)
    // Bảo vệ chặt chẽ: Bắt buộc đăng nhập & id_phan_quyen = 1
    // ==========================================
    Route::middleware(['auth', 'quan_tri'])->prefix('quan-tri')->name('quan_tri.')->group(function () {
        Route::get('/tong-quan', [QuanTriController::class, 'dashboard'])->name('tong_quan');
        // Các route quản trị mở rộng khác có thể khai báo tại đây...
    });

    // Chuyển hướng tương thích cho URL quản trị cũ
    Route::redirect('/admin/dashboard', '/quan-tri/tong-quan')->name('admin.dashboard');
    Route::redirect('/admin', '/quan-tri/tong-quan');

    // ==========================================
    // 4. Nhóm Học viên (/hoc-vien/...)
    // ==========================================
    Route::prefix('hoc-vien')->group(function () {
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