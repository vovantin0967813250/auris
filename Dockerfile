# Base image PHP + Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/laravel

# Copy code vào Docker image
COPY . .

# Bật mod_rewrite của Apache
RUN a2enmod rewrite

# Cài PHP extension & Composer
RUN apt-get update && apt-get install -y unzip zip curl libzip-dev sqlite3 \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Tạo file SQLite nếu chưa có
RUN mkdir -p database && touch database/database.sqlite

# Phân quyền cho Laravel
RUN chown -R www-data:www-data storage bootstrap/cache database

# Sửa Apache root để dùng thư mục public
RUN sed -i 's|/var/www/html|/var/www/laravel/public|g' /etc/apache2/sites-available/000-default.conf

# Chạy Composer khi container start lần đầu (sẽ chạy sau khi có ENV & database)
CMD composer install --no-dev --optimize-autoloader --no-interaction \
    && php artisan config:clear \
    && php artisan key:generate \
    && apache2-foreground
