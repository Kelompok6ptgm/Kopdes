<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->nama_produk }} — KoperasiDesa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Instrument Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

    @php $user = Auth::user(); @endphp

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-auto">
                <span class="text-[#c52228] font-bold text-base tracking-tight">KoperasiDesa</span>
            </a>
            <a href="{{ url('/') . '#user-products' }}" class="text-sm font-semibold text-gray-600 hover:text-[#c52228] flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Product Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Image -->
                <div class="md:w-80 flex-shrink-0 bg-gray-100 flex items-center justify-center min-h-64">
                    @if($product->gambar)
                        <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}" class="w-full h-72 md:h-full object-cover">
                    @else
                        <div class="w-full h-72 md:h-full flex items-center justify-center bg-gray-100">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="p-6 md:p-8 flex flex-col justify-between flex-1 space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="bg-red-50 text-[#c52228] text-xs font-bold px-2.5 py-1 rounded-full border border-red-100">
                                {{ $product->category->nama_kategori ?? 'Umum' }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $product->kopdes->nama_kopdes ?? 'KopDes' }}</span>
                        </div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-900">{{ $product->nama_produk }}</h1>

                        <!-- Rating Stars -->
                        <div class="flex items-center gap-2">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($avgRating >= $i)
                                        <span class="text-yellow-400 text-lg">★</span>
                                    @elseif($avgRating >= $i - 0.5)
                                        <span class="text-yellow-300 text-lg">★</span>
                                    @else
                                        <span class="text-gray-300 text-lg">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm font-semibold text-gray-700">{{ $avgRating ? number_format($avgRating, 1) : '0.0' }}</span>
                            <span class="text-xs text-gray-400">({{ $reviewCount }} ulasan)</span>
                        </div>

                        <p class="text-gray-600 text-sm leading-relaxed">{{ $product->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-[#c52228]">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <span class="text-sm {{ $product->stok > 0 ? 'text-green-600' : 'text-red-500' }} font-semibold">
                                {{ $product->stok > 0 ? 'Stok: ' . $product->stok . ' pcs' : 'Stok Habis' }}
                            </span>
                        </div>

                        @if($product->stok > 0)
                            <button id="add-to-cart-btn" onclick="addToCart({{ $product->id_product }})"
                                class="w-full bg-[#c52228] hover:bg-[#a51c21] text-white font-bold py-3 rounded-xl text-sm transition-all cursor-pointer">
                                + Tambah ke Keranjang
                            </button>
                        @else
                            <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3 rounded-xl text-sm cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-6 md:p-8 space-y-6">
            <h2 class="font-bold text-lg text-gray-900">Ulasan Pembeli ({{ $reviewCount }})</h2>

            @forelse($product->reviews->sortByDesc('reviewed_at') as $review)
                <div class="border-b border-gray-100 pb-5 last:border-0 last:pb-0 space-y-2">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-[#c52228] font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($review->user->nama ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <span class="font-semibold text-sm text-gray-900">{{ $review->user->nama ?? 'Pengguna' }}</span>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-{{ $i <= $review->rating ? 'yellow-400' : 'gray-300' }} text-sm">★</span>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $review->reviewed_at ? $review->reviewed_at->diffForHumans() : '' }}</span>
                    </div>

                    @if($review->komentar)
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $review->komentar }}</p>
                    @endif

                    @if($review->tanggapan_manager)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 mt-2">
                            <span class="text-xs font-bold text-[#c52228] uppercase">Tanggapan Manager</span>
                            <p class="text-sm text-gray-700 mt-1">{{ $review->tanggapan_manager }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <p class="text-gray-400 text-sm">Belum ada ulasan untuk produk ini.</p>
                </div>
            @endforelse
        </div>

    </main>

    <!-- Toast -->
    <div id="toast" class="fixed bottom-4 right-4 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-lg transition-all transform translate-y-10 opacity-0 z-50 hidden"></div>

    <script>
        function addToCart(productId) {
            const btn = document.getElementById('add-to-cart-btn');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Menambahkan...';
            }

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id_product: productId, quantity: 1 })
            })
            .then(res => {
                // Handle redirect to login (guest session expired or not JSON)
                if (res.redirected || res.url.includes('/login')) {
                    showToast('Silakan login terlebih dahulu.', 'error');
                    setTimeout(() => window.location.href = '{{ route("login") }}', 1200);
                    return null;
                }
                return res.json();
            })
            .then(data => {
                if (!data) return;
                if (data.success) {
                    showToast('Berhasil ditambahkan ke keranjang!', 'success');
                } else {
                    showToast(data.message || 'Gagal menambahkan barang.', 'error');
                }
            })
            .catch(() => showToast('Terjadi kesalahan koneksi.', 'error'))
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.textContent = '+ Tambah ke Keranjang';
                }
            });
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `fixed bottom-4 right-4 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-lg transition-all z-50 ${type === 'success' ? 'bg-emerald-600' : 'bg-[#c52228]'}`;
            toast.classList.remove('hidden', 'opacity-0', 'translate-y-10');
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-10');
                setTimeout(() => toast.classList.add('hidden'), 300);
            }, 3000);
        }
    </script>
</body>
</html>
