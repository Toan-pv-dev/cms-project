<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::create([
            'name' => 'Sample User', // Replace with a name if needed
            'email' => 'test@example2.com',
            'password' => '123456', // Replace 'password123' with your desired password
        ]);
    }
}
