FROM php:8.2-fpm-alpine

USER :33
RUN apk update

RUN apk add --virtual build-dependencies build-base libzip-dev openssl-dev autoconf

RUN set -uex; \
    umask 0002; \
    curl --silent --show-error https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod o+x /usr/local/bin/composer

# Install PostgreSQL and PDO driver
RUN apk add --no-cache postgresql-dev && docker-php-ext-install pdo_pgsql

ENV COMPOSER_HOME=/tmp/composer

COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/php-cli.ini /usr/local/etc/php/php-cli.ini

WORKDIR /var/www/html

COPY ./ .

RUN composer install

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

RUN nohup php /var/www/html/bin/console app:start-websocket-server &

#ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
