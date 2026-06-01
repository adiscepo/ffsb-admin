FROM php:8.4-cli

COPY . /var/www/html
WORKDIR /var/www/html

RUN apt-get update -y && apt-get install -y libmcrypt-dev libonig-dev libzip-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring zip mysqli pdo_mysql

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=80
