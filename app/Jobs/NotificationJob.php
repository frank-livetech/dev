<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pusher\Pusher;

class NotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    private $receiver,$sender , $message;

    public function __construct($receiver , $sender , $message)
    {
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->message = $message;
    }

  
    public function handle()
    {
        $pusher = new Pusher(
            pusherCredentials('key'),
            pusherCredentials('secret'),
            pusherCredentials('id'),
            [
            'cluster' => pusherCredentials('cluster'),
            'useTLS' => true
            ]
        );

        $data = [
            "receiver" => $this->receiver,
            "sender" => $this->sender,
            "message" => $this->message,
        ];

        $pusher->trigger('notification.'.(int) $this->receiver, 'notification-event', $data);
    }
}
