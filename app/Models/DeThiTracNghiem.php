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
        'id_chi_tiet_bai_hoc',
        'id_nguoi_tao',
        'tieu_de_de_thi',
        'mo_ta_yeu_cau',
        'thoi_luong_lam_bai_phut',
        'diem_so_toi_da',
        'diem_so_qua_mon',
        'so_lan_lam_lai_toi_da',
        'cho_phep_xem_loi_giai',
        'trang_thai_mo_de',
    ];

    // Người tạo đề thi (Học viên hoặc Giảng viên/Admin)
    public function nguoiTao()
    {
        return $this->belongsTo(User::class, 'id_nguoi_tao', 'id_nguoi_dung');
    }

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

    // Alias hỗ trợ cauHoi
    public function cauHoi()
    {
        return $this->danhSachCauHoi();
    }

    // Một đề thi có nhiều lượt làm bài thi của học viên
    public function danhSachLuotLamBai()
    {
        return $this->hasMany(LuotLamBaiThi::class, 'id_de_thi', 'id_de_thi');
    }

    // Alias hỗ trợ luotLamBai và luotLamBaiThi
    public function luotLamBai()
    {
        return $this->danhSachLuotLamBai();
    }

    public function luotLamBaiThi()
    {
        return $this->danhSachLuotLamBai();
    }

    public function getIdDeThiTracNghiemAttribute()
    {
        return $this->id_de_thi;
    }

    public function getThoiGianLamBaiAttribute()
    {
        return $this->thoi_luong_lam_bai_phut;
    }
}