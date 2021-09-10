FROM php:8.0.6-fpm-alpine3.13

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

# Group and User
RUN addgroup -g 1000 -S g-www \
 && adduser -u 1000 -D -S -G g-www u-www

# Default User
USER u-www

EXPOSE 9000
ENTRYPOINT ["php-fpm"]