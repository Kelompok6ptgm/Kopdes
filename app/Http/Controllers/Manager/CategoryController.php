<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $id_kopdes = Auth::user()->id_kopdes ?? 1;

        $category = new Category();
        $category->nama_kategori = $request->nama_kategori;
        $category->id_kopdes = $id_kopdes;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/categories'), $filename);
            $category->foto = $filename;
        }

        $category->save();

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->foto && file_exists(public_path('uploads/categories/' . $category->foto))) {
            unlink(public_path('uploads/categories/' . $category->foto));
        }

        $category->delete();
        
        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}