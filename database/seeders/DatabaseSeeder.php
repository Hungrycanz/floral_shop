<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            OccasionSeeder::class,
            BouquetOptionSeeder::class,
            DeliveryZoneSeeder::class,
            ProductSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Shop Admin',
            'email' => 'admin@floralshop.test',
            'phone' => '+256700000001',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Demo Customer',
            'email' => 'customer@floralshop.test',
            'phone' => '+256700000002',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Demo Courier',
            'email' => 'courier@floralshop.test',
            'phone' => '+256700000003',
            'role' => 'courier',
            'password' => bcrypt('password'),
        ]);
    }
}
