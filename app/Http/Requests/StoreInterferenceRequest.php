<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterferenceRequest extends FormRequest
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
            'in' => ['required', 'date_format:H:i'],
            'out' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'clock_entry_id' => ['nullable', 'exists:clock_entries,id'],
            'timezone' => ['required', 'timezone'],
        ];
    }

    public function prepareForValidation()
    {
        // Convert time strings (HH:MM) to full datetime strings for today
        $today = now()->toDateString();
        
        $this->merge([
            'in' => $today . ' ' . $this->in . ':00',
            'out' => $today . ' ' . $this->out . ':00',
            'timezone' => $this->timezone ?? $this->user()->timezone ?? config('app.timezone'),
        ]);
    }
}
