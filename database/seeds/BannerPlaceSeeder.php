<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BannerPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $places = array(
            ['place' => 'Новость верх (страница “Новости”)'],
            ['place' => 'Новость низ (страница “Новости”)'],
            ['place' => 'На странице выбранного блога'],
            ['place' => 'На странице записи выбранного блога'],
            ['place' => 'На странице выбранного Мероприятия'],
            ['place' => 'В шапке'],
            ['place' => 'В правом сайтбаре'],
            ['place' => 'На главной под блоком “Личные блоги”'],
            ['place' => 'На главной под блоком “Корпоративные блоги”'],
        );
        DB::table('banner_places')->insert($places);
    }
}
