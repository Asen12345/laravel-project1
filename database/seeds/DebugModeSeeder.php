<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DebugModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('debug_modes')->insert([
            'debug'       => false,
            'text'        => null,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);
    }
}
