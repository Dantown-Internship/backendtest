<?php

namespace App\Providers;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $status;

    public $transaction;

    /**
     * Create a new event instance.
     */
    public function __construct($status, $transaction)
    {
        $this->status = $status;
        $this->transaction = $transaction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
