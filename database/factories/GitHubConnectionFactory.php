<?php

namespace Database\Factories;

use App\Models\GitHubConnection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GitHubConnectionFactory extends Factory
{
    protected $model = GitHubConnection::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'github_user_id' => $this->faker->numberBetween(1000000, 9999999),
            'username' => $this->faker->userName(),
            'access_token' => encrypt($this->faker->sha256()),
            'refresh_token' => encrypt($this->faker->sha256()),
            'token_expires_at' => now()->addDays(30),
        ];
    }
}
