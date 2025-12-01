<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'organization_id' => ['nullable', 'integer', 'exists:tenant.organizations,id'],
            'type' => ['required', 'string', 'in:'.ProjectType::collect()->implode(',')],
            'status' => ['required', 'string', 'in:'.ProjectStatus::collect()->implode(',')],
            'start_date' => ['required', 'date'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'start_date' => now()->parse($this->start_date)->toDateString(),
            'type' => $this->type ?? ProjectType::Other->value,
            'status' => $this->status ?? ProjectStatus::Active->value,
        ]);
    }
}
