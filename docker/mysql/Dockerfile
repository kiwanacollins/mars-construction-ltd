FROM mysql:8.0

# Bake the seed dump into the image. Coolify rewrites relative bind mounts into
# host paths it creates empty (it never copies repo files into them), so the
# seed data has to travel inside the image instead of via a mounted directory.
COPY docker/mysql-init/ /docker-entrypoint-initdb.d/
