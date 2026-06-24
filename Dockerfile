FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql zip

# Enable apache rewrite module for CodeIgniter routing
RUN a2enmod rewrite

# Enable AllowOverride All so .htaccess rewrite rules work
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy Composer binary from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application source from Multi-Hospital/ subdirectory
COPY Multi-Hospital/ /var/www/html/

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Fix file permissions only for writable directories (logs, cache, uploads)
RUN chown -R www-data:www-data /var/www/html/application/logs \
    && chown -R www-data:www-data /var/www/html/application/cache \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 775 /var/www/html/application/logs \
    && chmod -R 775 /var/www/html/application/cache \
    && chmod -R 775 /var/www/html/uploads


# ci_sessions table is auto-created by docker-entrypoint.sh on container start

# Cache-bust: force re-copy of entrypoint and SQL on every build
ARG CACHE_BUST=20260624_2

# Copy database SQL dump for first-run import
COPY Database/database_tables.sql /docker-entrypoint-initdb.d/database_tables.sql

# Copy entrypoint script that creates ci_sessions table before Apache starts
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set CodeIgniter environment to production
ENV CI_ENV=production

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
