<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateData extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user && ($user->isSuperAdmin() || $user->id === (int) $this->input('id'))) {
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
            'birthdate' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', 'in:male,female'],
            'purok' => ['required' , 'integer', 'exists:puroks,id'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $this->id],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'User does not exist.',
            'birthdate.date' => 'Birthdate must be a valid date.',
            'gender.in' => 'Gender must be one of the following: Male, Female',
            'username.unique' => 'Username has already been taken.',
        ];
    }
}

