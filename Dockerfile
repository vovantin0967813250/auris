FROM php:8.2-apache

# Cài extension cần thiết
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy toàn bộ mã nguồn Laravel
COPY . /var/www/html

# Phân quyền thư mục
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Bật mod_rewrite để Laravel hoạt động đúng route
RUN a2enmod rewrite
