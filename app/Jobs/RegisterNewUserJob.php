<?php

namespace App\Jobs;

use App\Eloquent\Admin;
use App\Mail\ApprovedNewUser;
use App\Mail\RegisterNewUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class RegisterNewUserJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $user;
    private $type;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $type string
     */
    public function __construct($user, $type)
    {
        $this->user = $user;
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
                Mail::send(new RegisterNewUser($this->user, $admin));
            }
        } elseif ($type === 'send_user') {
            Mail::send(new ApprovedNewUser($this->user));
        }
    }
}
