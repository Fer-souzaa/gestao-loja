# Módulo de Autenticação (Auth)

## Visão Geral

O módulo de **Autenticação** gerencia o login e tokens de acesso dos usuários. Utiliza Laravel Passport para autenticação OAuth2 com cache em Redis para otimização.

## Estrutura do Módulo

```
app/Packages/Auth/
├── Controllers/
│   └── AuthController.php
├── Services/
│   ├── LoginService.php
│   ├── GeneratePersonalAccessTokenService.php
│   ├── TokenInCacheService.php
│   └── UserDataInCacheService.php
├── Models/
│   ├── User.php
│   └── PersonalAccessToken.php
└── Requests/
    └── LoginRequest.php
```

## Endpoints

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/v1/auth/login` | Fazer login |

## Fluxo de Autenticação

```
1. Usuário envia credenciais
       ↓
2. AuthController::login() recebe request
       ↓
3. LoginService valida credenciais
       ↓
4. GeneratePersonalAccessTokenService cria token Passport
       ↓
5. TokenInCacheService salva token no Redis
       ↓
6. UserDataInCacheService salva dados do usuário no Redis
       ↓
7. Retorna access_token ao cliente
```

## Login Request

```php
class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
```

## Modelo (User)

```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
```

## Services

### LoginService

Valida credenciais e retorna dados do usuário:

```php
public function execute(string $email, string $password): array
{
    // Valida credenciais
    // Retorna dados do usuário
}
```

### GeneratePersonalAccessTokenService

Cria token Passport:

```php
public function execute(User $user): string
{
    return $user->createToken('api-token')->accessToken;
}
```

### TokenInCacheService

Armazena token no Redis:

```php
public function execute(string $token, array $userData): void
{
    // Salva token com TTL
}
```

### UserDataInCacheService

Recupera dados do usuário cacheados:

```php
public function execute(string $token): array
{
    // Busca dados no Redis
}
```

## Respostas da API

### Login com Sucesso

**Request:**
```json
{
    "email": "usuario@empresa.com",
    "password": "senha123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login realizado com sucesso!",
    "data": {
        "access_token": "eyJ0eXAiOiJKV1...",
        "token_type": "Bearer",
        "expires_in": 31536000
    }
}
```

### Login com Erro

```json
{
    "success": false,
    "message": "Credenciais inválidas.",
    "data": {
        "message_error": "Credenciais inválidas."
    }
}
```

## Header de Autenticação

Após login, envie o token no header:

```
Authorization: Bearer eyJ0eXAiOiJKV1...
```

## Recuperando Dados do Usuário

Em qualquer lugar da aplicação:

```php
// Recupera token atual
$token = getToken();

// Recupera dados cacheados do usuário
$user_data = app(UserDataInCacheService::class)->execute($token);

// Acessa informações
$companyId = data_get($user_data, 'company.id');
$userId = data_get($user_data, 'id');
$email = data_get($user_data, 'email');
```

## Estrutura dos Dados Cacheados

```json
{
    "id": 1,
    "name": "João Silva",
    "email": "joao@empresa.com",
    "company": {
        "id": 1,
        "name": "Minha Empresa LTDA"
    }
}
```

## Tempo de Expiração

| Item | TTL |
|------|-----|
| Token | 1 ano (31536000 segundos) |
| Dados do usuário | Conforme TTL do token |

## Boas Práticas

1. Sempre armazene o token com segurança no客户端
2. Não exponha tokens em logs
3. Use `getToken()` para recuperar o token atual
4. Filtre queries por `company_id` baseado nos dados cacheados
