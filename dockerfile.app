FROM php:7.0.4-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    sqlite3 libsqlite3-dev libmagickwand-dev --no-install-recommends \
    && apt-get install -y libmemcached-dev memcached \
    && pecl install imagick \
    && pecl install xdebug \
    && printf "\n" | pecl install memcached \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt \
    && docker-php-ext-install sysvsem

COPY /php.ini /usr/local/etc/php/
