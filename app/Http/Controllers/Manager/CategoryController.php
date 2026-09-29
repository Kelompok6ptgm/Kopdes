<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private function idKopdes()
    {
        $manager = Auth::user();
        if (!$manager || $manager->id_role != 2) {
            abort(403, 'Unauthorized.');
        }
        if (!$manager->id_kopdes) {
            abort(403, 'Anda belum ditugaskan untuk mengelola KopDes manapun.');
        }
        return $manager->id_kopdes;
    }

    public function index()
    {
        $id_kopdes = $this->idKopdes();
        $categories = Category::where('id_kopdes', $id_kopdes)->get();
        return view('manager.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->idKopdes();
        return view('manager.categories.create');
    }

    public function store(Request $request)
    {
        $id_kopdes = $this->idKopdes();

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $category = new Category();
        $category->nama_kategori = $request->nama_kategori;
        $category->id_kopdes = $id_kopdes;
        $category->save();

        return redirect()->route('manager.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $id_kopdes = $this->idKopdes();
        $category = Category::where('id_kopdes', $id_kopdes)->findOrFail($id);
        return view('manager.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $id_kopdes = $this->idKopdes();
        $category = Category::where('id_kopdes', $id_kopdes)->findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $category->nama_kategori = $request->nama_kategori;
        $category->save();

        return redirect()->route('manager.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Cek apakah masih ada produk yang menggunakan kategori ini
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk!');
        }

        $category->delete();

        return redirect()->route('manager.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
