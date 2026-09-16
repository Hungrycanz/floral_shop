<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'courier']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->whereHas('user', fn ($q) => $q->where('name', 'ilike', "%{$term}%"));
        }

        $orders = $query->orderByDesc('order_date')->paginate(20);

        return view('admin.orders.index', ['orders' => $orders]);
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'payments', 'user', 'courier', 'deliveryZone', 'trackingEvents']);

        $couriers = User::where('role', 'courier')->orderBy('name')->get();
        $zones = DeliveryZone::active()->orderBy('name')->get();

        return view('admin.orders.show', compact('order', 'couriers', 'zones'));
    }

    public function assignCourier(Request $request, Order $order)
    {
        $request->validate([
            'courier_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $order->update(['courier_id' => $request->input('courier_id')]);

        return back()->with('success', 'Courier assigned.');
    }

    public function assignZone(Request $request, Order $order)
    {
        $request->validate([
            'delivery_zone_id' => ['nullable', 'integer', 'exists:delivery_zones,id'],
        ]);

        $zone = DeliveryZone::find($request->input('delivery_zone_id'));
        $deliveryFee = $zone ? (float) $zone->price : 0;
        $itemsTotal = (float) $order->items->sum('subtotal');
        $order->update([
            'delivery_zone_id' => $request->input('delivery_zone_id'),
            'total_amount' => round($itemsTotal + $deliveryFee, 2),
        ]);

        return back()->with('success', 'Delivery zone updated.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:placed,confirmed,out_for_pickup,out_for_delivery,delivered,cancelled'],
        ]);

        $status = $request->input('status');

        $order->update(['status' => $status]);

        $order->trackingEvents()->create([
            'status' => $status,
            'note' => 'Updated by admin',
        ]);

        if ($status === 'delivered') {
            $order->update(['delivered_at' => now()]);

            $order->latestPayment()->update(['status' => 'completed', 'paid_at' => now()]);
        }

        return back()->with('success', 'Order status updated.');
    }
}
