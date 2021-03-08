<?php

use Illuminate\Database\Seeder;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $places = array(
            [
                'name'      => 'Виджет меню в сайдбаре',
                'type'      => 'side_bar',
                'published' => true,
                'all_blogs' => true,
                'blogs_id'  => null,
                'text'      => '<div class="categories-menu-block">
    <div class="sidebar-standart-title">Категории</div>
    <ul class="sf-menu sf-vertical categories-menu mnu-standart-style-reset sf-js-enabled sf-arrows" style="touch-action: pan-y;">
        <li><a href="http://test5.ipnino.ru/news" class="categories-menu__link">Весь каталог</a></li>
                    <li class=""><a href="http://test5.ipnino.ru/news/index/section/securitization" class="categories-menu__link sf-with-ul">Секьюритизация</a>
                                    <ul class="categories-menu__sub" style="display: none;">
                                                    <li class="categories-menu__item-sub">
                                <a href="http://test5.ipnino.ru/news/index/section/securitization/securitization2" class="categories-menu__sublink">Секьюритизация второй уровень</a>
                            </li>
                                            </ul>
                            </li>
                    <li><a href="http://test5.ipnino.ru/news/index/section/mortgage" class="categories-menu__link">Ипотека и строительство</a>
                            </li>
            </ul>
</div>'

            ],
        );
        DB::table('widgets')->insert($places);
    }
}
