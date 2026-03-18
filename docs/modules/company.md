# Módulo de Empresas (Company)

## Visão Geral

O módulo de **Empresas** gerencia as empresas do sistema. Cada empresa representa um tenant no sistema multi-tenant, permitindo isolamento de dados entre clientes.

## Estrutura do Módulo

```
app/Packages/Company/
├── Controllers/
│   └── CompanyController.php
├── Services/
│   └── CreateCompanyService.php
├── Models/
│   └── Company.php
└── DTO/
    └── CreateCompanyDTO.php
```

## Estrutura da Tabela

```sql
CREATE TABLE social.company (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

## DTOs

### CreateCompanyDTO

```php
class CreateCompanyDTO
{
    public function __construct(
        public string $name,
    ) {}
}
```

## Modelo (Company)

```php
class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'social.company';

    protected $fillable = [
        'name',
    ];
}
```

## Relacionamentos

Uma empresa pode ter:
- Nenhum ou muitos **Clientes**
- Nenhum ou muitos **Funcionários**

```
Company (1) ──── (N) Customer
Company (1) ──── (N) Employee
```

## Respostas da API

### Cadastrar Empresa

**Request:**
```json
{
    "name": "Minha Empresa LTDA"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Empresa cadastrada com sucesso!",
    "data": {
        "id": 1,
        "name": "Minha Empresa LTDA"
    }
}
```

### Listar Empresas

```json
{
    "success": true,
    "message": "Empresas listadas com sucesso!",
    "data": [
        {
            "id": 1,
            "name": "Minha Empresa LTDA"
        }
    ]
}
```

## Regras de Negócio

1. **Nome único**: Cada empresa deve ter um nome único
2. **Multi-tenant**: Empresas são tenants do sistema
3. **Soft delete**: Empresas não são excluídas permanentemente

## Como Funciona o Multi-Tenant

Quando um usuário faz login:

1. Sistema identifica a empresa do usuário
2. Dados da empresa são cacheados no Redis
3. Todas as queries são filtradas por `company_id`

```php
// Exemplo: Listar clientes de uma empresa
$user_data = app(UserDataInCacheService::class)->execute(getToken());
$companyId = (int) data_get($user_data, 'company.id');

$customers = app(CustomerRepository::class)->listByCompany($companyId);
```

## Factory

```php
// Em testes
$company = Company::factory()->create();
```
