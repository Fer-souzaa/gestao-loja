# API - Visão Geral

## Introdução

A API é uma REST API que fornece endpoints para gestão de clientes, funcionários e configurações de loja.

## Base URL

```
http://localhost/api
```

## Autenticação

Todos os endpoints (exceto `/auth/login`) requerem autenticação via Bearer token.

### Header

```
Authorization: Bearer {access_token}
```

### Obtendo Token

```bash
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "usuario@empresa.com", "password": "senha123"}'
```

## Formato de Requisição

Content-Type: `application/json`

### Request Body

```json
{
    "campo1": "valor1",
    "campo2": "valor2"
}
```

## Formato de Resposta

### Sucesso

```json
{
    "success": true,
    "message": "Mensagem de sucesso",
    "data": { }
}
```

### Erro

```json
{
    "success": false,
    "message": "Mensagem de erro",
    "data": {
        "message_error": "Detalhes do erro"
    }
}
```

### Erro de Validação

```json
{
    "success": false,
    "message": "Por favor verifique os campos preenchidos.",
    "data": {
        "errors": {
            "name": ["O campo nome é obrigatório."]
        }
    }
}
```

## Códigos de Status HTTP

| Código | Descrição |
|--------|-----------|
| 200 | Sucesso |
| 201 | Criado |
| 400 | Bad Request |
| 401 | Não autorizado |
| 404 | Não encontrado |
| 422 | Erro de validação |
| 500 | Erro interno |

## Endpoints Disponíveis

### Autenticação

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/v1/auth/login` | Fazer login |

### Clientes

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/v1/customers` | Listar clientes |
| POST | `/v1/customers` | Cadastrar cliente |
| GET | `/v1/customers/{id}` | Ver cliente |
| PUT | `/v1/customers/{id}` | Atualizar cliente |
| DELETE | `/v1/customers/{id}` | Remover cliente |

### Funcionários

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/v1/employees` | Listar funcionários |
| POST | `/v1/employees/sellers` | Cadastrar vendedor |
| DELETE | `/v1/employees/sellers/{id}` | Demitir vendedor |

### Cores

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/v1/colors` | Listar cores |
| POST | `/v1/colors` | Cadastrar cor |
| PUT | `/v1/colors/{id}` | Atualizar cor |
| DELETE | `/v1/colors/{id}` | Remover cor |

### Coleções

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/v1/collections` | Listar coleções |
| POST | `/v1/collections` | Cadastrar coleção |
| PUT | `/v1/collections/{id}` | Atualizar coleção |
| DELETE | `/v1/collections/{id}` | Remover coleção |

## Exemplo Completo

### Login

```bash
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@empresa.com", "password": "password"}'
```

**Resposta:**
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

### Listar Clientes (Autenticado)

```bash
curl -X GET http://localhost/api/v1/customers \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1..."
```

**Resposta:**
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

## Rate Limiting

Consulte a documentação do Laravel Passport para limites de requisições.

## Versionamento

A API está na versão **v1**. Prefixe todas as rotas com `/v1/`.
