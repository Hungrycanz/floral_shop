<?php

namespace App\Services;

use App\Models\BouquetOption;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\OrderTrackingEvent;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderPlacementService
{
    public function placeOrder(User $user, array $items, array $deliveryData): Order
    {
        return DB::transaction(function () use ($user, $items, $deliveryData) {
            $deliveryZone = isset($deliveryData['delivery_zone_id'])
                ? DeliveryZone::find($deliveryData['delivery_zone_id'])
                : null;

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'placed',
                'delivery_zone_id' => $deliveryZone?->id,
                'recipient_name' => $deliveryData['recipient_name'] ?? $user->name,
                'recipient_phone' => $deliveryData['recipient_phone'],
                'delivery_address' => $deliveryData['delivery_address'],
                'delivery_date' => $deliveryData['delivery_date'],
                'card_message' => $deliveryData['card_message'] ?? null,
                'total_amount' => 0,
            ]);

            $itemsTotal = 0;

            foreach ($items as $line) {
                $product = Product::whereKey($line['product_id'])->lockForUpdate()->firstOrFail();

                if ($product->stock_quantity < $line['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Not enough stock for {$product->name} "
                            ."(have {$product->stock_quantity}, requested {$line['quantity']})",
                    ]);
                }

                $product->decrement('stock_quantity', $line['quantity']);

                $addons = $this->resolveAddons($line['addons'] ?? []);
                $addonTotal = (float) array_sum(array_column($addons, 'price'));
                $unitPrice = (float) $product->price;
                $subtotal = round(($unitPrice + $addonTotal) * $line['quantity'], 2);

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'addons' => $addons,
                ]);

                $itemsTotal += $subtotal;
            }

            $deliveryFee = $deliveryZone ? (float) $deliveryZone->price : 0;
            $total = round($itemsTotal + $deliveryFee, 2);

            $order->update(['total_amount' => $total]);

            $order->trackingEvents()->create([
                'status' => OrderTrackingEvent::STATUS_PLACED,
                'note' => 'Order placed',
            ]);

            $order->payments()->create([
                'method' => $deliveryData['payment_method'],
                'amount' => $total,
                'status' => 'pending',
            ]);

            return $order->fresh();
        });
    }

    private function resolveAddons(array $addons): array
    {
        if ($addons === []) {
            return [];
        }

        // Cart lines already carry name/price snapshots; accept them as-is.
        if (isset($addons[0]['price'])) {
            return $addons;
        }

        return BouquetOption::whereIn('id', $addons)
            ->where('is_active', true)
            ->get()
            ->map(fn (BouquetOption $option) => [
                'id' => $option->id,
                'name' => $option->name,
                'price' => (float) $option->price,
            ])
            ->all();
    }
}
