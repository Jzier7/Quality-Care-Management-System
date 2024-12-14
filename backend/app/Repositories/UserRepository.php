<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\HealthCareWorker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Patient;

class UserRepository extends JsonResponseFormat
{

    /**
     * Get the currently authenticated user with related data.
     *
     * @return array
     */
    public function me(): array
    {
        // Get the authenticated user
        $user = User::with(['role.abilities.route'])
            ->find(Auth::id());

        if ($user) {
            if ($user->role_id == 2) {
                $user->load('healthCareWorker.position');
            } elseif ($user->role_id == 3) {
                $user->load('patient');
            }

            return [
                'message' => 'User retrieved successfully',
                'body' => $user
            ];
        }

        return [
            'message' => 'User not found',
            'body' => null
        ];
    }

    /**
     * Update a user.
     *
     * @param array $data
     * @return array
     */
    public function updateWorker(array $data): array
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($data['id']);

            $user->update([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
            ]);

            $worker = HealthCareWorker::findOrFail($data['worker_id']);

            $worker->update([
                'license_number' => $data['license_number'],
                'department' => $data['department'],
                'position_id' => $data['position_id'],
                'shift_start_time' => $data['shift_start_time'],
                'shift_end_time' => $data['shift_end_time'],
            ]);

            DB::commit();
            return [
                'message' => 'User updated successfully',
                'body' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Update a user.
     *
     * @param array $data
     * @return array
     */
    public function updatePatient(array $data): array
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($data['id']);

            $user->update([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
            ]);

            $patient = Patient::findOrFail($data['patient_id']);

            $patient->update([
                'birthdate' => $data['birthdate'],
                'address' => $data['address'],
                'emergency_contact' => $data['emergency_contact'],
                'sex' => $data['sex'],
            ]);

            DB::commit();
            return [
                'message' => 'User updated successfully',
                'body' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Delete a user.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($data['id']);

            $user->delete();

            DB::commit();
            return [
                'message' => 'User deleted successfully',
                'body' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
