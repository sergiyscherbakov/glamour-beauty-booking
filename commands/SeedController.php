<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Service;
use app\models\Customer;
use app\models\Booking;

/**
 * Seed demo data
 */
class SeedController extends Controller
{
    /**
     * Seed demo data for booking service
     */
    public function actionIndex()
    {
        $this->stdout("Starting to seed demo data...\n");

        // Clear existing data
        $this->stdout("Clearing existing data...\n");
        Booking::deleteAll();
        Customer::deleteAll();
        Service::deleteAll();

        // Seed Services
        $this->stdout("Creating services...\n");
        $services = [
            [
                'name' => 'Стрижка чоловіча',
                'description' => 'Класична чоловіча стрижка з укладанням',
                'duration' => 30,
                'price' => 250.00,
                'is_active' => 1,
            ],
            [
                'name' => 'Стрижка жіноча',
                'description' => 'Жіноча стрижка будь-якої складності',
                'duration' => 60,
                'price' => 450.00,
                'is_active' => 1,
            ],
            [
                'name' => 'Фарбування волосся',
                'description' => 'Професійне фарбування якісними матеріалами',
                'duration' => 120,
                'price' => 800.00,
                'is_active' => 1,
            ],
            [
                'name' => 'Манікюр класичний',
                'description' => 'Класичний манікюр з покриттям',
                'duration' => 45,
                'price' => 350.00,
                'is_active' => 1,
            ],
            [
                'name' => 'Педикюр',
                'description' => 'Професійний педикюр',
                'duration' => 60,
                'price' => 400.00,
                'is_active' => 1,
            ],
            [
                'name' => 'Масаж релакс',
                'description' => 'Розслаблюючий масаж всього тіла',
                'duration' => 90,
                'price' => 700.00,
                'is_active' => 1,
            ],
        ];

        foreach ($services as $serviceData) {
            $service = new Service();
            $service->attributes = $serviceData;
            if ($service->save()) {
                $this->stdout("  ✓ Created service: {$service->name}\n");
            } else {
                $this->stdout("  ✗ Failed to create service: " . print_r($service->errors, true) . "\n");
            }
        }

        // Seed Customers
        $this->stdout("\nCreating customers...\n");
        $customers = [
            [
                'name' => 'Іван Петренко',
                'email' => 'ivan.petrenko@example.com',
                'phone' => '+380501234567',
            ],
            [
                'name' => 'Марія Коваленко',
                'email' => 'maria.kovalenko@example.com',
                'phone' => '+380502345678',
            ],
            [
                'name' => 'Олександр Шевченко',
                'email' => 'oleksandr.shevchenko@example.com',
                'phone' => '+380503456789',
            ],
            [
                'name' => 'Олена Бондаренко',
                'email' => 'olena.bondarenko@example.com',
                'phone' => '+380504567890',
            ],
            [
                'name' => 'Андрій Мельник',
                'email' => 'andriy.melnyk@example.com',
                'phone' => '+380505678901',
            ],
        ];

        foreach ($customers as $customerData) {
            $customer = new Customer();
            $customer->attributes = $customerData;
            if ($customer->save()) {
                $this->stdout("  ✓ Created customer: {$customer->name}\n");
            } else {
                $this->stdout("  ✗ Failed to create customer: " . print_r($customer->errors, true) . "\n");
            }
        }

        // Seed Bookings
        $this->stdout("\nCreating bookings...\n");

        $allServices = Service::find()->all();
        $allCustomers = Customer::find()->all();

        if (empty($allServices) || empty($allCustomers)) {
            $this->stdout("  ✗ Cannot create bookings: no services or customers available\n");
            return;
        }

        // Create bookings for next 7 days
        $startDate = new \DateTime();
        $bookings = [];

        for ($day = 0; $day < 7; $day++) {
            $date = clone $startDate;
            $date->modify("+{$day} days");

            // Create 3-5 random bookings per day
            $bookingsPerDay = rand(3, 5);

            for ($i = 0; $i < $bookingsPerDay; $i++) {
                $hour = rand(9, 17);
                $time = sprintf('%02d:00:00', $hour);

                // Check if this slot is already booked
                $exists = false;
                foreach ($bookings as $b) {
                    if ($b['booking_date'] === $date->format('Y-m-d') && $b['booking_time'] === $time) {
                        $exists = true;
                        break;
                    }
                }

                if ($exists) continue;

                $service = $allServices[array_rand($allServices)];
                $customer = $allCustomers[array_rand($allCustomers)];
                $statuses = ['pending', 'confirmed', 'completed'];
                $status = $statuses[array_rand($statuses)];

                $bookings[] = [
                    'user_id' => $customer->id,
                    'service_id' => $service->id,
                    'booking_date' => $date->format('Y-m-d'),
                    'booking_time' => $time,
                    'status' => $status,
                    'notes' => rand(0, 1) ? 'Demo booking note' : null,
                ];
            }
        }

        foreach ($bookings as $bookingData) {
            $booking = new Booking();
            $booking->attributes = $bookingData;
            if ($booking->save()) {
                $this->stdout("  ✓ Created booking: {$bookingData['booking_date']} {$bookingData['booking_time']}\n");
            } else {
                $this->stdout("  ✗ Failed to create booking: " . print_r($booking->errors, true) . "\n");
            }
        }

        $this->stdout("\n✓ Demo data seeding completed!\n");
        $this->stdout("  Services: " . count($services) . "\n");
        $this->stdout("  Customers: " . count($customers) . "\n");
        $this->stdout("  Bookings: " . count($bookings) . "\n");
    }

    /**
     * Seed all beauty services with categories and images
     */
    public function actionServices()
    {
        $this->stdout("Seeding beauty services...\n");

        // Delete existing services first
        Service::deleteAll();
        $this->stdout("Deleted existing services.\n");

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

        $count = 0;
        foreach ($services as $serviceData) {
            $service = new Service();
            $service->name = $serviceData['name'];
            $service->description = $serviceData['description'];
            $service->category = $serviceData['category'];
            $service->duration = $serviceData['duration'];
            $service->price = $serviceData['price'];
            $service->image_url = $serviceData['image_url'];
            $service->is_active = 1;

            if ($service->save()) {
                $count++;
                $this->stdout("  ✓ Added: {$service->name} ({$service->category})\n");
            } else {
                $this->stdout("  ✗ Failed to add: {$serviceData['name']}\n");
                print_r($service->errors);
            }
        }

        $this->stdout("\n✓ Total services added: $count\n");
        $this->stdout("Seeding completed!\n");
    }
}
