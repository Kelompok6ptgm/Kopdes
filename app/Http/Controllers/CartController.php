<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add an item to the cart.
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'id_product' => ['required', 'exists:product,id_product'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $productId = $request->id_product;
        $qty = $request->quantity ?? 1;

        $product = Product::findOrFail($productId);

        if ($qty > $product->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Stok produk tidak mencukupi. Tersedia: ' . $product->stok
            ], 422);
        }

        // ponytail: limit maximum quantity per item to prevent hoarders
        if ($qty > 10) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal pembelian adalah 10 barang per item.'
            ], 422);
        }

        if (Auth::check()) {
            $user = Auth::user();

            // Reject cross-KopDes mixing
            $existingKopdes = Cart::where('id_user', $user->id_user)
                ->whereHas('product', fn($q) => $q->where('id_kopdes', '!=', $product->id_kopdes))
                ->exists();
            if ($existingKopdes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mencampur barang dari koperasi yang berbeda. Kosongkan keranjang terlebih dahulu.'
                ], 422);
            }

            $cartItem = Cart::where('id_user', $user->id_user)
                ->where('id_product', $productId)
                ->first();

            $currentQty = $cartItem ? $cartItem->quantity : 0;
            $newQty = $currentQty + $qty;

            // ponytail: cap per-item at 10 total across all add-to-cart calls
            if ($newQty > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total barang di keranjang melebihi batas maksimal (10 per item).'
                ], 422);
            }

            if ($newQty > $product->stok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah di keranjang melebihi stok tersedia. Stok: ' . $product->stok
                ], 422);
            }

            if ($cartItem) {
                $cartItem->update(['quantity' => $newQty]);
            } else {
                Cart::create([
                    'id_user' => $user->id_user,
                    'id_product' => $productId,
                    'quantity' => $newQty,
                ]);
            }

            $cartCount = Cart::where('id_user', $user->id_user)->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            $currentQty = $cart[$productId] ?? 0;
            $newQty = $currentQty + $qty;

            if ($newQty > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total barang di keranjang melebihi batas maksimal (10 per item).'
                ], 422);
            }

            if ($newQty > $product->stok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah di keranjang melebihi stok tersedia. Stok: ' . $product->stok
                ], 422);
            }

            $cart[$productId] = $newQty;
            session()->put('cart', $cart);

            $cartCount = array_sum($cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke keranjang!',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Update quantity of an item in the cart.
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'id_product' => ['required', 'exists:product,id_product'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $productId = $request->id_product;
        $qty = $request->quantity;

        $product = Product::findOrFail($productId);

        if ($qty > $product->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Tersedia: ' . $product->stok
            ], 422);
        }

        if ($qty > 10) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal pembelian adalah 10 barang per item.'
            ], 422);
        }

        if (Auth::check()) {
            $user = Auth::user();
            $cartItem = Cart::where('id_user', $user->id_user)
                ->where('id_product', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->update(['quantity' => $qty]);
            }

            $cartCount = Cart::where('id_user', $user->id_user)->sum('quantity');
            $cartTotal = Cart::where('id_user', $user->id_user)
                ->get()
                ->sum(fn($item) => $item->product->harga * $item->quantity);
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId] = $qty;
                session()->put('cart', $cart);
            }

            $cartCount = array_sum($cart);
            
            $products = Product::whereIn('id_product', array_keys($cart))->get();
            $cartTotal = $products->sum(fn($p) => $p->harga * $cart[$p->id_product]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kuantitas berhasil diperbarui!',
            'cart_count' => $cartCount,
            'item_subtotal' => 'Rp ' . number_format($product->harga * $qty, 0, ',', '.'),
            'cart_total' => 'Rp ' . number_format($cartTotal, 0, ',', '.')
        ]);
    }

    /**
     * Remove an item from the cart.
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'id_product' => ['required', 'exists:product,id_product'],
        ]);

        $productId = $request->id_product;

        if (Auth::check()) {
            $user = Auth::user();
            Cart::where('id_user', $user->id_user)
                ->where('id_product', $productId)
                ->delete();

            $cartCount = Cart::where('id_user', $user->id_user)->sum('quantity');
            $cartTotal = Cart::where('id_user', $user->id_user)
                ->get()
                ->sum(fn($item) => $item->product->harga * $item->quantity);
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }

            $cartCount = array_sum($cart);
            $products = Product::whereIn('id_product', array_keys($cart))->get();
            $cartTotal = $products->sum(fn($p) => $p->harga * $cart[$p->id_product]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang dihapus dari keranjang!',
            'cart_count' => $cartCount,
            'cart_total' => 'Rp ' . number_format($cartTotal, 0, ',', '.')
        ]);
    }
}
