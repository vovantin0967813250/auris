# Stage 1: Use Composer image to install dependencies
FROM composer:2 AS composer-stage

WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Stage 2: Apache + PHP
FROM php:8.2-apache

# Cài thư viện cần thiết
RUN apt-get update && apt-get install -y \
    unzip zip sqlite3 libzip-dev libonig-dev curl \
    && docker-php-ext-install pdo pdo_sqlite zip

# Enable mod_rewrite
RUN a2enmod rewrite

# Tạo file SQLite (nếu chưa có)
RUN mkdir -p /var/www/laravel/database && touch /var/www/laravel/database/database.sqlite

# Set working dir & copy code
WORKDIR /var/www/laravel
COPY --from=composer-stage /app /var/www/laravel

# Fix permission
RUN chown -R www-data:www-data storage bootstrap/cache database

# Trỏ Apache về public/
RUN sed -i 's|/var/www/html|/var/www/laravel/public|g' /etc/apache2/sites-available/000-default.conf

# Laravel artisan setup
RUN php artisan config:clear && php artisan key:generate

EXPOSE 80
CMD ["apache2-foreground"]
