<?php

namespace App\Console;

use App\Console\Commands\Anons\DeleteByDate;
use App\Console\Commands\FiledJobs\ReturnJob;
use App\Console\Commands\FiledOrder\FiledOrder;
use App\Console\Commands\Newsletter\Newsletter;
use App\Console\Commands\SiteMap\GenerateSiteMap;
use App\Console\Commands\UpdateJobNow;
use App\Eloquent\NewsletterSetting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateSiteMap::class,
        Newsletter::class,
        DeleteByDate::class,
        ReturnJob::class,
        FiledOrder::class,
        UpdateJobNow::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->command('command:generateSiteMap')
            ->withoutOverlapping()
            ->cron('0 6 * * *');

        $schedule->command('command:delete-anons')
            ->withoutOverlapping()
            ->cron('0 3 * * *');

        $schedule->command('command:filed-order')
            ->withoutOverlapping()
            ->cron('*/4 * * * *');

        $schedule->command('command:return-filed-job')
            ->withoutOverlapping()
            ->everyTenMinutes();

        $setting = NewsletterSetting::first();
        if(!empty($setting)) {
            $schedule->command('command:newsletter')
                ->withoutOverlapping()
                ->days($setting['weekdays'])
                ->at($setting->send_time);
        }

        $schedule->command('command:remove-old-message')
            ->withoutOverlapping()
            ->cron('01 12 1 */1 *');

        $schedule->command('command:remove-old-research-archive')
            ->withoutOverlapping()
            ->cron('0 */3 * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
