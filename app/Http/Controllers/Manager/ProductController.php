<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
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
        $products = Product::where('id_kopdes', $id_kopdes)->with('category')->get();
        return view('manager.products.index', compact('products'));
    }

    public function create()
    {
        $id_kopdes = $this->idKopdes();
        $categories = Category::where('id_kopdes', $id_kopdes)->get();
        return view('manager.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $id_kopdes = $this->idKopdes();

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_category' => 'required|exists:category,id_category',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

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
            $product->gambar = 'uploads/products/' . $filename;
        }

        $product->save();

        return redirect()->route('manager.products.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $id_kopdes = $this->idKopdes();
        $product = Product::where('id_kopdes', $id_kopdes)->findOrFail($id);
        $categories = Category::where('id_kopdes', $id_kopdes)->get();
        return view('manager.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $id_kopdes = $this->idKopdes();
        $product = Product::where('id_kopdes', $id_kopdes)->findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_category' => 'required|exists:category,id_category',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product->nama_produk = $request->nama_produk;
        $product->id_category = $request->id_category;
        $product->harga = $request->harga;
        $product->stok = $request->stok;
        $product->deskripsi = $request->deskripsi;

        if ($request->hasFile('foto')) {
            if ($product->gambar && file_exists(public_path($product->gambar))) {
                unlink(public_path($product->gambar));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $product->gambar = 'uploads/products/' . $filename;
        }

        $product->save();

        return redirect()->to(route('dashboard') . '#mgr-products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $id_kopdes = $this->idKopdes();
        $product = Product::where('id_kopdes', $id_kopdes)->findOrFail($id);

        if ($product->gambar && file_exists(public_path($product->gambar))) {
            unlink(public_path($product->gambar));
        }

        $product->delete();

        return redirect()->route('manager.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
