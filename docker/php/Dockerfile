FROM php:7.4-fpm-buster

ARG TIMEZONE
ARG ENABLE_XDEBUG

RUN apt-get update && apt-get install -y apt-transport-https && apt-get -y install cron
RUN apt-get -y install supervisor && \
  mkdir -p /var/log/supervisor && \
  mkdir -p /etc/supervisor/conf.d

RUN apt-get install -y libicu-dev unzip zlib1g-dev libpng-dev libjpeg-dev openssl libc-client2007e-dev libkrb5-dev git zip libzip-dev libgmp-dev libfreetype6-dev libpng-dev libjpeg-dev libonig-dev libxslt1-dev

RUN docker-php-ext-install intl gd zip pdo pdo_mysql gmp
RUN docker-php-ext-install opcache

RUN docker-php-ext-configure gmp

RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl exif mbstring sockets bcmath

RUN pecl install apcu && docker-php-ext-enable apcu

RUN if [ ${ENABLE_XDEBUG} = 1 ]; \
    then echo "ENABLE XDEBUG"; \
    else echo "XDEBUG DISABLED"; \
    fi

RUN if [ ${ENABLE_XDEBUG} = 1 ]; \
        then pecl install xdebug-3.1.6 \
        && docker-php-ext-enable xdebug \
        && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && echo "xdebug.client_port=9007" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi;


COPY php.ini /usr/local/etc/php/php.ini


# Copy cronjobs file to the path
COPY ./crontab/cronjobs /etc/cron.d/cronjobs
# Apply permissions
RUN chmod 0644 /etc/cron.d/cronjobs
# Run crontab
RUN crontab /etc/cron.d/cronjobs
# Create log file where the result of running the cronjobs will be saved
RUN touch /var/log/cron.log
# Copy supervisord
COPY ./crontab/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-configure opcache --enable-opcache

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY entrypoint.sh /usr/local/entrypoint.sh
RUN chmod +x /usr/local/entrypoint.sh
#ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/local/entrypoint.sh","php-fpm","-F"]

WORKDIR /var/www/symfony
