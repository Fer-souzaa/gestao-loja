# Módulo de Coleções (Collection)

## Visão Geral

O módulo de **Coleções** gerencia o cadastro de coleções de produtos. Permite CRUD completo de coleções com datas de início e fim.

## Estrutura do Módulo

```
app/Packages/Collection/
├── Controllers/
│   └── CollectionController.php
├── Services/
│   ├── StoreCollectionService.php
│   ├── ListCollectionsService.php
│   ├── UpdateCollectionService.php
│   └── DeleteCollectionService.php
├── Models/
│   └── Collection.php
├── DTO/
│   ├── StoreCollectionDTO.php
│   └── UpdateCollectionDTO.php
└── Requests/
    ├── StoreCollectionRequest.php
    └── UpdateCollectionRequest.php
```

## Endpoints

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/v1/collections` | Listar coleções |
| POST | `/api/v1/collections` | Cadastrar coleção |
| PUT | `/api/v1/collections/{id}` | Atualizar coleção |
| DELETE | `/api/v1/collections/{id}` | Remover coleção |

## DTOs

### StoreCollectionDTO

```php
class StoreCollectionDTO
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?string $start_date = null,
        public ?string $end_date = null,
    ) {}
}
```

### UpdateCollectionDTO

```php
class UpdateCollectionDTO
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?string $start_date = null,
        public ?string $end_date = null,
    ) {}
}
```

## Validações

### StoreCollectionRequest

| Campo | Regras | Mensagem |
|-------|--------|----------|
| name | required, string, max:100 | O campo nome é obrigatório |
| description | nullable, string | - |
| start_date | nullable, date | Data de início inválida |
| end_date | nullable, date | Data de fim inválida |

### UpdateCollectionRequest

| Campo | Regras | Mensagem |
|-------|--------|----------|
| name | required, string, max:100 | O campo nome é obrigatório |
| description | nullable, string | - |
| start_date | nullable, date | Data de início inválida |
| end_date | nullable, date | Data de fim inválida |

## Modelo (Collection)

```php
class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
```

## Respostas da API

### Listar Coleções

```json
{
    "success": true,
    "message": "Coleções listadas com sucesso!",
    "data": [
        {
            "id": 1,
            "name": "Verão 2024",
            "description": "Coleção de verão",
            "start_date": "2024-06-01",
            "end_date": "2024-08-31"
        }
    ]
}
```

### Cadastrar Coleção

**Request:**
```json
{
    "name": "Verão 2024",
    "description": "Coleção de verão",
    "start_date": "2024-06-01",
    "end_date": "2024-08-31"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Coleção cadastrada com sucesso!",
    "data": {
        "id": 1,
        "name": "Verão 2024",
        "description": "Coleção de verão",
        "start_date": "2024-06-01",
        "end_date": "2024-08-31"
    }
}
```

### Atualizar Coleção

**Request:**
```json
{
    "name": "Verão 2024 Atualizada",
    "description": "Coleção de verão modificada",
    "start_date": "2024-06-15",
    "end_date": "2024-09-15"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Coleção atualizada com sucesso!",
    "data": {
        "id": 1,
        "name": "Verão 2024 Atualizada",
        "description": "Coleção de verão modificada",
        "start_date": "2024-06-15",
        "end_date": "2024-09-15"
    }
}
```

### Remover Coleção

**Response (200):**
```json
{
    "success": true,
    "message": "Coleção removida com sucesso!",
    "data": []
}
```

## Regras de Negócio

1. **Nome único**: Cada coleção deve ter um nome único
2. **Soft delete**: Coleções não são excluídas permanentemente
3. **Datas opcionais**: `start_date` e `end_date` são opcionais
4. **Validação de datas**: Se ambas informadas, `end_date` deve ser >= `start_date`

## Estrutura da Tabela

```sql
CREATE TABLE social.collection (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

## Factory

```php
// Em testes
$collection = Collection::factory()->create([
    'name' => 'Verão 2024',
    'description' => 'Coleção de verão',
    'start_date' => '2024-06-01',
    'end_date' => '2024-08-31',
]);
```
