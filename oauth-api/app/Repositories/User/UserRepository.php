<?php
declare(strict_types=1);

namespace Repository\User;

use App\Models\User;
use Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository
{
    const API_ENDPOINT_RESOURCE_NAME = 'users';
    const REGISTER_API_ENDPOINT_NAME = 'signup';
    const LOGIN_API_ENDPOINT_NAME = 'signin';
    const CURRENT_USER_API_ENDPOINT_NAME = 'current-user';
    const OAUTH_CURRENT_USER_API_ENDPOINT_NAME = 'oauth-current-user';
    const SSO_API_ENDPOINT_NAME = 'sso';
    const SSO_CALLBACK_API_ENDPOINT_NAME = 'callback';

    public function model()
    {
        return User::class;
    }

    protected function applyDefaultCriteria($query)
    {
        parent::applyDefaultCriteria($query);
        $query->where('id', '<>', Auth::id());
    }

    protected function getSearchFields()
    {
        return ['name', 'email'];
    }

    public function generateAccessToken(User $user): string
    {
        return $user->createToken('authToken')->plainTextToken;
    }
}
