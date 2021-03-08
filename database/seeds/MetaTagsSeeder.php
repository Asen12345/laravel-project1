<?php

use Illuminate\Database\Seeder;

class MetaTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meta_tags')->insert([
            [
                'name'               => 'Главная',
                'meta_id'            => 'meta_main',
                'meta_title'         => 'Мета тайтл Главная',
                'meta_keywords'      => 'Мета кейвордс Главная',
                'meta_description'   => 'Мета дескриптион Главная'
            ],[
                'name'               => 'Новости',
                'meta_id'            => 'meta_news',
                'meta_title'         => 'Мета тайтл Новости',
                'meta_keywords'      => 'Мета кейвордс Новости',
                'meta_description'   => 'Мета дескриптион Новости'
            ],[
                'name'               => 'Блоги',
                'meta_id'            => 'meta_blogs',
                'meta_title'         => 'Мета тайтл Блоги',
                'meta_keywords'      => 'Мета кейвордс Блоги',
                'meta_description'   => 'Мета дескриптион Блоги'
            ],[
                'name'               => 'Личные блоги',
                'meta_id'            => 'meta_expert_blog',
                'meta_title'         => 'Мета тайтл Личные блоги',
                'meta_keywords'      => 'Мета кейвордс Личные блоги',
                'meta_description'   => 'Мета дескриптион Личные блоги'
            ],[
                'name'               => 'Корпоративные блоги',
                'meta_id'            => 'meta_company_blog',
                'meta_title'         => 'Мета тайтл Корпоративные блоги',
                'meta_keywords'      => 'Мета кейвордс Корпоративные блоги',
                'meta_description'   => 'Мета дескриптион Корпоративные блоги'
            ],[
                'name'               => 'Все записи блогов',
                'meta_id'            => 'meta_blog_posts',
                'meta_title'         => 'Мета тайтл Все записи блогов',
                'meta_keywords'      => 'Мета кейвордс Все записи блогов',
                'meta_description'   => 'Мета дескриптион Все записи блогов'
            ],[
                'name'               => 'Новые записи блогов',
                'meta_id'            => 'meta_blog_posts_newest',
                'meta_title'         => 'Мета тайтл Новые записи блогов',
                'meta_keywords'      => 'Мета кейвордс Новые записи блогов',
                'meta_description'   => 'Мета дескриптион Новые записи блогов'
            ],[
                'name'               => 'Читаемые записи блогов',
                'meta_id'            => 'meta_blog_posts_popular',
                'meta_title'         => 'Мета тайтл Читаемые записи блогов',
                'meta_keywords'      => 'Мета кейвордс Читаемые записи блогов',
                'meta_description'   => 'Мета дескриптион Читаемые записи блогов'
            ],[
                'name'               => 'Обсуждаемые записи блогов',
                'meta_id'            => 'meta_blog_posts_discussed',
                'meta_title'         => 'Мета тайтл Обсуждаемые записи блогов',
                'meta_keywords'      => 'Мета кейвордс Обсуждаемые записи блогов',
                'meta_description'   => 'Мета дескриптион Обсуждаемые записи блогов'
            ],[
                'name'               => 'Рейтинг читателей блогов',
                'meta_id'            => 'meta_blog_posts_rate',
                'meta_title'         => 'Мета тайтл Рейтинг читателей блогов',
                'meta_keywords'      => 'Мета кейвордс Рейтинг читателей блогов',
                'meta_description'   => 'Мета дескриптион Рейтинг читателей блогов'
            ],[
                'name'               => 'Мероприятия',
                'meta_id'            => 'meta_anons',
                'meta_title'         => 'Мета тайтл Мероприятия',
                'meta_keywords'      => 'Мета кейвордс Мероприятия',
                'meta_description'   => 'Мета дескриптион Мероприятия'
            ],[
                'name'               => 'Все темы',
                'meta_id'            => 'meta_topics',
                'meta_title'         => 'Мета тайтл Все темы',
                'meta_keywords'      => 'Мета кейвордс Все темы',
                'meta_description'   => 'Мета дескриптион Все темы'
            ],[
                'name'               => 'Обратная связь',
                'meta_id'            => 'meta_feedback',
                'meta_title'         => 'Мета тайтл Обратная связь',
                'meta_keywords'      => 'Мета кейвордс Обратная связь',
                'meta_description'   => 'Мета дескриптион Обратная связь'
            ],[
                'name'               => 'Главная страница магазина',
                'meta_id'            => 'meta_researches_main',
                'meta_title'         => 'Мета тайтл Главная страница магазина',
                'meta_keywords'      => 'Мета кейвордс Главная страница магазина',
                'meta_description'   => 'Мета дескриптион Главная страница магазина'
            ],[
                'name'               => 'Авторы исследований',
                'meta_id'            => 'meta_researches_author',
                'meta_title'         => 'Мета тайтл Авторы исследований',
                'meta_keywords'      => 'Мета кейвордс Авторы исследований',
                'meta_description'   => 'Мета дескриптион Авторы исследований'
            ],
        ]);
    }
}
