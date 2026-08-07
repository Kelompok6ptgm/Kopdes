<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_category' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $id_kopdes = Auth::user()->id_kopdes ?? 1;

        $product = new Product();
        $product->nama_produk = $request->nama_produk;
        $product->id_category = $request->id_category;
        $product->harga = $request->harga;
        $product->stok = $request->stok;
        $product->deskripsi = $request->deskripsi;
        $product->id_kopdes = $id_kopdes;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $product->foto = $filename;
        }

        $product->save();

        return redirect()->back()->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail();
        
        if ($product->foto && file_exists(public_path('uploads/products/' . $product->foto))) {
            unlink(public_path('uploads/products/' . $product->foto));
        }
        
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}