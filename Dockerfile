# Stage 1: Composer - Install PHP dependencies
FROM composer:2 as composer
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

# Stage 2: NPM - Build frontend assets
FROM node:18 as npm
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 3: Final Application Image
FROM php:8.2-fpm-alpine as app
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    zlib-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    pdo \
    pdo_mysql \
    exif \
    pcntl \
    intl

# Copy application files from previous stages
COPY --from=composer /app/vendor/ /var/www/html/vendor/
COPY --from=npm /app/public/ /var/www/html/public/
COPY . /var/www/html

# Set up permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Nginx
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# Configure Supervisor
COPY .docker/supervisord.conf /etc/supervisord.conf

# Expose port 80 for Nginx
EXPOSE 80

# Start Supervisor to manage Nginx and PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
