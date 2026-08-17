FROM dunglas/frankenphp:1.8-php8.4-alpine

RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    pgsql \
    redis \
    intl \
    bcmath \
    opcache \
    && { echo 'expose_php=Off'; echo 'display_errors=Off'; echo 'log_errors=On'; echo 'max_execution_time=0'; } > /usr/local/etc/php/conf.d/zz-security.ini

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-autoloader --no-scripts --no-progress

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative \
    && php artisan optimize:clear \
    && echo "APP_KEY=" > .env \
    && php artisan key:generate --force \
    && php artisan optimize:clear \
    && rm -rf /app/node_modules

ENV OCTANE_SERVER=frankenphp

EXPOSE 8000 2019

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000", "--admin-port=2019", "--workers=2", "--max-requests=500"]
