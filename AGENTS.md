# AGENTS.md - Guia do Codebase para Assistentes IA

## Visão Geral do Projeto

- **Framework**: Laravel 12 / PHP 8.2+
- **Arquitetura**: Baseada em pacotes em `app/Packages/` com design modular
- **Banco de Dados**: PostgreSQL
- **Idioma**: Português (BR) para mensagens ao usuário, Inglês para código

---

## Comandos Docker (via Makefile)

```bash
# Iniciar containers
make up

# Parar containers
make down

# Acessar shell do container PHP
make shell-php

# Visualizar logs
make logs
make logs-php
```

## Code Formatting (Laravel Pint - PSR-12)

```bash
# Formatar todos os arquivos (apenas prévia)
docker compose exec nexstore-php ./vendor/bin/pint --test

# Aplicar formatação
docker compose exec nexstore-php ./vendor/bin/pint

# Formatar arquivo específico
docker compose exec nexstore-php ./vendor/bin/pint app/Packages/Customer/Models/Customer.php

# Formatar diretório específico
docker compose exec nexstore-php ./vendor/bin/pint app/Packages/Customer/
```

## Outros Comandos de Desenvolvimento

```bash
# Instalar dependências
make install

# Migrations do banco de dados
make migrate

# Fresh migrate + seed
make migrate-fresh

# Limpar caches
make clear

# Gerar documentação OpenAPI
make openapi

# Instalar Passport (autenticação)
make passport-install
```

---

## Padrões e Convenções de Código

### Regras Gerais

1. **Sempre rode `pint` antes de commitar** - O projeto usa Laravel Pint para conformidade PSR-12
2. **Tipos estritos** - Todos os arquivos devem começar com `<?php`
3. **Declarações de tipo de retorno** - Sempre use tipos de retorno explícitos nos métodos
4. **Comentários PHPDoc** - Use para lógica complexa, parâmetros e valores de retorno

### Formatação PHP

```php
// Tag de abertura - sem espaço depois
<?php

// Declaração de namespace
namespace App\Packages\Customer\Controllers;

// Declarações use agrupadas por: nativos, packages, app
use App\Base\Http\Controllers\BaseController;
use App\Packages\Customer\DTOs\CustomerStoreDTO;
use Illuminate\Http\JsonResponse;
use Throwable;

// Chaves de classe na mesma linha
class CustomerController extends BaseController {

    // Indentação de 4 espaços
    // Tipos de retorno obrigatórios
    public function index(): JsonResponse {
        // ...
    }
}
```

### Convenções de Nomenclatura

| Elemento | Convenção | Exemplo |
|-----------|-----------|---------|
| Classes | PascalCase | `CustomerController`, `CustomerStoreDTO` |
| Métodos | camelCase | `store()`, `listByCompany()` |
| Variáveis | camelCase | `$customerData`, `$companyId` |
| Constantes | SCREAMING_SNAKE | `MAX_RETRY_COUNT` |
| Tabelas (BD) | snake_case | `social.customer`, `billing_date` |
| Rotas | kebab-case | `customers`, `store-seller` |

### Estrutura de Pacotes

Siga esta estrutura de diretórios sob `app/Packages/`:

```
app/Packages/{Modulo}/
├── Controllers/
├── DTOs/ ou DTO/
├── Models/
├── Repositories/
├── Requests/
├── Resources/
├── Services/
├── Enums/
└── ...
```

### Padrão DTO

Use DTOs imutáveis com promoção no construtor e factory `fromRequest()`:

```php
class CustomerStoreDTO
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $cpf = null,
        public ?string $registration_date = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            phone: $data['phone'],
            cpf: $data['cpf'] ?? null,
            registration_date: $data['registration_date'] ?? null,
        );
    }
}
```

### Padrão Service Layer

Services lidam com lógica de negócio e usam `DB::transaction()`:

```php
class CustomerService {
    public function store(CustomerStoreDTO $dto): array {
        return DB::transaction(function () use ($dto) {
            // lógica de negócio
        });
    }
}
```

### Padrão Repository

Extenda `BaseRepository` para operações CRUD comuns:

```php
class CustomerRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Customer::class);
    }
}
```

### Padrão Controller

Controllers extendem `BaseController` e usam try-catch com `returnError()`:

```php
class CustomerController extends BaseController {
    public function index(): JsonResponse {
        try {
            $data = app(CustomerService::class)->list();
            return $this->successResponse(
                CustomerResource::collection($data),
                'Clientes listados com sucesso!'
            );
        } catch (Throwable $exception) {
            return $this->returnError($exception);
        }
    }
}
```

### Tratamento de Erros

Use a trait `Response` para respostas consistentes da API:

```php
// Resposta de sucesso
return $this->successResponse($data, 'Mensagem aqui', 201);

// Erro - lance exceções, o controller trata
throw new \Exception('Cliente não encontrado.', 404);

// Resposta de validação customizada
return $this->customValidationResponse(
    success: false,
    message: 'Validação falhou',
    errors: $validator->errors()
);
```

### Validação com Form Request

```php
class CustomerStoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'O campo nome é obrigatório.',
        ];
    }
}
```

### Convenções de Model

```php
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'social.customer'; // Nome de tabela customizada se necessário

    protected $fillable = [
        'person_id',
        'company_id',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
```

### Nomenclatura de Rotas

Rotas usam prefixo `v1.` com nomes em kebab-case:

```php
Route::prefix('v1')->name('v1.')->group(function () {
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
});
```

---

## Convenções de Banco de Dados

- Use prefixo de schema `social.` para tabelas da aplicação
- Timestamps: `created_at`, `updated_at`, `deleted_at` (soft deletes)
- Foreign keys: sufixo `{tabela}_id` (ex: `person_id`, `company_id`)
- Datas: nomes em `snake_case` (ex: `billing_date`, `registration_date`)

---

## Formato de Resposta da API

Todas as respostas da API seguem esta estrutura:

```json
{
    "success": true,
    "message": "Clientes listados com sucesso!",
    "data": [...]
}
```

Respostas de erro:

```json
{
    "success": false,
    "message": "Cliente não encontrado.",
    "data": {
        "message_error": "Cliente não encontrado."
    }
}
```

---

## Injeção de Dependência

Prefira helper `app()` sobre injeção por construtor para Services:

```php
$data = app(CustomerService::class)->list();

// Para repositories, use injeção por construtor
class CustomerRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Customer::class);
    }
}
```

---

## Padrões Comuns

### Obtendo Empresa do Usuário Autenticado

```php
$user_data = app(UserDataInCacheService::class)->execute(getToken());
$companyId = (int) data_get($user_data, 'company.id');
```

### Transação com Tratamento de Erros

```php
return DB::transaction(function () use ($id, $dto) {
    $customer = app(CustomerRepository::class)->find($id);
    if (!$customer) {
        throw new \Exception('Cliente não encontrado.', 404);
    }
    // ... operações
    return $result;
});
```
