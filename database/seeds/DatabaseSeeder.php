<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(RegionCitySeeder::class);
        $this->call(CompanyTypeSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(DebugModeSeeder::class);
        $this->call(DataTemplateSeeder::class);
        $this->call(BannerPlaceSeeder::class);
        $this->call(MailTemplateSeeder::class);
        $this->call(MetaTagsSeeder::class);
        $this->call(WidgetSeeder::class);
    }
}
