# Arquitetura

## Visão Geral

O sistema segue uma arquitetura baseada em **pacotes modulares** (package-based architecture), onde cada domínio de negócio é organizado em um pacote independente dentro de `app/Packages/`.

## Estrutura de Diretórios

```
app/
├── Base/                          # Código compartilhado
│   ├── Http/Controllers/         # Controllers base
│   ├── Repository/               # Repositories base
│   ├── Traits/                  # Traits reutilizáveis
│   └── Helpers/                  # Funções auxiliares
│
├── Packages/                      # Módulos do sistema
│   ├── Auth/                     # Autenticação
│   ├── Company/                  # Empresas
│   ├── Customer/                 # Clientes
│   ├── Employee/                 # Funcionários
│   ├── Person/                  # Pessoas
│   ├── Color/                    # Cores
│   └── Collection/               # Coleções
│
└── Providers/                    # Service Providers
```

## Arquitetura de Camadas

### Cada módulo segue o padrão:

```
Modules/{Nome}/
├── Controllers/     # Recebe requisições HTTP
├── Services/        # Lógica de negócio
├── Repositories/    # Acesso ao banco de dados
├── Models/          # Modelos Eloquent
├── DTOs/            # Data Transfer Objects
├── Requests/        # Validação de entrada
├── Resources/       # Formatação de saída
└── Enums/           # Enumerações
```

## Fluxo de uma Requisição

```
1. Requisição HTTP
       ↓
2. Route (routes/api.php)
       ↓
3. Controller (valida entrada)
       ↓
4. Service (lógica de negócio)
       ↓
5. Repository (acesso ao banco)
       ↓
6. Model (Eloquent ORM)
       ↓
7. Banco de Dados (PostgreSQL)
```

## Padrões Utilizados

### DTO (Data Transfer Object)

Transfere dados entre camadas de forma imutável:

```php
class CustomerStoreDTO
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $cpf = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(...);
    }
}
```

### Service Layer

Contém a lógica de negócio e transações:

```php
class CustomerService
{
    public function store(CustomerStoreDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            // lógica de negócio
        });
    }
}
```

### Repository Pattern

Abstrai o acesso ao banco de dados:

```php
class CustomerRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Customer::class);
    }
}
```

## Multi-Tenant

O sistema utiliza o conceito de multi-tenant, onde cada empresa (`Company`) possui seus próprios dados isolados.

### Como funciona:

1. Usuário faz login e recebe token
2. Token é cacheado no Redis com dados da empresa
3. A cada requisição, o `UserDataInCacheService` recupera a empresa do usuário
4. Queries são filtradas por `company_id`

```php
$user_data = app(UserDataInCacheService::class)->execute(getToken());
$companyId = (int) data_get($user_data, 'company.id');
```

## Injeção de Dependência

### Services

Use o helper `app()` para resolver Services:

```php
$data = app(CustomerService::class)->list();
```

### Repositories

Use injeção por construtor:

```php
class CustomerRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Customer::class);
    }
}
```

## Tratamento de Erros

Exceções são lançadas nos Services e tratadas no Controller via trait `Response`:

```php
// Service
if (!$customer) {
    throw new \Exception('Cliente não encontrado.', 404);
}

// Controller
try {
    $data = app(CustomerService::class)->show($id);
    return $this->successResponse($data);
} catch (Throwable $exception) {
    return $this->returnError($exception);
}
```

## Cache

O sistema utiliza Redis para cache de dados de sessão:

- `TokenInCacheService` - Armazena tokens
- `UserDataInCacheService` - Armazena dados do usuário logado

## Autenticação

Laravel Passport com Bearer tokens:

1. Login retorna access_token
2. Token enviado no header `Authorization: Bearer {token}`
3. Dados do usuário recuperáveis via `getToken()`
