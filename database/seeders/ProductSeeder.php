<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $bouquets = Category::where('name', 'Bouquets')->firstOrFail();
        $wreaths = Category::where('name', 'Wreaths')->firstOrFail();
        $potted = Category::where('name', 'Potted Plants')->firstOrFail();
        $arrangements = Category::where('name', 'Arrangements')->firstOrFail();

        $birthday = Occasion::where('name', 'Birthday')->firstOrFail();
        $wedding = Occasion::where('name', 'Wedding')->firstOrFail();
        $anniversary = Occasion::where('name', 'Anniversary')->firstOrFail();
        $valentine = Occasion::where('name', 'Valentine')->firstOrFail();
        $mothersDay = Occasion::where('name', 'Mother\'s Day')->firstOrFail();
        $sympathy = Occasion::where('name', 'Sympathy')->firstOrFail();

        $products = [
            [
                'category' => $bouquets,
                'name' => 'Classic Rose Bouquet',
                'description' => 'A dozen fresh red roses wrapped in kraft paper with a satin ribbon.',
                'price' => 45.00,
                'stock_quantity' => 24,
                'occasions' => [$anniversary, $valentine],
            ],
            [
                'category' => $bouquets,
                'name' => 'Sunshine Tulip Bouquet',
                'description' => 'Bright yellow and orange tulips that bring a smile to any room.',
                'price' => 32.00,
                'stock_quantity' => 18,
                'occasions' => [$birthday, $mothersDay],
            ],
            [
                'category' => $bouquets,
                'name' => 'Lily & Eucalyptus Wrap',
                'description' => 'White lilies with soft eucalyptus greenery for a fresh, elegant look.',
                'price' => 55.00,
                'stock_quantity' => 12,
                'occasions' => [$wedding, $sympathy],
            ],
            [
                'category' => $arrangements,
                'name' => 'Garden Party Centerpiece',
                'description' => 'A lush mixed arrangement of seasonal blooms in a ceramic vase.',
                'price' => 68.00,
                'stock_quantity' => 8,
                'occasions' => [$birthday, $anniversary],
            ],
            [
                'category' => $arrangements,
                'name' => 'Romance in Bloom',
                'description' => 'Peonies, ranunculus and spray roses styled low and romantic.',
                'price' => 79.00,
                'stock_quantity' => 10,
                'occasions' => [$valentine, $anniversary],
            ],
            [
                'category' => $potted,
                'name' => 'Orchid in Ceramic Pot',
                'description' => 'A graceful white phalaenopsis orchid, ready to bloom for months.',
                'price' => 42.00,
                'stock_quantity' => 15,
                'occasions' => [$birthday, $mothersDay],
            ],
            [
                'category' => $potted,
                'name' => 'Peace Lily',
                'description' => 'A classic low-maintenance peace lily that purifies the air.',
                'price' => 28.00,
                'stock_quantity' => 20,
                'occasions' => [$sympathy],
            ],
            [
                'category' => $wreaths,
                'name' => 'Eucalyptus & Rose Wreath',
                'description' => 'A round wreath of eucalyptus dotted with dried roses for the front door.',
                'price' => 39.00,
                'stock_quantity' => 6,
                'occasions' => [$wedding, $mothersDay],
            ],
            [
                'category' => $wreaths,
                'name' => 'Evergreen Sympathy Wreath',
                'description' => 'A calm evergreen wreath with white roses and soft ribbon accents.',
                'price' => 58.00,
                'stock_quantity' => 5,
                'occasions' => [$sympathy],
            ],
            [
                'category' => $bouquets,
                'name' => 'Daisy Posy',
                'description' => 'A cheerful hand-tied bundle of white daisies and gypsophila.',
                'price' => 22.00,
                'stock_quantity' => 30,
                'occasions' => [$birthday],
            ],
        ];

        foreach ($products as $product) {
            $record = Product::create([
                'category_id' => $product['category']->id,
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock_quantity' => $product['stock_quantity'],
            ]);

            $record->occasions()->attach($product['occasions']);
        }
    }
}
