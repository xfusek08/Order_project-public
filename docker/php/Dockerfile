FROM php:8.1-fpm-alpine

RUN addgroup www-data xfs

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql

# RUN apk add --no-cache zip libzip-dev libpng-dev imagemagick
# RUN docker-php-ext-configure zip
# RUN docker-php-ext-install zip
# RUN docker-php-ext-install gd

WORKDIR /app
