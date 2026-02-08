FROM debian:13

ENV DEBIAN_FRONTEND=noninteractive

RUN apt update && apt install -y \
    php8.3-fpm \
    php8.3-cli \
    php8.3-mysql \
    php8.3-curl \
    php8.3-gd \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-zip \
    php8.3-bcmath \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

CMD ["/usr/sbin/php-fpm8.3", "-F"]
