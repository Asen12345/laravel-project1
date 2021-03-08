<?php

use Illuminate\Database\Seeder;

class RegionCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql_countries = file_get_contents(database_path('seeder-sql/darklight_countries.sql'));
        $sql_regions = file_get_contents(database_path('seeder-sql/darklight_regions.sql'));
        $sql_city = file_get_contents(database_path('seeder-sql/darklight_city.sql'));

        DB::statement($sql_city);
        DB::statement($sql_regions);
        DB::statement($sql_countries);

    }
}
