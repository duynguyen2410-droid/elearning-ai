<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuongMucBaiHoc extends Model
{
    use HasFactory;

    protected $table = 'chuong_muc_bai_hoc';
    protected $primaryKey = 'id_chuong_muc';
    public $timestamps = false;

    protected $fillable = [
        'id_khoa_hoc',
        'tieu_de_chuong_muc',
        'mo_ta_chuong_muc',
        'thu_tu_sap_xep',
    ];

    // Thuộc về một khóa học
    public function khoaHoc()
    {
        return $this->belongsTo(KhoaHoc::class, 'id_khoa_hoc', 'id_khoa_hoc');
    }

    // Một chương mục có nhiều bài học
    public function danhSachBaiHoc()
    {
        return $this->hasMany(ChiTietBaiHoc::class, 'id_chuong_muc', 'id_chuong_muc')->orderBy('thu_tu_sap_xep', 'asc');
    }

    public function chiTietBaiHoc()
    {
        return $this->danhSachBaiHoc();
    }

    public function baiHoc()
    {
        return $this->danhSachBaiHoc();
    }
}