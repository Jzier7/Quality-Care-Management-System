<?php

namespace App\Http\Controllers;

use App\Http\Requests\Patient\Retrieve;
use App\Http\Requests\Patient\Store;
use App\Http\Requests\Patient\Update;
use App\Http\Requests\Patient\UpdateStatus;
use App\Http\Requests\Patient\Delete;
use App\Repositories\PatientRepository;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * @var App\Repositories\PatientRepository
     */
    protected $patientRepository;
    public function __construct()
    {
        $this->patientRepository = new PatientRepository;
    }

    /**
     * Retrieves paginated patient.
     *
     * @return Illuminate\Http\JsonResponse The patient data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        $response = $this->patientRepository->retrievePaginate($params);
        return $this->patientRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all patient.
     *
     * @return Illuminate\Http\JsonResponse The patient data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->patientRepository->retrieveAll();
        return $this->patientRepository->getJsonResponse($response);
    }

    /**
     * Add a patient.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = $request->validated();

        $response = $this->patientRepository->store($data);
        return $this->patientRepository->getJsonResponse($response);
    }

    /**
     * Update a patient.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = $request->validated();

        $response = $this->patientRepository->update($data);
        return $this->patientRepository->getJsonResponse($response);
    }

    /**
     * Update a patient.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function updateStatus(UpdateStatus $request): JsonResponse
    {

        $data = $request->validated();

        $response = $this->patientRepository->updateStatus($data);
        return $this->patientRepository->getJsonResponse($response);
    }

    /**
     * Delete a patient status.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->patientRepository->delete($data);
        return $this->patientRepository->getJsonResponse($response);
    }
}
