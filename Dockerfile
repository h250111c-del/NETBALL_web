FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql \
    && sed -i 's/Listen 80/Listen @@PORT@@/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:@@PORT@@>/' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/

CMD ["sh", "-c", "sed -i \"s/@@PORT@@/${PORT:-10000}/g\" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"]
