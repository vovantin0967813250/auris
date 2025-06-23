# Base image
FROM php:8.2-apache

# Copy app
WORKDIR /var/www/html
COPY . .

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Laravel permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Composer install
RUN apt-get update && apt-get install -y unzip zip curl libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-interaction

# Environment setup
RUN php artisan config:clear && \
    php artisan key:generate

# Expose port 80 (Apache)
EXPOSE 80
