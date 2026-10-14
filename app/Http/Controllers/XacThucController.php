<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class XacThucController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Đăng nhập
    |--------------------------------------------------------------------------
    */

    /**
     * Hiển thị form đăng nhập.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && ((int)$user->id_phan_quyen === 1 || in_array($user->phan_quyen ?? '', ['admin', 'quan_tri']))) {
                return redirect()->route('quan_tri.tong_quan');
            }
            return redirect()->route('hoc_vien.tong_quan');
        }

        return view('xac_thuc.dang_nhap');
    }

    /**
     * Xử lý đăng nhập.
     */
    public function login(Request $request)
    {
        $inputEmail = $request->input('email', $request->input('dia_chi_email'));
        $inputPassword = $request->input('password', $request->input('mat_khau'));

        $request->merge([
            'email' => $inputEmail,
            'password' => $inputPassword,
        ]);

        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Vui lòng nhập địa chỉ email.',
            'email.email'       => 'Địa chỉ email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $credentials = [
            'dia_chi_email' => $inputEmail,
            'password'      => $inputPassword,
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Kiểm tra trạng thái tài khoản (nếu bị khóa thì chặn)
            $user = Auth::user();
            if ($user && in_array($user->trang_thai_tai_khoan, ['khoa', 'tam_khoa', '0', 0])) {
                Auth::logout();
                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->with('error', 'Tài khoản của bạn đã bị tạm khóa.');
            }

            // Chuyển hướng theo phân quyền: Quản trị viên -> /quan-tri/tong-quan, Học viên -> /hoc-vien/tong-quan
            if ($user && ((int)$user->id_phan_quyen === 1 || in_array($user->phan_quyen ?? '', ['admin', 'quan_tri']))) {
                return redirect()->intended(route('quan_tri.tong_quan'));
            }

            return redirect()->intended(route('hoc_vien.tong_quan'));
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    /**
     * Đăng xuất.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Bạn đã đăng xuất thành công.');
    }

    /*
    |--------------------------------------------------------------------------
    | Đăng ký
    |--------------------------------------------------------------------------
    */

    /**
     * Hiển thị form đăng ký tài khoản học viên.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('hoc_vien.tong_quan');
        }

        return view('xac_thuc.dang_ky');
    }

    /**
     * Xử lý đăng ký tài khoản mới.
     */
    public function register(Request $request)
    {
        $request->validate([
            'ho_ten'   => 'required|string|max:150',
            'email'    => 'required|string|email|max:150|unique:tai_khoan_nguoi_dung,dia_chi_email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'ho_ten.required'     => 'Vui lòng nhập họ và tên.',
            'ho_ten.string'       => 'Họ và tên không hợp lệ.',
            'ho_ten.max'          => 'Họ và tên không được vượt quá 150 ký tự.',
            'email.required'      => 'Vui lòng nhập địa chỉ email.',
            'email.email'         => 'Địa chỉ email không hợp lệ.',
            'email.max'           => 'Email không được vượt quá 150 ký tự.',
            'email.unique'        => 'Email này đã được sử dụng. Vui lòng chọn email khác.',
            'password.required'   => 'Vui lòng nhập mật khẩu.',
            'password.min'        => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed'  => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'ho_va_ten'            => $request->ho_ten,
            'dia_chi_email'        => $request->email,
            'mat_khau_ma_hoa'      => Hash::make($request->password),
            'id_phan_quyen'        => 2, // 2 is typically the student role ID
            'trang_thai_tai_khoan' => 'kich_hoat',
        ]);

        Auth::login($user);

        return redirect()->route('hoc_vien.tong_quan')
            ->with('success', 'Đăng ký tài khoản thành công! Chào mừng bạn đến với EduMind AI.');
    }
}

