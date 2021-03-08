<?php

namespace App\Jobs;

use App\Eloquent\Admin;
use App\Mail\NewNewsAdmin;
use App\Mail\NewNewsUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class NewNewsJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $news;
    private $type;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $news
     * @param $type
     */
    public function __construct($news, $type)
    {
        $this->news = $news;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $type = $this->type;

        if ($type === 'send_admin') {
            $admins = Admin::where('role', 'admin')->where('active', true)->get();
            foreach ($admins as $admin) {
                Mail::send(new NewNewsAdmin($this->news, $admin));
            }
        } elseif ($type === 'send_user') {
            Mail::send(new NewNewsUser($this->news));
        }
    }
}
