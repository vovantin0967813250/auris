# Base image
FROM php:8.2-apache

# Set working directory to Laravel app
WORKDIR /var/www/laravel

# Copy source code
COPY . .

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache to serve the public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/laravel/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/>|<Directory /var/www/laravel/public>|g' /etc/apache2/apache2.conf

# Set correct permissions
RUN chown -R www-data:www-data /var/www/laravel/storage /var/www/laravel/bootstrap/cache

# Install dependencies
RUN apt-get update && apt-get install -y unzip zip curl libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies via Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Laravel environment setup
RUN php artisan config:clear && \
    php artisan key:generate || true  # Tránh lỗi nếu .env chưa sẵn sàng

# Expose Apache port
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
