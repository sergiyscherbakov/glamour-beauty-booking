<?php

use yii\db\Migration;

class m251026_114417_add_sample_services_for_all_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $services = [
            // Волосся
            ['name' => 'Стрижка жіноча', 'description' => 'Професійна стрижка від топових майстрів', 'category' => 'Волосся', 'duration' => 60, 'price' => 350, 'image_url' => 'https://images.unsplash.com/photo-1562322140-8baeececf3df'],
            ['name' => 'Фарбування волосся', 'description' => 'Балаяж, омбре, однотонне', 'category' => 'Волосся', 'duration' => 180, 'price' => 800, 'image_url' => 'https://images.unsplash.com/photo-1519415510236-718bdfcd89c8'],
            ['name' => 'Укладка волосся', 'description' => 'Професійна укладка для будь-якої події', 'category' => 'Волосся', 'duration' => 45, 'price' => 250, 'image_url' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035'],
            ['name' => 'Кератинове випрямлення', 'description' => 'Довготривале випрямлення волосся', 'category' => 'Волосся', 'duration' => 240, 'price' => 1200, 'image_url' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e'],

            // Нігті
            ['name' => 'Манікюр гелевий', 'description' => 'Гелевий манікюр з покриттям', 'category' => 'Нігті', 'duration' => 90, 'price' => 250, 'image_url' => 'https://images.unsplash.com/photo-1610992015732-2449b76344bc'],
            ['name' => 'Педикюр класичний', 'description' => 'Класичний педикюр з покриттям', 'category' => 'Нігті', 'duration' => 90, 'price' => 300, 'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881'],
            ['name' => 'Нарощування нігтів', 'description' => 'Гелеве нарощування нігтів', 'category' => 'Нігті', 'duration' => 150, 'price' => 400, 'image_url' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371'],
            ['name' => 'Дизайн нігтів', 'description' => 'Унікальний дизайн на нігтях', 'category' => 'Нігті', 'duration' => 120, 'price' => 350, 'image_url' => 'https://images.unsplash.com/photo-1632345031435-8727f6897d53'],

            // Брови та вії
            ['name' => 'Корекція брів', 'description' => 'Моделювання та фарбування брів', 'category' => 'Брови та вії', 'duration' => 30, 'price' => 150, 'image_url' => 'https://images.unsplash.com/photo-1516975080664-ed2fc6a32937'],
            ['name' => 'Нарощування вій', 'description' => '2D, 3D ефекти, класичне', 'category' => 'Брови та вії', 'duration' => 120, 'price' => 500, 'image_url' => 'https://images.unsplash.com/photo-1596178060810-4df5ea0b73b2'],
            ['name' => 'Ламінування вій', 'description' => 'Довготривалий об\'єм та підкручування', 'category' => 'Брови та вії', 'duration' => 90, 'price' => 400, 'image_url' => 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2'],
            ['name' => 'Фарбування брів та вій', 'description' => 'Стійке фарбування хною або фарбою', 'category' => 'Брови та вії', 'duration' => 40, 'price' => 200, 'image_url' => 'https://images.unsplash.com/photo-1583001308107-12f3002e0c61'],

            // Макіяж
            ['name' => 'Вечірній макіяж', 'description' => 'Професійний макіяж для особливих подій', 'category' => 'Макіяж', 'duration' => 60, 'price' => 600, 'image_url' => 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2'],
            ['name' => 'Весільний макіяж', 'description' => 'Бездоганний образ для вашого весілля', 'category' => 'Макіяж', 'duration' => 90, 'price' => 900, 'image_url' => 'https://images.unsplash.com/photo-1522337660859-02fbefca4702'],
            ['name' => 'Денний макіяж', 'description' => 'Природний макіяж на кожен день', 'category' => 'Макіяж', 'duration' => 45, 'price' => 400, 'image_url' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796'],

            // Косметологія
            ['name' => 'Чистка обличчя', 'description' => 'Механічна, ультразвукова', 'category' => 'Косметологія', 'duration' => 60, 'price' => 450, 'image_url' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15'],
            ['name' => 'Пілінг обличчя', 'description' => 'Хімічний пілінг для оновлення шкіри', 'category' => 'Косметологія', 'duration' => 45, 'price' => 550, 'image_url' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e'],
            ['name' => 'Масаж обличчя', 'description' => 'Релаксуючий та омолоджуючий масаж', 'category' => 'Косметологія', 'duration' => 40, 'price' => 400, 'image_url' => 'https://images.unsplash.com/photo-1559599101-f09722fb4948'],
            ['name' => 'Ботокс', 'description' => 'Ін\'єкції ботулотоксину', 'category' => 'Косметологія', 'duration' => 30, 'price' => 2000, 'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881'],

            // Депіляція
            ['name' => 'Шугарінг ніг', 'description' => 'Цукрова депіляція повністю ніг', 'category' => 'Депіляція', 'duration' => 60, 'price' => 350, 'image_url' => 'https://images.unsplash.com/photo-1522337660859-02fbefca4702'],
            ['name' => 'Ваксинг зони бікіні', 'description' => 'Воскова депіляція зони бікіні', 'category' => 'Депіляція', 'duration' => 30, 'price' => 250, 'image_url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef'],
            ['name' => 'Лазерна епіляція', 'description' => 'Довготривале видалення волосся', 'category' => 'Депіляція', 'duration' => 45, 'price' => 800, 'image_url' => 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2'],

            // Масаж
            ['name' => 'Релакс масаж', 'description' => 'Розслаблюючий масаж всього тіла', 'category' => 'Масаж', 'duration' => 60, 'price' => 500, 'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874'],
            ['name' => 'Антицелюлітний масаж', 'description' => 'Інтенсивний масаж проблемних зон', 'category' => 'Масаж', 'duration' => 45, 'price' => 450, 'image_url' => 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1'],
            ['name' => 'Масаж спини', 'description' => 'Лікувальний масаж спини та шиї', 'category' => 'Масаж', 'duration' => 40, 'price' => 350, 'image_url' => 'https://images.unsplash.com/photo-1600334129128-685c5582fd35'],

            // SPA
            ['name' => 'SPA програма для двох', 'description' => 'Романтичний відпочинок для пари', 'category' => 'SPA', 'duration' => 120, 'price' => 1500, 'image_url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef'],
            ['name' => 'Обгортання водоростями', 'description' => 'Детокс та зволоження шкіри', 'category' => 'SPA', 'duration' => 90, 'price' => 700, 'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874'],
            ['name' => 'Хамам', 'description' => 'Турецька лазня з пілінгом', 'category' => 'SPA', 'duration' => 60, 'price' => 600, 'image_url' => 'https://images.unsplash.com/photo-1571902943202-507ec2618e8f'],

            // Солярій
            ['name' => 'Вертикальний солярій', 'description' => '10 хвилин засмаги', 'category' => 'Солярій', 'duration' => 15, 'price' => 100, 'image_url' => 'https://images.unsplash.com/photo-1522337660859-02fbefca4702'],
            ['name' => 'Горизонтальний солярій', 'description' => '15 хвилин комфортної засмаги', 'category' => 'Солярій', 'duration' => 20, 'price' => 120, 'image_url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef'],

            // Татуаж
            ['name' => 'Татуаж брів', 'description' => 'Перманентний макіяж брів', 'category' => 'Татуаж', 'duration' => 120, 'price' => 1800, 'image_url' => 'https://images.unsplash.com/photo-1516975080664-ed2fc6a32937'],
            ['name' => 'Татуаж губ', 'description' => 'Перманентний макіяж губ', 'category' => 'Татуаж', 'duration' => 150, 'price' => 2000, 'image_url' => 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2'],
            ['name' => 'Татуаж очей', 'description' => 'Перманентна підводка очей', 'category' => 'Татуаж', 'duration' => 120, 'price' => 1500, 'image_url' => 'https://images.unsplash.com/photo-1522337660859-02fbefca4702'],
        ];

        $timestamp = time();
        foreach ($services as $service) {
            $this->insert('{{%services}}', [
                'name' => $service['name'],
                'description' => $service['description'],
                'category' => $service['category'],
                'duration' => $service['duration'],
                'price' => $service['price'],
                'image_url' => $service['image_url'],
                'is_active' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251026_114417_add_sample_services_for_all_categories cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251026_114417_add_sample_services_for_all_categories cannot be reverted.\n";

        return false;
    }
    */
}
