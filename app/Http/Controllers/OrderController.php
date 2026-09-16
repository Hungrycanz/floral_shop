<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->orderByDesc('order_date')
            ->paginate(15);

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless(
            $order->user_id === $request->user()->id || $request->user()->isAdmin(),
            403,
        );

        $order->load([
            'items.product',
            'payments',
            'user',
            'courier',
            'deliveryZone',
            'trackingEvents',
        ]);

        return view('orders.show', ['order' => $order]);
    }

    public function reorder(Order $order, CartService $cart): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);

        foreach ($order->items as $item) {
            if ($item->product && $item->product->is_active && $item->product->stock_quantity >= $item->quantity) {
                $cart->add($item->product, $item->quantity, $item->addons ?? []);
            }
        }

        return redirect()->route('cart.index')
            ->with('success', 'Items added to your cart from previous order.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        abort_unless(
            $order->user_id === auth()->id()
            && in_array($order->status, ['placed', 'confirmed'], true),
            403,
        );

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);

            $order->trackingEvents()->create([
                'status' => 'cancelled',
                'note' => 'Cancelled by customer',
            ]);
        });

        return back()->with('success', 'Order cancelled. Stock restored.');
    }
}
