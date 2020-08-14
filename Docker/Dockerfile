FROM php:7.4-cli
LABEL maintainer.name="Bernd Robertz"
LABEL maintainer.email="brobertz.net@gmail.com"
LABEL version="0.9.0"
LABEL description="Nebucord, a Discord bot with REST and WebSocket."
ENV DEBIAN_FRONTEND noninteractive

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get update && apt-get -y install libonig-dev libcurl4-openssl-dev libbz2-dev libzip-dev zlib1g-dev libpng-dev libxslt1-dev libmemcached-dev wget git unzip

RUN docker-php-ext-install mysqli mbstring gd bz2 xsl curl zip

RUN pecl install redis && docker-php-ext-enable redis;

RUN pecl install memcached && docker-php-ext-enable memcached

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '572cb359b56ad9ae52f9c23d29d4b19a040af10d6635642e646a7caa7b96de717ce683bd797a92ce99e5929cc51e7d5f') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN mv composer.phar /usr/bin/composer
RUN php -r "unlink('composer-setup.php');"

RUN mkdir -p /opt/nebucord && cd /opt/nebucord && composer require eurobertics/nebucord
RUN mkdir -p /var/nebucord && mkdir -p /var/nebucord_entry
VOLUME /var/nebucord
COPY nebucord_entrypoint.php /var/nebucord_entry
WORKDIR /var/nebucord

ENTRYPOINT ["php", "-f", "/var/nebucord_entry/nebucord_entrypoint.php"]
