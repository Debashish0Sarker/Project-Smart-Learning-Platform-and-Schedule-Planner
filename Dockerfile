# Use Node.js 22 for Vite compatibility
FROM node:22-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
# Install with legacy peer deps to ignore version conflicts
RUN npm install --legacy-peer-deps
COPY . .
RUN npm run build

FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

# Install PHP extensions (simplified)
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    gd \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy built assets from node stage
COPY --from=node-builder /app/public/build /var/www/html/public/build

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