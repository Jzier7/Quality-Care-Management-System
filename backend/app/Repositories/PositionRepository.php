<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PositionRepository extends JsonResponseFormat
{
    /**
     * Get paginated position
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = Position::query();

        if (!empty($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('created_at', $params['orderBy']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $position = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($position->items());

        return [
            'message' => "{$retrievedCount} position retrieved successfully",
            'body' => $position->items(),
            'current_page' => $position->currentPage(),
            'from' => $position->firstItem(),
            'to' => $position->lastItem(),
            'last_page' => $position->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $position->total(),
        ];
    }

    /**
     * Get all positions with their user's full name
     *
     * @return array
     */
    public function retrieveAll(): array
    {
        $positions = Position::select('id', 'name')->get();

        return [
            'message' => 'All positions retrieved successfully',
            'body' => $positions,
            'total' => $positions->count(),
        ];
    }

    /**
     * Add a position.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $position = [
                'name' => $data['name'] ?? null,
                'description' => $data['description'] ?? null,
            ];

            Position::create($position);

            DB::commit();

            return [
                'message' => 'Position added successfully',
                'body' => $position,
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
     * Update a position.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $position = Position::findOrFail($data['id']);

            $position->update([
                'name' => $data['name'] ?? $position->name,
                'description' => $data['description'] ?? $position->description,
            ]);

            DB::commit();

            return [
                'message' => 'Position updated successfully',
                'body' => $position,
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
     * Delete a position.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $position = Position::findOrFail($data['id']);

            $position->delete();

            DB::commit();
            return [
                'message' => 'Position deleted successfully',
                'body' => $position,
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
