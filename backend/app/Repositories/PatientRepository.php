<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PatientRepository extends JsonResponseFormat
{
    /**
     * Get paginated patient
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = Patient::with(['user', 'medical_records']);

        if (!empty($params['search'])) {
            $query->whereHas('user', function ($query) use ($params) {
                $query->where('first_name', 'like', '%' . $params['search'] . '%')
                    ->orWhere('last_name', 'like', '%' . $params['search'] . '%')
                    ->orWhere('middle_name', 'like', '%' . $params['search'] . '%');
            });
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('created_at', $params['orderBy']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $patient = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($patient->items());

        return [
            'message' => "{$retrievedCount} patient retrieved successfully",
            'body' => $patient->items(),
            'current_page' => $patient->currentPage(),
            'from' => $patient->firstItem(),
            'to' => $patient->lastItem(),
            'last_page' => $patient->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $patient->total(),
        ];
    }

    /**
     * Get all patients
     *
     * @return array
     */
    public function retrieveAll(): array
    {
        $patients = Patient::select('id', 'name')->get();

        return [
            'message' => 'All healthcare workers retrieved successfully',
            'body' => $patients,
            'total' => $patients->count(),
        ];
    }

    /**
     * Add a patient.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {
            $password = Str::random(10);

            $user = User::create([
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'password' => Hash::make($password),
            ]);

            $patient = [
                'user_id' => $user->id,
                'birthdate' => $data['birthdate'] ?? null,
                'address' => $data['address'] ?? null,
                'emergency_contact' => $data['emergency_contact'] ?? null,
                'sex' => $data['sex'] ?? null,
            ];

            Patient::create($patient);

            DB::commit();

            return [
                'message' => 'Patient added successfully',
                'body' => $password,
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
     * Update a patient.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $patient = Patient::findOrFail($data['id']);
            $user = $patient->user;

            $user->update([
                'first_name' => $data['first_name'] ?? $user->first_name,
                'middle_name' => $data['middle_name'] ?? $user->middle_name,
                'last_name' => $data['last_name'] ?? $user->last_name,
            ]);

            $patient->update([
                'birthdate' => $data['birthdate'] ?? $patient->birthdate,
                'address' => $data['address'] ?? $patient->address,
                'emergency_contact' => $data['emergency_contact'] ?? $patient->emergency_contact,
                'sex' => $data['sex'] ?? $patient->sex,
            ]);

            DB::commit();

            return [
                'message' => 'Patient updated successfully',
                'body' => $patient,
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
     * Update a patient status.
     *
     * @param array $data
     * @return array
     */
    public function updateStatus(array $data): array
    {
        DB::beginTransaction();
        try {
            $patient = Patient::findOrFail($data['id']);

            $patient->update([
                'status' => $data['status'] ?? $patient->status,
            ]);

            DB::commit();

            return [
                'message' => 'Patient updated successfully',
                'body' => $patient,
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
     * Delete a patient.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $patient = User::findOrFail($data['id']);

            $patient->delete();

            DB::commit();
            return [
                'message' => 'Patient deleted successfully',
                'body' => $patient,
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
