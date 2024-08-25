# Utiliser l'image officielle PHP
FROM php:8.0-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances de l'application
RUN composer install --no-dev --optimize-autoloader

# Exposer le port 9000
EXPOSE 9000

# Commande pour démarrer le serveur
CMD ["php-fpm"]
