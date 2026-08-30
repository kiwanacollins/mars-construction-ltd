#!/bin/sh
set -e

UPLOADS_DIR="/var/www/html/Admin/uploads"
SEED_DIR="/var/www/html/Admin/uploads-seed"
MARKER="$UPLOADS_DIR/.seeded"

if [ -d "$SEED_DIR" ] && [ ! -f "$MARKER" ]; then
    echo "Seeding Admin/uploads volume from image contents..."
    cp -rn "$SEED_DIR"/. "$UPLOADS_DIR"/
    touch "$MARKER"
fi

chown -R www-data:www-data "$UPLOADS_DIR"

exec apache2-foreground
