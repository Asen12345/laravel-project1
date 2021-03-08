<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NoticeTopicSubscriberAccountEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $topicSubscriber;

    /**
     * Create a new event instance.
     *
     * @param $topicSubscriber
     */
    public function __construct($topicSubscriber)
    {
        $this->topicSubscriber = $topicSubscriber;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
