# Utiliser PHP 7.3 avec Apache
FROM php:7.3-apache

# Installer les outils et extensions PHP nécessaires pour PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql pgsql

# [DIHAPUS] Instalasi mysqli dan pdo_mysql tidak lagi diperlukan
# RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier tout le projet dans le conteneur
COPY . /var/www/html/

# Modifier les permissions pour Apache
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour Apache
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]