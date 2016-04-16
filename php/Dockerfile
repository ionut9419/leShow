FROM php:5.6-fpm
# Install modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        libicu-dev \
        libxml2-dev \
        g++\
        cron \
    && docker-php-ext-install iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install intl \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install soap

RUN curl -L -o /tmp/xdebug.tar.gz https://github.com/xdebug/xdebug/archive/xdebug_2_3.tar.gz \
    && tar xfz /tmp/xdebug.tar.gz \
    && rm -r /tmp/xdebug.tar.gz \
    && mv xdebug-xdebug_2_3 /usr/src/php/ext/xdebug \
    && docker-php-ext-install xdebug
