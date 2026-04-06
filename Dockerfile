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
RUN mkdir -p var/cache var/log var/vendor /www/data/var
RUN chmod +x ./entrypoint.sh
RUN chmod -R 777 /www/data/var
RUN chmod -R 777 /tmp
RUN sed -i 's/user = www-data/user = root/g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/group = www-data/group = root/g' /usr/local/etc/php-fpm.d/www.conf
ENTRYPOINT ["./entrypoint.sh"]
