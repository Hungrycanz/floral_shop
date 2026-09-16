<?php

namespace Database\Seeders;

use App\Models\BouquetOption;
use Illuminate\Database\Seeder;

class BouquetOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            ['name' => 'Extra roses (x6)', 'price' => 8.00],
            ['name' => 'Chocolates box', 'price' => 12.00],
            ['name' => 'Birthday balloon set', 'price' => 6.50],
            ['name' => 'Gift card message', 'price' => 2.00],
            ['name' => 'Fancy wrapping', 'price' => 5.00],
            ['name' => 'Vase upgrade', 'price' => 15.00],
        ];

        foreach ($options as $option) {
            BouquetOption::firstOrCreate(['name' => $option['name']], $option);
        }
    }
}
