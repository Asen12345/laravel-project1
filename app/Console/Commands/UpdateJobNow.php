<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateJobNow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-job-now';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all jobs to run now';

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
        $jobs = \DB::table('jobs')->get();
        foreach ($jobs as $job) {
            \DB::table('jobs')->where('id', $job->id)->update([
                'available_at' => Carbon::now()->timestamp
            ]);
        }
    }
}
