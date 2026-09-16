<?php

namespace App\Http\Controllers;

use App\Models\DeliveryZone;
use App\Services\CartService;
use App\Services\OrderPlacementService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(CartService $cart): View
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', [
            'lines' => $cart->lines(),
            'total' => $cart->total(),
            'deliveryZones' => DeliveryZone::active()->orderBy('name')->get(),
        ]);
    }

    public function placeOrder(Request $request, CartService $cart, OrderPlacementService $placement)
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'delivery_address' => ['required', 'string', 'max:255'],
            'delivery_date' => ['required', 'date', 'after_or_equal:today'],
            'card_message' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:cash,mobile_money,card'],
            'delivery_zone_id' => ['nullable', 'integer', 'exists:delivery_zones,id'],
        ]);

        try {
            $order = $placement->placeOrder(
                $request->user(),
                $cart->itemsForOrder(),
                $validated,
            );

            $cart->clear();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }
    }
}
