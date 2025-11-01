# Налаштування проекту Glamour Beauty Booking Service

## Технологічний стек

### Backend: Yii2 Framework (PHP)
- **Версія PHP**: 8.2.12
- **Framework**: Yii2 (встановлено через Composer)
- **Розташування**: `booking-service/`

#### Де використовується Yii2:

1. **Models (Моделі)** - `booking-service/models/`
   - `Admin.php` - модель адміністратора з хешуванням паролів
   - `Booking.php` - модель бронювання з валідацією та унікальністю
   - `Customer.php` - модель клієнта
   - `Service.php` - модель послуги з категоріями

2. **Controllers (Контролери)** - `booking-service/controllers/api/`
   - `AdminController.php` - API для авторизації адміна (login, logout, check, bookings)
   - `BookingController.php` - REST API для бронювань (CRUD + available-slots)
   - `CustomerController.php` - REST API для клієнтів (CRUD)
   - `ServiceController.php` - REST API для послуг (CRUD з фільтрацією активних)

3. **Migrations (Міграції БД)** - `booking-service/migrations/`
   - `m251026_094722_init.php` - створення таблиць customers, services, bookings
   - `m251026_150945_create_admins_table.php` - створення таблиці admins з дефолтним адміном

4. **Commands (Консольні команди)** - `booking-service/commands/`
   - `SeedController.php` - наповнення БД тестовими даними (33 послуги)

5. **Config (Конфігурація)** - `booking-service/config/`
   - `web.php` - налаштування веб-додатку, CORS, URL rules
   - `db.php` - налаштування підключення до БД
   - `console.php` - налаштування консольних команд

### Database: MySQL
- **Версія**: MySQL Server
- **Підключення**:
  - Host: `localhost`
  - Port: `3306`
  - User: `root`
  - Password: `1234`
  - Database: `booking_service`

#### Де налаштовано підключення:
**Файл**: `booking-service/config/db.php`
```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=booking_service',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

#### Структура бази даних:

**Таблиця `customers`**:
- `id` - первинний ключ
- `name` - ім'я клієнта
- `email` - email (унікальний)
- `phone` - телефон (унікальний)
- `created_at`, `updated_at` - timestamps

**Таблиця `services`**:
- `id` - первинний ключ
- `name` - назва послуги
- `description` - опис
- `category` - категорія (Волосся, Нігті, Брови та вії, Макіяж, Косметологія, Депіляція, Масаж, SPA, Солярій, Татуаж)
- `image_url` - посилання на зображення (Unsplash)
- `duration` - тривалість в хвилинах
- `price` - ціна (decimal 10,2)
- `is_active` - активна чи ні (1/0)
- `created_at`, `updated_at` - timestamps

**Таблиця `bookings`**:
- `id` - первинний ключ
- `user_id` - зовнішній ключ до customers
- `service_id` - зовнішній ключ до services
- `booking_date` - дата бронювання
- `booking_time` - час бронювання
- `status` - статус (pending, confirmed, cancelled, completed)
- `notes` - примітки
- `created_at`, `updated_at` - timestamps
- **Унікальність**: не можна забронювати ту саму послугу на той самий час

**Таблиця `admins`**:
- `id` - первинний ключ
- `login` - логін (унікальний)
- `password_hash` - хеш пароля (bcrypt)
- `name` - ім'я
- `created_at`, `updated_at` - timestamps
- **Дефолтний адмін**: login=`admin`, password=`admin`

### Frontend: Vue.js 3 + Axios
- **Версія**: Vue 3 (CDN)
- **HTTP Client**: Axios (CDN)
- **Розташування**: `booking-service/web/`

#### Де використовується Vue.js:

1. **Головна сторінка** - `web/index.html`
   - Статичний HTML без Vue
   - CSS Grid для відображення послуг
   - Посилання на booking.html

2. **Сторінка бронювання** - `web/booking.html`
   - Vue.js додаток для бронювання
   - Компоненти:
     - Вибір послуги (з фільтрацією по категоріях)
     - Вибір дати (з валідацією минулих дат)
     - Вибір часу (динамічне завантаження доступних слотів)
     - Форма клієнта (ім'я, email, телефон)
   - Axios запити до API:
     - `GET /api/service` - отримання послуг
     - `GET /api/booking/available-slots?date=YYYY-MM-DD` - доступні слоти
     - `POST /api/customer` - створення клієнта
     - `POST /api/booking` - створення бронювання

3. **Сторінка авторизації адміна** - `web/admin-login.html`
   - Vue.js форма логіну
   - Axios запит:
     - `POST /api/admin/login` (з `withCredentials: true`)
   - Редирект на admin-dashboard.html після успіху

4. **Панель адміністратора** - `web/admin-dashboard.html`
   - Vue.js додаток для управління бронюваннями
   - Функції:
     - Перевірка автентифікації
     - Відображення всіх бронювань в таблиці
     - Статистика по статусах
     - Фільтрація по статусу
     - Пошук по імені клієнта
     - Вихід з системи
   - Axios запити:
     - `GET /api/admin/check` - перевірка автентифікації
     - `GET /api/admin/bookings` - всі бронювання
     - `POST /api/admin/logout` - вихід

## Початкове налаштування проекту

### 1. Створення бази даних

**ВАЖЛИВО**: База даних НЕ створюється автоматично! Треба створити вручну.

#### Крок 1: Створити базу даних
```bash
mysql -u root -p
# Ввести пароль: 1234
```

```sql
CREATE DATABASE booking_service CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### Крок 2: Перевірити підключення
Перевірте файл `booking-service/config/db.php` і переконайтесь, що дані підключення правильні:
- host: `localhost`
- database: `booking_service`
- username: `root`
- password: `1234`

### 2. Запуск міграцій

Міграції створюють таблиці та наповнюють їх початковими даними.

```bash
cd booking-service

# Запустити всі міграції
php yii migrate

# Підтвердити: yes
```

Це створить таблиці:
- `customers`
- `services`
- `bookings`
- `admins` (з дефолтним адміном admin/admin)

### 3. Наповнення бази даних послугами

```bash
# Додати 33 послуги в 10 категоріях
php yii seed/services
```

Це додасть послуги в категоріях:
- Волосся (4 послуги)
- Нігті (4 послуги)
- Брови та вії (4 послуги)
- Макіяж (3 послуги)
- Косметологія (4 послуги)
- Депіляція (3 послуги)
- Масаж (3 послуги)
- SPA (3 послуги)
- Солярій (2 послуги)
- Татуаж (3 послуги)

### 4. Запуск сервера

```bash
cd booking-service

# Запустити вбудований PHP сервер
php yii serve --port=8080
```

Сервер буде доступний на: `http://localhost:8080/`

### 5. Доступ до адмін панелі

1. Відкрити: `http://localhost:8080/admin-login.html`
2. Логін: `admin`
3. Пароль: `admin`
4. Після входу відкриється панель з усіма бронюваннями

## API Endpoints

### Публічні endpoints:

**Services (Послуги)**:
- `GET /api/service` - всі активні послуги
- `GET /api/service/{id}` - одна послуга
- `POST /api/service` - створити послугу
- `PUT /api/service/{id}` - оновити послугу
- `DELETE /api/service/{id}` - видалити послугу

**Customers (Клієнти)**:
- `GET /api/customer` - всі клієнти
- `GET /api/customer/{id}` - один клієнт
- `POST /api/customer` - створити клієнта
- `PUT /api/customer/{id}` - оновити клієнта
- `DELETE /api/customer/{id}` - видалити клієнта

**Bookings (Бронювання)**:
- `GET /api/booking` - всі бронювання
- `GET /api/booking/{id}` - одне бронювання
- `POST /api/booking` - створити бронювання
- `PUT /api/booking/{id}` - оновити бронювання
- `DELETE /api/booking/{id}` - видалити бронювання
- `GET /api/booking/available-slots?date=YYYY-MM-DD` - доступні часові слоти

### Адміністративні endpoints (потребують автентифікації):

**Admin**:
- `POST /api/admin/login` - авторизація
- `POST /api/admin/logout` - вихід
- `GET /api/admin/check` - перевірка автентифікації
- `GET /api/admin/bookings` - всі бронювання (з клієнтами та послугами)

## Важливі особливості

### CORS налаштування
- Всі API контролери мають CORS фільтри
- AdminController дозволяє тільки `http://localhost:8080` з credentials
- Інші контролери дозволяють `*` (wildcard)

### Сесії для адміна
- Використовується стандартна PHP сесія
- Cookie `PHPSESSID` зберігає сесію
- `withCredentials: true` потрібен в axios запитах

### Валідація дат
- Не можна вибрати минулу дату для бронювання
- Перевірка на клієнті (JavaScript) та сервері (PHP)

### Унікальність бронювань
- Не можна забронювати ту саму послугу на той самий час
- Скасовані бронювання не враховуються

## Структура проекту

```
booking-service/
├── commands/               # Консольні команди
│   └── SeedController.php  # Наповнення БД
├── config/                 # Конфігурація
│   ├── web.php            # Веб додаток
│   ├── db.php             # База даних
│   └── console.php        # Консольні команди
├── controllers/
│   └── api/               # REST API контролери
│       ├── AdminController.php
│       ├── BookingController.php
│       ├── CustomerController.php
│       └── ServiceController.php
├── migrations/            # Міграції БД
│   ├── m251026_094722_init.php
│   └── m251026_150945_create_admins_table.php
├── models/                # ActiveRecord моделі
│   ├── Admin.php
│   ├── Booking.php
│   ├── Customer.php
│   └── Service.php
├── runtime/               # Логи та кеш
│   └── logs/
│       └── app.log
├── vendor/                # Composer залежності
├── web/                   # Публічна директорія
│   ├── index.html         # Головна сторінка
│   ├── booking.html       # Сторінка бронювання (Vue.js)
│   ├── admin-login.html   # Логін адміна (Vue.js)
│   └── admin-dashboard.html # Панель адміна (Vue.js)
└── yii                    # Консольний скрипт
```

## Troubleshooting

### Помилка підключення до БД
```
SQLSTATE[HY000] [1049] Unknown database 'booking_service'
```
**Рішення**: Створіть базу даних вручну (див. "Створення бази даних")

### Помилка CORS при авторизації
```
InvalidConfigException: Allowing credentials for wildcard origins is insecure
```
**Рішення**: Перевірте, що в AdminController використовується конкретний origin, а не `*`

### Не встановлюється cookie
**Рішення**: Додайте `withCredentials: true` до всіх axios запитів для адміна

### Порт 8080 зайнятий
```bash
# Використати інший порт
php yii serve --port=8081

# Або знайти та вбити процес
netstat -ano | findstr :8080
taskkill /PID <PID> /F
```

## Використані технології та бібліотеки

- **Yii2 Framework** - PHP framework для backend
- **Vue.js 3** - JavaScript framework для frontend
- **Axios** - HTTP client для AJAX запитів
- **MySQL** - Реляційна база даних
- **Composer** - Менеджер залежностей для PHP
- **Unsplash** - Безкоштовні зображення для послуг

## Автор
Glamour Beauty Booking Service v1.0
