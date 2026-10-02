<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle authentication attempt with rate-limiting & remember-me.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        // Max 5 attempts per minute to prevent brute-force attacks
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan masuk yang gagal. Demi keamanan, silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            $this->mergeSessionCart();

            return redirect()->intended('/dashboard');
        }

        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ]);
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration with strict data validation & password confirmation.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z\s\.\,\'\-]+$/'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:user,email'],
            'no_hp' => ['required', 'string', 'regex:/^(?:\+62|62|0)8[1-9][0-9]{6,11}$/', 'unique:user,no_hp'],
            'kode_pos' => ['required', 'string', 'regex:/^[0-9]{5}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama lengkap minimal terdiri dari 3 karakter.',
            'nama.regex' => 'Nama lengkap hanya boleh memuat huruf alfabet, spasi, dan tanda baca umum.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.',
            'no_hp.required' => 'Nomor handphone wajib diisi.',
            'no_hp.regex' => 'Format nomor HP tidak valid (contoh: 081234567890, diawali 08/628 dan terdiri dari 10-14 digit).',
            'no_hp.unique' => 'Nomor handphone ini sudah digunakan oleh akun lain.',
            'kode_pos.required' => 'Kode pos wilayah wajib diisi.',
            'kode_pos.regex' => 'Kode pos harus berupa 5 digit angka yang valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter demi keamanan.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi yang dimasukkan.',
        ]);

        $kopdes = \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', $request->kode_pos)->first();
        if (!$kopdes) {
            $prefix = substr($request->kode_pos, 0, 2);
            $kopdes = \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', 'LIKE', $prefix . '%')->first();
        }
        $idKopdes = $kopdes ? $kopdes->id_kopdes : null;

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'kode_pos' => $request->kode_pos,
            'id_kopdes' => $idKopdes,
            'id_role' => 3, // Default to 'user'
        ]);

        Auth::login($user);
        $this->mergeSessionCart();

        return redirect('/dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Koperasi Desa.');
    }

    /**
     * Merge session cart items to database cart.
     */
    protected function mergeSessionCart()
    {
        $user = Auth::user();
        if ($user->id_role != 3) {
            return; // Only merge for user role
        }
        
        $sessionCart = session()->get('cart', []);

        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $qty) {
                $product = \App\Models\Product::find($productId);
                if ($product) {
                    $cartItem = \App\Models\Cart::where('id_user', $user->id_user)
                        ->where('id_product', $productId)
                        ->first();

                    $newQty = $cartItem ? $cartItem->quantity + $qty : $qty;
                    $newQty = min($newQty, $product->stok);

                    if ($cartItem) {
                        $cartItem->update(['quantity' => $newQty]);
                    } else {
                        \App\Models\Cart::create([
                            'id_user' => $user->id_user,
                            'id_product' => $productId,
                            'quantity' => $newQty
                        ]);
                    }
                }
            }
            session()->forget('cart');
        }
    }

    /**
     * Update user profile & photo.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'kode_pos' => ['required', 'string', 'max:10'],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = [
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'kode_pos' => $request->kode_pos,
            'alamat' => $request->alamat,
        ];

        if ($request->hasFile('foto')) {
            if ($user->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('profiles', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil dan foto berhasil diperbarui!');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Look up the user by identifier (email or phone) and request password reset.
     */
    public function forgotPasswordLookup(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
        ]);

        $identifier = $request->identifier;

        // Find user by email or phone
        $user = \App\Models\User::where('email', $identifier)
            ->orWhere('no_hp', $identifier)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan. Harap periksa kembali Email atau Nomor HP Anda.'
            ], 404);
        }

        // Get user's KopDes
        $kopdes = $user->kopdes;
        if (!$kopdes && $user->kode_pos) {
            $kopdes = \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', $user->kode_pos)->first();
            if (!$kopdes) {
                $prefix = substr($user->kode_pos, 0, 2);
                $kopdes = \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', 'LIKE', $prefix . '%')->first();
            }
            if ($kopdes) {
                $user->update(['id_kopdes' => $kopdes->id_kopdes]);
            }
        }

        if (!$kopdes) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum terasosiasi dengan Koperasi Desa manapun. Silakan hubungi Administrator utama.'
            ], 404);
        }

        // Update reset request flag on user
        $user->update(['reset_requested' => true]);

        return response()->json([
            'success' => true,
            'kopdes' => $kopdes->nama_kopdes,
            'message' => 'Pengajuan reset password berhasil dikirim ke Koperasi "' . $kopdes->nama_kopdes . '". Silakan konfirmasi ke Manager Koperasi Anda untuk disetujui.'
        ]);
    }

    /**
     * Reset a member's password to 'kopdes123' (Manager permission).
     */
    public function resetMemberPassword($id)
    {
        $manager = Auth::user();
        if ($manager->id_role != 2) {
            abort(403, 'Unauthorized action.');
        }

        $user = \App\Models\User::findOrFail($id);

        // Ensure user belongs to the same KopDes
        if ($user->id_kopdes != $manager->id_kopdes) {
            abort(403, 'Unauthorized action.');
        }

        // Enforce: Cannot reset password without user's explicit request!
        if (!$user->reset_requested) {
            return back()->withErrors(['error' => 'Gagal: Anggota ini tidak mengajukan permintaan reset password.']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make('kopdes123'),
            'reset_requested' => false
        ]);

        return back()->with('success', 'Password anggota "' . $user->nama . '" berhasil direset menjadi "kopdes123"!');
    }
}
