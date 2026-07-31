<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Kopdes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen w-full flex flex-col md:flex-row m-0 p-0 bg-white overflow-x-hidden">
    <!-- Left Side: Illustration & Overlaid Text (Full Screen Column) -->
    <div
        class="w-full md:w-1/2 bg-white p-8 md:p-16 flex flex-col justify-between relative overflow-hidden min-h-[450px] md:min-h-screen border-b md:border-b-0 md:border-r border-gray-200">
        <!-- Top Logo & Name -->
        <div class="flex items-center space-x-3 z-20">
            <img src="{{ asset('images/logo.jpg') }}" alt="Kopdes Logo" class="h-8 w-auto">
            <span class="text-[#c52228] font-bold text-lg">Koperasi Desa</span>
        </div>

        <!-- Overlaid Text -->
        <div class="mt-12 mb-auto z-20 max-w-lg">
            <h1 class="text-3xl md:text-5xl font-extrabold text-[#1a2d42] leading-tight mb-6">
                Membangun Ekonomi Desa<br>Lebih Berdaya.
            </h1>
            <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                Platform digital terpadu untuk kesejahteraan anggota koperasi di seluruh penjuru Indonesia. Mari
                melangkah bersama menuju kemandirian ekonomi.
            </p>
        </div>

        <!-- Illustration stretched and positioned at the bottom -->
        <div class="absolute bottom-0 left-0 w-full px-8 md:px-12 z-10 pointer-events-none">
            <img src="{{ asset('images/illustration.png') }}" alt="Cooperative Illustration"
                class="w-full h-auto object-contain object-bottom opacity-40" style="opacity: 0.5;">
        </div>
    </div>

    <!-- Right Side: Form (Full Screen Column) -->
    <div class="w-full md:w-1/2 p-8 md:p-20 flex flex-col justify-center bg-white z-20 min-h-screen">
        <div class="max-w-md w-full mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-[#1a2d42] mb-2 tracking-tight">Selamat Datang Kembali!</h2>
            <p class="text-gray-500 text-sm mb-8">Silakan masuk ke akun Anda untuk melanjutkan aktivitas.</p>

            @if ($errors->any())
                <div class="bg-red-50 text-[#c52228] p-4 rounded-lg mb-6 text-sm border border-red-100">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 text-sm border border-green-100">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Contoh: johndoe@gmail.com" required
                        class="w-full px-4 py-3 bg-[#f0f4f8] border border-transparent rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700 flex items-center">
                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Kata Sandi
                        </label>
                        <button type="button" onclick="openForgotPasswordModal()" class="text-xs text-[#c52228] font-bold hover:underline cursor-pointer">Lupa Password?</button>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="******" required
                            class="w-full px-4 py-3 bg-[#f0f4f8] border border-transparent rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400 pr-10">
                        <!-- Toggle Eye Button -->
                        <button type="button" onclick="togglePasswordVisibility()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg id="eye-icon-closed" class="w-5 h-5" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="remember" class="ml-2 text-sm text-gray-600 select-none font-medium">Ingat saya di
                        perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-[#c52228] hover:bg-[#a51c21] text-white py-3.5 rounded-lg font-bold text-base shadow-md hover:shadow-lg transition-all cursor-pointer">
                    Masuk
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-8 flex items-center justify-center">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <span
                    class="relative px-4 bg-white text-xs text-gray-400 font-bold tracking-wider uppercase">ATAU</span>
            </div>

            <!-- Footer Link -->
            <p class="text-center text-sm text-gray-600">
                Belum punya akun? <a href="{{ route('register') }}"
                    class="text-[#c52228] font-bold hover:underline">Daftar Sekarang</a>
            </p>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgot-password-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl relative animate-fade-in">
            <button onclick="closeForgotPasswordModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
            <div>
                <h3 class="text-lg md:text-xl font-bold text-gray-900">Ajukan Reset Password</h3>
                <p class="text-xs text-gray-500 mt-1">Masukkan Email atau Nomor HP Anda. Pengajuan reset password akan dikirim langsung ke Dashboard Manager Koperasi Anda.</p>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label for="fp-identifier" class="block text-xs font-bold text-gray-600 uppercase mb-1">Email / Nomor HP</label>
                    <input type="text" id="fp-identifier" placeholder="Contoh: johndoe@gmail.com" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-500 bg-gray-50">
                </div>
                <button onclick="submitResetRequest()" id="fp-lookup-btn" class="w-full bg-[#c52228] hover:bg-[#a51c21] text-white py-2 rounded-xl text-xs font-bold shadow-xs transition-all flex items-center justify-center cursor-pointer">
                    Kirim Pengajuan Reset
                </button>
            </div>

            <!-- Success Notification Area -->
            <div id="fp-result-area" class="hidden p-4 bg-emerald-50 text-emerald-800 rounded-xl border border-emerald-100 space-y-2 text-xs md:text-sm">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold text-sm">Pengajuan Terkirim!</span>
                </div>
                <p id="fp-success-msg" class="leading-relaxed"></p>
            </div>
            <div id="fp-error-area" class="hidden p-3 bg-red-50 text-red-700 rounded-xl border border-red-100 text-xs text-center font-semibold"></div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-icon-open');
            const eyeClosed = document.getElementById('eye-icon-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }

        function openForgotPasswordModal() {
            document.getElementById('forgot-password-modal').classList.remove('hidden');
            document.getElementById('forgot-password-modal').classList.add('flex');
            document.getElementById('fp-identifier').value = '';
            document.getElementById('fp-result-area').classList.add('hidden');
            document.getElementById('fp-error-area').classList.add('hidden');
        }

        function closeForgotPasswordModal() {
            document.getElementById('forgot-password-modal').classList.add('hidden');
            document.getElementById('forgot-password-modal').classList.remove('flex');
        }

        function submitResetRequest() {
            const identifier = document.getElementById('fp-identifier').value.trim();
            const btn = document.getElementById('fp-lookup-btn');
            const errorArea = document.getElementById('fp-error-area');
            const resultArea = document.getElementById('fp-result-area');

            if (!identifier) {
                errorArea.innerText = 'Harap isi pengenal akun!';
                errorArea.classList.remove('hidden');
                return;
            }

            errorArea.classList.add('hidden');
            resultArea.classList.add('hidden');
            btn.disabled = true;
            btn.innerText = 'Mengirim...';

            fetch('{{ route("forgot-password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ identifier: identifier })
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerText = 'Kirim Pengajuan Reset';

                if (data.success) {
                    document.getElementById('fp-success-msg').innerText = data.message;
                    resultArea.classList.remove('hidden');
                } else {
                    errorArea.innerText = data.message || 'Gagal mengirim pengajuan reset.';
                    errorArea.classList.remove('hidden');
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerText = 'Kirim Pengajuan Reset';
                errorArea.innerText = 'Terjadi kesalahan sistem.';
                errorArea.classList.remove('hidden');
            });
        }
    </script>
</body>

</html>
