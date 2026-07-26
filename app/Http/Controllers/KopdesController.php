<?php

namespace App\Http\Controllers;

use App\Models\Kopdes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KopdesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kopdes = Kopdes::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kopdes', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('no_telp', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest('id_kopdes')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kopdes.index', compact('kopdes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kopdes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kopdes' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:kopdes,email'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'status' => ['required', 'string', Rule::in(['aktif', 'nonaktif'])],
        ]);

        Kopdes::create($validated);

        return redirect()->route('admin.kopdes')->with('success', 'Data KopDes berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kopdes = Kopdes::findOrFail($id);
        return view('admin.kopdes.edit', compact('kopdes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kopdes = Kopdes::findOrFail($id);

        $validated = $request->validate([
            'nama_kopdes' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('kopdes', 'email')->ignore($kopdes->id_kopdes, 'id_kopdes')],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'status' => ['required', 'string', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $kopdes->update($validated);

        return redirect()->route('admin.kopdes')->with('success', 'Data KopDes berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kopdes = Kopdes::findOrFail($id);
        $kopdes->delete();

        return redirect()->route('admin.kopdes')->with('success', 'Data KopDes berhasil dihapus!');
    }
}
