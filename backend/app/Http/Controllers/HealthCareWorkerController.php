<?php

namespace App\Http\Controllers;

use App\Http\Requests\HealthCareWorker\Retrieve;
use App\Http\Requests\HealthCareWorker\Store;
use App\Http\Requests\HealthCareWorker\Update;
use App\Http\Requests\HealthCareWorker\Delete;
use App\Repositories\HealthCareWorkerRepository;
use Illuminate\Http\JsonResponse;

class HealthCareWorkerController extends Controller
{
    /**
     * @var App\Repositories\HealthCareWorkerRepository
     */
    protected $healthCareWorkerRepository;
    public function __construct()
    {
        $this->healthCareWorkerRepository = new HealthCareWorkerRepository;
    }

    /**
     * Retrieves paginated healthCareWorker.
     *
     * @return Illuminate\Http\JsonResponse The healthCareWorker data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        $response = $this->healthCareWorkerRepository->retrievePaginate($params);
        return $this->healthCareWorkerRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all healthCareWorker.
     *
     * @return Illuminate\Http\JsonResponse The healthCareWorker data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->healthCareWorkerRepository->retrieveAll();
        return $this->healthCareWorkerRepository->getJsonResponse($response);
    }

    /**
     * Add a healthCareWorker.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = $request->validated();

        $response = $this->healthCareWorkerRepository->store($data);
        return $this->healthCareWorkerRepository->getJsonResponse($response);
    }

    /**
     * Update a healthCareWorker.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = $request->validated();

        $response = $this->healthCareWorkerRepository->update($data);
        return $this->healthCareWorkerRepository->getJsonResponse($response);
    }

    /**
     * Delete a healthCareWorker.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->healthCareWorkerRepository->delete($data);
        return $this->healthCareWorkerRepository->getJsonResponse($response);
    }
}
