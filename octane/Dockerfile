FROM php:8.4-cli-alpine

WORKDIR /var/www/html
ENV NODE_PATH=/usr/local/lib/node_modules

# Instala dependências e ferramentas (incluindo shadow para facilitar gestão de usuários se preferir,
# mas vamos usar os nativos do Alpine aqui)
RUN apk add --no-cache \
    bash \
    icu-dev \
    libzip-dev \
    zlib-dev \
    nodejs \
    npm

# Instala extensões PHP
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions swoole pdo pdo_pgsql zip intl pcntl

# Instala chokidar para o --watch
RUN npm install -g chokidar

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia o entrypoint para um local global
COPY octane/entrypoint.sh /usr/local/bin/entrypoint.sh

# Dá permissão de execução
RUN chmod +x /usr/local/bin/entrypoint.sh


# Argumentos para ID de usuário e grupo (passados pelo docker-compose)
ARG ID_USER_LINUX=1000
ARG ID_GROUP_LINUX=1000

# No Alpine, a sintaxe de criação de usuário é diferente:
RUN addgroup -g ${ID_GROUP_LINUX} -S www && \
    adduser -u ${ID_USER_LINUX} -D -S -G www www

# Copia os arquivos e já define o dono como o usuário 'www' que criamos
COPY --chown=www:www . .

# Ajusta permissões específicas do Laravel usando o usuário correto (www, não www-data)
RUN chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache

# Porta do Octane
EXPOSE 8000

# Opcional: Define que o container deve rodar como o usuário 'www'
USER www

# Define o Entrypoint
ENTRYPOINT ["entrypoint.sh"]

# O CMD agora é passado como argumento para o entrypoint
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000"]
