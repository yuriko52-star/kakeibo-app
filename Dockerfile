# ── Stage 1: Node build (compile Vite/Tailwind assets) ───────────────────────
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# ── Stage 2: Composer dependencies (production only) ─────────────────────────
FROM composer:2 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --ignore-platform-reqs

# ── Stage 3: Runtime image ────────────────────────────────────────────────────
FROM php:8.2-cli-alpine

# Install required PHP extensions for Laravel
RUN apk add --no-cache \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        oniguruma-dev \
        sqlite-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_sqlite \
        mbstring \
        zip \
        gd \
        bcmath \
        opcache

WORKDIR /app

# Copy application source
COPY . .

# Copy compiled Vite assets from node-builder
COPY --from=node-builder /app/public/build ./public/build

# Copy production vendor directory from composer-builder
COPY --from=composer-builder /app/vendor ./vendor

# Ensure storage and cache directories exist with correct permissions
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# $PORT is injected by Railway at runtime; fall back to 8080 for local runs
CMD php -S 0.0.0.0:${PORT:-8080} -t public
