<?php
declare(strict_types=1);

namespace Repository\Client;

use App\Models\OauthClient;
use Repository\BaseRepository;

class ClientRepository extends BaseRepository
{
    const API_ENDPOINT_RESOURCE_NAME = 'clients';

    public function model()
    {
        return OauthClient::class;
    }

    protected function getSearchFields()
    {
        return ['name', 'redirect'];
    }

    public function getByClientId($clientId, $secret = null) {
        return $this->model()::where('id', $clientId)->orWhere('secret', $secret)->first();
    }

}
