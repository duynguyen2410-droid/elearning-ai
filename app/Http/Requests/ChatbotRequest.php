<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChatbotRequest extends FormRequest
{
    /**
     * Xác thực quyền gửi yêu cầu tới Chatbot
     */
    public function authorize(): bool
    {
        // Chỉ dành riêng cho tài khoản Học viên (id_phan_quyen = 3) đã đăng nhập
        return Auth::check() && (int) Auth::user()->id_phan_quyen === 3;
    }

    /**
     * Xử lý khi authorization thất bại
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'reply'   => 'Tính năng Trợ lý AI chỉ dành riêng cho tài khoản Học viên.',
        ], 403));
    }

    /**
     * Quy tắc kiểm tra dữ liệu đầu vào
     */
    public function rules(): array
    {
        return [
            'message'   => ['nullable', 'string', 'max:2000'],
            'image'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'lesson_id' => ['nullable', 'integer'],
            'context'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Thông báo lỗi tùy biến
     */
    public function messages(): array
    {
        return [
            'message.max' => 'Nội dung câu hỏi không được vượt quá 2000 ký tự.',
            'image.image' => 'Tệp tải lên phải là hình ảnh hợp lệ.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg hoặc webp.',
            'image.max'   => 'Dung lượng ảnh tối đa cho phép là 5MB.',
        ];
    }

    /**
     * Kiểm tra bổ sung: Phải có ít nhất message hoặc image
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $msg = trim($this->input('message') ?? '');
            $hasImage = $this->hasFile('image');

            if (empty($msg) && !$hasImage) {
                $validator->errors()->add(
                    'message',
                    'Vui lòng nhập câu hỏi hoặc đính kèm hình ảnh bài tập / lỗi cần hỗ trợ.'
                );
            }
        });
    }

    /**
     * Xử lý khi validation thất bại (trả về JSON 422 chuẩn cho client/widget)
     */
    protected function failedValidation(Validator $validator)
    {
        $firstError = $validator->errors()->first();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'reply'   => $firstError ?: 'Dữ liệu gửi lên không hợp lệ.',
            'errors'  => $validator->errors(),
        ], 422));
    }

    /**
     * Lấy nội dung câu hỏi đã được làm sạch, chống prompt injection cơ bản
     */
    public function getCleanMessage(): string
    {
        $raw = trim($this->input('message') ?? '');
        return $this->sanitizeForPrompt($raw);
    }

    /**
     * Lấy ngữ cảnh bổ sung đã được làm sạch
     */
    public function getCleanContext(): string
    {
        $raw = trim($this->input('context') ?? '');
        return $this->sanitizeForPrompt($raw);
    }

    /**
     * Làm sạch chuỗi chống can thiệp vào System Instruction
     */
    protected function sanitizeForPrompt(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Loại bỏ các ký tự null byte hoặc điều khiển nguy hiểm
        $clean = str_replace(["\0", "\x0B"], '', $text);

        // Chuẩn hóa xuống dòng
        $clean = preg_replace("/\r\n|\r/", "\n", $clean);

        // Hạn chế các cụm từ injection giả mạo System Instruction
        $suspiciousPatterns = [
            '/\[system\s*instruction\]/i',
            '/\[system\s*prompt\]/i',
            '/^system:\s*/im',
            '/^ignore\s+previous\s+instructions/im',
            '/^bỏ\s+qua\s+toàn\s+bộ\s+chỉ\s+dẫn\s+trước/im',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            $clean = preg_replace($pattern, '', $clean);
        }

        return trim($clean);
    }
}
