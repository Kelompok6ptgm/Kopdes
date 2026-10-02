<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota - Kopdes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen w-full flex flex-col md:flex-row m-0 p-0 bg-white overflow-x-hidden">
    <!-- Left Side: Kopdes Info Panel (Full Screen Column) -->
    <div class="w-full md:w-1/2 bg-[#f0f2f5] p-8 md:p-16 flex flex-col justify-between items-center text-center border-b md:border-b-0 md:border-r border-gray-200 min-h-[450px] md:min-h-screen">
        <!-- Centered logo at top -->
        <div class="mt-8 mb-6">
            <img src="{{ asset('images/logo.jpg') }}" alt="Kopdes Logo" class="h-28 w-auto mx-auto rounded-xl">
        </div>

        <!-- Header Text -->
        <div class="max-w-md mx-auto mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#c52228] mb-4 leading-tight">
                Membangun Ekonomi Desa
            </h1>
            <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                Bergabunglah bersama ribuan warga lainnya untuk mewujudkan kemandirian ekonomi desa yang sejahtera dan berkeadilan.
            </p>
        </div>

        <!-- Bottom Illustration Card -->
        <div class="bg-white p-4 rounded-3xl shadow-md max-w-sm w-full mt-auto mb-8 border border-gray-100">
            <img src="{{ asset('images/illustration.png') }}" alt="Cooperative Illustration" class="w-full h-auto rounded-2xl">
        </div>
    </div>

    <!-- Right Side: Registration Form (Full Screen Column) -->
    <div class="w-full md:w-1/2 p-8 md:p-20 flex flex-col justify-center bg-white min-h-screen">
        <div class="max-w-md w-full mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-[#1a2d42] mb-2 tracking-tight">Pendaftaran Anggota</h2>
            <p class="text-gray-500 text-sm mb-8">Silakan lengkapi data diri Anda untuk menjadi bagian dari Koperasi Desa Merah Putih.</p>

            @if ($errors->any())
                <div class="bg-red-50 text-[#c52228] p-4 rounded-lg mb-6 text-sm border border-red-100">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Nama Lengkap
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Fathur Rahman" required minlength="3" maxlength="100"
                        class="w-full px-4 py-2.5 bg-[#f0f4f8] border @error('nama') border-red-500 bg-red-50/30 @else border-transparent @enderror rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400">
                    @error('nama')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Contoh: fathur@gmail.com" required
                        class="w-full px-4 py-2.5 bg-[#f0f4f8] border @error('email') border-red-500 bg-red-50/30 @else border-transparent @enderror rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Handphone & Kode Pos Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nomor Handphone -->
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center">
                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Nomor Handphone
                        </label>
                        <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="081234567890" required minlength="10" maxlength="15"
                            class="w-full px-4 py-2.5 bg-[#f0f4f8] border @error('no_hp') border-red-500 bg-red-50/30 @else border-transparent @enderror rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400">
                        @error('no_hp')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Pos -->
                    <div>
                        <label for="kode_pos" class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center">
                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Kode Pos (5 Digit)
                        </label>
                        <input type="text" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" placeholder="12345" required maxlength="5" pattern="[0-9]{5}"
                            class="w-full px-4 py-2.5 bg-[#f0f4f8] border @error('kode_pos') border-red-500 bg-red-50/30 @else border-transparent @enderror rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400">
                        @error('kode_pos')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Kata Sandi (Min. 8 Karakter)
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required minlength="8"
                            class="w-full px-4 py-2.5 bg-[#f0f4f8] border @error('password') border-red-500 bg-red-50/30 @else border-transparent @enderror rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400 pr-10">
                        <!-- Toggle Eye Button -->
                        <button type="button" onclick="togglePasswordVisibility('password', 'eye-open-1', 'eye-closed-1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-open-1" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg id="eye-closed-1" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Konfirmasi Kata Sandi
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required minlength="8"
                            class="w-full px-4 py-2.5 bg-[#f0f4f8] border border-transparent rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400 pr-10">
                        <!-- Toggle Eye Button -->
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-open-2', 'eye-closed-2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-open-2" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg id="eye-closed-2" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#c52228] hover:bg-[#a51c21] text-white py-3 rounded-lg font-bold text-sm md:text-base shadow-md hover:shadow-lg transition-all cursor-pointer mt-2">
                    Daftar Sekarang
                </button>
            </form>

            <!-- Footer Link -->
            <p class="text-center text-sm text-gray-600 mt-5">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-[#c52228] font-bold hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, openId, closedId) {
            const passwordInput = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openId);
            const eyeClosed = document.getElementById(closedId);
            
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
    </script>
</body>
</html>
