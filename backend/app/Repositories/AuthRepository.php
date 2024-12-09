<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\File;
use App\Models\User;
use App\Services\FileUploadService;
use App\Models\Patient;
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

        $user = User::with(['role.abilities.route'])->find(Auth::id());

        if ($user->role_id == 2) {
            $user->load('healthCareWorker');
        } elseif ($user->role_id == 3) {
            $user->load('patient');
        }

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

            $patientData = [
                'user_id' => $user->id,
                'birthdate' => $data['birthdate'] ?? null,
                'address' => $data['address'] ?? null,
                'emergency_contact' => $data['emergency_contact'] ?? null,
                'sex' => $data['sex'] ?? null
            ];

            Patient::create($patientData);

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
     * @param array $data
     * @return array
     */
    public function forgotPassword(array $data): array
    {
        try {
            $user = User::where('first_name', $data['first_name'])
                ->where('middle_name', $data['middle_name'])
                ->where('last_name', $data['last_name'])
                ->where('email', $data['email'])
                ->first();

            if ($user) {
                return [
                    'message' => 'User exists',
                    'body' => $user,
                    'status' => 200
                ];
            }

            return [
                'message' => 'User not found',
                'status' => 404
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function updatePassword(array $data): array
    {
        try {
            $user = User::find($data['userId']);

            if (!$user) {
                return [
                    'message' => 'User not found',
                    'status' => 404
                ];
            }

            if ($data['password'] !== $data['confirm_password']) {
                return [
                    'message' => 'Passwords do not match',
                    'status' => 422
                ];
            }

            $user->password = bcrypt($data['password']);
            $user->save();

            return [
                'message' => 'Password updated successfully',
                'status' => 200
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }
}
