<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    // protected static ?string $hashedPassword = null;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('defaultPassword123'), // Default hashed password
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
