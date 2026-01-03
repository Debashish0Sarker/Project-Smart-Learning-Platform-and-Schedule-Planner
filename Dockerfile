# Use multi-stage build for PHP + Node
FROM node:18-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
# FIX: Use --legacy-peer-deps to ignore version conflicts
RUN npm install --legacy-peer-deps
COPY . .
RUN npm run build

FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

# Install PHP extensions and dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    zip \
    unzip \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy built assets from node stage
COPY --from=node-builder /app/public/build /var/www/html/public/build
COPY --from=node-builder /app/node_modules /var/www/html/node_modules

# Copy application code
COPY . /var/www/html/

# Install PHP dependencies (skip dev)
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copy supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]