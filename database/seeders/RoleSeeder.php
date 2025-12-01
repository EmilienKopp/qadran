<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (RoleEnum::values() as $role) {
            Role::create([
                'name' => $role,
            ]);
        }

        // Assign roles to users
        $users = User::all();
        $users->each(function ($user) {
            $user->assignRole(RoleEnum::User);
            if ($user->id === 1) {
                $user->assignRole(RoleEnum::Freelancer);
                $user->assignRole(RoleEnum::Employer);
            } else {
                $user->assignRole(RoleEnum::random());
            }
        });
    }
}
