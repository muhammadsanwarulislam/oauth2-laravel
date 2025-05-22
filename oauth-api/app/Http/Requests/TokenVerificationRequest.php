<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TokenVerificationRequest extends FormRequest
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
            'grant_type' => 'required|in:authorization_code',
            'client_id' => 'required|exists:oauth_clients,id',
            'client_secret' => 'required',
            'code' => 'required_if:grant_type,authorization_code',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Client ID is required',
            'client_id.exists' => 'Client ID does not exist',
        ];
    }
}
