<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Purok;
use Faker\Factory as Faker;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create an healthcare-worker user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'worker@worker.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a patient user
        User::create([
            'first_name' => 'Patient',
            'last_name' => 'User',
            'email' => 'patient@patient.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
