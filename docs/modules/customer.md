# Módulo de Clientes (Customer)

## Visão Geral

O módulo de **Clientes** gerencia os clientes da empresa. Cada cliente está vinculado a uma pessoa (`Person`) e a uma empresa (`Company`), permitindo que empresas vejam apenas seus próprios clientes.

## Estrutura do Módulo

```
app/Packages/Customer/
├── Controllers/
│   └── CustomerController.php
├── Services/
│   └── CustomerService.php
├── Repositories/
│   └── CustomerRepository.php
├── Models/
│   └── Customer.php
├── DTOs/
│   └── CustomerStoreDTO.php
├── Requests/
│   └── CustomerStoreRequest.php
├── Resources/
│   ├── CustomerResource.php
│   └── CustomerDetailResource.php
└── Tests/
    ├── CustomerListTest.php
    ├── CustomerStoreTest.php
    ├── CustomerShowTest.php
    ├── CustomerUpdateTest.php
    └── CustomerDestroyTest.php
```

## Endpoints

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/v1/customers` | Listar clientes da empresa |
| POST | `/api/v1/customers` | Cadastrar novo cliente |
| GET | `/api/v1/customers/{id}` | Ver detalhes de um cliente |
| PUT | `/api/v1/customers/{id}` | Atualizar cliente |
| DELETE | `/api/v1/customers/{id}` | Remover cliente |

## DTOs

### CustomerStoreDTO

```php
class CustomerStoreDTO
{
    public function __construct(
        public string $name,              // Nome da pessoa
        public string $phone,             // Telefone
        public ?string $cpf = null,       // CPF (opcional)
        public ?string $registration_date = null, // Data de registro
        public ?string $billing_date = null,       // Data de cobrança
        public ?string $address = null,   // Endereço
    ) {}
}
```

## Validações (CustomerStoreRequest)

| Campo | Regras | Mensagem |
|-------|--------|----------|
| name | required, string, max:255 | O campo nome é obrigatório |
| phone | required, string, max:20 | O campo telefone é obrigatório |
| cpf | nullable, string, max:14 | - |
| registration_date | nullable, date | Data de nascimento deve ser uma data válida |
| billing_date | nullable, date | Data de cobrança deve ser uma data válida |
| address | nullable, string | - |

## Modelo (Customer)

```php
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'social.customer';

    protected $fillable = [
        'person_id',
        'company_id',
        'billing_date',
        'address',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
```

## Respostas da API

### Listar Clientes

```json
{
    "success": true,
    "message": "Clientes listados com sucesso!",
    "data": [
        {
            "id": 1,
            "name": "João Silva",
            "cpf": "123.456.789-00",
            "phone": "(11) 99999-9999"
        }
    ]
}
```

### Detalhes do Cliente

```json
{
    "success": true,
    "message": "Dados do cliente recuperados com sucesso!",
    "data": {
        "id": 1,
        "name": "João Silva",
        "cpf": "123.456.789-00",
        "phone": "(11) 99999-9999",
        "registration_date": "1990-01-15",
        "billing_date": "2024-01-10",
        "address": "Rua Example, 123",
        "purchases": 0
    }
}
```

### Cadastrar Cliente

**Request:**
```json
{
    "name": "João Silva",
    "phone": "(11) 99999-9999",
    "cpf": "123.456.789-00",
    "registration_date": "1990-01-15",
    "billing_date": "2024-01-10",
    "address": "Rua Example, 123"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Cliente cadastrado com sucesso!",
    "data": {
        "id": 1,
        "person_id": 1,
        "name": "João Silva",
        "phone": "(11) 99999-9999",
        "cpf": "123.456.789-00",
        "billing_date": "2024-01-10",
        "address": "Rua Example, 123"
    }
}
```

## Regras de Negócio

1. **Multi-tenant**: Clientes são filtrados pela empresa do usuário logado
2. **Pessoa única**: Se o CPF já existe, não cria nova pessoa (reutiliza)
3. **Máscaras**: CPF e telefone são tratados com/removem máscaras
4. **Soft delete**: Clientes não são excluídos permanentemente

## Exemplos de Uso

### Cadastrar Cliente

```php
$data = app(CustomerService::class)->store(
    CustomerStoreDTO::fromRequest($request->validated())
);
```

### Listar Clientes

```php
$customers = app(CustomerService::class)->list();
```

### Buscar Cliente

```php
$customer = app(CustomerService::class)->show($id);
```

### Atualizar Cliente

```php
$customer = app(CustomerService::class)->update(
    $id,
    CustomerStoreDTO::fromRequest($request->validated())
);
```

### Remover Cliente

```php
app(CustomerService::class)->destroy($id);
```
