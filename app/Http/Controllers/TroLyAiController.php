<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChiTietBaiHoc;
use Illuminate\Support\Facades\Log;

class TroLyAiController extends Controller
{
    /**
     * Xử lý câu hỏi của học viên gửi tới Trợ lý AI Gemini
     */
    public function ask(Request $request)
    {
        $request->validate([
            'message'   => 'required|string|max:2000',
            'lesson_id' => 'nullable|integer',
            'context'   => 'nullable|string|max:1000',
        ]);

        $userMessage = trim($request->input('message'));
        $lessonId = $request->input('lesson_id');
        $extraContext = $request->input('context');

        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'reply'   => 'Hệ thống chưa được cấu hình GEMINI_API_KEY. Vui lòng liên hệ quản trị viên!',
            ], 500);
        }

        // 1. Thu thập ngữ cảnh bài học nếu có
        $lessonContext = '';
        if ($lessonId) {
            $lesson = ChiTietBaiHoc::find($lessonId);
            if ($lesson) {
                $lessonContext = "Ngữ cảnh bài học hiện tại: '{$lesson->tieu_de_bai_hoc}'.";
                if (!empty($lesson->tom_tat_kien_thuc_cho_ai)) {
                    $lessonContext .= " Tóm tắt kiến thức cốt lõi: '{$lesson->tom_tat_kien_thuc_cho_ai}'.";
                }
            }
        }

        if (!empty($extraContext)) {
            $lessonContext .= " Ngữ cảnh bổ sung: '{$extraContext}'.";
        }

        // 2. Xây dựng prompt
        $systemInstruction = "Bạn là trợ lý học tập AI thông minh của nền tảng EduMind AI. Nhiệm vụ của bạn là giải đáp thắc mắc của học viên một cách ngắn gọn, chính xác, dễ hiểu và truyền cảm hứng học tập. Hãy xưng hô 'mình' hoặc 'EduMind AI' và gọi người dùng là 'bạn'. Luôn định dạng câu trả lời gọn gàng (có thể dùng gạch đầu dòng, in đậm để làm nổi bật ý quan trọng).";

        $fullPrompt = $systemInstruction . "\n\n";
        if (!empty($lessonContext)) {
            $fullPrompt .= "[THÔNG TIN BÀI HỌC]:\n" . $lessonContext . "\n\n";
        }
        $fullPrompt .= "[CÂU HỎI CỦA HỌC VIÊN]:\n" . $userMessage;

        // 3. Danh sách model Gemini ưu tiên
        $models = ['gemini-flash-latest', 'gemini-flash-lite-latest', 'gemini-pro-latest'];
        $replyText = null;
        $lastError = '';

        foreach ($models as $modelName) {
            try {
                $response = Http::timeout(25)->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $fullPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.7,
                        'maxOutputTokens' => 1024,
                    ]
                ]);

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
                Log::warning("Gemini model {$modelName} error: " . $e->getMessage());
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
}

