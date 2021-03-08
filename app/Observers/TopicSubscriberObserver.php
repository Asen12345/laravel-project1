<?php

namespace App\Observers;

use App\Eloquent\TopicSubscriber;
use App\Events\NoticeTopicSubscriberAccountEvent;
use App\Jobs\TopicSubscriberJob;

class TopicSubscriberObserver
{
    /**
     * Handle the topic subscriber "created" event.
     *
     * @param TopicSubscriber $topicSubscriber
     * @return void
     */
    public function created(TopicSubscriber $topicSubscriber)
    {
        TopicSubscriberJob::dispatch($topicSubscriber);
        event(new NoticeTopicSubscriberAccountEvent($topicSubscriber));
    }

    /**
     * Handle the topic subscriber "updated" event.
     *
     * @param TopicSubscriber $topicSubscriber
     * @return void
     */
    public function updated(TopicSubscriber $topicSubscriber)
    {
        //
    }

    /**
     * Handle the topic subscriber "deleted" event.
     *
     * @param TopicSubscriber $topicSubscriber
     * @return void
     */
    public function deleted(TopicSubscriber $topicSubscriber)
    {
        //
    }

    /**
     * Handle the topic subscriber "restored" event.
     *
     * @param TopicSubscriber $topicSubscriber
     * @return void
     */
    public function restored(TopicSubscriber $topicSubscriber)
    {
        //
    }

    /**
     * Handle the topic subscriber "force deleted" event.
     *
     * @param TopicSubscriber $topicSubscriber
     * @return void
     */
    public function forceDeleted(TopicSubscriber $topicSubscriber)
    {
        //
    }
}
