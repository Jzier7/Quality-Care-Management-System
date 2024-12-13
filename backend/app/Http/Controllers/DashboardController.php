<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepository;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * @var App\Repositories\DashboardRepository
     */
    protected $dashboardRepository;
    public function __construct()
    {
        $this->dashboardRepository = new DashboardRepository;
    }

    /**
     * Retrieves all dashboard data.
     *
     * @return Illuminate\Http\JsonResponse The dashboard data in JSON format.
     */
    public function retrieve(): JsonResponse
    {

        $response = $this->dashboardRepository->retrieve();
        return $this->dashboardRepository->getJsonResponse($response);
    }
}
