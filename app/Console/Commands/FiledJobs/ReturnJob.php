<?php

namespace App\Console\Commands\FiledJobs;

use App\Eloquent\FailedJobs;
use Artisan;
use Illuminate\Console\Command;

class ReturnJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:return-filed-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'return one filed job';

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
        $queueList = FailedJobs::whereNotNull('id')->get();
        foreach ($queueList as $list) {
            Artisan::call('queue:retry ' . $list->id);
        }
    }

}
