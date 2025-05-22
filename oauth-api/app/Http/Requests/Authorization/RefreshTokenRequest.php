<?php

namespace App\Http\Requests\Authorization;

use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
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
            'refresh_token' => 'required|string',
            'client_id'     => 'required|string',
            'client_secret' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'refresh_token.required' => 'Refresh token is required',
            'client_id.required' => 'Client ID is required',
            'client_secret.required' => 'Client secret is required',
        ];
    }
}
