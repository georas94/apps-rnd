FROM php:8.2-fpm

# Étape 1: Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Étape 2: Configuration PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql zip intl opcache

# Étape 3: Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Étape 4: Configuration de l'application
WORKDIR /app

# Étape 5: Copie du code source
COPY . .

# Étape 8: Configuration finale
COPY docker/php-fpm/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]

EXPOSE 9000