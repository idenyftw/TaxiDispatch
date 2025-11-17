FROM php:8.4-apache

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/www/html/app/logs && \
    chown -R www-data:www-data /var/www/html/app/logs && \
    chmod -R 775 /var/www/html/app/logs

# Installer les extensions PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli zip

# Activer mod_rewrite pour Apache
RUN a2enmod rewrite

# Installer Composer depuis l'image officielle 
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Vérifier l'installation de Composer
RUN composer --version || (echo "Erreur: Composer n'est pas installé correctement" && exit 1)

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier composer.json
COPY composer.json composer.lock* ./

# Installer les dépendances inclut slim 
RUN composer install --no-dev --optimize-autoloader
# Copier la configuration du vhost dans le container
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Activer le site et recharger Apache
RUN a2ensite 000-default.conf && \
    service apache2 restart

# Copier le reste de l'application
COPY . .

# Créer le répertoire swagger dans le conteneur
RUN mkdir -p /var/www/html/swagger

# Copier swagger.php
COPY swagger.php /var/www/html/swagger.php
