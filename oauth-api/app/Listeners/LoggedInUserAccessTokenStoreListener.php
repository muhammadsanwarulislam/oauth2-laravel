<?php

namespace App\Listeners;

use Repository\User\UserRepository;
use App\Events\LoggedInUserAccessTokenStoreEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoggedInUserAccessTokenStoreListener
{
    /**
     * Create the event listener.
     */
    public function __construct(protected UserRepository $userRepository)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(LoggedInUserAccessTokenStoreEvent $event): void
    {
        $entity = $event->data['user'];
        $accessToken = $event->data['access_token'];

        $this->userRepository->updateByModelCondition(
            condition: ['id' => $entity->id],
            field: 'remember_token',
            value: $accessToken
        );
    }

}
