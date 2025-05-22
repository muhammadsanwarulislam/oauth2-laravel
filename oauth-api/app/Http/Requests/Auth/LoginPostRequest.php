<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginPostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'     => 'nullable|string|email',
            'phone'     => 'nullable|string',
            'password'  => 'required|string|min:8|max:255',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'The password field is required.',
            'password.min'      => 'The password length must be at least 8 characters',

        ];
    }
}
