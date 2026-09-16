<?php

namespace App\Http\Controllers\Courier;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::where('courier_id', $request->user()->id)
            ->whereIn('status', [
                OrderStatus::Placed->value,
                OrderStatus::Confirmed->value,
                OrderStatus::OutForPickup->value,
                OrderStatus::OutForDelivery->value,
            ])
            ->with(['user', 'deliveryZone'])
            ->orderByDesc('order_date')
            ->get();

        $completedToday = Order::where('courier_id', $request->user()->id)
            ->where('status', OrderStatus::Delivered->value)
            ->whereDate('delivered_at', today())
            ->count();

        return view('courier.deliveries.index', compact('orders', 'completedToday'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->courier_id === auth()->id(), 403);

        $order->load(['items.product', 'user', 'deliveryZone', 'trackingEvents']);

        return view('courier.deliveries.show', ['order' => $order]);
    }

    public function markStatus(Request $request, Order $order)
    {
        abort_unless($order->courier_id === auth()->id(), 403);

        $request->validate([
            'status' => [
                'required',
                Rule::enum(OrderStatus::class),
                Rule::in([
                    OrderStatus::Confirmed->value,
                    OrderStatus::OutForPickup->value,
                    OrderStatus::OutForDelivery->value,
                    OrderStatus::Delivered->value,
                ]),
            ],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $status = $request->input('status');

        $order->update(['status' => $status]);

        $order->trackingEvents()->create([
            'status' => $status,
            'location' => $request->input('location'),
            'note' => 'Updated by courier',
        ]);

        if ($status === OrderStatus::Delivered->value) {
            $order->update(['delivered_at' => now()]);

            $order->latestPayment()->update(['status' => 'completed', 'paid_at' => now()]);
        }

        return back()->with('success', 'Status updated.');
    }
}
