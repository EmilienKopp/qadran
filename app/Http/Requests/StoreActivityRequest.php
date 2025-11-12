<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
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
            'date' => 'required|date',
            'activities' => 'required|array|min:1',
            'activities.*.user_id' => 'required|exists:users,id',
            'activities.*.project_id' => 'required|exists:projects,id',
            'activities.*.task_category_id' => 'required|exists:task_categories,id',
            'activities.*.date' => 'required|date',
            'activities.*.duration' => 'required|integer|min:0',
            'activities.*.notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'activities.required' => 'At least one activity is required.',
            'activities.*.task_category_id.required' => 'Each activity must have a task category.',
            'activities.*.duration.required' => 'Each activity must have a duration.',
            'activities.*.duration.min' => 'Activity duration must be at least 0 seconds.',
        ];
    }
}
