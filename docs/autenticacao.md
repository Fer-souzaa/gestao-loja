# Autenticação

## Visão Geral

O sistema utiliza **Laravel Passport** para autenticação via tokens OAuth2, com cache em **Redis** para otimizar recuperação de dados do usuário.

## Fluxo de Autenticação

```
1. Usuário envia credenciais (email/senha)
       ↓
2. LoginService valida credenciais
       ↓
3. GeneratePersonalAccessTokenService cria token
       ↓
4. Token salvo no Redis (TokenInCacheService)
       ↓
5. Dados do usuário salvos no Redis (UserDataInCacheService)
       ↓
6. Retorna access_token ao cliente
```

## Endpoints

### Login

```
POST /api/v1/auth/login
```

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

## Header de Autenticação

Após o login, todas as requisições devem incluir o token:

```
Authorization: Bearer {access_token}
```

## Dados Cacheados no Redis

### Token Cache

```php
app(TokenInCacheService::class)->execute($token, $userData);
```

### Dados do Usuário

```php
$userData = [
    'id' => 1,
    'email' => 'usuario@empresa.com',
    'company' => [
        'id' => 1,
        'name' => 'Minha Empresa'
    ]
];
```

## Recuperando Dados do Usuário

Em qualquer ponto da aplicação:

```php
$user_data = app(UserDataInCacheService::class)->execute(getToken());
$companyId = (int) data_get($user_data, 'company.id');
```

## Estrutura dos Módulos

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

## Validação de Login

O `LoginRequest` valida:

| Campo | Regras |
|-------|--------|
| email | required, email |
| password | required, string |

## Códigos de Erro

| Código | Mensagem |
|--------|----------|
| 401 | Credenciais inválidas |
| 422 | Erro de validação |

## Refresh Token

Tokens Passport têm expiração configurada. Para refresh:

```
POST /oauth/token
```

## Boas Práticas

1. **Sempre** armazene o token com segurança no客户端
2. **Nunca** exponha tokens em logs
3. **Filtre** sempre queries por `company_id`
4. **Use** `getToken()` helper para recuperar token atual
