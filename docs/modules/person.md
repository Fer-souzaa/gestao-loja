# Módulo de Pessoas (Person)

## Visão Geral

O módulo de **Pessoas** é o cadastro base do sistema. Pessoas são utilizadas como base para clientes e funcionários, centralizando informações como nome, CPF e telefone.

## Estrutura do Módulo

```
app/Packages/Person/
├── Controllers/
│   └── PersonController.php
├── Services/
│   └── CreatePersonService.php
├── Repositories/
│   └── PersonRepository.php
├── Models/
│   └── Person.php
└── DTO/
    └── CreatePersonDTO.php
```

## Estrutura da Tabela

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

## DTOs

### CreatePersonDTO

```php
class CreatePersonDTO
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

## Modelo (Person)

```php
class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'social.person';

    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'registration_date',
    ];
}
```

## Relacionamentos

Uma pessoa pode ser:
- Nenhum ou um **Cliente** (relação 1:1 via Customer)
- Nenhum ou um **Funcionário** (relação 1:1 via Employee)

```
Person (1) ──── (1) Customer
Person (1) ──── (1) Employee
```

## Respostas da API

### Cadastrar Pessoa

**Request:**
```json
{
    "name": "João Silva",
    "phone": "(11) 99999-9999",
    "cpf": "123.456.789-00",
    "registration_date": "1990-01-15"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Pessoa cadastrada com sucesso!",
    "data": {
        "id": 1,
        "name": "João Silva",
        "phone": "(11) 99999-9999",
        "cpf": "123.456.789-00",
        "registration_date": "1990-01-15"
    }
}
```

### Listar Pessoas

```json
{
    "success": true,
    "message": "Pessoas listadas com sucesso!",
    "data": [
        {
            "id": 1,
            "name": "João Silva",
            "phone": "(11) 99999-9999",
            "cpf": "123.456.789-00"
        }
    ]
}
```

## Regras de Negócio

1. **CPF único**: O CPF deve ser único no sistema (quando informado)
2. **Soft delete**: Pessoas não são excluídas permanentemente
3. **Máscaras**: CPF e telefone podem ser armazenados com ou sem máscara

## Validações

| Campo | Regras |
|-------|--------|
| name | required, string, max:255 |
| phone | required, string, max:20 |
| cpf | nullable, string, max:14 |
| registration_date | nullable, date |

## Exemplos de Uso

### Criar Pessoa

```php
$data = app(CreatePersonService::class)->execute(
    new CreatePersonDTO(
        name: 'João Silva',
        phone: '(11) 99999-9999',
        cpf: '123.456.789-00',
    )
);
```

### Buscar por CPF

```php
$person = app(PersonRepository::class)->findByCpf($cpf);
```

## Factory

```php
// Em testes
$person = Person::factory()->create([
    'name' => 'João Silva',
    'cpf' => '123.456.789-00',
]);
```
