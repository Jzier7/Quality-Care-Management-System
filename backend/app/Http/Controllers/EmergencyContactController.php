<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmergencyContact\Retrieve;
use App\Http\Requests\EmergencyContact\Store;
use App\Http\Requests\EmergencyContact\Delete;
use App\Repositories\EmergencyContactRepository;
use Illuminate\Http\JsonResponse;

class EmergencyContactController extends Controller
{
    /**
     * @var App\Repositories\EmergencyContactRepository
     */
    protected $emergencyContactRepository;
    public function __construct()
    {
        $this->emergencyContactRepository = new EmergencyContactRepository;
    }

    /**
     * Retrieves paginated emergencyContact.
     *
     * @return Illuminate\Http\JsonResponse The emergencyContact data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        $response = $this->emergencyContactRepository->retrievePaginate($params);
        return $this->emergencyContactRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all emergencyContact.
     *
     * @return Illuminate\Http\JsonResponse The emergencyContact data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->emergencyContactRepository->retrieveAll();
        return $this->emergencyContactRepository->getJsonResponse($response);
    }

    /**
     * Add a emergencyContact.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = $request->validated();

        $response = $this->emergencyContactRepository->store($data);
        return $this->emergencyContactRepository->getJsonResponse($response);
    }

    /**
     * Delete a emergencyContact status.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->emergencyContactRepository->delete($data);
        return $this->emergencyContactRepository->getJsonResponse($response);
    }
}
