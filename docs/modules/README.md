# Módulos do Sistema

Este diretório contém a documentação de cada módulo do sistema.

## Lista de Módulos

### Gestão Principal

| Módulo | Descrição | Documentação |
|--------|-----------|--------------|
| [Customer](customer.md) | Gestão de clientes | clientes e pessoas associadas |
| [Employee](employee.md) | Gestão de funcionários | funcionários e vendedores |
| [Company](company.md) | Empresas | cadastro de empresas (multi-tenant) |
| [Person](person.md) | Pessoas | cadastro base de pessoas |

### Sistema

| Módulo | Descrição | Documentação |
|--------|-----------|--------------|
| [Auth](auth.md) | Autenticação | login e tokens |
| [Color](color.md) | Cores | cadastro de cores |
| [Collection](collection.md) | Coleções | cadastro de coleções |

## Estrutura de um Módulo

Cada módulo segue a mesma estrutura:

```
Modules/{Nome}/
├── Controllers/     # Recebe requisições HTTP
├── Services/        # Lógica de negócio
├── Repositories/    # Acesso ao banco
├── Models/          # Modelos Eloquent
├── DTOs/            # Data Transfer Objects
├── Requests/        # Validação de entrada
├── Resources/       # Formatação de saída
└── Enums/           # Enumerações (se aplicável)
```

## Padrão de Documentação

Cada módulo deve documentar:

1. **Visão Geral** - O que o módulo faz
2. **Endpoints** - Rotas da API
3. **DTOs** - Estrutura de dados de entrada
4. **Validações** - Regras de validação
5. **Respostas** - Formato de saída
6. **Exemplos** - Exemplos de requisição e resposta

## Módulos Relacionados

### Customer
- Relacionado com: Person, Company

### Employee
- Relacionado com: Person, Company, EmployeeFunction

### Person
- Base para: Customer, Employee

### Company
- Usado por: Customer, Employee

## Adicionando um Novo Módulo

Para documentar um novo módulo:

1. Crie um arquivo `docs/modules/{nome-do-modulo}.md`
2. Siga o template de documentação
3. Atualize este README com o novo módulo
