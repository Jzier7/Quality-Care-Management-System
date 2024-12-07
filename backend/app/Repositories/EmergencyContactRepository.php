<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\EmergencyContact;
use App\Models\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\FileUploadService;

class EmergencyContactRepository extends JsonResponseFormat
{
    /**
     * Get paginated emergencyContacts.
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = EmergencyContact::with(['files']);

        if (!empty($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('updated_at', $params['orderBy']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $emergencyContacts = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($emergencyContacts->items());

        return [
            'message' => "{$retrievedCount} information Contacts retrieved successfully",
            'body' => $emergencyContacts->items(),
            'current_page' => $emergencyContacts->currentPage(),
            'from' => $emergencyContacts->firstItem(),
            'to' => $emergencyContacts->lastItem(),
            'last_page' => $emergencyContacts->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $emergencyContacts->total(),
        ];
    }

    /**
     * Get all emergency contacts
     *
     * @return array
     */
    public function retrieveAll(): array
    {
        $patients = EmergencyContact::select('id', 'name')
            ->with('files')
            ->get();

        return [
            'message' => 'All emergency contacts retrieved successfully',
            'body' => $patients,
            'total' => $patients->count(),
        ];
    }

    /**
     * Store a new emergencyContact.
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

            $emergencyContact = EmergencyContact::create($data);

            if ($file) {
                $fileUploadService = new FileUploadService();

                $file_size = $fileUploadService->getFileSize($file);
                $file_path = $fileUploadService->upload($file, 'emergencyContacts');
                $file_name = $file->getClientOriginalName();

                $new_file = new File();
                $new_file->path = $file_path;
                $new_file->size = $file_size;
                $new_file->name = $file_name;

                $emergencyContact->files()->save($new_file);
            }

            DB::commit();
            return [
                'message' => 'Emergency Contact created successfully',
                'body' => $emergencyContact,
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
            $emergencyContact = EmergencyContact::findOrFail($data['id']);

            if (!empty($emergencyContact->image_path)) {
                Storage::delete($emergencyContact->image_path);
            }

            $emergencyContact->delete();

            DB::commit();
            return [
                'message' => 'Emergency Contact and associated image deleted successfully',
                'body' => $emergencyContact,
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
