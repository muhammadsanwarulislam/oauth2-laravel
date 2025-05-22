<?php
declare(strict_types=1);

namespace App\Http\Controllers\Oauth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TokenVerificationRequest;
use App\Services\Authorization\AuthorizationService;
use App\Http\Requests\Authorization\AuthorizationRequest;
use App\Http\Requests\Authorization\AuthorizationApproveRequest;

class AuthorizationController extends Controller
{
    use JsonResponseTrait;

    public function __construct(protected AuthorizationService $authorizationService) {}
    public function authorize(AuthorizationRequest $request)
    {
        try {
            $client = $this->authorizationService->getAuthorizationByClientId($request->validated()['client_id']);
            return $this->successJsonResponse('Client data', ['client' => $client]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    public function approveAuthorization(AuthorizationApproveRequest $request)
    {
        try {
            $code = $this->authorizationService->getAuthorizationCodeByClientId($request->validated());
            return $this->successJsonResponse('Client data', ['redirect' => $request->redirect, 'code' => $code]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    public function generateAccessToken(TokenVerificationRequest $request)
    {
        try {
            $token = $this->authorizationService->getAccessTokenByClientId($request->validated());
            return $this->successJsonResponse('Client data', ['token' => $token]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    public function refresh(Request $request)
    {
        try {
            // Validate the refresh token
            $refreshToken = $request->input('refresh_token');

            // Retrieve the associated access token record
            $tokenRecord = DB::table('oauth_access_tokens')
                ->where('refresh_token', $refreshToken)
                ->where('revoked', false)
                ->first();

            if (!$tokenRecord) {
                return response()->json(['error' => 'Invalid or revoked refresh token'], 401);
            }

            // Check if the refresh token has expired (if applicable)
            if (now()->isAfter($tokenRecord->expires_at)) {
                return response()->json(['error' => 'Refresh token expired'], 401);
            }

            // Generate new access token
            $newAccessToken = Str::random(40);

            // Update the existing record or create a new one as necessary
            DB::table('oauth_access_tokens')
                ->where('id', $tokenRecord->id)
                ->update([
                    'id' => $newAccessToken,
                    'expires_at' => now()->addSeconds(config('oauth.access_token_lifetime')),
                ]);

            return response()->json([
                'access_token' => $newAccessToken,
                'expires_in'   => config('oauth.access_token_lifetime'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function revoke(Request $request)
    {
        try {
            $this->authorizationService->revokeTokens($request->user()->id, $request->input('client_id'));

            return response()->json(['message' => 'Tokens revoked successfully']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
