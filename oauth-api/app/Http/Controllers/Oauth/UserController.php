<?php

namespace App\Http\Controllers\Oauth;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\UserCreateOrUpdateRequest;

class UserController extends Controller
{
    use JsonResponseTrait;

    public function __construct(protected UserService $userService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $data = $this->userService->getUsers($request);

            return $this->successJsonResponseWithLimitOffset(
                'List of users',
                $data['option'],
                $data['offset'],
                $data['limit'],
                $data['totalCount'],
                $data['metaData'],
                UserResource::collection($data['users']['result'])
            );
        } catch (\Exception $e) {

            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateOrUpdateRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->all() + ['password' => env('DEFAULT_PASSWORD')]);

            return $this->createdJsonResponse('User created successfully', ['user' => new UserResource($user)]);
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
            $user = $this->userService->getUserById($id);

            return $this->successJsonResponse("The user id is: $id", new UserResource($user));
        } catch (\Exception $e) {

            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserCreateOrUpdateRequest $request, string $id)
    {
        try {
            $isPatch = $request->isMethod('patch');
            $user = $this->userService->updateUser($request->all(), $id, $isPatch);

            $successMessage = $isPatch ? 'User status updated successfully' : 'User updated successfully';

            return $this->createdJsonResponse($successMessage, ['user' => new UserResource($user)]);
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
            $this->userService->deleteUserById($id);

            return $this->successJsonResponse('User delete successfully');
        } catch (\Exception $e) {

            return $this->errorJsonResponse($e->getMessage());
        }
    }
}
