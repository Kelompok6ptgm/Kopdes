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

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'kode_pos' => $request->kode_pos,
            'id_role' => 3, // Default to 'user'
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang.');
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
}
