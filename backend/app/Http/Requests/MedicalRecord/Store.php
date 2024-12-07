<?php

namespace App\Http\Requests\InformationBoard;

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

        return $user && ($user->isAdmin() || $user->isWorker());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'healthcare_worker_id' => ['required', 'exists:healthcare_workers,id'],
            'serial_number' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'prescriptions' => ['required', 'string'],
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'patient_id.exists' => 'The selected patient does not exist.',
            'healthcare_worker_id.exists' => 'The selected healthcare worker does not exist.',
            'date.date' => 'Please provide a valid date.',
        ];
    }
}
