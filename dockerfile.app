FROM php:7.0.4-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    sqlite3 libsqlite3-dev libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt
