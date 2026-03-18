# Índice da Documentação

## Comece Aqui

1. [Visão Geral](../docs/README.md) - Introdução ao projeto
2. [Arquitetura](arquitetura.md) - Como o sistema é estruturado

## Fundamentos

| Documento | Descrição |
|-----------|-----------|
| [Arquitetura](arquitetura.md) | Arquitetura do sistema e decisões técnicas |
| [Autenticação](autenticacao.md) | Como funciona o sistema de autenticação |
| [Banco de Dados](database.md) | Convenções e estrutura do banco de dados |

## Módulos

### Gestão Principal

| Módulo | Documentação | Descrição |
|---------|--------------|-----------|
| Clientes | [customer](modules/customer.md) | Gestão de clientes da empresa |
| Funcionários | [employee](modules/employee.md) | Gestão de funcionários e vendedores |
| Empresas | [company](modules/company.md) | Cadastro de empresas (multi-tenant) |
| Pessoas | [person](modules/person.md) | Cadastro base de pessoas |

### Sistema

| Módulo | Documentação | Descrição |
|---------|--------------|-----------|
| Autenticação | [auth](modules/auth.md) | Login e tokens de acesso |
| Cores | [color](modules/color.md) | Cadastro de cores de produtos |
| Coleções | [collection](modules/collection.md) | Cadastro de coleções de produtos |

## Referência da API

- [Visão Geral da API](api/README.md) - Endpoints disponíveis

---

## Convenções

### Nomenclatura de Arquivos

- Todos os arquivos em português (BR) para conteúdo
- Código em inglês
- Classes em PascalCase
- Métodos em camelCase
- Tabelas em snake_case

### Formato de Respostas

```json
{
    "success": true,
    "message": "Mensagem de sucesso",
    "data": { }
}
```

### Códigos de Status HTTP

| Código | Significado |
|--------|-------------|
| 200 | Sucesso |
| 201 | Criado |
| 400 | Bad Request |
| 401 | Não autorizado |
| 404 | Não encontrado |
| 422 | Erro de validação |
| 500 | Erro interno |
