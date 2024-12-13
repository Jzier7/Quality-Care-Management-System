<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\User;
use App\Models\Patient;
use App\Models\HealthCareWorker;
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

        $admittedCount = Patient::where('status', 'Admitted')->count();
        $dischargedCount = Patient::where('status', 'Discharged')->count();
        $underTreatmentCount = Patient::where('status', 'Under Treatment')->count();
        $transferredCount = Patient::where('status', 'Transferred')->count();

        $doctorsCount = HealthCareWorker::where('position', 'Doctor')->count();
        $nursesCount = HealthCareWorker::where('position', 'Nurse')->count();

        return [
            'message' => 'All dashboard data retrieved successfully',
            'body' => [
                'users' => $users,
                'patients' => [
                    'total' => $admittedCount + $dischargedCount + $underTreatmentCount + $transferredCount,
                    'admitted' => $admittedCount,
                    'discharged' => $dischargedCount,
                    'under_treatment' => $underTreatmentCount,
                    'transferred' => $transferredCount,
                ],
                'healthcare_worker' => [
                    'total' => $doctorsCount + $nursesCount,
                    'doctor' => $doctorsCount,
                    'nurse' => $nursesCount,
                ]
            ]
        ];
    }
}
