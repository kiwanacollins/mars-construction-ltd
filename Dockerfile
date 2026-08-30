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
# docker/mysql-init is only needed by the mysql service's own volume mount (it's
# read from the host deploy directory, not from inside this image) and must not
# be reachable from the web root, so it's removed here rather than excluded via
# .dockerignore (Coolify applies .dockerignore to the whole deploy artifact,
# which would strip it before the mysql container could see it too).
RUN cp -r /var/www/html/Admin/uploads /var/www/html/Admin/uploads-seed \
    && chown -R www-data:www-data /var/www/html/Admin/uploads \
    && find /var/www/html/Admin/uploads -type d -exec chmod 775 {} \; \
    && find /var/www/html/Admin/uploads -type f -exec chmod 664 {} \; \
    && rm -rf /var/www/html/docker/mysql-init

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
