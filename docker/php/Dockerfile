FROM webdevops/php-apache-dev:7.4

RUN apt-get update
RUN apt-get install -y firebird-dev
RUN apt-get install -y libfontbox-java
RUN apt-get install -y fop

# RUN addgroup www-data xfs

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install dependencies
# RUN apk add --update --no-cache libfbclient libgds

# RUN docker-php-ext-install interbase
# RUN docker-php-ext-enable interbase
RUN docker-php-ext-install pdo pdo_firebird

# COPY php.ini /usr/local/etc/php

# RUN apk add --no-cache zip libzip-dev libpng-dev imagemagick
# RUN docker-php-ext-configure zip
# RUN docker-php-ext-install zip
# RUN docker-php-ext-install gd

WORKDIR /app
