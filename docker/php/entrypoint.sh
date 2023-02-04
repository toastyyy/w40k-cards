#!/bin/sh

cd /var/www/symfony && composer install

CONTAINER_FIRST_STARTUP="/var/www/symfony/CONTAINER_FIRST_STARTUP"
if [ ! -e $CONTAINER_FIRST_STARTUP ]; then
    touch $CONTAINER_FIRST_STARTUP
    # place your script that you only want to run on first startup.
    #(echo "127.0.0.1   mysql-db" >> /etc/hosts)
    (cd /var/www/symfony && rm -r var/cache/*)
else
    # script that should run the rest of the times (instances where you
    # stop/restart containers).
    (cd /var/www/symfony && php bin/console cache:clear --env=prod)
    (cd /var/www/symfony && chmod -R 777 var/cache)
fi

service cron start
#Extra line added in the script to run all command line arguments
echo "starting php-fpm"
exec $@
