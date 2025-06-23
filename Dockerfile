# Base image
FROM php:8.2-cli

# Create app directory
WORKDIR /app

# Install system deps + PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Copy composer + install deps
COPY composer.json composer.lock ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --prefer-dist --no-interaction

# Copy app files
COPY . .

# Laravel permissions
RUN chmod -R 775 storage bootstrap/cache

# Copy .env
RUN cp .env.example .env

# Generate app key
RUN php artisan config:clear && \
    php artisan key:generate

# Expose port
EXPOSE 10000

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
