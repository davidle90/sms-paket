<?php namespace Rocketlabs\Sms\App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SmsSent
{
    use InteractsWithSockets, SerializesModels;

    public $event_ref;
    public $params;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($event_ref, $params)
    {
        $this->event_ref    = $event_ref;
        $this->params       = $params;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
