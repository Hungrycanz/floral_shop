<?php

namespace Database\Seeders;

use App\Models\Occasion;
use Illuminate\Database\Seeder;

class OccasionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Birthday', 'Wedding', 'Anniversary', 'Valentine', 'Mother\'s Day', 'Sympathy'] as $name) {
            Occasion::create(['name' => $name]);
        }
    }
}
