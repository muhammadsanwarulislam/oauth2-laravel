<?php

namespace App\Http\Requests\Authorization;

use Illuminate\Foundation\Http\FormRequest;

class AuthorizationApproveRequest extends FormRequest
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
            'client_id' => 'required|exists:oauth_clients,id',
            'user_id'   => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Client ID is required',
            'client_id.exists' => 'Client ID does not exist',
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User ID does not exist',
        ];
    }
}
