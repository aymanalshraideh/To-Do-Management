<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Task;

class TaskCreated implements ShouldBroadcast
{
    public string $title;
    public string $message;

    public function __construct(string $title, string $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('public.tasks');
    }
    public function broadcastAs()
    {
        return 'users';
    }

    public function broadcastWith()
    {

        return [
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
