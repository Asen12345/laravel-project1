<?php

namespace App\Console\Commands;

use App\Eloquent\Feedback;
use App\Eloquent\FeedbackShop;
use App\Eloquent\Mailing;
use App\Eloquent\MessageThread;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveOldMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:remove-old-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old message and feedback';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $beforeDays = 90;
        $beforeDateTime = Carbon::now()->subDays($beforeDays)->toDateTimeString();

        Feedback::whereDate('created_at', '<', $beforeDateTime)->delete();
        FeedbackShop::whereDate('created_at', '<', $beforeDateTime)->delete();

        $oldMailings = Mailing::whereDate('created_at', '<', $beforeDateTime)->get();

        $oldMessageTreads = MessageThread::whereDate('created_at', '<', $beforeDateTime)->get();

        foreach ($oldMessageTreads as $oldMessageTread) {
            $oldMessageTread->messageParticipants()->delete();
            $oldMessageTread->messages()->delete();
            $oldMessageTread->delete();
        }

        foreach ($oldMailings as $oldMailing) {
            $oldMailing->mailingUsers()->delete();
            $oldMailing->delete();
        }


    }
}
