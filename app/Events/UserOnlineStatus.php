<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserOnlineStatus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message,$reciever,$sender;

    
    public function __construct()
    {
        $this->message = $message;
        $this->reciever = $message->reciever_id;
        $this->sender = $sender;
    }

   
    public function broadcastOn()
    {
        return new PresenceChannel('user-status.'. $this->reciever);
    }
}
