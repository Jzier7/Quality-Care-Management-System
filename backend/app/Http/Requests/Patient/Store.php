<?php

namespace App\Http\Requests\Patient;

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

        return $user && $user->isAdmin() || $user->isWorker();
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
            'birthdate' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string'],
            'emergency_contact' => ['required', 'string', 'regex:/^(?:\+639|09)\d{9}$/'],
            'sex' => ['required', 'in:Male,Female'],
        ];
    }

    public function messages(): array
    {
        return [
            'emergency_contact.regex' => 'Emergency contact must be a valid Philippine phone number (e.g., +639123456789 or 09123456789).'
        ];
    }
}
