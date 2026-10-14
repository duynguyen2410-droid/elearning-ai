<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết Quả Bài Thi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container" style="max-width: 850px;">
    <!-- Thẻ điểm tổng kết -->
    <div class="card p-4 shadow-sm text-center mb-4 border-0">
        <h3 class="fw-bold mb-2">🎉 Kết Quả Bài Kiểm Tra</h3>
        <div class="display-3 fw-bold {{ $diemSo >= 5 ? 'text-success' : 'text-danger' }} my-2">
            {{ $diemSo }} <span class="fs-4 text-muted">/ 10</span>
        </div>
        <p class="fs-5 mb-3">Bạn đã trả lời đúng <strong>{{ $soCauDung }}</strong> / <strong>{{ $tongSoCau }}</strong> câu hỏi.</p>
        <div>
            <a href="{{ route('hoc_vien.tong_quan') }}" class="btn btn-outline-primary px-4 fw-semibold">
                ⬅️ Quay về danh sách bài học
            </a>
        </div>
    </div>

    <!-- Danh sách xem lại đáp án và giải thích -->
    <h5 class="fw-bold mb-3">Chi tiết từng câu hỏi:</h5>
    @foreach ($chiTietKetQua as $index => $item)
        <div class="card p-4 shadow-sm mb-3 border-0 border-start border-4 {{ $item['ket_qua'] ? 'border-success' : 'border-danger' }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">Câu {{ $index + 1 }}: {{ $item['cau_hoi'] }}</h6>
                <span class="badge {{ $item['ket_qua'] ? 'bg-success' : 'bg-danger' }}">
                    {{ $item['ket_qua'] ? 'Chính xác' : 'Chưa đúng' }}
                </span>
            </div>

            <p class="mb-1">
                Phương án bạn chọn: 
                <strong class="{{ $item['ket_qua'] ? 'text-success' : 'text-danger' }}">
                    {{ $item['phuong_an_chon']->ky_tu_phuong_an ?? $item['phuong_an_chon']->ky_tu ?? '' }}. 
                    {{ $item['phuong_an_chon']->noi_dung_phuong_an ?? $item['phuong_an_chon']->noi_dung ?? '' }}
                </strong>
            </p>

            @if (!$item['ket_qua'] && $item['phuong_an_dung'])
                <p class="mb-1 text-success">
                    Đáp án đúng: 
                    <strong>
                        {{ $item['phuong_an_dung']->ky_tu_phuong_an ?? $item['phuong_an_dung']->ky_tu ?? '' }}. 
                        {{ $item['phuong_an_dung']->noi_dung_phuong_an ?? $item['phuong_an_dung']->noi_dung ?? '' }}
                    </strong>
                </p>
            @endif

            @if ($item['giai_thich'])
                <div class="alert alert-secondary mt-2 mb-0 py-2">
                    <small>💡 <strong>Giải thích kiến thức:</strong> {{ $item['giai_thich'] }}</small>
                </div>
            @endif
        </div>
    @endforeach
</div>
</body>
</html>