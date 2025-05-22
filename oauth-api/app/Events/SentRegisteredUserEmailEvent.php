<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SendRegisteredUserEmailJob;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Listeners\SentRegisteredUserEmailListener;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SentRegisteredUserEmailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    
    public function __construct(User $user)
    {
        $this->user=$user;
        dispatch(new SendRegisteredUserEmailJob($user));
    }

    public function broadcastOn()
    {
        return [];
    }
}
