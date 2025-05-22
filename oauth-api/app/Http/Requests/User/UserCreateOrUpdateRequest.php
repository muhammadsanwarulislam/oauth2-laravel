<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Repository\User\UserRepository;

class UserCreateOrUpdateRequest extends FormRequest
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

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
    public function rules(): array
    {
        if($this->isMethod('put')) {
            $userId = $this->route(rtrim($this->userRepository::API_ENDPOINT_RESOURCE_NAME,'s'));
            $currentUser = $this->userRepository->findByID($userId);
            $incomingData = $this->all();
    
            // Compare incoming data with current user data
            if(empty($currentUser)) {
                $changedFields = [];
                foreach ($incomingData as $key => $value) {
                    if (isset($currentUser->$key) && $currentUser->$key != $value) {
                        $changedFields[$key] = $value;
                    }
                }
            }
        }

        $rules = [
            'status'  => 'nullable|in:0,1',
        ];

        if ($this->isMethod('post') || $this->isMethod('put')) {
            $rules = array_merge($rules, [
                'name'    => ['required', Rule::unique('users', 'name')->ignore($userId ?? 0)],
                'email'   => ['required', 'email', Rule::unique('users', 'email')->ignore($userId ?? 0)]
            ]);
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required',
            'name.unique'   => 'The name must be unique',
            'email.required' => 'The email field is required',
            'email.unique'   => 'The email must be unique',
        ];
    }
}
