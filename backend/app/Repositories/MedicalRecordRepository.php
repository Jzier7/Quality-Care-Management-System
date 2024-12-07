<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\MedicalRecord;
use App\Models\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\FileUploadService;

class MedicalRecordRepository extends JsonResponseFormat
{
    /**
     * Get paginated medicalRecords.
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = MedicalRecord::with(['files']);

        if (!empty($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('updated_at', $params['orderBy']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $medicalRecords = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($medicalRecords->items());

        return [
            'message' => "{$retrievedCount} medical records retrieved successfully",
            'body' => $medicalRecords->items(),
            'current_page' => $medicalRecords->currentPage(),
            'from' => $medicalRecords->firstItem(),
            'to' => $medicalRecords->lastItem(),
            'last_page' => $medicalRecords->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $medicalRecords->total(),
        ];
    }

    /**
     * Get all medical records with files
     *
     * @return array
     */
    public function retrieveAll(): array
    {
        $records = MedicalRecord::select('id', 'name')
            ->with('files')
            ->get();

        return [
            'message' => 'All medical records retrieved successfully',
            'body' => $records,
            'total' => $records->count(),
        ];
    }

    /**
     * Store a new medicalRecord.
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

            $medicalRecord = MedicalRecord::create($data);

            if ($file) {
                $fileUploadService = new FileUploadService();

                $file_size = $fileUploadService->getFileSize($file);
                $file_path = $fileUploadService->upload($file, 'medicalRecords');
                $file_name = $file->getClientOriginalName();

                $new_file = new File();
                $new_file->path = $file_path;
                $new_file->size = $file_size;
                $new_file->name = $file_name;

                $medicalRecord->files()->save($new_file);
            }

            DB::commit();
            return [
                'message' => 'medical record created successfully',
                'body' => $medicalRecord,
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
            $medicalRecord = MedicalRecord::findOrFail($data['id']);

            if (!empty($medicalRecord->image_path)) {
                Storage::delete($medicalRecord->image_path);
            }

            $medicalRecord->delete();

            DB::commit();
            return [
                'message' => 'medical record deleted successfully',
                'body' => $medicalRecord,
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
