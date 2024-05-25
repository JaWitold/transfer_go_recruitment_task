FROM php:8.3.6-fpm AS build

RUN apt-get update && apt-get install -y git curl zip unzip libpq-dev libicu-dev libssl-dev libxslt-dev

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install pdo
RUN docker-php-ext-install pdo pdo_pgsql && docker-php-ext-enable pdo pdo_pgsql

# enable OPcache
RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
COPY docker/php/config/opcache.ini "${PHP_INI_DIR}/conf.d"

# install intl
RUN docker-php-ext-install intl && docker-php-ext-enable intl

# install xsl
RUN docker-php-ext-install xsl && docker-php-ext-enable xsl

# install redis
RUN pecl install redis && docker-php-ext-enable redis

# install xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY docker/php/config/xdebug.ini "${PHP_INI_DIR}/conf.d"


WORKDIR /var/www/symfony
