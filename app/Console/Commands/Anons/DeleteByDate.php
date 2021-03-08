<?php

namespace App\Console\Commands\Anons;

use App\Eloquent\Anons;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteByDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete-anons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete anons if date is old';

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
        $anons = Anons::where('will_end', '<=', Carbon::now())->get();
        foreach ($anons as $row) {
            $row->delete();
        }
    }
}
