<?php
declare(strict_types=1);

namespace App\Services\Authorization;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Repository\Client\ClientRepository;
use Repository\AuthCode\AuthCodeRepository;
use Repository\AccessToken\AccessTokenRepository;

class AuthorizationService
{
    public function __construct(
        protected ClientRepository $clientRepository,
        protected AccessTokenRepository $accessTokenRepository,
        protected AuthCodeRepository $authCodeRepository
    ) {}

    public function getAuthorizationByClientId($clientId)
    {
        return $this->clientRepository->getByClientId($clientId);
    }

    public function getAuthorizationCodeByClientId($requestData)
    {
        $clientId = $this->clientRepository->getByClientId($requestData['client_id']);
        $code = Str::random(40);

        $this->authCodeRepository->create([
            'id'        => $code,
            'client_id' => $clientId->id,
            'user_id'   => $requestData['user_id'],
            'revoked'   => false,
            'expires_at'=> now()->addSeconds(config('oauth.access_token_lifetime')),
        ]);
        return $code;
    }

    public function getAccessTokenByClientId($requestData)
    {
        $clientId = $this->clientRepository->getByClientId($requestData['client_id'], $requestData['client_secret']);

        if ($requestData['grant_type'] === 'authorization_code') {
            $oauthClient = $this->authCodeRepository->getUserIdByClientId($requestData['code'], $clientId->id);
        }

        $accessToken = Str::random(40);
        $refreshToken = Str::random(40);

        $this->accessTokenRepository->create([
            'id'            => $accessToken,
            'refresh_token' => $refreshToken,
            'client_id'     => $clientId['id'],
            'user_id'       => $oauthClient['user_id'],
            'expires_at'    => now()->addSeconds(config('oauth.access_token_lifetime')),
            'revoked'       => false,
        ]);

        return [
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'created_at'    => now(),
            'expires_in'    => config('oauth.access_token_lifetime'),
            'expires_at'    => now()->addSeconds(config('oauth.access_token_lifetime')),
        ];
    }

    public function revokeTokens($userId, $clientId)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id', $userId)
            ->where('client_id', $clientId)
            ->update(['revoked' => true]);
    }

}
