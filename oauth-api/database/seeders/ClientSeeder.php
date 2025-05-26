<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Repository\Client\ClientRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    public function __construct(
        protected ClientRepository $clientRepository
    ) {}
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientsData = [
            [
                'name' => 'Test Client',
                'redirect' => 'http://localhost:3001/',
            ]
        ];
        foreach ($clientsData as $clientData) {
            $this->clientRepository->model()::updateOrCreate(
                ['name' => $clientData['name']],
                [
                    'user_id'   => 1, 
                    'name'      => $clientData['name'],
                    'redirect'  => $clientData['redirect'],
                    'secret'    => Str::random(40),
                    'created_at'=> now(),
                ]
            );
        }
    }
}
