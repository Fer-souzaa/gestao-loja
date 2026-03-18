# Banco de Dados

## Visão Geral

O sistema utiliza **PostgreSQL** como banco de dados principal, com suporte à extensão **PostGIS** para dados geoespaciais.

## Schema `social`

Todas as tabelas da aplicação estão no schema `social`:

```sql
social.customer
social.person
social.company
```

## Convenções de Nomenclatura

### Tabelas

| Convenção | Exemplo |
|-----------|---------|
| snake_case | `customer`, `person` |
| Singular | `customer` (não `customers`) |

### Colunas

| Convenção | Exemplo |
|-----------|---------|
| snake_case | `billing_date`, `registration_date` |
| IDs com sufixo | `person_id`, `company_id` |
| Timestamps | `created_at`, `updated_at`, `deleted_at` |

### Foreign Keys

```
{nome_tabela}_id
```

Exemplos:
- `person_id` - Referência à tabela person
- `company_id` - Referência à tabela company

## Estrutura de Tabelas

### person

Pessoas são a base do sistema (clientes e funcionários).

```sql
CREATE TABLE social.person (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    cpf VARCHAR(14),
    phone VARCHAR(20),
    registration_date DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

### company

Empresas (tenant do multi-tenant).

```sql
CREATE TABLE social.company (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

### customer

Clientes vinculados à empresa.

```sql
CREATE TABLE social.customer (
    id SERIAL PRIMARY KEY,
    person_id INTEGER REFERENCES social.person(id),
    company_id INTEGER REFERENCES social.company(id),
    billing_date DATE,
    address TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

## Soft Deletes

Todas as tabelas possuem `deleted_at` para exclusão lógica:

```php
class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'social.customer';
}
```

Queries automáticas filtram registros deletados.

## Timestamps

| Coluna | Descrição |
|--------|-----------|
| created_at | Data de criação |
| updated_at | Data de última alteração |
| deleted_at | Data de exclusão (ou null) |

## Índices Recomendados

Para performance, adicione índices em colunas frequentemente consultadas:

```sql
-- Índice para busca por empresa
CREATE INDEX idx_customer_company_id ON social.customer(company_id);

-- Índice para busca por pessoa
CREATE INDEX idx_customer_person_id ON social.customer(person_id);

-- Índice para busca por CPF
CREATE INDEX idx_person_cpf ON social.person(cpf);
```

## Migrations

Migrations estão em `database/migrations/`. Para criar:

```bash
docker compose exec nexstore-php php artisan make:migration create_color_table --create=social.color
```

## Seeders

Seeders estão em `database/seeders/`. Para rodar:

```bash
docker compose exec nexstore-php php artisan db:seed
```

## Factories

Factories para testes em `database/factories/`:

```php
Customer::factory()->create([
    'company_id' => $company->id,
]);
```

## Conexão de Teste

O ambiente de testes usa `gestao_estoque_test`:

```xml
<!-- phpunit.xml -->
<env name="DB_DATABASE" value="gestao_estoque_test"/>
```

## Comandos Úteis

```bash
# Rodar migrations
make migrate

# Fresh migrate
make migrate-fresh

# Recriar banco de testes
make recreate-testing-database

# Recriar banco de produção
make recreate-database
```
