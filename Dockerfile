FROM php:8.1-apache

# Install system dependencies + mysql-client (needed by entrypoint to init DB)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    default-mysql-client \
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

# Copy application source from Multi-Hospital/ subdirectory
COPY Multi-Hospital/ /var/www/html/

# Fix file permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 /var/www/html/application/logs \
    && chmod -R 775 /var/www/html/application/cache \
    && chmod -R 775 /var/www/html/uploads

# Copy and configure entrypoint (creates ci_sessions table, then starts Apache)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set CodeIgniter environment to production
ENV CI_ENV=production

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
