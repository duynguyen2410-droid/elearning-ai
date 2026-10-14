<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietCauTraLoi extends Model
{
    protected $table = 'chi_tiet_cau_tra_loi';
    protected $primaryKey = 'id_chi_tiet_tra_loi';
    public $timestamps = false;

    protected $fillable = [
        'id_luot_lam_bai',
        'id_cau_hoi',
        'id_phuong_an_da_chon',
        'danh_gia_dung_sai',
    ];
}