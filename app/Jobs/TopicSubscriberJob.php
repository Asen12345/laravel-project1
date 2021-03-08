<?php

namespace App\Jobs;

use App\Mail\NewMessage;
use App\Mail\TopicSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class TopicSubscriberJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $topicSubscriber;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $topicSubscriber
     */
    public function __construct($topicSubscriber)
    {
        $this->topicSubscriber = $topicSubscriber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::send(new TopicSubscriber($this->topicSubscriber));
    }
}
