# Use PHP base image
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www/html

# Copy composer and install
COPY composer.lock composer.json ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# Copy project files
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Laravel
CMD php artisan config:clear && \
    php artisan key:generate && \
    apache2-foreground
