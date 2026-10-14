{{-- ══════════════════════════════════════════════════════════════
     EDUMIND AI — FLOATING INTERACTIVE CHATBOT WIDGET
══════════════════════════════════════════════════════════════ --}}
<style>
    /* ────────────────────────────────────────────
       Chatbot Floating Widget Styles
    ──────────────────────────────────────────── */
    .edumind-chatbot-wrapper {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1060;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Floating Toggle Button */
    .chatbot-toggle-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #8b5cf6 100%);
        border: none;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.45rem;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4), 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
    }

    .chatbot-toggle-btn:hover {
        transform: scale(1.08) translateY(-2px);
        box-shadow: 0 12px 30px rgba(79, 70, 229, 0.5), 0 4px 10px rgba(0, 0, 0, 0.12);
        color: #ffffff;
    }

    .chatbot-toggle-btn:active {
        transform: scale(0.96);
    }

    .chatbot-toggle-btn .pulse-indicator {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 14px;
        height: 14px;
        background-color: #10b981;
        border: 2.5px solid #ffffff;
        border-radius: 50%;
    }

    .chatbot-toggle-btn .pulse-ping {
        position: absolute;
        top: 0;
        right: 0;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: #10b981;
        opacity: 0.75;
        animation: chat-ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    @keyframes chat-ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    /* Expandable Chat Window */
    .chatbot-window {
        position: absolute;
        bottom: 76px;
        right: 0;
        width: 380px;
        max-width: calc(100vw - 32px);
        height: 540px;
        max-height: calc(100vh - 120px);
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.22), 0 0 1px 1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px) scale(0.95);
        transform-origin: bottom right;
        transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .chatbot-window.is-open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    /* Chat Header */
    .chatbot-header {
        background: linear-gradient(135deg, #312e81 0%, #4338ca 50%, #6366f1 100%);
        color: #ffffff;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .chatbot-header-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .chatbot-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        color: #fde68a;
        flex-shrink: 0;
    }

    .chatbot-header-info h4 {
        margin: 0;
        font-size: 0.92rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .chatbot-status-row {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.85);
        margin-top: 0.15rem;
    }

    .chatbot-status-dot {
        width: 6px;
        height: 6px;
        background-color: #34d399;
        border-radius: 50%;
    }

    .chatbot-header-actions {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .chatbot-header-btn {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.8);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 0.85rem;
    }

    .chatbot-header-btn:hover {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
    }

    /* Chat Messages Body */
    .chatbot-body {
        flex: 1;
        overflow-y: auto;
        padding: 1.25rem;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        scroll-behavior: smooth;
    }

    .chatbot-body::-webkit-scrollbar {
        width: 5px;
    }

    .chatbot-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* Message Item */
    .chat-msg {
        display: flex;
        gap: 0.65rem;
        max-width: 88%;
        animation: chat-fade-in 0.2s ease-out;
    }

    @keyframes chat-fade-in {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .chat-msg.bot {
        align-self: flex-start;
    }

    .chat-msg.user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .chat-msg-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #8b5cf6);
        color: #fde68a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .chat-bubble {
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        font-size: 0.85rem;
        line-height: 1.55;
        word-break: break-word;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .chat-msg.bot .chat-bubble {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-top-left-radius: 0.25rem;
    }

    .chat-msg.user .chat-bubble {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: #ffffff;
        border-top-right-radius: 0.25rem;
    }

    .chat-bubble strong {
        font-weight: 700;
    }

    .chat-bubble ul, .chat-bubble ol {
        margin: 0.5rem 0 0.5rem 1.25rem;
        padding: 0;
    }

    .chat-bubble li {
        margin-bottom: 0.25rem;
    }

    .chat-bubble code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.78rem;
        background: rgba(0, 0, 0, 0.06);
        color: #4338ca;
        padding: 0.15rem 0.35rem;
        border-radius: 0.25rem;
    }

    .chat-msg.user .chat-bubble code {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    /* Quick Prompt Pills */
    .chat-quick-prompts {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 0.35rem;
    }

    .quick-prompt-pill {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        padding: 0.35rem 0.75rem;
        font-size: 0.74rem;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .quick-prompt-pill:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #4338ca;
        transform: translateY(-1px);
    }

    /* Typing Dots Indicator */
    .chat-typing-bubble {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 0.65rem 0.95rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        border-top-left-radius: 0.25rem;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #6366f1;
        animation: typing-jump 1.4s infinite ease-in-out both;
    }

    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    .typing-dot:nth-child(3) { animation-delay: 0; }

    @keyframes typing-jump {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
        40%           { transform: scale(1.1); opacity: 1; }
    }

    /* Chat Footer / Input */
    .chatbot-footer {
        padding: 0.85rem 1rem 0.65rem;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        flex-shrink: 0;
    }

    .chat-input-form {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.85rem;
        padding: 0.35rem 0.5rem 0.35rem 0.85rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .chat-input-form:focus-within {
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }

    .chat-input-field {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.85rem;
        color: #1e293b;
        font-family: inherit;
        padding: 0.35rem 0;
    }

    .chat-input-field::placeholder {
        color: #94a3b8;
    }

    .chat-send-btn {
        width: 36px;
        height: 36px;
        border-radius: 0.65rem;
        background: #4f46e5;
        border: none;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .chat-send-btn:hover:not(:disabled) {
        background: #4338ca;
        transform: scale(1.05);
    }

    .chat-send-btn:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
    }

    .chat-footer-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        font-size: 0.68rem;
        color: #94a3b8;
        margin-top: 0.45rem;
    }
</style>

<div class="edumind-chatbot-wrapper" id="edumindChatbotApp">
    {{-- 1. Floating Toggle Button --}}
    <button
        type="button"
        class="chatbot-toggle-btn"
        id="chatbotToggleBtn"
        title="Trò chuyện cùng Trợ lý AI Gemini"
        aria-label="Mở Trợ lý AI"
    >
        <span class="pulse-ping"></span>
        <span class="pulse-indicator"></span>
        <i class="fa-solid fa-wand-magic-sparkles" id="chatbotToggleIcon"></i>
    </button>

    {{-- 2. Expandable Chat Window --}}
    <div class="chatbot-window" id="chatbotWindow">
        {{-- Header --}}
        <div class="chatbot-header">
            <div class="chatbot-header-left">
                <div class="chatbot-avatar">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div class="chatbot-header-info">
                    <h4>
                        Trợ lý AI Gemini 24/7
                    </h4>
                    <div class="chatbot-status-row">
                        <span class="chatbot-status-dot"></span>
                        <span>Trực tuyến & Sẵn sàng hỗ trợ</span>
                    </div>
                </div>
            </div>
            <div class="chatbot-header-actions">
                <button
                    type="button"
                    class="chatbot-header-btn"
                    id="chatbotClearBtn"
                    title="Xóa lịch sử đoạn chat"
                >
                    <i class="fa-solid fa-rotate-left"></i>
                </button>
                <button
                    type="button"
                    class="chatbot-header-btn"
                    id="chatbotCloseBtn"
                    title="Đóng cửa sổ"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        {{-- Body: Messages Container --}}
        <div class="chatbot-body" id="chatbotMessages">
            {{-- Initial Bot Welcome Message --}}
            <div class="chat-msg bot">
                <div class="chat-msg-avatar">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <div class="chat-bubble">
                        Xin chào! Mình là <strong>Trợ lý AI Gemini</strong> của EduMind. Mình có thể giải thích bài học, tóm tắt kiến thức hoặc tạo câu hỏi ôn tập cho bạn. Hôm nay bạn cần hỗ trợ gì nào? 👋
                    </div>

                    {{-- Quick Prompt Suggestions --}}
                    <div class="chat-quick-prompts">
                        <button type="button" class="quick-prompt-pill" data-prompt="Tóm tắt ngắn gọn nội dung bài học này giúp mình">
                            💡 Tóm tắt bài học
                        </button>
                        <button type="button" class="quick-prompt-pill" data-prompt="Cho mình 2 câu hỏi trắc nghiệm ôn tập về bài này nhé">
                            🎯 Tạo câu hỏi ôn tập
                        </button>
                        <button type="button" class="quick-prompt-pill" data-prompt="Giải thích cho mình các khái niệm cốt lõi của bài">
                            ❓ Giải thích khái niệm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer: Form Input --}}
        <div class="chatbot-footer">
            <form id="chatbotForm" class="chat-input-form" onsubmit="return false;">
                <input
                    type="text"
                    id="chatbotInput"
                    class="chat-input-field"
                    placeholder="Đặt câu hỏi cho AI (nhấn Enter để gửi)..."
                    autocomplete="off"
                    maxlength="1000"
                />
                <button
                    type="submit"
                    id="chatbotSendBtn"
                    class="chat-send-btn"
                    title="Gửi câu hỏi"
                >
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
            <div class="chat-footer-badge">
                <i class="fa-solid fa-bolt text-warning"></i>
                Hỗ trợ bởi Google Gemini 1.5 Flash AI
            </div>
        </div>
    </div>
</div>

{{-- ────────────────────────────────────────────
     Chatbot Client-Side Logic
──────────────────────────────────────────── --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn    = document.getElementById('chatbotToggleBtn');
    const closeBtn     = document.getElementById('chatbotCloseBtn');
    const clearBtn     = document.getElementById('chatbotClearBtn');
    const chatWindow   = document.getElementById('chatbotWindow');
    const chatBody     = document.getElementById('chatbotMessages');
    const chatInput    = document.getElementById('chatbotInput');
    const sendBtn      = document.getElementById('chatbotSendBtn');
    const toggleIcon   = document.getElementById('chatbotToggleIcon');

    let isWaitingForAi = false;

    // Detect Lesson ID if present on page
    function getLessonId() {
        const lessonMeta = document.querySelector('meta[name="lesson-id"]');
        if (lessonMeta && lessonMeta.content) return lessonMeta.content;

        // URL check e.g. /student/bai-hoc/{id}
        const match = window.location.pathname.match(/\/bai-hoc\/(\d+)/);
        if (match && match[1]) return match[1];

        return null;
    }

    // Toggle Chat Window
    function toggleChat(open = null) {
        const isOpen = open !== null ? open : !chatWindow.classList.contains('is-open');
        if (isOpen) {
            chatWindow.classList.add('is-open');
            toggleIcon.classList.replace('fa-wand-magic-sparkles', 'fa-chevron-down');
            setTimeout(() => chatInput.focus(), 250);
            scrollToBottom();
        } else {
            chatWindow.classList.remove('is-open');
            toggleIcon.classList.replace('fa-chevron-down', 'fa-wand-magic-sparkles');
        }
    }

    toggleBtn.addEventListener('click', () => toggleChat());
    closeBtn.addEventListener('click', () => toggleChat(false));

    // Clear Conversation
    clearBtn.addEventListener('click', () => {
        chatBody.innerHTML = `
            <div class="chat-msg bot">
                <div class="chat-msg-avatar">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <div class="chat-bubble">
                        Đoạn hội thoại đã được làm mới. Hãy đặt bất kỳ câu hỏi nào bạn đang thắc mắc nhé! ✨
                    </div>
                    <div class="chat-quick-prompts">
                        <button type="button" class="quick-prompt-pill" data-prompt="Tóm tắt ngắn gọn nội dung bài học này giúp mình">
                            💡 Tóm tắt bài học
                        </button>
                        <button type="button" class="quick-prompt-pill" data-prompt="Cho mình 2 câu hỏi trắc nghiệm ôn tập về bài này nhé">
                            🎯 Tạo câu hỏi ôn tập
                        </button>
                    </div>
                </div>
            </div>
        `;
        attachQuickPromptListeners();
    });

    // Auto-scroll to bottom
    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Simple Safe Markdown to HTML Formatter
    function formatMarkdown(text) {
        if (!text) return '';
        let escaped = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Inline code: `code`
        escaped = escaped.replace(/`([^`]+)`/g, '<code>$1</code>');

        // Bold: **text**
        escaped = escaped.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');

        // Italic: *text*
        escaped = escaped.replace(/\*([^*]+)\*/g, '<em>$1</em>');

        // Bullet lists
        const lines = escaped.split('\n');
        let inList = false;
        let formatted = '';

        for (let line of lines) {
            const trimmed = line.trim();
            if (trimmed.startsWith('* ') || trimmed.startsWith('- ')) {
                if (!inList) {
                    formatted += '<ul>';
                    inList = true;
                }
                formatted += '<li>' + trimmed.substring(2) + '</li>';
            } else {
                if (inList) {
                    formatted += '</ul>';
                    inList = false;
                }
                if (trimmed) {
                    formatted += (formatted ? '<br>' : '') + line;
                }
            }
        }
        if (inList) formatted += '</ul>';

        return formatted || escaped.replace(/\n/g, '<br>');
    }

    // Append User Message
    function appendUserMessage(text) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'chat-msg user';
        msgDiv.innerHTML = `
            <div class="chat-bubble">
                ${text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}
            </div>
        `;
        chatBody.appendChild(msgDiv);
        scrollToBottom();
    }

    // Append Bot Message
    function appendBotMessage(htmlContent) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'chat-msg bot';
        msgDiv.innerHTML = `
            <div class="chat-msg-avatar">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <div class="chat-bubble">
                ${htmlContent}
            </div>
        `;
        chatBody.appendChild(msgDiv);
        scrollToBottom();
    }

    // Append Typing Indicator
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-msg bot';
        typingDiv.id = 'chatTypingIndicator';
        typingDiv.innerHTML = `
            <div class="chat-msg-avatar">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <div class="chat-typing-bubble">
                <span class="typing-dot"></span>
                <span class="typing-dot"></span>
                <span class="typing-dot"></span>
            </div>
        `;
        chatBody.appendChild(typingDiv);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        const typing = document.getElementById('chatTypingIndicator');
        if (typing) typing.remove();
    }

    // Send Message Handler
    async function sendMessage(text) {
        const message = (text || chatInput.value).trim();
        if (!message || isWaitingForAi) return;

        appendUserMessage(message);
        chatInput.value = '';
        isWaitingForAi = true;
        sendBtn.disabled = true;
        showTypingIndicator();

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const lessonId = getLessonId();

        try {
            const response = await fetch("{{ route('chatbot.ask') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    message: message,
                    lesson_id: lessonId,
                })
            });

            removeTypingIndicator();

            if (response.ok) {
                const data = await response.json();
                if (data.success && data.reply) {
                    appendBotMessage(formatMarkdown(data.reply));
                } else {
                    appendBotMessage(data.reply || 'Không nhận được câu trả lời từ AI.');
                }
            } else {
                const errData = await response.json().catch(() => ({}));
                appendBotMessage(errData.reply || 'Rất tiếc, đã xảy ra lỗi kết nối tới máy chủ AI. Bạn thử lại nhé!');
            }
        } catch (error) {
            removeTypingIndicator();
            appendBotMessage('Không thể kết nối đến mạng internet hoặc máy chủ. Vui lòng kiểm tra kết nối!');
        } finally {
            isWaitingForAi = false;
            sendBtn.disabled = false;
            chatInput.focus();
        }
    }

    // Form Submit Listener
    document.getElementById('chatbotForm').addEventListener('submit', (e) => {
        e.preventDefault();
        sendMessage();
    });

    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Quick Prompt Buttons
    function attachQuickPromptListeners() {
        document.querySelectorAll('.quick-prompt-pill').forEach(btn => {
            btn.onclick = function () {
                const prompt = this.getAttribute('data-prompt');
                if (prompt) sendMessage(prompt);
            };
        });
    }

    attachQuickPromptListeners();
});
</script>

