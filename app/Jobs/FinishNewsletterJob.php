<?php

namespace App\Jobs;

use App\Eloquent\NewsletterCount;
use App\Eloquent\NewsletterSetting;
use App\Eloquent\NotificationSubscriber;
use App\Mail\FinishNewsletterAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class FinishNewsletterJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $countUsers     = NotificationSubscriber::get()->count();
        $idNewsletter   = NewsletterCount::get()->last()->id;
        $dateNewsletter = NewsletterCount::get()->last()->updated_at;
        $settingEmail   = NewsletterSetting::first()->email;
        Mail::send(new FinishNewsletterAdmin($settingEmail, $idNewsletter, $countUsers, $dateNewsletter));
    }
}
