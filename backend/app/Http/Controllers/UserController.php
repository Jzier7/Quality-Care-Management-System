<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\RetrieveUsers;
use App\Http\Requests\User\UpdateWorker;
use App\Http\Requests\User\UpdatePatient;
use App\Http\Requests\User\Delete;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    /**
     * @var App\Repositories\UserRepository
     */
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
    }

    /**
     * Retrieves the authenticated user's information.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function me(): JsonResponse
    {
        $response = $this->userRepository->me();
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Retrieves paginated users.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function retrieveUsers(RetrieveUsers $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
            'activeVoter' => $request->input('activeVoter'),
        ];

        $response = $this->userRepository->retrieveUsers($params);
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all users.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function retrievePlayers(): JsonResponse
    {
        $response = $this->userRepository->retrievePlayers();
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Update a user.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function updateWorker(UpdateWorker $request)
    {

        $data = [
            'id' => $request->input('id'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'worker_id' => $request->input('health_care_worker.id'),
            'license_number' => $request->input('health_care_worker.license_number'),
            'department' => $request->input('health_care_worker.department'),
            'position_id' => $request->input('health_care_worker.position_id'),
            'shift_start_time' => $request->input('health_care_worker.shift_start_time'),
            'shift_end_time' => $request->input('health_care_worker.shift_end_time'),
        ];

        $response = $this->userRepository->updateWorker($data);
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Update a user.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function updatePatient(UpdatePatient $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'patient_id' => $request->input('patient.id'),
            'birthdate' => $request->input('patient.birthdate'),
            'address' => $request->input('patient.address'),
            'emergency_contact' => $request->input('patient.emergency_contact'),
            'sex' => $request->input('patient.sex'),
        ];

        $response = $this->userRepository->updatePatient($data);
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Delete a user.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->userRepository->delete($data);
        return $this->userRepository->getJsonResponse($response);
    }
}
