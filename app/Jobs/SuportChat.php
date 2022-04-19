<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pusher\Pusher;

class SuportChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $message,$reciever,$sender;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message,$reciever_id,$sender)
    {
        $this->message = $message;
        $this->reciever = $reciever_id;
        $this->sender = $sender;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
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
            "message" => $this->message,
            "reciever_id" => (int) $this->reciever,
            "sender" => $this->sender,
        ];

        $pusher->trigger('support-chat.'.(int) $this->reciever, 'support-chat-event', $data);

    }
}
