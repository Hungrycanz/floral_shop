<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

class CartService
{
    public const SESSION_KEY = 'cart';

    public function lines(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function count(): int
    {
        return array_sum(array_column($this->lines(), 'quantity'));
    }

    public function total(): float
    {
        return (float) array_sum(array_column($this->lines(), 'line_total'));
    }

    public function add(Product $product, int $quantity, array $addons = []): string
    {
        $quantity = max(1, $quantity);
        $addonTotal = (float) array_sum(array_column($addons, 'price'));
        $lineTotal = round(((float) $product->price + $addonTotal) * $quantity, 2);

        $line = [
            'id' => (string) Str::ulid(),
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_price' => (float) $product->price,
            'quantity' => $quantity,
            'addons' => $addons,
            'line_total' => $lineTotal,
        ];

        $lines = $this->lines();
        $lines[] = $line;
        session([self::SESSION_KEY => $lines]);

        return $line['id'];
    }

    public function updateQuantity(string $lineId, int $quantity): void
    {
        $lines = $this->lines();

        foreach ($lines as &$line) {
            if ($line['id'] !== $lineId) {
                continue;
            }

            $line['quantity'] = max(1, $quantity);
            $line['line_total'] = round((float) $line['unit_price'] + $this->addonsTotal($line), 2) * $line['quantity'];
            $line['line_total'] = round($line['line_total'], 2);
        }

        session([self::SESSION_KEY => array_values($lines)]);
    }

    public function remove(string $lineId): void
    {
        $lines = array_values(array_filter(
            $this->lines(),
            fn (array $line) => $line['id'] !== $lineId,
        ));

        session([self::SESSION_KEY => $lines]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function itemsForOrder(): array
    {
        return collect($this->lines())->map(fn (array $line) => [
            'product_id' => (int) $line['product_id'],
            'quantity' => (int) $line['quantity'],
            'addons' => $line['addons'],
        ])->all();
    }

    private function addonsTotal(array $line): float
    {
        return (float) array_sum(array_column($line['addons'] ?? [], 'price'));
    }
}
