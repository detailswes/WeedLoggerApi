FROM php:8.1.2-fpm-buster


RUN docker-php-ext-install bcmath pdo_mysql

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
RUN apt-get install -y git zip unzip
RUN curl -fsSL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ADD ./user.ini /usr/local/etc/php/conf.d/user.ini

# WORKDIR /var/www/
# COPY composer.* ./var/www/

# RUN composer install

EXPOSE 8000