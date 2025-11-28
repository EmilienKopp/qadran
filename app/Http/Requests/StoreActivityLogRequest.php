<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityLogRequest extends FormRequest
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
            'clock_entry_id' => ['required', 'integer', 'exists:clock_entries,id'],
            'activity_type_id' => ['nullable', 'integer', 'exists:activity_types,id'],
            'task_id' => ['nullable', 'integer', 'exists:tasks,id'],
            'start_offset_seconds' => ['nullable', 'integer', 'min:0'],
            'end_offset_seconds' => ['nullable', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'clock_entry_id.required' => 'Clock entry is required.',
            'clock_entry_id.exists' => 'The selected clock entry does not exist.',
            'activity_type_id.exists' => 'The selected activity type does not exist.',
            'task_id.exists' => 'The selected task does not exist.',
            'start_offset_seconds.min' => 'Start offset must be at least 0 seconds.',
            'end_offset_seconds.min' => 'End offset must be at least 0 seconds.',
            'duration_seconds.min' => 'Duration must be at least 0 seconds.',
            'notes.max' => 'Notes cannot exceed 5000 characters.',
        ];
    }
}
