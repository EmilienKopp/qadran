<?php

namespace Database\Seeders\Landlord;

use App\Models\Landlord\Tenant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Landlord\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            Tenant::create([
                'id' => str()->uuid(),
                'name' => 'Qadran.io Main Tenant',
                'domain' => 'qadranio.com',
                'host' => 'qadranio',
                'database' => 'qadran_db',
                'org_id' => env('DEFAULT_ORG_ID', 'org_01K7JKS4WYF6S3EFHQ4J0XJW89'),
            ]);

            User::factory()->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        });
    }
}
