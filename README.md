# Glamour Beauty - Booking Service

![PHP](https://img.shields.io/badge/PHP-8.2.12-777BB4?style=for-the-badge&logo=php&logoColor=white) ![Yii2](https://img.shields.io/badge/Yii2-2.0.49-0073AA?style=for-the-badge&logo=yii&logoColor=white) ![Vue.js](https://img.shields.io/badge/Vue.js-3.0-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white) ![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white) ![Composer](https://img.shields.io/badge/Composer-2.0-885630?style=for-the-badge&logo=composer&logoColor=white) ![Axios](https://img.shields.io/badge/Axios-1.6-5A29E4?style=for-the-badge&logo=axios&logoColor=white)

**Glamour Beauty Booking Service** – веб-додаток для онлайн-бронювання послуг салону краси, що дозволяє клієнтам обирати послуги з 10 категорій (волосся, нігті, брови та вії, макіяж, косметологія, депіляція, масаж, SPA, солярій, татуаж), переглядати доступні часові слоти в реальному часі та створювати бронювання через інтуїтивний інтерфейс, а також надає адміністраторам панель управління для перегляду всіх замовлень з фільтрацією та пошуком.

**Стек технологій:** Backend – Yii2 Framework (PHP 8.2.12) з REST API архітектурою, Frontend – Vue.js 3.0 з Axios для HTTP-запитів, Database – MySQL 8.0, Package Manager – Composer 2.0, Server – PHP Built-in Development Server, Images – Unsplash API для зображень послуг.

## Зміст

- [Системні вимоги](#системні-вимоги)
- [Встановлення](#встановлення)
- [Структура проекту](#структура-проекту)
- [Використання API](#використання-api)
- [Тестування](#тестування)
- [Приклади використання](#приклади-використання)
- [Troubleshooting](#troubleshooting)

---

## Системні вимоги

### Необхідне ПЗ:

1. **PHP** версії 8.0 або вище
   - Перевірка: `php -v`
   - Завантажити: https://www.php.net/downloads

2. **Composer** версії 2.0 або вище
   - Перевірка: `composer -V`
   - Завантажити: https://getcomposer.org/download/

3. **MySQL** версії 8.0 або вище
   - Перевірка: `mysql --version`
   - Завантажити: https://dev.mysql.com/downloads/mysql/

4. **Git** (для клонування репозиторію)
   - Перевірка: `git --version`
   - Завантажити: https://git-scm.com/downloads

---

## Встановлення

### Крок 1: Клонування репозиторію

```bash
# Клонувати репозиторій
git clone https://github.com/sergiyscherbakov/glamour-beauty-booking.git

# Перейти в директорію проекту
cd glamour-beauty-booking
```

### Крок 2: Встановлення залежностей

```bash
# Встановити Composer залежності
composer install

# Якщо виникає помилка, спробуйте оновити Composer
composer update
```

**Очікуваний результат:**
```
Loading composer repositories with package information
Installing dependencies from lock file
...
Generating autoload files
```

### Крок 3: Створення бази даних

```bash
# Підключитися до MySQL
mysql -u root -p
# Введіть ваш пароль MySQL
```

```sql
-- Створити базу даних
CREATE DATABASE booking_service CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Перевірити, що база створена
SHOW DATABASES;

-- Вийти
EXIT;
```

**Очікуваний результат:**
```
Query OK, 1 row affected (0.01 sec)
```

### Крок 4: Налаштування підключення до БД

Відкрийте файл `config/db.php` та відредагуйте параметри підключення:

```php
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=booking_service',
    'username' => 'root',           // Ваш MySQL username
    'password' => '1234',           // Ваш MySQL password
    'charset' => 'utf8',
];
```

**Перевірка підключення:**
```bash
php yii migrate/create test
# Якщо з'єднання успішне, побачите запит на створення міграції
# Натисніть Ctrl+C для скасування
```

### Крок 5: Запуск міграцій

```bash
# Запустити міграції для створення таблиць
php yii migrate

# Підтвердіть міграцію
# >>> yes
```

**Очікуваний результат:**
```
Yii Migration Tool (based on Yii v2.0.49)

Total 4 new migrations to be applied:
    m251026_101051_create_users_table
    m251026_101054_create_services_table
    m251026_101057_create_bookings_table
    m251026_150945_create_admins_table

Apply the above migrations? (yes|no) [no]:yes
*** applying m251026_101051_create_users_table
    > create table {{%customers}} ... done (time: 0.123s)
*** applied m251026_101051_create_users_table (time: 0.234s)
...
Migrated up successfully.
```

**Перевірка створених таблиць:**
```bash
mysql -u root -p booking_service -e "SHOW TABLES;"
```

**Очікуваний результат:**
```
+---------------------------+
| Tables_in_booking_service |
+---------------------------+
| admins                    |
| bookings                  |
| customers                 |
| migration                 |
| services                  |
+---------------------------+
```

### Крок 6: Наповнення бази даних

```bash
# Додати 33 послуги в 10 категоріях
php yii seed/services
```

**Очікуваний результат:**
```
Deleting existing services...
Deleted 0 existing services.
Adding 33 new services...
Services added successfully!
```

**Перевірка даних:**
```bash
mysql -u root -p booking_service -e "SELECT category, COUNT(*) as count FROM services GROUP BY category;"
```

**Очікуваний результат:**
```
+----------------+-------+
| category       | count |
+----------------+-------+
| Волосся        |     4 |
| Нігті          |     4 |
| Брови та вії   |     4 |
| Макіяж         |     3 |
| Косметологія   |     4 |
| Депіляція      |     3 |
| Масаж          |     3 |
| SPA            |     3 |
| Солярій        |     2 |
| Татуаж         |     3 |
+----------------+-------+
```

### Крок 7: Запуск сервера

```bash
# Запустити вбудований PHP сервер
php yii serve --port=8080
```

**Очікуваний результат:**
```
Server started on http://localhost:8080/
Document root is "C:\path\to\booking-service/web"
Quit the server with CTRL-C or COMMAND-C.
```

### Крок 8: Перевірка роботи

1. **Головна сторінка:** http://localhost:8080/
2. **Сторінка бронювання:** http://localhost:8080/booking.html
3. **API послуг:** http://localhost:8080/api/service
4. **Адмін панель:** http://localhost:8080/admin-login.html
   - Логін: `admin`
   - Пароль: `admin`

---

## Структура проекту

### Детальний опис файлів та директорій:

```
booking-service/
│
├── commands/                           # Консольні команди Yii2
│   ├── HelloController.php            # Приклад консольної команди (стандартний Yii2)
│   ├── SeedController.php             # Команда для наповнення БД послугами
│   ├── SeedBeautyServicesController.php # Старий seed (не використовується)
│   └── UpdateServicesController.php   # Утиліта для оновлення послуг
│
├── config/                             # Конфігурація додатку
│   ├── web.php                        # Конфігурація веб-додатку (URL rules, CORS)
│   ├── console.php                    # Конфігурація консольних команд
│   ├── db.php                         # Налаштування підключення до MySQL
│   ├── params.php                     # Глобальні параметри додатку
│   ├── test.php                       # Конфігурація для тестів
│   ├── test_db.php                    # База даних для тестів
│   └── __autocomplete.php             # Автокомпліт для IDE
│
├── controllers/                        # Контролери
│   ├── SiteController.php             # Контролер для стандартних сторінок Yii2
│   └── api/                           # REST API контролери
│       ├── AdminController.php        # Авторизація адміна (login, logout, check, bookings)
│       ├── BookingController.php      # CRUD бронювань + available-slots
│       ├── CustomerController.php     # CRUD клієнтів
│       └── ServiceController.php      # CRUD послуг (фільтр активних)
│
├── migrations/                         # Міграції бази даних
│   ├── m251026_101051_create_users_table.php      # Створення таблиці customers
│   ├── m251026_101054_create_services_table.php   # Створення таблиці services
│   ├── m251026_101057_create_bookings_table.php   # Створення таблиці bookings
│   ├── m251026_112445_add_category_and_image_to_services.php  # Додавання category, image_url
│   ├── m251026_114417_add_sample_services_for_all_categories.php # Старий seed
│   └── m251026_150945_create_admins_table.php     # Створення таблиці admins
│
├── models/                             # ActiveRecord моделі
│   ├── Admin.php                      # Модель адміністратора (validatePassword, findByLogin)
│   ├── Booking.php                    # Модель бронювання (валідація унікальності, статуси)
│   ├── Customer.php                   # Модель клієнта (валідація email, phone)
│   ├── Service.php                    # Модель послуги (категорії, is_active)
│   ├── User.php                       # Стандартна модель User (для Yii2 auth)
│   ├── LoginForm.php                  # Форма логіну (стандартний Yii2)
│   └── ContactForm.php                # Форма контактів (стандартний Yii2)
│
├── runtime/                            # Тимчасові файли, логи, кеш
│   └── logs/
│       └── app.log                    # Логи додатку
│
├── tests/                              # Тести
│   ├── unit/                          # Юніт тести
│   │   └── models/                    # Тести моделей
│   │       ├── BookingTest.php        # Тести моделі Booking
│   │       ├── CustomerTest.php       # Тести моделі Customer
│   │       └── ServiceTest.php        # Тести моделі Service
│   ├── functional/                    # Функціональні тести
│   │   ├── LoginFormCest.php          # Тести форми логіну
│   │   └── ContactFormCest.php        # Тести контактної форми
│   └── acceptance/                    # Acceptance тести
│       ├── HomeCest.php               # Тести головної сторінки
│       └── LoginCest.php              # Тести логіну
│
├── views/                              # Шаблони Yii2 (не використовуються в API)
│   ├── layouts/
│   │   └── main.php                   # Основний layout
│   └── site/                          # Стандартні сторінки Yii2
│       ├── index.php                  # Головна сторінка (Yii2)
│       ├── about.php                  # Про нас
│       ├── contact.php                # Контакти
│       └── error.php                  # Сторінка помилки
│
├── web/                                # Публічна директорія (document root)
│   ├── index.php                      # Entry point додатку (Yii2 bootstrap)
│   ├── .htaccess                      # Apache rewrite rules
│   ├── css/
│   │   └── site.css                   # Стилі для Yii2 сторінок
│   ├── index.html                     # Головна сторінка (Vue.js) - перелік послуг
│   ├── booking.html                   # Сторінка бронювання (Vue.js) - форма бронювання
│   ├── admin-login.html               # Сторінка логіну адміна (Vue.js)
│   ├── admin-dashboard.html           # Панель адміна (Vue.js) - таблиця бронювань
│   ├── favicon.ico                    # Іконка сайту
│   └── robots.txt                     # Правила для пошукових роботів
│
├── .gitignore                          # Git ignore rules
├── README.md                           # Цей файл
├── SETUP.md                            # Детальна документація для розробників
├── LICENSE.md                          # MIT ліцензія
├── composer.json                       # Composer залежності
├── codeception.yml                     # Конфігурація Codeception
├── requirements.php                    # Перевірка серверних вимог
├── yii                                 # Консольний скрипт (Unix)
└── yii.bat                             # Консольний скрипт (Windows)
```

---

## Використання API

### Базовий URL
```
http://localhost:8080/api
```

### 1. Послуги (Services)

#### Отримати всі активні послуги
```bash
curl -X GET http://localhost:8080/api/service
```

**Відповідь (приклад):**
```json
[
  {
    "id": 108,
    "name": "Стрижка жіноча",
    "description": "Професійна стрижка від топових майстрів",
    "category": "Волосся",
    "image_url": "https://images.unsplash.com/photo-1562322140-8baeececf3df",
    "duration": 60,
    "price": "350.00",
    "is_active": 1,
    "created_at": 1761492757,
    "updated_at": 1761492757
  }
]
```

#### Створити послугу
```bash
curl -X POST http://localhost:8080/api/service \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Нова послуга",
    "description": "Опис послуги",
    "category": "Волосся",
    "duration": 60,
    "price": 400,
    "is_active": 1
  }'
```

### 2. Клієнти (Customers)

#### Створити клієнта
```bash
curl -X POST http://localhost:8080/api/customer \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Іван Петренко",
    "email": "ivan@example.com",
    "phone": "+380501234567"
  }'
```

### 3. Бронювання (Bookings)

#### Перевірити доступні слоти
```bash
curl -X GET "http://localhost:8080/api/booking/available-slots?date=2025-11-01"
```

**Відповідь:**
```json
["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"]
```

#### Створити бронювання
```bash
curl -X POST http://localhost:8080/api/booking \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "service_id": 108,
    "booking_date": "2025-11-01",
    "booking_time": "10:00",
    "notes": "Прошу майстра Олену"
  }'
```

### 4. Адміністрація (Admin)

#### Авторизація адміна
```bash
curl -X POST http://localhost:8080/api/admin/login \
  -H "Content-Type: application/json" \
  -c cookies.txt \
  -d '{"login":"admin","password":"admin"}'
```

---

## Тестування

### Запуск всіх тестів

```bash
vendor/bin/codecept run
```

### Юніт тести

```bash
vendor/bin/codecept run unit
vendor/bin/codecept run unit models/BookingTest
vendor/bin/codecept run unit models/CustomerTest
vendor/bin/codecept run unit models/ServiceTest
```

---

## Приклади використання

### Повний цикл бронювання

```bash
# 1. Переглянути послуги
curl http://localhost:8080/api/service

# 2. Перевірити доступні слоти
curl "http://localhost:8080/api/booking/available-slots?date=2025-11-01"

# 3. Створити клієнта
curl -X POST http://localhost:8080/api/customer \
  -H "Content-Type: application/json" \
  -d '{"name":"Марія Іваненко","email":"maria@example.com","phone":"+380509876543"}'

# 4. Створити бронювання
curl -X POST http://localhost:8080/api/booking \
  -H "Content-Type: application/json" \
  -d '{"user_id":5,"service_id":108,"booking_date":"2025-11-01","booking_time":"14:00"}'
```

---

## Troubleshooting

### Помилка підключення до БД
```bash
mysql -u root -p
CREATE DATABASE booking_service CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Порт зайнятий
```bash
php yii serve --port=8081
```

### Очистити кеш
```bash
php yii cache/flush-all
```

---

**Детальна документація:** [SETUP.md](SETUP.md)

## Автор

**Розробник:** Сергій Щербаков
**Email:** sergiyscherbakov@ukr.net
**Telegram:** @s_help_2010

### 💰 Підтримати розробку
Задонатити на каву USDT (BINANCE SMART CHAIN):
**`0xDFD0A23d2FEd7c1ab8A0F9A4a1F8386832B6f95A`**
