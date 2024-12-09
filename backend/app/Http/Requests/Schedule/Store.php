<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user && $user->isWorker();
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
            'title' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date', 'before:end'],
            'end' => ['required', 'date', 'after:start'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     */
    protected function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $patientId = $this->input('patient_id');
            $start = $this->input('start');
            $end = $this->input('end');

            $overlap = \App\Models\Schedule::where('patient_id', $patientId)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start', [$start, $end])
                        ->orWhereBetween('end', [$start, $end])
                        ->orWhereRaw('? BETWEEN start AND end', [$start])
                        ->orWhereRaw('? BETWEEN start AND end', [$end]);
                })
                ->exists();

            if ($overlap) {
                $validator->errors()->add('start', 'The selected time overlaps with an existing schedule.');
                $validator->errors()->add('end', 'The selected time overlaps with an existing schedule.');
            }
        });
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
            'start.before' => 'The start time must be before the end time.',
            'end.after' => 'The end time must be after the start time.',
        ];
    }
}
