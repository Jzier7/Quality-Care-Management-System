<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\InformationBoard;
use App\Models\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\FileUploadService;

class InformationBoardRepository extends JsonResponseFormat
{
    /**
     * Get paginated informationBoards.
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = InformationBoard::with(['files']);

        if (!empty($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('updated_at', $params['orderBy']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $informationBoards = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($informationBoards->items());

        return [
            'message' => "{$retrievedCount} information Boards retrieved successfully",
            'body' => $informationBoards->items(),
            'current_page' => $informationBoards->currentPage(),
            'from' => $informationBoards->firstItem(),
            'to' => $informationBoards->lastItem(),
            'last_page' => $informationBoards->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $informationBoards->total(),
        ];
    }

    /**
     * Get all information boards with files
     *
     * @return array
     */
    public function retrieveAll(): array
    {
        $boards = InformationBoard::select('id', 'name')
            ->with('files')
            ->get();

        return [
            'message' => 'All information boards retrieved successfully',
            'body' => $boards,
            'total' => $boards->count(),
        ];
    }

    /**
     * Store a new informationBoard.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {
            $file = $data['file'] ?? null;
            unset($data['file']);

            $informationBoard = InformationBoard::create($data);

            if ($file) {
                $fileUploadService = new FileUploadService();

                $file_size = $fileUploadService->getFileSize($file);
                $file_path = $fileUploadService->upload($file, 'informationBoards');
                $file_name = $file->getClientOriginalName();

                $new_file = new File();
                $new_file->path = $file_path;
                $new_file->size = $file_size;
                $new_file->name = $file_name;

                $informationBoard->files()->save($new_file);
            }

            DB::commit();
            return [
                'message' => 'Information Board created successfully',
                'body' => $informationBoard,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $informationBoard = InformationBoard::findOrFail($data['id']);

            if (!empty($informationBoard->image_path)) {
                Storage::delete($informationBoard->image_path);
            }

            $informationBoard->delete();

            DB::commit();
            return [
                'message' => 'Information Board and associated image deleted successfully',
                'body' => $informationBoard,
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
