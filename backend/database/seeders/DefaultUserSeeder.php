<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\HealthCareWorker;
use App\Models\Patient;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Create a healthcare-worker user
        $worker = User::create([
            'first_name' => 'Worker',
            'last_name' => 'User',
            'email' => 'worker@worker.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        HealthCareWorker::create([
            'user_id' => $worker->id,
            'license_number' => '12312-3424-123',
            'department' => 'OB-GYN',
            'shift_start_time' => now()->toTimeString(),
            'shift_end_time' => now()->addHours(8)->toTimeString(),
            'position' => 'Doctor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a patient user
        $patient = User::create([
            'first_name' => 'Patient',
            'last_name' => 'User',
            'email' => 'patient@patient.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a patient profile linked to the user
        Patient::create([
            'user_id' => $patient->id,
            'birthdate' => $faker->date(),
            'address' => $faker->address(),
            'emergency_contact' => $faker->phoneNumber(),
            'sex' => $faker->randomElement(['male', 'female']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
