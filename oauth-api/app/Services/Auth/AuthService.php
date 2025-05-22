<?php 
declare(strict_types=1);

namespace App\Services\Auth;

use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Repository\{
    User\UserRepository
};

class AuthService {
    public function __construct(
        protected UserRepository $userRepository,
        protected UserService $userService
    )
    {

    }

    public function userRegistration($validateData)
    {
        return $this->userRepository->create($validateData);
    }
    
    public function userInformation()
    {
        return Auth::user();
    }

    public function getUserInformationUsingAccessToken($requestData)
    {
        $accessToken = $requestData->attributes->get('access_token');
        // Find the user associated with the access token
        $user = DB::table('oauth_access_tokens as oat')
            ->where('oat.id', $accessToken)
            ->where('oat.revoked', false)
            ->join('users as u', 'oat.user_id', '=', 'u.id')
            ->select('u.*','oat.expires_at')
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid Access Token'], 401);
        }

        if (now()->isAfter($user->expires_at)) {
            return response()->json(['error' => 'Access Token Expired'], 401);
        }
        return $user;
    }
}