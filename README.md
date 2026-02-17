# Gestão de Estoque

Sistema focado em gerenciar vendas e estoque de produtos de uma loja, construído com Laravel 12 e PHP 8.4.

## 🚀 Tecnologias

- **PHP 8.4**
- **Laravel 12**
- **PostgreSQL** (com suporte a PostGIS)
- **Redis**
- **Laravel Horizon** (Gestão de filas)
- **Docker & Docker Compose**
- **Pest** (Testes automatizados)

## 📋 Requisitos

- Docker e Docker Compose
- Make (opcional, mas recomendado para facilitar o uso dos comandos)

## 🛠️ Instalação e Configuração

O projeto utiliza um `Makefile` para automatizar os processos de configuração.

1.  **Clonar o repositório:**
    ```bash
    git clone <url-do-repositorio>
    cd gestao-estoque
    ```

2.  **Configurar o ambiente:**
    Copie o arquivo `.env.example` para `.env` e ajuste as variáveis se necessário (as configurações padrão funcionam com o Docker).
    ```bash
    cp .env.example .env
    ```

3.  **Instalação automática:**
    O comando abaixo realiza o build dos containers, instala dependências (Composer e NPM) e gera a chave da aplicação.
    ```bash
    make install
    ```

4.  **Subir os serviços:**
    ```bash
    make up
    ```

5.  **Executar as migrações:**
    ```bash
    make migrate
    ```

A aplicação estará disponível em: [http://localhost:8089](http://localhost:8089)

## ⚙️ Comandos Úteis (Makefile)

O projeto disponibiliza diversos comandos via `make` para facilitar o desenvolvimento:

- `make up`: Inicia os containers em modo background.
- `make down`: Para e remove os containers.
- `make restart`: Reinicia os containers e limpa o cache.
- `make shell-php`: Acessa o terminal do container PHP.
- `make migrate`: Executa as migrações do banco de dados.
- `make migrate-fresh`: Reinicia o banco de dados e executa todas as migrações.
- `make test`: Executa os testes automatizados (Pest/PHPUnit).
- `make logs`: Exibe os logs de todos os containers.
- `make clear`: Limpa todos os caches da aplicação (config, route, optimize).

## 📦 Estrutura do Projeto

O projeto segue uma arquitetura modularizada utilizando o diretório `app/Packages` para organizar as funcionalidades principais, além da estrutura padrão do Laravel.

- `app/Packages/Auth`: Módulo responsável pela autenticação.
- `database/migrations`: Definições da estrutura do banco de dados.
- `.docker`: Configurações de infraestrutura (Nginx, PHP, PostgreSQL, Redis).

## 🧪 Testes

Para rodar os testes da aplicação, utilize:
```bash
make test
```

## 📄 Licença

Este projeto é um software open-source sob a licença [MIT](https://opensource.org/licenses/MIT).
