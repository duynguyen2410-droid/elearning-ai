<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ChiTietBaiHoc;

class TroLyAiController extends Controller
{
    /**
     * Xử lý câu hỏi và hình ảnh bài tập của học viên gửi tới Trợ lý AI Gemini (1.5 Flash)
     */
    public function ask(Request $request)
    {
        // 1. Phân quyền chặt chẽ: Chỉ dành riêng cho tài khoản Học viên (id_phan_quyen = 3)
        if (!Auth::check() || Auth::user()->id_phan_quyen != 3) {
            return response()->json([
                'success' => false,
                'reply'   => 'Tính năng Trợ lý AI chỉ dành riêng cho tài khoản Học viên.',
            ], 403);
        }

        // 2. Validate dữ liệu đầu vào: message có thể nullable nếu có image đính kèm
        $request->validate([
            'message'   => 'nullable|string|max:2000',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'lesson_id' => 'nullable|integer',
            'context'   => 'nullable|string|max:1000',
        ], [
            'image.image' => 'Tệp tải lên phải là hình ảnh hợp lệ.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg hoặc webp.',
            'image.max'   => 'Dung lượng ảnh tối đa cho phép là 5MB.',
        ]);

        $userMessage = trim($request->input('message') ?? '');
        $hasImage    = $request->hasFile('image');

        if (empty($userMessage) && !$hasImage) {
            return response()->json([
                'success' => false,
                'reply'   => 'Vui lòng nhập câu hỏi hoặc đính kèm hình ảnh bài tập / lỗi cần hỗ trợ.',
            ], 422);
        }

        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'reply'   => 'Hệ thống chưa được cấu hình GEMINI_API_KEY. Vui lòng liên hệ quản trị viên!',
            ], 500);
        }

        // 3. Xây dựng mảng parts gửi đến Google Gemini
        $parts = [];

        // Nếu có file ảnh tải lên: Lấy mã hóa Base64 và mimeType
        if ($hasImage) {
            try {
                $imageFile = $request->file('image');
                $imageData = base64_encode(file_get_contents($imageFile->getRealPath()));
                $mimeType  = $imageFile->getMimeType();

                $parts[] = [
                    "inlineData" => [
                        "mimeType" => $mimeType,
                        "data"     => $imageData
                    ]
                ];
            } catch (\Throwable $e) {
                Log::error('Lỗi khi đọc file ảnh chatbot: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'reply'   => 'Không thể xử lý tệp ảnh vừa tải lên. Vui lòng thử lại với ảnh khác!',
                ], 422);
            }
        }

        // Thu thập ngữ cảnh bài học nếu có
        $lessonId = $request->input('lesson_id');
        $lessonContext = '';
        if ($lessonId) {
            $lesson = ChiTietBaiHoc::find($lessonId);
            if ($lesson) {
                $lessonContext .= "Ngữ cảnh bài học hiện tại: '{$lesson->tieu_de_bai_hoc}'. ";
                if (!empty($lesson->tom_tat_kien_thuc_cho_ai)) {
                    $lessonContext .= "Tóm tắt kiến thức cốt lõi: '{$lesson->tom_tat_kien_thuc_cho_ai}'. ";
                }
            }
        }

        if ($extraContext = $request->input('context')) {
            $lessonContext .= "Ngữ cảnh bổ sung: '{$extraContext}'. ";
        }

        $systemInstruction = "Bạn là trợ lý học tập AI thông minh của nền tảng EduMind AI. Nhiệm vụ của bạn là giải đáp thắc mắc, phân tích hình ảnh bài tập, bài toán, sơ đồ hoặc thông báo lỗi lập trình của học viên một cách ngắn gọn, chính xác, dễ hiểu và truyền cảm hứng học tập. Hãy xưng hô 'mình' hoặc 'EduMind AI' và gọi người dùng là 'bạn'. Trả lời bằng tiếng Việt, định dạng Markdown rõ ràng.";

        $cauHoi = !empty($userMessage)
            ? $userMessage
            : 'Hãy quan sát hình ảnh bài tập/vấn đề này và hướng dẫn, giải đáp chi tiết cho tôi.';

        $fullTextPrompt = $systemInstruction . "\n\n";
        if (!empty($lessonContext)) {
            $fullTextPrompt .= "[THÔNG TIN BÀI HỌC]:\n" . $lessonContext . "\n\n";
        }
        $fullTextPrompt .= "[CÂU HỎI / YÊU CẦU CỦA HỌC VIÊN]:\n" . $cauHoi;

        $parts[] = [
            "text" => $fullTextPrompt
        ];

        // Cấu trúc payload gửi Gemini API
        $payload = [
            "contents" => [
                [
                    "role"  => "user",
                    "parts" => $parts
                ]
            ],
            "generationConfig" => [
                "temperature"     => 0.7,
                "maxOutputTokens" => 1500,
            ]
        ];

        // 4. Danh sách model Gemini ưu tiên (Ưu tiên gemini-1.5-flash theo yêu cầu)
        $models = ['gemini-1.5-flash', 'gemini-flash-latest', 'gemini-flash-lite-latest', 'gemini-pro-latest'];
        $replyText = null;
        $lastError = '';

        foreach ($models as $modelName) {
            try {
                $response = Http::timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}", $payload);

                if ($response->successful()) {
                    $resData = $response->json();
                    $reply = $resData['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($reply) {
                        $replyText = trim($reply);
                        break;
                    }
                } else {
                    $lastError = $response->body();
                }
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                Log::warning("Gemini model {$modelName} chatbot error: " . $e->getMessage());
            }
        }

        if ($replyText) {
            return response()->json([
                'success' => true,
                'reply'   => $replyText,
            ]);
        }

        return response()->json([
            'success' => false,
            'reply'   => 'Rất tiếc, trợ lý AI hiện đang bận xử lý hoặc gặp sự cố kết nối. Bạn vui lòng thử lại sau giây lát nhé!',
            'error'   => config('app.debug') ? $lastError : null,
        ], 500);
    }

    /**
     * Alias hỗ trợ hoiDap
     */
    public function hoiDap(Request $request)
    {
        return $this->ask($request);
    }
}
