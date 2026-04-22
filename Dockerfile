FROM php:8.2-cli-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
        libzip-dev \
        libpng-dev \
        oniguruma-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        sqlite-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_sqlite \
        mbstring \
        zip \
        gd \
        bcmath \
        opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Copy the rest of the application
COPY . .

# Ensure storage and cache directories are writable
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=$PORT
