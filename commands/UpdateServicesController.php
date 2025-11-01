<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Service;

class UpdateServicesController extends Controller
{
    public function actionIndex()
    {
        $this->stdout("Updating services with categories and diverse images...\n");

        // Різноманітні фото для кожної категорії
        $categoryImages = [
            'Волосся' => [
                'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=400',
                'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=400',
                'https://images.unsplash.com/photo-1562322140-8baeececf3df?w=400',
                'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?w=400',
                'https://images.unsplash.com/photo-1605497788044-5a32c7078486?w=400',
            ],
            'Нігті' => [
                'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=400',
                'https://images.unsplash.com/photo-1610992015732-2449b76344bc?w=400',
                'https://images.unsplash.com/photo-1604902396830-aca29e19b067?w=400',
                'https://images.unsplash.com/photo-1519014816548-bf5fe059798b?w=400',
            ],
            'Брови та вії' => [
                'https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?w=400',
                'https://images.unsplash.com/photo-1588513079159-f226301e266f?w=400',
                'https://images.unsplash.com/photo-1620122645959-9b5c8e41a765?w=400',
            ],
            'Макіяж' => [
                'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=400',
                'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=400',
                'https://images.unsplash.com/photo-1596704017254-9b121068a7a5?w=400',
            ],
            'Косметологія' => [
                'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=400',
                'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=400',
                'https://images.unsplash.com/photo-1559599101-f09722fb4948?w=400',
            ],
            'Депіляція' => [
                'https://images.unsplash.com/photo-1519699047748-de8e457a634e?w=400',
                'https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=400',
            ],
            'Масаж' => [
                'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=400',
                'https://images.unsplash.com/photo-1600334129128-685c5582fd35?w=400',
                'https://images.unsplash.com/photo-1519824145371-296894a0daa9?w=400',
            ],
            'SPA' => [
                'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=400',
                'https://images.unsplash.com/photo-1596178060810-7404c6cef373?w=400',
                'https://images.unsplash.com/photo-1571772996211-2f02c9727629?w=400',
            ],
            'Солярій' => [
                'https://images.unsplash.com/photo-1522338242992-e1a54906a8da?w=400',
                'https://images.unsplash.com/photo-1509390874777-46ffd6d7c87f?w=400',
            ],
            'Татуаж' => [
                'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=400',
                'https://images.unsplash.com/photo-1519415510236-718bdfcd89c8?w=400',
            ],
        ];

        $categoryKeywords = [
            'Волосся' => ['Стрижка', 'Фарбування', 'Укладання', 'Кучері', 'Локон', 'Зачіск', 'Волосся', 'Кератин', 'Ботокс', 'Ламінування', 'Балаяж', 'Омбре', 'Шатуш', 'Airtouch', 'Тонування', 'Мелірування', 'Полірування', 'Дитяч', 'Вечірн', 'Весільн', 'Голлівуд'],
            'Нігті' => ['Манікюр', 'Педикюр', 'Нарощування нігтів', 'Гель-лак', 'Френч', 'Дизайн нігтів', 'Парафін'],
            'Брови та вії' => ['Коре брів', 'Фарбування брів', 'Фарбування вій', 'Ламінування брів', 'Ламінування вій', 'Нарощування вій', 'Архітектура брів', 'Зняття нарощених вій', 'бров'],
            'Макіяж' => ['макіяж'],
            'Косметологія' => ['Чистка обличчя', 'Пілінг', 'Мезотерапія', 'Біоревіталізація', 'Плазмотерапія', 'Ботокс обличчя', 'губ', 'Контурна пластика', 'Масаж обличчя', 'гуаша', 'Альгінатна маска'],
            'Депіляція' => ['Шугарінг', 'Ваксинг', 'епіляція'],
            'Масаж' => ['Масаж', 'LPG', 'Пресотерапія'],
            'SPA' => ['SPA', 'Хамам', 'Сауна', 'Обгортання', 'Скраб'],
            'Солярій' => ['Солярій', 'засмага'],
            'Татуаж' => ['Татуаж', 'Видалення татуажу'],
        ];

        $services = Service::find()->all();
        $updated = 0;
        $categoryCounters = [];

        foreach ($services as $service) {
            $category = null;

            foreach ($categoryKeywords as $cat => $keywords) {
                foreach ($keywords as $keyword) {
                    if (stripos($service->name, $keyword) !== false || stripos($service->description, $keyword) !== false) {
                        $category = $cat;
                        break 2;
                    }
                }
            }

            if ($category) {
                // Ініціалізуємо лічильник для категорії
                if (!isset($categoryCounters[$category])) {
                    $categoryCounters[$category] = 0;
                }

                // Вибираємо різні фото для різних послуг в категорії
                $imageIndex = $categoryCounters[$category] % count($categoryImages[$category]);

                $service->category = $category;
                $service->image_url = $categoryImages[$category][$imageIndex];

                if ($service->save(false)) {
                    $updated++;
                    $categoryCounters[$category]++;

                    if ($updated % 20 == 0) {
                        $this->stdout("  Updated $updated services...\n");
                    }
                }
            }
        }

        $this->stdout("\n✅ Updated $updated services!\n\n");
        $this->stdout("Services per category:\n");
        foreach ($categoryCounters as $cat => $count) {
            $this->stdout("  $cat: $count\n");
        }
    }
}
