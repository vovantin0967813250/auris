FROM php:8.2-fpm

# Cài các package Laravel cần
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project vào container
WORKDIR /var/www
COPY . .

# Cài Laravel + khởi động
RUN composer install \
 && php artisan config:clear \
 && php artisan key:generate

EXPOSE 10000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
