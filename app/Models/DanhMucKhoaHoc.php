<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMucKhoaHoc extends Model
{
    use HasFactory;

    protected $table = 'danh_muc_khoa_hoc';
    protected $primaryKey = 'id_danh_muc';
    public $timestamps = false;

    protected $fillable = [
        'ten_danh_muc',
        'mo_ta_danh_muc',
    ];

    // Quan hệ: Một danh mục có nhiều khóa học
    public function danhSachKhoaHoc()
    {
        return $this->hasMany(KhoaHoc::class, 'id_danh_muc', 'id_danh_muc');
    }
}