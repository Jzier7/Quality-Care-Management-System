<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreWorker extends FormRequest
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
            'email' => ['required', 'unique:users,email'],
            'license_number' => ['required', 'string', 'unique:users,license_number'],
            'department' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'position' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'sex.in' => 'Gender must be one of the following: Male, Female',
            'license_number.unique' => 'License Number has already been taken.',
            'end_time.after' => 'End time must be later than the start time.',
        ];
    }
}
