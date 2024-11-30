<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'birthdate' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:255'],
            'emergency_contact' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'string', 'in:male,female'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
        ];
    }

    /**
     * Custom messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'confirm_password.same' => 'Password does not match.',
            'sex.in' => 'Gender must be one of the following: male, female',
            'birthdate.before' => 'Birthdate must be a past date.',
        ];
    }
}
