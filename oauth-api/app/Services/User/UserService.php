<?php
declare(strict_types=1);

namespace App\Services\User;

use Repository\User\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    
    }

    public function getUsers($requestData)
    {
        $offset         = $requestData['offset'];
        $limit          = $requestData['limit'];
        $option         = $requestData['option'];
        $searchData     = $requestData['searchData'];

        $users = $this->userRepository->getAll($offset, $limit, $searchData, $option);
        $totalCount = $users['count'];

        return [
            'option'    =>  $option, 
            'offset'    =>  $offset, 
            'limit'     =>  $limit, 
            'totalCount'=>  $totalCount, 
            'users'     =>  $users,
            'metaData'  =>  $users['metadata']
        ];
    }

    public function createUser(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function updateUser(array $data, string $id, bool $isPatch = false)
    {
        return $this->userRepository->updateByID($id, $data);
    }

    public function getUserById($userId)
    {
        return $this->userRepository->findByID($userId);
    }

    public function deleteUserById($userId)
    {
        return$this->userRepository->deletedByID($userId);
    }
}
