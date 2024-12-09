<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\ForgotPassword;
use App\Http\Requests\Auth\UpdatePassword;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\AuthRepository;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    /**
     * @var App\Repositories\AuthRepository
     */
    protected $authRepository;

    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    /**
     * Handles login requests
     *
     * @param array $request
     * @return Illuminate\Http\Response
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        $response = $this->authRepository->login($data);
        return $this->authRepository->getJsonResponse($response);
    }

    /**
     * Handles logout requests
     *
     * @param array $request
     * @return Illuminate\Http\Response
     */
    public function logout(LogoutRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->authRepository->logout($data);
        return $this->authRepository->getJsonResponse($response);
    }

    /**
     * Handles register requests
     *
     * @param array $request
     * @return Illuminate\Http\Response
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->authRepository->register($data);
        return $this->authRepository->getJsonResponse($response);
    }

    /**
     * Checks if user exist.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function forgotPassword(ForgotPassword $request): JsonResponse
    {

        $data = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
        ];

        $response = $this->authRepository->forgotPassword($data);
        return $this->authRepository->getJsonResponse($response);
    }

    /**
     * Update password.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function updatePassword(UpdatePassword $request): JsonResponse
    {

        $data = [
            'userId' => $request->input('userId'),
            'password' => $request->input('password'),
            'confirm_password' => $request->input('confirm_password'),
        ];

        $response = $this->authRepository->updatePassword($data);
        return $this->authRepository->getJsonResponse($response);
    }
}
