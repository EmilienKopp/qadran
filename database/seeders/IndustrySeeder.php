<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industries = [
            'Agriculture',
            'Automotive',
            'Banking',
            'Construction',
            'Education',
            'Electronics',
            'Energy',
            'Entertainment',
            'Finance',
            'Food & Beverage',
            'Healthcare',
            'Hospitality',
            'Insurance',
            'IT',
            'Manufacturing',
            'Media',
            'Mining, Oil & Gas',
            'Network',
            'Pharmaceutical',
            'Real Estate',
            'Retail',
            'Technology',
            'Telecommunications',
            'Transportation',
            'Utilities',
            'Other',
        ];

        foreach ($industries as $industry) {
            \App\Models\Industry::create(['name' => $industry]);
        }
    }
}
