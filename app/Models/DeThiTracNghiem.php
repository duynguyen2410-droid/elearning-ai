<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeThiTracNghiem extends Model
{
    use HasFactory;

    protected $table = 'de_thi_trac_nghiem';
    protected $primaryKey = 'id_de_thi';
    public $timestamps = false;

    protected $fillable = [
        'id_bai_hoc',
        'tieu_de_de_thi',
        'mo_ta_yeu_cau',
        'thoi_luong_lam_bai_phut',
        'diem_so_toi_da',
        'diem_so_qua_mon',
        'so_lan_lam_lai_toi_da',
        'cho_phep_xem_loi_giai',
        'trang_thai_mo_de',
    ];

    // Đề thi gắn liền với một bài học
    public function baiHoc()
    {
        return $this->belongsTo(ChiTietBaiHoc::class, 'id_bai_hoc', 'id_bai_hoc');
    }

    // Một đề thi có nhiều câu hỏi
    public function danhSachCauHoi()
    {
        return $this->hasMany(CauHoiKiemTra::class, 'id_de_thi', 'id_de_thi')->orderBy('thu_tu_hien_thi', 'asc');
    }
}