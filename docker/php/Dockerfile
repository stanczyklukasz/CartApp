FROM php:8.1.6-apache-buster

WORKDIR /var/www/gogcartapp/

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libicu-dev  \
    libxml2-dev \
    libxslt-dev \
    libpng-dev \
    zip \
    libzip-dev

RUN docker-php-ext-configure zip

RUN docker-php-ext-install \
    opcache \
    intl \
    mysqli \
    pdo \
    xml \
    soap \
    xsl \
    pdo_mysql \
    gd \
    zip

RUN docker-php-ext-enable  \
    pdo_mysql

RUN pecl install apcu-5.1.21 && docker-php-ext-enable apcu

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

COPY ./docker/php/vhost.conf /etc/apache2/sites-available/gogcartapp.conf

RUN a2ensite gogcartapp

COPY ./docker/php/custom-php.ini $PHP_INI_DIR/conf.d/

COPY . /var/www/gogcartapp

RUN composer install

RUN chmod -R 777 ./var