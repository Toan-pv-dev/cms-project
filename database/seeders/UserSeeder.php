<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->truncate();

        $data = [];
        $chunkSize = 100; // Insert in chunks for efficiency
        $hashedPassword = Hash::make('defaultPassword123'); // Pre-hashed password

        for ($i = 0; $i < 100000; $i++) {
            $data[] = [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'password' => $hashedPassword,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert data in chunks 
            if (($i + 1) % $chunkSize === 0) {
                DB::table('user')->insert($data);
                $data = []; // Reset for the next chunk
            }
        }
        // Insert any remaining records
        if (!empty($data)) {
            DB::table('user')->insert($data);
        }
    }
}
