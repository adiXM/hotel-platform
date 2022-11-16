FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    zlib1g-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    libzip-dev \
    zip \
    acl  \
    && docker-php-ext-install pdo_mysql zip xsl gd intl opcache exif mbstring

# Alias
RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

WORKDIR /var/www/symfony

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


