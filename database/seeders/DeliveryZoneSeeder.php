<?php

namespace Database\Seeders;

use App\Models\DeliveryZone;
use Illuminate\Database\Seeder;

class DeliveryZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            ['name' => 'City Centre', 'price' => 5.00],
            ['name' => 'Suburbs', 'price' => 10.00],
            ['name' => 'Outskirts', 'price' => 15.00],
            ['name' => 'Nearby Towns', 'price' => 25.00],
        ];

        foreach ($zones as $zone) {
            DeliveryZone::firstOrCreate(['name' => $zone['name']], $zone);
        }
    }
}
