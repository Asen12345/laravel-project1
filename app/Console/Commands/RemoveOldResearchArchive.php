<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Storage;
use Zipper;

class RemoveOldResearchArchive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:remove-old-research-archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old research archive from temp_zip folder';

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
     * @throws \Exception
     */
    public function handle()
    {
        $pathZip = storage_path() . '/app/private/temp_zip';

        $directories = \File::directories($pathZip);

        foreach ($directories as $directory) {
            $timeString = filemtime($directory);
            $dateDelete = Carbon::parse($timeString)->addDay();
            if (Carbon::now() > $dateDelete) {
                \File::deleteDirectory($directory);
            }
        }
    }
}
