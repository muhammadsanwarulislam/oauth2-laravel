<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Repository\User\UserRepository;

class UserSeeder extends Seeder
{
    function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersData = [
            [
                'name'             => 'Super admin',
                'email'            => 'super@gmail.com',
                'phone'            => '1774412345',
                'password'         => 'password',
            ]
        ];

        foreach ($usersData as $userData) {
            $this->userRepository->model()::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name'          => $userData['name'],
                    'email'         => $userData['email'],
                    'phone'         => $userData['phone'],
                    'password'      => $userData['password'],
                    'email_verified_at' => now(),
                    'created_at'    => now(),
                ]
            );
        }
    }
}
