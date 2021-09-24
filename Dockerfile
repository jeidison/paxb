FROM php:8.0.10-fpm-alpine3.13

# Workdir Default
WORKDIR /var/www

# Installing Dependencies
RUN apk add --no-cache \
  openssl \
  bash \
  unzip \
  vim \
  git \
  $PHPIZE_DEPS \
  icu-dev \
  libxml2-dev

# icu-dev - ext-intl
# libxml2-dev - ext-soap

# Change TimeZone
RUN apk add --update tzdata
ENV TZ=America/Sao_Paulo

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP extentions
RUN docker-php-ext-configure intl \
 && docker-php-ext-install intl \
 && docker-php-ext-install opcache \
 && docker-php-ext-enable opcache \
 && docker-php-ext-install soap

#PHP XDebug
RUN pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.idekey=paxb-php" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.client_host=192.168.0.106" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Group and User
RUN addgroup -g 1000 -S g-www \
 && adduser -u 1000 -D -S -G g-www u-www

# Default User
USER u-www

EXPOSE 9000
ENTRYPOINT ["php-fpm"]