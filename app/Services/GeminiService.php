<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;
    protected string $defaultModel;
    protected array $fallbackModels;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->defaultModel = config('services.gemini.model', 'gemini-1.5-flash');
        $this->fallbackModels = config('services.gemini.models', [
            'gemini-1.5-flash',
            'gemini-2.0-flash',
            'gemini-1.5-pro',
        ]);
        $this->timeout = (int) config('services.gemini.timeout', 30);
    }

    /**
     * Lấy danh sách các models sẽ thử theo thứ tự ưu tiên
     */
    public function getModelsToTry(?string $preferredModel = null): array
    {
        $models = [];
        if ($preferredModel) {
            $models[] = $preferredModel;
        }
        if (!in_array($this->defaultModel, $models)) {
            $models[] = $this->defaultModel;
        }
        foreach ($this->fallbackModels as $model) {
            if (!in_array($model, $models)) {
                $models[] = $model;
            }
        }
        return $models;
    }

    /**
     * Gọi Gemini API để sinh nội dung văn bản tổng quát
     *
     * @param array|string $contents Mảng parts hoặc chuỗi prompt text
     * @param array $options Các tùy chọn: system_instruction, temperature, response_mime_type, preferred_model
     * @return array|null ['text' => string, 'model' => string] hoặc null nếu tất cả model thất bại
     */
    public function generateContent($contents, array $options = []): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('GeminiService: Chưa cấu hình GEMINI_API_KEY trong hệ thống.');
            return null;
        }

        // Chuẩn hóa cấu trúc contents
        if (is_string($contents)) {
            $parts = [['text' => $contents]];
        } elseif (isset($contents['parts'])) {
            $parts = $contents['parts'];
        } elseif (is_array($contents)) {
            $parts = $contents;
        } else {
            $parts = [['text' => (string) $contents]];
        }

        $models = $this->getModelsToTry($options['preferred_model'] ?? null);
        $temperature = $options['temperature'] ?? 0.7;
        $responseMimeType = $options['response_mime_type'] ?? null;
        $systemInstruction = $options['system_instruction'] ?? null;

        foreach ($models as $model) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}";

                $payload = [
                    'contents' => [
                        [
                            'parts' => $parts
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => $temperature,
                    ]
                ];

                if (!empty($responseMimeType)) {
                    $payload['generationConfig']['responseMimeType'] = $responseMimeType;
                }

                if (!empty($systemInstruction)) {
                    $payload['systemInstruction'] = [
                        'parts' => [
                            ['text' => $systemInstruction]
                        ]
                    ];
                }

                $response = Http::timeout($this->timeout)->post($url, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if ($text !== null && trim($text) !== '') {
                        return [
                            'text'  => trim($text),
                            'model' => $model,
                        ];
                    }

                    Log::warning("GeminiService: Model {$model} trả về nội dung rỗng.", [
                        'response' => $data,
                    ]);
                } else {
                    $status = $response->status();
                    $errorBody = $response->body();

                    if ($status === 429) {
                        Log::warning("GeminiService [Quota Exceeded]: Model {$model} bị vượt hạn mức (429). Chuyển model fallback.", [
                            'model' => $model,
                            'status' => 429,
                        ]);
                    } else {
                        Log::warning("GeminiService: Model {$model} trả về lỗi HTTP {$status}.", [
                            'model' => $model,
                            'status' => $status,
                            'error' => $errorBody,
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("GeminiService: Ngoại lệ khi gọi model {$model}: " . $e->getMessage(), [
                    'model' => $model,
                    'exception' => get_class($e),
                ]);
            }
        }

        Log::error('GeminiService: Tất cả các models Gemini đều không khả dụng hoặc bị lỗi.');
        return null;
    }

    /**
     * Sinh bộ câu hỏi trắc nghiệm dạng mảng JSON đã chuẩn hóa
     *
     * @param string $prompt Prompt yêu cầu sinh câu hỏi
     * @param int $expectedCount Số lượng câu hỏi kỳ vọng
     * @return array Danh sách câu hỏi đã chuẩn hóa (hoặc rỗng nếu không thể phân tích)
     */
    public function generateQuizQuestions(string $prompt, int $expectedCount = 4): array
    {
        $result = $this->generateContent($prompt, [
            'temperature' => 0.7,
            'response_mime_type' => 'application/json',
        ]);

        if (!$result || empty($result['text'])) {
            // Thử lại không ép response_mime_type nếu model cũ không hỗ trợ
            $result = $this->generateContent($prompt, [
                'temperature' => 0.7,
            ]);
        }

        if (!$result || empty($result['text'])) {
            return [];
        }

        return $this->parseAndValidateQuizJson($result['text'], $expectedCount);
    }

    /**
     * Phân tích và kiểm tra tính hợp lệ của JSON câu hỏi trắc nghiệm
     */
    public function parseAndValidateQuizJson(string $rawText, int $expectedCount = 4): array
    {
        $raw = trim($rawText);

        // Bóc tách JSON nếu bị bọc bởi markdown block
        if (preg_match('/```(?:json)?\s*(\[.*?\])\s*```/is', $raw, $matches)) {
            $raw = $matches[1];
        } elseif (preg_match('/(\[.*\])/is', $raw, $matches)) {
            $raw = $matches[1];
        }

        $parsed = json_decode($raw, true);

        if (!is_array($parsed)) {
            // Nếu là 1 đối tượng câu hỏi đơn lẻ
            if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/is', $rawText, $matches)) {
                $single = json_decode($matches[1], true);
                if (is_array($single)) {
                    $parsed = [$single];
                }
            }
        }

        if (!is_array($parsed) || empty($parsed)) {
            Log::warning('GeminiService: Không thể phân tích JSON câu hỏi từ phản hồi của AI.', [
                'raw_sample' => mb_substr($rawText, 0, 300),
            ]);
            return [];
        }

        $kyTuDefault = ['A', 'B', 'C', 'D'];
        $normalized = [];

        foreach ($parsed as $item) {
            if (!is_array($item)) continue;

            $content = $item['noi_dung_cau_hoi'] ?? $item['cau_hoi'] ?? $item['question'] ?? $item['noi_dung'] ?? null;
            $options = $item['phuong_an'] ?? $item['dap_an'] ?? $item['options'] ?? $item['choices'] ?? null;

            if (!$content || !is_array($options) || count($options) < 2) {
                continue;
            }

            $normalizedOptions = [];
            $hasCorrect = false;

            foreach (array_values($options) as $optIdx => $opt) {
                if (is_string($opt)) {
                    $isCorrect = ($optIdx === 0) ? 1 : 0;
                    $normalizedOptions[] = [
                        'ky_tu'    => $kyTuDefault[$optIdx] ?? chr(65 + $optIdx),
                        'noi_dung' => trim($opt),
                        'dung'     => $isCorrect,
                    ];
                    if ($isCorrect) $hasCorrect = true;
                } elseif (is_array($opt)) {
                    $isCorrect = (!empty($opt['dung']) || !empty($opt['is_correct']) || !empty($opt['correct'])) ? 1 : 0;
                    $normalizedOptions[] = [
                        'ky_tu'    => $opt['ky_tu'] ?? $opt['key'] ?? ($kyTuDefault[$optIdx] ?? chr(65 + $optIdx)),
                        'noi_dung' => trim($opt['noi_dung'] ?? $opt['text'] ?? $opt['content'] ?? ''),
                        'dung'     => $isCorrect,
                    ];
                    if ($isCorrect) $hasCorrect = true;
                }
            }

            // Đảm bảo có ít nhất 1 đáp án đúng
            if (!$hasCorrect && !empty($normalizedOptions)) {
                $normalizedOptions[0]['dung'] = 1;
            }

            $mucDoKho = $item['muc_do_kho'] ?? 'trung_binh';
            if (!in_array($mucDoKho, ['de', 'trung_binh', 'kho'])) {
                $mucDoKho = 'trung_binh';
            }

            $normalized[] = [
                'noi_dung_cau_hoi' => trim($content),
                'giai_thich'       => trim($item['giai_thich'] ?? $item['explanation'] ?? 'Đáp án chính xác theo nội dung bài học.'),
                'muc_do_kho'       => $mucDoKho,
                'phuong_an'        => $normalizedOptions,
            ];
        }

        return array_slice($normalized, 0, $expectedCount);
    }

    /**
     * Xử lý hội thoại của Trợ lý AI (Chatbot)
     *
     * @param string $message Tin nhắn từ người dùng
     * @param array|null $imageData Mảng ['mimeType' => string, 'data' => string base64]
     * @param string|null $context Ngữ cảnh bài học hoặc thông tin bổ sung
     * @param string|null $systemInstruction Chỉ dẫn hệ thống
     * @return string|null Câu trả lời từ AI
     */
    public function chat(string $message, ?array $imageData = null, ?string $context = null, ?string $systemInstruction = null): ?string
    {
        $parts = [];

        // Nếu có ảnh đính kèm
        if (!empty($imageData['mimeType']) && !empty($imageData['data'])) {
            $parts[] = [
                'inlineData' => [
                    'mimeType' => $imageData['mimeType'],
                    'data'     => $imageData['data'],
                ]
            ];
        }

        // Lắp ráp nội dung prompt
        $textPrompt = '';
        if (!empty($context)) {
            $textPrompt .= "[NGỮ CẢNH HỌC TẬP]\n" . trim($context) . "\n\n";
        }
        $textPrompt .= "[CÂU HỎI HỌC VIÊN]\n" . trim($message);

        $parts[] = ['text' => $textPrompt];

        $defaultInstruction = "Bạn là trợ lý học tập AI thông minh của nền tảng EduMind AI. "
            . "Nhiệm vụ của bạn là giải đáp thắc mắc, phân tích hình ảnh bài tập, bài toán, sơ đồ hoặc thông báo lỗi lập trình của học viên một cách ngắn gọn, chính xác, dễ hiểu và truyền cảm hứng học tập. "
            . "Hãy xưng hô 'mình' hoặc 'EduMind AI' và gọi người dùng là 'bạn'. Trả lời bằng tiếng Việt, định dạng Markdown rõ ràng.";

        $result = $this->generateContent($parts, [
            'system_instruction' => $systemInstruction ?: $defaultInstruction,
            'temperature'        => 0.7,
        ]);

        return $result ? $result['text'] : null;
    }
}
