FROM php:8.1-cli
LABEL maintainer.name="Bernd Robertz"
LABEL maintainer.email="brobertz.net@gmail.com"
LABEL version="1.2.1"
LABEL description="Nebucord, a Discord bot with REST and WebSocket."
ENV DEBIAN_FRONTEND noninteractive

RUN groupadd -f nebucord && RUN useradd -M -g nebucord nebucord

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get update && apt-get -y install libonig-dev libcurl4-openssl-dev libbz2-dev libzip-dev zlib1g-dev libpng-dev libxslt1-dev libmemcached-dev wget git unzip && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mbstring gd bz2 xsl curl zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN mv composer.phar /usr/bin/composer
RUN php -r "unlink('composer-setup.php');"

RUN mkdir -p /opt/nebucord && cd /opt/nebucord && composer require eurobertics/nebucord
RUN mkdir -p /var/nebucord && mkdir -p /var/nebucord_entry
VOLUME /var/nebucord
COPY Docker/nebucord_entrypoint.php /var/nebucord_entry
WORKDIR /var/nebucord

USER nebucord
ENTRYPOINT ["php", "-f", "/var/nebucord_entry/nebucord_entrypoint.php"]
