<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'   => 'required',
            'email'  => 'required|email|unique:users',
            'phone'  => 'required|max:10|unique:users',
        ];
    }


    public function messages()
    {
        return [
            'name.required'   => 'The name field is required',
            'phone.required'  => 'The phone field is required',
            'phone.unique'    => 'The phone must be unique',
            'phone.max'       => 'The phone must be less than 10 characters',
            'email.required'  => 'The email field is required.',
        ];
    }

}
