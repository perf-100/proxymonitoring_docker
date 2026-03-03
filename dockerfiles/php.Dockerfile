FROM php:8.3-fpm

WORKDIR /var/www/proxy

RUN docker-php-ext-install mysqli pdo pdo_mysql pcntl
RUN docker-php-ext-enable mysqli