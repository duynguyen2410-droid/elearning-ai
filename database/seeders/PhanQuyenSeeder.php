<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhanQuyen;
class PhanQuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $danh_sach_quyen = [
            [
                'ma_dinh_danh_quyen' => 'admin',
                'ten_hien_thi_quyen' => 'Quản trị viên',
                'mo_ta_quyen_han'    => 'Toàn quyền quản lý hệ thống e-learning',
            ],
            [
                'ma_dinh_danh_quyen' => 'giang_vien',
                'ten_hien_thi_quyen' => 'Giảng viên',
                'mo_ta_quyen_han'    => 'Quản lý khóa học, bài học và tạo đề thi',
            ],
            [
                'ma_dinh_danh_quyen' => 'hoc_vien',
                'ten_hien_thi_quyen' => 'Học viên',
                'mo_ta_quyen_han'    => 'Tham gia học tập, làm bài thi và hỏi đáp AI',
            ],
        ];

        foreach ($danh_sach_quyen as $quyen) {
            PhanQuyen::updateOrCreate(
                ['ma_dinh_danh_quyen' => $quyen['ma_dinh_danh_quyen']],
                $quyen
            );
        }
    }
}
