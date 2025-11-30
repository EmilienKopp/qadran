<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMcpTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated tenant users can create MCP tokens
        return $this->user() && auth()->guard('tenant')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_\.]+$/', // Allow only alphanumeric, spaces, hyphens, underscores, and dots
                function ($attribute, $value, $fail) {
                    $exists = $this->user()->tokens()
                        ->where('name', 'MCP: '.$value)
                        ->exists();

                    if ($exists) {
                        $fail('An MCP token with this name already exists.');
                    }
                },
            ],
            'expires_at' => [
                'nullable',
                'date',
                'after:now',
                'before:'.now()->addYears(5)->toISOString(), // Max 5 years
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please provide a name for your MCP token.',
            'name.regex' => 'Token name can only contain letters, numbers, spaces, hyphens, underscores, and dots.',
            'expires_at.after' => 'The expiration date must be in the future.',
            'expires_at.before' => 'The expiration date cannot be more than 5 years in the future.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim and sanitize the name
        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->input('name')),
            ]);
        }
    }
}
