# Módulo de Cores (Color)

## Visão Geral

O módulo de **Cores** gerencia o cadastro de cores disponíveis para produtos. Permite CRUD completo de cores.

## Estrutura do Módulo

```
app/Packages/Color/
├── Controllers/
│   └── ColorController.php
├── Services/
│   ├── StoreColorService.php
│   ├── ListColorsService.php
│   ├── UpdateColorService.php
│   └── DeleteColorService.php
├── Models/
│   └── Color.php
├── DTO/
│   ├── StoreColorDTO.php
│   └── UpdateColorDTO.php
└── Requests/
    ├── StoreColorRequest.php
    └── UpdateColorRequest.php
```

## Endpoints

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/v1/colors` | Listar cores |
| POST | `/api/v1/colors` | Cadastrar cor |
| PUT | `/api/v1/colors/{id}` | Atualizar cor |
| DELETE | `/api/v1/colors/{id}` | Remover cor |

## DTOs

### StoreColorDTO

```php
class StoreColorDTO
{
    public function __construct(
        public string $name,
        public ?string $hex_code = null,
    ) {}
}
```

### UpdateColorDTO

```php
class UpdateColorDTO
{
    public function __construct(
        public string $name,
        public ?string $hex_code = null,
    ) {}
}
```

## Validações

### StoreColorRequest

| Campo | Regras | Mensagem |
|-------|--------|----------|
| name | required, string, max:50 | O campo nome é obrigatório |
| hex_code | nullable, string, max:7 | - |

### UpdateColorRequest

| Campo | Regras | Mensagem |
|-------|--------|----------|
| name | required, string, max:50 | O campo nome é obrigatório |
| hex_code | nullable, string, max:7 | - |

## Modelo (Color)

```php
class Color extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'hex_code',
    ];
}
```

## Respostas da API

### Listar Cores

```json
{
    "success": true,
    "message": "Cores listadas com sucesso!",
    "data": [
        {
            "id": 1,
            "name": "Vermelho",
            "hex_code": "#FF0000"
        },
        {
            "id": 2,
            "name": "Azul",
            "hex_code": "#0000FF"
        }
    ]
}
```

### Cadastrar Cor

**Request:**
```json
{
    "name": "Vermelho",
    "hex_code": "#FF0000"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Cor cadastrada com sucesso!",
    "data": {
        "id": 1,
        "name": "Vermelho",
        "hex_code": "#FF0000"
    }
}
```

### Atualizar Cor

**Request:**
```json
{
    "name": "Vermelho Escuro",
    "hex_code": "#CC0000"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Cor atualizada com sucesso!",
    "data": {
        "id": 1,
        "name": "Vermelho Escuro",
        "hex_code": "#CC0000"
    }
}
```

### Remover Cor

**Response (200):**
```json
{
    "success": true,
    "message": "Cor removida com sucesso!",
    "data": []
}
```

## Regras de Negócio

1. **Nome único**: Cada cor deve ter um nome único
2. **Soft delete**: Cores não são excluídas permanentemente
3. **Hex code opcional**: O código hexadecimal é opcional

## Estrutura da Tabela

```sql
CREATE TABLE social.color (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    hex_code VARCHAR(7),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

## Factory

```php
// Em testes
$color = Color::factory()->create([
    'name' => 'Vermelho',
    'hex_code' => '#FF0000',
]);
```
