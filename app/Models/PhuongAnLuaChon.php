<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhuongAnLuaChon extends Model
{
    use HasFactory;

    protected $table = 'phuong_an_lua_chon';
    protected $primaryKey = 'id_phuong_an';
    public $timestamps = false;

    protected $fillable = [
        'id_cau_hoi',
        'ky_tu_phuong_an', // 'A', 'B', 'C', 'D'
        'noi_dung_phuong_an',
        'la_dap_an_chinh_xac', // 1: đúng, 0: sai
    ];

    // Thuộc về một câu hỏi
    public function cauHoi()
    {
        return $this->belongsTo(CauHoiKiemTra::class, 'id_cau_hoi', 'id_cau_hoi');
    }
}