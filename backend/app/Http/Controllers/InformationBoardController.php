<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformationBoard\Retrieve;
use App\Http\Requests\InformationBoard\Store;
use App\Http\Requests\InformationBoard\Delete;
use App\Repositories\InformationBoardRepository;
use Illuminate\Http\JsonResponse;

class InformationBoardController extends Controller
{
    /**
     * @var App\Repositories\InformationBoardRepository
     */
    protected $informationBoardRepository;
    public function __construct()
    {
        $this->informationBoardRepository = new InformationBoardRepository;
    }

    /**
     * Retrieves paginated informationBoard.
     *
     * @return Illuminate\Http\JsonResponse The informationBoard data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        $response = $this->informationBoardRepository->retrievePaginate($params);
        return $this->informationBoardRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all informationBoard.
     *
     * @return Illuminate\Http\JsonResponse The informationBoard data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->informationBoardRepository->retrieveAll();
        return $this->informationBoardRepository->getJsonResponse($response);
    }

    /**
     * Add a informationBoard.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = $request->validated();

        $response = $this->informationBoardRepository->store($data);
        return $this->informationBoardRepository->getJsonResponse($response);
    }

    /**
     * Delete a informationBoard status.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->informationBoardRepository->delete($data);
        return $this->informationBoardRepository->getJsonResponse($response);
    }
}
