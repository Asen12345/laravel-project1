<?php

namespace App\Jobs;

use App\Eloquent\Admin;
use App\Mail\NewsBlogAdmin;
use App\Mail\NewsBlogUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class NewsBlogJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $blog;
    private $type;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $blog
     * @param $type
     */
    public function __construct($blog, $type)
    {
        $this->blog = $blog;
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
                Mail::send(new NewsBlogAdmin($this->blog, $admin));
            }
        } elseif ($type === 'send_user') {
            Mail::send(new NewsBlogUser($this->blog));
        }
    }
}
