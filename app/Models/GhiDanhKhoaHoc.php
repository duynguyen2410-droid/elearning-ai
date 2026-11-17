<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GhiDanhKhoaHoc extends Model
{
    use HasFactory;

    protected $table = 'ghi_danh_khoa_hoc';
    protected $primaryKey = 'id_ghi_danh';
    public $timestamps = false;

    protected $fillable = [
        'id_hoc_vien',
        'id_khoa_hoc',
        'phan_tram_hoan_thanh',
        'trang_thai_khoa_hoc',
        'trang_thai_duyet',
        'ngay_ghi_danh',
        'ngay_hoan_thanh',
    ];

    public function hocVien()
    {
        return $this->belongsTo(User::class, 'id_hoc_vien', 'id_nguoi_dung');
    }

    public function khoaHoc()
    {
        return $this->belongsTo(KhoaHoc::class, 'id_khoa_hoc', 'id_khoa_hoc');
    }
}
