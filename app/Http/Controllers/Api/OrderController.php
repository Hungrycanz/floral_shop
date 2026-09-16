<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Models\Order;
use App\Services\OrderPlacementService;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(PlaceOrderRequest $request, OrderPlacementService $placement)
    {
        $items = array_map(fn (array $line) => [
            'product_id' => (int) $line['product_id'],
            'quantity' => (int) $line['quantity'],
            'addons' => $line['addons'] ?? [],
        ], $request->input('items'));

        $deliveryData = [
            'recipient_name' => $request->input('recipient_name'),
            'recipient_phone' => $request->input('recipient_phone'),
            'delivery_address' => $request->input('delivery_address'),
            'delivery_date' => $request->input('delivery_date'),
            'card_message' => $request->input('card_message'),
            'payment_method' => $request->input('payment_method'),
            'delivery_zone_id' => $request->input('delivery_zone_id'),
        ];

        try {
            $order = $placement->placeOrder($request->user(), $items, $deliveryData);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return response()->json(
            $order->load(['items.product', 'payments', 'user']),
            201,
        );
    }

    public function show(Order $order)
    {
        return $order->load(['items.product', 'user', 'payments', 'courier', 'deliveryZone', 'trackingEvents']);
    }
}
