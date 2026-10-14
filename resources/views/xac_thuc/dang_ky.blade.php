<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Đăng ký tài khoản EduMind AI - Nền tảng E-learning tích hợp AI thông minh">
    <title>Đăng ký | EduMind AI</title>

    {{-- Google Fonts – Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                    },
                },
            },
        }
    </script>

    {{-- Font Awesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        /* Dot pattern background */
        .dot-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(79,70,229,.04) 1px, transparent 0);
            background-size: 32px 32px;
        }

        /* Custom focus ring */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
        }

        /* Password toggle button */
        .pwd-toggle {
            transition: color .15s ease;
        }

        .pwd-toggle:hover {
            color: #4f46e5;
        }

        /* Entrance animation */
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-up {
            animation: slide-up .5s cubic-bezier(.4,0,.2,1) both;
        }

        /* Feature card hover lift */
        .feature-card {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .feature-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79,70,229,.1);
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen">

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative dot-pattern">
        {{-- Decorative background gradients --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-32 -left-32 w-[500px] h-[500px] rounded-full bg-brand-600/[.06] blur-3xl"></div>
            <div class="absolute -bottom-40 -right-20 w-[400px] h-[400px] rounded-full bg-violet-500/[.05] blur-3xl"></div>
        </div>

        {{-- ═══════════════════════════════════════
             MAIN CARD CONTAINER
        ═══════════════════════════════════════ --}}
        <div class="w-full max-w-[1000px] bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-2 relative z-10 animate-slide-up">

            {{-- ── LEFT PANEL — Branding & Highlights ───────── --}}
            <div class="hidden lg:flex flex-col justify-center p-10 bg-gradient-to-br from-brand-600 via-brand-500 to-violet-500 text-white relative overflow-hidden">
                {{-- Decorative circles --}}
                <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/[.07]" aria-hidden="true"></div>
                <div class="absolute -bottom-16 -left-16 w-56 h-56 rounded-full bg-amber-400/[.1]" aria-hidden="true"></div>

                <div class="relative z-10">
                    {{-- Logo --}}
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-11 h-11 bg-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center text-lg border border-white/20">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight">
                            Edu<span class="bg-gradient-to-r from-amber-300 to-amber-400 bg-clip-text text-transparent">Mind</span> AI
                        </span>
                    </div>

                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-1.5 bg-gradient-to-r from-amber-400/25 to-violet-400/25 border border-amber-300/30 text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full mb-5">
                        <i class="fas fa-sparkles text-[10px]"></i>
                        Miễn phí cho học viên
                    </div>

                    <h1 class="text-[1.7rem] font-extrabold leading-tight tracking-tight mb-3">
                        Bắt đầu hành trình<br>
                        học tập thông minh
                    </h1>

                    <p class="text-white/70 text-sm leading-relaxed mb-8 max-w-sm">
                        Tạo tài khoản miễn phí để trải nghiệm nền tảng e-learning thế hệ mới với trợ lý ảo AI hỗ trợ 24/7.
                    </p>

                    {{-- Feature highlights --}}
                    <div class="space-y-3">
                        <div class="feature-card flex items-start gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-3.5 border border-white/10">
                            <div class="w-9 h-9 bg-white/15 rounded-lg flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fas fa-wand-magic-sparkles"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold">AI tạo đề thi tự động</p>
                                <p class="text-xs text-white/55 mt-0.5">Gemini AI phân tích bài giảng & sinh câu hỏi tức thì</p>
                            </div>
                        </div>
                        <div class="feature-card flex items-start gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-3.5 border border-white/10">
                            <div class="w-9 h-9 bg-white/15 rounded-lg flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold">Theo dõi tiến độ chi tiết</p>
                                <p class="text-xs text-white/55 mt-0.5">Báo cáo & phân tích kết quả học tập trực quan</p>
                            </div>
                        </div>
                        <div class="feature-card flex items-start gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-3.5 border border-white/10">
                            <div class="w-9 h-9 bg-white/15 rounded-lg flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fas fa-unlock-alt"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold">Hoàn toàn miễn phí</p>
                                <p class="text-xs text-white/55 mt-0.5">Không giới hạn khóa học & bài kiểm tra trắc nghiệm</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT PANEL — Register Form ── --}}
            <div class="flex flex-col justify-center p-8 sm:p-10">

                {{-- Mobile logo --}}
                <div class="flex lg:hidden items-center gap-2.5 mb-6">
                    <div class="w-9 h-9 bg-brand-600 rounded-lg flex items-center justify-center text-white text-sm">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="text-lg font-extrabold tracking-tight text-slate-800">
                        Edu<span class="text-brand-600">Mind</span> AI
                    </span>
                </div>

                <div class="mb-7">
                    <h2 class="text-[1.45rem] font-extrabold text-slate-800 tracking-tight mb-1">Tạo tài khoản mới</h2>
                    <p class="text-slate-500 text-sm">Điền thông tin bên dưới để bắt đầu hành trình học tập</p>
                </div>

                {{-- ── Validation Errors Alert ─────── --}}
                @if (isset($errors) && $errors->any())
                    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-circle-exclamation text-red-500 text-sm"></i>
                            <span class="text-sm font-semibold text-red-700">Vui lòng kiểm tra lại thông tin</span>
                        </div>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-xs text-red-600 flex items-start gap-1.5">
                                    <i class="fas fa-chevron-right text-[8px] mt-[5px] text-red-400 flex-shrink-0"></i>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ── Session Alerts ───────── --}}
                @if (session('success'))
                    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2.5">
                        <i class="fas fa-circle-check text-emerald-500"></i>
                        <span class="text-sm font-medium text-emerald-700">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-2.5">
                        <i class="fas fa-circle-exclamation text-red-500"></i>
                        <span class="text-sm font-medium text-red-700">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- ── Registration Form ─────── --}}
                <form method="POST" action="{{ route('register.post') }}" novalidate class="space-y-5">
                    @csrf

                    {{-- Full Name --}}
                    <div>
                        <label for="ho_ten" class="block text-xs font-semibold text-slate-700 mb-1.5 tracking-wide">
                            Họ và tên
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-user text-slate-400 text-sm"></i>
                            </div>
                            <input
                                type="text"
                                id="ho_ten"
                                name="ho_ten"
                                value="{{ old('ho_ten') }}"
                                placeholder="Nguyễn Văn A"
                                autocomplete="name"
                                autofocus
                                required
                                class="input-focus w-full pl-10 pr-4 py-2.5 text-sm bg-white border rounded-xl outline-none transition-colors duration-200
                                       {{ $errors->has('ho_ten') ? 'border-red-400 text-red-700' : 'border-slate-200 hover:border-slate-300 focus:border-brand-500 text-slate-800' }}"
                            >
                        </div>
                        @error('ho_ten')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5 tracking-wide">
                            Địa chỉ email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-slate-400 text-sm"></i>
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                autocomplete="email"
                                required
                                class="input-focus w-full pl-10 pr-4 py-2.5 text-sm bg-white border rounded-xl outline-none transition-colors duration-200
                                       {{ $errors->has('email') ? 'border-red-400 text-red-700' : 'border-slate-200 hover:border-slate-300 focus:border-brand-500 text-slate-800' }}"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5 tracking-wide">
                            Mật khẩu
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400 text-sm"></i>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Tối thiểu 6 ký tự"
                                autocomplete="new-password"
                                required
                                class="input-focus w-full pl-10 pr-11 py-2.5 text-sm bg-white border rounded-xl outline-none transition-colors duration-200
                                       {{ $errors->has('password') ? 'border-red-400 text-red-700' : 'border-slate-200 hover:border-slate-300 focus:border-brand-500 text-slate-800' }}"
                            >
                            <button
                                type="button"
                                class="pwd-toggle absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 text-sm"
                                onclick="togglePassword('password', this)"
                                aria-label="Hiển thị mật khẩu"
                                tabindex="-1"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5 tracking-wide">
                            Xác nhận mật khẩu
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-shield-halved text-slate-400 text-sm"></i>
                            </div>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Nhập lại mật khẩu"
                                autocomplete="new-password"
                                required
                                class="input-focus w-full pl-10 pr-11 py-2.5 text-sm bg-white border border-slate-200 hover:border-slate-300 focus:border-brand-500 rounded-xl outline-none transition-colors duration-200 text-slate-800"
                            >
                            <button
                                type="button"
                                class="pwd-toggle absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 text-sm"
                                onclick="togglePassword('password_confirmation', this)"
                                aria-label="Hiển thị mật khẩu xác nhận"
                                tabindex="-1"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Terms notice --}}
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Bằng cách đăng ký, bạn đồng ý với
                        <a href="#" class="text-brand-600 font-medium hover:underline">Điều khoản dịch vụ</a>
                        và
                        <a href="#" class="text-brand-600 font-medium hover:underline">Chính sách bảo mật</a>
                        của EduMind AI.
                    </p>

                    {{-- Submit button --}}
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2.5 px-6 text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand-600/30 active:translate-y-0"
                    >
                        <i class="fas fa-user-plus text-xs"></i>
                        Tạo tài khoản
                    </button>
                </form>

                {{-- Divider --}}
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <span class="text-xs text-slate-400">hoặc</span>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>

                {{-- Login Link --}}
                <p class="text-center text-sm text-slate-500">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="text-brand-600 font-semibold hover:text-brand-700 hover:underline transition-colors">
                        Đăng nhập ngay
                    </a>
                </p>

                {{-- Security Note --}}
                <p class="text-center text-[11px] text-slate-400 mt-5 flex items-center justify-center gap-1.5">
                    <i class="fas fa-shield-halved"></i>
                    Dữ liệu được bảo mật với mã hóa SSL tiêu chuẩn
                </p>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         Vanilla JS — Password Toggle
    ══════════════════════════════════════════════════════ --}}
    <script>
        function togglePassword(inputId, btn) {
            var input = document.getElementById(inputId);
            var icon  = btn.querySelector('i');
            if (!input || !icon) return;

            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);
            btn.setAttribute('aria-label', isPassword ? 'Ẩn mật khẩu' : 'Hiển thị mật khẩu');
        }
    </script>

</body>

</html>
