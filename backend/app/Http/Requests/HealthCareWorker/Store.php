<?php

namespace App\Http\Requests\HealthCareWorker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'license_number' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'shift_start_time' => [
                'required',
                'regex:/^([01]?[0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]))?$/',
            ],
            'shift_end_time' => [
                'required',
                'regex:/^([01]?[0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]))?$/',
            ],
            'position' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'User does not exist.',
        ];
    }
}
