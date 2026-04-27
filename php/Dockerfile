# ------------------------------
# Stage 1: Composer (Multi-stage)
# ------------------------------
FROM composer:2 AS composer-stage

# ------------------------------
# Stage 2: PHP-FPM production
# ------------------------------
FROM php:8.3-fpm-bullseye

# Sistem paketleri (minimum, production ready)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        zlib1g-dev \
        libxml2-dev \
        libonig-dev \
        pkg-config \
        unzip \
        git \
        curl \
        wget \
        gnupg \
        libicu-dev \
        supervisor \
    && rm -rf /var/lib/apt/lists/*

# PHP uzantıları
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        mysqli \
        gd \
        mbstring \
        xml \
        zip \
        bcmath \
        opcache \
        intl \
    && pecl install redis \
    && docker-php-ext-enable redis opcache

# Opcache optimum ayarlar (Production)
RUN { \
    echo "opcache.enable=0"; \
    echo "opcache.validate_timestamps=1"; \
    echo "opcache.revalidate_freq=0"; \
    echo "opcache.enable_cli=1"; \
    echo "opcache.memory_consumption=512"; \
    echo "opcache.interned_strings_buffer=32"; \
    echo "opcache.max_accelerated_files=20000"; \
    echo "opcache.save_comments=1"; \
    echo "opcache.jit=1205"; \
    echo "opcache.jit_buffer_size=256M"; \
} > /usr/local/etc/php/conf.d/opcache.ini

# PHP-FPM child process ayarları (yük altı, prod)
RUN { \
    echo "[www]"; \
    echo "pm = dynamic"; \
    echo "pm.max_children = 25"; \
    echo "pm.start_servers = 5"; \
    echo "pm.min_spare_servers = 5"; \
    echo "pm.max_spare_servers = 10"; \
    echo "pm.max_requests = 500"; \
    echo "request_terminate_timeout = 30s"; \
} > /usr/local/etc/php-fpm.d/www.conf

# Non-root kullanıcı
RUN useradd -ms /bin/bash appuser

# Çalışma dizini
WORKDIR /var/www/html

# www klasörüne doğru izinler
RUN chown -R appuser:appuser /var/www/html

# Composer multi-stage copy
COPY --from=composer-stage /usr/bin/composer /usr/local/bin/composer

# Supervisor hazır
RUN mkdir -p /etc/supervisor/conf.d

# Kullanıcı değiştir
USER appuser

# ENTRYPOINT olarak supervisor eklenebilir (isteğe bağlı)
# CMD ["php-fpm"]