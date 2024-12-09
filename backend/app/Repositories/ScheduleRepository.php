<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ScheduleRepository extends JsonResponseFormat
{

    /**
     * Get schedules for a specific patient.
     *
     * @param array $params
     * @return array
     */
    public function retrievePatient(array $params): array
    {
        $query = Schedule::with(['patient.user'])
            ->where('patient_id', $params['patient']);

        $upcomingQuery = clone $query;
        $upcomingQuery->where('start', '>=', now());

        if (!empty($params['search'])) {
            $query->where('title', 'like', '%' . $params['search'] . '%');
            $upcomingQuery->where('title', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('updated_at', $params['orderBy']);
            $upcomingQuery->orderBy('updated_at', $params['orderBy']);
        }

        $allSchedules = $query->get();
        $upcomingSchedules = $upcomingQuery->get();

        return [
            'message' => "Schedules retrieved successfully",
            'body' => [
                'all_schedules' => $allSchedules,
                'upcoming_schedules' => $upcomingSchedules,
            ],
        ];
    }

    /**
     * Get schedules for a specific worker.
     *
     * @param array $params
     * @return array
     */
    public function retrieveWorker(array $params): array
    {
        $query = Schedule::with(['healthcare_worker.user', 'patient.user'])
            ->where('healthcare_worker_id', $params['healthcare_worker']);

        $upcomingQuery = clone $query;
        $upcomingQuery->where('start', '>=', now());

        if (!empty($params['search'])) {
            $query->where('title', 'like', '%' . $params['search'] . '%');
            $upcomingQuery->where('title', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('updated_at', $params['orderBy']);
            $upcomingQuery->orderBy('updated_at', $params['orderBy']);
        }

        $allSchedules = $query->get();
        $upcomingSchedules = $upcomingQuery->get();

        return [
            'message' => "Schedules retrieved successfully",
            'body' => [
                'all_schedules' => $allSchedules,
                'upcoming_schedules' => $upcomingSchedules,
            ],
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

            $schedule = Schedule::create($data);

            DB::commit();

            return [
                'message' => 'schedule added successfully',
                'body' => $schedule,
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
            $schedule = Schedule::findOrFail($data['id']);

            if (!empty($schedule->image_path)) {
                Storage::delete($schedule->image_path);
            }

            $schedule->delete();

            DB::commit();
            return [
                'message' => 'schedule deleted successfully',
                'body' => $schedule,
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
