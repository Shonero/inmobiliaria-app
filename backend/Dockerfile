FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia todo el código primero (incluyendo artisan)
COPY . .

# Ahora instala dependencias composer (ya con artisan presente)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Da permisos a storage y cache
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
