<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserCatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_catalogues')->truncate();

        $data = [];
        $chunkSize = 100; // Insert in chunks for efficiency

        for ($i = 0; $i < 100000; $i++) {
            $data[] = [
                'name' => fake()->name(),
                'description' => fake()->paragraph(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert data in chunks 
            if (($i + 1) % $chunkSize === 0) {
                DB::table('user_catalogues')->insert($data);
                $data = []; // Reset for the next chunk
            }
        }
        // Insert any remaining records
        if (!empty($data)) {
            DB::table('user_catalogues')->insert($data);
        }
    }
}
