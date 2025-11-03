<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Tag;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Tenant::create([
            'id' => str()->uuid(),
            'name' => 'Qadran.io Main Tenant',
            'domain' => 'qadranio.com',
            'host' => 'qadranio',
            'database' => 'qadran_db',
            'org_id' => env('DEFAULT_ORG_ID'),
        ]);

        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            IndustrySeeder::class,
            TaskCategorySeeder::class,
            ActivityTypeSeeder::class,
            OrganizationSeeder::class,
            TagSeeder::class,
            ProjectSeeder::class,
            ProjectUserSeeder::class,
            RoleSeeder::class,
            RateTypeSeeder::class,
        ]);
    }
}
