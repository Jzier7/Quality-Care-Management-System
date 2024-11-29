<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\File;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthRepository extends JsonResponseFormat
{

    /**
     * @param array $data
     * @return array
     */
    public function login(array $data): array
    {
        if (!Auth::attempt($data)) {
            return [
                'message' => 'Invalid credentials',
                'status' => 401
            ];
        }

        $user = User::with(['files', 'role.abilities.route'])->find(Auth::id());

        return [
            'message' => 'Login successful',
            'body' => $user
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function logout(): array
    {
        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return [
            'message' => 'Logout successful'
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function register($data): array
    {
        DB::beginTransaction();
        try {
            $data['role_id'] = 3;

            $user = User::create($data);

            Auth::login($user->load(['role.abilities.route']));

            DB::commit();
            return [
                'message' => 'Registered successfully',
                'body' => $user
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Checks Auth
     *
     * @return array
     */
    public function checkAuth(): array
    {
        $user = null;
        $isAuthenticated = Auth::check();
        $viaRemember = Auth::viaRemember();

        if ($isAuthenticated && $viaRemember) {
            $user = User::with(['files', 'role.abilities.route'])->find(Auth::id());
        }

        return [
            'body' => [
                'isAuthenticated' => $isAuthenticated,
                'viaRemember' => $viaRemember,
                'user' => $user
            ]
        ];
    }
}
