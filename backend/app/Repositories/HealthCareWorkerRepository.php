<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\HealthCareWorker;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class HealthCareWorkerRepository extends JsonResponseFormat
{
    /**
     * Get paginated healthCareWorker
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = HealthCareWorker::with('user');

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

        $healthCareWorker = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($healthCareWorker->items());

        return [
            'message' => "{$retrievedCount} healthCareWorker retrieved successfully",
            'body' => $healthCareWorker->items(),
            'current_page' => $healthCareWorker->currentPage(),
            'from' => $healthCareWorker->firstItem(),
            'to' => $healthCareWorker->lastItem(),
            'last_page' => $healthCareWorker->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $healthCareWorker->total(),
        ];
    }

    /**
     * Get all healthCareWorkers
     *
     * @return array
     */
    public function retrieveAll(): array
    {
        $healthCareWorkers = HealthCareWorker::select('id')
            ->with(['user' => function ($query) {
                $query->select('first_name', 'last_name');
            }])
            ->get();

        return [
            'message' => 'All healthcare workers retrieved successfully',
            'body' => $healthCareWorkers,
            'total' => $healthCareWorkers->count(),
        ];
    }

    /**
     * Add a healthCareWorker.
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

            $healthCareWorker = [
                'user_id' => $user->id,
                'license_number' => $data['license_number'] ?? null,
                'department' => $data['department'] ?? null,
                'shift_start_time' => $data['shift_start_time'] ?? null,
                'shift_end_time' => $data['shift_end_time'] ?? null,
                'position' => $data['position'] ?? null,
            ];

            HealthCareWorker::create($healthCareWorker);

            DB::commit();

            return [
                'message' => 'Healthcare Worker added successfully',
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
     * Update a healthCareWorker.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $healthCareWorker = HealthCareWorker::findOrFail($data['id']);
            $user = $healthCareWorker->user;

            $user->update([
                'first_name' => $data['first_name'] ?? $user->first_name,
                'middle_name' => $data['middle_name'] ?? $user->middle_name,
                'last_name' => $data['last_name'] ?? $user->last_name,
                'email' => $data['email'] ?? $user->email,
            ]);

            $healthCareWorker->update([
                'license_number' => $data['license_number'] ?? $healthCareWorker->license_number,
                'department' => $data['department'] ?? $healthCareWorker->department,
                'shift_start_time' => $data['shift_start_time'] ?? $healthCareWorker->shift_start_time,
                'shift_end_time' => $data['shift_end_time'] ?? $healthCareWorker->shift_end_time,
                'position' => $data['position'] ?? $healthCareWorker->position,
            ]);

            DB::commit();

            return [
                'message' => 'Healthcare Worker updated successfully',
                'body' => $healthCareWorker,
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
     * Delete a healthCareWorker.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $healthCareWorker = User::findOrFail($data['id']);

            $healthCareWorker->delete();

            DB::commit();
            return [
                'message' => 'Healthcare Worker deleted successfully',
                'body' => $healthCareWorker,
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
