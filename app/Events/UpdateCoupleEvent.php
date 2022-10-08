<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateCoupleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Model $couple;
    public array $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $couple, array $data)
    {
        $this->couple = $couple;
        $this->data = $data;
    }
}
