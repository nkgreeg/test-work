FROM php:8.3-fpm-alpine
RUN apk add --no-cache \
    git unzip icu-dev libzip-dev libpng-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install intl pdo pdo_mysql zip gd opcache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-scripts --no-interaction
RUN chown -R www-data:www-data var
