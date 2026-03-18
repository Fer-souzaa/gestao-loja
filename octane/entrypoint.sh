#!/bin/sh
set -e

# Garante que o .env existe (copia do example se necessário)
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Gera a APP_KEY se ela estiver vazia
if ! grep -q "APP_KEY=base64" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# 1. Instala as dependências se estivermos em ambiente local
if [ "$APP_ENV" = "local" ]; then
    echo "Running composer install..."
    composer install --no-interaction --no-progress --optimize-autoloader
fi

# 2. Garante que as permissões de storage/cache estejam corretas
# (Útil se novos diretórios foram criados no host)
chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Executa o comando principal (o CMD do Dockerfile ou do Compose)
echo "Starting Octane..."
exec "$@"
