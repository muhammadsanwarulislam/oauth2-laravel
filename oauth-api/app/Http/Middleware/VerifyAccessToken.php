<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Soap\Url;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->query('access_token') ?? $request->bearerToken();
        if (empty($accessToken)) {
            return response()->json(['error' => 'Access Token Missing'], 401);
        }

        // Extract token value (assuming Bearer token format)
        $accessToken = str_replace('Bearer ', '', $accessToken);

        // Validate the access token
        $token = DB::table('oauth_access_tokens')
            ->where('id', $accessToken)
            ->where('revoked', false)
            ->first();

        if (!$token) {
            return response()->json(['error' => 'Invalid Access Token'], 401);
        }
        // Check if the token is revoked
        if ($token->revoked) {
            return response()->json(['error' => 'Token has been revoked'], 401);
        }
        // Check if the token is expired
        if ($token->expires_at && $token->expires_at < now()) {
            return response()->json(['error' => 'Token has expired'], 401);
        }

        $request->attributes->set('access_token', $accessToken);

        return $next($request);
    }
}
