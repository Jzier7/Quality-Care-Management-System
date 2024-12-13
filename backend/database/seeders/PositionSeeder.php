<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
            [
                'name' => 'Doctor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nurse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
