<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\Retrieve;
use App\Http\Requests\Schedule\Store;
use App\Http\Requests\Schedule\Delete;
use App\Repositories\ScheduleRepository;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
{
    /**
     * @var App\Repositories\ScheduleRepository
     */
    protected $scheduleRepository;
    public function __construct()
    {
        $this->scheduleRepository = new ScheduleRepository;
    }

    /**
     * Retrieves all schedule.
     *
     * @return Illuminate\Http\JsonResponse The schedule data in JSON format.
     */
    public function retrievePatient(Retrieve $request): JsonResponse
    {
        $data = $request->validated();
        $user = auth()->user();
        $patient = $user->patient;

        $data['patient'] = $patient->id;

        $response = $this->scheduleRepository->retrievePatient($data);
        return $this->scheduleRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all schedule.
     *
     * @return Illuminate\Http\JsonResponse The schedule data in JSON format.
     */
    public function retrieveWorker(Retrieve $request): JsonResponse
    {
        $data = $request->validated();
        $user = auth()->user();
        $healthcareWorker = $user->healthCareWorker;

        $data['healthcare_worker'] = $healthcareWorker->id;

        $response = $this->scheduleRepository->retrieveWorker($data);
        return $this->scheduleRepository->getJsonResponse($response);
    }

    /**
     * Add a schedule.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = $request->validated();
        $user = auth()->user();
        $healthcareWorker = $user->healthCareWorker;

        $data['healthcare_worker_id'] = $healthcareWorker->id;

        $response = $this->scheduleRepository->store($data);
        return $this->scheduleRepository->getJsonResponse($response);
    }

    /**
     * Delete a schedule status.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->scheduleRepository->delete($data);
        return $this->scheduleRepository->getJsonResponse($response);
    }
}
