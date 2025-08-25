FROM php:8.2-fpm

COPY ./php.ini /usr/local/etc/php/conf.d/php-custom.ini

RUN apt-get update && apt-get install -y zip unzip && docker-php-ext-install pdo pdo_mysql mysqli

WORKDIR /data

VOLUME /data

CMD ["php-fpm"]
