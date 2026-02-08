FROM php:8.3-fpm

# Gerekli sistem paketleri
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    libxml2-dev \
    libonig-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# PHP uzantıları
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql mysqli gd mbstring xml zip bcmath

WORKDIR /var/www/html
