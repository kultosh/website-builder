# Use official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files into container
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Create the storage symbolic link
RUN php artisan storage:link

# Set file permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Copy the ping service script into the container
COPY ping_service.sh /usr/local/bin/ping_service.sh
RUN chmod +x /usr/local/bin/ping_service.sh

# Expose port 8000
EXPOSE 8000

# Start Laravel and ping service in parallel
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=8000 & /usr/local/bin/ping_service.sh"]
