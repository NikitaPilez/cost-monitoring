<?php

namespace App\Events;

use App\Http\Resources\PurchaseCollection;
use App\Models\Purchase;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewPurchase implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PurchaseCollection $userPurchases;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $userId)
    {
        $this->userPurchases = new PurchaseCollection(Purchase::filter($userId)->get());
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('new-purchase');
    }
}
