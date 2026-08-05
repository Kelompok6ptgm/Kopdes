<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // 1. Tampilkan daftar kategori
    public function index()
    {
        $id_kopdes = Auth::user()->id_kopdes ?? 1;
        $categories = Category::where('id_kopdes', $id_kopdes)->get();
        return view('manager.categories.index', compact('categories'));
    }

    // 2. Form tambah kategori
    public function create()
    {
        return view('manager.categories.create');
    }

    // 3. Simpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $id_kopdes = Auth::user()->id_kopdes ?? 1;

        Category::create([
            'id_kopdes' => $id_kopdes,
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('manager.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // 4. Form edit kategori
    public function edit($id)
    {
        $id_kopdes = Auth::user()->id_kopdes ?? 1;
        
        // Asumsi primary key di tabel kategori adalah 'id_category'
        $category = Category::where('id_kopdes', $id_kopdes)->where('id_category', $id)->firstOrFail();
        
        return view('manager.categories.edit', compact('category'));
    }

    // 5. Simpan perubahan kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $id_kopdes = Auth::user()->id_kopdes ?? 1;
        
        $category = Category::where('id_kopdes', $id_kopdes)->where('id_category', $id)->firstOrFail();
        
        $category->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('manager.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // 6. Hapus kategori
    public function destroy($id)
    {
        $id_kopdes = Auth::user()->id_kopdes ?? 1;
        
        $category = Category::where('id_kopdes', $id_kopdes)->where('id_category', $id)->firstOrFail();
        $category->delete();

        return redirect()->route('manager.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}