FROM php:7.2-fpm
MAINTAINER info@dev2bit.com

RUN apt-get update && apt-get -y install \
  apt-file \
  libmcrypt-dev \
  libpng-dev \
  curl \
  git \
  subversion \
  openssl \
  mercurial \
  bash \
  nano \
  sudo \
  unzip \
  libjpeg62-turbo-dev

RUN apt-file update

RUN docker-php-ext-install gettext && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install mysqli && \
    docker-php-ext-configure gd --with-jpeg-dir=/usr/include && \
    docker-php-ext-install gd

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.5.2

RUN php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" \
 && php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
 && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION} \
 && composer --ansi --version --no-interaction \
 && rm -rf /tmp/* /tmp/.htaccess

RUN adduser --uid 500 user -q

RUN mkdir /run.d && chmod 755 -R /run.d

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
  && php wp-cli.phar --info \
  && chmod +x wp-cli.phar \
  && mv wp-cli.phar /usr/local/bin/wp

COPY sudoers /etc/sudoers

USER user

WORKDIR /var/www/html

CMD run-parts --regex=.* /run.d
