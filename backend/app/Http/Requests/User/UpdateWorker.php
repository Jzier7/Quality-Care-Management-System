<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateWorker extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        // Allow the user to update their own information
        return $user->id === (int) $this->input('id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:users,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'health_care_worker.license_number' => ['required', 'string', 'max:50'],
            'health_care_worker.department' => ['required', 'string', 'max:100'],
            'health_care_worker.position_id' => ['required', 'integer', 'exists:positions,id'],
            'health_care_worker.shift_start_time' => [
                'required',
                'regex:/^([01]?[0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]))?$/',
            ],
            'health_care_worker.shift_end_time' => [
                'required',
                'regex:/^([01]?[0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]))?$/',
            ],
        ];
    }

    /**
     * Get the validation messages for the defined rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.exists' => 'The specified user does not exist.',
        ];
    }
}
