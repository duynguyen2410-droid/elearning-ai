<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Khớp đúng bảng 'tai_khoan_nguoi_dung' trong phpMyAdmin
    protected $table = 'tai_khoan_nguoi_dung';
    protected $primaryKey = 'id_nguoi_dung';

    const CREATED_AT = 'thoi_diem_tao';
    const UPDATED_AT = 'thoi_diem_cap_nhat';

    protected $fillable = [
        'id_phan_quyen',
        'ho_va_ten',
        'dia_chi_email',
        'mat_khau_ma_hoa',
        'trang_thai_tai_khoan',
    ];

    protected $hidden = [
        'mat_khau_ma_hoa',
        'remember_token',
    ];

    // Chỉ định cho Laravel biết cột mật khẩu là mat_khau_ma_hoa
    public function getAuthPassword()
    {
        return $this->mat_khau_ma_hoa;
    }

    // Quan hệ với bảng phân quyền
    public function phanQuyen()
    {
        return $this->belongsTo(PhanQuyen::class, 'id_phan_quyen', 'id_phan_quyen');
    }

    /**
     * Báo cho Laravel biết username dùng để đăng nhập là cột dia_chi_email
     */
    public function username()
    {
        return 'dia_chi_email';
    }

    // Accessors hỗ trợ hiển thị họ tên và email linh hoạt
    public function getHoTenAttribute()
    {
        return $this->ho_va_ten;
    }

    public function getEmailAttribute()
    {
        return $this->dia_chi_email;
    }

    /**
     * Vô hiệu hóa remember_token do bảng cơ sở dữ liệu không có cột này
     */
    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // Không lưu do bảng không có cột remember_token
    }

    public function getRememberTokenName()
    {
        return '';
    }
}