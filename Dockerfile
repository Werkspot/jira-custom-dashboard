FROM php:7.2-apache
# TODO: This can be split-up into a php-fpm image and nginx image later.

MAINTAINER Jeroen van den Heuvel <jeroen.van.den.heuvel>

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install \
        pdo_mysql

RUN rm /etc/apache2/sites-enabled/* \
    && ln -sf /proc/self/fd/1 /var/log/apache2/access.log \
    && ln -sf /proc/self/fd/1 /var/log/apache2/error.log

COPY apache-vhost.conf /etc/apache2/sites-enabled/

RUN mkdir /var/www/jiraboard

WORKDIR /var/www/jiraboard

COPY composer.* ./

RUN curl -o composer.phar https://getcomposer.org/composer.phar \
    && php composer.phar install --no-scripts \
    && rm composer.phar \
    && mkdir var \
    && chmod 0777 var

COPY .env.dist ./.env
COPY . ./
