FROM php:7.1-fpm

RUN apt-get update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-install -j$(nproc) intl pdo pdo_mysql
