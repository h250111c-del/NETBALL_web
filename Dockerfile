FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql \
    && sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/*:80>/*:10000>/' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . .

EXPOSE 10000
