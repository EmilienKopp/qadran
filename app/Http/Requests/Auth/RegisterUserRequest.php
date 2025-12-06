<?php

namespace App\Http\Requests\Auth;

use App\Utils\Formatters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:landlord.tenant_users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'host' => 'required|regex:/^[a-z0-9-]+$/|max:255|unique:landlord.tenants,host',
            'space' => 'required|string|max:255',
        ];
    }

    public function prepareForValidation(): void
    {
        if (app()->isProduction()) {
            $email = Formatters::stripPlusAddressing($this->input('email'));
            $this->merge([
                'email' => $email,
            ]);
        }

    }
}
