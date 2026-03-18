# Módulo de Funcionários (Employee)

## Visão Geral

O módulo de **Funcionários** gerencia os funcionários e vendedores da empresa. Permite listar funcionários e gerenciar vendedores (cadastro e demissão).

## Estrutura do Módulo

```
app/Packages/Employee/
├── Controllers/
│   └── EmployeeController.php
├── Services/
│   └── EmployeeService.php
├── Repositories/
│   └── EmployeeRepository.php
├── Models/
│   └── Employee.php
├── DTOs/
│   ├── SellerDTO.php
│   └── EmployeeListDTO.php
└── Requests/
    └── SellerStoreRequest.php
```

## Endpoints

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/v1/employees` | Listar funcionários da empresa |
| POST | `/api/v1/employees/sellers` | Cadastrar novo vendedor |
| DELETE | `/api/v1/employees/sellers/{id}` | Demitir vendedor |

## DTOs

### SellerDTO

```php
class SellerDTO
{
    public function __construct(
        public string $name,
        public string $phone,
        public string $email,
        public ?string $cpf = null,
        public ?string $function_slug = null,
    ) {}
}
```

### EmployeeListDTO

```php
class EmployeeListDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $phone,
        public ?string $cpf,
        public ?string $function_name,
        public int $company_id,
    ) {}
}
```

## Validações (SellerStoreRequest)

| Campo | Regras | Mensagem |
|-------|--------|----------|
| name | required, string, max:255 | O campo nome é obrigatório |
| phone | required, string, max:20 | O campo telefone é obrigatório |
| email | required, email | O campo email é obrigatório |
| cpf | nullable, string, max:14 | - |
| function_slug | nullable, string | - |

## Modelo (Employee)

```php
class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'social.employee';

    protected $fillable = [
        'person_id',
        'company_id',
        'function_id',
        'status',
    ];
}
```

## Funções Disponíveis (Enum)

As funções dos funcionários são gerenciadas pelo módulo `EmployeeFunction`:

| Slug | Nome |
|------|------|
| seller | Vendedor |
| manager | Gerente |
| admin | Administrador |

## Respostas da API

### Listar Funcionários

```json
{
    "success": true,
    "message": "Funcionários listados com sucesso!",
    "data": [
        {
            "id": 1,
            "name": "Maria Santos",
            "phone": "(11) 99999-9999",
            "cpf": "987.654.321-00",
            "function_name": "Vendedor",
            "status": "active"
        }
    ]
}
```

### Cadastrar Vendedor

**Request:**
```json
{
    "name": "Maria Santos",
    "phone": "(11) 99999-9999",
    "email": "maria@empresa.com",
    "cpf": "987.654.321-00",
    "function_slug": "seller"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Vendedor cadastrado com sucesso!",
    "data": {
        "id": 1,
        "name": "Maria Santos",
        "email": "maria@empresa.com"
    }
}
```

### Demitir Vendedor

**Response (200):**
```json
{
    "success": true,
    "message": "Vendedor removido com sucesso!",
    "data": []
}
```

## Regras de Negócio

1. **Multi-tenant**: Funcionários são filtrados pela empresa do usuário logado
2. **Status**: Funcionários podem ter status `active` ou `inactive`
3. **Pessoa + Employee**: Cada funcionário tem um registro em `Person` e em `Employee`
4. **Soft delete**: Funcionários não são excluídos permanentemente

## Exemplos de Uso

### Listar Funcionários

```php
$employees = app(EmployeeService::class)->list();
```

### Cadastrar Vendedor

```php
$data = app(EmployeeService::class)->storeSeller(
    SellerDTO::fromRequest($request->validated())
);
```

### Demitir Vendedor

```php
app(EmployeeService::class)->terminateSeller($id);
```
