<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\User;

class PrivateUserAlert implements ShouldBroadcast
{
    
    public string $title;
    public string $message;
    public int $userId;

    public function __construct(string $title, string $message, int $userId)
    {
        $this->title = $title;
        $this->message = $message;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
        
    }

    public function broadcastAs()
    {
        return 'user.alert';
    }

    public function broadcastWith()
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
