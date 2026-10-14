<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhoaHoc extends Model
{
    use HasFactory;

    protected $table = 'khoa_hoc';
    protected $primaryKey = 'id_khoa_hoc';
    public $timestamps = false;

    protected $fillable = [
        'id_giang_vien_phu_trach',
        'id_danh_muc',
        'tieu_de_khoa_hoc',
        'duong_dan_than_thien_slug',
        'mo_ta_ngan_gon',
        'noi_dung_gioi_thieu_chi_tiet',
        'duong_dan_anh_bia',
        'cap_do_khoa_hoc',
        'trang_thai_xuat_ban',
        'tong_thoi_luong_phut',
        'thoi_diem_tao',
        'thoi_diem_cap_nhat',
    ];

    // Thuộc về một danh mục
    public function danhMuc()
    {
        return $this->belongsTo(DanhMucKhoaHoc::class, 'id_danh_muc', 'id_danh_muc');
    }

    // Thuộc về giảng viên phụ trách (liên kết id_giang_vien_phu_trach -> id_nguoi_dung)
    public function giangVien()
    {
        return $this->belongsTo(User::class, 'id_giang_vien_phu_trach', 'id_nguoi_dung');
    }
    // Một khóa học có nhiều chương mục bài học
    public function danhSachChuongMuc()
    {
        return $this->hasMany(ChuongMucBaiHoc::class, 'id_khoa_hoc', 'id_khoa_hoc')->orderBy('thu_tu_sap_xep', 'asc');
    }
}