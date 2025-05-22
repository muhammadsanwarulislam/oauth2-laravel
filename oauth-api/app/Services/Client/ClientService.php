<?php
declare(strict_types=1);

namespace App\Services\Client;

use Illuminate\Support\Str;
use Repository\Client\ClientRepository;

class ClientService
{
    public function __construct(
        protected ClientRepository $clientRepository
    ) {
    
    }

    public function getClients($requestData): array
    {
        $offset         = $requestData['offset'];
        $limit          = $requestData['limit'];
        $option         = $requestData['option'];
        $searchData     = $requestData['searchData'];

        $clients = $this->clientRepository->getAll($offset, $limit, $searchData, $option);
        $totalCount = $clients['count'];

        return [
            'option'    =>  $option, 
            'offset'    =>  $offset, 
            'limit'     =>  $limit, 
            'totalCount'=>  $totalCount, 
            'clients'   =>  $clients,
            'metaData'  =>  $clients['metadata']
        ];
    }

    public function createClient(array $data)
    {
        return $this->clientRepository->create($data + [
            'user_id' => auth()->user()->id,
            'secret' => Str::random(40),
        ]);
    }

    public function updateClient(array $data, string $id, bool $isPatch = false)
    {
        return $this->clientRepository->updateByID($id, $data);
    }

    public function getClientById($clientId)
    {
        return $this->clientRepository->findByID($clientId);
    }

    public function deleteClientById($userId): bool|null
    {
        return $this->clientRepository->deletedByID($userId);
    }
}
