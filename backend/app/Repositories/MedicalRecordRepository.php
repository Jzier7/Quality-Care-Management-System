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
        $query = MedicalRecord::with(['healthcare_worker.user', 'patient.user']);

        $query->where('patient_id', $params['patient']);

        if (!empty($params['search'])) {
            $query->where('serial_number', 'like', '%' . $params['search'] . '%');
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
        $records = MedicalRecord::select('id', 'name')->get();

        return [
            'message' => 'All medical records retrieved successfully',
            'body' => $records,
            'total' => $records->count(),
        ];
    }

    /**
     * Get the latest medical record with healthcare worker and patient details
     *
     * @return array
     */
    public function retrieve(int $id): array
    {
        $record = MedicalRecord::with('healthcare_worker.user', 'patient.user')
            ->where('patient_id', $id)
            ->latest()
            ->first();

        return [
            'message' => 'Latest medical record retrieved successfully',
            'body' => $record ? [$record] : [],
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

            $medicalRecord = MedicalRecord::create($data);

            DB::commit();

            return [
                'message' => 'Medical Record added successfully',
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
