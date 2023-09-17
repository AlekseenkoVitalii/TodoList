#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
    rm -rf var/cache
    mkdir -p var/cache var/log
    chmod 777 var/cache var/log

    /usr/bin/crontab /etc/cron.d/cron
    /usr/sbin/crond
fi

exec docker-php-entrypoint "$@"
