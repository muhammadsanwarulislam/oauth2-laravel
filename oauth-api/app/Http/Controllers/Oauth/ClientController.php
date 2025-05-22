<?php
declare(strict_types=1);

namespace App\Http\Controllers\Oauth;


use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Client\ClientService;
use App\Http\Resources\Client\ClientResource;
use App\Http\Requests\Client\ClientCreateOrUpdateRequest;

class ClientController extends Controller
{
    use JsonResponseTrait;
    public function __construct(protected ClientService $clientService)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse     
    {
        try {
            $data = $this->clientService->getClients($request);
  
            return $this->successJsonResponseWithLimitOffset(
                'List of clients',
                $data['option'],
                $data['offset'],
                $data['limit'],
                $data['totalCount'],
                $data['metaData'],
                ClientResource::collection($data['clients']['result'])
            );
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage()); 
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientCreateOrUpdateRequest $request)
    {
        try {
            $client = $this->clientService->createClient($request->validated());

            return $this->createdJsonResponse('Client create successfully', [
                'client'          => $client,
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $client = $this->clientService->getClientById($id);

            return $this->successJsonResponse('Client details', new ClientResource($client));
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientCreateOrUpdateRequest $request, string $id)
    {
        try {
            $client = $this->clientService->updateClient($request->validated(), $id);

            return $this->successJsonResponse('Client updated successfully', [
                'client'          => $client,
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->clientService->deleteClientById($id);

            return $this->successJsonResponse('Client deleted successfully');
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }
}
