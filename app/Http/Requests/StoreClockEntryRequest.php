<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClockEntryRequest extends FormRequest
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
            'in' => ['date'],
            'out' => ['date'],
            'note' => ['nullable', 'string'],
            'project_id' => ['exists:tenant.projects,id'],
            'user_id' => ['required','exists:tenant.users,id'],
            'timezone' => ['nullable', 'timezone'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
            'timezone' => $this->timezone ?? 'UTC',
        ]);
    }
}
