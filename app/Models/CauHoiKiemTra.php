<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHoiKiemTra extends Model
{
    use HasFactory;

    protected $table = 'cau_hoi_kiem_tra';
    protected $primaryKey = 'id_cau_hoi';
    public $timestamps = false;

    protected $fillable = [
        'id_de_thi',
        'noi_dung_cau_hoi',
        'giai_thich_chi_tiet_dap_an',
        'nguon_goc_cau_hoi', // 'ai_sinh' hoặc 'giang_vien_soan'
        'trang_thai_kiem_duyet',
        'muc_do_kho',
        'diem_so_cau_hoi',
        'thu_tu_hien_thi',
    ];

    // Thuộc về một đề thi
    public function deThi()
    {
        return $this->belongsTo(DeThiTracNghiem::class, 'id_de_thi', 'id_de_thi');
    }

    // Một câu hỏi có nhiều phương án lựa chọn (A, B, C, D)
    public function danhSachPhuongAn()
    {
        return $this->hasMany(PhuongAnLuaChon::class, 'id_cau_hoi', 'id_cau_hoi');
    }
}