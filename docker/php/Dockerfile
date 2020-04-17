FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y \
        git \
        nano \
        zlib1g-dev \
        zlibc \
        libzip-dev \
        libpq-dev \
        unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) \
#        mbstring \
        zip \
        opcache \
        sockets \
        pdo pdo_pgsql \
        iconv

RUN pecl install xdebug-2.9.4 \
    && docker-php-ext-enable xdebug

COPY docker/php/config/php.ini "$PHP_INI_DIR/php.ini"
COPY docker/php/config/xdebug.ini "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer global require hirak/prestissimo --no-plugins --no-scripts

WORKDIR /app

COPY composer.json composer.lock /app/
RUN composer install --no-interaction --no-progress --no-ansi --no-autoloader --no-scripts --prefer-dist

COPY . /app/

RUN composer dump-autoload --no-scripts --no-dev --optimize

