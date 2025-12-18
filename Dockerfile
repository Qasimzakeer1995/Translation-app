FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev libzip-dev zip unzip git \
 && docker-php-ext-install pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose the port used by `php artisan serve` and run a helper startup sequence on container start
EXPOSE 8000

# If vendor is missing (bind mount overwrote it), install dependencies, ensure APP_KEY, link storage, fix perms, then serve
CMD ["sh","-lc","if [ ! -f vendor/autoload.php ]; then composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader; fi && php artisan key:generate --ansi --force || true && php artisan storage:link || true && chown -R www-data:www-data storage bootstrap/cache vendor || true && php artisan serve --host=0.0.0.0 --port=8000"]