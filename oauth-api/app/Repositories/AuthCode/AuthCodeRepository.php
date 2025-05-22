<?php
declare(strict_types=1);

namespace Repository\AuthCode;

use App\Models\OauthAuthCode;
use Repository\BaseRepository;

class AuthCodeRepository extends BaseRepository
{
    const API_ENDPOINT_RESOURCE_NAME = 'authorize';

    public function model()
    {
        return OauthAuthCode::class;
    }

    protected function getSearchFields()
    {
        return ['user_id'];
    }

    public function getUserIdByClientId($code, $clientId)
    {
        return $this->model()::where('id', $code)->where('client_id', $clientId)->first();
    }
}
