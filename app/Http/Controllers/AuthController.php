<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $this->mergeSessionCart();

            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
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
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:user,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'kode_pos' => ['required', 'string', 'max:10'],
            'password' => ['required', 'string', 'min:8'],
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

        return redirect('/dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang.');
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
