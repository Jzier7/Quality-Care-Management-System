<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\User;
use App\Models\Patient;
use App\Models\HealthCareWorker;
use App\Models\Position;
use Carbon\Carbon;

class DashboardRepository extends JsonResponseFormat
{

    /**
     * Get all dashboard data
     *
     * @return array
     */
    public function retrieve(): array
    {
        $users = User::select('id', 'first_name', 'middle_name', 'last_name', 'role_id')
            ->with(['role:id,name'])
            ->whereDate('created_at', Carbon::today())
            ->where('role_id', '!=', 1)
            ->get();

        $totalPatients = Patient::count();
        $admittedCount = Patient::where('status', 'Admitted')->count();
        $dischargedCount = Patient::where('status', 'Discharged')->count();
        $underTreatmentCount = Patient::where('status', 'Under Treatment')->count();
        $transferredCount = Patient::where('status', 'Transferred')->count();

        $healthcareWorkers = HealthCareWorker::with('position:id,name')
            ->get()
            ->groupBy('position.name')
            ->map(function ($group) {
                return $group->count();
            });

        $positions = Position::select('name')
            ->get()
            ->pluck('name');

        $healthcareWorkerData = $positions->mapWithKeys(function ($position) use ($healthcareWorkers) {
            return [$position => $healthcareWorkers->get($position, 0)];
        })->toArray();

        return [
            'message' => 'All dashboard data retrieved successfully',
            'body' => [
                'users' => $users,
                'patients' => [
                    'total' => $totalPatients,
                    'admitted' => $admittedCount,
                    'discharged' => $dischargedCount,
                    'under_treatment' => $underTreatmentCount,
                    'transferred' => $transferredCount,
                ],
                'healthcare_worker' => [
                    'total' => array_sum($healthcareWorkerData),
                    'details' => $healthcareWorkerData,
                ],
            ]
        ];
    }
}
