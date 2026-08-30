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

# Keep a pristine copy of the baked-in uploads so the entrypoint can seed an
# empty named volume on first boot without losing the images shipped in the repo.
RUN cp -r /var/www/html/Admin/uploads /var/www/html/Admin/uploads-seed \
    && chown -R www-data:www-data /var/www/html/Admin/uploads \
    && find /var/www/html/Admin/uploads -type d -exec chmod 775 {} \; \
    && find /var/www/html/Admin/uploads -type f -exec chmod 664 {} \;

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
