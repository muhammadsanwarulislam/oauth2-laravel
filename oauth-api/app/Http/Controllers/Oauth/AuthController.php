<?php
declare(strict_types=1);

namespace App\Http\Controllers\Oauth;

use Hash;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Services\Auth\AuthService;
use Repository\User\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\Auth\LoginPostRequest;
use App\Events\LoggedInUserAccessTokenStoreEvent;
use App\Http\Requests\Auth\RegistrationPostRequest;

class AuthController extends Controller
{
    use JsonResponseTrait;
    function __construct(
        protected UserRepository $userRepository,
        protected AuthService $authService
    ) {}

    public function register(RegistrationPostRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->userRegistration($request->validated());

            return $this->createdJsonResponse('User registered successfully', [
                'user' => new UserResource($user),
            ]);
        } catch (\Exception $e) {

            return $this->errorJsonResponse($e->getMessage());
        }
    }
    public function login(LoginPostRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['password']);

            if ($request->filled('email')) {
                $user = \App\Models\User::where('email', $request->input('email'))->first();
            } elseif ($request->filled('phone')) {

                $normalizedPhone = ltrim(preg_replace('/[^0-9]/', '', $request->input('phone')), '0');
                $user = \App\Models\User::where('phone', $normalizedPhone)->first();
            } else {
                return $this->errorJsonResponse('Email or phone is required.');
            }

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return $this->errorJsonResponse('Invalid credentials');
            }

            auth()->login($user); 

            $userData = [
                'access_token' => $this->userRepository->generateAccessToken($user),
                'user' => $user,
            ];

            event(new LoggedInUserAccessTokenStoreEvent($userData));

            return $this->successJsonResponse('Signin successful!', [
                'access_token' => $userData['access_token'],
                'access_type' => 'Bearer',
                'user' => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return $this->successJsonResponse('User logged out');
    }

    public function authorizedUserInformation(): JsonResponse
    {
        try {
            $user = $this->authService->userInformation();
            
            return $this->successJsonResponse('Logged in user information', [
                'access_token'  => $user['remember_token'],
                'user'          => new UserResource($user)
            ]);
        } catch (\Exception $e) {

            return $this->unAuthenticatedJsonResponse($e->getMessage());
        }
    }

    public function getUserInfo(Request $request)
    {
        try {
            $user = $this->authService->getUserInformationUsingAccessToken($request);
            
            return $this->successJsonResponse('User info',[
                'user' => new UserResource($user)
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e);
        }
    }
}
