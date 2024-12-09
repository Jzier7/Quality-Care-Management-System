<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePatient extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user->id === (int) $this->input('id')) {
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
            'id' => ['required', 'integer', 'exists:users,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'patient.birthdate' => ['required', 'date', 'before:today'],
            'patient.address' => ['required', 'string'],
            'patient.emergency_contact' => ['required', 'string', 'regex:/^(?:\+639|09)\d{9}$/'],
            'patient.sex' => ['required', 'in:Male,Female'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'User does not exist.',
        ];
    }
}
