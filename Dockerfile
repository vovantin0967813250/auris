# Base image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/laravel

# Copy source code vào Docker image
COPY . .

# Enable mod_rewrite
RUN a2enmod rewrite

# Cài PHP extensions và Composer
RUN apt-get update && \
    apt-get install -y unzip zip libzip-dev libonig-dev curl sqlite3 libsqlite3-dev && \
    docker-php-ext-install pdo pdo_mysql pdo_sqlite zip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader --no-interaction

# Tạo file SQLite nếu chưa có
RUN mkdir -p database && touch database/database.sqlite

# Gán quyền cho Laravel
RUN chown -R www-data:www-data storage bootstrap/cache database

# Cấu hình Apache trỏ đúng thư mục public
RUN sed -i 's|/var/www/html|/var/www/laravel/public|g' /etc/apache2/sites-available/000-default.conf

# Chạy artisan khi container khởi động
CMD php artisan config:clear && \
    php artisan key:generate && \
    php artisan migrate --force && \
    apache2-foreground


# Mở port 80
EXPOSE 80
