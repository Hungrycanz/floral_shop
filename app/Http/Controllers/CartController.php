<?php

namespace App\Http\Controllers;

use App\Models\BouquetOption;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartService $cart): View
    {
        return view('cart.index', [
            'lines' => $cart->lines(),
            'total' => $cart->total(),
            'count' => $cart->count(),
        ]);
    }

    public function add(Request $request, CartService $cart): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'addon_ids' => ['array'],
            'addon_ids.*' => ['integer', 'exists:bouquet_options,id'],
        ]);

        $product = Product::findOrFail($request->input('product_id'));
        $quantity = $request->integer('quantity', 1);
        $addonIds = $request->input('addon_ids', []);

        $addons = [];
        if ($addonIds !== []) {
            $addons = BouquetOption::whereIn('id', $addonIds)
                ->where('is_active', true)
                ->get()
                ->map(fn (BouquetOption $o) => ['id' => $o->id, 'name' => $o->name, 'price' => (float) $o->price])
                ->all();
        }

        $cart->add($product, $quantity, $addons);

        return redirect()->route('cart.index')
            ->with('success', "{$product->name} added to your cart.");
    }

    public function update(Request $request, string $lineId, CartService $cart): RedirectResponse
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:10']]);

        $cart->updateQuantity($lineId, $request->integer('quantity'));

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(string $lineId, CartService $cart): RedirectResponse
    {
        $cart->remove($lineId);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
