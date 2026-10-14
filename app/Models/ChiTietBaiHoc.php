<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietBaiHoc extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_bai_hoc';
    protected $primaryKey = 'id_bai_hoc';
    public $timestamps = false;

    protected $fillable = [
        'id_chuong_muc',
        'tieu_de_bai_hoc',
        'loai_bai_hoc',
        'duong_dan_video_bai_giang',
        'thoi_luong_video_giay',
        'noi_dung_bai_hoc_chi_tiet',
        'tom_tat_kien_thuc_cho_ai',
        'duong_dan_tai_lieu_dinh_kem',
        'cho_phep_hoc_thu',
        'thu_tu_sap_xep',
    ];

    // Thuộc về một chương mục bài học
    public function chuongMuc()
    {
        return $this->belongsTo(ChuongMucBaiHoc::class, 'id_chuong_muc', 'id_chuong_muc');
    }
    // Một bài học có thể có một hoặc nhiều đề kiểm tra
    public function danhSachDeThi()
    {
        return $this->hasMany(DeThiTracNghiem::class, 'id_bai_hoc', 'id_bai_hoc');
    }

    // Đề thi trắc nghiệm gắn liền với bài học (1-1)
    public function deThi()
    {
        return $this->hasOne(DeThiTracNghiem::class, 'id_bai_hoc', 'id_bai_hoc')->latest('id_de_thi');
    }

    public function deThiTracNghiem()
    {
        return $this->hasOne(DeThiTracNghiem::class, 'id_bai_hoc', 'id_bai_hoc')->latest('id_de_thi');
    }
}