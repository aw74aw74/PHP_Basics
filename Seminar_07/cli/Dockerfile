FROM php:8.2-cli

COPY ./php.ini /usr/local/etc/php/conf.d/php-custom.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apt-get update && apt-get install -y zip unzip && docker-php-ext-install pdo pdo_mysql mysqli

WORKDIR /data/mysite.local

VOLUME /data/mysite.local
