
FROM php:8.3-apache AS base
RUN docker-php-ext-install pdo_mysql
WORKDIR /var/www/html
EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD curl -fs http://localhost/health.php || exit 1


FROM base AS dev
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip \
 && rm -rf /var/lib/apt/lists/*
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"


FROM base AS prod
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --chown=www-data:www-data src/ /var/www/html/