<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanQuyen extends Model
{
    use HasFactory;
    protected $table = 'phan_quyen';
    protected $primaryKey = 'id_phan_quyen';
    public $timestamps = false; // Bảng này không có 2 cột created_at, updated_at

    protected $fillable = [
        'ma_dinh_danh_quyen',
        'ten_hien_thi_quyen',
        'mo_ta_quyen_han',
    ];
}

