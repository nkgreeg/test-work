# Posiflora test work 

## Up local

### Setup

### Up db

`docker compose -f compose.yaml -f compose.override.yaml up`

### Start

## 

`composer install && cp .env .env.local` (отредактируйте DATABASE_URL), затем `php bin/console doctrine:database:create`, `php bin/console doctrine:migrations:migrate`, `php bin/console doctrine:fixtures:load`, `symfony serve`

## Запуск frontend
Frontend — статическая страница на React, отдаётся через Symfony. Открыть в браузере: `http://localhost:8000/shops/{shopId}/growth/telegram` (пример: `http://localhost:8000/shops/1/growth/telegram`)
## Тестовые данные
`php bin/console doctrine:fixtures:load` — создаёт 2-5 магазинов и 5-10 заказов на каждый (Faker, русская локализация)
## Запуск тестов
`php bin/phpunit`
## Режим отправки Telegram
По умолчанию **mock-режим** (логирование в stderr). Для реальной отправки в `.env` установите `TELEGRAM_MOCK=false`
## Допущения/упрощения
Идемпотентность: уникальная связь (shop_id, order_id). Ошибки Telegram не роняют заказ. Пароли/токены в .env. Frontend — React без сборки (CDN + Babel).
