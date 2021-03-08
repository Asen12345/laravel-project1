<?php

namespace App\Console\Commands\FiledOrder;

use App\Eloquent\ShoppingCart;
use App\Jobs\SendFiledOrderJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FiledOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:filed-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $filedOrders = ShoppingCart::where(function ($query){
                $query->where('status', 'started')
                    ->orWhere('status', 'waiting')
                    ->orWhere('status', 'cancelled');
            })
            ->where('remind', false)
            ->whereTime('created_at', '>', Carbon::now()->subMinutes(30)->isoFormat('H:mm:ss'))
            ->whereTime('created_at', '<', Carbon::now()->subMinutes(10)->isoFormat('H:mm:ss'))
            ->get();

        foreach ($filedOrders as $filedOrder) {
            $filedOrder->update([
                'status' => 'started',
                'remind' => true
            ]);
            SendFiledOrderJob::dispatch($filedOrder);
        }

    }
}
