# Reproducible local portfolio demo; never use seeded credentials in production.
FROM php:8.3-cli-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libonig-dev libzip-dev libxml2-dev libsqlite3-dev \
    && docker-php-ext-install mbstring pdo_sqlite pdo_mysql zip dom xml bcmath pcntl \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-interaction --prefer-dist --no-progress
COPY . .
RUN composer dump-autoload --optimize --no-interaction \
    && mkdir -p storage/app/sqlite storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && chmod +x docker/entrypoint.sh
EXPOSE 8000
ENTRYPOINT ["./docker/entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
