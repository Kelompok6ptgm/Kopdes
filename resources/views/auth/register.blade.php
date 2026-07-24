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

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Nama Lengkap
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: johndoe" required
                        class="w-full px-4 py-3 bg-[#f0f4f8] border border-transparent rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Contoh: johndoe@gmail.com" required
                        class="w-full px-4 py-3 bg-[#f0f4f8] border border-transparent rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Nomor Handphone -->
                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Nomor Handphone
                    </label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 0856982394" required
                        class="w-full px-4 py-3 bg-[#f0f4f8] border border-transparent rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Alamat
                    </label>
                    <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" placeholder="Contoh: Jl. Merdeka 123" required
                        class="w-full px-4 py-3 bg-[#f0f4f8] border border-transparent rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="*******" required
                            class="w-full px-4 py-3 bg-[#f0f4f8] border border-transparent rounded-lg text-sm focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder-gray-400 pr-10">
                        <!-- Toggle Eye Button -->
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eye-icon-closed" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#c52228] hover:bg-[#a51c21] text-white py-3.5 rounded-lg font-bold text-base shadow-md hover:shadow-lg transition-all cursor-pointer mt-4">
                    Daftar Sekarang
                </button>
            </form>

            <!-- Footer Link -->
            <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-[#c52228] font-bold hover:underline">Masuk di sini</a>
            </p>
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
    </script>
</body>
</html>
