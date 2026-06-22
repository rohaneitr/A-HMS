FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql zip

# Enable apache rewrite module for CodeIgniter routing
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# =================================================================
# 🚨 এই নিচের অংশটুকু নতুন যোগ করা হয়েছে (৪MD Forbidden ফিক্স করার জন্য)
# =================================================================

# ১. গিটহাবের Multi-Hospital ফোল্ডারের ভেতরের সব ফাইল অ্যাপাচির রুটে কপি করুন
COPY Multi-Hospital/ /var/www/html/

# ২. অ্যাপাচি ওয়েব সার্ভারকে (www-data) ফাইলগুলো পড়ার ও মালিকানার অনুমতি দিন
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
