# --- PHP + Node (build único) ---
FROM php:8.4-cli

WORKDIR /var/www/html

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    locales-all \
    zip \
    unzip \
    git \
    curl \
    wget \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    libmagickwand-dev \
    libgmp-dev \
    zlib1g-dev \
    libicu-dev \
    g++ \
    cron \
    supervisor

# Extensões PHP
RUN docker-php-ext-install pdo_pgsql pgsql gd bcmath zip pcntl
RUN pecl install swoole && docker-php-ext-enable swoole

# PHP.ini
COPY ./docker/8.4/php.ini /usr/local/etc/php/conf.d/99-production.ini

# Supervisor
COPY ./docker/8.4/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia projeto
COPY . .

# Instala deps PHP
RUN composer install --no-dev --optimize-autoloader

# Instala deps Node
RUN npm install

# Builda assets (Wayfinder agora encontra o PHP 👍)
RUN npm run build

# Permissões
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
