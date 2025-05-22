<?php
declare(strict_types=1);

namespace Repository\AccessToken;

use App\Models\OauthAccessToken;
use Repository\BaseRepository;

class AccessTokenRepository extends BaseRepository
{
    const API_ENDPOINT_RESOURCE_NAME = 'get-token';
    const API_ENDPOINT_REFRESH_TOKEN = 'refresh-token';
    const API_ENDPOINT_REVOKE_TOKEN = 'revoke-token';

    public function model()
    {
        return OauthAccessToken::class;
    }

    protected function getSearchFields()
    {
        return ['user_id'];
    }
}
