<?php

namespace App\Jobs;

use App\Eloquent\ResearchAuthorSubscriber;
use App\Mail\NewResearches;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class NewResearchesJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $news;
    private $type;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $researches;

    /**
     * Create a new job instance.
     *
     * @param $researches
     */
    public function __construct($researches)
    {
        $this->researches = $researches;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $researchesSubscribers = ResearchAuthorSubscriber::where('active', true)->where('author_id', $this->researches->author->id)->get();
        foreach ($researchesSubscribers as $row){
            Mail::send(new NewResearches($this->researches, $row->email));
        }

    }
}
