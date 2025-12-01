<?php

namespace Database\Seeders;

use App\Models\RateType;
use Illuminate\Database\Seeder;

class RateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (\App\Enums\RateType::values() as $rateType) {
            RateType::create([
                'name' => $rateType,
            ]);
        }

    }
}
