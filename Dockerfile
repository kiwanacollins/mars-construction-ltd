FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev \
        unzip \
        default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/Admin/uploads \
    && find /var/www/html/Admin/uploads -type d -exec chmod 775 {} \; \
    && find /var/www/html/Admin/uploads -type f -exec chmod 664 {} \;

EXPOSE 80
