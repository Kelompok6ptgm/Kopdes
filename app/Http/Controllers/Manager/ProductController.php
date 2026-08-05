<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product; 
use App\Models\Category; // Pastikan Model Category di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // 1. Menampilkan daftar produk
    public function index()
    {
        $id_kopdes = Auth::user()->id_kopdes ?? 1; 
        $products = Product::where('id_kopdes', $id_kopdes)->get();
        return view('manager.products.index', compact('products'));
    }

    // 2. Fungsi untuk nampilin halaman Form Tambah Produk
    public function create()
    {
        $id_kopdes = Auth::user()->id_kopdes ?? 1;
        // Ambil kategori milik KopDes ini aja buat pilihan di form
        $categories = Category::where('id_kopdes', $id_kopdes)->get(); 
        
        return view('manager.products.create', compact('categories'));
    }

    // 3. Fungsi untuk memproses data dari form ke Database
    public function store(Request $request)
    {
        // Validasi inputan biar data yang masuk nggak ngawur
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_category' => 'required|integer',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'deskripsi'   => 'nullable|string'
        ]);

        $id_kopdes = Auth::user()->id_kopdes ?? 1;

        // Simpan ke database sesuai struktur teman lo
        Product::create([
            'id_kopdes'   => $id_kopdes,
            'id_category' => $request->id_category,
            'nama_produk' => $request->nama_produk,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
            'deskripsi'   => $request->deskripsi,
            // Nanti kalau ada gambar, fitur uploadnya bisa kita tambahin menyusul
        ]);

        return redirect()->route('manager.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // 4. Fungsi untuk melihat detail 1 produk (opsional)
    public function show($id)
    {
        $id_kopdes = Auth::user()->id_kopdes ?? 1;
        
        // findOrFail dengan where id_kopdes agar aman tidak diintip kopdes lain
        $product = Product::where('id_kopdes', $id_kopdes)->findOrFail($id);
        
        return view('manager.products.show', compact('product'));
    }

    // 5. Fungsi untuk nampilin form Edit Produk
    public function edit($id)
    {
        $id_kopdes = Auth::user()->id_kopdes ?? 1;
        
        // Ambil data produk yang mau diedit
        $product = Product::where('id_kopdes', $id_kopdes)->findOrFail($id);
        
        // Ambil data kategori untuk pilihan dropdown di form edit
        $categories = Category::where('id_kopdes', $id_kopdes)->get();
        
        return view('manager.products.edit', compact('product', 'categories'));
    }

    // 6. Fungsi untuk memproses update data ke database
    public function update(Request $request, $id)
    {
        // Validasi inputan
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_category' => 'required|integer',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'deskripsi'   => 'nullable|string'
        ]);

        $id_kopdes = Auth::user()->id_kopdes ?? 1;
        
        // Cari produk berdasarkan ID dan pastikan miliknya KopDes tersebut
        $product = Product::where('id_kopdes', $id_kopdes)->findOrFail($id);

        // Update data
        $product->update([
            'id_category' => $request->id_category,
            'nama_produk' => $request->nama_produk,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
            'deskripsi'   => $request->deskripsi,
        ]);

        return redirect()->route('manager.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // 7. Fungsi untuk menghapus produk
    public function destroy($id)
    {
        $id_kopdes = Auth::user()->id_kopdes ?? 1;
        
        // Cari produk dan hapus
        $product = Product::where('id_kopdes', $id_kopdes)->findOrFail($id);
        $product->delete();

        return redirect()->route('manager.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}