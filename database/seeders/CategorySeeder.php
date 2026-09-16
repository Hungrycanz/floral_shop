<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Bouquets', 'Wreaths', 'Potted Plants', 'Arrangements'] as $name) {
            Category::create(['name' => $name]);
        }
    }
}
