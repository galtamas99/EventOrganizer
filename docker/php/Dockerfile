FROM php:8.3-fpm-alpine

RUN apk add --no-cache bash git oniguruma-dev libzip-dev libpq-dev icu-dev \
 && docker-php-ext-install pdo pdo_pgsql mbstring zip intl bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

CMD ["php-fpm"]
