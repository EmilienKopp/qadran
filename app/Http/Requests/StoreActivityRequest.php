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
        return $this->isJsonFormat()
            ? $this->jsonFormatRules()
            : $this->formFormatRules();
    }

    public function prepareForValidation(): void
    {
        \Log::debug('StoreActivityRequest prepareForValidation', $this->all());
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'hours.*.min' => 'Hours must be at least 0.',
            'minutes.*.min' => 'Minutes must be at least 0.',
            'minutes.*.max' => 'Minutes may not be greater than 59.',
            'activities.*.hours.min' => 'Hours must be at least 0.',
            'activities.*.minutes.min' => 'Minutes must be at least 0.',
            'activities.*.minutes.max' => 'Minutes may not be greater than 59.',
        ];
    }

    /**
     * Get the validated data from the request, normalized to form format.
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        if ($this->isJsonFormat()) {
            return $this->normalizeJsonToFormFormat($validated);
        }

        return $validated;
    }

    /**
     * Detect if the request is using JSON format (activities array with nested objects).
     */
    protected function isJsonFormat(): bool
    {
        return $this->has('activities');
    }

    /**
     * Validation rules for form format (flat arrays).
     */
    protected function formFormatRules(): array
    {
        return [
            'id' => 'required|array',
            'deleted' => 'nullable|array',
            'clock_entry_id' => 'required|exists:tenant.clock_entries,id',
            'activity_type_id' => 'nullable|array',
            'activity_type_id.*' => 'nullable|exists:tenant.activity_types,id',
            'hours' => 'nullable|array',
            'hours.*' => 'nullable|integer|min:0',
            'minutes' => 'nullable|array',
            'minutes.*' => 'nullable|integer|min:0|max:59',
        ];
    }

    /**
     * Validation rules for JSON format (nested objects in activities array).
     */
    protected function jsonFormatRules(): array
    {
        return [
            'activities' => 'required|array',
            'activities.*.id' => 'nullable|integer|exists:tenant.activity_logs,id',
            'activities.*.clock_entry_id' => 'required|integer|exists:tenant.clock_entries,id',
            'activities.*.activity_type_id' => 'nullable|integer|exists:tenant.activity_types,id',
            'activities.*.hours' => 'nullable|integer|min:0',
            'activities.*.minutes' => 'nullable|integer|min:0|max:59',
            'deleted' => 'nullable|array',
            'deleted.*' => 'integer|exists:tenant.activity_logs,id',
        ];
    }

    /**
     * Transform JSON format to form format for repository compatibility.
     */
    protected function normalizeJsonToFormFormat(array $validated): array
    {
        $activities = $validated['activities'];

        $normalized = [
            'id' => [],
            'activity_type_id' => [],
            'hours' => [],
            'minutes' => [],
            'clock_entry_id' => $activities[0]['clock_entry_id'] ?? null,
            'deleted' => $validated['deleted'] ?? [],
        ];

        foreach ($activities as $activity) {
            $normalized['id'][] = $activity['id'] ?? null;
            $normalized['activity_type_id'][] = $activity['activity_type_id'] ?? null;
            $normalized['hours'][] = $activity['hours'] ?? 0;
            $normalized['minutes'][] = $activity['minutes'] ?? 0;
        }

        return $normalized;
    }
}
