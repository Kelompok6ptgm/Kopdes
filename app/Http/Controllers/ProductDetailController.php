<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kopdes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDetailController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['kopdes', 'category', 'reviews.user'])->findOrFail($id);

        $avgRating = $product->reviews->avg('rating');
        $reviewCount = $product->reviews->count();

        // For cart add button: check if user already has this in cart
        $cartQty = 0;
        $user = Auth::user();
        if ($user) {
            $cartItem = \App\Models\Cart::where('id_user', $user->id_user)
                ->where('id_product', $id)
                ->first();
            $cartQty = $cartItem ? $cartItem->quantity : 0;
        } else {
            $cart = session()->get('cart', []);
            $cartQty = $cart[$id] ?? 0;
        }

        return view('products.show', compact('product', 'avgRating', 'reviewCount', 'cartQty'));
    }
}
