<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $managers = User::query()
            ->where('id_role', 2) // Manager Role
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest('id_user')
            ->paginate(10)
            ->withQueryString();

        return view('admin.manager.index', compact('managers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.manager.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:user,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required', 'string', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $validated['id_role'] = 2; // Always Manager role
        $validated['password'] = Hash::make($request->password);

        User::create($validated);

        return redirect()->route('admin.manager')->with('success', 'Akun Manager berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $manager = User::where('id_role', 2)->findOrFail($id);
        return view('admin.manager.edit', compact('manager'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $manager = User::where('id_role', 2)->findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('user', 'email')->ignore($manager->id_user, 'id_user')],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'status' => ['required', 'string', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $manager->update($validated);

        return redirect()->route('admin.manager')->with('success', 'Akun Manager berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $manager = User::where('id_role', 2)->findOrFail($id);
        $manager->delete();

        return redirect()->route('admin.manager')->with('success', 'Akun Manager berhasil dihapus!');
    }

    /**
     * Reset the password for the specified manager.
     */
    public function resetPassword(Request $request, $id)
    {
        $manager = User::where('id_role', 2)->findOrFail($id);

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $manager->password = Hash::make($request->password);
        $manager->save();

        return redirect()->route('admin.manager')->with('success', "Password untuk manager {$manager->nama} berhasil direset!");
    }
}
