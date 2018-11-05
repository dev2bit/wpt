# Base -----------------------------------------------------------------

FROM php:7.2-fpm

# Instalación paquetes -------------------------------------------------
RUN apt-get update && apt-get -y install libmcrypt-dev libpng-dev libjpeg62-turbo-dev
RUN docker-php-ext-install gettext && docker-php-ext-install pdo_mysql && docker-php-ext-configure gd --with-jpeg-dir=/usr/include  && docker-php-ext-install gd 
RUN docker-php-ext-install pdo_mysql mysqli
