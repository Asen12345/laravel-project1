<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_redactor = [
            //['name_id' => 'admins', 'permission_name' => 'Администраторы/Редакторы'],
            //['name_id' => 'users', 'permission_name' => 'Эксперты/Компании'],
            //['name_id' => 'messages', 'permission_name' => 'Массовая рассылка'],
            ['name_id' => 'news', 'permission_name' => 'Раздел Новости'],
            ['name_id' => 'blogs', 'permission_name' => 'Блоги'],
            ['name_id' => 'all_news', 'permission_name' => 'Все Новости'],
            //['name_id' => 'text_pages', 'permission_name' => 'Текстовые страницы'],
            //['name_id' => 'banners', 'permission_name' => 'Баннеры'],
            //['name_id' => 'settings', 'permission_name' => 'Настройки'],
            //['name_id' => 'anons', 'permission_name' => 'Мероприятия'],
            ['name_id' => 'shop', 'permission_name' => 'Магазин исследований'],
            ['name_id' => 'topics', 'permission_name' => 'Темы'],
            //['name_id' => 'feedback', 'permission_name' => 'Темы сообщений'],
            //['name_id' => 'newsletter', 'permission_name' => 'Новостная рассылка'],
        ];

        foreach ($data_redactor as $permission) {
            DB::table('permissions')->insert([
                'name_id'                  => $permission['name_id'],
                'permission_name'          => $permission['permission_name'],
                'role_name'                => 'redactor',
                'created_at'               => Carbon::now(),
                'updated_at'               => Carbon::now(),
            ]);
        }
    }
}
