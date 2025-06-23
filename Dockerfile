# Base image
FROM php:8.2-apache

# Working directory
WORKDIR /var/www/laravel

# Copy source code
COPY . .

# Enable mod_rewrite
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/laravel/storage /var/www/laravel/bootstrap/cache

# PHP extensions & Composer
RUN apt-get update && apt-get install -y unzip zip curl libzip-dev sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-interaction

# Apache to serve from Laravel public
RUN sed -i 's|/var/www/html|/var/www/laravel/public|g' /etc/apache2/sites-available/000-default.conf

# Laravel environment setup
RUN php artisan config:clear && php artisan key:generate

# Expose port
EXPOSE 80
