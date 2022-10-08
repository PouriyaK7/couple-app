<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Model $transaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $transaction)
    {
        $this->transaction = $transaction;
    }
}
