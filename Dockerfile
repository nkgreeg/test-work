FROM php:8.3-fpm-alpine
RUN apk add --no-cache nginx
RUN apk add --no-cache git unzip icu-dev libzip-dev libpng-dev libjpeg-turbo-dev postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql opcache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /www/data
COPY bin/ ./bin/
COPY config/ ./config/
COPY migrations/ ./migrations/
COPY public/ ./public/
COPY src/ ./src/
COPY templates/ ./templates/
COPY templates/ ./templates/
COPY composer.json composer.lock ./
COPY entrypoint.sh ./
COPY .env ./
RUN composer install --no-scripts --no-interaction
RUN mkdir -p var/cache var/log var/vendor
RUN chmod +x ./entrypoint.sh
ENTRYPOINT ["./entrypoint.sh"]
