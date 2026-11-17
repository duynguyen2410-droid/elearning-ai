<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatbotRequest;
use App\Models\ChiTietBaiHoc;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TroLyAiController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Xử lý câu hỏi và hình ảnh bài tập của học viên gửi tới Trợ lý AI Gemini
     */
    public function ask(ChatbotRequest $request)
    {
        $userMessage = $request->getCleanMessage();
        $hasImage    = $request->hasFile('image');

        $imageData = null;

        // Nếu có file ảnh tải lên: Lấy mã hóa Base64 và mimeType
        if ($hasImage) {
            try {
                $imageFile = $request->file('image');
                $imageData = [
                    'mimeType' => $imageFile->getMimeType(),
                    'data'     => base64_encode(file_get_contents($imageFile->getRealPath())),
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
                $lessonContext .= "Ngữ cảnh bài học: '{$lesson->tieu_de_bai_hoc}'. ";
                if (!empty($lesson->tom_tat_kien_thuc_cho_ai)) {
                    $lessonContext .= "Tóm tắt kiến thức cốt lõi: '{$lesson->tom_tat_kien_thuc_cho_ai}'. ";
                }
            }
        }

        if ($extraContext = $request->getCleanContext()) {
            $lessonContext .= "Ngữ cảnh bổ sung: '{$extraContext}'. ";
        }

        $cauHoi = !empty($userMessage)
            ? $userMessage
            : 'Hãy quan sát hình ảnh bài tập/vấn đề này và hướng dẫn, giải đáp chi tiết cho tôi.';

        $reply = $this->geminiService->chat(
            message: $cauHoi,
            imageData: $imageData,
            context: $lessonContext
        );

        if ($reply !== null && trim($reply) !== '') {
            return response()->json([
                'success' => true,
                'reply'   => $reply,
            ]);
        }

        return response()->json([
            'success' => false,
            'reply'   => 'Rất tiếc, trợ lý AI hiện đang bận xử lý hoặc gặp sự cố kết nối. Bạn vui lòng thử lại sau giây lát nhé!',
        ], 500);
    }

    /**
     * Alias hỗ trợ hoiDap
     */
    public function hoiDap(ChatbotRequest $request)
    {
        return $this->ask($request);
    }

    /**
     * Sinh đề thi trắc nghiệm bằng AI
     */
    public function sinhDeThiAi(Request $request, $id)
    {
        return app(AiTaoDeThiController::class)->generateQuiz($request, $id);
    }

    /**
     * Alias sinh đề thi cho học viên
     */
    public function hocVienSinhDeThi(Request $request, $id)
    {
        return app(AiTaoDeThiController::class)->generateQuiz($request, $id);
    }
}
