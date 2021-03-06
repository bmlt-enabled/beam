FROM php:7.1
RUN apt-get update -y \
    && apt-get install -y openssl zip unzip git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install pdo mbstring pdo_mysql

WORKDIR /app
COPY . /app

RUN composer install \
    && chmod 775 storage/app \
    && chmod 775 storage/framework \
    && chmod 775 storage/logs \
    && chmod 775 bootstrap/cache

ENV PHP_INI_PATH "/usr/local/etc/php/php.ini"

RUN pecl install xdebug-2.7.2 && docker-php-ext-enable xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" >> ${PHP_INI_PATH} \
    && echo "xdebug.remote_port=9000" >> ${PHP_INI_PATH} \
    && echo "xdebug.remote_enable=1" >> ${PHP_INI_PATH} \
    && echo "xdebug.remote_connect_back=0" >> ${PHP_INI_PATH} \
    && echo "xdebug.remote_host=docker.for.mac.localhost" >> ${PHP_INI_PATH} \
    && echo "xdebug.idekey=IDEA_BEAM_DEBUG" >> ${PHP_INI_PATH} \
    && echo "xdebug.remote_autostart=1" >> ${PHP_INI_PATH} \
    && echo "xdebug.remote_log=/tmp/xdebug.log" >> ${PHP_INI_PATH}

CMD php artisan serve --host=0.0.0.0 --port=8101 --env=docker
EXPOSE 8101
