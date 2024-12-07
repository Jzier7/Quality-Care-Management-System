<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicalRecord\Retrieve;
use App\Http\Requests\MedicalRecord\Store;
use App\Http\Requests\MedicalRecord\Delete;
use App\Repositories\MedicalRecordRepository;
use Illuminate\Http\JsonResponse;

class MedicalRecordController extends Controller
{
    /**
     * @var App\Repositories\MedicalRecordRepository
     */
    protected $medicalRecordRepository;
    public function __construct()
    {
        $this->medicalRecordRepository = new MedicalRecordRepository;
    }

    /**
     * Retrieves paginated medicalRecord.
     *
     * @return Illuminate\Http\JsonResponse The medicalRecord data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
            'patient' => $request->input('patient')
        ];

        $response = $this->medicalRecordRepository->retrievePaginate($params);
        return $this->medicalRecordRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all medicalRecord.
     *
     * @return Illuminate\Http\JsonResponse The medicalRecord data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->medicalRecordRepository->retrieveAll();
        return $this->medicalRecordRepository->getJsonResponse($response);
    }

    /**
     * Retrieves medical Records.
     *
     * @return Illuminate\Http\JsonResponse The medical record data in JSON format.
     */
    public function retrieve(): JsonResponse
    {
        $user = auth()->user();
        $patient = $user->patient;

        $id = $patient->id;

        $response = $this->medicalRecordRepository->retrieve($id);
        return $this->medicalRecordRepository->getJsonResponse($response);
    }

    /**
     * Add a medicalRecord.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = $request->validated();
        $user = auth()->user();
        $healthcareWorker = $user->healthCareWorker;

        $data['healthcare_worker_id'] = $healthcareWorker->id;

        $response = $this->medicalRecordRepository->store($data);
        return $this->medicalRecordRepository->getJsonResponse($response);
    }

    /**
     * Delete a medicalRecord status.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->medicalRecordRepository->delete($data);
        return $this->medicalRecordRepository->getJsonResponse($response);
    }
}
