<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuotLamBaiThi extends Model
{
    protected $table = 'luot_lam_bai_thi';
    protected $primaryKey = 'id_luot_lam_bai';
    public $timestamps = false;

    protected $fillable = [
        'id_de_thi',
        'id_hoc_vien',
        'lan_thi_thu',
        'thoi_diem_bat_dau_lam',
        'thoi_diem_nop_bai',
        'tong_so_cau_hoi',
        'so_cau_tra_loi_dung',
        'tong_diem_dat_duoc',
        'ket_qua_xep_loai',
    ];
}